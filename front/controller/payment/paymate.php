<?php
class ControllerPaymentPaymate extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		if (!$this->config->get('paymate_test')) {
			$data['action'] = 'https://www.paymate.com/PayMate/ExpressPayment';
		} else {
			$data['action'] = 'https://www.paymate.com.au/PayMate/TestExpressPayment';
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['mid'] = $this->config->get('paymate_username');
		$data['amt'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$data['currency'] = $order_info['currency_code'];
		$data['ref'] = $order_info['order_id'];

		$data['pmt_sender_email'] = $order_info['email'];
		$data['pmt_contact_firstname'] = html_entity_decode($order_info['firstname'], ENT_QUOTES, 'UTF-8');
		$data['pmt_contact_surname'] = html_entity_decode($order_info['lastname'], ENT_QUOTES, 'UTF-8');
		$data['pmt_contact_phone'] = $order_info['telephone'];
		$data['pmt_country'] = $this->config->get('config_country_code');

                
                //get address 
                $this->load->model('account/address');
                $this->load->model('account/customer');
                
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                
                if($customer_info) {
                    $address_id = $customer_info['address_id'];
                }else{
                    $address_id = 0;
                }
                
                $address_info = $this->model_account_address->getAddress($address_id);
                
                if($address_info) {
                    $city = $address_info['city'];
                    $address = $address_info['address'] . ', ' . $address_info['city'];
                }else{
                    $city = '';
                    $address = '';
                }
                
		$data['regindi_address1'] = html_entity_decode($order_info['shipping_address'], ENT_QUOTES, 'UTF-8');
		$data['regindi_address2'] = '';
		$data['regindi_sub'] = html_entity_decode($city, ENT_QUOTES, 'UTF-8');
		$data['regindi_state'] = html_entity_decode($this->config->get('config_state'), ENT_QUOTES, 'UTF-8');
		$data['regindi_pcode'] = '';

		$data['return'] = $this->url->link('payment/paymate/callback', 'hash=' . md5($order_info['order_id'] . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . $order_info['currency_code'] . $this->config->get('paymate_password')));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paymate.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/paymate.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/paymate.tpl', $data);
		}
	}

	public function callback() {
		$this->load->language('payment/paymate');

		if (isset($this->request->post['ref'])) {
			$order_id = $this->request->post['ref'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$error = '';

			if (!isset($this->request->post['responseCode']) || !isset($this->request->get['hash'])) {
				$error = $this->language->get('text_unable');
			} elseif ($this->request->get['hash'] != md5($order_info['order_id'] . $this->currency->format($this->request->post['paymentAmount'], $this->request->post['currency'], 1.0000000, false) . $this->request->post['currency'] . $this->config->get('paymate_password'))) {
				$error = $this->language->get('text_unable');
			} elseif ($this->request->post['responseCode'] != 'PA' && $this->request->post['responseCode'] != 'PP') {
				$error = $this->language->get('text_declined');
			}
		} else {
			$error = $this->language->get('text_unable');
		}

		if ($error) {
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_basket'),
				'href' => $this->url->link('checkout/cart')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_checkout'),
				'href' => $this->url->link('checkout/checkout', '', 'SSL')
			);

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_failed'),
				'href' => $server.'checkout-success';//$this->url->link('checkout/success')
			);

			$data['heading_title'] = $this->language->get('text_failed');

			$data['text_message'] = sprintf($this->language->get('text_failed_message'), $error, $this->url->link('information/contact'));

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
			}
		} else {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paymate_order_status_id'));

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }
			$this->response->redirect($server.'checkout-success');
		}
	}
}