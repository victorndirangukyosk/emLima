<?php

class ControllerSaleEditinvoice extends Controller {

    private $error = [];

    public function EditInvoice() {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['token'] = $this->session->data['token'];

        $data['sale_order_link'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

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
        $data['column_produce_type'] = $this->language->get('column_produce_type');

        $data['column_unit'] = $this->language->get('column_unit') . ' ( Ordered ) ';
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity') . ' ( Ordered ) ';
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $data['column_unit_update'] = $this->language->get('column_unit') . ' ( Variance ) ';
        $data['column_quantity_update'] = $this->language->get('column_quantity') . ' ( Variance ) ';

        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_cpf_number'] = $this->language->get('text_cpf_number');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $this->load->model('extension/extension');

        $sort_order = [];

        $data['allCodes'] = $this->model_extension_extension->getExtensions('total');

        foreach ($data['allCodes'] as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
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
            require_once DIR_SYSTEM . 'library/Iugu.php';

            $data['settlement_tab'] = true;
            $data['settlement_done'] = false;

            $order_info = $this->model_sale_order->getOrder($order_id);
            //$this->load->model('sale/order');
            //check vendor order

            if ($this->user->isVendor()) {
                if (!$this->isVendorOrder($order_id)) {
                    $this->response->redirect($this->url->link('error/not_found'));
                }
            }
            //echo "<pre>";print_r($order_info);die;
            //$data['settlement_done'] = false;
            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

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
                    $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'] . $order_info['invoice_sufix'];
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

                $sub_total = 0;
                $tax = 0;
                $new_total = 0;
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

                    $variations = $this->model_sale_order->getProductVariationsNew($product['name'], 75, $order_id);
                    $missed_quantity = $this->model_sale_order->getMissingProductQuantityByProductIdOrderId($order_id, $product['product_id'], 0);
                    $required_quantity = isset($missed_quantity) && count($missed_quantity) > 0 ? $missed_quantity['quantity_required'] : 0;
                    $product_data[] = [
                        'name' => $product['name'],
                        'product_id' => $product['product_id'],
                        'model' => $product['model'],
                        'unit' => $product['unit'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'] - $required_quantity,
                        'produce_type' => $product['produce_type'],
                        'product_note' => $product['product_note'],
                        /* OLD PRICE WITH TAX */ //'price' => $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
                        'price' => number_format((float) $product['price'], 2, '.', ''),
                        //'total' => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0)
                        /* OLD TOTAL WITH TAX */ //'total' => ($product['price'] * $product['quantity']) + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),
                        'total' => ($product['price'] * ($product['quantity'] - $required_quantity)),
                        'variations' => $variations,
                        'missed_quantity' => isset($missed_quantity) && count($missed_quantity) > 0 ? $missed_quantity['quantity_required'] : 0,
                    ];
                    $sub_total += ($product['price'] * ($product['quantity'] - $required_quantity));
                    $tax += $product['tax'] * ($product['quantity'] - $required_quantity);
                }
                $new_total = $sub_total + $tax;

                $total_data = [];

                if ($store_id) {
                    $totals = $this->model_sale_order->getVendorOrderTotals($order_id, $store_id);
                } else {
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                }

                foreach ($totals as $total) {
                    if ($total['code'] == 'sub_total') {
                        $total_data[] = [
                            'title' => $total['title'],
                            'code' => $total['code'],
                            //'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                            'text' => number_format((float) $sub_total, 2, '.', ''),
                            'actual_value' => number_format((float) $total['actual_value'], 2, '.', ''),
                        ];
                    } elseif ($total['code'] == 'tax') {
                        $total_data[] = [
                            'title' => $total['title'],
                            'code' => $total['code'],
                            //'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                            'text' => number_format((float) $tax, 2, '.', ''),
                            'actual_value' => number_format((float) $total['actual_value'], 2, '.', ''),
                        ];
                    } elseif ($total['code'] == 'total') {
                        $total_data[] = [
                            'title' => $total['title'],
                            'code' => $total['code'],
                            //'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                            'text' => number_format((float) $new_total, 2, '.', ''),
                            'actual_value' => number_format((float) $total['actual_value'], 2, '.', ''),
                        ];
                    } else {
                        $total_data[] = [
                            'title' => $total['title'],
                            'code' => $total['code'],
                            //'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                            'text' => number_format((float) $total['value'], 2, '.', ''),
                            'actual_value' => number_format((float) $total['actual_value'], 2, '.', ''),
                        ];
                    }
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
                    'cpf_number' => ($this->getUser($order_info['customer_id'])) ? $this->getUser($order_info['customer_id']) : $order_info['fax'],
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_city' => $order_info['shipping_city'],
                    'shipping_contact_no' => ($order_info['shipping_contact_no']) ? $order_info['shipping_contact_no'] : $order_info['telephone'],
                    'shipping_name' => $order_info['shipping_name'],
                    'po_number' => $order_info['po_number'],
                    'shipping_method' => $order_info['shipping_method'],
                    'payment_method' => $order_info['payment_method'],
                    'product' => $product_data,
                    'total' => $total_data,
                    'customer_id' => $order_info['customer_id'],
                    'comment' => nl2br($order_info['comment']),
                    'order_status_id' => $order_info['order_status_id'],
                ];

                // echo '<pre>';
                // print_r($order_info);
                // echo  '</pre>';die;
            }
        }

        //echo "<pre>";print_r($data);die;
        $this->response->setOutput($this->load->view('sale/edit_order_invoice.tpl', $data));
    }

