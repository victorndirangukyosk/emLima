<?php

class ModelShippingStoreDelivery extends Model
{
    public function getQuote($cost, $name, $store_id = false)
    {
        $log = new Log('error.log');
        $log->write('ModelShippingStoreDelivery');

        $this->load->language('shipping/store_delivery');

        $method_data = [];

        $quote_data = [];

        $actual_cost = 0;
        $free_delivery = false;
        $free_delivery_amount = 9999999;
        //check total
        $total = $this->cart->getTotal();

        //comment if u dont want use store free delviery amount
        if (isset($store_id) && $store_id) {
            $store_info = $this->model_tool_image->getStore($store_id);

            if ($store_info) {
                $free_delivery_amount = $store_info['min_order_cod'];
            }

            $total = $this->cart->getSubTotal($store_id);
        }

        if ($total >= $free_delivery_amount) {
            //$cost = 0;
            $free_delivery = true;
        }

        // if on use delivery system shipping cost
        $settings = $this->getSettings('store_delivery', 0);

        $useDeliverySystem = $settings['store_delivery_use_deliverysystem'];

        if ($useDeliverySystem) {
            $log->write('useDeliverySystem');

            $data['delivery_priority'] = 'normal';

            if (isset($this->session->data['shipping_address_id']) && isset($store_id) && $store_id) {
                $log->write('useDeliverySystem shipping_address_id if');

                $shipping_address_id = $this->session->data['shipping_address_id'];

                $this->load->model('account/address');
                $this->load->model('account/order');
                $this->load->model('tool/image');

                $shipping_address_data = $this->model_account_address->getAddress($shipping_address_id);

                //echo "<pre>";print_r($shipping_address_data);die;
                $data['dropoff_lat'] = $shipping_address_data['latitude'];
                $data['dropoff_lng'] = $shipping_address_data['longitude'];

                $data['latitude'] = $store_info['latitude'];
                $data['longitude'] = $store_info['longitude'];

                //get store city name
                $data['city'] = $this->model_account_order->getCityName($shipping_address_data['city_id']);

                $log->write($data);
                //$data['city'] = 'Brussels';
                //echo "<pre>";print_r($data);die;
                $response = $this->load->controller('deliversystem/deliversystem/getShippingPrice', $data);

                $log->write($response);
                //echo "<pre>";print_r($response);die;
                if ($response['status']) {
                    $cost = $response['data']->price + $response['data']->tax;
                } else {
                    //$cost = 5;
                }
            }
        }

        if ($free_delivery) {
            $actual_cost = $cost;
            $cost = 0;
        } else {
            $actual_cost = $cost;
        }

        $quote_data['store_delivery'] = [
            'code' => 'store_delivery.store_delivery',
            'title' => $this->language->get('text_description').'-'.$name,
            'title_with_store' => $this->language->get('text_description').'-'.$name,
            'cost' => $cost,
            'actual_cost' => $actual_cost,
            'tax_class_id' => $this->config->get('store_delivery_tax_class_id'),
            'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('store_delivery_tax_class_id'), $this->config->get('config_tax'))),
        ];

        $method_data = [
            'code' => 'store_delivery',
            'title' => $this->language->get('text_title'),
            'quote' => $quote_data,
            'sort_order' => $this->config->get('store_delivery_sort_order'),
            'error' => false,
        ];

        return $method_data;
    }

    public function getApiQuote($cost = '', $name = '', $subtotal, $total)
    {
        $this->load->language('shipping/store_delivery');

        $log = new Log('error.log');
        $log->write('ModelShippingStoreDelivery getApiQuote');

        $method_data = [];

        $quote_data = [];

        $free_delivery_amount = $this->config->get('normal_free_delivery_amount');

        //check total
        $total = $total;

        if ($total >= $free_delivery_amount) {
            $cost = 0;
        }

        $quote_data['store_delivery'] = [
            'code' => 'store_delivery.store_delivery',
            'title' => $this->language->get('text_description').'-'.$name,
            'title_with_store' => $this->language->get('text_description').'-'.$name,
            'cost' => $cost,
            'actual_cost' => $cost,
            'tax_class_id' => $this->config->get('store_delivery_tax_class_id'),
            'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('store_delivery_tax_class_id'), $this->config->get('config_tax'))),
        ];

        $method_data = [
            'code' => 'store_delivery',
            'title' => $this->language->get('text_title'),
            'quote' => $quote_data,
            'sort_order' => $this->config->get('store_delivery_sort_order'),
            'error' => false,
        ];

        return $method_data;
    }

