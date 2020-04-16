<?php
class ModelPaymentPayza extends Model {
	public function getMethod( $total) {
		$this->load->language('payment/payza');

		if ($this->config->get('payza_total') > 0 && $this->config->get('payza_total') > $total) {
			$status = false;
		} else {
			$status = true;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'payza',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payza_sort_order')
			);
		}

		return $method_data;
	}
}