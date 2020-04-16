<?php
class ModelPaymentNOCHEX extends Model {
	public function getMethod($total) {
		$this->load->language('payment/nochex');

		if ($this->config->get('nochex_total') > 0 && $this->config->get('nochex_total') > $total) {
			$status = false;
		} else {
			$status = true;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'nochex',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('nochex_sort_order')
			);
		}

		return $method_data;
	}
}