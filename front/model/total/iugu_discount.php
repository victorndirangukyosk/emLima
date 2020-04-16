<?php
/*
	Author: Valdeir Santana
	Site: http://www.valdeirsantana.com.br
	License: http://www.gnu.org/licenses/gpl-3.0.en.html
*/
class ModelTotalIuguDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$status = false;
		
		if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'iugu_credit_card') {
			$settings = $this->config->get('iugu_credit_card_discount');
			
			if ($settings['value'] > 0) {
				$status = true;
			}
		}
		
		if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'iugu_billet') {
			$settings = $this->config->get('iugu_billet_discount');
			
			if ($settings['value'] > 0) {
				$status = true;
			}
		}
		
		if ($status) {
			$this->load->language('payment/iugu');

			if ($settings['type'] == 'F') {
				$discount = $settings['value'];
			} else {
				$discount = ($this->cart->getSubTotal()/100) * $settings['value'];
			}

			if ($discount > 0) {
				$total_data[] = array(
					'code'       => 'iugu_discount',
					'title'      => $this->language->get('text_discount'),
					'value'      => -$discount,
					'sort_order' => 3
				);

				$total -= $discount;
			}
		}
	}

	public function getApiTotal(&$total_data, &$total, &$taxes,$args) {
		$status = false;
		
		if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'iugu_credit_card') {
			$settings = $this->config->get('iugu_credit_card_discount');
			
			if ($settings['value'] > 0) {
				$status = true;
			}
		}
		
		if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'iugu_billet') {
			$settings = $this->config->get('iugu_billet_discount');
			
			if ($settings['value'] > 0) {
				$status = true;
			}
		}
		
		if ($status) {
			$this->load->language('payment/iugu');

			if ($settings['type'] == 'F') {
				$discount = $settings['value'];
			} else {
				$discount = ($this->cart->getSubTotal()/100) * $settings['value'];
			}

			if ($discount > 0) {
				$total_data[] = array(
					'code'       => 'iugu_discount',
					'title'      => $this->language->get('text_discount'),
					'value'      => -$discount,
					'sort_order' => 3
				);

				$total -= $discount;
			}
		}
	}
}