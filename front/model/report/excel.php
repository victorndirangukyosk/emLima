<?php

class ModelReportExcel extends Model {

    public function download_customer_statement_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);

        $results = $this->model_report_customer->getValidCustomerOrders($data);

        $this->load->model('sale/order');

        $data['customers'] = [];

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
            foreach ($totals as $total) {
                if ('sub_total' == $total['code']) {
                    $sub_total = $total['value'];
                    break;
                }
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
            ];
        }

        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_customer_statement_excel');
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Order Statement')->setDescription('none');

            //PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

            $objPHPExcel->setActiveSheetIndex(0);

            // Field names in the first row
            // ID, Photo, Name, Contact no., Reason, Valid from, Valid upto, Intime, Outtime
            $title = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '4390df',
                    ],
                ],
            ];

            //Company name, address
            //$objPHPExcel->getActiveSheet()->mergeCells("A1:E2");
            if ($data['customers']) {
                $sheet_subtitle = 'Company Name : ' . $data['customers'][0]['company'];
                $sheet_subtitle_sap = $data['customers'][0]['SAP_customer_no'];
                $order_start_date = $data['customers'][0]['date_added'];
            } else {
                $sheet_subtitle = $sheet_subtitle_sap = '';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
            $objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Orders Statement');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SAP Customer Number');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->setCellValue('E1', $sheet_subtitle_sap);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            if (!empty($data['filter_date_start']))
                $from = date('d-m-Y', strtotime($data['filter_date_start']));
            else
                $from = str_replace("/", "-", $order_start_date);
            // try{
            // if(strpos($data['filter_date_start'], '1990'))
            // $from=$order_start_date ;
            // $from=str_replace("/","-",$order_start_date);
            // }
            // catch(Exception $ex)
            // {
            // }
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Order Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Delivery Date');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'P.O. Number');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Order Value');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 7;
            $Amount = 0;
            foreach ($data['customers'] as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */
                $log->write('RESULT download_customer_statement_excel');
                $log->write($result);
                $log->write('RESULT download_customer_statement_excel');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['po_number']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['subtotal']);
                $Amount = $Amount + $result['subtotalvalue'];
                ++$row;
            }
            $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $log->write($data['customers'][0]['customer'] . 'RESULT2 download_customer_statement_excel');
            $log->write('download_customer_statement_excel');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Customer_order_statement_' . $data['customers'][0]['customer'] . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_customer_statement_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_mostpurchased_products_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        // $this->load->language('report/income');
        // $this->load->model('sale/customer');
        // $rows = $this->model_sale_customer->getCustomers($data);

        $date = date('Y-m-d', strtotime('-30 day'));
        $customer_id = $data['customer_id'];
        $sql = 'SELECT SUM( op.quantity )AS total,pd.name,op.unit FROM ' . DB_PREFIX . 'order_product AS op LEFT JOIN ' . DB_PREFIX . 'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  ' . DB_PREFIX . "product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id = " . $customer_id . ' AND o.date_added >= ' . $date . ' GROUP BY pd.name  having sum(op.quantity)>100   ';
        $query = $this->db->query($sql);
        $rows = $query->rows;

        // echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Most bought Products')->setDescription('none');
            $objPHPExcel->setActiveSheetIndex(0);

            // Field names in the first row
            // ID, Photo, Name, Contact no., Reason, Valid from, Valid upto, Intime, Outtime
            $title = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '4390df',
                    ],
                ],
            ];

            //Company name, address
            $objPHPExcel->getActiveSheet()->mergeCells('A1:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:C2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

            // foreach(range('A','L') as $columnID) {
            // 	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            // 		->setAutoSize(true);
            // }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Unit of Measure');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Qty');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['total']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $filename = 'MostBoughtProducts.xlsx';
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_consolidated_order_products_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Delivery Sheet')->setDescription('none');

            // Consolidated Product Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Consolidated');

            $title = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '51AB66',
                    ],
                ],
            ];

            $sheet_title = 'Individual & Consolidated Products Summary';
            $sheet_subtitle = ''; // 'To be delivered on: '.$data['orders'][0]['delivery_date'];

            $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
            $objPHPExcel->getActiveSheet()->getStyle('A1:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM');
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Source');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data['products'] as $product) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
                ++$row;
            }

            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = 'order ID-' . $order['order_id'];
                $worksheetName = ' ' . $sheetIndex;

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
                $sheet_subtitle = $order['shipping_address'];
                $sheet_subtitle_1 = $order['comment'];

                $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_subtitle_1);

                $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                            ->setAutoSize(true);
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Product Note');
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Source');

                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
                //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

                $row = 5;
                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['product_note']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');

                    ++$row;
                }

                ++$sheetIndex;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            // $deliveryDate = $data['orders'][0]['delivery_date'];
            $filename = 'KB_Sale_OrderProducts.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            //            echo "<pre>";print_r($e);die;
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_order_products_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Customer Order Statement')
                    ->setDescription('none');

            // Consolidated Customer Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Products');

            $title = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '51AB66',
                    ],
                ],
            ];

            $sheet_title = 'Customer Order Statement';
            $sheet_title0 = 'Company Name: ' . $data['consolidation'][0]['company'];
            $sheet_subtitle = 'Customer Name: ' . $data['consolidation'][0]['customer'] . ' #  ' . 'Order Id :' . $data['consolidation'][0]['orderid'];
            $sheet_subtitle1 = 'Order Date: ' . $data['consolidation'][0]['date'];

            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_title0);
            $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->setCellValue('A4', $sheet_subtitle1);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //    foreach(range('A','L') as $columnID) {
            // 	   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            // 		   ->setAutoSize(true);
            //    }
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Product');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);

            $row = 6;
            $Amount = 0;
            foreach ($data['products'] as $order) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $order['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['unit_updated']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $order['quantity_updated']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, str_replace('KES', ' ', $order['total_updated']));
                $Amount = $Amount + $order['total_updatedvalue'];
                ++$row;
            }
            $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $Amount);

            // Individual customer orders
            //    $sheetIndex = 1;
            //    foreach ($data['orders'] as $order) {
            // 	   $objPHPExcel->createSheet($sheetIndex);
            // 	   $objPHPExcel->setActiveSheetIndex($sheetIndex);
            // 	   $worksheetName = $order['company_name'] ?: $order['firstname'] . ' ' . $order['lastname'];
            // 	   // A fatal error is thrown for worksheet titles with more than 30 character
            // 	   if(strlen($worksheetName) > 30) {
            // 		   $worksheetName = substr($worksheetName, 0, 27) . '...';
            // 	   }
            // 	   $objPHPExcel->getActiveSheet()->setTitle($worksheetName);
            // 	   $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
            // 	   $sheet_subtitle = 'Calculation Sheet ' . $order['delivery_date'];
            // 	   $objPHPExcel->getActiveSheet()->mergeCells("A1:G1");
            // 	   $objPHPExcel->getActiveSheet()->mergeCells("A2:G2");
            // 	   $objPHPExcel->getActiveSheet()->setCellValue("A1", $sheet_title);
            // 	   $objPHPExcel->getActiveSheet()->setCellValue("A2", $sheet_subtitle);
            // 	   $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->applyFromArray(array("font" => array("bold" => true), 'color' => array(
            // 		   'rgb' => '51AB66'
            // 	   ),));
            // 	   $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->applyFromArray(array("font" => array("bold" => true), 'color' => array(
            // 		   'rgb' => '51AB66'
            // 	   ),));
            // 	   $objPHPExcel->getActiveSheet()->mergeCells("A3:G3");
            // 	   $objPHPExcel->getActiveSheet()->getStyle("A1:G3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // 	   foreach(range('A','L') as $columnID) {
            // 		   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            // 			   ->setAutoSize(true);
            // 	   }
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM');
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Weight Change');
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'UOM');
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Unit Price');
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Total');
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // 	   $row = 5;
            // 	   $totalOrderAmount = 0;
            // 	   foreach($order['products'] as $product) {
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['quantity_updated']);
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $product['unit_updated']);
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $product['price']);
            // 		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total_updated']);
            // 		   $totalOrderAmount += $product['total_updatedvalue'];
            // 		   $row++;
            // 	   }
            // 	   $totalOrderAmount=$this->currency->format($totalOrderAmount);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, "Total");
            // 	   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totalOrderAmount);
            // 	   $sheetIndex++;
            //    }

            $objPHPExcel->setActiveSheetIndex(0);

            $filename = 'Products_' . $data['consolidation'][0]['customer'] . '_' . $data['consolidation'][0]['orderid'] . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            //            echo "<pre>";print_r($e);
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_customer_unpaid_order_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $rows = $this->model_sale_order->getOrders($data);
        //echo "<pre>";print_r($rows);
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Orders Sheet')->setDescription('none');
            $objPHPExcel->setActiveSheetIndex(0);

            // Field names in the first row
            // ID, Photo, Name, Contact no., Reason, Valid from, Valid upto, Intime, Outtime
            $title = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '4390df',
                    ],
                ],
            ];

            //Company name, address
            $objPHPExcel->getActiveSheet()->mergeCells('A1:H2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Orders Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');

            $objPHPExcel->getActiveSheet()->mergeCells('A4:H4');

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Vendor : KwikBasket');

            $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Address');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 6, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 6, 'Delivery Time Slot');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 6, 'Order Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 6)->applyFromArray($title);

            // Fetching the table data
            $row = 7;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['shipping_address']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->currency->format($result['total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['delivery_timeslot']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="orders_sheet.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

}
