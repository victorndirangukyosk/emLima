<?php

class ControllerSaleFastOrder extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('sale/order');

        $this->document->setTitle($this->language->get('heading_title_fastorders'));

        $this->load->model('sale/order');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('sale/order');

        $this->document->setTitle($this->language->get('heading_title_fastorders'));

        $this->load->model('sale/order');

        unset($this->session->data['cookie']);

        if ($this->validate()) {
            // API
            $this->load->model('user/api');

            $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

            if ($api_info) {
                $curl = curl_init();

                // Set SSL if required
                if ('https' == substr(HTTPS_CATALOG, 0, 5)) {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG.'index.php?path=api/login');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

                $json = curl_exec($curl);

                if (!$json) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($json, true);

                    if (isset($response['cookie'])) {
                        $this->session->data['cookie'] = $response['cookie'];
                    }

                    curl_close($curl);
                }
            }
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('sale/order');

        $this->document->setTitle($this->language->get('heading_title_fastorders'));

        $this->load->model('sale/order');

        unset($this->session->data['cookie']);

        if ($this->validate()) {
            // API
            $this->load->model('user/api');

            $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

            if ($api_info) {
                $curl = curl_init();

                // Set SSL if required
                if ('https' == substr(HTTPS_CATALOG, 0, 5)) {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG.'index.php?path=api/login');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

                $json = curl_exec($curl);

                if (!$json) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($json, true);

                    if (isset($response['cookie'])) {
                        $this->session->data['cookie'] = $response['cookie'];
                    }

                    curl_close($curl);
                }
            }
        }

        $this->getForm();
    }

    public function EditInvoice()
    {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['token'] = $this->session->data['token'];

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_contact_no'] = $this->language->get('text_contact_no');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');

        $data['column_unit'] = $this->language->get('column_unit');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_cpf_number'] = $this->language->get('text_cpf_number');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $this->load->model('extension/extension');

        $sort_order = [];

        $data['allCodes'] = $this->model_extension_extension->getExtensions('total');

        foreach ($data['allCodes'] as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
        }
        array_multisort($sort_order, SORT_ASC, $data['allCodes']);

        $data['orders'] = [];

        $orders = [];

        if (isset($this->request->post['selected'])) {
            $orders = $this->request->post['selected'];
        } elseif (isset($this->request->get['order_id'])) {
            $orders[] = $this->request->get['order_id'];
            $data['order_id'] = $this->request->get['order_id'];
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        foreach ($orders as $order_id) {
            require_once DIR_SYSTEM.'library/Iugu.php';

            //$this->load->model('sale/order');
            $iuguData = $this->model_sale_order->getOrderIugu($order_id);

            $data['settlement_tab'] = false;
            $data['settlement_done'] = true;

            if ($iuguData) {
                $invoiceId = $iuguData['invoice_id'];

                Iugu::setApiKey($this->config->get('iugu_token'));

                $invoice = Iugu_Invoice::fetch($invoiceId);

                // echo "<pre>";print_r($invoice);die;
                if ('paid' == $invoice['status']) {
                    //enable refund button
                    $data['settlement_done'] = false;
                }

                $data['settlement_tab'] = true;
            }

            //echo "<pre>";print_r($data);die;
            $order_info = $this->model_sale_order->getOrder($order_id);

            //check vendor order

            if ($this->user->isVendor()) {
                if (!$this->isVendorOrder($order_id)) {
                    $this->response->redirect($this->url->link('error/not_found'));
                }
            }

            //$data['settlement_done'] = false;
            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

                // if ($store_info) {
                //     $store_address = $store_info['config_address'];
                //     $store_email = $store_info['config_email'];
                //     $store_telephone = $store_info['config_telephone'];
                //     $store_fax = $store_info['config_fax'];
                // } else {
                //     $store_address = $this->config->get('config_address');
                //     $store_email = $this->config->get('config_email');
                //     $store_telephone = $this->config->get('config_telephone');
                //     $store_fax = $this->config->get('config_fax');
                // }

                $store_data = $this->model_sale_order->getStoreData($order_info['store_id']);

                $data['customer_id'] = $order_info['customer_id'];

                if ($store_data) {
                    $store_address = $store_data['address'];
                    $store_email = $store_data['email'];
                    $store_telephone = $store_data['telephone'];
                    $store_fax = $store_data['fax'];
                    $store_tax = $store_data['tax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                    $store_tax = '';
                }

                $data['store_name'] = $store_data['name'];

                if ($order_info['invoice_no']) {
                    $invoice_no = $order_info['invoice_prefix'].$order_info['invoice_no'];
                } else {
                    $invoice_no = '';
                }

                //$data['settlement_done'] = $order_info['settlement_amount'];

                $this->load->model('tool/upload');

                $product_data = [];

                if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
                    $products = $this->model_sale_order->getRealOrderProducts($order_id);
                } else {
                    $products = $this->model_sale_order->getOrderProducts($order_id);
                }

                foreach ($products as $product) {
                    if ($store_id && $product['store_id'] != $store_id) {
                        continue;
                    }
                    $option_data = [];

                    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => $value,
                        ];
                    }

                    $product_data[] = [
                        'name' => $product['name'],
                        'product_id' => $product['product_id'],
                        'model' => $product['model'],
                        'unit' => $product['unit'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'price' => $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
                        'total' => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),
                    ];
                }

                $total_data = [];

                if ($store_id) {
                    $totals = $this->model_sale_order->getVendorOrderTotals($order_id, $store_id);
                } else {
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                }

                foreach ($totals as $total) {
                    $total_data[] = [
                        'title' => $total['title'],
                        'code' => $total['code'],
                        //'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                        'text' => $total['value'],
                    ];
                }

                $data['orders'][] = [
                    'order_id' => $order_id,
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                    'store_name' => $order_info['store_name'],
                    'store_url' => rtrim($order_info['store_url'], '/'),
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_tax' => $store_tax,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'cpf_number' => $this->getUser($order_info['customer_id']),
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_city' => $order_info['shipping_city'],
                    'shipping_contact_no' => $order_info['shipping_contact_no'],
                    'shipping_name' => $order_info['shipping_name'],
                    'shipping_method' => $order_info['shipping_method'],
                    'payment_method' => $order_info['payment_method'],
                    'product' => $product_data,
                    'total' => $total_data,
                    'customer_id' => $order_info['customer_id'],
                    'comment' => nl2br($order_info['comment']),
                ];
            }
        }

        $this->response->setOutput($this->load->view('sale/edit_order_invoice.tpl', $data));
    }

    public function delete()
    {
        //check vendor order
        if ($this->user->isVendor()) {
            die('not allowed!');
        }

        $this->load->language('sale/order');

        $this->document->setTitle($this->language->get('heading_title_fastorders'));

        $this->load->model('sale/order');

        unset($this->session->data['cookie']);

        if (isset($this->request->get['order_id']) && $this->validateDelete()) {
            // API
            $this->load->model('user/api');

            $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

            if ($api_info) {
                $curl = curl_init();

                // Set SSL if required
                if ('https' == substr(HTTPS_CATALOG, 0, 5)) {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG.'index.php?path=api/login');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

                $json = curl_exec($curl);

                if (!$json) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($json, true);

                    if (isset($response['cookie'])) {
                        $this->session->data['cookie'] = $response['cookie'];
                    }

                    curl_close($curl);
                }
            }
        }

        if (isset($this->session->data['cookie'])) {
            $curl = curl_init();

            // Set SSL if required
            if ('https' == substr(HTTPS_CATALOG, 0, 5)) {
                curl_setopt($curl, CURLOPT_PORT, 443);
            }

            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG.'index.php?path=api/order/delete&order_id='.$this->request->get['order_id']);
            curl_setopt($curl, CURLOPT_COOKIE, session_name().'='.$this->session->data['cookie'].';');

            $json = curl_exec($curl);

            if (!$json) {
                $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
            } else {
                $response = json_decode($json, true);

                curl_close($curl);

                if (isset($response['error'])) {
                    $this->error['warning'] = $response['error'];
                }
            }
        }

        if (isset($response['error'])) {
            $this->error['warning'] = $response['error'];
        }

        if (isset($response['success'])) {
            $this->session->data['success'] = $response['success'];

            $url = '';

            if (isset($this->request->get['filter_city'])) {
                $url .= '&filter_city='.$this->request->get['filter_city'];
            }

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_customer'])) {
                $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_vendor'])) {
                $url .= '&filter_vendor='.urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_store_name'])) {
                $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_delivery_method'])) {
                $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_payment'])) {
                $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_status'])) {
                $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
            }

            if (isset($this->request->get['filter_order_day'])) {
                $url .= '&filter_order_day='.$this->request->get['filter_order_day'];
            }

            if (isset($this->request->get['filter_total'])) {
                $url .= '&filter_total='.$this->request->get['filter_total'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_modified'])) {
                $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('sale/order', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }


        if (isset($this->request->get['filter_order_from_id'])) {
            $filter_order_from_id = $this->request->get['filter_order_from_id'];
        } else {
            $filter_order_from_id = null;
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $filter_order_to_id = $this->request->get['filter_order_to_id'];
        } else {
            $filter_order_to_id = null;
        }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = null;
        }

        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = null;
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $filter_delivery_method = $this->request->get['filter_delivery_method'];
        } else {
            $filter_delivery_method = null;
        }

        if (isset($this->request->get['filter_payment'])) {
            $filter_payment = $this->request->get['filter_payment'];
        } else {
            $filter_payment = null;
        }

        if (isset($this->request->get['filter_order_type'])) {
            $filter_order_type = $this->request->get['filter_order_type'];
        } else {
            $filter_order_type = null;
        }


        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_order_day'])) {
            $filter_order_day = $this->request->get['filter_order_day'];
        } else {
            $filter_order_day = 'today';
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

         if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'delivery_timeslot'; //'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_order_from_id'])) {
            $url .= '&filter_order_from_id='.$this->request->get['filter_order_from_id'];
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $url .= '&filter_order_to_id='.$this->request->get['filter_order_to_id'];
        }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date=' . urlencode(html_entity_decode($this->request->get['filter_delivery_date'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_order_day'])) {
            $url .= '&filter_order_day='.$this->request->get['filter_order_day'];
        }


        if (isset($this->request->get['filter_order_type'])) {
            $url .= '&filter_order_type=' . $this->request->get['filter_order_type'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }


        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }
        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title_fastorders'),
            'href' => $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
        $data['shipping'] = $this->url->link('sale/order/shipping', 'token='.$this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('sale/fast_order/add', 'token='.$this->session->data['token'], 'SSL');

        $data['orders'] = [];


        // $data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'], 'SSL');
        // $data['delivery_sheet'] = $this->url->link('sale/order/consolidatedOrderSheet', 'token=' . $this->session->data['token'], 'SSL');
         



        $filter_data = [
            'filter_city' => $filter_city,
            'filter_order_id' => $filter_order_id,
            'filter_order_from_id' => $filter_order_from_id,
            'filter_order_to_id' => $filter_order_to_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_vendor' => $this->getUserByName($filter_vendor),
            'filter_store_name' => $filter_store_name,
            'filter_delivery_method' => $filter_delivery_method,
            'filter_delivery_date' => $filter_delivery_date,

            'filter_payment' => $filter_payment,
            'filter_order_status' => $filter_order_status,
            'filter_order_type' => $filter_order_type,

            'filter_order_day' => $filter_order_day,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,

            'filter_date_modified' => $filter_date_modified,
            'sort' => $sort,
            'order' => $order,// all orders are fecting by group, so dont send limit
            // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            // 'limit' => $this->config->get('config_limit_admin'),
        ];

        //echo "<pre>";print_r($filter_data);die;

        $order_total_final = [];
        $results_final = [];

        if (!empty($filter_data['filter_order_status']) || !empty($filter_data['filter_order_day'])) {
            //echo "<pre>";print_r($filter_data['filter_order_status']);die;
            $filter_order_status_temp = explode(',', $filter_data['filter_order_status']);

            //echo "<pre>";print_r($filter_order_status_temp);die;

            foreach ($filter_order_status_temp as $tmp) {
                // code...
                $filter_data['filter_order_status'] = $tmp;

                // $order_total = $this->model_sale_order->getTotalOrdersFilter($filter_data);
                 $order_total = $this->model_sale_order->getTotalOrders($filter_data);

                //echo "<pre>";print_r($filter_data);die;
                // $results = $this->model_sale_order->getOrdersFilter($filter_data);
                $results = $this->model_sale_order->getOrders($filter_data);

                //echo "<pre>";print_r($results);die;

                if ('delivery_timeslot' == $filter_data['sort']) {
                    //echo "<pre>";print_r("Ce");die;
                    $temp = $results;

                    $amTimeslot = [];
                    $pmTimeslot = [];
                    $inPmfirstTimeslot = [];

                    //sort timeslot based

                    //echo "<pre>";print_r($temp);die;
                    foreach ($temp as $temp1) {
                        $temp2 = explode('-', $temp1['delivery_timeslot']);

                        if (false !== strpos($temp2[0], 'am')) {
                            array_push($amTimeslot, $temp1);
                        } else {
                            if ('12' == substr($temp2[0], 0, 2)) {
                                array_push($inPmfirstTimeslot, $temp1);
                            } else {
                                array_push($pmTimeslot, $temp1);
                            }
                        }
                    }

                    // echo "<pre>";print_r($amTimeslot);die;
                    // echo "<pre>";print_r($pmTimeslot);die;

                    foreach ($inPmfirstTimeslot as $te) {
                        array_push($amTimeslot, $te);
                    }

                    foreach ($pmTimeslot as $te) {
                        array_push($amTimeslot, $te);
                    }

                    $results = $amTimeslot;

                    //echo "<pre>";print_r($results);die;
                    if ('ASC' == $filter_data['order']) {
                        $results = array_reverse($results, true);
                    }
                }

                // echo "<pre>";print_r($amTimeslot);die;

                // echo "<pre>";print_r($results);die;
                array_push($order_total_final, $order_total);
                array_push($results_final, $results);
            }

            $order_total = array_sum($order_total_final);
        } else {
            $order_total = 0;
            $results = [];
        }

        //echo "<pre>";print_r($order_total_final);
        //echo "<pre>";print_r($results_final);die;

        //echo "<pre>";print_r($results);die;

        foreach ($results_final as $key => $results) {
            // code...

            $order_statuses_color_temp = null;
            $result_status_tmp = null;

            foreach ($results as $result) {
                $sub_total = 0;

                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $sub_total = $total['value'];
                        break;
                    }
                }

                if ($this->user->isVendor()) {
                    $result['customer'] = strtok($result['customer'], ' ');
                }
                $this->load->model('localisation/order_status');
                $data['orders'][$result['status']]['orders'][] = [
                    'order_id' => $result['order_id'],
                    'customer' => $result['customer'],
                    'company_name' => $result['company_name'],
                    'payment_method' => $result['payment_method'],
                    'shipping_method' => $result['shipping_method'],
                    'shipping_address' => $result['shipping_address'],
                    'status' => $result['status'],
                    'store' => $result['store_name'],
                    'order_status_id' => $result['order_status_id'],

                    'order_status_color' => $result['color'],
                    'city' => $result['city'],
                    'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                    'sub_total' => $this->currency->format($sub_total, $result['currency_code'], $result['currency_value']),
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                    'delivery_timeslot' => $result['delivery_timeslot'],
                    'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                    'shipping_code' => $result['shipping_code'],
                    'view' => $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&order_id='.$result['order_id'].$url, 'SSL'),
                    'edit' => $this->url->link('sale/order/EditInvoice', 'token='.$this->session->data['token'].'&order_id='.$result['order_id'].$url, 'SSL'),
                    'delete' => $this->url->link('sale/order/delete', 'token='.$this->session->data['token'].'&order_id='.$result['order_id'].$url, 'SSL'),
                    'invoice' => $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'order_spreadsheet' => $this->url->link('sale/order/orderCalculationSheet', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'shipping' => $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                
                'po_number' => $result['po_number'],
                'SAP_customer_no' => $result['SAP_customer_no'],
                'SAP_doc_no' => $result['SAP_doc_no'],
                'all_order_statuses' => $this->model_localisation_order_status->getOrderStatuses()
                ];

                $order_statuses_color_temp = $result['color'];
                $result_status_tmp = $result['status'];
            }

            if (!is_null($result_status_tmp)) {
                $data['orders'][$result_status_tmp]['order_status_color'] = $order_statuses_color_temp;
            }
        }

        //echo "<pre>";print_r($data);die;
        $data['all_orders'] = $data['orders'];

        //echo "<pre>";print_r($data['all_orders']);die;

        $data['heading_title_fastorders'] = $this->language->get('heading_title_fastorders');

        $data['text_vendor'] = $this->language->get('text_vendor');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_action'] = $this->language->get('column_action');

        $data['column_payment'] = $this->language->get('column_payment');
        $data['column_delivery_method'] = $this->language->get('column_delivery_method');

        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');
        $data['entry_store_name'] = $this->language->get('entry_store_name');
        $data['button_invoice_print'] = $this->language->get('button_invoice_print');
        $data['button_shipping_print'] = $this->language->get('button_shipping_print');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_view'] = $this->language->get('button_view');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_day'])) {
            $url .= '&filter_order_day='.$this->request->get['filter_order_day'];
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=o.order_id'.$url, 'SSL');
        $data['sort_city'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=c.name'.$url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=customer'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        $data['sort_total'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=o.total'.$url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=o.date_added'.$url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('sale/fast_order', 'token='.$this->session->data['token'].'&sort=delivery_timeslot'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/order', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_vendor'] = $filter_vendor;
        $data['filter_store_name'] = $filter_store_name;
        $data['filter_delivery_method'] = $filter_delivery_method;
        $data['filter_payment'] = $filter_payment;

        $data['filter_order_status'] = $filter_order_status;
        $data['filter_order_day'] = $filter_order_day;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatusesbeforeReadyforDelivery();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->load->model('executives/executives');
        $delivery_executives = $this->model_executives_executives->getExecutives();
        $data['delivery_executives'] = $delivery_executives;

        $this->load->model('drivers/drivers');
        $drivers = $this->model_drivers_drivers->getDrivers();
        $data['drivers'] = $drivers;


        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $order_processing_groups = $this->model_orderprocessinggroup_orderprocessinggroup->getOrderProcessingGroups();
        $data['order_processing_groups'] = $order_processing_groups;
        

        $this->response->setOutput($this->load->view('sale/fast_order_list.tpl', $data));
    }

    public function getUserByName($name)
    {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '".$this->db->escape($name)."%'");

            return $query->row['user_id'];
        }
    }

    public function getUser($id)
    {
        if ($id) {
            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."customer`  WHERE customer_id ='".$id."'");

            return $query->row['fax'];
        }
    }

    public function getForm()
    {
        $this->load->model('sale/customer');

        $data['heading_title_fastorders'] = $this->language->get('heading_title_fastorders');

        $data['text_stree_society_office'] = $this->language->get('text_stree_society_office');
        $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');
        $data['text_locality'] = $this->language->get('text_locality');
        $data['label_zipcode'] = $this->language->get('label_zipcode');

        $data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_select_date'] = $this->language->get('text_select_date');
        $data['text_select_timeslot'] = $this->language->get('text_select_timeslot');

        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_contact_no'] = $this->language->get('entry_contact_no');
        $data['entry_addess'] = $this->language->get('entry_addess');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_affiliate'] = $this->language->get('entry_affiliate');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_zone_code'] = $this->language->get('entry_zone_code');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_option'] = $this->language->get('entry_option');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_to_name'] = $this->language->get('entry_to_name');
        $data['entry_to_email'] = $this->language->get('entry_to_email');
        $data['entry_from_name'] = $this->language->get('entry_from_name');
        $data['entry_from_email'] = $this->language->get('entry_from_email');
        $data['entry_theme'] = $this->language->get('entry_theme');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
        $data['entry_payment_method'] = $this->language->get('entry_payment_method');
        $data['entry_date_timeslot'] = $this->language->get('entry_date_timeslot');
        $data['entry_coupon'] = $this->language->get('entry_coupon');
        $data['entry_voucher'] = $this->language->get('entry_voucher');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_order_status'] = $this->language->get('entry_order_status');

        $data['tab_location'] = $this->language->get('tab_location');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_product_add'] = $this->language->get('button_product_add');
        $data['button_voucher_add'] = $this->language->get('button_voucher_add');

        $data['button_payment'] = $this->language->get('button_payment');
        $data['button_shipping'] = $this->language->get('button_shipping');
        $data['button_coupon'] = $this->language->get('button_coupon');
        $data['button_voucher'] = $this->language->get('button_voucher');
        $data['button_reward'] = $this->language->get('button_reward');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_remove'] = $this->language->get('button_remove');

        $data['tab_order'] = $this->language->get('tab_order');
        $data['tab_customer'] = $this->language->get('tab_customer');
        $data['tab_payment'] = $this->language->get('tab_payment');
        $data['tab_shipping'] = $this->language->get('tab_shipping');
        $data['tab_product'] = $this->language->get('tab_product');
        $data['tab_voucher'] = $this->language->get('tab_voucher');
        $data['tab_total'] = $this->language->get('tab_total');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title_fastorders'),
            'href' => $this->url->link('sale/order', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['cancel'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['order_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
        }

        if (!empty($order_info)) {
            $data['order_id'] = $this->request->get['order_id'];
            $data['store_id'] = $order_info['store_id'];

            $data['customer'] = $order_info['customer'];
            $data['customer_id'] = $order_info['customer_id'];
            $data['customer_group_id'] = $order_info['customer_group_id'];
            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];
            $data['email'] = $order_info['email'];
            $data['telephone'] = $order_info['telephone'];
            $data['fax'] = $order_info['fax'];
            $data['account_custom_field'] = $order_info['custom_field'];

            $this->load->model('sale/customer');

            //$data['addresses'] = $this->model_sale_customer->getAddresses($order_info['customer_id']);

            $data['payment_method'] = $order_info['payment_method'];
            $data['payment_code'] = $order_info['payment_code'];

            $data['shipping_name'] = $order_info['shipping_name'];
            $data['shipping_city_id'] = $order_info['shipping_city_id'];
            $data['shipping_address'] = $order_info['shipping_address'];
            $data['shipping_contact_no'] = $order_info['shipping_contact_no'];
            $data['shipping_method'] = $order_info['shipping_method'];
            $data['shipping_code'] = $order_info['shipping_code'];

            $data['shipping_flat_number'] = $order_info['shipping_flat_number'];
            $data['shipping_building_name'] = $order_info['shipping_building_name'];
            $data['shipping_landmark'] = $order_info['shipping_landmark'];
            $data['shipping_zipcode'] = $order_info['shipping_zipcode'];

            // Add products to the API
            $data['order_products'] = [];

            $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $data['order_products'][] = [
                    'product_store_id' => $product['product_id'],
                    'store_product_variation_id' => $product['variation_id'],
                    'vendor_id' => $product['vendor_id'],
                    'store_id' => $product['store_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'reward' => $product['reward'],
                ];
            }

            // Add vouchers to the API
            // $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);
            $data['order_vouchers'] = [];

            $data['coupon'] = '';
            $data['voucher'] = '';
            $data['reward'] = '';

            $data['order_totals'] = [];

            $order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

            foreach ($order_totals as $order_total) {
                // If coupon, voucher or reward points
                $start = strpos($order_total['title'], '(') + 1;
                $end = strrpos($order_total['title'], ')');

                if ($start && $end) {
                    if ('coupon' == $order_total['code']) {
                        $data['coupon'] = substr($order_total['title'], $start, $end - $start);
                    }

                    if ('voucher' == $order_total['code']) {
                        $data['voucher'] = substr($order_total['title'], $start, $end - $start);
                    }

                    if ('reward' == $order_total['code']) {
                        $data['reward'] = substr($order_total['title'], $start, $end - $start);
                    }
                }
            }

            $data['order_status_id'] = $order_info['order_status_id'];
            $data['comment'] = $order_info['comment'];
            $data['affiliate_id'] = $order_info['affiliate_id'];
            $data['delivery_date'] = $order_info['delivery_date'];
            $data['delivery_timeslot'] = $order_info['delivery_timeslot'];

            $data['affiliate'] = '';
        } else {
            $data['order_id'] = 0;
            $data['store_id'] = '';
            $data['customer'] = '';
            $data['customer_id'] = '';
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
            $data['firstname'] = '';
            $data['lastname'] = '';
            $data['email'] = '';
            $data['telephone'] = '';
            $data['fax'] = '';
            $data['customer_custom_field'] = [];

            $data['addresses'] = [];

            $data['payment_method'] = '';
            $data['payment_code'] = '';

            $data['shipping_name'] = '';
            $data['shipping_city_id'] = '';
            $data['shipping_address'] = '';
            $data['shipping_contact_no'] = '';
            $data['shipping_method'] = '';
            $data['shipping_code'] = '';

            $data['order_products'] = [];
            $data['order_vouchers'] = [];
            $data['order_totals'] = [];

            $data['order_status_id'] = $this->config->get('config_order_status_id');

            $data['comment'] = '';
            $data['affiliate_id'] = '';
            $data['affiliate'] = '';

            $data['coupon'] = '';
            $data['voucher'] = '';
            $data['reward'] = '';
        }

        // Stores
        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        // Customer Groups
        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        // Custom Fields
        $this->load->model('sale/custom_field');

        $data['custom_fields'] = [];

        $custom_fields = $this->model_sale_custom_field->getCustomFields();

        foreach ($custom_fields as $custom_field) {
            $data['custom_fields'][] = [
                'custom_field_id' => $custom_field['custom_field_id'],
                'custom_field_value' => $this->model_sale_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
                'name' => $custom_field['name'],
                'value' => $custom_field['value'],
                'type' => $custom_field['type'],
                'location' => $custom_field['location'],
            ];
        }

        $data['cities'] = $this->model_sale_custom_field->getCities();

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['voucher_min'] = $this->config->get('config_voucher_min');

        $this->load->model('sale/voucher_theme');

        $data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/order_form.tpl', $data));
    }

    public function info()
    {
        $this->load->model('sale/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        //check vendor order
        if ($this->user->isVendor()) {
            if (!$this->isVendorOrder($order_id)) {
                $this->response->redirect($this->url->link('error/not_found'));
            }
        }

        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {
            $this->load->language('sale/order');

            $this->document->setTitle($this->language->get('heading_title_fastorders'));

            $data['text_yes'] = $this->language->get('text_yes');
            $data['text_no'] = $this->language->get('text_no');

            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
            $data['text_driver_contact_no'] = $this->language->get('text_driver_contact_no');
            $data['text_driver_name'] = $this->language->get('text_driver_name');
            $data['tab_location'] = $this->language->get('tab_location');
            $data['text_pickup_notes'] = $this->language->get('text_pickup_notes');
            $data['text_final_amount'] = $this->language->get('text_final_amount');
            $data['text_driver_notes'] = $this->language->get('text_driver_notes');

            $data['text_settle'] = $this->language->get('text_settle');
            $data['text_dropoff_notes'] = $this->language->get('text_dropoff_notes');
            $data['text_original_amount'] = $this->language->get('text_original_amount');

            $data['text_driver_image'] = $this->language->get('text_driver_image');
            $data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
            $data['text_completed'] = $this->language->get('text_completed');
            $data['text_cancelled'] = $this->language->get('text_cancelled');
            $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');

            $data['heading_title_fastorders'] = $this->language->get('heading_title_fastorders');
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

            $data['help_country_match'] = $this->language->get('help_country_match');
            $data['help_country_code'] = $this->language->get('help_country_code');
            $data['help_high_risk_country'] = $this->language->get('help_high_risk_country');
            $data['help_distance'] = $this->language->get('help_distance');
            $data['help_ip_region'] = $this->language->get('help_ip_region');
            $data['help_ip_city'] = $this->language->get('help_ip_city');
            $data['help_ip_latitude'] = $this->language->get('help_ip_latitude');
            $data['help_ip_longitude'] = $this->language->get('help_ip_longitude');
            $data['help_ip_isp'] = $this->language->get('help_ip_isp');
            $data['help_ip_org'] = $this->language->get('help_ip_org');
            $data['help_ip_asnum'] = $this->language->get('help_ip_asnum');
            $data['help_ip_user_type'] = $this->language->get('help_ip_user_type');
            $data['help_ip_country_confidence'] = $this->language->get('help_ip_country_confidence');
            $data['help_ip_region_confidence'] = $this->language->get('help_ip_region_confidence');
            $data['help_ip_city_confidence'] = $this->language->get('help_ip_city_confidence');
            $data['help_ip_postal_confidence'] = $this->language->get('help_ip_postal_confidence');
            $data['help_ip_postal_code'] = $this->language->get('help_ip_postal_code');
            $data['help_ip_accuracy_radius'] = $this->language->get('help_ip_accuracy_radius');
            $data['help_ip_net_speed_cell'] = $this->language->get('help_ip_net_speed_cell');
            $data['help_ip_metro_code'] = $this->language->get('help_ip_metro_code');
            $data['help_ip_area_code'] = $this->language->get('help_ip_area_code');
            $data['help_ip_time_zone'] = $this->language->get('help_ip_time_zone');
            $data['help_ip_region_name'] = $this->language->get('help_ip_region_name');
            $data['help_ip_domain'] = $this->language->get('help_ip_domain');
            $data['help_ip_country_name'] = $this->language->get('help_ip_country_name');
            $data['help_ip_continent_code'] = $this->language->get('help_ip_continent_code');
            $data['help_ip_corporate_proxy'] = $this->language->get('help_ip_corporate_proxy');
            $data['help_anonymous_proxy'] = $this->language->get('help_anonymous_proxy');
            $data['help_proxy_score'] = $this->language->get('help_proxy_score');
            $data['help_is_trans_proxy'] = $this->language->get('help_is_trans_proxy');
            $data['help_free_mail'] = $this->language->get('help_free_mail');
            $data['help_carder_email'] = $this->language->get('help_carder_email');
            $data['help_high_risk_username'] = $this->language->get('help_high_risk_username');
            $data['help_high_risk_password'] = $this->language->get('help_high_risk_password');
            $data['help_bin_match'] = $this->language->get('help_bin_match');
            $data['help_bin_country'] = $this->language->get('help_bin_country');
            $data['help_bin_name_match'] = $this->language->get('help_bin_name_match');
            $data['help_bin_name'] = $this->language->get('help_bin_name');
            $data['help_bin_phone_match'] = $this->language->get('help_bin_phone_match');
            $data['help_bin_phone'] = $this->language->get('help_bin_phone');
            $data['help_customer_phone_in_billing_location'] = $this->language->get('help_customer_phone_in_billing_location');
            $data['help_ship_forward'] = $this->language->get('help_ship_forward');
            $data['help_city_postal_match'] = $this->language->get('help_city_postal_match');
            $data['help_ship_city_postal_match'] = $this->language->get('help_ship_city_postal_match');
            $data['help_score'] = $this->language->get('help_score');
            $data['help_explanation'] = $this->language->get('help_explanation');
            $data['help_risk_score'] = $this->language->get('help_risk_score');
            $data['help_queries_remaining'] = $this->language->get('help_queries_remaining');
            $data['help_maxmind_id'] = $this->language->get('help_maxmind_id');
            $data['help_error'] = $this->language->get('help_error');

            $data['column_product'] = $this->language->get('column_product');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
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
            $data['tab_history'] = $this->language->get('tab_history');
            $data['tab_settlement'] = $this->language->get('tab_settlement');
            $data['tab_fraud'] = $this->language->get('tab_fraud');
            $data['tab_question'] = $this->language->get('tab_question');
            $data['tab_action'] = $this->language->get('tab_action');

            $data['tab_delivery'] = $this->language->get('tab_delivery');

            $data['token'] = $this->session->data['token'];

            $url = '';

            //$this->load->model('payment/iugu');

            require_once DIR_SYSTEM.'library/Iugu.php';

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

            if (isset($this->request->get['filter_city'])) {
                $url .= '&filter_city='.$this->request->get['filter_city'];
            }

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_customer'])) {
                $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_vendor'])) {
                $url .= '&filter_vendor='.urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_store_name'])) {
                $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_delivery_method'])) {
                $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_payment'])) {
                $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_status'])) {
                $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
            }

            if (isset($this->request->get['filter_total'])) {
                $url .= '&filter_total='.$this->request->get['filter_total'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_modified'])) {
                $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title_fastorders'),
                'href' => $this->url->link('sale/order', 'token='.$this->session->data['token'].$url, 'SSL'),
            ];

            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
                $data['shipping'] = $this->url->link('sale/order/shipping', 'store_id='.$store_id.'&token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');

                if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
                    $data['invoice'] = $this->url->link('sale/order/newinvoice', 'store_id='.$store_id.'&token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');
                } else {
                    $data['invoice'] = $this->url->link('sale/order/invoice', 'store_id='.$store_id.'&token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');
                }
            } else {
                $data['shipping'] = $this->url->link('sale/order/shipping', 'token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');

                if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
                    $data['invoice'] = $this->url->link('sale/order/newinvoice', 'token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');
                } else {
                    $data['invoice'] = $this->url->link('sale/order/invoice', 'token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');
                }
            }

            $data['edit'] = $this->url->link('sale/order/edit', 'token='.$this->session->data['token'].'&order_id='.(int) $this->request->get['order_id'], 'SSL');
            $data['cancel'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].$url, 'SSL');

            $data['order_id'] = $this->request->get['order_id'];

            if ($order_info['invoice_no']) {
                $data['invoice_no'] = $order_info['invoice_prefix'].$order_info['invoice_no'];
            } else {
                $data['invoice_no'] = '';
            }

            if ($order_info['settlement_amount']) {
                $data['settlement_amount'] = $order_info['settlement_amount'];
            } else {
                $data['settlement_amount'] = 0;
            }

            if ($order_info['rating']) {
                $data['rating'] = $order_info['rating'];
            } else {
                $data['rating'] = 0;
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

            if ($store_data['latitude']) {
                $data['pickup_latitude'] = $store_data['latitude'];
            } else {
                $data['pickup_latitude'] = '';
            }

            if ($store_data['longitude']) {
                $data['pickup_longitude'] = $store_data['longitude'];
            } else {
                $data['pickup_longitude'] = '';
            }

            $data['pointA'] = "'".$data['dropoff_latitude'].','.$data['dropoff_longitude']."'";
            $data['pointB'] = "'".$data['pickup_latitude'].','.$data['pickup_longitude']."'";

            /*$data['pointA'] = "'50.8505851,4.3680522'";
            $data['pointB'] = "'50.91257,4.346170453967261'";*/

            //echo "<pre>";print_r($data['pointA']);die;
            $data['map_s'] = '[
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#d6e2e6"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#cfd4d5"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#7492a8"
                        }
                    ]
                },
                {
                    "featureType": "administrative.neighborhood",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "lightness": 25
                        }
                    ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#dde2e3"
                        }
                    ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#cfd4d5"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#dde2e3"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#7492a8"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural.terrain",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#dde2e3"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#588ca4"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "saturation": -100
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#a9de83"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#bae6a1"
                        }
                    ]
                },
                {
                    "featureType": "poi.sports_complex",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#c6e8b3"
                        }
                    ]
                },
                {
                    "featureType": "poi.sports_complex",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#bae6a1"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#41626b"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "saturation": -45
                        },
                        {
                            "lightness": 10
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#c1d1d6"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#a6b5bb"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road.highway.controlled_access",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#9fb6bd"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "saturation": -70
                        }
                    ]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#b4cbd4"
                        }
                    ]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#588ca4"
                        }
                    ]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#008cb5"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "transit.station.airport",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": -5
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#a6cbe3"
                        }
                    ]
                }
            ]';

            //echo "<pre>";print_r($data['map_s']);die;
            /* end */

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

            $data['status'] = $this->model_sale_order->getStatus($order_info['order_status_id']);

            //added
            $data['questions'] = $this->model_sale_order->getOrderQuestions($order_id);

            $data['commsion_received'] = $order_info['commsion_received'];
            //$data['commission'] = $order_info['commission'];

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');

            $data['delivery_id'] = $order_info['delivery_id']; //"del_XPeEGFX3Hc4ZeWg5";//
            $data['shopper_link'] = $this->config->get('config_shopper_link').'/storage/';

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

            /*$response = $this->load->controller('deliversystem/deliversystem/getToken',$data);

            if($response['status']) {
                $data['tokens'] = $response['token'];
                $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus',$data);


                $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus',$data);

                if(!$resp['status']) {
                    $data['delivery_data'] = [];
                } else {
                    $data['delivery_data'] = $resp['data'][0];
                    if(isset($data['delivery_data']->assigned_to)) {
                        $data['delivery_details'] = true;
                    }
                }

                if(!$productStatus['status']) {
                    $data['products_status'] = [];
                } else {
                    $data['products_status'] = $productStatus['data']   ;
                }

            }*/

            if ($order_info['customer_id']) {
                $data['customer'] = $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&customer_id='.$order_info['customer_id'], 'SSL');

                $data['customer_id'] = $order_info['customer_id'];
            } else {
                $data['customer_id'] = 0;
                $data['customer'] = '';
            }

            $this->load->model('sale/customer_group');

            $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

            if ($customer_group_info) {
                $data['customer_group'] = $customer_group_info['name'];
            } else {
                $data['customer_group'] = '';
            }

            $data['email'] = $order_info['email'];
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
            $data['payment_method'] = $order_info['payment_method'];
            $data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

            $data['original_total'] = 0;

            $sql = 'SELECT    * FROM `'.DB_PREFIX.'order_total`  WHERE code = "sub_total" and  order_id = "'.$order_info['order_id'].'"';

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
                $data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token='.$this->session->data['token'].'&affiliate_id='.$order_info['affiliate_id'], 'SSL');
            } else {
                $data['affiliate'] = '';
            }

            $data['commission'] = number_format($order_info['commission'], 2);

            $this->load->model('marketing/affiliate');

            $data['commission_total'] = '';

            $this->load->model('localisation/order_status');

            $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

            //echo "<pre>";print_r($order_status_info);die;
            if ($order_status_info) {
                $data['order_status'] = $order_status_info['name'];
                $data['order_status_color'] = $order_status_info['color'];
            } else {
                $data['order_status'] = '';
                $data['order_status_color'] = '';
            }

            $data['ip'] = $order_info['ip'];
            $data['forwarded_ip'] = $order_info['forwarded_ip'];
            $data['user_agent'] = $order_info['user_agent'];
            $data['accept_language'] = $order_info['accept_language'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));

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

            $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

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
                                'href' => $this->url->link('tool/upload/download', 'token='.$this->session->data['token'].'&code='.$upload_info['code'], 'SSL'),
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
                    'unit' => $product['unit'],
                    'product_type' => $product['product_type'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'href' => $this->url->link('catalog/product/edit', 'token='.$this->session->data['token'].'&product_id='.$product['product_id'], 'SSL'),
                ];
            }

            $totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                ];
            }

            $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

            //echo "<pre>";print_r($data['order_statuses']);die;
            $data['order_status_id'] = $order_info['order_status_id'];

            $data['order_status_name'] = '';

            $orderStatusDetail = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

            if (is_array($orderStatusDetail) && isset($orderStatusDetail['name'])) {
                $data['order_status_name'] = $orderStatusDetail['name'];
            }

            // Unset any past sessions this page date_added for the api to work.
            unset($this->session->data['cookie']);

            // Set up the API session
            if ($this->user->hasPermission('modify', 'sale/order')) {
                $this->load->model('user/api');

                $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

                if ($api_info) {
                    $curl = curl_init();

                    // Set SSL if required
                    if ('https' == substr(HTTPS_CATALOG, 0, 5)) {
                        curl_setopt($curl, CURLOPT_PORT, 443);
                    }

                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG.'index.php?path=api/login');
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

                    $json = curl_exec($curl);

                    if (!$json) {
                        $data['error_warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                    } else {
                        $response = json_decode($json, true);
                    }

                    if (isset($response['cookie'])) {
                        $this->session->data['cookie'] = $response['cookie'];
                    }
                }
            }

            if (isset($response['cookie'])) {
                $this->session->data['cookie'] = $response['cookie'];
            } else {
                $data['error_warning'] = $this->language->get('error_permission');
            }

            // Fraud
            $this->load->model('sale/fraud');

            $fraud_info = $this->model_sale_fraud->getFraud($order_info['order_id']);

            if ($fraud_info) {
                $data['country_match'] = $fraud_info['country_match'];

                if ($fraud_info['country_code']) {
                    $data['country_code'] = $fraud_info['country_code'];
                } else {
                    $data['country_code'] = '';
                }

                $data['high_risk_country'] = $fraud_info['high_risk_country'];
                $data['distance'] = $fraud_info['distance'];

                if ($fraud_info['ip_region']) {
                    $data['ip_region'] = $fraud_info['ip_region'];
                } else {
                    $data['ip_region'] = '';
                }

                if ($fraud_info['ip_city']) {
                    $data['ip_city'] = $fraud_info['ip_city'];
                } else {
                    $data['ip_city'] = '';
                }

                $data['ip_latitude'] = $fraud_info['ip_latitude'];
                $data['ip_longitude'] = $fraud_info['ip_longitude'];

                if ($fraud_info['ip_isp']) {
                    $data['ip_isp'] = $fraud_info['ip_isp'];
                } else {
                    $data['ip_isp'] = '';
                }

                if ($fraud_info['ip_org']) {
                    $data['ip_org'] = $fraud_info['ip_org'];
                } else {
                    $data['ip_org'] = '';
                }

                $data['ip_asnum'] = $fraud_info['ip_asnum'];

                if ($fraud_info['ip_user_type']) {
                    $data['ip_user_type'] = $fraud_info['ip_user_type'];
                } else {
                    $data['ip_user_type'] = '';
                }

                if ($fraud_info['ip_country_confidence']) {
                    $data['ip_country_confidence'] = $fraud_info['ip_country_confidence'];
                } else {
                    $data['ip_country_confidence'] = '';
                }

                if ($fraud_info['ip_region_confidence']) {
                    $data['ip_region_confidence'] = $fraud_info['ip_region_confidence'];
                } else {
                    $data['ip_region_confidence'] = '';
                }

                if ($fraud_info['ip_city_confidence']) {
                    $data['ip_city_confidence'] = $fraud_info['ip_city_confidence'];
                } else {
                    $data['ip_city_confidence'] = '';
                }

                if ($fraud_info['ip_postal_confidence']) {
                    $data['ip_postal_confidence'] = $fraud_info['ip_postal_confidence'];
                } else {
                    $data['ip_postal_confidence'] = '';
                }

                if ($fraud_info['ip_postal_code']) {
                    $data['ip_postal_code'] = $fraud_info['ip_postal_code'];
                } else {
                    $data['ip_postal_code'] = '';
                }

                $data['ip_accuracy_radius'] = $fraud_info['ip_accuracy_radius'];

                if ($fraud_info['ip_net_speed_cell']) {
                    $data['ip_net_speed_cell'] = $fraud_info['ip_net_speed_cell'];
                } else {
                    $data['ip_net_speed_cell'] = '';
                }

                $data['ip_metro_code'] = $fraud_info['ip_metro_code'];
                $data['ip_area_code'] = $fraud_info['ip_area_code'];

                if ($fraud_info['ip_time_zone']) {
                    $data['ip_time_zone'] = $fraud_info['ip_time_zone'];
                } else {
                    $data['ip_time_zone'] = '';
                }

                if ($fraud_info['ip_region_name']) {
                    $data['ip_region_name'] = $fraud_info['ip_region_name'];
                } else {
                    $data['ip_region_name'] = '';
                }

                if ($fraud_info['ip_domain']) {
                    $data['ip_domain'] = $fraud_info['ip_domain'];
                } else {
                    $data['ip_domain'] = '';
                }

                if ($fraud_info['ip_country_name']) {
                    $data['ip_country_name'] = $fraud_info['ip_country_name'];
                } else {
                    $data['ip_country_name'] = '';
                }

                if ($fraud_info['ip_continent_code']) {
                    $data['ip_continent_code'] = $fraud_info['ip_continent_code'];
                } else {
                    $data['ip_continent_code'] = '';
                }

                if ($fraud_info['ip_corporate_proxy']) {
                    $data['ip_corporate_proxy'] = $fraud_info['ip_corporate_proxy'];
                } else {
                    $data['ip_corporate_proxy'] = '';
                }

                $data['anonymous_proxy'] = $fraud_info['anonymous_proxy'];
                $data['proxy_score'] = $fraud_info['proxy_score'];

                if ($fraud_info['is_trans_proxy']) {
                    $data['is_trans_proxy'] = $fraud_info['is_trans_proxy'];
                } else {
                    $data['is_trans_proxy'] = '';
                }

                $data['free_mail'] = $fraud_info['free_mail'];
                $data['carder_email'] = $fraud_info['carder_email'];

                if ($fraud_info['high_risk_username']) {
                    $data['high_risk_username'] = $fraud_info['high_risk_username'];
                } else {
                    $data['high_risk_username'] = '';
                }

                if ($fraud_info['high_risk_password']) {
                    $data['high_risk_password'] = $fraud_info['high_risk_password'];
                } else {
                    $data['high_risk_password'] = '';
                }

                $data['bin_match'] = $fraud_info['bin_match'];

                if ($fraud_info['bin_country']) {
                    $data['bin_country'] = $fraud_info['bin_country'];
                } else {
                    $data['bin_country'] = '';
                }

                $data['bin_name_match'] = $fraud_info['bin_name_match'];

                if ($fraud_info['bin_name']) {
                    $data['bin_name'] = $fraud_info['bin_name'];
                } else {
                    $data['bin_name'] = '';
                }

                $data['bin_phone_match'] = $fraud_info['bin_phone_match'];

                if ($fraud_info['bin_phone']) {
                    $data['bin_phone'] = $fraud_info['bin_phone'];
                } else {
                    $data['bin_phone'] = '';
                }

                if ($fraud_info['customer_phone_in_billing_location']) {
                    $data['customer_phone_in_billing_location'] = $fraud_info['customer_phone_in_billing_location'];
                } else {
                    $data['customer_phone_in_billing_location'] = '';
                }

                $data['ship_forward'] = $fraud_info['ship_forward'];

                if ($fraud_info['city_postal_match']) {
                    $data['city_postal_match'] = $fraud_info['city_postal_match'];
                } else {
                    $data['city_postal_match'] = '';
                }

                if ($fraud_info['ship_city_postal_match']) {
                    $data['ship_city_postal_match'] = $fraud_info['ship_city_postal_match'];
                } else {
                    $data['ship_city_postal_match'] = '';
                }

                $data['score'] = $fraud_info['score'];
                $data['explanation'] = $fraud_info['explanation'];
                $data['risk_score'] = $fraud_info['risk_score'];
                $data['queries_remaining'] = $fraud_info['queries_remaining'];
                $data['maxmind_id'] = $fraud_info['maxmind_id'];
                $data['error'] = $fraud_info['error'];
            } else {
                $data['maxmind_id'] = '';
            }

            $data['payment_action'] = $this->load->controller('payment/'.$order_info['payment_code'].'/orderAction', '');

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('sale/order_info.tpl', $data));
        } else {
            $this->load->language('error/not_found');

            $this->document->setTitle($this->language->get('heading_title_fastorders'));

            $data['heading_title_fastorders'] = $this->language->get('heading_title_fastorders');

            $data['text_not_found'] = $this->language->get('text_not_found');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title_fastorders'),
                'href' => $this->url->link('error/not_found', 'token='.$this->session->data['token'], 'SSL'),
            ];

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
        }
    }

    protected function validateDelete()
    {
        $this->load->model('sale/order');

        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        //check if any return
        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $query = $this->model_sale_order->getReturn($order_id);
        if ($query->row['total'] > 0) {
            $this->error['warning'] = sprintf($this->language->get('error_return'), $query->row['total']);
        }

        return !$this->error;
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;

//        return true;
    }

    public function createInvoiceNo()
    {
        $this->load->language('sale/order');

        $json = [];

        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $json['error'] = $this->language->get('error_permission');
        } elseif (isset($this->request->get['order_id'])) {
            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $this->load->model('sale/order');

            $invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

            if ($invoice_no) {
                $json['invoice_no'] = $invoice_no;
            } else {
                $json['error'] = $this->language->get('error_action');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addReward()
    {
        $this->load->language('sale/order');

        $json = [];

        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $this->load->model('sale/order');

            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
                $this->load->model('sale/customer');

                $reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_id);

                if (!$reward_total) {
                    $this->model_sale_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id').' #'.$order_id, $order_info['reward'], $order_id);
                }
            }

            $json['success'] = $this->language->get('text_reward_added');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeReward()
    {
        $this->load->language('sale/order');

        $json = [];

        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $this->load->model('sale/order');

            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info) {
                $this->load->model('sale/customer');

                $this->model_sale_customer->deleteReward($order_id);
            }

            $json['success'] = $this->language->get('text_reward_removed');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCommission()
    {
        $this->load->language('sale/order');

        $json = [];

        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $this->load->model('sale/order');

            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info) {
                $this->load->model('marketing/affiliate');

                $affiliate_total = $this->model_marketing_affiliate->getTotalCommissionsByOrderId($order_id);

                if (!$affiliate_total) {
                    $this->model_marketing_affiliate->addCommission($order_info['affiliate_id'], $this->language->get('text_order_id').' #'.$order_id, $order_info['commission'], $order_id);
                }
            }

            $json['success'] = $this->language->get('text_commission_added');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeCommission()
    {
        $this->load->language('sale/order');

        $json = [];

        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $this->load->model('sale/order');

            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info) {
                $this->load->model('marketing/affiliate');

                $this->model_marketing_affiliate->deleteCommission($order_id);
            }

            $json['success'] = $this->language->get('text_commission_removed');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function country()
    {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function history()
    {
        $this->load->language('sale/order');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_notify'] = $this->language->get('column_notify');
        $data['column_comment'] = $this->language->get('column_comment');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['histories'] = [];

        $this->load->model('sale/order');

        $results = $this->model_sale_order->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = [
                'notify' => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
                'status' => $result['status'],
                'order_status_color' => $result['color'],
                'comment' => nl2br($result['comment']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/order/history', 'token='.$this->session->data['token'].'&order_id='.$this->request->get['order_id'].'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('sale/order_history.tpl', $data));
    }

    public function invoice()
    {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_contact_no'] = $this->language->get('text_contact_no');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');

        $data['column_unit'] = $this->language->get('column_unit');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_cpf_number'] = $this->language->get('text_cpf_number');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $data['orders'] = [];

        $orders = [];

        if (isset($this->request->post['selected'])) {
            $orders = $this->request->post['selected'];
        } elseif (isset($this->request->get['order_id'])) {
            $orders[] = $this->request->get['order_id'];
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);

            //check vendor order

            if ($this->user->isVendor()) {
                if (!$this->isVendorOrder($order_id)) {
                    $this->response->redirect($this->url->link('error/not_found'));
                }
            }

            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

                // if ($store_info) {
                //     $store_address = $store_info['config_address'];
                //     $store_email = $store_info['config_email'];
                //     $store_telephone = $store_info['config_telephone'];
                //     $store_fax = $store_info['config_fax'];
                // } else {
                //     $store_address = $this->config->get('config_address');
                //     $store_email = $this->config->get('config_email');
                //     $store_telephone = $this->config->get('config_telephone');
                //     $store_fax = $this->config->get('config_fax');
                // }

                $store_data = $this->model_sale_order->getStoreData($order_info['store_id']);

                if ($store_data) {
                    $store_address = $store_data['address'];
                    $store_email = $store_data['email'];
                    $store_telephone = $store_data['telephone'];
                    $store_fax = $store_data['fax'];
                    $store_tax = $store_data['tax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                    $store_tax = '';
                }

                $data['store_name'] = $store_data['name'];

                if ($order_info['invoice_no']) {
                    $invoice_no = $order_info['invoice_prefix'].$order_info['invoice_no'];
                } else {
                    $invoice_no = '';
                }

                $this->load->model('tool/upload');

                $product_data = [];

                $products = $this->model_sale_order->getOrderProducts($order_id);

                foreach ($products as $product) {
                    if ($store_id && $product['store_id'] != $store_id) {
                        continue;
                    }
                    $option_data = [];

                    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => $value,
                        ];
                    }

                    $product_data[] = [
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'unit' => $product['unit'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $total_data = [];

                if ($store_id) {
                    $totals = $this->model_sale_order->getVendorOrderTotals($order_id, $store_id);
                } else {
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                }

                foreach ($totals as $total) {
                    $total_data[] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $data['orders'][] = [
                    'order_id' => $order_id,
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                    'store_name' => $order_info['store_name'],
                    'store_url' => rtrim($order_info['store_url'], '/'),
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_tax' => $store_tax,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'cpf_number' => $this->getUser($order_info['customer_id']),
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_city' => $order_info['shipping_city'],
                    'shipping_contact_no' => $order_info['shipping_contact_no'],
                    'shipping_name' => $order_info['shipping_name'],
                    'shipping_method' => $order_info['shipping_method'],
                    'payment_method' => $order_info['payment_method'],
                    'product' => $product_data,
                    'total' => $total_data,
                    'comment' => nl2br($order_info['comment']),
                ];
            }
        }
        $this->response->setOutput($this->load->view('sale/order_invoice.tpl', $data));
    }

    public function newinvoice()
    {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_contact_no'] = $this->language->get('text_contact_no');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');

        $data['column_unit'] = $this->language->get('column_unit');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_cpf_number'] = $this->language->get('text_cpf_number');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $data['orders'] = [];

        $orders = [];

        if (isset($this->request->post['selected'])) {
            $orders = $this->request->post['selected'];
        } elseif (isset($this->request->get['order_id'])) {
            $orders[] = $this->request->get['order_id'];
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);

            //check vendor order

            if ($this->user->isVendor()) {
                if (!$this->isVendorOrder($order_id)) {
                    $this->response->redirect($this->url->link('error/not_found'));
                }
            }

            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

                // if ($store_info) {
                //     $store_address = $store_info['config_address'];
                //     $store_email = $store_info['config_email'];
                //     $store_telephone = $store_info['config_telephone'];
                //     $store_fax = $store_info['config_fax'];
                // } else {
                //     $store_address = $this->config->get('config_address');
                //     $store_email = $this->config->get('config_email');
                //     $store_telephone = $this->config->get('config_telephone');
                //     $store_fax = $this->config->get('config_fax');
                // }

                $store_data = $this->model_sale_order->getStoreData($order_info['store_id']);

                if ($store_data) {
                    $store_address = $store_data['address'];
                    $store_email = $store_data['email'];
                    $store_telephone = $store_data['telephone'];
                    $store_fax = $store_data['fax'];
                    $store_tax = $store_data['tax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                    $store_tax = '';
                }

                $data['store_name'] = $store_data['name'];

                if ($order_info['invoice_no']) {
                    $invoice_no = $order_info['invoice_prefix'].$order_info['invoice_no'];
                } else {
                    $invoice_no = '';
                }

                $this->load->model('tool/upload');

                $product_data = [];

                $products = $this->model_sale_order->getRealOrderProducts($order_id);

                foreach ($products as $product) {
                    if ($store_id && $product['store_id'] != $store_id) {
                        continue;
                    }
                    $option_data = [];

                    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => $value,
                        ];
                    }

                    $product_data[] = [
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'unit' => $product['unit'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $total_data = [];

                if ($store_id) {
                    $totals = $this->model_sale_order->getVendorOrderTotals($order_id, $store_id);
                } else {
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                }

                foreach ($totals as $total) {
                    $total_data[] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $data['orders'][] = [
                    'order_id' => $order_id,
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                    'store_name' => $order_info['store_name'],
                    'store_url' => rtrim($order_info['store_url'], '/'),
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_tax' => $store_tax,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'cpf_number' => $this->getUser($order_info['customer_id']),
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_city' => $order_info['shipping_city'],
                    'shipping_contact_no' => $order_info['shipping_contact_no'],
                    'shipping_name' => $order_info['shipping_name'],
                    'shipping_method' => $order_info['shipping_method'],
                    'payment_method' => $order_info['payment_method'],
                    'product' => $product_data,
                    'total' => $total_data,
                    'comment' => nl2br($order_info['comment']),
                ];
            }
        }
        $this->response->setOutput($this->load->view('sale/order_invoice.tpl', $data));
    }

    public function shipping()
    {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_shipping');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_picklist'] = $this->language->get('text_picklist');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_contact_no'] = $this->language->get('text_contact_no');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_from'] = $this->language->get('text_from');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_sku'] = $this->language->get('text_sku');
        $data['text_upc'] = $this->language->get('text_upc');
        $data['text_ean'] = $this->language->get('text_ean');
        $data['text_jan'] = $this->language->get('text_jan');
        $data['text_isbn'] = $this->language->get('text_isbn');
        $data['text_mpn'] = $this->language->get('text_mpn');

        $data['column_location'] = $this->language->get('column_location');
        $data['column_reference'] = $this->language->get('column_reference');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_weight'] = $this->language->get('column_weight');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_comment'] = $this->language->get('column_comment');

        $this->load->model('sale/order');

        $this->load->model('catalog/product');

        $this->load->model('setting/setting');

        $data['orders'] = [];

        $orders = [];

        if (isset($this->request->post['selected'])) {
            $orders = $this->request->post['selected'];
        } elseif (isset($this->request->get['order_id'])) {
            $orders[] = $this->request->get['order_id'];
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            if (isset($this->session->data['config_store_id'])) {
                $store_id = $this->session->data['config_store_id'];
            } else {
                $store_id = 0;
            }
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);

            //check vendor order
            if ($this->user->isVendor() && !$this->isMyOrder($order_info['order_id'], $store_id)) {
                continue;
            }

            // Make sure there is a shipping method
            if ($order_info && $order_info['shipping_code']) {
                $store_info = $this->model_setting_setting->getStore($order_info['store_id']);

                if ($store_info) {
                    $store_address = $store_info['address'];
                    $store_name = $store_info['name'];

                    $store_email = $store_info['email'];
                    $store_telephone = $store_info['telephone'];
                    $store_fax = $store_info['fax'];
                } else {
                    $store_name = $this->config->get('config_name');
                    $store_address = $this->config->get('config_address');

                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                }

                if ($order_info['invoice_no']) {
                    $invoice_no = $order_info['invoice_prefix'].$order_info['invoice_no'];
                } else {
                    $invoice_no = '';
                }

                $this->load->model('tool/upload');

                $product_data = [];

                $products = $this->model_sale_order->getOrderProducts($order_id);

                foreach ($products as $product) {
                    if ($store_id && $product['store_id'] != $store_id) {
                        continue;
                    }

                    $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                    $option_data = [];

                    $product_data[] = [
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                    ];
                }

                $data['orders'][] = [
                    'order_id' => $order_id,
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                    'store_name' => $store_name,
                    'store_url' => rtrim($order_info['store_url'], '/'),
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'telephone' => $order_info['telephone'],
                    'shipping_method' => $order_info['shipping_method'],
                    'shipping_contact_no' => $order_info['shipping_contact_no'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_name' => $order_info['shipping_name'],
                    'shipping_city' => $order_info['shipping_city'],
                    'product' => $product_data,
                    'comment' => nl2br($order_info['comment']),
                ];
            }
        }

        $this->response->setOutput($this->load->view('sale/order_shipping.tpl', $data));
    }

    public function updateInvoice()
    {
        $json = [];

        $this->load->language('sale/order');

        $json['status'] = true;
        $log = new Log('error.log');
        $log->write('api/updateInvoice');

        $datas = $this->request->post;

        $uniqueIds = array_keys($datas['products']);
        $log->write($datas);
        $log->write($uniqueIds);

        $order_id = $this->request->get['order_id'];

        if ($this->validate()) {
            // Store

            $this->load->model('sale/order');

            $allProductIds = $this->model_sale_order->getOrderProductsIds($order_id);

            $log->write($allProductIds);

            foreach ($allProductIds as $deletePro) {
                if (!isset($datas['products'][$deletePro['product_id']])) {
                    $products = $this->model_sale_order->deleteOrderProduct($order_id, $deletePro['product_id']);
                } else {
                    $log->write('set');
                }
            }

            $sumTotal = 0;
            foreach ($datas['products'] as $p_id_key => $updateProduct) {
                /*$log->write("updateProduct");
                $log->write($p_id_key);
                $log->write($updateProduct);*/
                $products = $this->model_sale_order->updateOrderProduct($order_id, $p_id_key, $updateProduct);
                $sumTotal += ($updateProduct['price'] * $updateProduct['quantity']);
            }

            $subTotal = $sumTotal;

            //saving order total below

            $this->model_sale_order->deleteOrderTotal($order_id);

            $p = 2;
            foreach ($datas['totals'] as $p_id_code => $tot) {
                $log->write('updatetotals');
                $log->write($tot);
                $tot['sort'] = $p;
                $this->model_sale_order->insertOrderTotal($order_id, $tot);
                $sumTotal += $tot['value'];
                ++$p;
            }

            $orderTotal = $sumTotal;

            $this->model_sale_order->insertOrderSubTotalAndTotal($order_id, $subTotal, $orderTotal, $p);

            if ($this->request->get['settle']) {
                //settle and  update
                $log->write('if settle');
                $customer_id = $this->request->get['customer_id'];
                $final_amount = $orderTotal;

                $iuguData = $this->model_sale_order->getOrderIuguAndTotal($order_id);

                if ($iuguData) {
                    $log->write('if iugu');

                    $description = 'On Order #'.$order_id;

                    if ($this->request->get['charge']) {
                        //new invoice is charged

                        $userCharged = $this->chargeCustomer($customer_id, $description, $final_amount, $order_id);

                        if (isset($order_id) && isset($final_amount) && $userCharged) {
                            $this->model_sale_order->settle_payment($order_id, $final_amount);
                        }
                    } else {
                        //refund is done
                        $userCharged = $this->refundCustomer($customer_id, $description, $final_amount, $order_id);
                    }

                    $json['success'] = $this->language->get('text_settlement');
                } else {
                    $log->write('else iugu');
                }
            } else {
                //not settle only update so reset column settlement_amount
                $this->model_sale_order->settle_payment($order_id, $orderTotal);
            }

            $this->sendNewInvoice($order_id);
        } else {
            $json['status'] = false;
        }

        $json = json_encode($json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($json);
    }

    public function api()
    {
        $json = [];

        $this->load->language('sale/order');

        $log = new Log('error.log');
        $log->write('api/order');

        $this->document->setTitle($this->language->get('heading_title_fastorders'));

        if ($this->validate()) {
            // Store
            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
                $this->session->data['config_store_id'] = $store_id;
            } else {
                $store_id = 0;
            }
            $log->write($store_id);

            $this->load->model('setting/store');

            /*$store_info = $this->model_setting_store->getStore($store_id);
            $log->write($store_info);*/
            // if ($store_info) {
            //     $url = $store_info['ssl'];
            // } else {
            $url = HTTPS_CATALOG;
            // }
            //$log->write($this->session->data['cookie']);
            $log->write($this->request->get['api']);
            if (isset($this->request->get['api'])) {
                // Include any URL perameters

                $log->write('if cook');
                $url_data = [];
                $log->write('if');
                foreach ($this->request->get as $key => $value) {
                    if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                        $url_data[$key] = $value;
                    }
                }

                $curl = curl_init();

                // Set SSL if required
                if ('https' == substr($url, 0, 5)) {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                $log->write($url.'index.php?path='.$this->request->get['api'].($url_data ? '&'.http_build_query($url_data) : ''));

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, $url.'index.php?path='.$this->request->get['api'].($url_data ? '&'.http_build_query($url_data) : ''));

                if ($this->request->post) {
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
                }

                /*curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');*/

                $json = curl_exec($curl);

                /*if ( in_array( $this->request->post['order_status_id'], $this->config->get( 'config_ready_for_pickup_status' ) ) )
                {
                    $log->write('create delivery if');
                    $this->createDeliveryRequest($this->request->get['order_id']);
                }*/

                curl_close($curl);
            }

            $log->write('if ekse');
        } else {
            $response = [];
            $response['error'] = $this->error;
            unset($this->error);

            $json = json_encode($response);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($json);
    }

    public function isMyOrder($order_id, $store_id)
    {
        $this->load->model('sale/order');

        $row = $this->model_sale_order->isMyOrder($order_id, $store_id);

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function isVendorOrder($order_id)
    {
        $this->load->model('sale/order');

        $row = $this->model_sale_order->isVendorOrder($order_id, $this->user->getId());
        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    private function timeIsBetween($from, $to, $time, $time_diff = false)
    {
        //calculate from_time in minuts
        $i = explode(':', $to);
        if (12 == $i[0]) {
            $to_min = substr($i[1], 0, 2);
        } else {
            $to_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours
        $am_pm = substr($to, -2);
        if ('pm' == $am_pm) {
            $to_min += 12 * 60;
        }
        //calculate time in minuts
        $i = explode(':', $time);
        if (12 == $i[0]) {
            $min = substr($i[1], 0, 2);
        } else {
            $min = $i[0] * 60 + substr($i[1], 0, 2);
        }

        //if pm add 12 hours
        $am_pm = substr($time, -2);
        if ('pm' == $am_pm) {
            $min += 12 * 60;
        }

        //if time difference
        if ($time_diff) {
            $i = explode(':', $time_diff);
            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
        }

        if ($min < $to_min) {
            return true;
        } else {
            return false;
        }
    }

    //save timeslot in data
    public function save_timeslots()
    {
        $order_id = $this->request->get['order_id'];

        $delivery_date = $this->request->post['delivery_date'];

        $delivery_time = $this->request->post['delivery_time'];
    }

    public function payment_status()
    {
        if (!$this->user->isVendor()) {
            $this->load->model('sale/order');

            $order_id = $this->request->post['order_id'];
            $status = $this->request->post['status'];
            $store_id = $this->request->post['store_id'];

            $this->model_sale_order->payment_status($order_id, $status, $store_id);
        }
    }

    public function city_autocomplete()
    {
        $this->load->model('sale/order');

        $json = $this->model_sale_order->getCities();

        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function checkStoreDelivery()
    {
        $json = [];

        // if ($store_info['city_id']  !=  $this->request->post['shipping_city_id']) {
        //     $json['error']['warning'] = 'Store is not provide delivery in this area';
        // }
        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function notFraudApi()
    {
        $json = [];

        $this->load->language('sale/order');

        $log = new Log('error.log');
        $log->write('api/order');

        $this->document->setTitle($this->language->get('heading_title_fastorders'));

        if ($this->validate()) {
            // Store
            $log->write('api/order validate');
            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
                $this->session->data['config_store_id'] = $store_id;
            } else {
                $store_id = 0;
            }
            $log->write($store_id);

            $this->load->model('setting/store');

            $url = HTTPS_CATALOG;

            $log->write($this->request->get['api']);

            if (isset($this->request->get['api'])) {
                // Include any URL perameters
                $log->write('if cook');
                $url_data = [];
                $log->write('if');
                foreach ($this->request->get as $key => $value) {
                    if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                        $url_data[$key] = $value;
                    }
                }

                $curl = curl_init();

                // Set SSL if required
                if ('https' == substr($url, 0, 5)) {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, $url.'index.php?path='.$this->request->get['api'].($url_data ? '&'.http_build_query($url_data) : ''));

                $log->write($url_data);
                $log->write($this->request->post);

                if ($this->request->post) {
                    $this->load->model('localisation/order_status');

                    $order_status = $this->model_localisation_order_status->getOrderStatuses();

                    $log->write($order_status);

                    $order_status_id = 'no';
                    foreach ($order_status as $order_state) {
                        // code...
                        if ('pending' == strtolower($order_state['name'])) {
                            $order_status_id = $order_state['order_status_id'];
                            break;
                        }
                    }

                    $log->write($order_status_id);
                    if ('no' != $order_status_id) {
                        $this->request->post['comment'] = 'Marked not fraud from admin';
                        $this->request->post['order_status_id'] = $order_status_id;

                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
                    }
                }

                /*curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');*/

                $json = curl_exec($curl);

                $this->createDeliveryRequest($this->request->get['order_id']);

                $log->write('json');
                $log->write($json);
                curl_close($curl);
            }

            $log->write('if ekse');
        } else {
            $response = [];
            $response['error'] = $this->error;
            unset($this->error);

            $json = json_encode($response);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($json);
    }

    public function reversePaymentApi()
    {
        //set order status to Pending and raise delivery request

        /*Iugu::setApiKey($this->config->get('iugu_token'));

        $Invoice = Iugu_Invoice::fetch ("AE27126C4B8A4C79859B29CD210BA58F");
        $Invoice-> refund ();*/

        if (isset($this->request->post)) {
            $event = $this->request->post['event'];
            $data = $this->request->post['data'];

            $this->load->model('payment/iugu');

            $order_id = $this->model_payment_iugu->updateOrderHistory($data['id'], $data['status']);

            if (false !== $order_id) {
                /* Carrega Model */
                $this->load->model('payment/iugu');

                /* Carrega library */
                require_once DIR_SYSTEM.'library/Iugu.php';

                /* Define a API */
                Iugu::setApiKey($this->config->get('iugu_token'));

                $data = [];

                /* Método de Pagamento (somente boleto) */
                $data['payable_with '] = $this->config->get('iugu_reminder_payment_method');

                /* Url de Notificações */
                $data['notification_url'] = $this->url->link('payment/iugu/notification', '', 'SSL');

                /* Url de Expiração */
                $data['expired_url'] = $this->url->link('payment/iugu/expired', '', 'SSL');

                /* Validade */
                $data['due_date'] = date('d/m/Y', strtotime('+7 days'));

                /* Captura informações do pedido */
                $order_info = $this->model_payment_iugu->getOrder($order_id);

                /* Captura o E-mail do Cliente */
                $data['email'] = $order_info['email'];

                /* Captura os produtos comprados */
                $products = $this->model_payment_iugu->getOrderProducts($order_id);

                /* Formata as informações do produto (Nome, Quantidade e Preço unitário) */
                $data['items'] = [];

                $count = 0;

                foreach ($products as $product) {
                    $data['items'][$count] = [
                        'description' => $product['name'],
                        'quantity' => $product['quantity'],
                        'price_cents' => $this->currency->format($product['price'], 'BRL', null, false) * 100,
                    ];
                    ++$count;
                }

                $totals = $this->model_payment_iugu->getOrderTotals($order_id);

                foreach ($totals as $total) {
                    if ('sub_total' != $total['code'] && 'total' != $total['code']) {
                        $data['items'][$count] = [
                            'description' => $total['title'],
                            'quantity' => 1,
                            'price_cents' => $total['value'] * 100,
                        ];
                        ++$count;
                    }
                }

                unset($count);

                /* Captura os Descontos, Acréscimo, Vale-Presente, Crédito do Cliente, etc. */
                $data['items'] = $data['items'];

                /* Captura valor do desconto */
                $sub_total = 0;

                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $sub_total = $total['value'];
                        break;
                    }
                }
                $data['discount_cents'] = $this->model_payment_iugu->getDiscount($sub_total, $this->config->get('iugu_reminder_payment_method'));

                /* Captura valor do acréscimo */
                $data['tax_cents'] = $this->model_payment_iugu->getInterest($sub_total, $this->config->get('iugu_reminder_payment_method'));

                /* Informações do Cliente */
                $data['payer'] = [];
                $data['payer']['cpf_cnpj'] = isset($order_info['custom_field'][$this->config->get('iugu_custom_field_cpf')]) ? $order_info['custom_field'][$this->config->get('iugu_custom_field_cpf')] : '';
                $data['payer']['name'] = $order_info['firstname'].' '.$order_info['lastname'];
                $data['payer']['phone_prefix'] = substr($order_info['telephone'], 0, 2);
                $data['payer']['phone'] = substr($order_info['telephone'], 2);
                $data['payer']['email'] = $order_info['email'];

                /* Informações de Endereço */
                $data['payer']['address'] = [];
                $data['payer']['address']['street'] = $order_info['payment_address_1'];
                $data['payer']['address']['number'] = isset($order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')]) ? $order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')] : 0;
                $data['payer']['address']['city'] = $order_info['payment_city'];
                $data['payer']['address']['state'] = $order_info['payment_zone_code'];
                $data['payer']['address']['country'] = $order_info['payment_country'];
                $data['payer']['address']['zip_code'] = $order_info['payment_postcode'];

                /* Informações adicionais */
                $data['custom_variables'] = [
                    [
                        'name' => 'order_id',
                        'value' => $order_id,
                    ],
                ];

                /* Envia informações */
                try {
                    $token = Iugu_Invoice::create($data);
                } catch (Exception $e) {
                    $this->log->write($e->getMessage());
                    die();
                }

                $result = [];

                foreach (reset($token) as $key => $value) {
                    $result[$key] = $value;
                }

                if (isset($result['errors']) && !empty($result['errors'])) {
                    foreach ($result['errors'] as $key => $error_base) {
                        foreach ($error_base as $error) {
                            $this->log->write('Iugu: '.ucfirst($key).' '.$error);
                        }
                    }
                } else {
                    $this->model_payment_iugu->updateOrder($order_id, $result);

                    $data = array_merge($this->language->load('mail/iugu'), $result);

                    $mail = new Mail();
                    $mail->protocol = $this->config->get('config_mail_protocol');
                    $mail->parameter = $this->config->get('config_mail_parameter');
                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                    $mail->setTo($order_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject(sprintf($this->language->get('text_mail_subject_expired'), $this->config->get('config_name')));
                    $mail->setHtml($this->getHtml($order_info, $products, $totals));

                    $mail->setText($this->language->get('text_mail_text'));

                    return $mail->send();
                }
            } else {
                $this->log->write('Iugu: Invoice '.$data['id'].' não localizado.');
            }
        }
    }

    public function createDeliveryRequest($order_id, $order_status_id = 1)
    {
        $log = new Log('error.log');
        $order_info = $this->getOrder($order_id);

        $this->load->model('account/order');

        $deliveryAlreadyCreated = $this->model_account_order->getOrderDeliveryId($this->request->get['order_id']);

        if (1 == $order_status_id && $order_info && !$deliveryAlreadyCreated) {
            $log->write('inside createDeliveryRequest');

            $data['products']['products'] = [];

            $products = $this->model_account_order->getOrderProducts($order_id);

            $log = new Log('error.log');

            $log->write('tester log');
            $log->write($order_info);

            foreach ($products as $product) {
                $replacable = 'no';

                if ('replacable' == $product['product_type']) {
                    $replacable = 'yes';
                }

                $this->load->model('tool/image');

                if (file_exists(DIR_IMAGE.$product['image'])) {
                    $image = HTTP_IMAGE.$product['image'];
                } else {
                    $image = HTTP_IMAGE.'placeholder.png';
                }

                $var = [
                    'product_name' => $product['name'],
                    'product_unit' => $product['unit'],
                    'product_quantity' => $product['quantity'],
                    'product_image' => $image, //"http:\/\/\/product-images\/camera.jpg",
                    'product_price' => $product['price'], //"1500.00",//product price unit price?? or total
                    'product_replaceable' => $replacable, //"no"
                ];

                array_push($data['products']['products'], $var);
            }
            $log->write($data['products']['products']);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            $log->write($store_details);

            $delivery_priority = 'normal';

            $temp = explode('.', $order_info['shipping_code']);
            if (isset($temp[0])) {
                $delivery_priority = $temp[0];
            }

            $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);

            $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

            $deliverAddress = $order_info['shipping_flat_number'].', '.$order_info['shipping_building_name'].', '.$order_info['shipping_landmark'];

            $data['body'] = [
                'pickup_name' => $store_details['name'], //store name??
                'pickup_phone' => $store_details['telephone'],
                'pickup_address' => $store_details['address'],
                'pickup_city' => $store_city_name,
                'pickup_state' => 'Brussels',
                'from_lat' => $store_details['latitude'],
                'from_lng' => $store_details['longitude'],
                'pickup_zipcode' => $store_details['store_zipcode'], //''
                'pickup_notes' => '',
                'dropoff_name' => $order_info['shipping_name'],
                'dropoff_phone' => $order_info['telephone'],
                'dropoff_address' => $deliverAddress,
                'to_lat' => $order_info['latitude'],
                'to_lng' => $order_info['longitude'],
                'dropoff_city' => $order_info['shipping_city'], // from $order_info['city_id'],
                'dropoff_state' => 'Brussels',
                'dropoff_zipcode' => $order_info['shipping_zipcode'], // from $order_info['city_id'],
                'delivery_priority' => $delivery_priority, // normal/express all small
                'delivery_date' => $order_info['delivery_date'], //2017-04-13
                'delivery_slot' => $timeSlotAverage, //$order_info['delivery_timeslot'],//"10:30" //delivery slot is time so what will i enter here as i have data in format 06:26pm - 08:32pm
                'dropoff_notes' => '',
                'type_of_delivery' => 'delivery', //delivery/return . Is it only one option for this index?

                'manifest_id' => $order_id, //order_id,
                'manifest_data' => json_encode($data['products']),
                'payment_method' => $order_info['payment_method'],
                'payment_code' => $order_info['payment_code'],
            ];

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['token'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/createDelivery', $data);
                $log->write('reeponse');
                $log->write($res);

                if ($res['status']) {
                    $log->write('stsus');
                    if (isset($res['data']->delivery_id)) {
                        $delivery_id = $res['data']->delivery_id;
                        $log->write($delivery_id);
                        $this->db->query('UPDATE `'.DB_PREFIX."order` SET delivery_id = '".$delivery_id."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");
                    }
                    //save in order table delivery id
                }
            }
        }
    }

    public function createDeliveryRequestold($order_id, $order_status_id = 1)
    {
        $log = new Log('error.log');
        $order_info = $this->getOrder($order_id);
        $log->write('inside createDeliveryRequest');
        if (1 == $order_status_id && $order_info) {
            $log->write('inside createDeliveryRequest');
            $this->load->model('account/order');

            $data['products']['products'] = [];

            $products = $this->model_account_order->getOrderProducts($order_id);

            $log = new Log('error.log');

            $log->write('tester log');
            $log->write($order_info);

            foreach ($products as $product) {
                $replacable = 'no';

                if ('replacable' == $product['product_type']) {
                    $replacable = 'yes';
                }

                $this->load->model('tool/image');

                if (file_exists(DIR_IMAGE.$product['image'])) {
                    $image = HTTP_IMAGE.$product['image'];
                } else {
                    $image = HTTP_IMAGE.'placeholder.png';
                }

                $var = [
                    'product_name' => $product['name'],
                    'product_unit' => $product['unit'],
                    'product_quantity' => $product['quantity'],
                    'product_image' => $image, //"http:\/\/\/product-images\/camera.jpg",
                    'product_price' => $product['price'], //"1500.00",//product price unit price?? or total
                    'product_replaceable' => $replacable, //"no"
                ];

                array_push($data['products']['products'], $var);
            }
            $log->write($data['products']['products']);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            $log->write($store_details);

            $delivery_priority = 'normal';

            $temp = explode('.', $order_info['shipping_code']);
            if (isset($temp[0])) {
                $delivery_priority = $temp[0];
            }

            $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);

            $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

            $data['body'] = [
                'pickup_name' => $store_details['name'], //store name??
                'pickup_phone' => $store_details['telephone'],
                'pickup_address' => 'BTM  2nd stage, Axa building', //$store_details['address'],//
                'pickup_city' => 'Bengaluru', //$store_city_name,// from $order_info['city_id'],
                'pickup_state' => 'Karnataka',
                'pickup_zipcode' => '560067', //$store_details['zipcode'],
                'pickup_notes' => '',
                'dropoff_name' => $order_info['shipping_name'],
                'dropoff_phone' => $order_info['telephone'],
                'dropoff_address' => 'BTM  2nd stage, Axa building', //$order_info['shipping_building_name']." ".$order_info['shipping_landmark'],//'BTM  2nd stage, Axa building',
                'dropoff_city' => $order_info['shipping_city'], // from $order_info['city_id'],
                'dropoff_state' => 'Karnataka', // from $order_info['city_id'],
                'dropoff_zipcode' => '560068', //$order_info['shipping_zipcode'],// from $order_info['city_id'],
                'delivery_priority' => $delivery_priority, // normal/express all small
                'delivery_date' => $order_info['delivery_date'], //2017-04-13
                'delivery_slot' => $timeSlotAverage, //$order_info['delivery_timeslot'],//"10:30" //delivery slot is time so what will i enter here as i have data in format 06:26pm - 08:32pm
                'dropoff_notes' => '',
                'type_of_delivery' => 'delivery', //delivery/return . Is it only one option for this index?

                'manifest_id' => $order_id, //order_id,
                'manifest_data' => json_encode($data['products']),
            ];
            //if pending status create delivery in system

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['tokens'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/createDelivery', $data);
                $log->write('reeponse');
                $log->write($res);

                if ($res['status']) {
                    $log->write('stsus');
                    if (isset($res['data']->delivery_id)) {
                        $delivery_id = $res['data']->delivery_id;
                        $log->write($delivery_id);
                        $this->db->query('UPDATE `'.DB_PREFIX."order` SET delivery_id = '".$delivery_id."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");

                        return true;
                    }
                    //save in order table delivery id
                }
            }
        }

        return false;
    }

    public function getOrder($order_id)
    {
        $order_query = $this->db->query('SELECT *, (SELECT os.name FROM `'.DB_PREFIX.'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `'.DB_PREFIX."order` o WHERE o.order_id = '".(int) $order_id."'");

        if ($order_query->num_rows) {
            $this->load->model('localisation/language');
            $this->load->model('account/order');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            $city_name = $this->model_account_order->getCityName($order_query->row['shipping_city_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'email' => $order_query->row['email'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'custom_field' => unserialize($order_query->row['custom_field']),
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_city' => $city_name,
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],
                'shipping_code' => $order_query->row['shipping_code'],

                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],

                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_status' => $order_query->row['order_status'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'order_pdf_link' => $order_query->row['order_pdf_link'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],

                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                /*'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added']*/
            ];
        } else {
            return false;
        }
    }

    public function getTimeslotAverage($timeslot)
    {
        $str = $timeslot; //"06:26pm - 08:32pm";
        $arr = explode('-', $str);
        //print_r($arr);
        if (2 == count($arr)) {
            $one = date('H:i', strtotime($arr[0]));
            $two = date('H:i', strtotime($arr[1]));

            $time1 = explode(':', $one);
            $time2 = explode(':', $two);
            if (2 == count($time1) && 2 == count($time2)) {
                $mid1 = ($time1[0] + $time2[0]) / 2;
                $mid2 = ($time1[1] + $time2[1]) / 2;

                $mid1 = round($mid1);
                $mid2 = round($mid2);

                if ($mid2 <= 9) {
                    $mid2 = '0'.$mid2;
                }
                if ($mid1 <= 9) {
                    $mid1 = '0'.$mid1;
                }

                //if 19.5 is mid1 then i send 19 integer part cant send decimals

                return $mid1.':'.$mid2;
            }
        }

        return false;
    }

    public function settle_payment()
    {
        $this->load->language('sale/order');

        $json['status'] = true;

        $log = new Log('error.log');

        if (!$this->user->isVendor()) {
            $this->load->model('sale/order');

            $order_id = $this->request->post['order_id'];
            $customer_id = $this->request->post['customer_id'];
            $final_amount = $this->request->post['final_amount'];

            $iuguData = $this->model_sale_order->getOrderIuguAndTotal($order_id);

            if ($iuguData) {
                $invoiceId = $iuguData['invoice_id'];
                $original_subtotal = $iuguData['value'];

                //$this->captureInvoice($order_id,$invoiceId);

                $log->write('admin settle ');

                if ($original_subtotal != $final_amount) {
                    //cancel invoice API and create new invoice and save this new api where??
                    // wallet entry do
                    $amountWallet = $original_subtotal - $final_amount;
                    $description = 'On Order #'.$order_id;
                    $log->write('admin settle not =');
                    //$this->actionOnCustomerWallet($customer_id,$description,$amountWallet);

                    $this->chargeCustomer($customer_id, $description, $final_amount, $order_id);
                }

                if (isset($order_id) && isset($final_amount)) {
                    $this->model_sale_order->settle_payment($order_id, $final_amount);
                }

                $json['success'] = $this->language->get('text_settlement');
            } else {
                $json['status'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function captureInvoice($order_id, $invoiceId)
    {
        $log = new Log('error.log');
        $log->write('captureInvoice');

        if ($order_id && $invoiceId) {
            $invoiceId = $invoiceId;

            $log->write($invoiceId);
            $ch = curl_init();
            //https://api.iugu.com/v1/invoices/5A4CBC68F7A647ECADD59D4E1B4B0DDF/capture
            curl_setopt($ch, CURLOPT_URL, 'https://api.iugu.com/v1/invoices/'.$invoiceId.'/capture');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, array('name'=>"Todd",'commission_percent'=>10));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('iugu_token'));

            $headers = [];
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $result = json_decode($result);

            $log->write($result);
            if (curl_errno($ch) || isset($result->errors)) {
                return false;
            }
            curl_close($ch);
            //print_r($result);die;
            return true;
        }

        return false;
    }

    public function actionOnCustomerWallet($customer_id, $description, $amount)
    {
        $this->load->model('sale/customer');

        $this->model_sale_customer->addCredit($customer_id, $description, $amount);

        return true;
    }

    public function chargeCustomerOld($customer_id, $description, $final_amount, $order_id)
    {
        require_once DIR_SYSTEM.'library/Iugu.php';

        $log = new Log('error.log');

        $data['settlement_tab'] = false;

        $this->load->model('sale/order');
        $iuguData = $this->model_sale_order->getOrderIugu($order_id);

        $log->write('chargeCustomer');
        $log->write($iuguData);
        if ($iuguData) {
            $invoiceId = $iuguData['invoice_id'];

            $use_payment_method_id = $iuguData['identification'];

            Iugu::setApiKey($this->config->get('iugu_token'));

            $invoice = Iugu_Invoice::fetch($invoiceId);
            $resp = $invoice->refund();

            $log->write('refundAPI');
            $log->write($resp);

            if ($resp) {
                //new invoice create and charge
                $customerIuguData = $this->model_sale_order->getOrderIuguCustomer($customer_id);

                /* Here i have to code */
                $log->write('refinuded if');

                $data['amount'] = $final_amount;
                $data['order_id'] = $order_id;
                //$data['payment_method_id'] = $customerIuguData['payment_method_id'];
                $data['payment_method_id'] = $use_payment_method_id;

                if (!empty($use_payment_method_id)) {
                    $iuguChargeed = $this->iuguCharge($data);

                    if ($iuguChargeed) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }

        return false;
    }

    public function chargeCustomer($customer_id, $description, $final_amount, $order_id)
    {
        require_once DIR_SYSTEM.'library/Iugu.php';

        $log = new Log('error.log');

        $data['settlement_tab'] = false;

        $this->load->model('sale/order');
        $iuguData = $this->model_sale_order->getOrderIugu($order_id);

        $log->write('chargeCustomer');
        $log->write($iuguData);
        if ($iuguData) {
            $invoiceId = $iuguData['invoice_id'];

            $use_payment_method_id = $iuguData['identification'];

            Iugu::setApiKey($this->config->get('iugu_token'));

            //new invoice create and charge
            $customerIuguData = $this->model_sale_order->getOrderIuguCustomer($customer_id);

            $data['amount'] = $final_amount;
            $data['order_id'] = $order_id;
            $data['payment_method_id'] = $use_payment_method_id;

            if (!empty($use_payment_method_id)) {
                $iuguChargeed = $this->iuguCharge($data);

                if ($iuguChargeed) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    public function refundCustomer($customer_id, $description, $final_amount, $order_id)
    {
        require_once DIR_SYSTEM.'library/Iugu.php';

        $log = new Log('error.log');

        $data['settlement_tab'] = false;

        $this->load->model('sale/order');
        $iuguData = $this->model_sale_order->getOrderIugu($order_id);

        $log->write('refundCustomer');
        $log->write($iuguData);
        if ($iuguData) {
            $invoiceId = $iuguData['invoice_id'];

            $use_payment_method_id = $iuguData['identification'];

            Iugu::setApiKey($this->config->get('iugu_token'));

            $invoice = Iugu_Invoice::fetch($invoiceId);
            $resp = $invoice->refund();

            $log->write('refundAPI');
            $log->write($resp);

            if ($resp) {
                return true;
            }
        }

        return false;
    }

    public function iuguCharge($data)
    {
        $order_id = $data['order_id'];

        $amount = $data['amount'];

        $description = 'ORDERID #'.$order_id.' Changed';

        $payment_method_id = $data['payment_method_id'];
        /* Carrega Model */
        //$this->load->model('payment/iugu');

        /* Carrega library */
        require_once DIR_SYSTEM.'library/Iugu.php';

        /* Define a API */
        Iugu::setApiKey($this->config->get('iugu_token'));

        $data = [];

        $data['months'] = 1;
        $data['payable_with'] = 'credit_card';

        /* Url de Notificações */
        $data['notification_url'] = $this->url->link('payment/iugu/notification', '', 'SSL');

        /* Url de Expiração */
        $data['expired_url'] = $this->url->link('payment/iugu/expired', '', 'SSL');

        /* Validade */
        $data['due_date'] = date('d/m/Y', strtotime('+7 days'));

        /* Carrega model de pedido */
        $this->load->model('account/order');
        $this->load->model('checkout/order');

        //$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_info = $this->model_checkout_order->getOrder($order_id);

        /* Captura o E-mail do Cliente */
        $data['email'] = $order_info['email'];

        /* Captura os produtos comprados */
        //$products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);

        $data['items'] = [];

        $count = 0;

        unset($count);

        $data['items'][0] = [
            'description' => $description,
            'quantity' => 1,
            'price_cents' => $amount * 100,
        ];

        /* Informações do Cliente */
        $data['payer'] = [];
        $data['payer']['cpf_cnpj'] = isset($order_info['custom_field'][$this->config->get('iugu_custom_field_cpf')]) ? $order_info['custom_field'][$this->config->get('iugu_custom_field_cpf')] : '';
        $data['payer']['name'] = $order_info['firstname'].' '.$order_info['lastname'];
        $data['payer']['phone_prefix'] = substr($order_info['telephone'], 0, 2);
        $data['payer']['phone'] = substr($order_info['telephone'], 2);
        $data['payer']['email'] = $order_info['email'];

        /* Informações de Endereço */
        $data['payer']['address'] = [];

        $data['payer']['address']['street'] = $order_info['shipping_address'];
        $data['payer']['address']['number'] = isset($order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')]) ? $order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')] : 0;
        $data['payer']['address']['city'] = $order_info['shipping_city'];

        $data['payer']['address']['zip_code'] = $order_info['shipping_zipcode'];

        $log = new Log('error.log');

        $log->write('admin recharge '.$payment_method_id);

        $data['customer_payment_method_id'] = $payment_method_id;

        $result = Iugu_Charge::create($data);

        $response = [];

        foreach (reset($result) as $key => $value) {
            $response[$key] = $value;
        }

        $response['identification'] = $payment_method_id;

        $log->write($response);

        if (isset($response['success']) && $response['success']) {
            $this->model_checkout_order->addOrder($order_id, $response, true);

            return true;
        } else {
            return false;
        }
    }

    public function sendNewInvoice($order_id, $comment = '', $notify = true)
    {
        $this->trigger->fire('pre.order.history.add', $order_id);

        //print_r($this->config->get('config_ssl'));die;

        $order_info = $this->getOrder($order_id);

        $pdf_link = '';

        $log = new Log('error.log');
        $log->write('sendNewInvoice');

        if ($order_info) {
            // Fraud Detection
            $this->load->model('account/customer');
            $this->load->model('sale/order');

            $order_status_id = $order_info['order_status_id'];

            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

            if ($customer_info && $customer_info['safe']) {
                $safe = true;
            } else {
                $safe = false;
            }

            if ($this->config->get('config_fraud_detection')) {
                $this->load->model('checkout/fraud');

                $risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

                if (!$safe && $risk_score > $this->config->get('config_fraud_score')) {
                    $order_status_id = $this->config->get('config_fraud_status_id');
                }
            }

            // Ban IP
            if (!$safe) {
                $log->write('addOrderHistory not safe');
                $status = false;

                if ($order_info['customer_id']) {
                    $results = $this->model_account_customer->getIps($order_info['customer_id']);

                    foreach ($results as $result) {
                        if ($this->model_account_customer->isBanIp($result['ip'])) {
                            $status = true;

                            break;
                        }
                    }
                } else {
                    $status = $this->model_account_customer->isBanIp($order_info['ip']);
                }

                $log->write('status'.$status);
                if ($status) {
                    $order_status_id = $this->config->get('config_order_status_id');
                }
            }

            $order_status = $this->db->query('SELECT name FROM '.DB_PREFIX."order_status WHERE order_status_id = '".(int) $order_status_id."' AND language_id = '".(int) $order_info['language_id']."'");

            if ($order_status->num_rows) {
                $order_status = $order_status->row['name'];
            } else {
                $order_status = '';
            }

            // Account Href
            $order_href = '';
            $order_pdf_href = '';

            if ($order_info['customer_id']) {
                $order_href = $order_info['store_url'].'index.php?path=account/order/info&order_id='.$order_info['order_id'];
            }

            //Address Shipping and Payment
            $totals = [];
            $tax_amount = 0;

            if (0 != strlen($order_info['shipping_name'])) {
                $address = $order_info['shipping_name'].'<br />'.$order_info['shipping_address'].'<br /><b>Contact No.:</b> '.$order_info['shipping_contact_no'];
            } else {
                $address = '';
            }

            $payment_address = '';

            $order_total = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_total` WHERE order_id = '".(int) $order_info['order_id']."' order by sort_order");

            foreach ($order_total->rows as $total) {
                $totals[$total['code']][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    'value' => $total['value'],
                ];

                if ('tax' == $total['code']) {
                    $tax_amount += $total['value'];
                }
            }

            $this->load->model('account/order');

            $iugu_detail = $this->getOrderDetailIugu($order_info['order_id']);

            $log->write($iugu_detail);

            if (count($iugu_detail) > 0) {
                $order_pdf_href = '<a href="'.$iugu_detail['pdf'].'"> Payment Gateway Receipt </a>';
            }

            $special = null;

            $data = [
                'template_id' => 'order_'.(int) $order_status_id,
                'order_info' => $order_info,
                'address' => $address,
                'payment_address' => $payment_address,
                'special' => $special,
                'order_href' => $order_href,
                'order_pdf_href' => $order_pdf_href,
                'order_status' => $order_status,
                'totals' => $totals,
                'tax_amount' => $tax_amount,
                'invoice_no' => !empty($invoice_no) ? $invoice_no : '',
                'new_invoice' => 1,
            ];

            $getTotal = $order_total->rows;

            $textData = [
                'order_info' => $order_info,
                'order_id' => $order_id,
                'order_status' => $order_status,
                'comment' => $comment,
                'notify' => $notify,
                'getProdcuts' => $this->model_sale_order->getRealOrderProducts($order_id),
                'getVouchers' => [],
                'getTotal' => $getTotal,
            ];

            $subject = $this->emailtemplate->getSubject('OrderAll', 'invoice_1', $data);
            $message = $this->emailtemplate->getMessage('OrderAll', 'invoice_1', $data);

            $mail = new mail($this->config->get('config_mail'));
            //$mail->setTo( 'chaurasia.abhi09@gmail.com');
            $mail->setTo($order_info['email']);

            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($order_info['store_name']);
            $mail->setSubject($subject);
            $mail->setHtml($message);
            $mail->send();
        }

        return true;
    }

    public function getOrderDetailIugu($order_id)
    {
        return $this->db->query('SELECT * FROM `'.DB_PREFIX.'order_iugu` WHERE `order_id` = '.(int) $order_id)->row;
    }
}
