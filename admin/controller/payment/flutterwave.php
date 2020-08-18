<?php
class ControllerPaymentFlutterwave extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/flutterwave');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('flutterwave', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_customer_key'] = $this->language->get('entry_customer_key');
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
                
		if (isset($this->error['public_key'])) {
			$data['error_public_key'] = $this->error['public_key'];
		} else {
			$data['error_customer_key'] = '';
		}

		if (isset($this->error['secret_key'])) {
			$data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$data['error_secret_key'] = '';
		}

		
		if (isset($this->error['encryption_key'])) {
			$data['error_encryption_key'] = $this->error['encryption_key'];
		} else {
			$data['error_encryption_key'] = '';
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
			'href' => $this->url->link('payment/flutterwave', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/flutterwave', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['flutterwave_public_key'])) {
			$data['flutterwave_public_key'] = $this->request->post['flutterwave_public_key'];
		} else {
			$data['flutterwave_public_key'] = $this->config->get('flutterwave_public_key');
		}

		if (isset($this->request->post['flutterwave_secret_key'])) {
			$data['flutterwave_secret_key'] = $this->request->post['flutterwave_secret_key'];
		} else {
			$data['flutterwave_secret_key'] = $this->config->get('flutterwave_secret_key');
		}

		if (isset($this->request->post['flutterwave_environment'])) {
			$data['flutterwave_environment'] = $this->request->post['flutterwave_environment'];
		} else {
			$data['flutterwave_environment'] = $this->config->get('flutterwave_environment');
		}
		

		if (isset($this->request->post['flutterwave_encryption_key'])) {
			$data['flutterwave_encryption_key'] = $this->request->post['flutterwave_encryption_key'];
		} else {
			$data['flutterwave_encryption_key'] = $this->config->get('flutterwave_encryption_key');
		}

		if (isset($this->request->post['flutterwave_failed_order_status_id'])) {
			$data['flutterwave_failed_order_status_id'] = $this->request->post['flutterwave_failed_order_status_id'];
		} else {
			$data['flutterwave_failed_order_status_id'] = $this->config->get('flutterwave_failed_order_status_id');
		}


		if (isset($this->request->post['flutterwave_total'])) {
			$data['flutterwave_total'] = $this->request->post['flutterwave_total'];
		} else {
			$data['flutterwave_total'] = $this->config->get('flutterwave_total');
		}

		if (isset($this->request->post['flutterwave_order_status_id'])) {
			$data['flutterwave_order_status_id'] = $this->request->post['flutterwave_order_status_id'];
		} else {
			$data['flutterwave_order_status_id'] = $this->config->get('flutterwave_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['flutterwave_status'])) {
			$data['flutterwave_status'] = $this->request->post['flutterwave_status'];
		} else {
			$data['flutterwave_status'] = $this->config->get('flutterwave_status');
		}

		if (isset($this->request->post['flutterwave_sort_order'])) {
			$data['flutterwave_sort_order'] = $this->request->post['flutterwave_sort_order'];
		} else {
			$data['flutterwave_sort_order'] = $this->config->get('flutterwave_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/flutterwave.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/flutterwave')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['flutterwave_public_key']) {
			$this->error['public_key'] = $this->language->get('error_public_key');
		}
		if (!$this->request->post['flutterwave_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}
		if (!$this->request->post['flutterwave_encryption_key']) {
			$this->error['encryption_key'] = $this->language->get('error_encryption_key');
		}


		return !$this->error;
	}
}