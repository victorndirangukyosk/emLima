<?php
class ControllerPaymentMoneybookers extends Controller {
	public function index() {
		$this->load->model('checkout/order');

		$this->load->language('payment/moneybookers');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['action'] = 'https://www.moneybookers.com/app/payment.pl?p=OpenCart';

		if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

		$data['pay_to_email'] = $this->config->get('moneybookers_email');
		$data['platform'] = '31974336';
		$data['description'] = $this->config->get('config_name');
		$data['transaction_id'] = $this->session->data['order_id'];
		$data['return_url'] = $server.'checkout-success';//$this->url->link('checkout/success');
		$data['cancel_url'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['status_url'] = $this->url->link('payment/moneybookers/callback');
		$data['language'] = $this->session->data['language'];
		$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['pay_from_email'] = $order_info['email'];
		$data['firstname'] = $order_info['firstname'];
		$data['lastname'] = $order_info['lastname'];
		$data['address'] = $order_info['shipping_address'];
		$data['address2'] = '';
		$data['phone_number'] = $order_info['telephone'];
		$data['postal_code'] = '';
		$data['city'] = '';
		$data['state'] = $this->config->get('state');
		$data['country'] =$this->config->get('country');
		$data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency'] = $order_info['currency_code'];

		$products = '';

		foreach ($this->cart->getProducts() as $product) {
			$products .= $product['quantity'] . ' x ' . $product['name'] . ', ';
		}

		$data['detail1_text'] = $products;

		$data['order_id'] = $this->session->data['order_id'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/moneybookers.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/moneybookers.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/moneybookers.tpl', $data);
		}
	}

	public function callback() {
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));

			$verified = true;

			// md5sig validation
			if ($this->config->get('moneybookers_secret')) {
				$hash  = $this->request->post['merchant_id'];
				$hash .= $this->request->post['transaction_id'];
				$hash .= strtoupper(md5($this->config->get('moneybookers_secret')));
				$hash .= $this->request->post['mb_amount'];
				$hash .= $this->request->post['mb_currency'];
				$hash .= $this->request->post['status'];

				$md5hash = strtoupper(md5($hash));
				$md5sig = $this->request->post['md5sig'];

				if ($md5hash != $md5sig) {
					$verified = false;
				}
			}

			if ($verified) {
				switch($this->request->post['status']) {
					case '2':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('moneybookers_order_status_id'), '', true);
						break;
					case '0':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('moneybookers_pending_status_id'), '', true);
						break;
					case '-1':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('moneybookers_canceled_status_id'), '', true);
						break;
					case '-2':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('moneybookers_failed_status_id'), '', true);
						break;
					case '-3':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('moneybookers_chargeback_status_id'), '', true);
						break;
				}
			} else {
				$this->log->write('md5sig returned (' + $md5sig + ') does not match generated (' + $md5hash + '). Verify Manually. Current order state: ' . $this->config->get('config_order_status_id'));
			}
		}
	}
}