<?php

class ControllerPaymentPezesha extends Controller {

    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->language('payment/pezesha');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');
        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pezesha.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/pezesha.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/pezesha.tpl', $data);
        }
    }

}