    public function updateInvoice() {
        $json = [];

        $this->load->language('sale/order');

        $json['status'] = true;
        $log = new Log('error.log');
        $log->write('api/updateInvoice');

        $this->load->model('sale/order');

        $this->load->model('tool/image');

        $datas = $this->request->post;

        $allp_id = true;

        $log = new Log('error.log');
        $log->write('products');
        $log->write($datas['products']);
        $log->write('products');
        //  echo "<pre>";print_r($datas);die;

        $this->model_sale_order->updatePO($this->request->get['order_id'], $datas['po_number']);

        foreach ($datas['products'] as $p_id_key => $updateProduct) {
            if (!is_numeric($updateProduct['product_id'])) {
                $json['status'] = false;
                $allp_id = false;
                $json['message'] = 'One of the newly added products was not selected from auto suggestion';
            }
        }

        $data_valid = $this->validateform($datas['products']);
        if ($data_valid['error'] == false) {
            $uniqueIds = array_keys($datas['products']);
            $log->write($datas);

            $order_id = $this->request->get['order_id'];

            $order_info = $this->model_sale_order->getOrder($order_id);

            $store_id = 0;

            if (!empty($order_info)) {
                $store_id = $order_info['store_id'];
            }

            $shipping_city_id = 0;

            if (!empty($order_info)) {
                $shipping_city_id = $order_info['shipping_city_id'];
            }

            //echo "<pre>";print_r($datas);die;
            if ($this->validate() && $allp_id) {
                // Store

                if (isset($order_info['total'])) {
                    $old_total = $order_info['total'];
                }

                $old_sub_total = 0;

                /* if($this->model_sale_order->hasRealOrderProducts($order_id)) {
                  $log->write('edited again');
                  $totals = $this->model_sale_order->getOrderTotals($order_id);
                  } else {
                  $totals = $this->model_sale_order->getOrderTotals($order_id);
                  } */

                //echo "<pre>";print_r($order_id);die;
                $totals = $this->model_sale_order->getOrderTotals($order_id);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $old_sub_total = $total['value'];
                    }

                    if ('total' == $total['code']) {
                        $old_total = $total['value'];
                    }
                }

                //echo "<pre>";print_r($old_sub_total);die;

                $allProductIds = $this->model_sale_order->getOrderProductsIds($order_id);
                foreach ($allProductIds as $deletePro) {
                    if (!isset($datas['products'][$deletePro['product_id']])) {
                        $log->write('DELETE PRODUCT');
                        $log->write($deletePro['product_id']);
                        $log->write('DELETE PRODUCT');
                        $products = $this->model_sale_order->deleteOrderProduct($order_id, $deletePro['product_id']);
                        $order_missing_product_info = $this->model_sale_order->deleteOrderProductToMissingProductsFromInvoice($deletePro['product_id'], $order_id);
                    } else {
                        //$log->write("set");
                    }
                }

                $sumTotal = 0;

                $tempProds['products'] = [];

                //$log->write($datas['products']);

                $vendor_id = $this->model_sale_order->getVendorId($store_id);

                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                /* IF CUSTOMER SUB CUSTOMER */
                $parent_customer_info = NULL;
                $pricing_category = NULL;
                if (isset($customer_info) && $customer_info['parent'] > 0) {
                    $parent_customer_info = $this->model_account_customer->getCustomer($customer_info['parent']);
                }

                if ($parent_customer_info == NULL && isset($customer_info['customer_category']) && $customer_info['customer_category'] != NULL) {
                    $pricing_category = $customer_info['customer_category'];
                }

                if ($parent_customer_info != NULL && isset($parent_customer_info) && isset($parent_customer_info['customer_category']) && $parent_customer_info['customer_category'] != NULL) {
                    $pricing_category = $parent_customer_info['customer_category'];
                }

                //echo "<pre>";print_r($datas['products']);die;
                foreach ($datas['products'] as $p_id_key => $updateProduct) {
                    $updateProduct['quantity'] = $updateProduct['quantity'];
                    $updateProduct['store_id'] = $store_id;
                    $updateProduct['vendor_id'] = $vendor_id;
                    $custom_price = $updateProduct['price'];

                    if (is_numeric($p_id_key)) {
                        $updateProduct_tax_total = NULL;
                        //echo "<pre>";print_r($datas['products']);die;
                        $updateProduct_tax_total = $this->model_tool_image->getTaxTotalCustom($updateProduct, $store_id, $pricing_category, $custom_price);
                        $products = $this->model_sale_order->updateOrderProduct($order_id, $p_id_key, $updateProduct, $updateProduct_tax_total);
                    } else {
                        $updateProduct_tax_total = NULL;
                        //echo "<pre>";print_r($updateProduct);die;
                        $updateProduct_tax_total = $this->model_tool_image->getTaxTotalCustom($updateProduct, $store_id, $pricing_category, $custom_price);
                        $products = $this->model_sale_order->updateOrderNewProduct($order_id, $updateProduct['product_id'], $updateProduct, $updateProduct_tax_total);
                    }

                    $sumTotal += ($updateProduct['price'] * $updateProduct['quantity']);

                    array_push($tempProds['products'], $updateProduct);

                    if ($updateProduct['quantity_missed'] != NULL && $updateProduct['quantity_missed'] > 0) {
                        $ordered_products = $this->model_sale_order->getRealOrderProductStoreId($order_id, $updateProduct['product_id']);
                        if ($ordered_products == NULL) {
                            $ordered_products = $this->model_sale_order->getOrderProductStoreId($order_id, $updateProduct['product_id']);
                        }

                        $order_missing_product_info = $this->model_sale_order->addOrderProductToMissingProducts($ordered_products['order_product_id'], $updateProduct['quantity_missed'], $ordered_products['name'], $ordered_products['unit'], $ordered_products['product_note'], $ordered_products['model'], $order_id, 1);
                    }

                    if ($updateProduct['quantity_missed'] != NULL && $updateProduct['quantity_missed'] <= 0) {
                        $ordered_products = $this->model_sale_order->getRealOrderProductStoreId($order_id, $updateProduct['product_id']);
                        if ($ordered_products == NULL) {
                            $ordered_products = $this->model_sale_order->getOrderProductStoreId($order_id, $updateProduct['product_id']);
                        }

                        $order_missing_product_info = $this->model_sale_order->deleteOrderProductToMissingProducts($ordered_products['order_product_id'], $updateProduct['quantity_missed'], $ordered_products['name'], $ordered_products['unit'], $ordered_products['product_note'], $ordered_products['model'], $order_id);
                    }
                }

                $subTotal = $sumTotal;

                //$log->write("tax_total start ");
                $tax_total = $this->model_tool_image->getTaxTotal($tempProds, $store_id, $pricing_category, $custom_price);

                //echo "<pre>";print_r($tax_total);die;

                /* $log->write("tax_total");
                  $log->write($tax_total); */

                if (count($tax_total) > 0) {
                    foreach ($tax_total as $x => $tmpV) {
                        array_push($datas['totals'], $tmpV);
                    }
                }

                //unset totals coming from web
                if (isset($datas['totals']['tax'])) {
                    unset($datas['totals']['tax']);
                }

                if (!isset($datas['totals'])) {
                    $datas['totals'] = [];
                }

                $log->write('datastotals');
                $log->write($datas['totals']);

                //echo "<pre>";print_r($datas['totals']);die;
                //$log->write($datas['totals']);
                //die;
                //saving order total below

                $this->model_sale_order->deleteOrderTotal($order_id);

                foreach ($datas['totals'] as $p_id_code => $tot) {
                    $sumTotal += $tot['value'];
                }

                $orderTotal = $sumTotal;

                //get shipping method and get price
                //echo "<pre>";print_r($order_info);die;
                $tmp = explode('.', $order_info['shipping_code']);

                $shipping_price = [];

                if ('normal' == $tmp[0] || 'store_delivery' == $tmp[0]) {
                    $p = $tmp[0] . '_free_delivery_amount';
                    $free_delivery_amount = $this->config->get($p);

                    if (isset($store_id) && $store_id) {
                        $store_info = $this->model_tool_image->getStore($store_id);

                        if ($store_info) {
                            $free_delivery_amount = $store_info['min_order_cod'];
                        }
                    }

                    //echo "<pre>";print_r($free_delivery_amount);print_r($old_sub_total);die;
                    $log->write($old_sub_total);
                    $log->write($free_delivery_amount);
                    $log->write($subTotal);

                    if ($subTotal < $free_delivery_amount) {
                        $log->write('shipping_price if');

                        $this->load->model('shipping/' . $tmp[0]);
                        $shipping_price = $this->{'model_shipping_' . $tmp[0]}->getPrice($store_id, $subTotal, $subTotal, $order_info['latitude'], $order_info['longitude'], $shipping_city_id);

                        $log->write($shipping_price);

                        $value_coming_tmp = 0;

                        if ((isset($datas['totals']) && array_key_exists('shipping', $datas['totals']))) {
                            $value_coming_tmp = $datas['totals']['shipping']['value'];

                            $datas['totals']['shipping']['value_coming'] = $value_coming_tmp;
                        }
                    } else {
                        $value_coming_tmp = 0;

                        if ((isset($datas['totals']) && array_key_exists('shipping', $datas['totals']))) {
                            $value_coming_tmp = $datas['totals']['shipping']['value'];
                        }

                        $datas['totals']['shipping'] = [];
                        $datas['totals']['shipping']['code'] = 'shipping';
                        $datas['totals']['shipping']['title'] = 'Shipping charge';
                        $datas['totals']['shipping']['value'] = $value_coming_tmp;
                        $datas['totals']['shipping']['actual_value'] = $value_coming_tmp;

                        $datas['totals']['shipping']['value_coming'] = $value_coming_tmp;
                    }
                }

                $p = 2;

                //$log->write("datas_totals");

                /* if(count($datas['totals']) < 1 || (isset($datas['totals']) && !array_key_exists("shipping",$datas['totals'])) ) {

                  $datas['totals']['shipping'] =[];
                  $datas['totals']['shipping']['code'] = 'shipping';
                  $datas['totals']['shipping']['title'] = 'Shipping charge';
                  $datas['totals']['shipping']['value'] = 0;
                  $datas['totals']['shipping']['actual_value'] = 0;

                  } */

                $log->write('datas_totals');

                $log->write($datas['totals']);

                $log->write($orderTotal);
                $log->write($shipping_price);

                $wallet_amount_positive = 0;

                foreach ($datas['totals'] as $p_id_code => $tot) {
                    // echo "<pre>";print_r($tot);die;

                    /* $log->write("updatetotals");
                      $log->write($tot); */
                    $tot['sort'] = $p;
                    $this->model_sale_order->insertOrderTotal($order_id, $tot, $shipping_price);
                    if ($tot['code'] == "credit") {
                        $wallet_amount_positive = abs($tot['value']);
                    }
                    /* if ('shipping' == $tot['code']) {
                      if (count($shipping_price) > 0 && isset($shipping_price['cost']) && isset($shipping_price['actual_cost'])) {
                      if ((array_key_exists('value_coming', $tot))) {
                      $orderTotal -= $tot['value_coming'];
                      }

                      $orderTotal += $shipping_price['cost'];
                      } else {
                      //$orderTotal -= $tot['value'];

                      if ((array_key_exists('value_coming', $tot))) {
                      $orderTotal -= $tot['value_coming'];
                      }
                      }
                      } */

                    ++$p;
                }
                // if($wallet_amount_positive>0)
                // {
                // $orderTotal +=$wallet_amount_positive;
                // }
                $orderTotal = round($orderTotal, 2);
                $subTotal = round($subTotal, 2);

                $this->model_sale_order->insertOrderSubTotalAndTotal($order_id, $subTotal, $orderTotal, $p);
                $log->write($orderTotal);
                //die;
                // editDeliveryRequest
                $this->editDeliveryRequest($order_id);

                //$this->sendNewInvoice($order_id);
                // echo "<pre>";print_r($this->request->get['settle']);die;


                if ($this->request->get['settle']) {
                    //settle and  update
                    $log->write('if settle');
                    $customer_id = $this->request->get['customer_id'];
                    $final_amount = $orderTotal;

                    $log->write($final_amount);
                    $log->write($old_total);

                    if ($final_amount != $old_total) {

                        //update Payment Paid status and pending amount
                        $this->model_sale_order->updatePaymentStatus($order_id, $customer_id, $old_total, $final_amount);

                        //$iuguData = $this->refundAndChargeNewTotalStripe($order_id,$customer_id,$final_amount);
                    } else {
                        $log->write('same amount settle');
                    }

                    /* $iuguData = $this->model_sale_order->getOrderIuguAndTotal($order_id);

                      if($iuguData) {

                      $log->write("if iugu");

                      $description = 'On Order #'.$order_id;


                      if($this->request->get['charge']) {

                      //new invoice is charged

                      $userCharged = $this->chargeCustomer($customer_id,$description,$final_amount,$order_id);

                      if(isset($order_id) && isset($final_amount) && $userCharged) {

                      $this->model_sale_order->settle_payment($order_id, $final_amount);
                      }

                      } else {

                      //refund is done
                      $userCharged = $this->refundCustomer($customer_id,$description,$final_amount,$order_id);
                      }

                      $json['success'] = $this->language->get('text_settlement');

                      } else {
                      $log->write("else iugu");

                      } */
                } else {
                    //not settle only update so reset column settlement_amount
                    $this->model_sale_order->settle_payment($order_id, $orderTotal);
                }
                $filter['filter_order_id'] = $order_id;
                $products = $this->model_sale_order->getOrderedMissingProducts($filter);
                $log->write('MISSING PRODUCTS COUNT');
                $log->write(count($products));
                $log->write($order_info['delivery_timeslot']);
                $log->write('MISSING PRODUCTS COUNT');
                if (is_array($products) && count($products) > 0 /* && ($order_info['delivery_timeslot'] == '06:00am - 08:00am' || $order_info['delivery_timeslot'] == '08:00am - 10:00am' || $order_info['delivery_timeslot'] == '10:00am - 12:00am') */) {
                    try {
                        $this->load->controller('sale/order_product_missing/sendmailwithmissingproducts', $order_id);
                    } catch (exception $ex) {
                        $log = new Log('error.log');
                        $log->write('EDIT INVOICE EXCEPTION');
                        $log->write($ex);
                        $log->write('EDIT INVOICE EXCEPTION');
                    }
                }
            } else {
                $json['status'] = false;
            }

