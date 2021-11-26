<?php

class ControllerCommonOrderinfo extends Controller {

    public function index() {
        $this->load->model('sale/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = base64_decode($this->request->get['order_id']);
            $order_id = (int) $order_id;
            $this->request->get['order_id'] = $order_id;
        } else {
            $order_id = 0;
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        $order_transaction_data = $this->model_sale_order->getOrderTransactionId($order_id);

        if (count($order_transaction_data) > 0) {
            $data['order_transaction_id'] = trim($order_transaction_data['transaction_id']);
        }

        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {

            $kw_shipping_charges = 0;
            $kw_shipping_charges_vat = 0;

            $totals = $this->model_sale_order->getOrderTotals($order_info['order_id']);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('shipping' == $total['code']) {
                    $kw_shipping_charges = $total['value'];
                    break;
                }
            }
            $data['kw_shipping_charges'] = $kw_shipping_charges;

            foreach ($totals as $total) {
                if ('delivery_vat' == $total['code']) {
                    $kw_shipping_charges_vat = $total['value'];
                    break;
                }
            }
            $data['kw_shipping_charges_vat'] = $kw_shipping_charges_vat;
            $this->load->language('sale/order');

            $this->document->setTitle($this->language->get('heading_title'));

            $data['delivery_latitude'] = $order_info['latitude'];
            $data['delivery_longitude'] = $order_info['longitude'];
            $data['text_yes'] = $this->language->get('text_yes');
            $data['text_no'] = $this->language->get('text_no');

            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
            $data['text_driver_contact_no'] = $this->language->get('text_driver_contact_no');
            $data['text_driver_name'] = $this->language->get('text_driver_name');
            $data['text_pickup_notes'] = $this->language->get('text_pickup_notes');
            $data['text_final_amount'] = $this->language->get('text_final_amount');
            $data['text_driver_notes'] = $this->language->get('text_driver_notes');

            $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');

            $data['text_settle'] = $this->language->get('text_settle');
            $data['text_dropoff_notes'] = $this->language->get('text_dropoff_notes');
            $data['text_original_amount'] = $this->language->get('text_original_amount');

            $data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
            $data['text_completed'] = $this->language->get('text_completed');
            $data['text_cancelled'] = $this->language->get('text_cancelled');
            $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');

            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_replacable'] = $this->language->get('text_replacable');
            $data['text_not_replacable'] = $this->language->get('text_not_replacable');
            $data['text_replacable_title'] = $this->language->get('text_replacable_title');
            $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');

            $data['text_order_id'] = $this->language->get('text_order_id');
            $data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $data['text_invoice_date'] = $this->language->get('text_invoice_date');
            $data['text_store_name'] = $this->language->get('text_store_name');
            $data['text_store_url'] = $this->language->get('text_store_url');
            $data['text_customer'] = $this->language->get('text_customer');
            $data['text_name'] = $this->language->get('text_name');
            $data['text_store'] = $this->language->get('text_store');
            $data['text_status'] = $this->language->get('text_status');
            $data['text_payment_status'] = $this->language->get('text_payment_status');
            $data['text_paid'] = $this->language->get('text_paid');
            $data['text_unpaid'] = $this->language->get('text_unpaid');
            $data['text_commision'] = $this->language->get('text_commision');
            $data['text_expected_delivery_time'] = $this->language->get('text_expected_delivery_time');
            $data['text_contact_no'] = $this->language->get('text_contact_no');
            $data['text_address'] = $this->language->get('text_address');
            $data['text_customer_group'] = $this->language->get('text_customer_group');
            $data['text_email'] = $this->language->get('text_email');
            $data['text_telephone'] = $this->language->get('text_telephone');
            $data['text_fax'] = $this->language->get('text_fax');
            $data['text_total'] = $this->language->get('text_total');
            $data['text_reward'] = $this->language->get('text_reward');
            $data['text_order_status'] = $this->language->get('text_order_status');
            $data['text_comment'] = $this->language->get('text_comment');
            $data['text_affiliate'] = $this->language->get('text_affiliate');
            $data['text_commission'] = $this->language->get('text_commission');
            $data['text_ip'] = $this->language->get('text_ip');
            $data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
            $data['text_user_agent'] = $this->language->get('text_user_agent');
            $data['text_accept_language'] = $this->language->get('text_accept_language');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_date_modified'] = $this->language->get('text_date_modified');
            $data['text_firstname'] = $this->language->get('text_firstname');
            $data['text_lastname'] = $this->language->get('text_lastname');
            $data['text_company'] = $this->language->get('text_company');
            $data['text_address_1'] = $this->language->get('text_address_1');
            $data['text_address_2'] = $this->language->get('text_address_2');
            $data['text_city'] = $this->language->get('text_city');
            $data['text_postcode'] = $this->language->get('text_postcode');
            $data['text_zone'] = $this->language->get('text_zone');
            $data['text_zone_code'] = $this->language->get('text_zone_code');
            $data['text_country'] = $this->language->get('text_country');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $data['text_payment_method'] = $this->language->get('text_payment_method');
            $data['text_history'] = $this->language->get('text_history');
            $data['text_country_match'] = $this->language->get('text_country_match');
            $data['text_country_code'] = $this->language->get('text_country_code');
            $data['text_high_risk_country'] = $this->language->get('text_high_risk_country');
            $data['text_distance'] = $this->language->get('text_distance');
            $data['text_ip_region'] = $this->language->get('text_ip_region');
            $data['text_ip_city'] = $this->language->get('text_ip_city');
            $data['text_ip_latitude'] = $this->language->get('text_ip_latitude');
            $data['text_ip_longitude'] = $this->language->get('text_ip_longitude');
            $data['text_ip_isp'] = $this->language->get('text_ip_isp');
            $data['text_ip_org'] = $this->language->get('text_ip_org');
            $data['text_ip_asnum'] = $this->language->get('text_ip_asnum');
            $data['text_ip_user_type'] = $this->language->get('text_ip_user_type');
            $data['text_ip_country_confidence'] = $this->language->get('text_ip_country_confidence');
            $data['text_ip_region_confidence'] = $this->language->get('text_ip_region_confidence');
            $data['text_ip_city_confidence'] = $this->language->get('text_ip_city_confidence');
            $data['text_ip_postal_confidence'] = $this->language->get('text_ip_postal_confidence');
            $data['text_ip_postal_code'] = $this->language->get('text_ip_postal_code');
            $data['text_ip_accuracy_radius'] = $this->language->get('text_ip_accuracy_radius');
            $data['text_ip_net_speed_cell'] = $this->language->get('text_ip_net_speed_cell');
            $data['text_ip_metro_code'] = $this->language->get('text_ip_metro_code');
            $data['text_ip_area_code'] = $this->language->get('text_ip_area_code');
            $data['text_ip_time_zone'] = $this->language->get('text_ip_time_zone');
            $data['text_ip_region_name'] = $this->language->get('text_ip_region_name');
            $data['text_ip_domain'] = $this->language->get('text_ip_domain');
            $data['text_ip_country_name'] = $this->language->get('text_ip_country_name');
            $data['text_ip_continent_code'] = $this->language->get('text_ip_continent_code');
            $data['text_ip_corporate_proxy'] = $this->language->get('text_ip_corporate_proxy');
            $data['text_anonymous_proxy'] = $this->language->get('text_anonymous_proxy');
            $data['text_proxy_score'] = $this->language->get('text_proxy_score');
            $data['text_is_trans_proxy'] = $this->language->get('text_is_trans_proxy');
            $data['text_free_mail'] = $this->language->get('text_free_mail');
            $data['text_carder_email'] = $this->language->get('text_carder_email');
            $data['text_high_risk_username'] = $this->language->get('text_high_risk_username');
            $data['text_high_risk_password'] = $this->language->get('text_high_risk_password');
            $data['text_bin_match'] = $this->language->get('text_bin_match');
            $data['text_bin_country'] = $this->language->get('text_bin_country');
            $data['text_bin_name_match'] = $this->language->get('text_bin_name_match');
            $data['text_bin_name'] = $this->language->get('text_bin_name');
            $data['text_bin_phone_match'] = $this->language->get('text_bin_phone_match');
            $data['text_bin_phone'] = $this->language->get('text_bin_phone');
            $data['text_customer_phone_in_billing_location'] = $this->language->get('text_customer_phone_in_billing_location');
            $data['text_ship_forward'] = $this->language->get('text_ship_forward');
            $data['text_city_postal_match'] = $this->language->get('text_city_postal_match');
            $data['text_ship_city_postal_match'] = $this->language->get('text_ship_city_postal_match');
            $data['text_score'] = $this->language->get('text_score');
            $data['text_explanation'] = $this->language->get('text_explanation');
            $data['text_risk_score'] = $this->language->get('text_risk_score');
            $data['text_queries_remaining'] = $this->language->get('text_queries_remaining');
            $data['text_maxmind_id'] = $this->language->get('text_maxmind_id');
            $data['text_error'] = $this->language->get('text_error');
            $data['text_loading'] = $this->language->get('text_loading');
            $data['text_delivery_date'] = $this->language->get('text_delivery_date');
            $data['text_delivery_timeslot'] = $this->language->get('text_delivery_timeslot');

            $data['column_product'] = $this->language->get('column_product');
            $data['column_produce_type'] = $this->language->get('column_produce_type');

            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity') . '( Ordered )';
            $data['column_quantity_update'] = $this->language->get('column_quantity') . '( Updated )';
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');
            $data['column_name'] = $this->language->get('column_name');

            $data['column_unit'] = $this->language->get('column_unit');

            $data['entry_order_status'] = $this->language->get('entry_order_status');
            $data['entry_notify'] = $this->language->get('entry_notify');
            $data['entry_comment'] = $this->language->get('entry_comment');

            $data['button_invoice_print'] = $this->language->get('button_invoice_print');
            $data['button_shipping_print'] = $this->language->get('button_shipping_print');
            $data['button_edit'] = $this->language->get('button_edit');
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['button_generate'] = $this->language->get('button_generate');
            $data['button_reward_add'] = $this->language->get('button_reward_add');
            $data['button_reward_remove'] = $this->language->get('button_reward_remove');
            $data['button_commission_add'] = $this->language->get('button_commission_add');
            $data['button_commission_remove'] = $this->language->get('button_commission_remove');
            $data['button_commision_pay'] = $this->language->get('button_commision_pay');
            $data['button_commision_unpaid'] = $this->language->get('button_commision_unpaid');
            $data['button_history_add'] = $this->language->get('button_history_add');
            $data['button_not_fraud'] = $this->language->get('button_not_fraud');
            $data['button_reverse_payment'] = $this->language->get('button_reverse_payment');

            $data['tab_order'] = $this->language->get('tab_order');
            $data['tab_payment'] = $this->language->get('tab_payment');
            $data['tab_shipping'] = $this->language->get('tab_shipping');
            $data['tab_product'] = $this->language->get('tab_product');

            $url = '';

            //$this->load->model('payment/iugu');

            require_once DIR_SYSTEM . 'library/Iugu.php';

            //$this->load->model('sale/order');
            $iuguData = $this->model_sale_order->getOrderIugu($order_id);

            $data['settlement_tab'] = false;

            if ($iuguData) {
                $invoiceId = $iuguData['invoice_id'];

                Iugu::setApiKey($this->config->get('iugu_token'));

                $invoice = Iugu_Invoice::fetch($invoiceId);

                //if($invoice['status'] == 'paid') {
                //disable settlement tab
                $data['settlement_tab'] = true;
                //}
            }

            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
                $data['shipping'] = $this->url->link('sale/order/shipping', 'store_id=' . $store_id . '&token=' . $this->session->data['token'] . '&order_id=' . (int) $this->request->get['order_id'], 'SSL');

                if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
                    $data['invoice'] = $this->url->link('sale/order/newinvoice', 'store_id=' . $store_id . '&token=' . $this->session->data['token'] . '&order_id=' . (int) $this->request->get['order_id'], 'SSL');
                    $data['invoicepdf'] = $this->url->link('sale/order/newinvoicepdf', 'store_id=' . $store_id . '&token=' . $this->session->data['token'] . '&order_id=' . (int) $this->request->get['order_id'], 'SSL');
                } else {
                    $data['invoice'] = $this->url->link('sale/order/invoice', 'store_id=' . $store_id . '&token=' . $this->session->data['token'] . '&order_id=' . (int) $this->request->get['order_id'], 'SSL');
                    $data['invoicepdf'] = $this->url->link('sale/order/invoicepdf', 'store_id=' . $store_id . '&token=' . $this->session->data['token'] . '&order_id=' . (int) $this->request->get['order_id'], 'SSL');
                }
            } else {
                
            }

