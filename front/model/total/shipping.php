<?php

class ModelTotalShipping extends Model {

    public function getTotal(&$total_data, &$total, &$taxes, $store_id = '') {
        $log = new Log('error.log');
        $log->write('Shipping 1');

        // if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
        //     //print_r("in if");
        //     $log->write('Shipping 2');
        //     if ($store_id) {
        //         $log->write('Shipping 3');
        //         if ($this->session->data['shipping_method']) {
        //             $log->write('Shipping 3.1');
        //             foreach ($this->session->data['shipping_method'] as $data) {
        //                 if (isset($data['store_id'])) {
        //                     if ($data['store_id'] == $store_id) {
        //                         $total_data[] = [
        //                             'code' => 'shipping',
        //                             'title' => $data['shipping_method']['title'],
        //                             //'title' => $data['shipping_method']['title_with_store'],
        //                             'value' => $data['shipping_method']['cost'],
        //                             'sort_order' => $this->config->get('shipping_sort_order'),
        //                         ];
        //                     }
        //                 }
        //             }
        //         }
        //         $totalcost = 0;
        //         if (isset($this->session->data['shipping_method'])) {
        //             foreach ($this->session->data['shipping_method'] as $key => $value) {
        //                 //print_r($value);die;
        //                 if (isset($value['store_id'])) {
        //                     if ($store_id == $value['store_id']) {
        //                         $tax_rates = $this->tax->getRates($value['shipping_method']['cost'], $value['shipping_method']['tax_class_id']);
        //                         foreach ($tax_rates as $tax_rate) {
        //                             if (!isset($taxes[$tax_rate['tax_rate_id']])) {
        //                                 $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
        //                             } else {
        //                                 $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
        //                             }
        //                         }
        //                         $totalcost += $value['shipping_method']['cost'];
        //                     }
        //                 }
        //             }
        //         }
        //         $total += $totalcost;
        //     } else {
        //         $log->write('Shipping 4');
        //         if ($this->session->data['shipping_method']) {
        //             foreach ($this->session->data['shipping_method'] as $key => $value) {
        //                 $total_data[] = [
        //                     'code' => 'shipping',
        //                     'title' => $value['shipping_method']['title'],
        //                     //'title' => $value['shipping_method']['title_with_store'],
        //                     'value' => $value['shipping_method']['cost'],
        //                     'sort_order' => $this->config->get('shipping_sort_order'),
        //                 ];
        //             }
        //         }
        //         //echo "<pre>";print_r("Rve");die;
        //         $totalcost = 0;
        //         if ($this->session->data['shipping_method']) {
        //             foreach ($this->session->data['shipping_method'] as $key => $value) {
        //                 $tax_rates = $this->tax->getRates($value['shipping_method']['cost'], $value['shipping_method']['tax_class_id']);
        //                 foreach ($tax_rates as $tax_rate) {
        //                     if (!isset($taxes[$tax_rate['tax_rate_id']])) {
        //                         $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
        //                     } else {
        //                         $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
        //                     }
        //                 }
        //                 $totalcost += $value['shipping_method']['cost'];
        //             }
        //         }
        //         $total += $totalcost;
        //     }
        // } else {
        //     $log->write('Shipping 6');
        //     //print_r("else");
        // }


        if ($this->cart->getSubTotal() <= $this->config->get('config_active_store_minimum_order_amount') && ($store_id == 75 || $store_id == -1 || $store_id == '') && !isset($this->session->data['add_delivery_charges'])) {
            //$shipping_charge = $this->config->get('config_active_store_delivery_charge') ?? 0;

            $shipping_charge = $this->config->get('config_active_store_delivery_charge') > 0 ? $this->config->get('config_active_store_delivery_charge') : 0;
            if (isset($this->session->data['adminlogin']) && $this->session->data['adminlogin'] && isset($this->session->data['add_delivery_charges'])) {
                $shipping_charge = isset($this->session->data['add_delivery_charges']) && $this->session->data['add_delivery_charges'] == 'true' ? $shipping_charge : 0;
            }
            // echo "<pre>";print_r($shipping_charge);die;

            $shipping_charge_VAT = 0;

            $total_data[] = [
                'code' => 'shipping',
                'title' => 'Standard Delivery',
                'value' => $shipping_charge,
                'sort_order' => $this->config->get('shipping_sort_order'),
            ];
            if ($this->config->get('config_active_store_delivery_charge_vat')>0 ) {
                $shipping_charge_VAT = ($shipping_charge * ($this->config->get('config_active_store_delivery_charge_vat')/100));

            $total_data[] = [
                'code' => 'delivery_vat',
                'title' => 'VAT on Standard Delivery',
                'value' => $shipping_charge_VAT,
                'sort_order' => $this->config->get('shipping_sort_order') + 1,
            ];
        }

            // echo "<pre>";print_r($total_data);die;

            $total += $shipping_charge + $shipping_charge_VAT;
        } elseif (isset($this->session->data['add_delivery_charges']) && $this->session->data['add_delivery_charges'] != NULL) {
            $log = new Log('error.log');
            $log->write('Shipping 2');
            if (isset($this->session->data['delivery_charges_value']) && $this->session->data['delivery_charges_value'] != NULL) {
            $shipping_charge = $this->session->data['delivery_charges_value'] ;
            }
            else
            {
            $shipping_charge = $this->config->get('config_active_store_delivery_charge') > 0 ? $this->config->get('config_active_store_delivery_charge') : 0;

            }
            $shipping_charge = $this->session->data['add_delivery_charges'] == 'true' ? $shipping_charge : 0;
            $shipping_charge_VAT = ($shipping_charge * 0.16);

            $total_data[] = [
                'code' => 'shipping',
                'title' => 'Standard Delivery',
                'value' => $shipping_charge,
                'sort_order' => $this->config->get('shipping_sort_order'),
            ];

            $total_data[] = [
                'code' => 'delivery_vat',
                'title' => 'VAT on Standard Delivery',
                'value' => $shipping_charge_VAT,
                'sort_order' => $this->config->get('shipping_sort_order') + 1,
            ];

            // echo "<pre>";print_r($total_data);die;

            $total += $shipping_charge + $shipping_charge_VAT;
        }
        else{
            $log = new Log('error.log');
            $log->write('Shipping 3 , coming here');
            $log->write('Shipping 3 , coming here');
        }
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = '', $args) {
        $log = new Log('error.log');
        $log->write('shiping getApiTotal');

        $all_total = 10000000;

        //echo "<pre>";print_r($args);die;
        if (isset($store_id) && isset($args['stores'][$store_id])) {
            $this->load->model('tool/image');

            $args = $args['stores'][$store_id];

            $log->write($args);

            $store_info = $this->model_tool_image->getStore($store_id);

            $log->write($store_id);
            $log->write($store_info);

            $delivery_by_owner = $store_info['delivery_by_owner'];

            $pickup_delivery = $store_info['store_pickup_timeslots'];

            $free_delivery_amount = $store_info['min_order_cod'];

            $sub_total = $args['total'];

            $store_total = $sub_total;

            $log->write($store_total);
            $log->write($free_delivery_amount);

            if ((int) $store_total > (int) $free_delivery_amount) {
                $cost = 0;
            } else {
                $cost = $store_info['cost_of_delivery'];
            }

            $log->write($cost);
            $temp_shipping_method_name = explode('.', $args['shipping_code']);
            $shipping_method_name = $temp_shipping_method_name[0];

            //print_r("in if");
            $log->write('Shipping 2');
            if ($store_id) {
                $this->load->model('shipping/' . $shipping_method_name);

                $quote = $this->{'model_shipping_' . $shipping_method_name}->getApiQuote($cost, $store_info['name'], $sub_total, $all_total);

                //echo "<pre>";print_r($quote);die;

                if (isset($quote['quote'][$shipping_method_name]) && count($quote['quote'][$shipping_method_name]) > 0) {
                    $total_data[] = [
                        'code' => 'shipping',
                        'title' => $quote['quote'][$shipping_method_name]['title'],
                        //'title' => $quote['quote'][$shipping_method_name]['title_with_store'],
                        'value' => $quote['quote'][$shipping_method_name]['cost'],
                        'sort_order' => $this->config->get('shipping_sort_order'),
                    ];

                    $totalcost = 0;

                    $tax_rates = $this->tax->getRates($quote['quote'][$shipping_method_name]['cost'], $quote['quote'][$shipping_method_name]['tax_class_id']);

                    foreach ($tax_rates as $tax_rate) {
                        if (!isset($taxes[$tax_rate['tax_rate_id']])) {
                            $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                        } else {
                            $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                        }
                    }

                    $totalcost += $quote['quote'][$shipping_method_name]['cost'];

                    $total += $totalcost;
                }
            }
        } else {
            $log->write('Shipping 6');
        }
    }

}