    public function getShippingCharegApiQuote($cost = '', $name = '', $subtotal, $total, $shipping_address_id = '', $store_id = false, $getParam = [])
    {
        $log = new Log('error.log');
        $log->write('ModelShippingStoreDelivery getShippingCharegApiQuote');

        $this->load->language('shipping/store_delivery');

        $method_data = [];

        $quote_data = [];

        $free_delivery_amount = 9999999;

        $actual_cost = 0;
        $free_delivery = false;

        //comment if u dont want use store free delviery amount
        if (isset($store_id) && $store_id) {
            $store_info = $this->model_tool_image->getStore($store_id);

            if ($store_info) {
                $free_delivery_amount = $store_info['min_order_cod'];
            }
        }

        if ($total >= $free_delivery_amount) {
            //$cost = 0;
            $free_delivery = true;
        }

        // if on use delivery system shipping cost
        $settings = $this->getSettings('store_delivery', 0);

        $useDeliverySystem = $settings['store_delivery_use_deliverysystem'];

        if ($useDeliverySystem) {
            $log->write('useDeliverySystem');

            $data['delivery_priority'] = 'normal';

            if (isset($this->session->data['shipping_address_id']) && isset($store_id) && $store_id) {
                $log->write('useDeliverySystem shipping_address_id if');

                $shipping_address_id = $this->session->data['shipping_address_id'];

                $this->load->model('account/address');
                $this->load->model('account/order');
                $this->load->model('tool/image');

                $shipping_address_data = $this->model_account_address->getAddress($shipping_address_id);

                //echo "<pre>";print_r($shipping_address_data);die;
                $data['dropoff_lat'] = $shipping_address_data['latitude'];
                $data['dropoff_lng'] = $shipping_address_data['longitude'];

                $data['latitude'] = $store_info['latitude'];
                $data['longitude'] = $store_info['longitude'];

                //get store city name
                $data['city'] = $this->model_account_order->getCityName($shipping_address_data['city_id']);

                $log->write($data);
                //$data['city'] = 'Brussels';
                //echo "<pre>";print_r($data);die;
                $response = $this->load->controller('deliversystem/deliversystem/getShippingPrice', $data);

                $log->write($response);
                //echo "<pre>";print_r($response);die;
                if ($response['status']) {
                    $cost = $response['data']->price + $response['data']->tax;
                } else {
                    //$cost = 5;
                }
            }
        }

        if ($free_delivery) {
            $actual_cost = $cost;
            $cost = 0;
        } else {
            $actual_cost = $cost;
        }

        $quote_data['store_delivery'] = [
            'code' => 'store_delivery.store_delivery',
            'title' => $this->language->get('text_description').'-'.$name,
            'title_with_store' => $this->language->get('text_description').'-'.$name,
            'cost' => $cost,
            'actual_cost' => $actual_cost,
            'tax_class_id' => $this->config->get('store_delivery_tax_class_id'),
            'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('store_delivery_tax_class_id'), $this->config->get('config_tax'))),
        ];

        $method_data = [
            'code' => 'store_delivery',
            'title' => $this->language->get('text_title'),
            'quote' => $quote_data,
            'sort_order' => $this->config->get('store_delivery_sort_order'),
            'error' => false,
        ];

        return $method_data;
    }

    public function getSettings($code, $store_id = 0)
    {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }

    public function getPrice($store_id = false, $subtotal, $total, $latitude, $longitude, $order_city_id = '', $weight = 0)
    {
        $log = new Log('error.log');
        $log->write('ModelShippingNormal getPrice');

        $log->write($weight);

        $this->load->model('tool/image');
        $this->load->language('shipping/normal');

        $status = true;

        /*if ($subtotal < $this->config->get('normal_total')) {
            $status = false;
        }*/

        $method_data = [];
        $actual_cost = 0;
        $cost = 0;

        if ($status) {
            $free_delivery = false;
            //$cost = $this->config->get('normal_cost');
            $free_delivery_amount = 9999999;

            //comment if u dont want use store free delviery amount
            if (isset($store_id) && $store_id) {
                $store_info = $this->model_tool_image->getStore($store_id);

                if ($store_info) {
                    $free_delivery_amount = $store_info['min_order_cod'];

                    $cost = $store_info['cost_of_delivery'];
                }
            }

            $log->write($cost);

            //check total
            $total = $total;

            if ($total >= $free_delivery_amount) {
                //$cost = 0;
                $free_delivery = true;
            }

            $useDeliverySystem = $this->config->get('store_delivery_use_deliverysystem');
            //$useDeliverySystem = false;

            //if($useDeliverySystem && false) {
            if ($useDeliverySystem) {
                $log->write('useDeliverySystem');

                $data['delivery_priority'] = 'normal';

                if (isset($latitude) && isset($longitude) && isset($store_id) && $store_id) {
                    $log->write('useDeliverySystem shipping_address_id if');

                    $this->load->model('account/order');
                    $this->load->model('tool/image');

                    //echo "<pre>";print_r($shipping_address_data);die;
                    $data['dropoff_lat'] = $latitude;
                    $data['dropoff_lng'] = $longitude;

                    $data['latitude'] = $store_info['latitude'];
                    $data['longitude'] = $store_info['longitude'];

                    $data['weight'] = $weight;

                    //get store city name
                    $data['city'] = $this->model_account_order->getCityName($order_city_id);

                    $log->write($data);
                    //$data['city'] = 'Brussels';
                    //echo "<pre>";print_r($data);die;
                    $response = $this->load->controller('deliversystem/deliversystem/getShippingPrice', $data);

                    $log->write('getShippingPrice response');

                    $log->write($response);
                    //echo "<pre>";print_r($response);die;
                    if ($response['status']) {
                        $cost = $response['data']->price + $response['data']->tax;
                    }
                }
            }
            //end

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