            if ($this->user->isLogged() && $this->session->data['token'] != NULL) {
                $data['order_link'] = $this->url->adminLinkss('sale/order', 'token=' . $this->session->data['token'].'&filter_order_id=' . $order_id, 'SSL');
            } else {
                $data['login_link'] = $this->url->adminLinkss('sale/order', 'token=' . $this->session->data['token'].'&filter_order_id=' . $order_id, 'SSL');
            }


            $data['order_id'] = $this->request->get['order_id'];

            if ($order_info['invoice_no']) {
                $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'] . $order_info['invoice_sufix'];
            } else {
                $data['invoice_no'] = '';
            }

            if ($order_info['order_id']) {
                $data['order_id'] = $order_info['order_id'];
            } else {
                $data['order_id'] = 0;
            }

            if ($order_info['settlement_amount']) {
                $data['settlement_amount'] = $order_info['settlement_amount'];
            } else {
                $data['settlement_amount'] = 0;
            }

            if (!is_null($order_info['rating'])) {
                $data['rating'] = $order_info['rating'];
            } else {
                $data['rating'] = null;
            }

            //echo "<pre>";print_r($order_info);die;
            //dropoff latitude  and pikcup latitide

            if ($order_info['latitude']) {
                $data['dropoff_latitude'] = $order_info['latitude'];
            } else {
                $data['dropoff_latitude'] = '';
            }

