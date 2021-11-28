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
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path.'/'.$directory;

                if (!is_dir(DIR_IMAGE.$path)) {
                    $this->filesystem->mkdir(DIR_IMAGE.$path);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE.$old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE.$old_image);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE.$new_image);
            } else {
                copy(DIR_IMAGE.$old_image, DIR_IMAGE.$new_image);
            }
        }

        if ($this->request->server['HTTPS']) {
            return $this->config->get('config_ssl').'image/'.$new_image;
        } else {
            return $this->config->get('config_url').'image/'.$new_image;
        }
    }

    public function getCities()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city` order by sort_order')->rows;
    }

    public function getVendor($vendor_id)
    {
        return $this->db->query('select CONCAT(firstname," ",lastname) as vendor_name from `'.DB_PREFIX.'user` WHERE user_id="'.$vendor_id.'"')->row;
    }

    public function getStore($store_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'store` where status=1 and store_id="'.$store_id.'"')->row;
    }

    public function getStoreOpenHours($store_id, $day)
    {
        $this->db->where('day', $day);
        $this->db->select('timeslot', false);
        $this->db->where('status', '1');
        $this->db->where('store_id', $store_id);
        $row = $this->db->get('store_open_hours')->rows;

        return $row;
    }

    public function getStoreData($store_id)
    {
        return $this->db->query('select name,number_of_days_front,store_id,delivery_time_diff from `'.DB_PREFIX.'store` WHERE store_id = "'.$store_id.'"')->row;
    }

    public function getTestimonial()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'testimonial` WHERE status = "1"')->rows;
    }

    public function getAllOffers()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'offer`')->rows;
    }

    public function getStoreZipCodes($config_store_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'store_zipcodes` where store_id = "'.$config_store_id.'"')->rows;
    }

    public function getBlocks($data = [])
    {
        if ($data) {
            $sql = 'SELECT * FROM '.DB_PREFIX."blocks WHERE language_id = '".(int) $this->config->get('config_language_id')."'";

            $sql .= ' order by sort_order ASC';

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $query = $this->db->query('SELECT * FROM '.DB_PREFIX."blocks WHERE language_id = '".(int) $this->config->get('config_language_id')."' order by sort_order ASC");

            $order_status_data = $query->rows;

            return $order_status_data;
        }
    }
}
