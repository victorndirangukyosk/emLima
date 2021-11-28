<?php

static $registry = null;

class Modeltoolcleardata extends Model
{
    public function deleteCustomers()
    {
        //echo "cu";die;
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."customer`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_activity`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_ban_ip`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_credit`;\n";

        /*$sql .= "TRUNCATE TABLE `" . DB_PREFIX . "customer_group`;\n";
        $sql .= "TRUNCATE TABLE `" . DB_PREFIX . "customer_group_description`;\n";*/

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_history`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_ip`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_login`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_online`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_reward`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."customer_ip`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."address`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."wishlist`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."wishlist_products`;\n";

        $this->multiquery($sql);
    }

    public function deleteOrders()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."order`;\n";
        //$sql .= "TRUNCATE TABLE `" . DB_PREFIX . "order_custom_field`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_fraud`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_history`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_iugu`;\n";
        //$sql .= "TRUNCATE TABLE `" . DB_PREFIX . "order_option`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_product`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."real_order_product`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_recurring`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_recurring_transaction`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_request`;\n";
        //$sql .= "TRUNCATE TABLE `" . DB_PREFIX . "order_status`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_total`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_voucher`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."paypal_order_transaction`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."shopper_order_log`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."realex_order_transaction`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."realex_remote_order_transaction`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."return`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."return_history`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."order_transaction_id`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."transaction_details`;\n";

        $this->multiquery($sql);
    }

    public function deleteStores()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."simple_blog_article_to_store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."simple_blog_category_to_store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."store_delivery_timeslot`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."store_pickup_timeslot`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."store_zipcodes`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."variation_to_product_store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_to_store`;\n";

        //$sql .= "TRUNCATE TABLE `" . DB_PREFIX . "information_to_store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."manufacturer_to_store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."menu_child_to_store`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."menu_to_store`;\n";

        $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` LIKE 'store_id=%';\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."product_to_store`;\n";
        $this->multiquery($sql);

        $sql = 'SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `'.DB_PREFIX.'url_alias` LIMIT 1';
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = 'ALTER TABLE `'.DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
    }

    public function deleteVendors()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."vendor_to_package`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."vendor_wallet`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."vendor_notifications`;\n";        //11

        $sql .= 'DELETE FROM `'.DB_PREFIX.'user` WHERE `user_group_id` IN ('.$this->config->get('config_vendor_group_ids').") ;\n";
        $this->multiquery($sql);
    }

    public function deleteMiscellaneous()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."admin_wallet`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."otp`;\n";

        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."coupon`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."coupon_category`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."coupon_history`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."coupon_product`;\n";

        $this->multiquery($sql);
    }

    public function deleteOrderTransactions()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."paypal_order_transaction`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."realex_order_transaction`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."realex_remote_order_transaction`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."mpesa_order`;\n";

        $this->multiquery($sql);
    }

    public function deleteProducts()
    {
        $sql = 'TRUNCATE  `'.DB_PREFIX."product`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_variation`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_description`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_special`;\n";
        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_reward`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_to_layout`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_tag`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_to_category`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_to_category`;\n";
        //$sql .= "DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id IN (" . $product_ids . ");\n";

        $sql .= 'TRUNCATE `'.DB_PREFIX."product_image`;\n";

        $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` LIKE 'product_id=%';\n";

        //$sql .= "DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'category_id=%';\n";

        $sql .= 'TRUNCATE `'.DB_PREFIX."product_related`;\n";

        $this->multiquery($sql);
        $sql = 'SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `'.DB_PREFIX.'url_alias` LIMIT 1';
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = 'ALTER TABLE `'.DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
        /*$remove = array();
        foreach ( $url_alias_ids as $product_id => $url_alias_id ) {
            if ( $url_alias_id >= $next_url_alias_id ) {
                $remove[$product_id] = $url_alias_id;
            }
        }
        foreach ( $remove as $product_id => $url_alias_id ) {
            unset( $url_alias_ids[$product_id] );
        }*/
    }

    public function deleteCategories()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."category`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_description`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_to_store`;\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` LIKE 'category_id=%';\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_to_layout`;\n";
        $this->multiquery($sql);
        $sql = 'SHOW TABLES LIKE "'.DB_PREFIX.'category_path"';
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $sql = 'TRUNCATE TABLE `'.DB_PREFIX.'category_path`';
            $this->db->query($sql);
        }
        $sql = 'SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `'.DB_PREFIX.'url_alias` LIMIT 1';
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = 'ALTER TABLE `'.DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
    }

    protected function multiquery($sql)
    {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);

            //echo $sql;
            if ($sql) {
                $this->db->query($sql);
            }
        }// die;
    }

    public function loadSql($file)
    {
        if (file_exists(DIR_UPLOAD.$file.'.sql')) {
            $commands = file_get_contents(DIR_UPLOAD.$file.'.sql');
            $x = $this->db->query($commands);

            //echo "cd";print_r($x);print_r($commands);die;
            return true;
        }

        return false;
    }
}
