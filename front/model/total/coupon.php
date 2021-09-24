<?php

class ModelTotalCoupon extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = '')
    {
        //echo "$store_id";print_r($total_data);die;

        //print_r($this->session->data['shipping_method']);
        /*Array ( [8] => Array ( [store_id] => 8 [shipping_method] => Array ( [code] => express.express [title] => Express Delivery [cost] => 25 [tax_class_id] => 0 [text] => R$25.00 ) ) )*/

        $log = new Log('error.log');

        $log->write('ModelTotalCoupon');

        /*echo "<pre>";print_r($total);die;
        echo "<pre>";print_r($total_data);die;*/

        if ($store_id) {
            if (isset($this->session->data['coupon'])) {
                $this->load->language('total/coupon');

                $this->load->model('checkout/coupon');

                $coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

                if ($coupon_info) {
                    $discount_total = 0;

                    if (!$coupon_info['product']) {
                        $sub_total = $this->cart->getSubTotal($store_id);
                    } else {
                        $sub_total = 0;

                        foreach ($this->cart->getProducts() as $product) {
                            if ($product['store_id'] == $store_id) {
                                if (in_array($product['product_id'], $coupon_info['product'])) {
                                    $sub_total += $product['total'];
                                }
                            } else {
                                if (in_array($product['product_id'], $coupon_info['product'])) {
                                    $sub_total += $product['total'];
                                }
                            }
                        }
                    }
                    $main_total = $this->cart->getSubTotal();
                    $weightage = ($sub_total * 100) / $main_total;
                    if ('F' == $coupon_info['type']) {
                        $store_discount = ($coupon_info['discount'] * $weightage) / 100;

                        $discount_total = min($store_discount, $sub_total);
                    } elseif ('P' == $coupon_info['type']) {
                        $discount_total = $sub_total / 100 * $coupon_info['discount'];
                    }

                    /*if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {

                        $cost = $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];

                        $discount_total += $cost;
                    }*/

                    if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {
                        if (!empty($this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id'])) {
                            $tax_rates = $this->tax->getRates($this->session->data['shipping_method'][$store_id]['shipping_method']['cost'], $this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id']);

                            foreach ($tax_rates as $tax_rate) {
                                if ('P' == $tax_rate['type']) {
                                    $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                                }
                            }
                        }

                        $discount_total += $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];
                    }

                    if ($discount_total > $total) {
                        $discount_total = $total;
                    }

                    if ('c' == $coupon_info['coupon_type']) {
                        $discount_total = -0;
                    }

                    $total_data[] = [
                        'code' => 'coupon',
                        'title' => sprintf($this->language->get('text_coupon'), $this->session->data['coupon']),
                        'value' => -$discount_total,
                        'sort_order' => $this->config->get('coupon_sort_order'),
                    ];

                    $total -= $discount_total;
                }
            }
        } else {
            if (isset($this->session->data['coupon'])) {
                $this->load->language('total/coupon');

                $this->load->model('checkout/coupon');

                $coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

                if ($coupon_info) {
                    $discount_total = 0;

                    if (!$coupon_info['product']) {
                        $sub_total = $this->cart->getSubTotal();
                    } else {
                        $sub_total = 0;

                        foreach ($this->cart->getProducts() as $product) {
                            if (in_array($product['product_id'], $coupon_info['product'])) {
                                $sub_total += $product['total'];
                            }
                        }
                    }

                    if ('F' == $coupon_info['type']) {
                        $coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
                    }

                    $log->write($taxes);

                    foreach ($this->cart->getProducts() as $product) {
                        $discount = 0;

                        if (!$coupon_info['product']) {
                            $status = true;
                        } else {
                            if (in_array($product['product_id'], $coupon_info['product'])) {
                                $status = true;
                            } else {
                                $status = false;
                            }
                        }

                        if ($status) {
                            if ('F' == $coupon_info['type']) {
                                $discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
                            } elseif ('P' == $coupon_info['type']) {
                                $discount = $product['total'] / 100 * $coupon_info['discount'];
                            }

                            if ($product['tax_class_id'] && 'c' != $coupon_info['coupon_type']) {
                                $tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

                                $log->write('taxes appl');
                                $log->write($tax_rates);
                                $log->write($discount);

                                foreach ($tax_rates as $tax_rate) {
                                    if ('P' == $tax_rate['type']) {
                                        $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                                    }
                                }
                            }
                        }

                        $discount_total += $discount;
                    }

                    $log->write($taxes);

                    $log->write('taxes end');

                    //echo "<pre>";print_r($this->session->data['shipping_method']);die;

                    /*if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {

                        $cost = $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];

                        $discount_total += $cost;
                    }*/

                  


                    if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
                        $store_id = key($this->session->data['shipping_method']);

                        if (!empty($this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id'])) {
                            $tax_rates = $this->tax->getRates($this->session->data['shipping_method'][$store_id]['shipping_method']['cost'], $this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id']);

                            foreach ($tax_rates as $tax_rate) {
                                if ('P' == $tax_rate['type']) {
                                    $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                                }
                            }
                        }

                        $discount_total += $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];
                    }

                    // If discount greater than total
                    if ($discount_total > $total) {
                        $discount_total = $total;
                    }
                    //for 100% discount , this condition is added e
                    if('P' == $coupon_info['type'] && $coupon_info['discount']==100.0000 && !$coupon_info['product'])
                    {
                        $discount_total=$this->cart->getTotal();
                    }

                    if ('c' == $coupon_info['coupon_type']) {
                        $discount_total = -0;
                    }

                    $total_data[] = [
                    'code' => 'coupon',
                    'title' => sprintf($this->language->get('text_coupon'), $this->session->data['coupon']),
                    'value' => -$discount_total,
                    'sort_order' => $this->config->get('coupon_sort_order'),
                ];

                    $total -= $discount_total;
                }
            }
        }
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = '', $args)
    {
        //echo "$store_id";print_r($total_data);die;

        //print_r($this->session->data['shipping_method']);
        /*Array ( [8] => Array ( [store_id] => 8 [shipping_method] => Array ( [code] => express.express [title] => Express Delivery [cost] => 25 [tax_class_id] => 0 [text] => R$25.00 ) ) )*/

        $log = new Log('error.log');

        $log->write('ModelTotalCoupon');
        //$log->write($args);

        if ($store_id) {
            if (isset($args['coupon'])) {
                $this->load->language('total/coupon');

                $this->load->model('checkout/coupon');

                $coupon_info = $this->model_checkout_coupon->apiGetCoupon($args['coupon'], $args['total']);

                if ($coupon_info) {
                    $log->write('coupon_info');
                    $discount_total = 0;

                    if (!$coupon_info['product'] || true) {
                        //$sub_total = $this->cart->getSubTotal($store_id);
                        $sub_total = $args['sub_total'];
                    } else {
                        // product coupon
                        $sub_total = 0;

                        foreach ($this->cart->getProducts() as $product) {
                            if ($product['store_id'] == $store_id) {
                                if (in_array($product['product_id'], $coupon_info['product'])) {
                                    $sub_total += $product['total'];
                                }
                            } else {
                                if (in_array($product['product_id'], $coupon_info['product'])) {
                                    $sub_total += $product['total'];
                                }
                            }
                        }
                    }

                    //$main_total  = $this->cart->getSubTotal();
                    $main_total = $args['sub_total'];

                    $weightage = ($sub_total * 100) / $main_total;
                    if ('F' == $coupon_info['type']) {
                        $store_discount = ($coupon_info['discount'] * $weightage) / 100;

                        $discount_total = min($store_discount, $sub_total);
                    } elseif ('P' == $coupon_info['type']) {
                        $discount_total = $sub_total / 100 * $coupon_info['discount'];
                    }

                    /*if ($coupon_info['shipping'] && isset($args['shipping_method'][$store_id])) {

                        $cost = $args['shipping_method'][$store_id]['shipping_method']['cost'];

                        $discount_total += $cost;
                    }*/

                    if ($coupon_info['shipping'] && isset($args['shipping_method'][$store_id])) {
                        if (!empty($args['shipping_method'][$store_id]['shipping_method']['tax_class_id'])) {
                            $tax_rates = $this->tax->getRates($args['shipping_method'][$store_id]['shipping_method']['cost'], $args['shipping_method'][$store_id]['shipping_method']['tax_class_id']);

                            foreach ($tax_rates as $tax_rate) {
                                if ('P' == $tax_rate['type']) {
                                    $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                                }
                            }
                        }

                        $discount_total += $args['shipping_method'][$store_id]['shipping_method']['cost'];
                    }

                    $log->write('discount_total');
                    $log->write($discount_total);

                    if ($discount_total > $total) {
                        $discount_total = $total;
                    }

                    if ('c' == $coupon_info['coupon_type']) {
                        $discount_total = -0;
                    }

                    $total_data[] = [
                        'code' => 'coupon',
                        'title' => sprintf($this->language->get('text_coupon'), $args['coupon']),
                        'value' => -$discount_total,
                        'sort_order' => $this->config->get('coupon_sort_order'),
                    ];

                    $total -= $discount_total;
                }
            }
        }
    }

    public function confirm($order_info, $order_total)
    {
        //echo "con";
        $code = '';

        $start = strpos($order_total['title'], '(') + 1;
        $end = strrpos($order_total['title'], ')');

        if ($start && $end) {
            $code = substr($order_total['title'], $start, $end - $start);
        }

        $this->load->model('checkout/coupon');

        $log = new Log('error.log');
        $coupon_info = $this->model_checkout_coupon->adminGetCoupon($code);

        //$log->write('PayPal Express debug code'. $coupon_info."s");
        if ($coupon_info && 'd' == $coupon_info['coupon_type']) {
            $log->write('coupon discount'.$coupon_info['coupon_id'].'s'.$order_info['customer_id'].'s'.$order_total['value']);
            $this->db->query('INSERT INTO `'.DB_PREFIX."coupon_history` SET coupon_id = '".(int) $coupon_info['coupon_id']."', order_id = '".(int) $order_info['order_id']."', customer_id = '".(int) $order_info['customer_id']."', amount = '".(float) $order_total['value']."', date_added = NOW()");
        }

        if ($coupon_info && 'c' == $coupon_info['coupon_type']) {
            $cashbackValue = $this->getCashbackTotal($order_info['order_id'], $code);

            $log->write('coupon cashbackValue'.$coupon_info['coupon_id'].'s'.$order_info['customer_id'].'cashbackValue'.$cashbackValue);

            $this->db->query('INSERT INTO `'.DB_PREFIX."coupon_history` SET coupon_id = '".(int) $coupon_info['coupon_id']."', order_id = '".(int) $order_info['order_id']."', customer_id = '".(int) $order_info['customer_id']."', amount = '".(float) $cashbackValue."', date_added = NOW()");
        }
    }

    public function unconfirm($order_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."coupon_history` WHERE order_id = '".(int) $order_id."'");
    }

    public function getCashbackTotal($order_id, $coupon)
    {
        $discount_total = 0;

        if (isset($coupon) && isset($order_id)) {
            $this->load->language('total/coupon');

            $this->load->model('checkout/coupon');
            $this->load->model('account/order');

            $coupon_info = $this->model_checkout_coupon->adminGetCoupon($coupon);

            if ($coupon_info) {
                $discount_total = 0;

                $sub_total = 0;

                $main_total = 1; //initialized to 1 so that it doesn't throughs  divide by zero error

                /*if (!$coupon_info['product']) {

                    echo "pree";print_r($totals);die;
                    //$sub_total = $this->cart->getSubTotal($store_id);
                } else {*/
                if (true) {
                    $totals = $this->model_account_order->getOrderTotals($order_id);

                    foreach ($totals as $total) {
                        if ('sub_total' == $total['code']) {
                            $sub_total = $total['value'];
                        }
                    }
                }

                if ($sub_total) {
                    $main_total = $sub_total;
                }

                $weightage = ($sub_total * 100) / $main_total; //weightage in this case will be always 100
                if ('F' == $coupon_info['type']) {
                    $store_discount = ($coupon_info['discount'] * $weightage) / 100;

                    $discount_total = min($store_discount, $sub_total);
                } elseif ('P' == $coupon_info['type']) {
                    $discount_total = $sub_total / 100 * $coupon_info['discount'];
                }

                //commented shipping method cost for discount calculation

                /*if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {

                    $cost = $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];

                    $discount_total += $cost;
                }*/
                if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
                    $store_id = key($this->session->data['shipping_method']);

                    if (!empty($this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id'])) {
                        $tax_rates = $this->tax->getRates($this->session->data['shipping_method'][$store_id]['shipping_method']['cost'], $this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id']);

                        foreach ($tax_rates as $tax_rate) {
                            if ('P' == $tax_rate['type']) {
                                $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                            }
                        }
                    }

                    $discount_total += $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];
                }

                if ($discount_total > $main_total) {
                    $discount_total = $main_total;
                }
            }
        }

        return -1 * $discount_total;
    }

    public function getCashbackTotalCheckout()
    {
        $discount_total = 0;

        $coupon = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : null;

        if (isset($coupon)) {
            $this->load->language('total/coupon');

            $this->load->model('checkout/coupon');
            $this->load->model('account/order');

            $coupon_info = $this->model_checkout_coupon->adminGetCoupon($coupon);

            if ($coupon_info) {
                $discount_total = 0;

                $sub_total = 0;

                $main_total = 1; //initialized to 1 so that it doesn't throughs  divide by zero error

                /*if (!$coupon_info['product']) {

                    echo "pree";print_r($totals);die;
                    //$sub_total = $this->cart->getSubTotal($store_id);
                } else {*/
                if (true) {
                    $sub_total = $this->cart->getSubTotal();
                }

                if ($sub_total) {
                    $main_total = $sub_total;
                }

                $weightage = ($sub_total * 100) / $main_total; //weightage in this case will be always 100
                if ('F' == $coupon_info['type']) {
                    $store_discount = ($coupon_info['discount'] * $weightage) / 100;

                    $discount_total = min($store_discount, $sub_total);
                } elseif ('P' == $coupon_info['type']) {
                    $discount_total = $sub_total / 100 * $coupon_info['discount'];
                }

                //commented shipping method cost for discount calculation

                /*if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {

                    $cost = $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];

                    $discount_total += $cost;
                }*/

                if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
                    //echo key($this->session->data['shipping_method']);
                    $store_id = key($this->session->data['shipping_method']);
                    if (!empty($this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id'])) {
                        $tax_rates = $this->tax->getRates($this->session->data['shipping_method'][$store_id]['shipping_method']['cost'], $this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id']);

                        foreach ($tax_rates as $tax_rate) {
                            if ('P' == $tax_rate['type']) {
                                $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                            }
                        }
                    }

                    $discount_total += $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];
                }

                if ($discount_total > $main_total) {
                    $discount_total = $main_total;
                }
            }
        }

        return $discount_total;
    }
}
