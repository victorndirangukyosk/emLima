<?php

class ModelCheckoutCoupon extends Model
{
    public function getCoupon($code)
    {
        $status = true;

        $log = new Log('error.log');

        $coupon_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."coupon` WHERE code = '".$this->db->escape($code)."' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

        if ($coupon_query->num_rows) {
            $log->write('PayPal Express debug codes 1');
            if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
                $log->write('PayPal Express debug codes 1');
                $status = false;
            }

            //echo "<pre>";print_r($coupon_query);die;
            $coupon_history_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."coupon_history` ch WHERE ch.coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
                $log->write('PayPal Express debug codes 2');
                $status = false;
            }

            $log->write(' codes 3');

            if ($coupon_query->row['logged'] && !$this->customer->getId()) {
                $log->write(' codes 3.1'.$coupon_query->row['logged'].'hh'.$this->customer->getId());
                $status = false;
            }

            if ($this->customer->getId()) {
                $coupon_history_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."coupon_history` ch WHERE ch.coupon_id = '".(int) $coupon_query->row['coupon_id']."' AND ch.customer_id = '".(int) $this->customer->getId()."'");

                if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
                    $status = false;
                }
            }

            $log->write('PayPal Express debug codes 4');

            // Products
            $coupon_product_data = [];

            $coupon_product_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."coupon_product` WHERE coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            foreach ($coupon_product_query->rows as $product) {
                $coupon_product_data[] = $product['product_id'];
            }

            $log->write('PayPal Express debug codes 5');

            // Categories
            $coupon_category_data = [];

            $coupon_category_query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'coupon_category` cc LEFT JOIN `'.DB_PREFIX."category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            foreach ($coupon_category_query->rows as $category) {
                $coupon_category_data[] = $category['category_id'];
            }

            $product_data = [];

            $log->write('PayPal Express debug codes 6');

            //echo "<pre>";print_r($coupon_product_data);die;
            if ($coupon_product_data || $coupon_category_data) {
                $log->write('PayPal Express debug codes 7');

                foreach ($this->cart->getProducts() as $product) {
                    //echo "<pre>";print_r($product);die;
                    if (in_array($product['product_id'], $coupon_product_data)) {
                        $product_data[] = $product['product_id'];

                        continue;
                    }

                    foreach ($coupon_category_data as $category_id) {
                        $coupon_category_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."product_to_category` WHERE `product_id` = '".(int) $product['product_id']."' AND category_id = '".(int) $category_id."'");

                        if ($coupon_category_query->row['total']) {
                            $product_data[] = $product['product_id'];

                            continue;
                        }
                    }
                }

                $log->write('PayPal Express debug codes 8');

                if (!$product_data) {
                    $log->write('PayPal Express debug codes 9');
                    $status = false;
                }
            }
        } else {
            $status = false;
        }

        if ($status) {
            return [
                'coupon_id' => $coupon_query->row['coupon_id'],
                'code' => $coupon_query->row['code'],
                'name' => $coupon_query->row['name'],
                'type' => $coupon_query->row['type'],
                'discount' => $coupon_query->row['discount'],
                'shipping' => $coupon_query->row['shipping'],
                'total' => $coupon_query->row['total'],
                'product' => $product_data,
                'date_start' => $coupon_query->row['date_start'],
                'date_end' => $coupon_query->row['date_end'],
                'uses_total' => $coupon_query->row['uses_total'],
                'uses_customer' => $coupon_query->row['uses_customer'],
                'status' => $coupon_query->row['status'],
                'date_added' => $coupon_query->row['date_added'],
                'coupon_type' => $coupon_query->row['coupon_type'],
            ];
        }
    }

    public function apiGetCoupon($code, $total)
    {
        $status = true;

        $log = new Log('error.log');

        $coupon_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."coupon` WHERE code = '".$this->db->escape($code)."' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

        if ($coupon_query->num_rows) {
            $log->write('PayPal Express debug codes 1');
            if ($coupon_query->row['total'] > $total) {
                //echo "<pre>";print_r("rgr");die;
                $log->write('PayPal Express debug codes 1');
                $status = false;
            }

            //echo "<pre>";print_r($coupon_query);die;
            $coupon_history_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."coupon_history` ch WHERE ch.coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
                $log->write('PayPal Express debug codes 2');
                $status = false;
            }

            $log->write(' codes 3');

            if ($coupon_query->row['logged'] && !$this->customer->getId()) {
                $log->write(' codes 3.1'.$coupon_query->row['logged'].'hh'.$this->customer->getId());
                $status = false;
            }

            if ($this->customer->getId()) {
                $coupon_history_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."coupon_history` ch WHERE ch.coupon_id = '".(int) $coupon_query->row['coupon_id']."' AND ch.customer_id = '".(int) $this->customer->getId()."'");

                if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
                    $status = false;
                }
            }

            $log->write('PayPal Express debug codes 4');

            // Products
            $coupon_product_data = [];

            $coupon_product_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."coupon_product` WHERE coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            foreach ($coupon_product_query->rows as $product) {
                $coupon_product_data[] = $product['product_id'];
            }

            $log->write('PayPal Express debug codes 5');

            // Categories
            $coupon_category_data = [];

            $coupon_category_query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'coupon_category` cc LEFT JOIN `'.DB_PREFIX."category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            foreach ($coupon_category_query->rows as $category) {
                $coupon_category_data[] = $category['category_id'];
            }

            $product_data = [];

            $log->write('PayPal Express debug codes 6');

            //echo "<pre>";print_r($coupon_product_data);die;
            if ($coupon_product_data || $coupon_category_data) {
                $log->write('PayPal Express debug codes 7');

                foreach ($this->cart->getProducts() as $product) {
                    //echo "<pre>";print_r($product);die;
                    if (in_array($product['product_id'], $coupon_product_data)) {
                        $product_data[] = $product['product_id'];

                        continue;
                    }

                    foreach ($coupon_category_data as $category_id) {
                        $coupon_category_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."product_to_category` WHERE `product_id` = '".(int) $product['product_id']."' AND category_id = '".(int) $category_id."'");

                        if ($coupon_category_query->row['total']) {
                            $product_data[] = $product['product_id'];

                            continue;
                        }
                    }
                }

                $log->write('PayPal Express debug codes 8');

                if (!$product_data) {
                    $log->write('PayPal Express debug codes 9');
                    $status = false;
                }
            }
        } else {
            $status = false;
        }

        if ($status) {
            return [
                'coupon_id' => $coupon_query->row['coupon_id'],
                'code' => $coupon_query->row['code'],
                'name' => $coupon_query->row['name'],
                'type' => $coupon_query->row['type'],
                'discount' => $coupon_query->row['discount'],
                'shipping' => $coupon_query->row['shipping'],
                'total' => $coupon_query->row['total'],
                'product' => $product_data,
                'date_start' => $coupon_query->row['date_start'],
                'date_end' => $coupon_query->row['date_end'],
                'uses_total' => $coupon_query->row['uses_total'],
                'uses_customer' => $coupon_query->row['uses_customer'],
                'status' => $coupon_query->row['status'],
                'date_added' => $coupon_query->row['date_added'],
                'coupon_type' => $coupon_query->row['coupon_type'],
            ];
        }
    }

    public function adminGetCoupon($code)
    {
        $status = true;

        $log = new Log('error.log');

        $coupon_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."coupon` WHERE code = '".$this->db->escape($code)."' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

        if ($coupon_query->num_rows) {
            /*if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
                $log->write('PayPal Express debug codes 1');
                $status = false;
            }*/

            $coupon_history_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."coupon_history` ch WHERE ch.coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
                $log->write('PayPal Express debug codes 2');
                $status = false;
            }

            $log->write('PayPal Express debug codes 4');

            // Products
            $coupon_product_data = [];

            $coupon_product_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."coupon_product` WHERE coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            foreach ($coupon_product_query->rows as $product) {
                $coupon_product_data[] = $product['product_id'];
            }

            $log->write('PayPal Express debug codes 5');

            // Categories
            $coupon_category_data = [];

            $coupon_category_query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'coupon_category` cc LEFT JOIN `'.DB_PREFIX."category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '".(int) $coupon_query->row['coupon_id']."'");

            foreach ($coupon_category_query->rows as $category) {
                $coupon_category_data[] = $category['category_id'];
            }

            $product_data = [];

            $log->write('PayPal Express debug codes 6');

            //echo "<pre>";print_r($coupon_product_data);die;
            if ($coupon_product_data || $coupon_category_data) {
                $log->write('PayPal Express debug codes 7');

                foreach ($this->cart->getProducts() as $product) {
                    //echo "<pre>";print_r($product);die;
                    if (in_array($product['product_id'], $coupon_product_data)) {
                        $product_data[] = $product['product_id'];

                        continue;
                    }

                    foreach ($coupon_category_data as $category_id) {
                        $coupon_category_query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."product_to_category` WHERE `product_id` = '".(int) $product['product_id']."' AND category_id = '".(int) $category_id."'");

                        if ($coupon_category_query->row['total']) {
                            $product_data[] = $product['product_id'];

                            continue;
                        }
                    }
                }

                $log->write('PayPal Express debug codes 8');

                if (!$product_data) {
                    $log->write('PayPal Express debug codes 9');
                    $status = false;
                }
            }
        } else {
            $status = false;
        }

        if ($status) {
            return [
                'coupon_id' => $coupon_query->row['coupon_id'],
                'code' => $coupon_query->row['code'],
                'name' => $coupon_query->row['name'],
                'type' => $coupon_query->row['type'],
                'discount' => $coupon_query->row['discount'],
                'shipping' => $coupon_query->row['shipping'],
                'total' => $coupon_query->row['total'],
                'product' => $product_data,
                'date_start' => $coupon_query->row['date_start'],
                'date_end' => $coupon_query->row['date_end'],
                'uses_total' => $coupon_query->row['uses_total'],
                'uses_customer' => $coupon_query->row['uses_customer'],
                'status' => $coupon_query->row['status'],
                'date_added' => $coupon_query->row['date_added'],
                'coupon_type' => $coupon_query->row['coupon_type'],
            ];
        }
    }
}
