<?php

class ModelShippingExpress extends Model
{
    public function getQuote($cost = '', $name = '', $store_id = false)
    {
        $this->load->language('shipping/express');

        $status = true;

        $this->load->model('tool/image');

        if ($this->cart->getSubTotal() < $this->config->get('express_total')) {
            $status = false;
        }

        $settings = $this->getSettings('express', 0);

        if ($store_id) {
            $timeDiff = $settings['express_how_much_time'];

            $store_open_hours = $this->model_tool_image->getStoreOpenHours($store_id, date('w'))[0];

            if ($store_open_hours && isset($store_open_hours['timeslot'])) {
                $temp = explode('-', $store_open_hours['timeslot']);

                $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);

                if (!$is_enabled) {
                    $status = false;
                }
            } else {
                $status = false;
            }
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

    private function timeIsBetween($from, $to, $time, $time_diff = false)
    {
        //echo "time";print_r($from.$to.$time.$time_diff);die;
        $log = new Log('error.log');
        $log->write('time diff');
        $log->write($from.$to.$time.$time_diff);

        /* to calc */
        $to = trim($to);
        //calculate from_time in minuts
        $i = explode(':', $to);
        if (12 == $i[0]) {
            $to_min = substr($i[1], 0, 2);
        } else {
            $to_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours
        $am_pm = substr($to, -2);
        if ('pm' == $am_pm) {
            $to_min += 12 * 60;
        }

        //calculate time in minuts
        $i = explode(':', $time);
        if (12 == $i[0]) {
            $min = substr($i[1], 0, 2);
        } else {
            $min = $i[0] * 60 + substr($i[1], 0, 2);
        }

        //if pm add 12 hours
        $am_pm = substr($time, -2);
        if ('pm' == $am_pm) {
            $min += 12 * 60;
        }

        //if time difference
        if ($time_diff) {
            $i = explode(':', $time_diff);
            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
        }

        /* from calc*/

        $from = trim($from);
        //calculate from_time in minuts
        $i = explode(':', $from);
        if (12 == $i[0]) {
            $from_min = substr($i[1], 0, 2);
        } else {
            $from_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours
        $am_pm = substr($from, -2);
        if ('pm' == $am_pm) {
            $from_min += 12 * 60;
        }

        /*from calc end*/

        //echo "<pre>";print_r($min."cer".$to_min."from_min".$from_min);die;

        $log->write($min);
        $log->write($to_min);
        if ($from_min <= $min && $min <= $to_min) {
            return true;
        } else {
            return 0;
        }
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

    public function getShippingCharegApiQuote($cost = '', $name = '', $subtotal, $total, $shipping_address_id = '', $store_id = false)
    {
        $this->load->language('shipping/express');

        $status = true;

        if ($subtotal < $this->config->get('express_total')) {
            $status = false;
        }

        $this->load->model('tool/image');

        $settings = $this->getSettings('express', 0);

        if ($store_id) {
            $timeDiff = $settings['express_how_much_time'];

            $store_open_hours = $this->model_tool_image->getStoreOpenHours($store_id, date('w'));

            if ($store_open_hours && isset($store_open_hours['timeslot'])) {
                $temp = explode('-', $store_open_hours['timeslot']);

                $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);

                if (!$is_enabled) {
                    $status = false;
                }
            } else {
                $status = false;
            }
        }

        $cost = 0;
        $actual_cost = 0;
        $free_delivery = false;

        $method_data = [];

        if ($status) {
            $cost = $this->config->get('express_cost');
            $free_delivery_amount = $this->config->get('express_free_delivery_amount');

            //check total
            $total = $this->cart->getTotal();

            if ($total >= $free_delivery_amount) {
                //$cost = 0;
                $free_delivery = false;
            }

            $quote_data = [];

            // if on use delivery system shipping cost
            $settings = $this->getSettings('express', 0);

            $useDeliverySystem = $settings['express_use_deliverysystem'];

            if ($useDeliverySystem) {
                $data['delivery_priority'] = 'express';

                if (isset($shipping_address_id) && isset($store_id) && $store_id) {
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

            if ($free_delivery) {
                $actual_cost = $cost;
                $cost = 0;
            } else {
                $actual_cost = $cost;
            }

            //end

            $select_delivery_text = '';

            if (true) {
                $settings = $this->getSettings('express', 0);
                $timeDiff = $settings['express_how_much_time'];

                $min = 0;
                if ($timeDiff) {
                    $i = explode(':', $timeDiff);
                    $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                }

                $select_delivery_text = 'Within '.$min.' minutes';
            }

            $quote_data['express'] = [
                'code' => 'express.express',
                'title' => $this->language->get('text_description'),
                'title_with_store' => $this->language->get('text_description').'-'.$name,
                'cost' => (int) $cost,
                'actual_cost' => (int) $actual_cost,
                'tax_class_id' => 0,
                'text' => $this->currency->format($cost),
                'select_delivery_text' => $select_delivery_text,
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

    public function getPrice($store_id = false, $subtotal, $total, $shipping_address_id)
    {
        $this->load->language('shipping/express');

        $status = true;

        if ($subtotal < $this->config->get('express_total')) {
            $status = false;
        }

        $cost = 0;
        $actual_cost = 0;

        $method_data = [];

        if ($status) {
            $cost = $this->config->get('express_cost');
            $free_delivery_amount = $this->config->get('express_free_delivery_amount');

            //check total
            $total = $total;

            if ($total >= $free_delivery_amount) {
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

                if (isset($shipping_address_id) && isset($store_id) && $store_id) {
                    $shipping_address_id = $shipping_address_id;

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
        }

        $res['cost'] = $cost;
        $res['actual_cost'] = $actual_cost;
        $res['status'] = $status;

        return $res;
    }
}