            $json = json_encode($json);

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'order_id' => $order_id,
            ];
            $log->write('user update_invoice');

            $this->model_user_user_activity->addActivity('update_invoice', $activity_data);

            $log->write('user update_invoice');
        } else {
            $json = $data_valid;
            $json = json_encode($json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($json);
    }

    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }

    public function getUser($id) {
        if ($id) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer`  WHERE customer_id ='" . $id . "'");

            return $query->row['fax'];
        }
    }

    protected function validate() {
        $this->load->language('sale/order');
        if (!$this->user->hasPermission('modify', 'sale/editinvoice')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;

        //        return true;
    }

    protected function validateform($products) {

        $i = 1;
        $data['error'] = false;
        $data['message'] = NULL;
        $data['status'] = true;
        foreach ($products as $form_products) {
            if (!array_key_exists('name', $form_products)) {
                $data['message'] = 'Product Name Should Not Be Empty In ' . $i . ' Row!';
                $data['error'] = true;
                $data['status'] = false;
            } elseif (!array_key_exists('unit', $form_products)) {
                $data['message'] = 'Product Unit Should Not Be Empty In ' . $i . ' Row!';
                $data['error'] = true;
                $data['status'] = false;
                $data['status'] = false;
            } elseif (array_key_exists('quantity_missed', $form_products) && $form_products['quantity_missed'] < 0) {
                $data['message'] = 'Missed Quantity Should Not Be Less Than Zero In ' . $i . ' Row!';
                $data['error'] = true;
                $data['status'] = false;
                $data['status'] = false;
            } elseif (array_key_exists('quantity', $form_products) && $form_products['quantity'] < 0) {
                $data['message'] = 'Ordered Quantity Should Not Be Less Than Zero In ' . $i . ' Row!';
                $data['error'] = true;
                $data['status'] = false;
                $data['status'] = false;
            } elseif (!array_key_exists('quantity', $form_products)) {
                $data['message'] = 'Product Quantity Should Not Be Empty In ' . $i . ' Row!';
                $data['error'] = true;
                $data['status'] = false;
            } elseif (!array_key_exists('price', $form_products)) {
                $data['message'] = 'Product Price Should Not Be Empty In ' . $i . ' Row!';
                $data['error'] = true;
                $data['status'] = false;
            }

            $log = new Log('error.log');
            $log->write('VALIDATE_FORM');
            $log->write($form_products);
            $log->write('VALIDATE_FORM');
            $i++;
        }
        return $data;
    }

    public function editDeliveryRequest($order_id) {
        $log = new Log('error.log');

        $log->write('inside editDeliveryRequest');
        $order_info = $this->getOrder($order_id);

        $this->load->model('sale/order');
        $this->load->model('account/order');

        if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
            $products = $this->model_account_order->getRealOrderProducts($order_id);
        } else {
            $products = $this->model_account_order->getOrderProducts($order_id);
        }

        $this->load->model('sale/order');

        $new_total = 0;

        $totals = $this->model_sale_order->getOrderTotals($order_id);

        foreach ($totals as $total) {
            if ('total' == $total['code']) {
                $new_total = $total['value'];
                break;
            }
        }

        $pay_diff = ($new_total - $order_info['total']);

        $total_type = 'green';

        if (!$this->isOnlinePayment($order_info['payment_code']) || $pay_diff > 0) {
            $total_type = 'red';
        }

        if ($this->isOnlinePayment($order_info['payment_code'])) {
            if ($pay_diff < 0) {
                $getPayment = 0;
            } else {
                $getPayment = $pay_diff;
            }
        } else {
            $getPayment = $new_total;
        }

        $log->write($products);

        $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

        //$deliveryAlreadyCreated = true;

        if ($order_info && $products && $deliveryAlreadyCreated) {
            $log->write('if');

            $data['products']['products'] = [];

            foreach ($products as $product) {
                $replacable = 'no';

                if ('replacable' == $product['product_type']) {
                    $replacable = 'yes';
                }

                $this->load->model('tool/image');

                if (file_exists(DIR_IMAGE . $product['image'])) {
                    $image = HTTP_IMAGE . $product['image'];
                } else {
                    $image = HTTP_IMAGE . 'placeholder.png';
                }

                $var = [
                    'product_name' => htmlspecialchars_decode($product['name']),
                    'product_unit' => $product['unit'],
                    'product_quantity' => $product['quantity'],
                    'product_image' => $image, //"http:\/\/\/product-images\/camera.jpg",
                    'product_price' => $product['price'], //"1500.00",//product price unit price?? or total
                    'product_replaceable' => $replacable, //"no"
                ];

                array_push($data['products']['products'], $var);
            }

            $log->write($data['products']['products']);

            $data['body'] = [
                'manifest_id' => $deliveryAlreadyCreated, //order_id,
                'total_price' => (int) round($new_total),
                'get_amount' => (int) round($getPayment),
                'total_type' => $total_type,
                'manifest_data' => json_encode($data['products']),
            ];

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            $log->write('token');
            $log->write($response);
            if ($response['status']) {
                $data['tokens'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/editDelivery', $data);
                $log->write('reeponse');
                $log->write($res);
            }
        }
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query('SELECT *, (SELECT os.name FROM `' . DB_PREFIX . 'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `' . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

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
                'invoice_sufix' => $order_query->row['invoice_sufix'],
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
                    /* 'date_modified' => $order_query->row['date_modified'],
                      'date_added' => $order_query->row['date_added'] */
            ];
        } else {
            return false;
        }
    }

    public function isOnlinePayment($payment_code) {
        $refundToCustomerWallet = false;

        $allowedPaymentMethods = $this->config->get('config_payment_methods_status');

        if (is_array($allowedPaymentMethods) && count($allowedPaymentMethods) > 0) {
            foreach ($allowedPaymentMethods as $method) {
                if ($payment_code == $method) {
                    $refundToCustomerWallet = true;
                }
            }
        }

        return $refundToCustomerWallet;
    }

}
