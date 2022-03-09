<?php


require_once DIR_ROOT . '/vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf; 

class ControllerCommonScheduler extends Controller {

    private $error = [];

    public function consolidatedOrderSheet() { 
        // $deliveryDate =   date("Y-m-d");// date("Y-m-d",strtotime("-1 days"));//$this->request->get['filter_delivery_date'];
        $deliveryDate = date("Y-m-d", strtotime("1 days")); // as eat at 11:30 means , next day orders need to be displayed

        $filter_data = [
            'filter_delivery_date' => $deliveryDate,
        ];
        $this->load->model('sale/order');
        // $results = $this->model_sale_order->getOrders($filter_data);
        // $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
        $results = $this->model_sale_order->getOrderswithProcessing($filter_data);
        $data = [];
        $unconsolidatedProducts = [];

        foreach ($results as $index => $order) { 
            $data['orders'][$index] = $order;
            $orderProducts = $this->model_sale_order->getOrderAndRealOrderProducts($data['orders'][$index]['order_id']);
            $data['orders'][$index]['products'] = $orderProducts;

            foreach ($orderProducts as $product) {
                if($product['general_product_id']==null || $product['general_product_id']==0 || $product['general_product_id']=='' )
                {
                $product_category=$this->model_sale_order->getProductCategoryByProductID($product['product_id']);
                }
                else{
                    $product_category=$this->model_sale_order->getProductCategoryByGeneralProductID($product['general_product_id']);

                }
                if($product_category!=null)
                {
                    $unconsolidatedProducts[] = [
                        'name' => $product['name'],
                        'unit' => $product['unit'],
                        'quantity' => $product['quantity'],
                        'note' => $product['product_note'],
                        'produce_type' => $product['produce_type'],
                        'product_category' => $product_category['category'],
                        'product_category_id' => $product_category['category_id'],
                    ];
                }
                else
                {
                    $unconsolidatedProducts[] = [
                        'name' => $product['name'],
                        'unit' => $product['unit'],
                        'quantity' => $product['quantity'],
                        'note' => $product['product_note'],
                        'produce_type' => $product['produce_type'],
                        'product_category' => '',
                        'product_category_id' => 0,
                    ];

                 }
            }
        }

        $consolidatedProducts = [];

        foreach ($unconsolidatedProducts as $product) {
            $productName = $product['name'];
            $productUnit = $product['unit'];
            $productQuantity = $product['quantity'];
            $productNote = $product['product_note'];
            $produceType = $product['produce_type'];
            $product_category = $product['product_category'];
            $product_category_id = $product['product_category_id'];

            $consolidatedProductNames = array_column($consolidatedProducts, 'name');
            if (false !== array_search($productName, $consolidatedProductNames)) {
                $indexes = array_keys($consolidatedProductNames, $productName);

                $foundExistingProductWithSimilarUnit = false;
                foreach ($indexes as $index) {
                    if ($productUnit == $consolidatedProducts[$index]['unit']) {
                        if ($consolidatedProducts[$index]['produce_type']) {
                            $produceType = $consolidatedProducts[$index]['produce_type'] . ' / ' . $produceType . ' ';
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
                        'product_category' => $product_category,
                    'product_category_id' => $product_category_id,

                        
                    ];
                }
            } else {
                $consolidatedProducts[] = [
                    'name' => $productName,
                    'unit' => $productUnit,
                    'quantity' => $productQuantity,
                    'note' => $productNote,
                    'produce_type' => $produceType,
                    'product_category' => $product_category,
                    'product_category_id' => $product_category_id,


                ];
            }
        }

        $productCat = array_column($consolidatedProducts, 'product_category_id');
        array_multisort(  $productCat,SORT_ASC,$consolidatedProducts);

        // echo "<pre>";print_r($consolidatedProducts);die;

        $data['products'] = $consolidatedProducts;
        //   echo "<pre>";print_r($data);die;
        if ($data['products'] != null) {
            // echo "<pre>";print_r($data['products']);die;
            $this->load->model('report/excel');
            $file = $this->model_report_excel->mail_consolidated_order_sheet_excel($data);
        }
        //     else{
        //    echo "<pre>";print_r(1);die;
        //     }
        //    echo "<pre>";print_r($file);die;
    }

    public function stockout() {
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
            $filter_date_start = date('Y-m-01', strtotime('last month'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-t', strtotime('last month'));
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

    public function UpdateOrderProcessing() {
        $log = new Log('error.log');
        try {
            // $deliveryDate =   date("Y-m-d");// date("Y-m-d",strtotime("-1 days"));//$this->request->get['filter_delivery_date'];
            $deliveryDate = date("Y-m-d", strtotime("1 days")); // as eat at 10:45 means , next day orders need to be displayed
            $log->write('UpdateOrderProcessing -' . $deliveryDate);

            $this->load->model('scheduler/dbupdates');
            $result = $this->model_scheduler_dbupdates->UpdateOrderProcessing($deliveryDate);
            // echo "<pre>";print_r($result);die; 
            $log->write('UpdateOrderProcessing-result -' . $result);
            echo "updated successfully";
        } catch (exception $ex) {
            $log->write('UpdateOrderProcessing -' . $ex);
        }
    }

    public function checkIssueStatus() {
        //check issue status and send notification to higher authority
        $log = new Log('error.log');

        try {
            $Issues_currentDateTime = date("Y-m-d  H:i:s");
            $max_Issues_currentDateTime = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($Issues_currentDateTime)));
            $log->write('Issue Status checking -' . $Issues_currentDateTime);

            $this->load->model('sale/customer_feedback');
            $result_open = $this->model_sale_customer_feedback->GetNonProcessedIssues($Issues_currentDateTime, $max_Issues_currentDateTime, 'Open');
            $result_Attending = $this->model_sale_customer_feedback->GetNonProcessedIssues($Issues_currentDateTime, $max_Issues_currentDateTime, 'Attending');
            if ($result_open != null && $result_Attending != null)
                $result = array_merge($result_open, $result_Attending);
            else if ($result_open != null)
                $result = $result_open;
            else
                $result = $result_Attending;

            // echo "<pre>";print_r($result);die;  
            #region mail sending 
            foreach ($result as $feedback) {

                $maildata['customer_name'] = $feedback['name'];
                $maildata['email'] = $feedback['email'];
                $maildata['mobile'] = $feedback['telephone'];
                $maildata['feedback_type'] = ($feedback['feedback_type'] == "s" ? "Suggestions" : ($feedback['feedback_type'] == "p" ? "Issue" . " - " . $feedback['issue_type'] : "Happy"));
                $maildata['description'] = $feedback['comments'];
                if ($feedback['status'] == 'Open') {
                    $maildata['issue_status'] = "Open";
                } else if ($feedback['status'] == 'Attending') {
                    $maildata['issue_status'] = "Attending. It is Accepted by " . $feedback['AcceptedBy'];
                } else {
                    $maildata['issue_status'] = "NA";
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
        } catch (exception $ex) {
            $log->write('Issue Status Mail -' . $ex);
        }
    }

    public function customerStatement() {

        $dt = $this->request->get['dt'];

        if ($dt == '' || $dt == null) {
            $dt = strtotime(date("Y-m-d"));
        } else {
            $dt = strtotime(($dt));
        }

        echo "<pre>";
        print_r($dt);

        //   echo 'First day : '. date("Y-m-01", strtotime($dt)).' - Last day : '. date("Y-m-t", strtotime($dt)); 
        // echo "<pre>";print_r($this->request->get['pdf']);die;
        $pdf = $this->request->get['pdf'];

        // $filter_date_end =   date("Y-m-t", strtotime($dt));
        // $filter_date_start =   date("Y-m-01", strtotime($dt));
        $filter_data = [
            // 'filter_date_start' => $filter_date_start,
            // 'filter_date_end' => $filter_date_end,
        ];        

        $this->load->model('report/excel');
        $this->model_report_excel->mail_download_customer_statement_excel($filter_data, $dt, $pdf);
    }

    public function testpdf() {

        $dt = $this->request->get['dt'];

        if ($dt == '' || $dt == null) {
            $dt = strtotime(date("Y-m-d"));
        } else {
            $dt = strtotime(($dt));
        }
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }


        $log = new Log('error.log');
        // echo "<pre>";print_r(date('l', $dt)); 
        // echo "<pre>";print_r(date('d', $dt)); 
        if (date('l', $dt) != 'Sunday' && date('d', $dt) != '01' && date('d', $dt) != '16') {//weekly
            echo "<pre>";
            print_r('No execution today');
            return;
        } else {
            if (date('d', $dt) == '01') {
                // echo "<pre>";print_r($dt); 

                $dtp = date("Y-m-d", strtotime("-1 days", $dt));
                // echo "<pre>";print_r($dtp); 
                $data['filter_date_end'] = date("Y-m-t", strtotime($dtp));
                $data['filter_date_start'] = date("Y-m-01", strtotime($dtp));
            } else if (date('l', $dt) == 'Sunday' && date('d', $dt) != '01' && date('d', $dt) != '16') {//weekly
                $data['filter_date_start'] = date("Y-m-d", strtotime("-1 days", $dt));
                $data['filter_date_end'] = date("Y-m-d", strtotime("-7 days", $dt));
            } else if (date('d', $dt) == '16') {//incase of 15 days or week
                $data['filter_date_end'] = date("Y-m-t", strtotime($dt));
                $data['filter_date_start'] = date("Y-m-01", strtotime($dt));
            } else {
                echo "<pre>";
                print_r('Date Varient missed');
                $log->write("Date Varient missed- Automatic statement error");

                return;
            }
        }
        // echo "<pre>";print_r($data);


        $this->load->model('report/customer');
        $customerswithOrders = $this->model_report_customer->getCustomerWithOrders($data);

        //Firstly get all customers
        // echo "<pre>";print_r($customerswithOrders);
        // echo "<pre>";print_r("$customerswithOrders");
        // $log = new Log('error.log');
        foreach ($customerswithOrders as $validcust) {
            $data['filter_customer'] = $validcust['name'];
            $data['filter_customer_email'] = $validcust['email'];
            $data['filter_customer_id'] = $validcust['customer_id'];

            // $data['filter_customer']='Product Team Kdsfsdf';
            // $data['filter_customer_id']=273;
            // $data['filter_customer_email']='stalluri89@gmail.com';
            // $results = $this->model_report_customer->getValidCustomerOrders($data);
            $results = $this->model_report_customer->getValidCustomerOrdersByDates($data, $dt);
            if ($results != null) {
                $Amount_ordervalue_grand=0;
                $Amount_paid_grand=0;
                $Amount_pending_grand=0;
                $this->load->model('sale/order');
                $data['customers'] = [];

                // echo "<pre>";print_r($results);die;
                foreach ($results as $result) {
                    $products_qty = 0;
                    if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                        $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
                    } else {
                        $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
                    }
                    $sub_total = 0;
                    $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
                    // echo "<pre>";print_r($totals);die;
                    // $data['customers']= (array) null;
                    foreach ($totals as $total) {
                        if ('total' == $total['code']) {
                            $sub_total = $total['value'];
                            break;
                        }
                    }
                    if ($result['paid'] == 'N') {
                        //check transaction Id Exists are not// if exists, it is paid order,
                        $transcation_id = $this->model_sale_order->getOrderTransactionId($result['order_id']);
                        if (!empty($transcation_id)) {
                            $result['paid'] = 'Paid';
                            $result['amountpaid'] = $sub_total;
                            $result['pendingamount'] = $sub_total - $result['amountpaid'];
                        } else {
                            $result['paid'] = 'Pending';
                            $result['amountpaid'] = 0;
                            $result['pendingamount'] = $sub_total - $result['amountpaid'];
                        }
                    } else if ($result['paid'] == 'P') {
                        // $result['paid']=$result['paid'].'(Amount Paid :'.$result['amount_partialy_paid'] .')';
                        $result['paid'] = 'Few Amount Paid';
                        $result['amountpaid'] = $result['amount_partialy_paid'];
                        $result['pendingamount'] = $sub_total - $result['amountpaid'];
                    } else if ($result['paid'] == 'Y') {
                        // $result['paid']=$result['paid'].'(Amount Paid :'.$result['amount_partialy_paid'] .')';
                        $result['paid'] = 'Paid';
                        $result['amountpaid'] = $sub_total;
                        $result['pendingamount'] = $sub_total - $result['amountpaid'];
                    }

                    $data['customers'][] = [
                        'company' => $result['company'],
                        'customer' => $result['customer'],
                        'email' => $result['email'],
                        'customer_group' => $result['customer_group'],
                        'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                        'order_id' => $result['order_id'],
                        'products' => $result['products'],
                        'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'editedproducts' => (int) $products_qty,
                        'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                        //'subtotal'     => $this->currency->format($sub_total),
                        'subtotalvalue' => $sub_total,
                        'po_number' => $result['po_number'],
                        'subtotal' => str_replace('KES', ' ', $this->currency->format($sub_total)),
                        'SAP_customer_no' => $result['SAP_customer_no'],
                        'paid' => $result['paid'],
                        'amountpaid' => number_format($result['amountpaid'], 2),
                        'pendingamount' => number_format($result['pendingamount'], 2),
                        'pendingamountvalue' => ($result['pendingamount']),
                    ];

                    $Amount_ordervalue_grand=$Amount_ordervalue_grand+$sub_total;
                    $Amount_paid_grand=$Amount_paid_grand+$result['paid'];
                    $Amount_pending_grand=$Amount_pending_grand+$result['pendingamount'];
                }

                if($data['customers']!=null)
                {
                    $data['customers'][0]['Amount_ordervalue_grand']=$Amount_ordervalue_grand;
                    $data['customers'][0]['Amount_paid_grand']=$Amount_paid_grand;
                    $data['customers'][0]['Amount_pending_grand']=$Amount_pending_grand;

                }

                //   echo "<pre>";print_r($data);die;
                 $data['token'] = $this->session->data['token'];
                //$this->response->redirect($this->url->link('report/customer_statement_pdf.tpl', $data));
                // $this->response->setOutput($this->load->view('report/customer_statement_pdf.tpl', $data));
                //  return;
            }
        }

        // echo "<pre>";print_r($data);die;
        try {
            $pdf = new Pdf([
                'commandOptions' => array(
                    'useExec' => true, // Can help on Windows systems
                    'procEnv' => array(
                        // Check the output of 'locale -a' on your system to find supported languages
                        'LANG' => 'en_US.utf-8',
                    ),
                ),
            ]);
            $template = $this->load->view('report/customer_statement_pdf.tpl', $data);
            $pageOptions = array(
                'javascript-delay' => 2000,
                'encoding' => 'UTF-8',
            );
            $pdf->addPage($template, $pageOptions);

            
            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp';
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list 
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file); // Delete the given file  
            }

            if (!$pdf->saveAs(DIR_UPLOAD . 'schedulertemp/' . "Customer_order_statement_" . $data['customers'][0]['customer'] . ".pdf")) {
                $errors = $pdf->getError();
                echo $errors;
                die;
            }
            if (!$pdf->send("Customer_order_statement_" . $data['customers'][0]['customer'] . ".pdf")) {
                $error = $pdf->getError();
                echo $error;
                die;
            }


            $filename = 'Customer_order_statement_' . $data['customers'][0]['customer'] . '.pdf';
            // echo "<pre>";print_r($filename);die;

 
            echo '$pdf->getError()';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function consolidatedSaleOrders() {
        // $deliveryDate =   date("Y-m-d");// date("Y-m-d",strtotime("-1 days"));//$this->request->get['filter_delivery_date'];
        $deliveryDate = date("Y-m-d", strtotime("0 days")); // as eat at 11:30 means , next day orders need to be displayed

        if (isset($this->request->get['sort'])) {
            $sort = 'status';
        } else {
            $sort = 'status';
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }
        $filter_data = [
            'filter_delivery_date' => $deliveryDate,
            'sort' => $sort,
            'order' => $order,
        ];
        $this->load->model('sale/order');
        $this->load->model('vendor/vendor');
        // $results = $this->model_sale_order->getOrders($filter_data);
        $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
        // echo "<pre>";print_r($results);die;      
        //   echo "<pre>";print_r($data);die;
        if ($results != null) {
            foreach ($results as $result) {
                $sub_total = 0;
                $total = 0;

                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
                $store_details = $this->model_vendor_vendor->getVendorByStoreId($result['store_id']);
                $vendor_details = $this->model_vendor_vendor->getVendorDetails($store_details['vendor_id']);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $sub_total = $total['value'];
                        // break;
                    }
                    if ('total' == $total['code']) {
                        $total = $total['value'];
                        // break;
                    }
                }

                // if ($this->user->isVendor()) {
                //     $result['customer'] = strtok($result['firstname'], ' ');
                // }

                if ($result['company_name']) {
                    $result['company_name'] = ' (' . $result['company_name'] . ')';
                } else {
                    // $result['company_name'] = "(NA)";
                }
                // $vendor_total = $this->currency->format(($result['total'] - ($result['total'] * $result['commission']) / 100), $this->config->get('config_currency'));
                $data['orders'][] = [
                    'order_id' => $result['order_id'],
                    'delivery_id' => $result['delivery_id'],
                    'order_prefix' => $vendor_details['orderprefix'] != '' ? $vendor_details['orderprefix'] . '-' : '',
                    'vendor_name' => $vendor_details['lastname'],
                    'customer' => $result['customer'],
                    'company_name' => $result['company_name'],
                    'status' => $result['status'],
                    'payment_method' => $result['payment_method'],
                    'shipping_method' => $result['shipping_method'],
                    'shipping_address' => $result['shipping_address'],
                    'delivery_date_value' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                    'delivery_date' => $result['delivery_date'],
                    'delivery_timeslot' => $result['delivery_timeslot'],
                    'store' => $result['store_name'],
                    'order_status_id' => $result['order_status_id'],
                    'order_status_color' => $result['color'],
                    'city' => $result['city'],
                    'vendor_total' => isset($vendor_total) ? $vendor_total : 0,
                    'total' => $this->currency->format($total, $result['currency_code'], $result['currency_value']),
                    'sub_total' => $this->currency->format($sub_total, $result['currency_code'], $result['currency_value']),
                    'sub_total_custom' => $sub_total, $result['currency_code'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                    'shipping_code' => $result['shipping_code'],
                    // 'view' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                    // 'invoice' => $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                    // 'order_spreadsheet' => $this->url->link('sale/order/orderCalculationSheet', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                    // 'shipping' => $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                    // 'edit' => $this->url->link('sale/order/EditInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                    // 'delete' => $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                    'po_number' => $result['po_number'],
                    'SAP_customer_no' => $result['SAP_customer_no'],
                    'SAP_doc_no' => $result['SAP_doc_no'],
                        // 'all_order_statuses' => $this->model_localisation_order_status->getOrderStatuses()
                ];
            }

            $this->load->model('report/excel');
            $file = $this->model_report_excel->mail_consolidated_sale_order_excel($data['orders']);
        }
    }




    public function consolidatedOrderSheet3PM() { 
         $deliveryDate = date("Y-m-d"); // current day delivery date
         $time = $this->request->get['time'];
         $dateAdded = date("Y-m-d", strtotime("-1 days"));
         $dateAdded = new DateTime($dateAdded);
         // Here 5 hours, 3 Minutes and 10 seconds is added--PT5H3M10S
            $dateAdded->add(new DateInterval('PT23H0M0S'));
        // echo "<pre>";print_r($dateAdded);die;
        $dateAdded_filter=$dateAdded->format('Y-m-d H:i:s');
        if(!isset($time))
        {
            $dateAdded_filter=null;

        }

        $filter_data = [
            'filter_delivery_date' => $deliveryDate,
            'filter_date_added_greater'=>$dateAdded_filter
        ];


        $this->load->model('sale/order');
        // $results = $this->model_sale_order->getOrders($filter_data);
        // $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
        $results = $this->model_sale_order->getOrderswithProcessing($filter_data);
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
                            $produceType = $consolidatedProducts[$index]['produce_type'] . ' / ' . $produceType . ' ';
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
        // echo "<pre>";print_r($consolidatedProducts);die;

        $data['products'] = $consolidatedProducts;
        //   echo "<pre>";print_r($data);die;
        if ($data['products'] != null) {
            // echo "<pre>";print_r($data['products']);die;
            $this->load->model('report/excel');
            $file = $this->model_report_excel->mail_consolidated_order_sheet_excel($data,'3pm');
        }
        //     else{
        //    echo "<pre>";print_r(1);die;
        //     }
        //    echo "<pre>";print_r($file);die;
    }

    public function walletRunningLow() {
         
        $this->load->model('scheduler/dbupdates');
        // $results = $this->model_sale_order->getOrders($filter_data);
        $results = $this->model_scheduler_dbupdates->getWalletRunningLowCustomers();
         
        $log = new Log('error.log');
        $log->write("Wallet Amount Running low");

        // echo "<pre>";print_r($results);die;      
        //   echo "<pre>";print_r($data);die;
        if ($results != null) {
            foreach ($results as $result) {
                $log->write($result['customer_id']);
                $this->model_scheduler_dbupdates->checkWalletRunningLow($result['customer_id']);
                  
            }
        }
    }



    public function getUnpaidOrdersPaymentOnDelivery() {

        try{

        $sendingDate =   date("Y-m-d");
        $log = new Log('error.log');
        $data['filter_status'] = 1;
        $data['sendingDate'] = $sendingDate;
        $data['filter_payment_terms'] = 'Payment On Delivery';        
        $this->load->model('report/excel');
        $this->model_report_excel->mail_customer_unpaid_order_excel($data);
        $log->write('Unpaid Orders Excel Sent Successfully -' . $sendingDate);

        }
        catch(excetion $ex)
        {
            $log = new Log('error.log');
            $log->write($ex);
            $log->write('Unpaid Orders Excel Sending Failed -' . $sendingDate);

        }

       
    }

    
}
