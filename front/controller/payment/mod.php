<?php

class ControllerPaymentMod extends Controller {
	public function index() {

		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->language('payment/mod');

		$data['text_loading'] = $this->language->get('text_loading');
		
		$data['continue'] = $this->url->link('checkout/success');
		$data['continue'] = $this->url->link('checkout/success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mod.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/mod.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/mod.tpl', $data);
		}
	}

	public function confirm() {

		$log = new Log('error.log');
		$log->write('mod confirm');
		$log->write($this->session->data['payment_method']['code']);
		if ($this->session->data['payment_method']['code'] == 'mod') {
			$this->load->model('checkout/order');

			$log->write($this->session->data['order_id']);
			$log->write($this->config->get('mod_order_status_id'));
			
			foreach ($this->session->data['order_id'] as $order_id) {

				$log->write('mod loop'.$order_id);

				$ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'));
			}
		}
	}

	public function apiConfirm($orders) {

		$log = new Log('error.log');
		$log->write('apiConfirm mod confirm');
		
		$this->load->model('checkout/order');
		
		$log->write($this->config->get('mod_order_status_id'));
		
		foreach ($orders as $order_id) {

			$log->write('mod loop'.$order_id);

			$ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'));
		}
	}
}