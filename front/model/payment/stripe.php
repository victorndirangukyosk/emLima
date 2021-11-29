<?php

class ModelPaymentStripe extends Model
{
    //public function getMethod($address, $total) {
    public function getMethod($total)
    {
        $this->load->language('payment/stripe');

        $status = true;

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'stripe',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('stripe_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $stripe_charge_id, $environment = 'test')
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."stripe_order` SET `order_id` = '".(int) $order_info['order_id']."', `stripe_order_id` = '".$stripe_charge_id."', `environment` = '".$environment."'");

        return $this->db->getLastId();
    }

    public function getCustomer($customer_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."stripe_customer` WHERE `customer_id` = '".$customer_id."' LIMIT 1");

        if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    public function getCards($customer_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."stripe_card` WHERE `customer_id` = '".$customer_id."'");

        if ($query->num_rows) {
            return $query;
        } else {
            return false;
        }
    }

    public function addCustomer($stripe_customer, $customer_id, $environment = 'test')
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."stripe_customer` SET `customer_id` = '".(int) $customer_id."', `stripe_customer_id` = '".$stripe_customer['id']."', `environment` = '".$environment."'");

        return $this->db->getLastId();
    }

    public function addCard($stripe_card, $customer_id, $environment = 'test')
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."stripe_card` SET `customer_id` = '".(int) $customer_id."', `stripe_card_id` = '".$stripe_card['id']."', `environment` = '".$environment."', `last_four` = '".$stripe_card['last4']."', `brand` = '".$stripe_card['brand']."', `exp_year` = '".$stripe_card['exp_year']."', `exp_month` = '".$stripe_card['exp_month']."'");

        return $this->db->getLastId();
    }

    public function addVendorStripeAccount($data)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."stripe_vendor` WHERE `stripe_user_id` = '".$data['stripe_user_id']."' LIMIT 1");

        if ($query->num_rows) {
            return false;
        } else {
            $this->db->query('INSERT INTO `'.DB_PREFIX."stripe_vendor` SET `vendor_id` = '".(int) $data['vendor_id']."', `stripe_user_id` = '".$data['stripe_user_id']."', `refresh_token` = '".$data['refresh_token']."', `stripe_publishable_key` = '".$data['stripe_publishable_key']."', `access_token` = '".$data['access_token']."'");

            return true;
        }
    }

    public function getVendorStripeAccount($vendor_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."stripe_vendor` WHERE `vendor_id` = '".$vendor_id."' LIMIT 1");

        if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    public function deleteVendorStripeAccount($vendor_id)
    {
        $query = $this->db->query('DELETE FROM `'.DB_PREFIX."stripe_vendor` WHERE `vendor_id` = '".$vendor_id."' LIMIT 1");

        return true;
    }
}
