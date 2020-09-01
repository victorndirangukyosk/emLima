<?php

class ModelToolImage extends Model
{
    public function resize($filename, $width, $height)
    {
        if (!is_file(DIR_IMAGE.$filename)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $old_image = $filename;
        $new_image = 'cache/'.utf8_substr($filename, 0, utf8_strrpos($filename, '.')).'-'.$width.'x'.$height.'.'.$extension;

        if (!is_file(DIR_IMAGE.$new_image) || (filectime(DIR_IMAGE.$old_image) > filectime(DIR_IMAGE.$new_image))) {
            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE.$old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $path = '';

                $directories = explode('/', dirname(str_replace('../', '', $new_image)));

                foreach ($directories as $directory) {
                    $path = $path.'/'.$directory;

                    if (!is_dir(DIR_IMAGE.$path)) {
                        $this->filesystem->mkdir(DIR_IMAGE.$path);
                    }
                }

                $image = new Image(DIR_IMAGE.$old_image);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE.$new_image);
            } else {
                $this->filesystem->copy(DIR_IMAGE.$old_image, DIR_IMAGE.$new_image);
            }
        }

        if ($this->request->server['HTTPS']) {
            return HTTPS_CATALOG.'image/'.$new_image;
        } else {
            return HTTP_CATALOG.'image/'.$new_image;
        }
    }

    public function getImage($filename)
    {
        if (!is_file(DIR_IMAGE.$filename)) {
            return;
        }

        if ($this->request->server['HTTPS']) {
            return HTTPS_CATALOG.'image/'.$filename;
        } else {
            return HTTP_CATALOG.'image/'.$filename;
        }
    }

    public function getCities()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city` order by sort_order')->rows;
    }

    public function getVendorDetails($vendor_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'user` WHERE user_id="'.$vendor_id.'"')->row;
    }

    public function getVendor($vendor_id)
    {
        return $this->db->query('select CONCAT(firstname," ",lastname) as vendor_name from `'.DB_PREFIX.'user` WHERE user_id="'.$vendor_id.'"')->row;
    }

    public function getStore($store_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'store` where status=1 and store_id="'.$store_id.'"')->row;
    }

    public function getTaxTotal($data, $store_id)
    {
        $log = new Log('error.log');
        $log->write('getApiTotal taxes');
        $log->write($data);
        $log->write($store_id);

        //$taxes = $this->cart->getTaxes();die;

        $taxes = $this->getTaxesByApiForSaving($data, $store_id); //getTaxesStoreWise

        $log->write('taxes');
        $log->write($taxes);

        $total_data = [];
        foreach ($taxes as $key => $value) {
            if ($value > 0) {
                $total_data[] = [
                    'code' => 'tax',
                    'title' => $this->tax->getRateName($key),
                    'value' => round($value, 2),
                    'sort_order' => $this->config->get('tax_sort_order')
                ];
            }
        }

        return $total_data;
    }

    public function getTaxesByApiForSaving($args, $store_id)
    {
        $tax_data = [];

        $log = new Log('error.log');
        $log->write('getTaxesByApiForSaving');
        $log->write($args);

        foreach ($args['products'] as $product) {
            $product['product_store_id'] = $product['product_id'];

            if ($store_id) {
                $row = $this->db->query('select city_id from '.DB_PREFIX.'store WHERE store_id="'.$store_id.'"')->row;
                if ($row) {
                    $this->tax->setShippingAddress($row['city_id']);
                    $this->tax->setCity($row['city_id']);
                }
            }

            $this->db->select('product_to_store.*,product.*,product_description.*', false);
            $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');
            $this->db->group_by('product_to_store.product_store_id');
            $this->db->where('product_to_store.product_store_id', $product['product_store_id']);
            $productData = $this->db->get('product_to_store')->row;

            if (isset($productData['special_price']) && (int) $productData['special_price']) {
                $productData['price'] = $productData['special_price'];
            }

            //$log->write("end 2");
            if ((int) $productData['tax_class_id'] && $productData['store_id'] == $store_id) {
                $tax_rates = $this->tax->getRates($productData['price'], $productData['tax_class_id']);

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                        $tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
                    } else {
                        $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
                    }
                }
            }
        }

        $log->write('end');

        return $tax_data;
    }

    public function getStoreData($store_id)
    {
        return $this->db->query('select name,store_id,delivery_time_diff from `'.DB_PREFIX.'store` WHERE store_id = "'.$store_id.'"')->row;
    }

    public function getProductStoreId($product_id, $store_id)
    {
        $query = $this->db->query('SELECT * from  '.DB_PREFIX.'product_to_store where store_id = '.(int) $store_id.' and product_id = '.$product_id);

        return $query->row;
    }
}