            if ($order_info['longitude']) {
                $data['dropoff_longitude'] = $order_info['longitude'];
            } else {
                $data['dropoff_longitude'] = '';
            }

            $store_data = $this->model_sale_order->getStoreData($order_info['store_id']);

            $data['filter_stores'] = [];

            $this->load->model('setting/store');

            if ($this->user->isVendor()) {
                $rows = $this->model_setting_store->getStoreIds($this->user->getId());
                foreach ($rows as $row) {
                    $data['filter_stores'][] = $row['store_id'];
                }
            }

            $data['store_name'] = $order_info['store_name'];

            $data['settlement_done'] = $order_info['settlement_amount'];

            $data['store_url'] = $order_info['store_url'];
            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];
            $data['store_id'] = $order_info['store_id'];

            $data['delivery_details'] = false;

            $data['delivery_date'] = $order_info['delivery_date'];
            $data['delivery_timeslot'] = $order_info['delivery_timeslot'];

            $data['shipping_zipcode'] = $order_info['shipping_zipcode'];
            $data['shipping_lat'] = $order_info['latitude'];
            $data['shipping_lon'] = $order_info['longitude'];
            $data['shipping_building_name'] = $order_info['shipping_building_name'];

            $data['shipping_landmark'] = $order_info['shipping_landmark'];
            $data['shipping_flat_number'] = $order_info['shipping_flat_number'];

            $data['status'] = $this->model_sale_order->getStatus($order_info['order_status_id']);

            //added
            $data['questions'] = $this->model_sale_order->getOrderQuestions($order_id);

            $data['commsion_received'] = $order_info['commsion_received'];
            //$data['commission'] = $order_info['commission'];

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');

            $data['delivery_id'] = $order_info['delivery_id']; //"del_XPeEGFX3Hc4ZeWg5";//
            $data['shopper_link'] = $this->config->get('config_shopper_link') . '/storage/';

            $data['products_status'] = [];
            $data['delivery_data'] = [];

            $log = new Log('error.log');

            if (isset($data['delivery_id'])) {
                $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                if ($response['status']) {
                    $data['tokens'] = $response['token'];
                    $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus', $data);

                    $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus', $data);

                    if (!$resp['status'] || isset($resp['error'])) {
                        $data['delivery_data'] = [];
                    } else {
                        $data['delivery_data'] = $resp['data'][0];
                        if (isset($data['delivery_data']->assigned_to)) {
                            $data['delivery_details'] = true;
                        }
                    }

                    if (!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
                        $data['products_status'] = [];
                    } else {
                        $data['products_status'] = $productStatus['data'];
                    }
                }
            }

            $data['email'] = $order_info['email'];
            $this->load->model('sale/customer');
            $parent_user_info = $this->model_sale_customer->getCustomerParentDetails($order_info['customer_id']);
            if ($parent_user_info != NULL && $parent_user_info['email'] != NULL) {
                $data['parent_user_email'] = $parent_user_info['email'];
                $data['parent_customer_link'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_email=' . $parent_user_info['email'], 'SSL');
            } else {
                $data['parent_user_email'] = NULL;
            }

            $data['head_chef'] = NULL;
            $data['procurement'] = NULL;
            if (($order_info['head_chef'] != 'Approved' || $order_info['procurement'] != 'Approved') && $parent_user_info != NULL) {
                $this->load->model('account/customer');
                $data['head_chef'] = $this->model_account_customer->getHeadChef($parent_user_info['customer_id']);
                $data['head_chef_link'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_email=' . $data['head_chef']['email'], 'SSL');
                $data['procurement'] = $this->model_account_customer->getProcurement($parent_user_info['customer_id']);
                $data['procurement_link'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_email=' . $data['procurement']['email'], 'SSL');
            }

            $data['telephone'] = $order_info['telephone'];
            $data['fax'] = $order_info['fax'];

            $data['account_custom_field'] = $order_info['custom_field'];

            // Uploaded files
            $this->load->model('tool/upload');

            // Custom Fields
            $this->load->model('sale/custom_field');

            $data['account_custom_fields'] = [];

            $custom_fields = $this->model_sale_custom_field->getCustomFields();

            foreach ($custom_fields as $custom_field) {
                if ('account' == $custom_field['location'] && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
                    if ('select' == $custom_field['type'] || 'radio' == $custom_field['type']) {
                        $custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['account_custom_fields'][] = [
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                            ];
                        }
                    }

                    if ('checkbox' == $custom_field['type'] && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['account_custom_fields'][] = [
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                ];
                            }
                        }
                    }

                    if ('text' == $custom_field['type'] || 'textarea' == $custom_field['type'] || 'file' == $custom_field['type'] || 'date' == $custom_field['type'] || 'datetime' == $custom_field['type'] || 'time' == $custom_field['type']) {
                        $data['account_custom_fields'][] = [
                            'name' => $custom_field['name'],
                            'value' => $order_info['custom_field'][$custom_field['custom_field_id']],
                        ];
                    }

                    if ('file' == $custom_field['type']) {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['account_custom_fields'][] = [
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                            ];
                        }
                    }
                }
            }

            $data['comment'] = nl2br($order_info['comment']);

            $data['shipping_method'] = $order_info['shipping_method'];

            $data['allowedShippingMethods'] = false;

            $allowedShippingMethods = $this->config->get('config_delivery_shipping_methods_status');
            //echo "<pre>";print_r($allowedShippingMethods);die;
            if (is_array($allowedShippingMethods) && count($allowedShippingMethods) > 0) {
                foreach ($allowedShippingMethods as $method) {
                    if ($order_info['shipping_code'] == $method . '.' . $method) {
                        $data['allowedShippingMethods'] = true;
                    }
                }
            }

            $data['payment_method'] = $order_info['payment_method'];

            $data['original_final_total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

            //$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

            $data['original_total'] = 0;

            $sql = 'SELECT    * FROM `' . DB_PREFIX . 'order_total`  WHERE code = "sub_total" and  order_id = "' . $order_info['order_id'] . '"';

            $iuguData = $this->db->query($sql)->row;

            if ($iuguData) {
                $data['original_total'] = $iuguData['value'];
            }

            $this->load->model('sale/customer');

            $data['reward'] = $order_info['reward'];

            $data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

            $data['affiliate_firstname'] = '';
            $data['affiliate_lastname'] = '';

            if ($order_info['affiliate_id']) {
                $data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
            } else {
                $data['affiliate'] = '';
            }

            $data['commission'] = number_format($order_info['commission'], 2);

            $this->load->model('marketing/affiliate');

            $data['commission_total'] = '';

            $this->load->model('localisation/order_status');

            $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

            if ($order_status_info) {
                $data['order_status'] = $order_status_info['name'];
                $data['order_status_color'] = $order_status_info['color'];
            } else {
                $data['order_status'] = '';
                $data['order_status_color'] = '';
            }
            //echo '<pre>';print_r($order_info);exit;
            $data['ip'] = $order_info['ip'];
            $data['forwarded_ip'] = $order_info['forwarded_ip'];
            $data['user_agent'] = $order_info['user_agent'];
            $data['accept_language'] = $order_info['accept_language'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
            $data['login_latitude'] = $order_info['login_latitude'] == NULL || $order_info['login_latitude'] == 0 || $order_info['login_latitude'] == '' ? 'NA' : $order_info['login_latitude'];
            $data['login_longitude'] = $order_info['login_longitude'] == NULL || $order_info['login_longitude'] > 0 || $order_info['login_longitude'] == '' ? 'NA' : $order_info['login_longitude'];
            // Custom fields
            $data['payment_custom_fields'] = [];

            foreach ($custom_fields as $custom_field) {
                if ('address' == $custom_field['location'] && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
                    if ('select' == $custom_field['type'] || 'radio' == $custom_field['type']) {
                        $custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['payment_custom_fields'][] = [
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                            ];
                        }
                    }

                    if ('checkbox' == $custom_field['type'] && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['payment_custom_fields'][] = [
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                ];
                            }
                        }
                    }

                    if ('text' == $custom_field['type'] || 'textarea' == $custom_field['type'] || 'file' == $custom_field['type'] || 'date' == $custom_field['type'] || 'datetime' == $custom_field['type'] || 'time' == $custom_field['type']) {
                        $data['payment_custom_fields'][] = [
                            'name' => $custom_field['name'],
                            'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
                        ];
                    }

                    if ('file' == $custom_field['type']) {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['payment_custom_fields'][] = [
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                            ];
                        }
                    }
                }
            }

            // Shipping
            $data['shipping_name'] = $order_info['shipping_name'];
            $data['shipping_city'] = $order_info['shipping_city'];
            $data['shipping_contact_no'] = $order_info['shipping_contact_no'];
            $data['shipping_address'] = $order_info['shipping_address'];
            $data['order_vehicle_number'] = $order_info['vehicle_number'];

            $this->load->model('drivers/drivers');
            $order_driver_details = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
            if (is_array($order_driver_details) && $order_driver_details != NULL) {
                $data['order_driver_details'] = $order_driver_details;
            } else {
                $data['order_driver_details'] = NULL;
            }

            $this->load->model('executives/executives');
            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if (is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
                $data['order_delivery_executive_details'] = $order_delivery_executive_details;
            } else {
                $data['order_delivery_executive_details'] = NULL;
            }

            $this->load->model('orderprocessinggroup/orderprocessinggroup');
            $this->load->model('orderprocessinggroup/orderprocessor');

            $order_processing_group_details = $this->model_orderprocessinggroup_orderprocessinggroup->getOrderProcessingGroup($order_info['order_processing_group_id']);
            $order_processor = $this->model_orderprocessinggroup_orderprocessor->getOrderProcessor($order_info['order_processor_id']);
            if (is_array($order_processing_group_details) && $order_processing_group_details != NULL) {
                $data['order_processing_group_details'] = $order_processing_group_details;
            } else {
                $data['order_processing_group_details'] = NULL;
            }

            if (is_array($order_processor) && $order_processor != NULL) {
                $data['order_processor'] = $order_processor;
            } else {
                $data['order_processor'] = NULL;
            }

            $data['shipping_custom_fields'] = [];

            foreach ($custom_fields as $custom_field) {
                if ('address' == $custom_field['location'] && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
                    if ('select' == $custom_field['type'] || 'radio' == $custom_field['type']) {
                        $custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

                        if ($custom_field_value_info) {
                            $data['shipping_custom_fields'][] = [
                                'name' => $custom_field['name'],
                                'value' => $custom_field_value_info['name'],
                            ];
                        }
                    }

                    if ('checkbox' == $custom_field['type'] && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
                        foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                            $custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($custom_field_value_id);

                            if ($custom_field_value_info) {
                                $data['shipping_custom_fields'][] = [
                                    'name' => $custom_field['name'],
                                    'value' => $custom_field_value_info['name'],
                                ];
                            }
                        }
                    }

                    if ('text' == $custom_field['type'] || 'textarea' == $custom_field['type'] || 'file' == $custom_field['type'] || 'date' == $custom_field['type'] || 'datetime' == $custom_field['type'] || 'time' == $custom_field['type']) {
                        $data['shipping_custom_fields'][] = [
                            'name' => $custom_field['name'],
                            'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
                        ];
                    }

                    if ('file' == $custom_field['type']) {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

                        if ($upload_info) {
                            $data['shipping_custom_fields'][] = [
                                'name' => $custom_field['name'],
                                'value' => $upload_info['name'],
                            ];
                        }
                    }
                }
            }

            $data['products'] = [];

            $data['is_edited'] = $this->model_sale_order->hasRealOrderProducts($this->request->get['order_id']);

            $data['original_products'] = [];

            $data['difference_products'] = [];

            $EditedProducts = $this->model_sale_order->getRealOrderProducts($this->request->get['order_id']);
            $original_products = $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
            if ($this->model_sale_order->hasRealOrderProducts($this->request->get['order_id'])) {
                foreach ($original_products as $original_product) {
                    $option_data = [];

                    $options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $original_product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $option_data[] = [
                                'name' => $option['name'],
                                'value' => $option['value'],
                                'type' => $option['type'],
                            ];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $option_data[] = [
                                    'name' => $option['name'],
                                    'value' => $upload_info['name'],
                                    'type' => $option['type'],
                                    'href' => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL'),
                                ];
                            }
                        }
                    }

                    $present = false;

                    foreach ($EditedProducts as $EditedProduct) {
                        if ($original_product['product_id'] == $EditedProduct['product_id']) {
                            $original_product['quantity_updated'] = $EditedProduct['quantity'];
                            $original_product['unit_updated'] = $EditedProduct['unit'];
                        }

                        if (!empty($original_product['name']) && $original_product['name'] == $EditedProduct['name'] && $original_product['unit'] == $EditedProduct['unit'] && $original_product['quantity'] == $EditedProduct['quantity']) {
                            $present = true;
                        }
                    }

                    $data['original_products'][] = [
                        'order_product_id' => $original_product['order_product_id'],
                        'product_id' => $original_product['product_id'],
                        'vendor_id' => $original_product['vendor_id'],
                        'store_id' => $original_product['store_id'],
                        'name' => $original_product['name'],
                        'product_note' => $original_product['product_note'],
                        'unit' => $original_product['unit'],
                        'product_type' => $original_product['product_type'],
                        'produce_type' => $original_product['produce_type'],
                        'model' => $original_product['model'],
                        'option' => $option_data,
                        'quantity' => $original_product['quantity'],
                        'quantity_updated' => $original_product['quantity_updated'],
                        'unit_updated' => $original_product['unit_updated'], //as of now unit change is not there
                        'price' => $this->currency->format($original_product['price'] + ($this->config->get('config_tax') ? $original_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($original_product['total'] + ($this->config->get('config_tax') ? ($original_product['tax'] * $original_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];

                    //echo '<pre>';print_r($original_product);exit;
                    if (!$present && !empty($original_product['name'])) {
                        $data['difference_products'][] = [
                            'order_product_id' => $original_product['order_product_id'],
                            'product_id' => $original_product['product_id'],
                            'vendor_id' => $original_product['vendor_id'],
                            'store_id' => $original_product['store_id'],
                            'name' => $original_product['name'],
                            'product_note' => $original_product['product_note'],
                            'unit' => $original_product['unit'],
                            'product_type' => $original_product['product_type'],
                            'produce_type' => $original_product['produce_type'],
                            'model' => $original_product['model'],
                            'option' => $option_data,
                            'quantity_updated' => $original_product['quantity_updated'],
                            'unit_updated' => $original_product['unit_updated'], //as of now unit change is not there
                            'quantity' => $original_product['quantity'],
                            'price' => $this->currency->format($original_product['price'] + ($this->config->get('config_tax') ? $original_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                            'total' => $this->currency->format($original_product['total'] + ($this->config->get('config_tax') ? ($original_product['tax'] * $original_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                            'href' => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $original_product['product_id'], 'SSL'),
                        ];
                    }
                }
                //echo '<pre>';print_r($data['difference_products']);exit;
                //echo "<pre>";print_r($data['original_products']);die;

                $products = $this->model_sale_order->getRealOrderProducts($this->request->get['order_id']);
            } else {
                $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
                foreach ($original_products as $original_product) {
                    $data['original_products'][] = [
                        'order_product_id' => $original_product['order_product_id'],
                        'product_id' => $original_product['product_id'],
                        'vendor_id' => $original_product['vendor_id'],
                        'store_id' => $original_product['store_id'],
                        'name' => $original_product['name'],
                        'product_note' => $original_product['product_note'],
                        'unit' => $original_product['unit'],
                        'product_type' => $original_product['product_type'],
                        'produce_type' => $original_product['produce_type'],
                        'model' => $original_product['model'],
                        'option' => $option_data,
                        'quantity' => $original_product['quantity'],
                        'quantity_updated' => '-',
                        'unit_updated' => '-',
                        'price' => $this->currency->format($original_product['price'] + ($this->config->get('config_tax') ? $original_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($original_product['total'] + ($this->config->get('config_tax') ? ($original_product['tax'] * $original_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'href' => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $original_product['product_id'], 'SSL'),
                    ];
                }
            }

            foreach ($products as $product) {
                $option_data = [];

                $options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ('file' != $option['type']) {
                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => $option['value'],
                            'type' => $option['type'],
                        ];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $option_data[] = [
                                'name' => $option['name'],
                                'value' => $upload_info['name'],
                                'type' => $option['type'],
                                'href' => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL'),
                            ];
                        }
                    }
                }

                $data['products'][] = [
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'vendor_id' => $product['vendor_id'],
                    'store_id' => $product['store_id'],
                    'name' => $product['name'],
                    'product_note' => $product['product_note'],
                    'unit' => $product['unit'],
                    'product_type' => $product['product_type'],
                    'produce_type' => $product['produce_type'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'quantity_updated' => $product['quantity'],
                    'unit_updated' => $product['unit'], //as of now unit change is not there
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                ];

                //   echo '<pre>';print_r($data['products']);exit;
            }

            $data['country_code'] = '+' . $this->config->get('config_telephone_code');
            //echo "<pre>";print_r($data['products']);die;

            $totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

            //echo "<pre>";print_r($totals);die;

            $data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'code' => $total['code'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                ];

                if ('total' == $total['code']) {
                    $data['total'] = $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']);
                }
            }

            $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

            $data['order_status_id'] = $order_info['order_status_id'];

            $data['order_status_name'] = '';

            $orderStatusDetail = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

            if (is_array($orderStatusDetail) && isset($orderStatusDetail['name'])) {
                $data['order_status_name'] = $orderStatusDetail['name'];
            }

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('sale/order_infos.tpl', $data));
        } else {
            $this->load->language('error/not_found');

            $this->document->setTitle($this->language->get('heading_title'));

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_not_found'] = $this->language->get('text_not_found');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
            ];

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
        }
    }

}
