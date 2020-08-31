<?php
class ControllerPaymentPesapal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/pesapal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pesapal', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_consumer_key'] = $this->language->get('entry_consumer_key');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
                        unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
                
		if (isset($this->error['consumer_key'])) {
			$data['error_consumer_key'] = $this->error['consumer_key'];
		} else {
			$data['error_consumer_key'] = '';
		}

		if (isset($this->error['consumer_secret'])) {
			$data['error_consumer_secret'] = $this->error['consumer_secret'];
		} else {
			$data['error_consumer_secret'] = '';
		}
		



		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/pesapal', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/pesapal', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pesapal_consumer_key'])) {
			$data['pesapal_consumer_key'] = $this->request->post['pesapal_consumer_key'];
		} else {
			$data['pesapal_consumer_key'] = $this->config->get('pesapal_consumer_key');
		}

		if (isset($this->request->post['pesapal_consumer_secret'])) {
			$data['pesapal_consumer_secret'] = $this->request->post['pesapal_consumer_secret'];
		} else {
			$data['pesapal_consumer_secret'] = $this->config->get('pesapal_consumer_secret');
		}

		if (isset($this->request->post['pesapal_environment'])) {
			$data['pesapal_environment'] = $this->request->post['pesapal_environment'];
		} else {
			$data['pesapal_environment'] = $this->config->get('pesapal_environment');
		}

		if (isset($this->request->post['pesapal_failed_order_status_id'])) {
			$data['pesapal_failed_order_status_id'] = $this->request->post['pesapal_failed_order_status_id'];
		} else {
			$data['pesapal_failed_order_status_id'] = $this->config->get('pesapal_failed_order_status_id');
		}


		if (isset($this->request->post['pesapal_total'])) {
			$data['pesapal_total'] = $this->request->post['pesapal_total'];
		} else {
			$data['pesapal_total'] = $this->config->get('pesapal_total');
		}

		if (isset($this->request->post['pesapal_order_status_id'])) {
			$data['pesapal_order_status_id'] = $this->request->post['pesapal_order_status_id'];
		} else {
			$data['pesapal_order_status_id'] = $this->config->get('pesapal_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pesapal_status'])) {
			$data['pesapal_status'] = $this->request->post['pesapal_status'];
		} else {
			$data['pesapal_status'] = $this->config->get('pesapal_status');
		}

		if (isset($this->request->post['pesapal_sort_order'])) {
			$data['pesapal_sort_order'] = $this->request->post['pesapal_sort_order'];
		} else {
			$data['pesapal_sort_order'] = $this->config->get('pesapal_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pesapal.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pesapal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pesapal_customer_key']) {
			$this->error['consumer_key'] = $this->language->get('error_consumer_key');
		}
		if (!$this->request->post['pesapal_customer_secret']) {
			$this->error['consumer_secret'] = $this->language->get('error_consumer_secret');
		}

		return !$this->error;
	}
}