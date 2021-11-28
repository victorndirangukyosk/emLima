<?php

    /**
     * @copyright     	(c) 2017 iPay Limited. All rights reserved.
     * @author        	Moses King'ori <moses@intrepid.co.ke>
     * @license			This program is free software; you can redistribute it and/or modify
     *            		it under the terms of the GNU General Public License, version 2, as
     *              	published by the Free Software Foundation.
     *
     * 					This program is distributed in the hope that it will be useful,
     *      			but WITHOUT ANY WARRANTY; without even the implied warranty of
     *         			MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *            		GNU General Public License for more details.
     *
     * 					You should have received a copy of the GNU General Public License
     *      			along with this program; if not, write to the Free Software
     *         			Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
     */
    class ControllerPaymentiPayPaymentSoftware extends Controller
    {
        private $error = [];

        public function index()
        {
            $this->load->language('payment/iPay_payment_software');

            $this->document->setTitle($this->language->get('heading_title'));

            $this->load->model('setting/setting');

            if (('POST' == $this->request->server['REQUEST_METHOD']) && ($this->validate())) {
                $this->model_setting_setting->editSetting('iPay_payment_software', $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'));
            }

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_enabled'] = $this->language->get('text_enabled');
            $data['text_disabled'] = $this->language->get('text_disabled');
            $data['text_all_zones'] = $this->language->get('text_all_zones');
            $data['text_test'] = $this->language->get('text_test');
            $data['text_live'] = $this->language->get('text_live');
            $data['text_authorization'] = $this->language->get('text_authorization');
            $data['text_capture'] = $this->language->get('text_capture');

            $data['entry_login'] = $this->language->get('entry_login');
            $data['entry_key'] = $this->language->get('entry_key');

            $data['entry_callback_url'] = $this->language->get('entry_callback_url');
            $data['entry_ipay_url'] = $this->language->get('entry_ipay_url');

            $data['entry_elipa_enabled'] = $this->language->get('entry_elipa_enabled');
            $data['entry_mvisa_enabled'] = $this->language->get('entry_mvisa_enabled');

            $data['entry_mpesa_enabled'] = $this->language->get('entry_mpesa_enabled');
            $data['entry_airtel_enabled'] = $this->language->get('entry_airtel_enabled');
            $data['entry_equity_enabled'] = $this->language->get('entry_equity_enabled');
            $data['entry_mobilebanking_enabled'] = $this->language->get('entry_mobilebanking_enabled');
            $data['entry_debitcard_enabled'] = $this->language->get('entry_debitcard_enabled');
            $data['entry_creditcard_enabled'] = $this->language->get('entry_creditcard_enabled');
            $data['entry_mkoporahisi_enabled'] = $this->language->get('entry_mkoporahisi_enabled');
            $data['entry_saida_enabled'] = $this->language->get('entry_saida_enabled');

            $data['help_key'] = $this->language->get('help_key');
            $data['entry_mode'] = $this->language->get('entry_mode');
            $data['entry_method'] = $this->language->get('entry_method');
            $data['entry_total'] = $this->language->get('entry_total');
            $data['help_total'] = $this->language->get('help_total');
            $data['entry_order_status'] = $this->language->get('entry_order_status');
            $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
            $data['entry_status'] = $this->language->get('entry_status');
            $data['entry_sort_order'] = $this->language->get('entry_sort_order');

            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');

            $data['tab_general'] = $this->language->get('tab_general');

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->error['login'])) {
                $data['error_login'] = $this->error['login'];
            } else {
                $data['error_login'] = '';
            }

            if (isset($this->error['key'])) {
                $data['error_key'] = $this->error['key'];
            } else {
                $data['error_key'] = '';
            }

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                   'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                   'text' => $this->language->get('text_payment'),
                'href' => $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                   'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('payment/iPay_payment_software', 'token='.$this->session->data['token'], 'SSL'),
            ];

            $data['action'] = $this->url->link('payment/iPay_payment_software', 'token='.$this->session->data['token'], 'SSL');

            $data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');

            if (isset($this->request->post['iPay_payment_software_login'])) {
                $data['iPay_payment_software_merchant_name'] = $this->request->post['iPay_payment_software_merchant_name'];
            } else {
                $data['iPay_payment_software_merchant_name'] = $this->config->get('iPay_payment_software_merchant_name');
            }

            if (isset($this->request->post['iPay_payment_software_merchant_key'])) {
                $data['iPay_payment_software_merchant_key'] = $this->request->post['iPay_payment_software_merchant_key'];
            } else {
                $data['iPay_payment_software_merchant_key'] = $this->config->get('iPay_payment_software_merchant_key');
            }

            if (isset($this->request->post['iPay_payment_software_callback_url'])) {
                $data['iPay_payment_software_callback_url'] = $this->request->post['iPay_payment_software_callback_url'];
            } else {
                $data['iPay_payment_software_callback_url'] = $this->config->get('iPay_payment_software_callback_url');
            }

            if (isset($this->request->post['iPay_payment_software_ipay_url'])) {
                $data['iPay_payment_software_ipay_url'] = $this->request->post['iPay_payment_software_ipay_url'];
            } else {
                $data['iPay_payment_software_ipay_url'] = $this->config->get('iPay_payment_software_ipay_url');
            }

            if (isset($this->request->post['iPay_payment_software_elipa_enabled'])) {
                $data['iPay_payment_software_elipa_enabled'] = $this->request->post['iPay_payment_software_elipa_enabled'];
            } else {
                $data['iPay_payment_software_elipa_enabled'] = $this->config->get('iPay_payment_software_elipa_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_mvisa_enabled'])) {
                $data['iPay_payment_software_mvisa_enabled'] = $this->request->post['iPay_payment_software_mvisa_enabled'];
            } else {
                $data['iPay_payment_software_mvisa_enabled'] = $this->config->get('iPay_payment_software_mvisa_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_mpesa_enabled'])) {
                $data['iPay_payment_software_mpesa_enabled'] = $this->request->post['iPay_payment_software_mpesa_enabled'];
            } else {
                $data['iPay_payment_software_mpesa_enabled'] = $this->config->get('iPay_payment_software_mpesa_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_airtel_enabled'])) {
                $data['iPay_payment_software_airtel_enabled'] = $this->request->post['iPay_payment_software_airtel_enabled'];
            } else {
                $data['iPay_payment_software_airtel_enabled'] = $this->config->get('iPay_payment_software_airtel_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_equity_enabled'])) {
                $data['iPay_payment_software_equity_enabled'] = $this->request->post['iPay_payment_software_equity_enabled'];
            } else {
                $data['iPay_payment_software_equity_enabled'] = $this->config->get('iPay_payment_software_equity_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_mobilebanking_enabled'])) {
                $data['iPay_payment_software_mobilebanking_enabled'] = $this->request->post['iPay_payment_software_mobilebanking_enabled'];
            } else {
                $data['iPay_payment_software_mobilebanking_enabled'] = $this->config->get('iPay_payment_software_mobilebanking_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_debitcard_enabled'])) {
                $data['iPay_payment_software_debitcard_enabled'] = $this->request->post['iPay_payment_software_debitcard_enabled'];
            } else {
                $data['iPay_payment_software_debitcard_enabled'] = $this->config->get('iPay_payment_software_debitcard_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_creditcard_enabled'])) {
                $data['iPay_payment_software_creditcard_enabled'] = $this->request->post['iPay_payment_software_creditcard_enabled'];
            } else {
                $data['iPay_payment_software_creditcard_enabled'] = $this->config->get('iPay_payment_software_creditcard_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_mkoporahisi_enabled'])) {
                $data['iPay_payment_software_mkoporahisi_enabled'] = $this->request->post['iPay_payment_software_mkoporahisi_enabled'];
            } else {
                $data['iPay_payment_software_mkoporahisi_enabled'] = $this->config->get('iPay_payment_software_mkoporahisi_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_saida_enabled'])) {
                $data['iPay_payment_software_saida_enabled'] = $this->request->post['iPay_payment_software_saida_enabled'];
            } else {
                $data['iPay_payment_software_saida_enabled'] = $this->config->get('iPay_payment_software_saida_enabled');
            }

            if (isset($this->request->post['iPay_payment_software_mode'])) {
                $data['iPay_payment_software_mode'] = $this->request->post['iPay_payment_software_mode'];
            } else {
                $data['iPay_payment_software_mode'] = $this->config->get('iPay_payment_software_mode');
            }

            if (isset($this->request->post['iPay_payment_software_method'])) {
                $data['iPay_payment_software_method'] = $this->request->post['iPay_payment_software_method'];
            } else {
                $data['iPay_payment_software_method'] = $this->config->get('iPay_payment_software_method');
            }

            if (isset($this->request->post['iPay_payment_software_order_status_id'])) {
                $data['iPay_payment_software_order_status_id'] = $this->request->post['iPay_payment_software_order_status_id'];
            } else {
                $data['iPay_payment_software_order_status_id'] = $this->config->get('iPay_payment_software_order_status_id');
            }

            $this->load->model('localisation/order_status');

            $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

            if (isset($this->request->post['iPay_payment_software_geo_zone_id'])) {
                $data['iPay_payment_software_geo_zone_id'] = $this->request->post['iPay_payment_software_geo_zone_id'];
            } else {
                $data['iPay_payment_software_geo_zone_id'] = $this->config->get('iPay_payment_software_geo_zone_id');
            }

            $this->load->model('localisation/geo_zone');

            $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

            if (isset($this->request->post['iPay_payment_software_status'])) {
                $data['iPay_payment_software_status'] = $this->request->post['iPay_payment_software_status'];
            } else {
                $data['iPay_payment_software_status'] = $this->config->get('iPay_payment_software_status');
            }

            if (isset($this->request->post['iPay_payment_software_total'])) {
                $data['iPay_payment_software_total'] = $this->request->post['iPay_payment_software_total'];
            } else {
                $data['iPay_payment_software_total'] = $this->config->get('iPay_payment_software_total');
            }

            if (isset($this->request->post['iPay_payment_software_sort_order'])) {
                $data['iPay_payment_software_sort_order'] = $this->request->post['iPay_payment_software_sort_order'];
            } else {
                $data['iPay_payment_software_sort_order'] = $this->config->get('iPay_payment_software_sort_order');
            }

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('payment/iPay_payment_software.tpl', $data));
        }

        private function validate()
        {
            if (!$this->user->hasPermission('modify', 'payment/iPay_payment_software')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }

            if (!$this->request->post['iPay_payment_software_merchant_name']) {
                $this->error['login'] = $this->language->get('error_login');
            }

            if (!$this->request->post['iPay_payment_software_merchant_key']) {
                $this->error['key'] = $this->language->get('error_key');
            }

            if (!$this->error) {
                return true;
            } else {
                return false;
            }
        }
    }
