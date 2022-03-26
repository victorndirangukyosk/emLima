<?php

class ModelSaleOrder extends Model {

    public function getShopper($shopper_id) {
        $query = $this->db->query('select CONCAT(firstname," ",lastname) as name from `' . DB_PREFIX . '` WHERE user_id="' . $shopper_id . '"');

        if ($query->num_rows) {
            return $query->row['name'];
        }
    }

    public function getTopCategories() {
        $sql = 'select d.name, c.category_id from oc_category c inner join ' . DB_PREFIX . 'category_description d on d.category_id = c.category_id WHERE c.parent_id=0 AND d.language_id="' . $this->config->get('config_language_id') . '"';
        $rows = $this->db->query($sql)->rows;

        $result = [];
        foreach ($rows as $row) {
            $result[$row['category_id']] = $row['name'];
        }

        return $result;
    }

    public function getProductDataByStoreFilter($filter_name, $store_id) {
        //$store_id = (int)$this->session->data['config_store_id'];
        $language_id = (int) $this->config->get('config_language_id');

        $limit = 18;
        $offset = 0;

        $this->db->select('product_description.*,product_to_store.*,product.unit,product.model', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        /* if ( $data['start'] < 0 ) {
          $data['start'] = 0;
          $offset = $data['start'];
          }else{
          $offset = $data['start'];
          }
          if ( $data['limit'] < 1 ) {
          $data['limit'] = 20;
          $limit = $data['limit'];

          }else{
          $limit = $data['limit'];
          }
          $sort_data = array(
          'product_description.name',
          'product.model',
          'product_to_store.quantity',
          'product_to_store.price',
          'product.sort_order',
          'product.date_added'
          );
          if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
          if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
          $this->db->order_by($data['sort'], 'asc');
          }else {
          $this->db->order_by($data['sort'], 'asc');
          }
          } else {
          $this->db->order_by('product.sort_order', 'asc');
          } */
        $this->db->group_by('product_to_store.product_store_id');
        $this->db->where('product_to_store.store_id', $store_id);
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $language_id);
        $this->db->where('product.status', 1);
        $ret = $this->db->get('product_to_store', $limit)->rows;
        //$ret = $this->db->get('product_to_store')->rows;
        // echo $this->db->last_query();die;
        return $ret;
    }

    public function getProductDataByStoreFilterNew($filter_name) {

        $this->db->select('product_to_store.*,product_to_category.category_id,product.*,product_description.*,product_description.name as pd_name', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        $limit = 18;
        $offset = 0;

        $sort_data = [
            'product_description.name',
            'product.model',
            'product_to_store.quantity',
            'product_to_store.price',
            'product.sort_order',
            'product.date_added',
        ];

        $this->db->group_by('product_description.name');
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
        $ret = $this->db->get('product_to_store')->rows;
        return $ret;
    }

    public function getProductDataByStoreFilterFarmer($filter_name, $store_id) {
        //$store_id = (int)$this->session->data['config_store_id'];
        $language_id = (int) $this->config->get('config_language_id');

        $limit = 5;
        $offset = 0;

        $this->db->select('product_description.*,product_to_store.*,product.unit,product.model', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        /* if ( $data['start'] < 0 ) {
          $data['start'] = 0;
          $offset = $data['start'];
          }else{
          $offset = $data['start'];
          }
          if ( $data['limit'] < 1 ) {
          $data['limit'] = 20;
          $limit = $data['limit'];

          }else{
          $limit = $data['limit'];
          }
          $sort_data = array(
          'product_description.name',
          'product.model',
          'product_to_store.quantity',
          'product_to_store.price',
          'product.sort_order',
          'product.date_added'
          );
          if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
          if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
          $this->db->order_by($data['sort'], 'asc');
          }else {
          $this->db->order_by($data['sort'], 'asc');
          }
          } else {
          $this->db->order_by('product.sort_order', 'asc');
          } */
        $this->db->group_by('product_to_store.product_store_id');
        $this->db->where('product_to_store.store_id', $store_id);
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $language_id);
        $this->db->where('product.status', 1);
        $ret = $this->db->get('product_to_store', $limit)->rows;
        //$ret = $this->db->get('product_to_store')->rows;
        // echo $this->db->last_query();die;
        return $ret;
    }

    public function getCategoryPriceStatusByCategoryName($category_name, $status) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE price_category ='" . $category_name . "' AND status ='" . $status . "'");
        return $query->rows;
    }

