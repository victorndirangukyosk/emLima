<?php

class ControllerCommonScheduler extends Controller
{
    private $error = [];

    public function consolidatedOrderSheet()
    {
        // $deliveryDate =   date("Y-m-d");// date("Y-m-d",strtotime("-1 days"));//$this->request->get['filter_delivery_date'];
        $deliveryDate =   date("Y-m-d",strtotime("1 days"));// as eat at 11:30 means , next day orders need to be displayed

        $filter_data = [
            'filter_delivery_date' => $deliveryDate,
        ];        
        $this->load->model('sale/order');
        // $results = $this->model_sale_order->getOrders($filter_data);
        $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
        $data = [];
        $unconsolidatedProducts = [];

        foreach ($results as $index => $order) {
            $data['orders'][$index] = $order;
            $orderProducts = $this->model_sale_order->getOrderAndRealOrderProducts($data['orders'][$index]['order_id']);
            $data['orders'][$index]['products'] = $orderProducts;

            foreach ($orderProducts as $product) {
                $unconsolidatedProducts[] = [
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'quantity' => $product['quantity'],
                    'note' => $product['product_note'],
                    'produce_type' => $product['produce_type'],
                ];
            }
        }

        $consolidatedProducts = [];

        foreach ($unconsolidatedProducts as $product) {
            $productName = $product['name'];
            $productUnit = $product['unit'];
            $productQuantity = $product['quantity'];
            $productNote = $product['product_note'];
            $produceType = $product['produce_type'];

            $consolidatedProductNames = array_column($consolidatedProducts, 'name');
            if (false !== array_search($productName, $consolidatedProductNames)) {
                $indexes = array_keys($consolidatedProductNames, $productName);

                $foundExistingProductWithSimilarUnit = false;
                foreach ($indexes as $index) {
                    if ($productUnit == $consolidatedProducts[$index]['unit']) {
                        if ($consolidatedProducts[$index]['produce_type']) {
                            $produceType = $consolidatedProducts[$index]['produce_type'].' / '.$produceType.' ';
                        }

                        $consolidatedProducts[$index]['quantity'] += $productQuantity;
                        $consolidatedProducts[$index]['produce_type'] = $produceType;
                        $foundExistingProductWithSimilarUnit = true;
                        break;
                    }
                }

                if (!$foundExistingProductWithSimilarUnit) {
                    $consolidatedProducts[] = [
                        'name' => $productName,
                        'unit' => $productUnit,
                        'quantity' => $productQuantity,
                        'note' => $productNote,
                        'produce_type' => $produceType,
                    ];
                }
            } else {
                $consolidatedProducts[] = [
                    'name' => $productName,
                    'unit' => $productUnit,
                    'quantity' => $productQuantity,
                    'note' => $productNote,
                    'produce_type' => $produceType,
                ];
            }
        }
        //echo "<pre>";print_r($consolidatedProducts);die;

        $data['products'] = $consolidatedProducts;
        //   echo "<pre>";print_r($data);die;
        if($data['products']!=null)
        {
            // echo "<pre>";print_r($data['products']);die;
        $this->load->model('report/excel');
       $file= $this->model_report_excel->mail_consolidated_order_sheet_excel($data);
       
        }
    //     else{
    //    echo "<pre>";print_r(1);die;

    //     }
    //    echo "<pre>";print_r($file);die;
    }
    public function stockout()
    {
        //echo "<pre>";print_r($this->request->get);die;
        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }

        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = '';
        }

        // echo date('Y-m-01',strtotime('last month')) . '<br/>';
        // echo date('Y-m-t',strtotime('last month')) . '<br/>';exit;

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start =date('Y-m-01',strtotime('last month'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-t',strtotime('last month'))  ;

        }

        $data['filter_store'] = $filter_store_id;
        $data['filter_store_name'] = $filter_store;

        $data['filter_store_id'] = $filter_store_id;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_order_status_id'] = $filter_order_status_id;

        // echo "<pre>";print_r($data);die;

        $this->load->model('report/excel');
        $this->model_report_excel->mail_download_saleorderproductmissing($data);
    }

    public function UpdateOrderProcessing()
    {
        $log = new Log('error.log');
        try{
        // $deliveryDate =   date("Y-m-d");// date("Y-m-d",strtotime("-1 days"));//$this->request->get['filter_delivery_date'];
        $deliveryDate =   date("Y-m-d",strtotime("1 days"));// as eat at 10:45 means , next day orders need to be displayed
        $log->write('UpdateOrderProcessing -'.$deliveryDate);
       
        $this->load->model('scheduler/dbupdates');
        $result=$this->model_scheduler_dbupdates->UpdateOrderProcessing($deliveryDate);   
        // echo "<pre>";print_r($result);die; 
        $log->write('UpdateOrderProcessing-result -'.$result);  
        echo "updated successfully";       
        }
        catch(exception $ex)
        {
            $log->write('UpdateOrderProcessing -'.$ex);
        }     
       
     
    }




    public function checkIssueStatus()
    { 
            //check issue status and send notification to higher authority
            $log = new Log('error.log');

            try{
                $Issues_currentDateTime =   date("Y-m-d  H:i:s");
                $max_Issues_currentDateTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($Issues_currentDateTime)));
                $log->write('Issue Status checking -'.$Issues_currentDateTime);
               
            $this->load->model('sale/customer_feedback');
            $result_open=$this->model_sale_customer_feedback->GetNonProcessedIssues($Issues_currentDateTime,$max_Issues_currentDateTime,'Open');   
            $result_Attending=$this->model_sale_customer_feedback->GetNonProcessedIssues($Issues_currentDateTime,$max_Issues_currentDateTime,'Attending');   
            if($result_open!=null && $result_Attending!=null)
            $result=array_merge($result_open, $result_Attending);
            else if($result_open!=null)
            $result=$result_open;
            else
            $result=$result_Attending;
            
            // echo "<pre>";print_r($result);die;  
                
               #region mail sending 
               foreach($result as $feedback)
               {
               
               $maildata['customer_name']=$feedback['name'];
               $maildata['email']=$feedback['email'];
               $maildata['mobile']=$feedback['telephone'];
               $maildata['feedback_type']= ($feedback['feedback_type'] =="s"? "Suggestions" : ($feedback['feedback_type'] =="p"? "Issue"." - ".$feedback['issue_type'] :"Happy"));
               $maildata['description']=$feedback['comments'];
               if($feedback['status'] =='Open')
               {
               $maildata['issue_status']= "Open";
               }
               else if($feedback['status'] =='Attending')
               {
               $maildata['issue_status']="Attending. It is Accepted by ".$feedback['AcceptedBy'];
               }
               else{
                $maildata['issue_status']="NA";
               }
  
               $subject = $this->emailtemplate->getSubject('Feedback', 'Feedback_2', $maildata);
               $message = $this->emailtemplate->getMessage('Feedback', 'Feedback_2', $maildata);
            //    echo "<pre>";print_r($maildata);die;  
   
               // $subject = "Consolidated Order Sheet";                 
               // $message = "Please find the attachment.  <br>";
               // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
               $this->load->model('setting/setting');
               $email = $this->model_setting_setting->getEmailSetting('issue');
   
            //    if (strpos($email, "@") == false) {//if mail Id not set in define.php
            //        $email = "sridivya.talluri@technobraingroup.com";
            //    }
               // $bccemail = "sridivya.talluri@technobraingroup.com";
               //   echo "<pre>";print_r($email);die;
               $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
               $mail = new Mail($this->config->get('config_mail'));
               $mail->setTo($email);
               $mail->setBCC($bccemail);
               $mail->setFrom($this->config->get('config_from_email'));
               $mail->setSender($this->config->get('config_name'));
               $mail->setSubject($subject);
               $mail->setHTML($message);
               $mail->addAttachment($filepath);
               $mail->send();
            }
               #endregion
               echo "Issue status checked ";
            }
            catch(exception $ex)
            {
                $log->write('Issue Status Mail -'.$ex);
            }     
           
    }



    public function customerStatement()
    {
         
        $dt = $this->request->get['dt'];

        if($dt=='' || $dt==null)
        {
        $dt = strtotime(date("Y-m-d"));
        }
        else
        {
        $dt =strtotime(($dt));
        }

        echo "<pre>";print_r($dt); 

        //   echo 'First day : '. date("Y-m-01", strtotime($dt)).' - Last day : '. date("Y-m-t", strtotime($dt)); 
        // echo "<pre>";print_r($this->request->get['pdf']);die;
        $pdf=$this->request->get['pdf'];

        // $filter_date_end =   date("Y-m-t", strtotime($dt));
        // $filter_date_start =   date("Y-m-01", strtotime($dt));

        // $filter_data = [
        //     'filter_date_start' => $filter_date_start,
        //     'filter_date_end' => $filter_date_end,
        // ];        
         
          $this->load->model('report/excel');
          $this->model_report_excel->mail_download_customer_statement_excel($filter_data,$dt,$pdf);
   
        }




        public function testpdf() {
            $this->load->language('sale/order');
    
            $data['title'] = $this->language->get('text_invoice');
    
            if ($this->request->server['HTTPS']) {
                $data['base'] = HTTPS_SERVER;
            } else {
                $data['base'] = HTTP_SERVER;
            }
    
            $data['direction'] = $this->language->get('direction');
            $data['lang'] = $this->language->get('code');
    
            $data['text_date_delivered'] = $this->language->get('text_date_delivered');
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
            $data['text_po_no'] = $this->language->get('text_po_no');
            $data['text_ship_to'] = $this->language->get('text_ship_to');
            $data['text_payment_method'] = $this->language->get('text_payment_method');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
    
            $data['column_product'] = $this->language->get('column_product');
            $data['column_produce_type'] = $this->language->get('column_produce_type');
    
            $data['column_model'] = $this->language->get('column_model');
            $data['column_unit'] = $this->language->get('column_unit') . ' Ordered';
            $data['column_quantity'] = $this->language->get('column_quantity') . ' Ordered';
            $data['column_unit_change'] = $this->language->get('column_unit') . ' Change';
            $data['column_quantity_change'] = $this->language->get('column_quantity') . ' Change';
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
                    $this->load->model('drivers/drivers');
                    $driver_info = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
                    $driver_name = NULL;
                    $driver_phone = NULL;
                    if ($driver_info) {
                        $driver_name = $driver_info['firstname'] . ' ' . $driver_info['lastname'];
                        $driver_phone = $driver_info['telephone'];
                    }
                    $data['driver_name'] = $driver_name;
                    $data['driver_phone'] = $driver_phone;
    
                    $this->load->model('executives/executives');
                    $executive_info = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
                    $executive_name = NULL;
                    $executive_phone = NULL;
                    if ($executive_info) {
                        $executive_name = $executive_info['firstname'] . ' ' . $executive_info['lastname'];
                        $executive_phone = $executive_info['telephone'];
                    }
                    $data['delivery_executive_name'] = $executive_name;
                    $data['delivery_executive_phone'] = $executive_phone;
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
                        $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'] . $order_info['invoice_sufix'];
                    } else {
                        $invoice_no = '';
                    }
    
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
                            'product_id' => $product['product_id'],
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
                            'amount_in_words' => ucwords($this->translateAmountToWords(floor(($total['value'] * 100) / 100))) . ' Kenyan Shillings',
                        ];
                    }
    
                    $this->load->model('sale/customer');
                    $order_customer_detials = $this->model_sale_customer->getCustomer($order_info['customer_id']);
                    $order_customer_first_last_name = NULL;
                    $company_name = NULL;
                    if ($order_customer_detials != NULL && is_array($order_customer_detials)) {
                        $order_customer_first_last_name = $order_customer_detials['firstname'] . ' ' . $order_customer_detials['lastname'];
                        $company_name = $order_customer_detials['company_name'];
                    }
    
                    $this->load->model('drivers/drivers');
                    $driver_info = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
                    $driver_name = NULL;
                    $driver_phone = NULL;
                    if ($driver_info) {
                        $driver_name = $driver_info['firstname'] . ' ' . $driver_info['lastname'];
                        $driver_phone = $driver_info['telephone'];
                    }
                    $data['driver_name'] = $driver_name;
                    $data['driver_phone'] = $driver_phone;
    
                    $delivery_executive_info = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
                    $delivery_executive_name = NULL;
                    $delivery_executive_phone = NULL;
                    if ($delivery_executive_info) {
                        $delivery_executive_name = $delivery_executive_info['firstname'] . ' ' . $delivery_executive_info['lastname'];
                        $delivery_executive_phone = $delivery_executive_info['telephone'];
                    }
                    $data['delivery_executive_name'] = $delivery_executive_name;
                    $data['delivery_executive_phone'] = $delivery_executive_phone;
    
                    $data['orders'][] = [
                        'order_id' => $order_id,
                        'invoice_no' => $invoice_no,
                        'date_added' => date($this->language->get('datetime_format'), strtotime($order_info['date_added'])),
                        'delivery_date' => date($this->language->get('date_format_short'), strtotime($order_info['delivery_date'])),
                        'delivery_timeslot' => $order_info['delivery_timeslot'],
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
                        'shipping_flat_number' => $order_info['shipping_flat_number'],
                        'shipping_contact_no' => ($order_info['shipping_contact_no']) ? $order_info['shipping_contact_no'] : $order_info['telephone'],
                        /* 'shipping_name' => ($order_info['shipping_name']) ? $order_info['shipping_name'] : $order_info['firstname'] . ' ' . $order_info['lastname'], */
                        'shipping_name' => $order_customer_first_last_name == NULL ? $order_info['firstname'] . ' ' . $order_info['lastname'] : $order_customer_first_last_name,
                        'customer_company_name' => $company_name == NULL ? $order_info['customer_company_name'] : $company_name,
                        'shipping_method' => $order_info['shipping_method'],
                        'po_number' => $order_info['po_number'],
                        'payment_method' => $order_info['payment_method'],
                        'products' => $product_data,
                        'totals' => $total_data,
                        'comment' => nl2br($order_info['comment']),
                        'shipping_name_original' => $order_info['shipping_name'],
                        'driver_name' => $driver_name,
                        'driver_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $driver_phone,
                        'delivery_executive_name' => $delivery_executive_name,
                        'delivery_executive_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $delivery_executive_phone
                    ];
                }
            }
    
            // echo "<pre>";print_r($data);die;
            try {
                require_once DIR_ROOT . '/vendor/autoload.php';
                 
                    $pdf = new \mikehaertl\wkhtmlto\Pdf;
                    $template = $this->load->view('sale/order_invoice_pdf.tpl', $data['orders'][0]);
                    $pdf->addPage($template);
                    if (!$pdf->send("KwikBasket Invoice #" . $data['orders'][0]['order_id'] . ".pdf")) {
                        $error = $pdf->getError();
                        echo $error;
                        die;
                    }
                
            } catch (Exception $e) {
                echo $e->getMessage();
            }
    
        }
    
}
