<?php

class ModelShippingExpress extends Model
{
    public function getQuote($cost = '', $name = '', $store_id = false)
    {
        $this->load->language('shipping/express');

        $status = true;

        if ($this->cart->getSubTotal() < $this->config->get('express_total')) {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $cost = $this->config->get('express_cost');
            $free_delivery_amount = $this->config->get('express_free_delivery_amount');

            //check total
            $total = $this->cart->getTotal();

            if ($total >= $free_delivery_amount) {
                $cost = 0;
            }

            //check membership
            $member_upto = $this->customer->getMemberUpto();
            $member_group_id = $this->config->get('config_member_group_id');
            $customer_group_id = $this->customer->getGroupId();

            if (strtotime($member_upto) > time() && $member_group_id == $customer_group_id) {
                $cost = 0;
            }

            $quote_data = [];

            // if on use delivery system shipping cost
            $settings = $this->getSettings('express', 0);

            $useDeliverySystem = $settings['express_use_deliverysystem'];

            if ($useDeliverySystem) {
                /*$data['dropoff_lat'] = 12.916188;
                $data['dropoff_lng'] = 77.605405;

                $data['latitude'] = 12.918329;
                $data['longitude'] = 77.601821;

                $data['city'] = 'Brussels'; */
                $data['delivery_priority'] = 'express';

                if (isset($this->session->data['shipping_address_id']) && isset($store_id) && $store_id) {
                    $shipping_address_id = $this->session->data['shipping_address_id'];

                    $this->load->model('account/address');
                    $this->load->model('account/order');
                    $this->load->model('tool/image');

                    $shipping_address_data = $this->model_account_address->getAddress($shipping_address_id);

                    //echo "<pre>";print_r($shipping_address_data);die;
                    $data['dropoff_lat'] = $shipping_address_data['latitude'];
                    $data['dropoff_lng'] = $shipping_address_data['longitude'];

                    $store_info = $this->model_tool_image->getStore($store_id);

                    $data['latitude'] = $store_info['latitude'];
                    $data['longitude'] = $store_info['longitude'];

                    //get store city name
                    $data['city'] = $this->model_account_order->getCityName($shipping_address_data['city_id']);

                    //$data['city'] = 'Brussels';
                    //echo "<pre>";print_r($data);die;
                    $response = $this->load->controller('deliversystem/deliversystem/getShippingPrice', $data);

                    //echo "<pre>";print_r($response);die;
                    if ($response['status']) {
                        $cost = $response['data']->price + $response['data']->tax;
                    }
                }
            }
            //end

            $quote_data['express'] = [
                'code' => 'express.express',
                'title' => $this->language->get('text_description'),
                'title_with_store' => $this->language->get('text_description').'-'.$name,
                'cost' => $cost,
                'actual_cost' => $cost,
                'tax_class_id' => 0,
                'text' => $this->currency->format($cost),
            ];

            $method_data = [
                'code' => 'express',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('express_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }

    public function getApiQuote($cost = '', $name = '', $subtotal, $total)
    {
        $this->load->language('shipping/express');

        $status = true;

        if ($subtotal < $this->config->get('express_total')) {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $cost = $this->config->get('express_cost');
            $free_delivery_amount = $this->config->get('express_free_delivery_amount');

            //check total
            $total = $total;

            if ($total >= $free_delivery_amount) {
                $cost = 0;
            }

            //check membership
            $member_upto = $this->customer->getMemberUpto();
            $member_group_id = $this->config->get('config_member_group_id');
            $customer_group_id = $this->customer->getGroupId();

            if (strtotime($member_upto) > time() && $member_group_id == $customer_group_id) {
                $cost = 0;
            }

            $quote_data = [];

            /*$p = '';

            if($name != '') {
                $p ='-'.$name;
            }*/

            $quote_data['express'] = [
                'code' => 'express.express',
                'title' => $this->language->get('text_description'),
                'title_with_store' => $this->language->get('text_description').'-'.$name,
                'cost' => $cost,
                'actual_cost' => $cost,
                'tax_class_id' => 0,
                'text' => $this->currency->format($cost),
            ];

            $method_data = [
                'code' => 'express',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('express_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }

    public function getSettings($code, $store_id = 0)
    {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }

    public function getPrice($store_id = false, $subtotal, $total, $latitude, $longitude, $order_city_id = '')
    {
        $this->load->language('shipping/express');

        $status = true;

        /*if ($subtotal < $this->config->get('express_total')) {
            $status = false;
        }*/

        $this->load->model('tool/image');

        $cost = 0;
        $actual_cost = 0;
        $free_delivery = false;

        $method_data = [];

        if ($status) {
            $cost = $this->config->get('express_cost');
            $free_delivery_amount = $this->config->get('express_free_delivery_amount');

            //check total
            $total = $total;

            if ($total >= $free_delivery_amount) {
                $free_delivery = true;
            }

            $quote_data = [];

            // if on use delivery system shipping cost
            $settings = $this->getSettings('express', 0);

            $useDeliverySystem = $settings['express_use_deliverysystem'];

            if ($useDeliverySystem) {
                /*$data['dropoff_lat'] = 12.916188;
                $data['dropoff_lng'] = 77.605405;

                $data['latitude'] = 12.918329;
                $data['longitude'] = 77.601821;

                $data['city'] = 'Brussels'; */
                $data['delivery_priority'] = 'express';

                if (isset($latitude) && isset($longitude) && isset($store_id) && $store_id) {
                    $this->load->model('account/order');
                    $this->load->model('tool/image');

                    $data['dropoff_lat'] = $latitude;
                    $data['dropoff_lng'] = $longitude;

                    $store_info = $this->model_tool_image->getStore($store_id);

                    $data['latitude'] = $store_info['latitude'];
                    $data['longitude'] = $store_info['longitude'];

                    //get store city name
                    $data['city'] = $this->model_account_order->getCityName($order_city_id);

                    //$data['city'] = 'Brussels';
                    //echo "<pre>";print_r($data);die;
                    $response = $this->load->controller('deliversystem/deliversystem/getShippingPrice', $data);

                    //echo "<pre>";print_r($response);die;
                    if ($response['status']) {
                        $cost = $response['data']->price + $response['data']->tax;
                    }
                }
            }

            if ($free_delivery) {
                $actual_cost = $cost;
                $cost = 0;
            } else {
                $actual_cost = $cost;
            }
        }

        $res['cost'] = $cost;
        $res['actual_cost'] = $actual_cost;
        $res['status'] = $status;

        return $res;
    }
}