    public function getCategoryPrices($product_store_id, $store_id, $price_category) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE price_category ='" . $price_category . "' AND product_store_id ='" . $product_store_id . "' AND store_id ='" . $store_id . "'");
        return $query->row;
    }

    public function getProductsForEditInvoice($filter_name, $store_id, $order_id) {

        $this->load->model('sale/order');
        $this->load->model('account/customer');
        $order_info = $this->model_sale_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        /* IF CUSTOMER SUB CUSTOMER */
        $parent_customer_info = NULL;
        if (isset($customer_info) && $customer_info['parent'] > 0) {
            $parent_customer_info = $this->model_account_customer->getCustomer($customer_info['parent']);
        }

        $disabled_products_string = NULL;
        if ($parent_customer_info == NULL && isset($customer_info['customer_category']) && $customer_info['customer_category'] != NULL) {
            $category_pricing_disabled_products = $this->getCategoryPriceStatusByCategoryName($customer_info['customer_category'], 0);
            //$log = new Log('error.log');
            //$log->write('category_pricing_disabled_products');
            $disabled_products = array_column($category_pricing_disabled_products, 'product_store_id');
            $disabled_products_string = implode(',', $disabled_products);
            //$log->write($disabled_products_string);
            //$log->write('category_pricing_disabled_products');
        } elseif ($parent_customer_info != NULL && isset($parent_customer_info) && isset($parent_customer_info['customer_category']) && $parent_customer_info['customer_category'] != NULL) {
            $category_pricing_disabled_products = $this->getCategoryPriceStatusByCategoryName($parent_customer_info['customer_category'], 0);
            //$log = new Log('error.log');
            //$log->write('category_pricing_disabled_products');
            $disabled_products = array_column($category_pricing_disabled_products, 'product_store_id');
            $disabled_products_string = implode(',', $disabled_products);
            //$log->write($disabled_products_string);
            //$log->write('category_pricing_disabled_products');  
        }

        $store_id = $store_id;

        $this->db->select('product_to_store.*,product_to_category.category_id,product.*,product_description.*,product_description.name as pd_name', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        if ($disabled_products_string != NULL) {
            $this->db->where_not_in('product_to_store.product_store_id', $disabled_products_string);
        }

        $limit = 18;
        $offset = 0;

        $sort_data = [
            'product_description.name',
            'product.model',
            'product_to_store.quantity',
            'product_to_store.price',
            'product.sort_order',
            'product.date_added',
        ];

        $this->db->group_by('product_description.name');
        $this->db->where('product_to_store.status', 1);
        //REMOVED QUANTITY VALIDATION
        //$this->db->where('product_to_store.quantity >=', 1);
        $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
        $this->db->where('product.status', 1);
        if ($store_id > 0) {
            $this->db->where('product_to_store.store_id', $store_id);
        }
        $ret = $this->db->get('product_to_store', $limit, $offset)->rows;
        $ret2 = array();
        foreach ($ret as $re) {
            if ($parent_customer_info == NULL && isset($customer_info['customer_category']) && $customer_info['customer_category'] != NULL) {
                $category_price_data = $this->getCategoryPrices($re['product_store_id'], $store_id, $customer_info['customer_category']);
                $re['category_price'] = is_array($category_price_data) && count($category_price_data) > 0 && array_key_exists('price', $category_price_data) && $category_price_data['price'] > 0 ? $category_price_data['price'] : 0;
                //$log = new Log('error.log');
                //$log->write('category_price');
            } elseif ($parent_customer_info != NULL && isset($parent_customer_info['customer_category']) && $parent_customer_info['customer_category'] != NULL) {
                $category_price_data = $this->getCategoryPrices($re['product_store_id'], $store_id, $parent_customer_info['customer_category']);
                $re['category_price'] = is_array($category_price_data) && count($category_price_data) > 0 && array_key_exists('price', $category_price_data) && $category_price_data['price'] > 0 ? $category_price_data['price'] : 0;
                //$log = new Log('error.log');
                //$log->write('category_price');
            } else {
                $re['category_price'] = 0;
                //$log = new Log('error.log');
                //$log->write('category_price_2');
            }
            $ret2[] = $re;
        }
        //$log = new Log('error.log');
        //$log->write('ret2');
        //$log->write($ret2);
        //$log->write('ret2');
        //$log->write($ret);
        //return $ret;
        return $ret2;
    }

    public function getProductsForCategory($filter_name, $cateogry_price_products) {


        $disabled_products = array_column($cateogry_price_products, 'product_store_id');
        $disabled_products_string = implode(',', $disabled_products);

        $store_id = 0;

        $this->db->select('product_to_store.*,product_to_category.category_id,product.*,product_description.*,product_description.name as pd_name', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        if ($disabled_products_string != NULL) {
            $this->db->where_not_in('product_to_store.product_store_id', $disabled_products_string);
        }

        $limit = 18;
        $offset = 0;
        $this->db->group_by('product_description.name');
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
        $this->db->where('product.status', 1);
        if ($store_id > 0) {
            $this->db->where('product_to_store.store_id', $store_id);
        }
        $ret = $this->db->get('product_to_store', $limit, $offset)->rows;
        return $ret;
    }

    public function getProductsForInventory($filter_name) {

        $store_id = 0;

        $this->db->select('product_to_store.*,product_to_category.category_id,product.*,product_description.*,product_description.name as pd_name', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        $limit = 18;
        $offset = 0;
        $this->db->group_by('product_description.name');
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
        $this->db->where('product.status', 1);
        if ($store_id > 0) {
            $this->db->where('product_to_store.store_id', $store_id);
        }
        $ret = $this->db->get('product_to_store', $limit, $offset)->rows;
        return $ret;
    }

    public function getProductForPopup($product_store_id, $is_admin = false, $store_id) {
        if (!isset($store_id)) {
            $store_id = $this->session->data['config_store_id'];
        }
        $this->db->select('product_to_store.*,product_description.*,product.*,product_description.name as pd_name', false);
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->group_by('product_to_store.product_store_id');
        $this->db->where('product_to_store.store_id', $store_id);
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
        $this->db->where('product_to_store.product_store_id', $product_store_id);
        $ret = $this->db->get('product_to_store')->row;

        return $ret;
    }

    public function getProductVariationsNew($product_name, $store_id, $order_id, $formated = false) {
        $returnData = [];

        $this->load->model('sale/order');
        $this->load->model('account/customer');
        $order_info = $this->model_sale_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        /* IF CUSTOMER SUB CUSTOMER */
        $parent_customer_info = NULL;
        if (isset($customer_info) && $customer_info['parent'] > 0) {
            $parent_customer_info = $this->model_account_customer->getCustomer($customer_info['parent']);
        }

        $all_variations = 'SELECT * ,product_store_id as variation_id FROM ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE name = '$product_name' and ps.status=1";

        $result = $this->db->query($all_variations);

        foreach ($result->rows as $r) {
            if ($r['status']) {
                //REMOVED QUANTITY VALIDATION
                //if ($r['quantity'] > 0 && $r['status']) {
                $key = base64_encode(serialize(['product_store_id' => (int) $r['product_store_id'], 'store_id' => $store_id]));

                $r['key'] = $key;

                $percent_off = null;
                if (isset($r['special_price']) && isset($r['price']) && 0 != $r['price'] && 0 != $r['special_price']) {
                    $percent_off = (($r['price'] - $r['special_price']) / $r['price']) * 100;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $r['price'] = $this->currency->formatWithoutCurrency($r['price']);
                }

                if ((float) $r['special_price']) {
                    $r['special_price'] = $this->currency->formatWithoutCurrency((float) $r['special_price']);
                } else {
                    $r['special_price'] = false;
                }

                if ($parent_customer_info == NULL && $customer_info != NULL && array_key_exists('customer_category', $customer_info) && $customer_info['customer_category'] != NULL) {
                    $category_price_data = $this->getCategoryPrices($r['product_store_id'], $store_id, $customer_info['customer_category']);
                    $log = new Log('error.log');
                    $log->write($category_price_data);

                    if (is_array($category_price_data) && count($category_price_data) > 0) {
                        $category_price = $this->currency->formatWithoutCurrency((float) $category_price_data['price']);
                        $category_price_status = $category_price_data['status'];
                    } else {
                        $category_price = 0;
                        $category_price_status = 1;
                    }
                } elseif ($parent_customer_info != NULL && array_key_exists('customer_category', $parent_customer_info) && $parent_customer_info['customer_category'] != NULL) {
                    $category_price_data = $this->getCategoryPrices($r['product_store_id'], $store_id, $parent_customer_info['customer_category']);
                    $log = new Log('error.log');
                    $log->write($category_price_data);

                    if (is_array($category_price_data) && count($category_price_data) > 0) {
                        $category_price = $this->currency->formatWithoutCurrency((float) $category_price_data['price']);
                        $category_price_status = $category_price_data['status'];
                    } else {
                        $category_price = 0;
                        $category_price_status = 1;
                    }
                } else {
                    $category_price = 0;
                    $category_price_status = 1;
                }
                $r['category_price'] = $category_price;
                $r['category_price_status'] = $category_price_status;
                $r['category_price_variant'] = $category_price > 0 && $category_price_status == 0 ? 'disabled' : '';
                $r['model'] = $r['model'];

                $res = [
                    'variation_id' => $r['product_store_id'],
                    'unit' => $r['unit'],
                    'weight' => floatval($r['weight']),
                    'price' => $r['price'],
                    'special' => $r['special_price'],
                    'percent_off' => number_format($percent_off, 0),
                    'category_price' => $category_price,
                    'category_price_status' => $category_price_status,
                    'category_price_variant' => $category_price > 0 && $category_price_status == 0 ? 'disabled' : '',
                    'max_qty' => $r['min_quantity'] > 0 ? $r['min_quantity'] : $r['quantity'],
                    'qty_in_cart' => isset($r['qty_in_cart']) ? $r['qty_in_cart'] : NULL,
                    'key' => $key,
                    'model' => $r['model']
                ];

                if (true == $formated) {
                    array_push($returnData, $res);
                } else {
                    array_push($returnData, $r);
                }
            }
        }

        return $returnData;
    }

    public function getVendorProductVariationsNew($product_name, $store_id, $vendor_category_price_product, $formated = false) {
        $returnData = [];

        $disabled_products = array_column($vendor_category_price_product, 'product_store_id');
        $disabled_products_string = implode(',', $disabled_products);

        $all_variations = 'SELECT * ,product_store_id as variation_id FROM ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE name = '$product_name' AND ps.product_store_id NOT IN (" . $disabled_products_string . ") and ps.status=1";
        $log = new Log('error.log');
        $log->write($all_variations);
        $result = $this->db->query($all_variations);

        foreach ($result->rows as $r) {
            if ($r['status']) {
                $key = base64_encode(serialize(['product_store_id' => (int) $r['product_store_id'], 'store_id' => $store_id]));

                $r['key'] = $key;

                $percent_off = null;
                if (isset($r['special_price']) && isset($r['price']) && 0 != $r['price'] && 0 != $r['special_price']) {
                    $percent_off = (($r['price'] - $r['special_price']) / $r['price']) * 100;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $r['price'] = $this->currency->formatWithoutCurrency($r['price']);
                }

                if ((float) $r['special_price']) {
                    $r['special_price'] = $this->currency->formatWithoutCurrency((float) $r['special_price']);
                } else {
                    $r['special_price'] = false;
                }

                $r['model'] = $r['model'];

                $res = [
                    'variation_id' => $r['product_store_id'],
                    'unit' => $r['unit'],
                    'weight' => floatval($r['weight']),
                    'price' => $r['price'],
                    'special' => $r['special_price'],
                    'percent_off' => number_format($percent_off, 0),
                    'key' => $key,
                    'model' => $r['model']
                ];

                if (true == $formated) {
                    array_push($returnData, $res);
                } else {
                    array_push($returnData, $r);
                }
            }
        }

        return $returnData;
    }

    public function getVendorProductVariations($product_name, $store_id, $formated = false) {
        $returnData = [];

        $all_variations = 'SELECT * ,product_store_id as variation_id FROM ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE name = '$product_name' and ps.status=1";
        $log = new Log('error.log');
        $log->write($all_variations);
        $result = $this->db->query($all_variations);

        foreach ($result->rows as $r) {
            if ($r['status']) {
                $key = base64_encode(serialize(['product_store_id' => (int) $r['product_store_id'], 'store_id' => $store_id]));

                $r['key'] = $key;

                $percent_off = null;
                if (isset($r['special_price']) && isset($r['price']) && 0 != $r['price'] && 0 != $r['special_price']) {
                    $percent_off = (($r['price'] - $r['special_price']) / $r['price']) * 100;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $r['price'] = $this->currency->formatWithoutCurrency($r['price']);
                }

                if ((float) $r['special_price']) {
                    $r['special_price'] = $this->currency->formatWithoutCurrency((float) $r['special_price']);
                } else {
                    $r['special_price'] = false;
                }

                $r['model'] = $r['model'];

                $res = [
                    'variation_id' => $r['product_store_id'],
                    'unit' => $r['unit'],
                    'weight' => floatval($r['weight']),
                    'price' => $r['price'],
                    'special' => $r['special_price'],
                    'percent_off' => number_format($percent_off, 0),
                    'key' => $key,
                    'model' => $r['model']
                ];

                if (true == $formated) {
                    array_push($returnData, $res);
                } else {
                    array_push($returnData, $r);
                }
            }
        }

        return $returnData;
    }

    public function getProductVariationsNewFarmer($product_name, $store_id, $formated = false) {
        $returnData = [];

        $this->load->model('sale/order');

        $all_variations = 'SELECT * ,product_store_id as variation_id FROM ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE name = '$product_name' and ps.status=1";

        $result = $this->db->query($all_variations);

        foreach ($result->rows as $r) {
            if ($r['status']) {
                //REMOVED QUANTITY VALIDATION
                //if ($r['quantity'] > 0 && $r['status']) {
                $key = base64_encode(serialize(['product_store_id' => (int) $r['product_store_id'], 'store_id' => $store_id]));

                $r['key'] = $key;

                $percent_off = null;
                if (isset($r['special_price']) && isset($r['price']) && 0 != $r['price'] && 0 != $r['special_price']) {
                    $percent_off = (($r['price'] - $r['special_price']) / $r['price']) * 100;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $r['price'] = $this->currency->formatWithoutCurrency($r['price']);
                }

                if ((float) $r['special_price']) {
                    $r['special_price'] = $this->currency->formatWithoutCurrency((float) $r['special_price']);
                } else {
                    $r['special_price'] = false;
                }
                $r['model'] = $r['model'];

                $res = [
                    'variation_id' => $r['product_store_id'],
                    'unit' => $r['unit'],
                    'weight' => floatval($r['weight']),
                    'price' => $r['price'],
                    'special' => $r['special_price'],
                    'percent_off' => number_format($percent_off, 0),
                    'max_qty' => $r['min_quantity'] > 0 ? $r['min_quantity'] : $r['quantity'],
                    'qty_in_cart' => $r['qty_in_cart'],
                    'key' => $key,
                    'model' => $r['model']
                ];

                if (true == $formated) {
                    array_push($returnData, $res);
                } else {
                    array_push($returnData, $r);
                }
            }
        }

        return $returnData;
    }

    public function getOrdersFilter($data = []) {//Same as getORders,so removed this method from fastorders screen
        $sql = "SELECT c.name as city, o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, (SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.total, o.currency_code, o.store_name , o.delivery_date ,o.delivery_timeslot,  o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_day'])) {
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'delivery_timeslot',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            if (isset($data['sort']) && in_array($data['sort'], $sort_data) && 'delivery_timeslot' == $data['sort']) {
                $sql .= ' ASC';
            } else {
                $sql .= ' DESC';
            }
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo "<pre>";print_r($query->rows);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrdersFilter($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getSellingByTopCategory($category_id) {
        $sql = 'select sum(op.total) as total from ' . DB_PREFIX . 'order_product op';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = op.order_id';
        $sql .= ' where op.category_id = "' . $category_id . '"';
        // o.order_status_id = '.$this->config->get('config_complete_status_id');$sql .= ' AND
        return $this->db->query($sql)->row['total'];
    }

    public function getVendorTotalSales() {
        $query = $this->db->query('SELECT SUM(total) AS total FROM `' . DB_PREFIX . "vendor_order` WHERE payment_status = '1' and vendor_id='" . $this->user->getId() . "'");

        return $query->row['total'];
    }

    public function getVendorTotalSalesByYear($year) {
        $query = $this->db->query('SELECT SUM(total) AS total FROM `' . DB_PREFIX . "vendor_order` WHERE payment_status = '1' AND YEAR(date_added) = '" . (int) $year . "'");

        return $query->row['total'];
    }

    public function getVendorTotal($order_id) {
        /*
          key
          - total
          - total_data
         */
        $result = [];
        $sub_orders = $this->db->query('select * from ' . DB_PREFIX . 'vendor_order where order_id=' . $order_id)->rows;

        foreach ($sub_orders as $sub_order) {
            $result[$sub_order['store_id']]['total'] = $sub_order['total'];
            $result[$sub_order['store_id']]['total_data'] = $this->db->query('select * from ' . DB_PREFIX . 'vendor_order_total where store_id="' . $sub_order['store_id'] . '" AND order_id="' . $order_id . '"')->rows;
        }

        return $result;
    }

    public function get_commision($vendor_id) {
        $row = $this->db->query('select commision, free_from, free_to from ' . DB_PREFIX . 'user where user_id="' . $vendor_id . '"')->row;
        if (time() >= strtotime($row['free_from']) && time() <= strtotime($row['free_to'])) {
            return 0; //in free trial month
        } else {
            return $row['commision'];
        }
    }

    public function getTotalCommisionByVendor($user_id) {
        $sql = 'SELECT sum(commision * vo.total / 100) as result from ' . DB_PREFIX . 'order vo';
        $sql .= ' left join ' . DB_PREFIX . 'store st on st.store_id = vo.order_id';
        $sql .= " WHERE vo.order_status_id='" . $this->config->get('config_complete_status_id') . "'";
        $sub_query = 'select order_id from ' . DB_PREFIX . "order_product where vendor_id='" . $user_id . "' group by order_id";
        $sql .= ' AND vo.order_id IN (' . $sub_query . ')';
        $sql .= " AND st.vendor_id='" . $user_id . "'";

        return $this->currency->format($this->db->query($sql)->row['result']);
    }

    public function getTotalSellingByVendor($user_id) {
        $sql = 'SELECT sum(op.total) as result from ' . DB_PREFIX . 'order_product op';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = op.order_id';
        $sql .= " where op.vendor_id='" . $user_id . "'";
        $sql .= " AND o.order_status_id='" . $this->config->get('config_complete_status_id') . "'";

        return $this->currency->format($this->db->query($sql)->row['result']);
    }

    public function getTotalOrdersByVendor($user_id) {
        $row = $this->db->query('select count(order_id) as total from ' . DB_PREFIX . "order_product where vendor_id='" . $user_id . "' group by order_id")->row;
        if ($row) {
            return $row['total'];
        } else {
            return 0;
        }
    }

    public function getStatus($status_id) {
        $sql = 'select * from ' . DB_PREFIX . 'order_status WHERE order_status_id="' . $status_id . '" AND language_id="' . $this->config->get('config_language_id') . '"';

        $row = $this->db->query($sql)->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getOrderQuestions($order_id) {
        $sql = 'select * from ' . DB_PREFIX . 'order_questions WHERE order_id="' . $order_id . '"';

        return $this->db->query($sql)->rows;
    }

    public function getStatusNameById($store_id) {
        $row = $this->db->query('select name from ' . DB_PREFIX . 'order_status WHERE language_id= ' . $this->config->get('config_language_id') . ' AND order_status_id = ' . $store_id)->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getVendorId($store_id) {
        return $this->db->query('select vendor_id from `' . DB_PREFIX . 'store` WHERE store_id="' . $store_id . '"')->row['vendor_id'];
    }

    public function getOrderData($order_id) {
        $order_query = $this->db->query("SELECT o.*,  CONCAT(c.firstname, ' ', c.lastname) AS customer ,c.customer_id FROM `" . DB_PREFIX . 'order` o   Left Join  `' . DB_PREFIX . "customer` c on  c.customer_id = o.customer_id WHERE o.order_id = '" . (int) $order_id . "'");
        //echo $this->db->last_query();die;

        if ($order_query->num_rows) {
            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'customer_id' => $order_query->row['customer_id'],
                'invoice_sufix' => $order_query->row['invoice_sufix'],
            ];
        } else {
            return;
        }
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT o.*, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . 'customer c WHERE c.customer_id = o.customer_id) AS customer FROM `' . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

//      echo $this->db->last_query();die;

        if ($order_query->num_rows) {
            $reward = 0;

            $order_product_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
            $customer_company_name_query = $this->db->query('SELECT company_name FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $order_query->row['customer_id'] . "'");

//            echo "<pre>";print_r($customer_company_name_query->row['company_name']);die;

            foreach ($order_product_query->rows as $product) {
                $reward += $product['reward'];
            }

            if ($order_query->row['affiliate_id']) {
                $affiliate_id = $order_query->row['affiliate_id'];
            } else {
                $affiliate_id = 0;
            }

            $this->load->model('localisation/city');

            $shipping_city_info = $this->model_localisation_city->getCity($order_query->row['shipping_city_id']);

            if ($shipping_city_info) {
                $shipping_city = $shipping_city_info['name'];
            } else {
                $shipping_city = '';
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

//            echo "<pre>";print_r($order_query->row);die;

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'invoice_sufix' => $order_query->row['invoice_sufix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'customer' => $order_query->row['customer'],
                'customer_group_id' => $order_query->row['customer_group_id'],
                'customer_company_name' => $customer_company_name_query->row['company_name'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'email' => $order_query->row['email'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'custom_field' => unserialize($order_query->row['custom_field']),
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_city_id' => $order_query->row['shipping_city_id'],
                'shipping_city' => $shipping_city,
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_code' => $order_query->row['shipping_code'],
                'driver_id' => $order_query->row['driver_id'],
                'vehicle_number' => $order_query->row['vehicle_number'],
                'delivery_executive_id' => $order_query->row['delivery_executive_id'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'reward' => $reward,
                'rating' => $order_query->row['rating'],
                'order_status_id' => $order_query->row['order_status_id'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                //'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_added' => $order_query->row['date_added'],
                'date_modified' => $order_query->row['date_modified'],
                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'commsion_received' => $order_query->row['commsion_received'],
                'delivery_id' => $order_query->row['delivery_id'],
                'commission' => $order_query->row['commission'],
                'settlement_amount' => $order_query->row['settlement_amount'],
                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
                'po_number' => $order_query->row['po_number'],
                'login_latitude' => $order_query->row['login_latitude'],
                'login_longitude' => $order_query->row['login_longitude'],
                'order_processing_group_id' => $order_query->row['order_processing_group_id'],
                'order_processor_id' => $order_query->row['order_processor_id'],
                'head_chef' => $order_query->row['head_chef'],
                'procurement' => $order_query->row['procurement'],
                'delivery_charge' => $order_query->row['delivery_charges'],
                'vendor_terms_cod' => $order_query->row['vendor_terms_cod'],
                'vendor_order_status_id' => $order_query->row['vendor_order_status_id'],
            ];
        } else {
            return;
        }
    }

    public function getOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, o.vendor_order_status_id,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no,o.paid,o.amount_partialy_paid,o.delivery_charges FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if (isset($data['filter_orders'])) {
            $sql .= ' AND  o.order_id in (' . $data['filter_orders'] . ')';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_customer_id'])) {
            $sql .= " AND o.customer_id = '" . $data['filter_customer_id'] . "'";
        }

        if (!empty($data['filter_paid'])) {
            $sql .= " AND o.paid = '" . $data['filter_paid'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method']) && $data['filter_delivery_method'] != 'undefined') {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot']) && $data['filter_delivery_time_slot'] != 'undefined') {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total']) && $data['filter_total'] > 0) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //   echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPezeshaOrders($data = []) {
        $sql = "SELECT pz.mpesa_reference, c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, o.vendor_order_status_id,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no,o.paid,o.amount_partialy_paid,o.delivery_charges FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' JOIN ' . DB_PREFIX . 'pezesha_loan_recceivables pz on (pz.order_id = o.order_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if (isset($data['filter_orders'])) {
            $sql .= ' AND  o.order_id in (' . $data['filter_orders'] . ')';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_customer_id'])) {
            $sql .= " AND o.customer_id = '" . $data['filter_customer_id'] . "'";
        }

        if (!empty($data['filter_paid'])) {
            $sql .= " AND o.paid = '" . $data['filter_paid'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method']) && $data['filter_delivery_method'] != 'undefined') {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot']) && $data['filter_delivery_time_slot'] != 'undefined') {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total']) && $data['filter_total'] > 0) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //   echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrdersDelivaries($data = []) {
        $sql = "SELECT * FROM `" . DB_PREFIX . 'order_delivery` o ';

        // $sql = "SELECT od.order_reference_id, od.delivery_partner, od.delivery_status, od.drop_latitude, od.drop_longitude, od.created_at, od.updated_at, c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, o.latitude, o.longitude,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";
        // $sql .= ' JOIN `' . DB_PREFIX . 'order_delivery` od on od.order_id = o.order_id ';
        // $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        // $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        // $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            // $sql .= " WHERE o.order_status_id > '0'";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id in (" . $data['filter_order_id'] . ")";
        }

        // if (!empty($data['filter_order_from_id'])) {
        //     $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        // }
        // if (!empty($data['filter_order_to_id'])) {
        //     $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        // }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //   echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrdersByAccountManager($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        // echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        $sql .= " AND cust.account_manager_id = '" . $this->user->getId() . "'";

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrdersByAccountManagerCustom($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        // echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        if (!empty($data['account_manager'])) {
            $sql .= " AND cust.account_manager_id = '" . $data['account_manager'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    //Created immediately as requested
    public function getNonCancelledOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,(SELECT cust.SAP_customer_no FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS SAP_customer_no,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        // if (isset($data['filter_order_status'])) {
        $sql .= " WHERE o.order_status_id != '6'  And o.order_status_id != '15'  And o.order_status_id > '0'";

        //} else {
        // $sql .= " WHERE o.order_status_id > '0'";
        // }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        // echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        // echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getNonCancelledOrderswithPending($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,(SELECT cust.SAP_customer_no FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS SAP_customer_no,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method,o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id , o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";
        // $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        // if (isset($data['filter_order_status'])) {
        $sql .= " WHERE o.order_status_id != '6'  And o.order_status_id > '0'";

        //} else {
        // $sql .= " WHERE o.order_status_id > '0'";
        // }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city']) && $data['filter_city'] != 'undefined') {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id']) && $data['filter_order_id'] != 'undefined') {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer']) && $data['filter_customer'] != 'undefined') {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor']) && $data['filter_vendor'] != 'undefined') {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        // echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store_name']) && $data['filter_store_name'] != 'undefined') {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment']) && $data['filter_payment'] != 'undefined') {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method']) && $data['filter_delivery_method'] != 'undefined') {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date']) && $data['filter_delivery_date'] != 'undefined') {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && $data['filter_date_added'] != 'undefined') {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }
        //added below filter to get orders added after the given date time
        if (!empty($data['filter_date_added_greater']) && $data['filter_date_added_greater'] != 'undefined') {
            $sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added_greater']) . "')";
        }

        if (!empty($data['filter_date_modified']) && $data['filter_date_modified'] != 'undefined') {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total']) && $data['filter_total'] != 'undefined') {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        //  echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getOrderswithProcessing($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,(SELECT cust.SAP_customer_no FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS SAP_customer_no,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method,o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, o.shipping_code, o.order_status_id,o.store_name,o.store_id , o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";
        // $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (isset($data['filter_order_status'])) {
            // $sql .= " WHERE o.order_status_id = '1' ";

            $implodestatus = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implodestatus[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implodestatus) {
                $sql .= ' WHERE (' . implode(' OR ', $implodestatus) . ')';
            }

            // $sql .= " WHERE o.order_status_id not in (0,2,5,6,4,16,13,10,3)";//8?
        } else {
            // $sql .= " WHERE o.order_status_id > '0'";
            $sql .= " WHERE o.order_status_id not in (0,2,5,6,4,16,13,10,3)"; //8?
        }



        if (isset($data['filter_orders'])) {


            $sql .= ' and  o.order_id in (' . $data['filter_orders'] . ')';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city']) && $data['filter_city'] != 'undefined') {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id']) && $data['filter_order_id'] != 'undefined') {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer']) && $data['filter_customer'] != 'undefined') {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor']) && $data['filter_vendor'] != 'undefined') {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        // echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store_name']) && $data['filter_store_name'] != 'undefined') {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment']) && $data['filter_payment'] != 'undefined') {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method']) && $data['filter_delivery_method'] != 'undefined') {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date']) && $data['filter_delivery_date'] != 'undefined') {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (isset($data['filter_delivery_time']) && !empty($data['filter_delivery_time']) && $data['filter_delivery_time'] != 'undefined') {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time']) . "'";
        }

        if (!empty($data['filter_date_added']) && $data['filter_date_added'] != 'undefined') {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }
        //added below filter to get orders added after the given date time
        if (!empty($data['filter_date_added_greater']) && $data['filter_date_added_greater'] != 'undefined') {
            $sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added_greater']) . "')";
        }

        if (!empty($data['filter_date_modified']) && $data['filter_date_modified'] != 'undefined') {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total']) && $data['filter_total'] != 'undefined') {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        //  echo "<pre>";print_r($sql);die;
        return $query->rows;
    }

    public function getFastOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,(SELECT cust.SAP_customer_no FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS SAP_customer_no,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }



        if (isset($data['filter_orders'])) {

            $sql .= ' and  o.order_id in ("' . $data['filter_orders'] . '")';
        }

        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        //   echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        // echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getOrderProductsStockOut($order_id, $store_id = 0, $name) {
        $sql = "SELECT * ,'0' as quantity_updated,'0' as unit_updated FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        if ($name != NULL) {
            $sql .= " AND name='" . $name . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderProducts($order_id, $store_id = 0) {
        $sql = "SELECT * ,'0' as quantity_updated,'0' as unit_updated FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);
        // echo "<pre>";print_r( $query->rows);die;
        return $query->rows;
    }

    public function getOrderProductById($order_id, $product_id) {
        $sql = "SELECT * ,'0' as quantity_updated,'0' as unit_updated FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_id ='" . (int) $product_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getOrderProductStoreId($order_id, $product_store_id) {
        $sql = "SELECT * ,'0' as quantity_updated,'0' as unit_updated FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND product_id ='" . (int) $product_store_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getOrderAndRealOrderProducts($order_id, $store_id = 0) {
        $sql1 = "SELECT * ,'0' as quantity_updated,'0' as unit_updated,0 as category_id,'' as category FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        $sql2 = "SELECT * ,'0' as quantity_updated,'0' as unit_updated,0 as category_id,'' as category FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql1 .= " AND store_id='" . $store_id . "'";
            $sql2 .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql1);
        if ($query->rows) {
            return $query->rows;
        } else {
            $query = $this->db->query($sql2);

            return $query->rows;
        }
    }

    public function getOrderProductsItems($order_id, $store_id = 0) {
        $qty = 0;

        $sql = 'SELECT sum(quantity) as quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql)->row;

        if (isset($query['quantity'])) {
            $qty = $query['quantity'];
        }

        return $qty;
    }

    public function getRealOrderProductsStockOut($order_id, $store_id = 0, $name) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        if ($name != NULL) {
            $sql .= " AND name='" . $name . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getRealOrderProducts($order_id, $store_id = 0) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getRealOrderProductById($order_id, $product_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getRealOrderProductStoreId($order_id, $product_store_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_store_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getRealOrderProductsItems($order_id, $store_id = 0) {
        $qty = 0;

        $sql = 'SELECT sum(quantity) as quantity FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql)->row;

        if (isset($query['quantity'])) {
            $qty = $query['quantity'];
        }

        return $qty;
    }

    public function deleteOrderProduct($order_id, $product_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "' and product_id = '" . (int) $product_id . "'";

        $query = $this->db->query($sql);
    }

    public function deleteCustomerOrderProduct($order_id, $product_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' and product_id = '" . (int) $product_id . "'";

        $query = $this->db->query($sql);
    }

    public function getOrderProductsIds($order_id) {
        $sql = 'SELECT product_id FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderTransactionId($order_id) {
        $sql = 'SELECT transaction_id FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getOrderTransactionIdExists($order_id) {
        $sql = 'SELECT transaction_id FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);
        if (isset($query->row)) {
            /* $log = new Log('error.log');
              $log->write('order model');
              $log->write($query->row);
              $log->write('order model'); */
            if (array_key_exists('transaction_id', $query->row)) {
                return $query->row['transaction_id'];
            } else {
                return '';
            }
        }

        return null;
    }

    public function deleteOrderTotal($order_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);
    }

    public function insertOrderTransactionId($order_id, $transaction_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function insertOrderTotal($order_id, $total, $shipping_price) {
        if ('shipping' == $total['code']) {
            if (count($shipping_price) > 0 && isset($shipping_price['cost']) && isset($shipping_price['actual_cost'])) {
                $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $shipping_price['cost'] . "', actual_value = '" . $shipping_price['actual_cost'] . "', order_id = '" . $order_id . "', title = '" . $total['title'] . "', sort_order = '" . $total['sort'] . "', code = '" . $total['code'] . "'";

                $query = $this->db->query($sql);
            } else {
                $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $total['value'] . "', actual_value = '" . $total['actual_value'] . "', order_id = '" . $order_id . "', title = '" . $total['title'] . "', sort_order = '" . $total['sort'] . "', code = '" . $total['code'] . "'";

                $query = $this->db->query($sql);
            }
        } else {
            $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $total['value'] . "', order_id = '" . $order_id . "', title = '" . $total['title'] . "', sort_order = '" . $total['sort'] . "', code = '" . $total['code'] . "'";
            $query = $this->db->query($sql);
        }
    }

    public function insertOrderSubTotalAndTotal($order_id, $sub_total, $total, $sort_order) {
        $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $sub_total . "', order_id = '" . $order_id . "', title = 'Sub-Total', code = 'sub_total', sort_order = '1'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $total . "', order_id = '" . $order_id . "', title = 'Total', code = 'total', sort_order = '" . $sort_order . "'";

        $query = $this->db->query($sql);

        $order_sql = 'UPDATE ' . DB_PREFIX . "order SET total = '" . $total . "' WHERE order_id = '" . (int) $order_id . "'";

        $order_query = $this->db->query($order_sql);
    }

    public function hasRealOrderProducts($order_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return true;
        }

        return false;
    }

    public function updateOrderNewProduct($order_id, $product_id, $data, $tax = NULL) {
        $log = new Log('error.log');
        $log->write('updateOrderNewProduct');

        $log->write($data);
        $tax_value = 0;
        if (is_array($tax) && count($tax) > 0 && array_key_exists('code', $tax[0]) && array_key_exists('title', $tax[0]) && array_key_exists('value', $tax[0]) && array_key_exists('sort_order', $tax[0]) && $tax[0]['code'] == 'tax' && $tax[0]['value'] > 0) {
            $tax_value = $tax[0]['value'];
            //$log->write('tax_value NEW');
            //$log->write($tax_value);
            //$log->write('tax_value NEW');
        }

        $total = $data['price'] * $data['quantity'];

        //$this->deleteOrderProduct($order_id,$product_id);

        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "' and product_id = '" . $product_id . "'";

        $query = $this->db->query($sql);

        if (!$query->num_rows) {
            $sql = 'INSERT into ' . DB_PREFIX . "real_order_product SET name = '" . $this->db->escape($data['name']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', price = '" . $this->db->escape($data['price']) . "', model = '" . $this->db->escape($data['model']) . "', unit = '" . $this->db->escape($data['unit']) . "', vendor_id = '" . $data['vendor_id'] . "', store_id = '" . $data['store_id'] . "', order_id = '" . $order_id . "', product_id = '" . $product_id . "',produce_type = '" . $data['produce_type'] . "',product_note = '" . $data['product_note'] . "', total = '" . $total . "', tax = '" . $tax_value . "'";

            $query = $this->db->query($sql);
        } else {
            //echo "<pre>";print_r($query->row['quantity']);die;

            $data['quantity'] += $query->row['quantity'];

            $total = $data['price'] * $data['quantity'];

            //echo "<pre>";print_r($data['quantity']);die;

            $log->write('else ss');

            $log->write($total);

            $sql = 'UPDATE ' . DB_PREFIX . "real_order_product SET name = '" . $this->db->escape($data['name']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', model = '" . $this->db->escape($data['model']) . "', vendor_id = '" . $data['vendor_id'] . "', store_id = '" . $data['store_id'] . "', price = '" . $this->db->escape($data['price']) . "', unit = '" . $this->db->escape($data['unit']) . "', product_note = '" . $data['product_note'] . "', total = '" . $total . "', tax = '" . $tax_value . "' WHERE order_id = '" . (int) $order_id . "' and product_id = '" . $product_id . "'";

            $query = $this->db->query($sql);
        }
    }

    public function updateOrderProduct($order_id, $product_id, $data, $tax = NULL) {
        $total = $data['price'] * $data['quantity'];

        //$this->deleteOrderProduct($order_id,$product_id);
        $tax_value = 0;
        if (is_array($tax) && count($tax) > 0 && array_key_exists('code', $tax[0]) && array_key_exists('title', $tax[0]) && array_key_exists('value', $tax[0]) && array_key_exists('sort_order', $tax[0]) && $tax[0]['code'] == 'tax' && $tax[0]['value'] > 0) {
            $tax_value = $tax[0]['value'];
            //$log = new Log('error.log');
            //$log->write('tax_value OLD');
            //$log->write($tax_value);
            //$log->write('tax_value OLD');
        }

        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "' and product_id = '" . $product_id . "'";

        $query = $this->db->query($sql);

        if ($data['quantity'] > 0) {
            if (!$query->num_rows) {
                $sql = 'INSERT into ' . DB_PREFIX . "real_order_product SET name = '" . $this->db->escape($data['name']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', price = '" . $this->db->escape($data['price']) . "', model = '" . $this->db->escape($data['model']) . "', unit = '" . $this->db->escape($data['unit']) . "', vendor_id = '" . $data['vendor_id'] . "', store_id = '" . $data['store_id'] . "', order_id = '" . $order_id . "', product_id = '" . $product_id . "',produce_type = '" . $data['produce_type'] . "',product_note = '" . $data['product_note'] . "', total = '" . $total . "', tax = '" . $tax_value . "'";

                $query = $this->db->query($sql);
            } else {
                $sql = 'UPDATE ' . DB_PREFIX . "real_order_product SET name = '" . $this->db->escape($data['name']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', vendor_id = '" . $data['vendor_id'] . "', store_id = '" . $data['store_id'] . "', model = '" . $this->db->escape($data['model']) . "', price = '" . $this->db->escape($data['price']) . "', unit = '" . $this->db->escape($data['unit']) . "', total = '" . $total . "', tax = '" . $tax_value . "', product_note = '" . $data['product_note'] . "' WHERE order_id = '" . (int) $order_id . "' and product_id = '" . $product_id . "'";

                $query = $this->db->query($sql);
            }
        } if ($data['quantity'] <= 0) {
            $sql = 'Delete FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "' and product_id = '" . (int) $product_id . "'";
            $query = $this->db->query($sql);
        }
    }

    public function updateOrderProductNew($order_id, $product_id, $data, $tax = NULL) {
        $total = $data['price'] * $data['quantity'];

        //$this->deleteOrderProduct($order_id,$product_id);
        $tax_value = 0;
        if (is_array($tax) && count($tax) > 0 && array_key_exists('code', $tax[0]) && array_key_exists('title', $tax[0]) && array_key_exists('value', $tax[0]) && array_key_exists('sort_order', $tax[0]) && $tax[0]['code'] == 'tax' && $tax[0]['value'] > 0) {
            $tax_value = $tax[0]['value'];
            //$log = new Log('error.log');
            //$log->write('tax_value OLD');
            //$log->write($tax_value);
            //$log->write('tax_value OLD');
        }

        $sql = 'SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' and product_id = '" . $product_id . "'";

        $query = $this->db->query($sql);

        if (!$query->num_rows) {
            $sql = 'INSERT into ' . DB_PREFIX . "order_product SET name = '" . $this->db->escape($data['name']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', price = '" . $this->db->escape($data['price']) . "', model = '" . $this->db->escape($data['model']) . "', unit = '" . $this->db->escape($data['unit']) . "', vendor_id = '" . $data['vendor_id'] . "', store_id = '" . $data['store_id'] . "', order_id = '" . $order_id . "', product_id = '" . $product_id . "',produce_type = '" . $data['produce_type'] . "',product_note = '" . $data['product_note'] . "', total = '" . $total . "', tax = '" . $tax_value . "'";

            $query = $this->db->query($sql);
        } else {
            $sql = 'UPDATE ' . DB_PREFIX . "order_product SET name = '" . $this->db->escape($data['name']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', vendor_id = '" . $data['vendor_id'] . "', store_id = '" . $data['store_id'] . "', model = '" . $this->db->escape($data['model']) . "', price = '" . $this->db->escape($data['price']) . "', unit = '" . $this->db->escape($data['unit']) . "', total = '" . $total . "', tax = '" . $tax_value . "' WHERE order_id = '" . (int) $order_id . "' and product_id = '" . $product_id . "'";

            $query = $this->db->query($sql);
        }
    }

    public function getOrderOption($order_id, $order_option_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_option_id = '" . (int) $order_option_id . "'");

        return $query->row;
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getOrderTransactionFee($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' AND code = 'transaction_fee' ORDER BY sort_order");

        return $query->row;
    }

    public function getTotalCreditsByOrderId($order_id) {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM ' . DB_PREFIX . "customer_credit WHERE order_id = '" . (int) $order_id . "' and amount > 0");

        return $query->row['total'];
    }

    public function getTotalOrders($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }
        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }


        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_paid'])) {
            $sql .= " AND o.paid = '" . $data['filter_paid'] . "'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;
        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot'])) {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        // echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalPezeshaOrders($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        $sql .= ' JOIN ' . DB_PREFIX . 'pezesha_loan_recceivables pz on (pz.order_id = o.order_id) ';

        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }
        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }


        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;
        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot'])) {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        // echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersDelivaries($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order_delivery` o ';

        // $sql .= ' JOIN `' . DB_PREFIX . 'order_delivery` od on od.order_id = o.order_id ';
        // $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        // $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        // $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        }
        // else {
        //     $sql .= " WHERE o.order_status_id > '0'";
        // }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }
        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id in ( " . $data['filter_order_id'] . ")";
        }

        //not correct ,as orders have comma seperated values
        // if (!empty($data['filter_order_from_id'])) {
        //     $sql .= " AND o.order_id >= '" .  $data['filter_order_from_id'] . "'";
        // }
        // if (!empty($data['filter_order_to_id'])) {
        //     $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        // }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;
        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        // echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersCustom($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersByAccountManager($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        $sql .= " AND cust.account_manager_id = '" . $this->user->getId() . "'";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersByStoreId($store_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE store_id = '" . (int) $store_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrdersByOrderStatusId($order_status_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE order_status_id = '" . (int) $order_status_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByProcessingStatus() {
        $implode = [];

        $order_statuses = $this->config->get('config_processing_status');

        foreach ($order_statuses as $order_status_id) {
            $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
        }

        if ($implode) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` WHERE ' . implode(' OR ', $implode));

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalOrdersByCompleteStatus() {
        $implode = [];

        $order_statuses = $this->config->get('config_complete_status');

        foreach ($order_statuses as $order_status_id) {
            $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
        }

        if ($implode) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` WHERE ' . implode(' OR ', $implode) . '');

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalOrdersByLanguageId($language_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE language_id = '" . (int) $language_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByCurrencyId($currency_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE currency_id = '" . (int) $currency_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function createInvoiceNo($order_id) {
        $order_info = $this->getOrderData($order_id);

        if ($order_info && !$order_info['invoice_no']) {
            $query = $this->db->query('SELECT MAX(invoice_no) AS invoice_no FROM `' . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

            if ($query->row['invoice_no']) {
                $invoice_no = $query->row['invoice_no'] + 1;
            } else {
                $invoice_no = 1;
            }
            $invoice_sufix = '-' . $order_info['customer_id'] . '-' . $order_id;
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "', invoice_sufix = '" . $invoice_sufix . "' WHERE order_id = '" . (int) $order_id . "'");

            return $order_info['invoice_prefix'] . $invoice_no . $invoice_sufix;
        }
    }

    public function getOrderHistories($order_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query('SELECT oh.date_added, oh.added_by, oh.role, os.name AS status, os.color AS color, oh.comment, oh.notify FROM ' . DB_PREFIX . 'order_history oh LEFT JOIN ' . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int) $start . ',' . (int) $limit);

        return $query->rows;
    }

    public function getTotalOrderHistories($order_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "order_history WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "order_history WHERE order_status_id = '" . (int) $order_status_id . "'");

        return $query->row['total'];
    }

    public function getEmailsByProductsOrdered($products, $start, $end) {
        $implode = [];

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . (int) $product_id . "'";
        }

        $query = $this->db->query('SELECT DISTINCT email FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_product op ON (o.order_id = op.order_id) WHERE (' . implode(' OR ', $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int) $start . ',' . (int) $end);

        return $query->rows;
    }

    public function getTotalEmailsByProductsOrdered($products) {
        $implode = [];

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . (int) $product_id . "'";
        }

        $query = $this->db->query('SELECT DISTINCT email FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_product op ON (o.order_id = op.order_id) WHERE (' . implode(' OR ', $implode) . ") AND o.order_status_id <> '0'");

        return $query->row['total'];
    }

    public function payment_status($order_id, $status, $store_id) {
        $log = new Log('error.log');

        $log->write('order payment distribution fn admin');

        $this->load->model('sale/order');

        /* $this->db->query('update `' . DB_PREFIX . 'order` SET commsion_received="' . $status . '" WHERE store_id="' . $store_id . '" AND order_id="' . $order_id . '"'); */

        $order_info = $this->db->query('select * from ' . DB_PREFIX . 'order LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE ' . DB_PREFIX . 'order.store_id="' . $store_id . '" AND order_id="' . $order_id . '"')->row;

        $order_sub_total = $order_info['total'];

        $shipping_charges = 0;

        $deduct_from_vendor_shipping = false;

        $order_total_info = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="shipping"')->row;

        if (is_array($order_total_info) && !is_null($order_total_info['actual_value'])) {
            $tempCalc = $order_total_info['value'] + 0;
            if (!$tempCalc) {
                $deduct_from_vendor_shipping = true;
            }

            $shipping_charges = $order_total_info['actual_value'];
        }

        //shipping_charges to be sent to delviery system

        $order_total_info = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="sub_total"')->row;

        if (is_array($order_total_info)) {
            $order_sub_total = $order_total_info['value'];
        }

        if (isset($order_info['settlement_amount'])) {
            $order_sub_total = $order_info['settlement_amount'];
        }

        //echo "<pre>";print_r($order_sub_total);die;
        //print_r($order_info);die;

        $temp_vendor_commision = 0;
        $temp_vendor_com = 0;

        $store_info = $this->getStoreData($store_id);

        //echo "<pre>";print_r($store_info);die;
        if ('category' == $store_info['commission_type']) {
            $order_products = $this->getOrderProductsAdminCopy($order_id, $store_id);

            //echo "<pre>";print_r($order_products);die;
            foreach ($order_products as $key => $value) {
                //this is product store id $value['product_id']

                $p_to_s_info = $this->db->query('select * from ' . DB_PREFIX . 'product_to_store  WHERE product_store_id=' . $value['product_id'])->row;

                if (count($p_to_s_info) > 0) {
                    $cat_info = [];

                    $cat_infos = $this->db->query('select * from ' . DB_PREFIX . 'product_to_category  WHERE product_id=' . $p_to_s_info['product_id'])->rows;

                    //echo "<pre>";print_r($cat_infos);die;
                    foreach ($cat_infos as $key => $value_tmp) {
                        $cat_temp = $this->db->query('select * from ' . DB_PREFIX . 'category  WHERE category_id=' . $value_tmp['category_id'])->row;

                        if (0 == $cat_temp['parent_id']) {
                            $cat_info = $value_tmp;
                        }
                    }

                    //echo "<pre>";print_r($cat_info);die;

                    if (count($cat_info) > 0) {
                        $cat_id = $cat_info['category_id'];

                        $cat_commission = $this->getStoreCategoryCommision($store_id, $cat_id);

                        //echo "<pre>";print_r($cat_commission);die;

                        if (count($cat_commission) > 0) {
                            $temp_vendor_com += ($value['total'] * $cat_commission['commission'] / 100);
                        } else {
                            // not present so applying stores commission
                            $temp_vendor_com += ($value['total'] * $order_info['commission'] / 100);
                        }
                    } else {
                        $temp_vendor_com += ($value['total'] * $order_info['commission'] / 100);
                    }
                }
            }

            //fixed subtraction at last

            $temp_vendor_com += $order_info['fixed_commission'];

            $vendor_commision = $order_sub_total - $temp_vendor_com;
            $admin_commision = $order_sub_total - $vendor_commision;
        } else {
            $vendor_commision_rate = $order_info['commission'];
            $vendor_commision = $order_sub_total - ($order_sub_total * $vendor_commision_rate / 100);

            //my added //if fixed is set subtract it
            $vendor_commision_fixed = $order_info['fixed_commission'];
            $vendor_commision = $vendor_commision - $vendor_commision_fixed;

            //end

            $shopper_commision = $order_info['shopper_commision'];
            $admin_commision = $order_sub_total - $vendor_commision;
        }

        if ($deduct_from_vendor_shipping) {
            $vendor_commision = $vendor_commision - $shipping_charges;
        }

        $order_total_info_tax = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="tax"')->row;

        if (is_array($order_total_info_tax) && isset($order_total_info_tax['value'])) {
            $vendor_commision += $order_total_info_tax['value'];
        }

        $log->write('front payment_status');

        $log->write('vendor_commision' . $vendor_commision);
        $log->write('admin_commision' . $admin_commision);
        $log->write('delivery sytem send' . $shipping_charges);
        $log->write('status' . $status);
        $log->write($order_info);

        $vendorCommision = 0;
        if (1 == $status && 1 == $order_info['commsion_received']) {
            $log->write('vendor_commision if ' . $vendor_commision);

            //echo "<pre>";print_r("s");die;
            //$vendorCommision = $vendor_commision;
            $vendorCommision = 0 - $vendor_commision;

            $this->db->query('insert into `' . DB_PREFIX . 'vendor_wallet` SET vendor_id="' . $order_info['vendor_id'] . '", order_id="' . $order_id . '", description="Order Value : ' . $this->currency->format($order_sub_total) . '", amount="' . $vendor_commision . '", date_added=NOW()');

            //Admin Waller add
            $this->db->query('insert into `' . DB_PREFIX . 'admin_wallet` SET  order_id="' . $order_id . '", description="admin commision", amount="' . $admin_commision . '", date_added=NOW()');

            if ('shopper.shopper' == $order_info['shipping_code']) {
                if ('cod' == $order_info['payment_code']) {
                    //debit order_total - shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . ($order_sub_total - $shopper_commision) . '", date_added=NOW()');
                } else {
                    //credit shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . $shopper_commision . '", date_added=NOW()');
                }
            }
        } else {
            $log->write('vendor_commision else' . $vendor_commision);
            //$vendorCommision = 0 -$vendor_commision;
            $vendorCommision = $vendor_commision;
            $this->db->query('insert into `' . DB_PREFIX . 'vendor_wallet` SET vendor_id="' . $order_info['vendor_id'] . '", order_id="' . $order_id . '", description="Order Value : ' . $this->currency->format($order_sub_total) . '", amount="' . $vendor_commision . '", date_added=NOW()');

            //Admin Wallet add
            $this->db->query('insert into `' . DB_PREFIX . 'admin_wallet` SET  order_id="' . $order_id . '", description="admin commision", amount="' . (0 - $admin_commision) . '", date_added=NOW()');

            if ('shopper.shopper' == $order_info['shipping_code']) {
                if ('cod' == $order_info['payment_code']) {
                    //debit order_total - shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . (0 - ($order_sub_total - $shopper_commision)) . '", date_added=NOW()');
                } else {
                    //credit shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . (0 - $shopper_commision) . '", date_added=NOW()');
                }
            }
        }

        $vendorData = $this->getVendorDetails($order_info['vendor_id']);

        //echo "<pre>";print_r($vendorData);die;

        if (isset($vendorData['email'])) {
            // 6 merchant mail
            $vendorData['amount'] = $vendorCommision;

            $vendorData['transaction_type'] = 'credited';

            if ($vendorData['amount'] <= 0) {
                $vendorData['transaction_type'] = 'debited';
            }

            $vendorData['amount'] = $this->currency->format($vendorData['amount']);

            $subject = $this->emailtemplate->getSubject('Contact', 'contact_6', $vendorData);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_6', $vendorData);
            //mishramanjari15@gmail.com
            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($vendorData['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            //$mail->setReplyTo($vendorData['email']);
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }

        $log = new Log('error.log');
        if ($this->emailtemplate->getNotificationEnabled('Contact', 'contact_6')) {
            $vendorData['amount'] = $vendorCommision;
            $vendorData['transaction_type'] = 'credited';

            if ($vendorData['amount'] <= 0) {
                $vendorData['transaction_type'] = 'debited';
            }

            $vendorData['amount'] = $this->currency->format($vendorData['amount']);

            $log->write('status enabled of wallet mobi noti');
            $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Contact', 'contact_6', $vendorData);

            $log->write($mobile_notification_template);

            $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Contact', 'contact_6', $vendorData);

            $log->write($mobile_notification_title);

            $log->write($vendorData);

            if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {
                $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set ADMIN.MODEL.SALE.ORDER');

                $notification_id = $this->saveVendorNotification($order_info['vendor_id'], $vendorData['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);

                $sen['notification_id'] = $notification_id;

                $ret = $this->emailtemplate->sendVendorPushNotification($order_info['vendor_id'], $vendorData['device_id'], $order_id, $order_info['store_id'], $mobile_notification_template, $mobile_notification_title, $sen);
            } else {
                $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set ADMIN.MODEL.SALE.ORDER');
            }
        }
    }

    public function getReturn($order_id) {
        return $this->db->query('select count(*) as total from `' . DB_PREFIX . 'return` WHERE order_id="' . $order_id . '"');
    }

    public function getStoreData($store_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'store where store_id =' . $store_id . '')->row;
    }

    public function isMyOrder($order_id, $store_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'order WHERE order_id="' . $order_id . '" AND store_id="' . $store_id . '"')->row;
    }

    public function getCreditDetail($transaction_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "vendor_wallet WHERE id = '" . (int) $transaction_id . "'");

        return $query->row;
    }

    public function isVendorOrder($order_id, $vendor_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'order  LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_id="' . $order_id . '" AND vendor_id = "' . $vendor_id . '"')->row;
    }

    public function getCities() {
        return $this->db->query('select city_id, name from `' . DB_PREFIX . 'city` order by sort_order')->rows;
    }

    public function getCitiesLike($like) {
        return $this->db->query('select name, city_id from `' . DB_PREFIX . 'city` WHERE name LIKE "' . $like . '%" order by sort_order')->rows;
    }

    public function getCitiesLikeWithLimit($like, $limit) {
        return $this->db->query('select name, city_id from `' . DB_PREFIX . 'city` WHERE name LIKE "' . $like . '%" order by sort_order limit ' . $limit)->rows;
    }

    public function getTotalFromOrder($city_id) {
        return $this->db->query('select count(*) as total from `' . DB_PREFIX . 'order` WHERE shipping_city_id="' . $city_id . '"')->row['total'];
    }

    public function getTotalFromAddress($city_id) {
        return $this->db->query('select count(*) as total from `' . DB_PREFIX . 'address` WHERE city_id="' . $city_id . '"')->row['total'];
    }

    public function getVendorToPackages($vendor_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'vendor_to_package where vendor_id="' . $vendor_id . '" AND date_end > "' . date('Y-m-d') . '" ORDER BY vp DESC')->row;
    }

    public function getStoreDatas($vendor_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'store where vendor_id="' . $vendor_id . '"')->rows;
    }

    public function getUserDetails($filter_name, $shopper_group_id) {
        $sql = 'select CONCAT(firstname," ",lastname) as name, user_id from `' . DB_PREFIX . 'user` ';
        $sql .= 'WHERE CONCAT(firstname," ",lastname) LIKE "' . $filter_name . '%" ';
        $sql .= 'AND user_group_id IN (' . $shopper_group_id . ')';

        return $this->db->query($sql)->rows;
    }

    public function getVendorUserData($filter_name) {
        $sql = 'select user_id, CONCAT(firstname," ",lastname) as name from `' . DB_PREFIX . 'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "' . $filter_name . '%" AND user_group_id =11 LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getVendorUserDataOnlyFirstName($filter_name) {
        $sql = 'select user_id, CONCAT(firstname) as name from `' . DB_PREFIX . 'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "' . $filter_name . '%" AND user_group_id =11 LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getVendorDetails($vendor_id) {
        $sql = 'select *, CONCAT(firstname," ",lastname) as name from `' . DB_PREFIX . 'user`';
        $sql .= ' WHERE user_id="' . $vendor_id . '" AND user_group_id =11 LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getUserData($filter_name) {
        $sql = 'select user_id, CONCAT(firstname," ",lastname) as name from `' . DB_PREFIX . 'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "' . $filter_name . '%" LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreDetails($q) {
        $sql = 'SELECT name, store_id from `' . DB_PREFIX . 'store` WHERE name LIKE "%' . $q . '%"';

        /* if ($this->user->isVendor()) {
          $sql .= " AND vendor_id='" . $this->user->getId() . "'";
          } */

        $sql .= ' LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreGroupDetails($q) {
        $sql = 'SELECT name, id from `' . DB_PREFIX . 'store_groups` WHERE name LIKE "%' . $q . '%"';

        $sql .= ' LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreNameById($id) {
        $sql = 'SELECT name,store_id from `' . DB_PREFIX . 'store` WHERE store_id =' . $id;

        return $this->db->query($sql)->row;
    }

    public function getStoreIdByVendorId($vendor_id) {
        return $this->db->query('select store_id from `' . DB_PREFIX . 'store` WHERE vendor_id="' . $vendor_id . '"')->rows;
    }

    public function getVendorOrder($order_id, $store_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'vendor_order WHERE order_id="' . $order_id . '" AND store_id="' . $store_id . '"')->row;
    }

    public function updateVendorOrderDeliveryDate($value, $key, $order_id) {
        $this->db->query('update ' . DB_PREFIX . 'vendor_order SET delivery_date="' . date('Y-m-d', strtotime($value)) . '" where store_id="' . $key . '" AND order_id = "' . $order_id . '"');
    }

    public function updateVendorOrderDeliveryTime($value, $key, $order_id) {
        $this->db->query('update ' . DB_PREFIX . 'vendor_order set delivery_timeslot="' . $value . '" where store_id="' . $key . '" AND order_id = "' . $order_id . '"');
    }

    public function updateVendorOrderStatus($status, $store_id, $order_id) {
        $this->db->query('update `' . DB_PREFIX . 'vendor_order` SET order_status_id="' . $status . '" WHERE store_id="' . $store_id . '" AND order_id="' . $order_id . '"');
    }

    public function selectDeliverySlotsFromStore($store_id) {
        return $this->db->query('select delivery_time_diff, home_delivery_timeslots from ' . DB_PREFIX . 'store where store_id = "' . $store_id . '"')->row;
    }

    public function shopper_autocomplete($config_shopper_group_ids, $filter_name) {
        $sql = "SELECT CONCAT(firstname,' ',lastname) as name, user_id FROM `" . DB_PREFIX . 'user` ';
        $sql .= 'WHERE user_group_id IN (' . $config_shopper_group_ids . ') ';

        if ($filter_name) {
            $sql .= 'AND CONCAT(firstname," ",lastname) LIKE "' . $filter_name . '%"';
        }

        return $this->db->query($sql)->rows;
    }

    public function settle_payment($order_id, $final_amount) {
        $this->db->query('update `' . DB_PREFIX . 'order` SET settlement_amount="' . $final_amount . '" WHERE order_id="' . $order_id . '"');
    }

    public function getOrderIugu($order_id) {
        $sql = 'select * from `' . DB_PREFIX . 'order_iugu`';
        $sql .= ' WHERE order_id="' . $order_id . '" LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderIuguCustomer($customer_id) {
        $sql = 'select * from `' . DB_PREFIX . 'customer_to_customer_iugu`';
        $sql .= ' WHERE customer_id="' . $customer_id . '" LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderIuguAndTotal($order_id) {
        $sql = 'SELECT    * FROM   `' . DB_PREFIX . 'order_iugu` n LEFT OUTER JOIN `' . DB_PREFIX . 'order_total`  a ON a.order_id = n.order_id WHERE (a.code = "sub_total" and  a.order_id = "' . $order_id . '")';

        return $this->db->query($sql)->row;
    }

    public function saveVendorNotification($user_id, $deviceId, $order_id, $message, $title) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "vendor_notifications SET user_id = '" . $user_id . "', type = 'wallet', purpose_id = '" . $order_id . "', title = '" . $title . "', message = '" . $message . "', status = 'unread', created_at = NOW() , updated_at = NOW()");

        $notificaiton_id = $this->db->getLastId();

        return $notificaiton_id;
    }

    public function getStoreCategoryCommision($store_id, $category_id) {
        $sql = 'select * from `' . DB_PREFIX . 'store_category_commission`';
        $sql .= ' WHERE store_id="' . $store_id . '" and category_id="' . $category_id . '"  LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function stripeCharge() {
        
    }

    public function updatePO($order_id, $po_number, $SAP_customer_no = '', $SAP_doc_no = '') {

        //    echo "<pre>";print_r($this->db->escape($SAP_customer_no));die;
        //sap customer number is moved to customer table, as it is unique for all orders of customer
        if ('' == $SAP_customer_no && '' == $SAP_doc_no) {

            $this->db->query('update `' . DB_PREFIX . 'order` SET po_number="' . $po_number . '" WHERE order_id="' . $order_id . '"');
        } else {
            $this->db->query('update `' . DB_PREFIX . 'order` SET po_number="' . $po_number . '",SAP_customer_no="' . $SAP_customer_no . '",SAP_doc_no="' . $SAP_doc_no . '" WHERE order_id="' . $order_id . '"');
            // echo 'update `' . DB_PREFIX . 'order` SET po_number="' . $po_number . '", SAP_doc_no="' . $SAP_doc_no . '" ,SAP_customer_no="'.$SAP_customer_no.'" WHERE order_id="' . $order_id . '"';
            //get customerid
            $custsql = 'select customer_id from `' . DB_PREFIX . 'order` where order_id="' . $order_id . '"';

            $customer_id = $this->db->query($custsql)->row['customer_id'];
            // echo   "<pre>";print_r($customer_id);die;

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET SAP_customer_no = '" . $SAP_customer_no . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }
    }

    public function getPO($order_id) {
        // $sql = 'SELECT o.order_id,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `'.DB_PREFIX."order` o  WHERE o.order_id = '$order_id'";

        $sql = 'SELECT o.order_id,o.po_number,c.SAP_customer_no,o.SAP_doc_no FROM ' . DB_PREFIX . 'order o   LEFT join ' . DB_PREFIX . "customer c ON (o.customer_id =c.customer_id)  WHERE o.order_id = '$order_id'";

        //   echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function UpdateOrderVehicleDetails($order_id, $vehicle_details) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET vehicle_number="' . $vehicle_details . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');
    }

    public function UpdateOrderDriverDetails($order_id, $driver_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET driver_id="' . $driver_id . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');
    }

    public function UpdateOrderDeliveryCharge($order_id, $delivery_charge) {

        // $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET delivery_charges="' . $delivery_charge . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');
        //insert into order_total
        $alreadyupdated = $this->db->query('select delivery_charges from ' . DB_PREFIX . 'order  WHERE order_id="' . $order_id . '" and delivery_charges > 0');
        if ($alreadyupdated->num_rows <= 0) {
            $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET delivery_charges="' . $delivery_charge . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');

            $exists = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="shipping"');
            // echo "<pre>";print_r($exists);
            //  echo "<pre>";print_r('UPDATE `' . DB_PREFIX . 'order_total` SET value=value+ '.$delivery_charge.'  WHERE order_id="' . $order_id . '" and code="shipping"');die;
            if ($exists->num_rows > 0) {
                $delivery_charge = $exists->row['value'] + $delivery_charge;
                $this->db->query('UPDATE `' . DB_PREFIX . 'order_total` SET value=' . (int) $delivery_charge . ' WHERE order_id="' . $order_id . '" and code="shipping"');
            } else {
                //   echo "<pre>";print_r('INSERT into ' . DB_PREFIX . "order_total SET value = '" . $delivery_charge . "', order_id = '" . $order_id . "', title = 'Standard Delivery', sort_order = 6, code = 'shipping'");die;
                $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $delivery_charge . "', order_id = '" . $order_id . "', title = 'Standard Delivery', sort_order = 6, code = 'shipping'";
                $query = $this->db->query($sql);
            }
            //insert vat delivery charge
            $delivery_vat_applicable = 0;
            try {
                $deliverycharge_temp = $this->db->query('select * from ' . DB_PREFIX . 'temp_deliverycharge Limit 0,1')->row;

                $delivery_vat_applicable = $deliverycharge_temp['delivery_charge_vat'];
                $delivery_vat_percentage = $deliverycharge_temp['VAT_percent'];
            } catch (exception $ex) {
                $delivery_vat_applicable = 0;
            }
            if ($delivery_vat_applicable == 1) {
                $delivery_charge_vat = (($delivery_charge) * $delivery_vat_percentage);
                $Total_amount = $delivery_charge_vat + $delivery_charge;

                $sql2 = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $delivery_charge_vat . "', order_id = '" . $order_id . "', title = 'VAT on Standard Delivery', sort_order = 6, code = 'delivery_vat'";
                $query2 = $this->db->query($sql2);
            } else {
                $Total_amount = $delivery_charge;
            }

            $this->db->query('UPDATE `' . DB_PREFIX . 'order_total` SET value=value+' . $Total_amount . ' WHERE order_id="' . $order_id . '" and code="total"');
            $order_totals = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="total"');
            if ($order_totals->num_rows > 0) {
                $log = new Log('error.log');
                $log->write('order_totals_shipping_charge');
                $log->write($order_totals->num_rows);
                $log->write('order_totals_shipping_charge');
                $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET total=' . (int) $order_totals->row['value'] . ' WHERE order_id="' . $order_id . '"');
            }
            // echo "<pre>";print_r('UPDATE `' . DB_PREFIX . 'order_total` SET value=value+' . $delivery_charge . ' WHERE order_id="' . $order_id . '" and code="total"');die;
        }
    }

    public function UpdateOrderDeliveryCharges($order_id, $delivery_charge) {

        $old_delivery_charge = 0;
        $old_delivery_vat_charge = 0;
        $old_delivery_charge_total = 0;
        $order_total_without_delivery_charges = 0;

        $total_without_delivery_charges = $this->db->query('SELECT SUM(value) AS total FROM `' . DB_PREFIX . "order_total` WHERE order_id ='" . $order_id . "' and code NOT IN ('shipping', 'delivery_vat', 'total')");
        $order_total_without_delivery_charges = $total_without_delivery_charges->row['total'];
        $log = new Log('error.log');
        $log->write($order_total_without_delivery_charges);
        //insert into order_total
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET delivery_charges="' . $delivery_charge . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');

        $exists = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="shipping"');
        // echo "<pre>";print_r($exists);
        //  echo "<pre>";print_r('UPDATE `' . DB_PREFIX . 'order_total` SET value=value+ '.$delivery_charge.'  WHERE order_id="' . $order_id . '" and code="shipping"');die;
        if ($exists->num_rows > 0) {
            $old_delivery_charge = $exists->row['value'];
            $this->db->query('UPDATE `' . DB_PREFIX . 'order_total` SET value=' . (int) $delivery_charge . ' WHERE order_id="' . $order_id . '" and code="shipping"');
        } else {
            $old_delivery_charge = 0;
            //   echo "<pre>";print_r('INSERT into ' . DB_PREFIX . "order_total SET value = '" . $delivery_charge . "', order_id = '" . $order_id . "', title = 'Standard Delivery', sort_order = 6, code = 'shipping'");die;
            $sql = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $delivery_charge . "', order_id = '" . $order_id . "', title = 'Standard Delivery', sort_order = 6, code = 'shipping'";
            $query = $this->db->query($sql);
        }

        $exists2 = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="delivery_vat"');
        if ($exists2->num_rows > 0) {
            $old_delivery_vat_charge = $exists2->row['value'];
        } else {
            $old_delivery_vat_charge = 0;
        }
        $old_delivery_charge_total = $old_delivery_charge + $old_delivery_vat_charge;

        //insert vat delivery charge
        $delivery_vat_applicable = 0;
        try {
            $deliverycharge_temp = $this->db->query('select * from ' . DB_PREFIX . 'temp_deliverycharge Limit 0,1')->row;

            $delivery_vat_applicable = $deliverycharge_temp['delivery_charge_vat'];
            $delivery_vat_percentage = $deliverycharge_temp['VAT_percent'];
        } catch (exception $ex) {
            $delivery_vat_applicable = 0;
        }
        if ($delivery_vat_applicable == 1 && $exists2->num_rows <= 0) {
            $delivery_charge_vat = (($delivery_charge) * $delivery_vat_percentage);
            $Total_amount = $delivery_charge_vat + $delivery_charge;

            $sql2 = 'INSERT into ' . DB_PREFIX . "order_total SET value = '" . $delivery_charge_vat . "', order_id = '" . $order_id . "', title = 'VAT on Standard Delivery', sort_order = 6, code = 'delivery_vat'";
            $query2 = $this->db->query($sql2);
        } elseif ($delivery_vat_applicable == 1 && $exists2->num_rows >= 0) {
            $delivery_charge_vat = (($delivery_charge) * $delivery_vat_percentage);
            $Total_amount = $delivery_charge_vat + $delivery_charge;

            $this->db->query('UPDATE `' . DB_PREFIX . 'order_total` SET value=' . $delivery_charge_vat . ' WHERE order_id="' . $order_id . '" and code="delivery_vat"');
        } else {
            $Total_amount = $delivery_charge;
        }
        $order_grand_total = $Total_amount + $order_total_without_delivery_charges;
        $this->db->query('UPDATE `' . DB_PREFIX . 'order_total` SET value=' . $order_grand_total . ' WHERE order_id="' . $order_id . '" and code="total"');
        $order_totals = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="total"');
        if ($order_totals->num_rows > 0) {
            $log = new Log('error.log');
            $log->write('order_totals_shipping_charge');
            $log->write($order_totals->num_rows);
            $log->write('order_totals_shipping_charge');
            $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET total=' . (int) $order_totals->row['value'] . ' WHERE order_id="' . $order_id . '"');
        }
        // echo "<pre>";print_r('UPDATE `' . DB_PREFIX . 'order_total` SET value=value+' . $delivery_charge . ' WHERE order_id="' . $order_id . '" and code="total"');die;
    }

    public function UpdateOrderDeliveryExecutiveDetails($order_id, $delivery_executive_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET delivery_executive_id="' . $delivery_executive_id . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');
    }

    public function UpdateOrderProcessingDetails($order_id, $order_processing_group_id, $order_processor_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET order_processing_group_id="' . $order_processing_group_id . '", order_processor_id="' . $order_processor_id . '", date_modified = NOW() WHERE order_id="' . $order_id . '"');
    }

    public function getTotalIncompleteOrders($data = []) {
        $log = new Log('error.log');
        $log->write('Check For incomplete Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id = '0'";
        }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }


        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;


        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        // echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function TotalRevenueBookedDashBoard($data = []) {
        $all_orders = $this->getOrders($data);
        $log = new Log('error.log');
        if (is_array($all_orders) && count($all_orders) > 0) {
            $order_grand_total = 0;
            foreach ($all_orders as $all_order) {
                $order_totals = $this->getOrderTotals($all_order['order_id']);
                //$log->write($all_order['order_id']);
                if (is_array($order_totals) && count($order_totals) > 0) {
                    foreach ($order_totals as $order_total) {
                        if ($order_total['code'] == 'total') {
                            $order_grand_total += $order_total['value'];
                            //$log->write($order_total);
                        }
                    }
                }
            }
        }
        return $order_grand_total;
    }

    public function getOrderedProducts($data = []) {
        $sql = "SELECT p.order_product_id, o.firstname,o.lastname,cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, o.order_status_id,p.product_id,p.general_product_id,p.name,p.unit,p.quantity,p.price,p.total,p.tax FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'order_product p on (o.order_id = p.order_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];
        // $sql .= " Group by o.order_id";
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //    echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderedMissingProducts($data = []) {
        $sql = "SELECT mp.id,o.firstname,o.lastname,cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.order_status_id,mp.product_id,mp.product_store_id,mp.name,mp.unit,mp.quantity,mp.price,mp.total,mp.tax,mp.model,mp.product_note,mp.quantity_required,mp.price AS mp_price,mp.tax AS mp_tax,mp.total AS mp_total FROM `" . DB_PREFIX . 'order` o ';

        $sql .= ' INNER JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'missing_products mp on (o.order_id = mp.order_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'  and o.order_id =mp.order_id ";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot']) && $data['filter_delivery_time_slot'] != 'undefined') {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];
        // $sql .= " Group by o.order_id";
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo "<pre>";print_r($sql);die;
        $log = new Log('error.log');
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrderedProducts($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'LEFT JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'order_product p on (o.order_id = p.order_id) ';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }
        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }


        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;
        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        //  echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrderedMissingProducts($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'INNER JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'INNER JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'missing_products p on (o.order_id = p.order_id) ';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }
        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }


        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;
        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot']) && $data['filter_delivery_time_slot'] != 'undefined') {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        //  echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function addOrderProductToMissingProduct($order_product_id, $required_quantity = 0, $name, $unit, $product_note, $model, $order_id, $removed_from_invoice) {
        $sql = 'Select * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "' AND order_id = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);
        $productinfo = $query->row;

        if ($productinfo == NULL) {
            $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_product_id = '" . (int) $order_product_id . "' AND order_id = '" . (int) $order_id . "'";
            $query = $this->db->query($sql);
            $productinfo = $query->row;
        }

        if ($productinfo != null) {
            $sql = 'Delete FROM ' . DB_PREFIX . "missing_products WHERE order_id = '" . (int) $productinfo['order_id'] . "' and product_store_id = '" . (int) $productinfo['product_id'] . "'";

            $query = $this->db->query($sql);

            if ($required_quantity == 0 || $required_quantity == null)
                $required_quantity = $productinfo['quantity'];

            // echo "<pre>";print_r($required_quantity);die;

            $sql = 'INSERT into ' . DB_PREFIX . "missing_products SET order_id = '" . $productinfo['order_id'] . "', product_store_id = '" . $productinfo['product_id'] . "' , product_id = '" . $productinfo['general_product_id'] . "', quantity = '" . $productinfo['quantity'] . "', price = '" . $productinfo['price'] . "', tax = '" . $productinfo['tax'] . "', total = '" . $productinfo['price'] * $required_quantity . "',  quantity_required = '" . $required_quantity . "', name = '" . $name . "', unit = '" . $unit . "', product_note = '" . $product_note . "', model = '" . $model . "', removed_from_invoice = '" . $removed_from_invoice . "', created_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', updated_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', created_by = '" . $this->user->getId() . "'";
            //  echo "<pre>";print_r($sql);die;


            $query = $this->db->query($sql);
        }
    }

    public function addOrderProductToMissingProducts($order_product_id, $required_quantity = 0, $name, $unit, $product_note, $model, $order_id, $removed_from_invoice) {
        $log = new Log('error.log');
        $sql = 'SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "' AND order_id = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);
        $productinfo = $query->row;

        if ($productinfo == NULL) {
            $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_product_id = '" . (int) $order_product_id . "' AND order_id = '" . (int) $order_id . "'";
            $query = $this->db->query($sql);
            $productinfo = $query->row;
        }

        if ($productinfo != null) {
            $sql2 = 'SELECT * FROM ' . DB_PREFIX . "missing_products WHERE order_id = '" . (int) $productinfo['order_id'] . "' and product_store_id = '" . (int) $productinfo['product_id'] . "'";

            $query2 = $this->db->query($sql2);

            $missing_product_info = $query2->row;

            if ($required_quantity == 0 || $required_quantity == null) {
                $required_quantity = $productinfo['quantity'];
            }
            if ($missing_product_info != NULL) {
                $sql3 = 'UPDATE ' . DB_PREFIX . "missing_products SET quantity = '" . $productinfo['quantity'] . "', price = '" . $productinfo['price'] . "', tax = '" . $productinfo['tax'] . "', total = '" . $productinfo['price'] * $required_quantity . "',  quantity_required = '" . $required_quantity . "', name = '" . $name . "', unit = '" . $unit . "', product_note = '" . $product_note . "', model = '" . $model . "', removed_from_invoice = '" . $removed_from_invoice . "',  updated_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', updated_by = '" . $this->user->getId() . "' WHERE id = '" . $missing_product_info['id'] . "'";

                $query3 = $this->db->query($sql3);
            } else {
                $sql4 = 'INSERT INTO ' . DB_PREFIX . "missing_products SET order_id = '" . $productinfo['order_id'] . "', product_store_id = '" . $productinfo['product_id'] . "' , product_id = '" . $productinfo['general_product_id'] . "', quantity = '" . $productinfo['quantity'] . "', price = '" . $productinfo['price'] . "', tax = '" . $productinfo['tax'] . "', total = '" . $productinfo['price'] * $required_quantity . "',  quantity_required = '" . $required_quantity . "', name = '" . $name . "', unit = '" . $unit . "', product_note = '" . $product_note . "', model = '" . $model . "', removed_from_invoice = '" . $removed_from_invoice . "', created_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', updated_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', created_by = '" . $this->user->getId() . "'";

                $query4 = $this->db->query($sql4);
            }
        }
    }

    public function deleteOrderProductToMissingProducts($order_product_id, $required_quantity = 0, $name, $unit, $product_note, $model, $order_id) {
        $log = new Log('error.log');
        $sql = 'SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "' AND order_id = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);
        $productinfo = $query->row;

        if ($productinfo == NULL) {
            $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_product_id = '" . (int) $order_product_id . "' AND order_id = '" . (int) $order_id . "'";
            $query = $this->db->query($sql);
            $productinfo = $query->row;
        }

        if ($productinfo != NULL) {
            $sql2 = 'SELECT * FROM ' . DB_PREFIX . "missing_products WHERE order_id = '" . (int) $productinfo['order_id'] . "' AND product_store_id = '" . (int) $productinfo['product_id'] . "'";
            $query2 = $this->db->query($sql2);
            $missing_product_info = $query2->row;

            if ($missing_product_info != NULL) {
                $sql3 = 'DELETE FROM ' . DB_PREFIX . "missing_products WHERE id = '" . $missing_product_info['id'] . "'";
                $query3 = $this->db->query($sql3);
            }
        }
    }

    public function deleteOrderProductToMissingProductsFromInvoice($product_id, $order_id) {
        $log = new Log('error.log');
        $sql = 'SELECT * FROM ' . DB_PREFIX . "order_product WHERE product_id = '" . (int) $product_id . "' AND order_id = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);
        $productinfo = $query->row;

        if ($productinfo == NULL) {
            $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE product_id = '" . (int) $product_id . "' AND order_id = '" . (int) $order_id . "'";
            $query = $this->db->query($sql);
            $productinfo = $query->row;
        }

        if ($productinfo != NULL) {
            $sql2 = 'SELECT * FROM ' . DB_PREFIX . "missing_products WHERE order_id = '" . (int) $productinfo['order_id'] . "' AND product_store_id = '" . (int) $productinfo['product_id'] . "'";
            $query2 = $this->db->query($sql2);
            $missing_product_info = $query2->row;

            if ($missing_product_info != NULL) {
                $sql3 = 'DELETE FROM ' . DB_PREFIX . "missing_products WHERE id = '" . $missing_product_info['id'] . "'";
                $query3 = $this->db->query($sql3);
            }
        }
    }

    //changes in this getOrderedMissingProducts need to chek 
    public function getOrderedMissingProductsOnlyOrder($data = []) {
        $sql = "SELECT distinct o.order_id FROM `" . DB_PREFIX . 'order` o ';

        $sql .= ' INNER JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'missing_products mp on (o.order_id = mp.order_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'  and o.order_id =mp.order_id ";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];
        // $sql .= " Group by o.order_id";
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo "<pre>";print_r($sql);die;
        $log = new Log('error.log');
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrdersDashboard($data = []) {
        $sql = "SELECT o.order_id,  ot.value FROM `" . DB_PREFIX . 'order` o ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'order_total ot on (o.order_id = ot.order_id) where ot.code="total"';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' and (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " and o.order_status_id > '0'";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }


        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sql .= ' ORDER BY o.order_id';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }



        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        //   return $query->rows;


        $all_orders = $query->rows;
        $log = new Log('error.log');
        if (is_array($all_orders) && count($all_orders) > 0) {
            $order_grand_total = 0;
            foreach ($all_orders as $all_order) {



                $order_grand_total += $all_order['value'];
                //$log->write($order_total);
            }
        }
        return $order_grand_total;
    }

    public function getOrderInfo($order_id) {

        $sql = "SELECT o.order_id, o.delivery_date, o.delivery_timeslot,  o.payment_method,  (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id ) AS status,  o.order_status_id,o.store_name,  o.total,  o.date_added,o.paid,o.amount_partialy_paid,o.delivery_charges FROM `" . DB_PREFIX . 'order` o where o.order_id=' . $order_id;

        //   echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function UpdateOrderDeliveryDate($order_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET delivery_date= NOW() WHERE order_id="' . $order_id . '"');
    }

    public function updatePaymentStatus($order_id, $customer_id, $old_total, $final_amount) {

        if ($final_amount > $old_total) {
            //as of now, we are checking payment for wallet only
            //if cod no need to update
            //so placing payment method='wallet' condition,for mpesa online and other payment options only restrict order
            $this->db->query('update ' . DB_PREFIX . 'order SET paid="P",amount_partialy_paid ="' . $old_total . '" where  order_id = "' . $order_id . '" and paid="Y" and payment_code="wallet"');
        }
        // else if($final_amount<$old_total)//commented as multiple edits may add more amount to customer wallet
        // {
        //     $credit=(($old_total)-($final_amount));
        //     $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int)  $order_id . "', description = 'Wallet amount returned from order change', amount = '" . (float) $credit . "', date_added = NOW()");
        // }
    }

    public function getOrderedAcceptRejectProductsOnlyOrder($data = []) {
        $sql = "SELECT mp.id,o.firstname,o.lastname,cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.order_status_id,p.product_id,p.general_product_id,p.name,p.unit,p.quantity,p.price,p.total,p.tax,mp.status FROM `" . DB_PREFIX . 'order` o ';

        $sql .= ' INNER JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'order_product p on (o.order_id = p.order_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'accept_reject_products mp on (p.product_id = mp.product_store_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'  and p.order_id =mp.order_id ";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        if (isset($data['filter_accept_reject_status'])) {

            $sql .= ' AND mp.status= ' . $data['filter_accept_reject_status'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];
        // $sql .= " Group by o.order_id";
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo "<pre>";print_r($sql);die;
        $log = new Log('error.log');
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrderedAcceptRejectProducts($data = []) {
        $log = new Log('error.log');
        $log->write('Check For Accept reject Orders');
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` o ';

        $sql .= 'INNER JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id)';
        $sql .= 'INNER JOIN ' . DB_PREFIX . 'customer cust on(cust.customer_id = o.customer_id)';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'accept_reject_products p on (o.order_id = p.order_id) ';

        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (isset($data['filter_order_type'])) {
            $sql .= ' AND isadmin_login="' . $data['filter_order_type'] . '"';
        }

        if (isset($data['filter_accept_reject_status'])) {

            $sql .= ' AND p.status= ' . $data['filter_accept_reject_status'] . '';
        }

        //below if condition added for fast orders
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        }
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cust.account_manager_id="' . $this->user->getId() . '"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }


        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }


        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        //  echo "<pre>";print_r($data);die;
        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }
        //  echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getOrderedAcceptRejectProducts($data = []) {
        $sql = "SELECT mp.id,o.firstname,o.lastname,cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.order_status_id,p.product_id,p.general_product_id,p.name,p.unit,p.quantity,p.price,p.total,p.tax,mp.status FROM `" . DB_PREFIX . 'order` o ';

        $sql .= ' INNER JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'order_product p on (o.order_id = p.order_id) ';
        $sql .= ' INNER JOIN ' . DB_PREFIX . 'accept_reject_products mp on (p.product_id = mp.product_store_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'  and p.order_id =mp.order_id ";
        }

        //   echo "<pre>";print_r($data['filter_order_type']);die; 


        if (isset($data['filter_order_type'])) {

            $sql .= ' AND isadmin_login= ' . $data['filter_order_type'] . '';
        }

        if (isset($data['filter_accept_reject_status'])) {

            $sql .= ' AND mp.status= ' . $data['filter_accept_reject_status'] . '';
        }

        //below if condition added for fast orders used in fast order sreen 
        if (!empty($data['filter_order_day'])) {
            $current_date = date('Y-m-d');
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

            // fast orders means, ordered placed on current dadte
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($current_date) . "')";

            //echo "<pre>";print_r($delivery_date);die;
        }



        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_order_from_id'])) {
            $sql .= " AND o.order_id >= '" . (int) $data['filter_order_from_id'] . "'";
        }

        if (!empty($data['filter_order_to_id'])) {
            $sql .= " AND o.order_id <= '" . (int) $data['filter_order_to_id'] . "'";
        }


        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }
        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '" . $data['filter_store_name'] . "'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%" . $data['filter_delivery_method'] . "%'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= " AND DATE_FORMAT(o.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_order_status_id_not_in'])) {
            $sql .= " AND o.order_status_id NOT IN (" . $data['filter_order_status_id_not_in'] . ")";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];
        // $sql .= " Group by o.order_id";
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo "<pre>";print_r($sql);die;
        $log = new Log('error.log');
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function updatevendororderstatuss($order_id, $vendor_order_status_id) {
        $order_sql = 'UPDATE ' . DB_PREFIX . "order SET vendor_order_status_id = '" . $vendor_order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'";
        $order_query = $this->db->query($order_sql);
        return $order_query;
    }

    public function getVendorOrderStatus($vendor_order_status_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "vendor_order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' AND order_status_id = '" . (int) $vendor_order_status_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function updateordertotal($order_id, $total) {
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET total = '" . (int) $total . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
    }

    public function getCustomersNew($data = []) {
        $sql = "SELECT customer_id,CONCAT(c.firstname, ' ', c.lastname) as customer_name,company_name FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_payment_terms']) && !is_null($data['filter_payment_terms'])) {
            $implode[] = "c.payment_terms = '" . $data['filter_payment_terms'] . "'";
        }

        if (isset($data['filter_customer_id_array']) && !is_null($data['filter_customer_id_array'])) {
            $implode[] = "c.customer_id IN (" . $data['filter_customer_id_array'] . ")";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        $log = new Log('error.log');
        $log->write($sql);
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getUnpaidOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, o.vendor_order_status_id,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no,o.paid,o.amount_partialy_paid,o.delivery_charges,ot.value as order_total FROM `" . DB_PREFIX . 'order` o ';
        //    ('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.store_id,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value,o.amount_partialy_paid,o.paid FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id IN (4,5) AND o.paid IN ('N', 'P') AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC LIMIT " . (int) $start . ',' . (int) $limit);

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id)';

        $sql .= " WHERE o.order_status_id in(4,5) AND o.paid IN ('N', 'P') And ot.code = 'total' AND ot.title = 'Total'";

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " and cust.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_payment_terms']) && !is_null($data['filter_payment_terms'])) {
            $sql .= " and cust.payment_terms = '" . $data['filter_payment_terms'] . "'";
        }



        $sql .= ' ORDER BY cust.company_name,cust.firstname,o.order_id';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //   echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getNewOrderIdByMissingProductOrderId($order_id) {
        $new_order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "missing_products` WHERE order_id = '" . (int) $order_id . "' AND new_order_id > 0");
        return $new_order_query->row;
    }

    public function getMissingProductQuantityByProductIdOrderId($order_id, $product_id, $removed_from_invoice) {
        $new_order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "missing_products` WHERE order_id = '" . (int) $order_id . "' AND product_store_id = '" . (int) $product_id . "' AND removed_from_invoice = '" . (int) $removed_from_invoice . "'");
        return $new_order_query->row;
    }

    public function getMissingProductsByOrderId($order_id) {
        $new_order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "missing_products` WHERE order_id = '" . (int) $order_id . "'");
        return $new_order_query->rows;
    }

    public function getMissingProductsByOrderIdNew($order_id, $removed_from_invoice) {
        $new_order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "missing_products` WHERE order_id = '" . (int) $order_id . "' AND removed_from_invoice = '" . (int) $removed_from_invoice . "'");
        return $new_order_query->rows;
    }

    public function getProductCategoryByGeneralProductID($general_product_id) {
        $sql = "SELECT p.category_id,c.name as category FROM " . DB_PREFIX . "category_description c join " . DB_PREFIX . "product_to_category p on c.category_id =p.category_id and p.product_id = '" . (int) $general_product_id . "'";

        $query = $this->db->query($sql);
        // echo "<pre>";print_r( $query->rows);die;
        return $query->row;
    }

    public function getProductCategoryByProductID($product_id) {
        $sql = "SELECT p.category_id,c.name as category FROM " . DB_PREFIX . "category_description c join " . DB_PREFIX . "product_to_category p on c.category_id =p.category_id join hf7_product_to_store ps on ps.product_id =p.product_id and ps.product_store_id = '" . (int) $product_id . "'";

        $query = $this->db->query($sql);
        // echo "<pre>";print_r( $query->rows);die;
        return $query->row;
    }

}
