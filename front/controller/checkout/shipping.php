<?php

class ControllerCheckoutShipping extends Controller
{
    public function index()
    {
        if ($this->config->get('shipping_status') && $this->config->get('shipping_estimator') && $this->cart->hasShipping()) {
            $this->load->language('checkout/shipping');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_shipping'] = $this->language->get('text_shipping');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $data['text_select'] = $this->language->get('text_select');
            $data['text_none'] = $this->language->get('text_none');
            $data['text_loading'] = $this->language->get('text_loading');

            $data['entry_country'] = $this->language->get('entry_country');
            $data['entry_zone'] = $this->language->get('entry_zone');
            $data['entry_postcode'] = $this->language->get('entry_postcode');

            $data['button_quote'] = $this->language->get('button_quote');
            $data['button_shipping'] = $this->language->get('button_shipping');
            $data['button_cancel'] = $this->language->get('button_cancel');

            if (isset($this->session->data['shipping_address']['country_id'])) {
                $data['country_id'] = $this->session->data['shipping_address']['country_id'];
            } else {
                $data['country_id'] = $this->config->get('config_country_id');
            }

            $this->load->model('localisation/country');

            $data['countries'] = $this->model_localisation_country->getCountries();

            if (isset($this->session->data['shipping_address']['zone_id'])) {
                $data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
            } else {
                $data['zone_id'] = '';
            }

            if (isset($this->session->data['shipping_address']['postcode'])) {
                $data['postcode'] = $this->session->data['shipping_address']['postcode'];
            } else {
                $data['postcode'] = '';
            }

            if (isset($this->session->data['shipping_method'])) {
                foreach ($this->session->data['shipping_method'] as $key => $value) {
                    $data['shipping_method'] = $value['shipping_method']['cost'];
                }
            } else {
                $data['shipping_method'] = '';
            }

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/shipping.tpl')) {
                return $this->load->view($this->config->get('config_template').'/template/checkout/shipping.tpl', $data);
            } else {
                return $this->load->view('default/template/checkout/shipping.tpl', $data);
            }
        }
    }

    public function shipping()
    {
        $this->load->language('checkout/shipping');

        $json = [];

        if (!empty($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $json['warning'] = $this->language->get('error_shipping');
            }
        } else {
            $json['warning'] = $this->language->get('error_shipping');
        }

        if (!$json) {
            $shipping = explode('.', $this->request->post['shipping_method']);

            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
            $this->session->data['success'] = $this->language->get('text_success');

            $json['redirect'] = $this->url->link('checkout/cart');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function country()
    {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
