<?php

class ControllerApiCustomerTotals extends Controller {

    public function getTotals($args = []) {
        $log = new Log('error.log');
        $this->load->language('checkout/cart');

        if (isset($this->request->get['city_id']) && $this->request->get['city_id'] > 0) {
            $this->tax->setShippingAddress($this->request->get['city_id']);
        } else {
            $this->request->get['city_id'] = 32;
            $this->tax->setShippingAddress($this->request->get['city_id']);
        }

        // Totals
        $this->load->model('extension/extension');

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();
        $custom_discounts = $this->cart->getDiscounts();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = [];

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {

                $log->write('code');
                $log->write($result['code']);
                $log->write('code');

                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    if ($result['code'] != 'discount') {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }

                    if ($result['code'] == 'discount') {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes, NULL, $custom_discounts);
                    }
                }
            }

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
            ];
        }
        $log->write('totals');
        $log->write($data['totals']);
        $log->write('totals');

        $data['cashback_condition'] = $this->language->get('cashback_condition');
        $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
        $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

        $this->load->model('total/coupon');
        $data['cashbackAmount'] = $this->currency->format(0);
        $data['cashbackAmount'] = $this->currency->format($this->model_total_coupon->getCashbackTotalCheckout());

        /* MINIMUM ORDER AMOUNT CHECKING */
        $this->load->model('account/address');
        $order_stores = $this->cart->getStores();
        $min_order_or_not = [];
        $store_data = [];

        $data['min_order_amount_reached'] = TRUE;
        $data['min_order_amount_away'] = NULL;
        foreach ($order_stores as $os) {
            $store_info = $this->model_account_address->getStoreData($os);
            $store_total = $this->cart->getSubTotal($os);
            $store_info['servicable_zipcodes'] = $this->model_account_address->getZipList($os);
            $store_data[] = $store_info;

            if ($this->cart->getTotalProductsByStore($os) && $this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
                $data['min_order_amount_reached'] = FALSE;
                $data['min_order_amount_away'] = '*' . $this->currency->format($this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal()) . ' away from minimum order value.';
            }
        }
        /* MINIMUM ORDER AMOUNT CHECKING */

        $json = $data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}

?>
