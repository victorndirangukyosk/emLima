<?php

class ControllerCheckoutTotals extends Controller
{
    public function index()
    {
        $this->load->language('checkout/cart');

        if (isset($this->request->get['city_id'])) {
            $this->tax->setShippingAddress($this->request->get['city_id']);
        }

        // Totals
        $this->load->model('extension/extension');

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = [];

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'].'_status')) {
                    $this->load->model('total/'.$result['code']);

                    $this->{'model_total_'.$result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            //echo "<pre>";print_r($results);die;
            $sort_order = [];

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $data['totals'] = [];

        foreach ($total_data as $total) {
            $data['totals'][] = [
                'title' => $total['title'],
                'text' => $this->currency->format($total['value'])
            ];
        }

        //echo "<pre>";print_r($data);die;
        $data['cashback_condition'] = $this->language->get('cashback_condition');

        $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
        $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

        $this->load->model('total/coupon');

        $data['cashbackAmount'] = $this->currency->format(0);

        $data['cashbackAmount'] = $this->currency->format($this->model_total_coupon->getCashbackTotalCheckout());

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/totals.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/checkout/totals.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/totals.tpl', $data));
        }
    }

    public function getTotal($order_id)
    {
        $this->load->language('checkout/cart');

        // Totals
        $this->load->model('account/order');

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $total_data = $this->model_account_order->getOrderTotals($order_id);

        $data['text_inc_tax'] = $this->language->get('text_inc_tax');
        $sort_order = [];

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $data['totals'] = [];

        foreach ($total_data as $total) {
            $data['totals'][] = [
                'title' => $total['title'],
                'text' => $this->currency->format($total['value'])
            ];
        }

        $order_info = $this->model_account_order->getOrder($order_id);

        $data['coupon_cashback'] = false;

        if ($order_info) {
            foreach ($this->config->get('config_complete_status') as $key => $value) {
                if ($value == $order_info['order_status_id']) {
                    $data['coupon_cashback'] = true;
                    break;
                }
            }
        }

        $data['cashbackAmount'] = $this->currency->format(0);

        $coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

        if (count($coupon_history_data) > 0) {
            $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount']));
        }

        $data['cashback_condition'] = $this->language->get('cashback_condition');
        $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
        $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/order_totals.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/checkout/order_totals.tpl', $data);
        } else {
            return $this->load->view('default/template/checkout/order_totals.tpl', $data);
        }
    }

    public function totalData()
    {
        $this->load->language('checkout/cart');

        if (isset($this->request->get['city_id'])) {
            $this->tax->setShippingAddress($this->request->get['city_id']);
        }

        // Totals
        $this->load->model('extension/extension');

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = [];

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'].'_status')) {
                    $this->load->model('total/'.$result['code']);

                    if ('reward' == $result['code']) {
                        break;
                    }

                    $this->{'model_total_'.$result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            return $total;
            //echo "<pre>";print_r($results);die;
            $sort_order = [];

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $data['totals'] = [];

        foreach ($total_data as $total) {
            $data['totals'][] = [
                'title' => $total['title'],
                'text' => $this->currency->format($total['value']),
                'value' => $total['value']
            ];
        }

        return $data;
    }
}
