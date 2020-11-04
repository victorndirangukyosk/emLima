<?php

class ModelReportExcel extends Model {

    public function download_income_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $rows = $this->model_report_sale->getIncomes($data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Income Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Income Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Vendor');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                if ($result['pt']) {
                    $amount = $result['pt'];
                } else {
                    $amount = 0;
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['vendor']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $amount);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="income_report.xlsx"');
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

    public function download_product_purchased_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $this->load->model('report/product');

        $rows = $this->model_report_product->getPurchased($data);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Income Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products Purchased Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Unit');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Model');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['model']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['total']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="product_purchased_report.xlsx"');
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

    protected function setCellRowNew($worksheet, $row/* 1-based */, $data, &$style = null) {
        //echo "<pre>";print_r($data);die;
        $worksheet->fromArray($data, null, 'A' . $row, true);
        foreach ($data as $col => $val) {
            if (1 == $col) {
                $worksheet->setCellValueExplicit('A' . $row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        if (!empty($style)) {
            $worksheet->getStyle("$row:$row")->applyFromArray($style, false);
        }
    }

    public function download_sale_shipping_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $this->load->model('report/product');

        $rows = $this->model_report_sale->getShipping($data);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Income Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sale Shipping Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            if ('1990-01-01' == $data['filter_date_start']) {
                $html = 'START TO ' . $to;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date Start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date End');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Title');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date($this->language->get('date_format_short'), strtotime($result['date_start'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['date_end'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['title']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['total']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="sale_shipping_report.xlsx"');
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

    public function download_sale_payment_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $this->load->model('report/product');

        $rows = $this->model_report_sale->getPayment($data);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Income Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sale Payment Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            if ('1990-01-01' == $data['filter_date_start']) {
                $html = 'START TO ' . $to;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */
            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date Start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date End');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Title');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date($this->language->get('date_format_short'), strtotime($result['date_start'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['date_end'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['title']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['total']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="sale_payment_report.xlsx"');
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

    public function download_sale_transaction_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale_transaction');
        $this->load->model('report/product');

        $rows = $this->model_report_sale_transaction->getOrders($data);

        $i = 0;
        foreach ($rows as $result) {
            $sub_total = 0;
            $latest_total = 0;
            $totals = $this->model_report_sale_transaction->getOrderTotals($result['order_id']);
            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('sub_total' == $total['code']) {
                    $sub_total = $total['value'];
                    //break;
                }
                if ('total' == $total['code']) {
                    $latest_total = $total['value'];
                    //break;
                }
            }

            // $transaction_id = '';
            // $order_transaction_data = $this->model_report_sale_transaction->getOrderTransactionId($result['order_id']);
            // if (count($order_transaction_data) > 0) {
            //     $transaction_id = trim($order_transaction_data['transaction_id']);
            // }

            $rows[$i]['subtotal'] = $this->currency->format($sub_total);
            $rows[$i]['total'] = $this->currency->format($latest_total);
            ++$i;
        }

        // echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Sale Transaction Report')->setDescription('none');

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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sale Transaction Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            //
            //
            // $orderDate = date('d-m-Y', strtotime($data['filter_date_order']));
            // $deliveryDate = date('d-m-Y', strtotime($data['filter_date_delivery']));
            // $from = date('d-m-Y', strtotime($data['filter_date_added']));
            // $to = date('d-m-Y', strtotime($data['filter_date_modified']));
            // //$orderID=$data['filter_order_id'];
            // $objPHPExcel->getActiveSheet()->mergeCells("A3:I3");
            // $html = 'FROM '.$from.' TO '.$to;
            // if($data['filter_date_added'] == '1990-01-01') {
            // 	$html = 'START TO '.$to;
            // }
            // $html ='Filters Applied ' ;
            // if($data['filter_date_added']) {
            // 	$html = 	$html + 'Start Date:'.$from.' ' ;
            // }
            // if($data['filter_date_modified']) {
            // 	$html = 	$html + 'To Date:'.$to.' ' ;
            // }
            // if($data['filter_date_order']) {
            // 	$order = date('d-m-Y', strtotime($data['filter_date_order']));
            // 	$html = 	$html + 'Order Date:'.$order.' ' ;
            // }
            // if($data['filter_date_delivery']) {
            // 	$delivery = date('d-m-Y', strtotime($data['filter_date_delivery']));
            // 	$html = 	$html + 'Delivery Date:'.$delivery.' ' ;
            // }
            // if($data['filter_company']) {
            // 	$company = $data['filter_company'];
            // 	$html = 	$html + 'Company :'.$company.' ' ;
            // }

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Company Name');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order Date');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, ' Delivery Date');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Transaction ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Delivery Status');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Payment Method');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Amount');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($result['delivery_date'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['transaction_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['status']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['total']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="sale_transaction_report.xlsx"');
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

    public function download_vendor_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor');
        $this->load->model('report/sale');

        $rows = $this->model_report_sale->getVendors($data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vendor Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date end');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'No. Vendors');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date($this->language->get('date_format_short'), strtotime($result['date_start'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['date_end'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['total']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            $datetime = date('Y-m-d');
            $filename = 'vendor_report_' . $datetime . '.xls';
            //header('Content-Disposition: attachment;filename="vendor_report.xls"');
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

    public function download_vendor_order_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('report/sale');
        $rows = $this->model_report_sale->getVendorOrders($data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vendor Order Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor Order Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Vendor');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Date end');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Products');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['vendor']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['date_start'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date($this->language->get('date_format_short'), strtotime($result['date_end'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['products']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->currency->format($result['total']));

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            $datetime = date('Y-m-d');
            $filename = 'vendor_orders_report_' . $datetime . '.xls';
            //header('Content-Disposition: attachment;filename="vendor_report.xls"');
            header('Content-Disposition: attachment;filename="' . $filename . '"');

            //header('Content-Disposition: attachment;filename="vendor_orders_report.xls"');
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
    //any changes in this method, should be modified in "mail_consolidated_order_sheet_excel"
    public function download_consolidated_order_sheet_excel($data) {
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

            $sheet_title = 'Consolidated Product Orders';
            $sheet_subtitle = 'To be delivered on: ' . $data['orders'][0]['delivery_date'];

            // $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            // $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
            $objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            // $objPHPExcel->getActiveSheet()->getStyle('A1:D')->applyFromArray($styleArray);
            // foreach (range('A', 'F') as $columnID) {
            //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            //             ->setAutoSize(true);
            //     // $objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($styleArray);
            // }

            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);

            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Source');

            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            //  $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data['products'] as $product) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
                ++$row;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E'. $row)->getAlignment()->setWrapText(true); 
            
            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ? : $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
                $sheet_subtitle = $order['shipping_address'];
                $sheet_subtitle_1 = $order['comment'];

                // $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date of Delivery');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', $data['orders'][0]['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $row = 5;
                $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');

                if ($sheet_subtitle_1 != "" && $sheet_subtitle_1 != null) {
                    $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
                    $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Order Note : ' . $sheet_subtitle_1);
                    $row = 6;
                    $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'Product Note');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');

                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Product Note');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Source');

                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                }

                $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                // foreach (range('A', 'L') as $columnID) {
                //     if($columnID!='B')
                //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                //             ->setAutoSize(true);
                // }
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
                $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);


                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['product_note']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');

                    ++$row;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->getAlignment()->setWrapText(true); 

                ++$sheetIndex;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $deliveryDate = $data['orders'][0]['delivery_date'];
            $filename = 'KB_Order_Sheet_' . $deliveryDate . '.xlsx';

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
    
    public function mail_consolidated_order_sheet_excel($data) {
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

            $sheet_title = 'Consolidated Product Orders';
            $sheet_subtitle = 'To be delivered on: ' . $data['orders'][0]['delivery_date'];

            // $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            // $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
            $objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            // $objPHPExcel->getActiveSheet()->getStyle('A1:D')->applyFromArray($styleArray);
            // foreach (range('A', 'F') as $columnID) {
            //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            //             ->setAutoSize(true);
            //     // $objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($styleArray);
            // }

            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);

            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Source');

            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            //  $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data['products'] as $product) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
                ++$row;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E'. $row)->getAlignment()->setWrapText(true); 
            
            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ? : $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
                $sheet_subtitle = $order['shipping_address'];
                $sheet_subtitle_1 = $order['comment'];

                // $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date of Delivery');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', $data['orders'][0]['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $row = 5;
                $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');

                if ($sheet_subtitle_1 != "" && $sheet_subtitle_1 != null) {
                    $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
                    $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Order Note : ' . $sheet_subtitle_1);
                    $row = 6;
                    $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'Product Note');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');

                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Product Note');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Source');

                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                }

                $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                // foreach (range('A', 'L') as $columnID) {
                //     if($columnID!='B')
                //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                //             ->setAutoSize(true);
                // }
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
                $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);


                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['product_note']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');

                    ++$row;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->getAlignment()->setWrapText(true); 

                ++$sheetIndex;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $deliveryDate = $data['orders'][0]['delivery_date'];
            $filename = 'KB_Order_Sheet_' . $deliveryDate . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="' . $filename . '"');
            //header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');
            

            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp'; 
            $files = glob($folder_path.'/*');    
            // Deleting all the files in the list 
            foreach($files as $file) {             
                if(is_file($file)) 
                    unlink($file); // Delete the given file  
                } 
                // echo "<pre>";print_r($file);;
              $objWriter->save(DIR_UPLOAD . 'schedulertemp/' .$filename);            

                #region mail sending 
                $maildata['deliverydate']=$deliveryDate;
                $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
                $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
                
                // $subject = "Consolidated Order Sheet";                 
                // $message = "Please find the attachment.  <br>";
                // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
                $this->load->model('setting/setting');
                $email = $this->model_setting_setting->getEmailSetting('consolidatedorder');
                 
                if(strpos( $email,"@")==false)//if mail Id not set in define.php
               {
               $email = "sridivya.talluri@technobraingroup.com";
               }
                // $bccemail = "sridivya.talluri@technobraingroup.com";
                //   echo "<pre>";print_r($email);die;
                $filepath = DIR_UPLOAD . 'schedulertemp/' .$filename;
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($email);
                $mail->setBCC($bccemail);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->addAttachment($filepath);
                $mail->send();
                #endregion
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
    public function download_consolidated_calculation_sheet_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Consolidated Calculation Sheet')
                    ->setDescription('none');

            // Consolidated Customer Orders
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

            $sheet_title = 'Consolidated Customer Orders';
            $sheet_subtitle = 'Delivered on: ' . $data['orders'][0]['delivery_date'];

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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Date');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data['consolidation'] as $order) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $order['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['amount']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $order['delivery_date']);
                ++$row;
            }

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['consolidation']['total']);

            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ? : $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
                $sheet_subtitle = 'Calculation Sheet ' . $order['delivery_date'];

                $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                            ->setAutoSize(true);
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Weight Change');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Unit Price');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Total');

                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

                $row = 5;
                $totalOrderAmount = 0;
                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['quantity_updated']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $product['unit_updated']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $product['price']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total_updated']);

                    $totalOrderAmount += $product['total_updatedvalue'];

                    ++$row;
                }
                $totalOrderAmount = $this->currency->format($totalOrderAmount);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totalOrderAmount);

                ++$sheetIndex;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $deliveryDate = $data['orders'][0]['delivery_date'];
            $filename = 'KB_Calculation_Sheet_' . $deliveryDate . '.xlsx';

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

    public function download_calculation_sheet_excel($data) {
        //	    echo "<pre>";print_r($data);die;
        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Order Calculation Sheet')->setDescription('none');
            $objPHPExcel->setActiveSheetIndex(0);

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

            $sheet_title = 'Order #' . $data['orders'][0]['order_id'] . ' for ' . $data['orders'][0]['shipping_flat_number'];
            $sheet_subtitle = 'Calculation Sheet ' . date('d/m/Y');

            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Item');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'UOM');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Weight Change');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'UOM');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Unit Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data['orders'][0]['product'] as $product) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, '');

                ++$row;
            }
            $objPHPExcel->setActiveSheetIndex(0);

            $datetime = date('Y-m-d');
            $filename = 'CS_Order#' . $data['orders'][0]['order_id'] . '_' . $datetime . '.xlsx';

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

    public function download_report_vendor_orders_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('report/sale');
        $this->load->model('sale/order');
        $rows = $this->model_report_sale->getReportVendorOrders($data);

        //echo "<pre>";print_r($rows);
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vendor Orders Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor Orders Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $storename = $data['filter_store_name'];

            if (empty($data['filter_store_name'])) {
                $storename = 'Combined';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');

            $objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
            $objPHPExcel->getActiveSheet()->mergeCells('A5:F5');

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Seller : ' . $storename);

            $objPHPExcel->getActiveSheet()->getStyle('A1:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Order No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Date of Delivery');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'No. Of Items');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Order Amount');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 6)->applyFromArray($title);

            // Fetching the table data
            $row = 7;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);

            foreach ($rows as $result) {
                $products_qty = 0;

                if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                    $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
                } else {
                    $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
                }

                $sub_total = 0;

                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $sub_total = $total['value'];
                        break;
                    }
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['delivery_date'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $products_qty);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $sub_total);

                ++$row;

                if (date('Y-m-d') >= $result['date_added']) {
                    //echo "cef";print_r($result['date_added']);
                    $data['filter_date_start'] = $result['date_added'];
                }

                if ($data['filter_date_end'] <= $result['date_added']) {
                    $data['filter_date_end'] = $result['date_added'];
                }
            }

            //echo "<pre>";print_r($data['filter_date_end']."erxx".$data['filter_date_start']);die;
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));

            $html = 'FROM ' . $from . ' TO ' . $to;

            //echo "<pre>";print_r($from."e".$to);die;
            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="vendor_orders_report.xlsx"');
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

    public function download_report_vendor_returns_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('report/sale');
        $this->load->model('report/return');
        $this->load->model('sale/order');
        $rows = $this->model_report_return->getReportReturns($data);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vendor Returns Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');

            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor Returns Report');

            $stores_text = '';

            $stores_names = $this->model_report_sale->getVendorStores($data['filter_store']);

            if ($stores_names) {
                $stores_text = 'Stores: ' . $stores_names;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A2', $stores_text);

            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');

            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Return No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Return Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Return Amount');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['return_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date($this->language->get('date_format_short'), strtotime($result['date_modified'])));

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['price'] * $result['quantity']);

                ++$row;

                if (date('Y-m-d') >= $result['date_added']) {
                    //echo "cef";print_r($result['date_added']);
                    $data['filter_date_start'] = $result['date_added'];
                }

                if ($data['filter_date_end'] <= $result['date_added']) {
                    $data['filter_date_end'] = $result['date_added'];
                }
            }

            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));

            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            $datetime = date('Y-m-d');
            $filename = 'vendor_returns_report_' . $datetime . '.xls';
            //header('Content-Disposition: attachment;filename="vendor_report.xls"');
            header('Content-Disposition: attachment;filename="' . $filename . '"');

            //header('Content-Disposition: attachment;filename="vendor_returns_report.xlsx"');
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

    public function download_report_combined_report_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('report/sale');
        $this->load->model('report/return');
        $this->load->model('sale/order');

        $order_rows = $this->model_report_sale->getCombinedReportVendorOrders($data);

        $rows = $this->model_report_return->getCombinedReportReturns($data);

        //echo "<pre>";print_r($rows);die;
        //echo "<pre>";print_r($order_rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Combined Vendor Report')->setDescription('none');
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

            $stores_text = '';

            $stores_names = $this->model_report_sale->getVendorStores($data['filter_store']);

            if ($stores_names) {
                $stores_text = 'Vendor Name: ' . $stores_names;
            }

            //Company name, address
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');

            $objPHPExcel->getActiveSheet()->setCellValue('A1', htmlspecialchars_decode('VIRTUAL SUPERMARKETS LIMITED'));

            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Vendor Orders Report');

            $objPHPExcel->getActiveSheet()->setCellValue('A3', htmlspecialchars_decode($stores_text));

            //$objPHPExcel->getActiveSheet()->setCellValue("A4", 'Vendor Name: '.$this->user->getUserName());

            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');

            $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Reference');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'Ref No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'Date of Delivery');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'No. Of Items');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Order Amount');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 5)->applyFromArray($title);

            // Fetching the table data
            $row = 6;

            $sum = 0;

            //echo "<pre>";print_r($order_rows);die;
            foreach ($order_rows as $result) {
                $products_qty = 0;

                if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                    $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
                } else {
                    $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
                }

                $sub_total = 0;

                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $sub_total = $total['value'];
                        break;
                    }
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Order');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date($this->language->get('date_format_short'), strtotime($result['delivery_date'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $products_qty);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sub_total);

                ++$row;

                if (date('Y-m-d') >= $result['delivery_date']) {
                    //echo "cef";print_r($result['delivery_date']);
                    $data['filter_date_start'] = $result['delivery_date'];
                }

                if ($data['filter_date_end'] <= $result['delivery_date']) {
                    $data['filter_date_end'] = $result['delivery_date'];
                }

                $sum += $sub_total;
            }

            //==echo "<pre>";print_r($rows);die;

            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Return');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['return_id']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date($this->language->get('date_format_short'), strtotime($result['date_modified'])));

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['quantity']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, -1 * $result['price'] * $result['quantity']);

                ++$row;

                $sum -= ($result['price'] * $result['quantity']);
            }

            $row += 2;

            //total sales
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Total Sales');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sum);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray(['font' => ['bold' => true], 'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => 'F6FF33',
                    ],
            ]]);

            $row += 2;

            $commission_per = ($data['commission_per'] / 100);
            $vat_commission_per = ($data['vat_commission_per'] / 100);

            //sale commission
            $sales_commision = ($sum * $commission_per);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sales Commission - ' . $data['commission_per'] . '%');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sales_commision);

            ++$row;

            //vat on sale commission

            $vat_on_sales_commision = (($sum * $commission_per) * $vat_commission_per);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'VAT on Sales Commission - ' . $data['vat_commission_per'] . '%');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $vat_on_sales_commision);

            ++$row;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray(['font' => ['bold' => true], 'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => 'F6FF33',
                    ],
            ]]);

            //Sales Commission incl. VAT
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sales Commission incl. VAT');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sales_commision + $vat_on_sales_commision);

            ++$row;

            //Invoice No. XXXXXXXXX
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Invoice No. XXXXXXXXX');

            $row += 2;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray(['font' => ['bold' => true], 'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '54B452',
                    ],
            ]]);

            //Net Amount Payable
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Net Amount Payable ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sum - ($sales_commision + $vat_on_sales_commision));

            ++$row;

            //Net Amount Payable
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Bank Transfer No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, '');

            //echo "<pre>";print_r($data['filter_date_start']);die;
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));

            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A4', $html);

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            $datetime = date('Y-m-d');
            $filename = 'combined_vendor_report_' . $datetime . '.xls';
            //header('Content-Disposition: attachment;filename="vendor_report.xls"');
            header('Content-Disposition: attachment;filename="' . $filename . '"');

            //header('Content-Disposition: attachment;filename="vendor_returns_report.xlsx"');
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

    public function download_vendor_commission($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $rows = $this->model_report_sale->getOrdersCommission($data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Commission Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Commission Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date Start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date End');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Vendor Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Commission Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $totalCommission = $this->currency->format(($result['total'] * $result['commission']) / 100, $this->config->get('config_currency'));

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date($this->language->get('date_format_short'), strtotime($result['date_start'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['date_end'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $totalCommission);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="vendor_commission_report.xlsx"');
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

    public function download_saleorderadvanced($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        //$rows = $this->model_report_sale->getOrdersCommission($data);

        $rows = $this->model_report_sale->getExcelAdvancedOrders($data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Sales Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'VIRTUAL SUPERMARKETS LIMITED');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d/m/Y', strtotime($data['filter_date_start']));
            $to = date('d/m/Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'SALES REPORT FOR THE PERIOD ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);

            $storename = $data['filter_store_name'];

            if (empty($data['filter_store_name'])) {
                $storename = 'Combined';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A4:I4');
            $objPHPExcel->getActiveSheet()->mergeCells('A5:I5');

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Seller : ' . $storename);

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Order No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'No. Of Items');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Sub Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Wallet Used');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 6, 'Coupon Used');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 6, 'Reward Points Claimed');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 6, 'Delivery Charges');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 6, 'Order Total');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 6, 'Wallet Credited');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 6, 'Payment Method');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 6, 'Transaction ID');

            // Fetching the table data

            $this->load->model('sale/order');
            $this->load->model('account/order');

            $row = 7;
            foreach ($rows as $result) {
                $sub_total = 0;
                $total = 0;
                $delivery_charge = 0;
                $wallet_used = 0;
                $coupon_used = 0;
                $reward_points_used = 0;

                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

                $walletCredited = $this->model_sale_order->getTotalCreditsByOrderId($result['order_id']);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $tmptotal) {
                    if ('sub_total' == $tmptotal['code']) {
                        $sub_total = $tmptotal['value'];
                    }

                    if ('total' == $tmptotal['code']) {
                        $total = $tmptotal['value'];
                    }

                    if ('shipping' == $tmptotal['code']) {
                        $delivery_charge = $tmptotal['value'];
                    }

                    if ('credit' == $tmptotal['code']) {
                        $wallet_used = $tmptotal['value'];
                    }

                    if ('coupon' == $tmptotal['code']) {
                        $coupon_used = $tmptotal['value'];
                    }

                    if ('reward' == $tmptotal['code']) {
                        $reward_points_used = $tmptotal['value'];
                    }
                }

                $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

                $real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);

                if ($real_product_total) {
                    $product_total = $real_product_total;
                }

                $order_transaction_id = '';

                $order_transaction_data = $this->model_sale_order->getOrderTransactionId($result['order_id']);

                if (count($order_transaction_data) > 0) {
                    $order_transaction_id = trim($order_transaction_data['transaction_id']);
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('d-M-y', strtotime($result['delivery_date'])));

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product_total);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $sub_total);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $wallet_used);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $coupon_used);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $reward_points_used);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $delivery_charge);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $walletCredited);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $order_transaction_id);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="sales_report.xlsx"');
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

    public function download_saleorderproductmissing($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('sale/order');
        $this->load->model('account/order');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        //$rows = $this->model_report_sale->getOrdersCommission($data);
        //echo "<pre>";print_r($data);die;

        $results = $this->model_report_sale->getstockoutOrders($data);

        $data['orders'] = [];
        $data['torders'] = [];

        //echo "<pre>";print_r($results);die;
        /* foreach ($results as $result) {



          $is_edited = $this->model_sale_order->hasRealOrderProducts($result['order_id']);

          if(!$is_edited) {
          continue;
          }

          $EditedProducts = $this->model_sale_order->getRealOrderProducts($result['order_id']);

          $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);

          foreach ($OrignalProducts as $OrignalProduct) {

          $present = false;

          foreach ($EditedProducts as $EditedProduct) {
          if(!empty($OrignalProduct['name']) && $OrignalProduct['name'] == $EditedProduct['name']) {
          $present = true;
          }
          }

          if(!$present && !empty($OrignalProduct['name'])) {

          $data['torders'][] = array(
          'store' => $result['store_name'],
          'model' => $OrignalProduct['model'],

          'product_name' => $OrignalProduct['name'],
          'unit' => $OrignalProduct['unit'],
          'product_qty' => $OrignalProduct['quantity'],
          );

          }
          }
          }

          foreach ($data['torders'] as $torders1) {

          $ex = false;

          foreach ($data['orders'] as $value1) {

          if($value1['model'] == $torders1['model'] && $value1['store'] == $torders1['store']) {

          $ex = true;

          }

          }

          if(!$ex) {
          $sum = 0;

          foreach ($data['torders'] as $key => $torders2) {

          if($torders1['model'] == $torders2['model'] && $torders1['store'] == $torders2['store']) {

          $sum += $torders2['product_qty'];

          unset($data['torders'][$key]);

          }

          }

          $torders1['product_qty'] = $sum;

          array_push($data['orders'], $torders1);
          }
          }
         */

        foreach ($results as $result) {
            $is_edited = $this->model_sale_order->hasRealOrderProducts($result['order_id']);

            if ($is_edited) {
                //continue;
                $OrignalProducts  = $this->model_sale_order->getRealOrderProducts($result['order_id']);
            } else {
                $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);
            }
            // $EditedProducts = $this->model_sale_order->getRealOrderProducts($result['order_id']);
            // $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);

            /* echo "<pre>";print_r($OrignalProducts);
              echo "<pre>";print_r($EditedProducts);die; */

            foreach ($OrignalProducts as $OrignalProduct) {
                // $present = false;
                // foreach ($EditedProducts as $EditedProduct) {
                //     if(!empty($OrignalProduct['name']) && $OrignalProduct['name'] == $EditedProduct['name'] && $OrignalProduct['unit'] == $EditedProduct['unit']) {
                //         $present = true;
                //     }
                // }!$present &&

                if ( !empty($OrignalProduct['name'])) {
                    $data['torders'][] = [
                        'store' => $result['store_name'],
                        'model' => $OrignalProduct['model'],
                        'product_name' => $OrignalProduct['name'],
                        'unit' => $OrignalProduct['unit'],
                        'product_qty' => (float) ($OrignalProduct['quantity']),
                    ];
                }
            }
        }

        //echo "<pre>";print_r($data['torders']);die;
        foreach ($data['torders'] as $torders1) {
            $ex = false;

            foreach ($data['orders'] as $value1) {
                if ($value1['product_name'] == $torders1['product_name'] && $value1['store'] == $torders1['store'] && $value1['unit'] == $torders1['unit']) {
                    $ex = true;
                }
            }

            if (!$ex) {
                $sum = (float) 0.00;

                foreach ($data['torders'] as $key => $torders2) {
                    if ($torders1['product_name'] == $torders2['product_name'] && $torders1['store'] == $torders2['store']  &&  $torders1['unit'] == $torders2['unit']) {
                        $sum += (float) $torders2['product_qty'];
                        unset($data['torders'][$key]);
                    }
                }
                $torders1['product_qty'] = (float) $sum;
                ++$order_total;
                array_push($data['orders'], $torders1);
            }
        }

        $rows = $data['orders'];

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('STOCK OUT PRODUCTS')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d/m/Y', strtotime($data['filter_date_start']));
            $to = date('d/m/Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $html1 = 'STOCK OUT PRODUCTS';

            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html1);

            $objPHPExcel->getActiveSheet()->setCellValue('A4', $html);

            $storename = $data['filter_store_name'];

            if (empty($data['filter_store_name'])) {
                $storename = 'Combined';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
            $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');

            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Store Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Barcode');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'Product Name');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Ordered Qty');

            // Fetching the table data

            $row = 7;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['store']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['model']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $row, $result['model'], PHPExcel_Cell_DataType::TYPE_STRING);

                //$worksheet->setCellValueExplicit('A'.$row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['product_qty']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="stock_out_products.xlsx"');
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

    public function download_saleorderproductmissingNew($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('sale/order');
        $this->load->model('account/order');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        //$rows = $this->model_report_sale->getOrdersCommission($data);
        //echo "<pre>";print_r($data);die;
        $rows = $data['orders'];

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('STOCK OUT PRODUCTS')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'KWIKBASKET');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d/m/Y', strtotime($data['filter_date_start']));
            $to = date('d/m/Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html1 = 'STOCK OUT PRODUCTS';

            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html1);

            $objPHPExcel->getActiveSheet()->setCellValue('A4', $html);

            $storename = $data['filter_store_name'];

            if (empty($data['filter_store_name'])) {
                $storename = 'Combined';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A4:I4');
            $objPHPExcel->getActiveSheet()->mergeCells('A5:I5');

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Store Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Barcode');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'Product Name');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Ordered Qty');

            // Fetching the table data

            $row = 7;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['store']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['model']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $row, $result['model'], PHPExcel_Cell_DataType::TYPE_STRING);

                //$worksheet->setCellValueExplicit('A'.$row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['product_qty']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="stock_out_products.xlsx"');
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

    public function download_vendor_c_commission($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $rows = $this->model_report_sale->getVendorOrdersCommission($data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Commission Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Commission Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date Start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date End');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Order Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Commission Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $totalCommission = $this->currency->format(($result['total'] * $result['commission']) / 100, $this->config->get('config_currency'));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date($this->language->get('date_format_short'), strtotime($result['date_start'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['date_end'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $totalCommission);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="commission_report.xlsx"');
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

    public function download_store_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('setting/store');
        $rows = $this->model_setting_store->getStores($data);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Stores')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Stores ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Store Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Store Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Store Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['status'] ? 'Enabled' : 'Disabled');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="store.xlsx"');
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

    public function download_vendor_wallet_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('setting/store');
        $this->load->model('sale/customer');
        $rows = $this->model_sale_customer->getAllVendorCredits($data);

        //echo "<pre>";print_r($rows);die;
        /* [name] => Steve Wood
          [id] => 131
          [vendor_id] => 12
          [order_id] => 760
          [description] => Order Value : R$17
          [amount] => 33.22
          [date_added] => 2018-06-28 11:02:47
          [email] => c.haurasiaabhi09@gmail.com */
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vendor Wallet')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor Wallet - Date From to Date End of filter');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Email');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Order Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Description');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;

            $sum_total = 0;

            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['email']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['order_id']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['description']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['amount']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));

                $sum_total += $result['amount'];
                ++$row;
            }

            ++$row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, '');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Total:');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sum_total);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, '');

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="vendor_wallet.xls"');
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

    public function download_cityzipcode_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('setting/store');

        //$rows = $this->model_setting_store->getStores($data);

        $this->load->model('localisation/city');

        $rows = $this->model_localisation_city->getAllZipcodeByCity($data['city_id']);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Zipcodes')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Zipcodes');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Zipcode');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);

            //echo "<pre>";print_r("ve");die;
            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['zipcode']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="' . $data['city_name'] . '_zipcodes.xlsx"');
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

    public function download_customer_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('sale/customer');
        $rows = $this->model_sale_customer->getCustomers($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customers')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers ');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'DOB');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'gender');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Telephone');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Approved');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 4)->applyFromArray($title);


            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['dob'] == '0000-00-00 00:00:00' ? '' : $result['dob']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['gender']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['telephone']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['status'] ? 'Enabled' : 'Disabled');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['approved']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['customer_group']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="customers.xlsx"');
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

    public function download_accountmanager_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('user/accountmanager');
        $rows = $this->model_user_accountmanager->getAccountManagers($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('AccountManagers')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'AccountManagers');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Account Manager Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');


            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Telephone');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['user_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['telephone']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['status'] ? 'Enabled' : 'Disabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="accountmanagers.xlsx"');
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

    public function download_accountmanagercustomers_excel($data, $account_manager_id) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('user/accountmanager');
        $rows = $this->model_user_accountmanager->getAccountManagersCustomers($data, $account_manager_id);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('AccountManagers')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');


            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Telephone');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['telephone']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['status'] ? 'Enabled' : 'Disabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="customers.xlsx"');
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

    public function getCity($city_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'city where city_id= "' . $city_id . '"')->row;
    }

    public function download_vendorproduct_excel($data, $filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('catalog/vendor_product');

        // $rows = $this->model_catalog_vendor_product->getProducts("");
        $rows = $this->model_catalog_vendor_product->getProducts($filter_data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vendor Products')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:J2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vendor Products ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:J2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);


             */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'General Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Vendor Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Barcode');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Unit Size');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Store Name');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Category');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'image');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Special Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['name']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['model']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['store_name']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row,$result['category']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row,$result['imagep']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['special_price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['sts'] ? 'Enabled' : 'Disabled');
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="vendor_products.xlsx"');
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

    public function download_vendorproduct_category_prices($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('catalog/vendor_product');

        // $rows = $this->model_catalog_vendor_product->getProducts("");
        $rows = $data;

        //   echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Product_Prices');

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

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'product_store_id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'product_id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'store_id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'price_category');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'UOM');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 1)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 1)->applyFromArray($title);

            // Fetching the table data
            $row = 2;
            $store_id = 75;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $store_id);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, 1);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="products_category_prices.xlsx"');
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

    public function download_dashboard_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        // $rows = $data;
        // echo "<pre>";print_r($data);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Information ')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Dashboard Information ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B6:B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);


             */

            foreach (range('A', 'D') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Start Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, $data['start_date']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, '               ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'End Date ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, $data['end_date']);
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, '');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 6;
            //foreach ($rows as $result) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Revenue Collected');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['ss']);
            // $objPHPExcel->getActiveSheet()->setHorizontal(1, $row, \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            ++$row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Orders Delivered');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['os']);
            ++$row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Customers Onboarded');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['cs']);
            ++$row;

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Revenue Booked');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bs']);
            ++$row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Orders Created');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['cos']);

            ++$row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Cancelled Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['cns']);

            //$row++;
            //}

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="Dashboard_info.xlsx"');
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
            } else {
                $sheet_subtitle = '';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Orders Statement');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

    public function download_customer_order_pattern_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Customer Orders Pattern')
                    ->setDescription('none');

            // Consolidated Customer Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Orders Pattern');

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

            $sheet_title = 'Customer Orders Pattern';
            // $sheet_title0 = 'Company Name: ' . $data['consolidation'][0]['company'];
            // $sheet_subtitle = 'Customer Name: ' . $data['consolidation'][0]['customer'] . ' #  ' . 'Order Id :' . $data['consolidation'][0]['orderid'];
            // $sheet_subtitle1 = 'Order Date: ' . $data['consolidation'][0]['date'];

            // $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            // $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_title0);
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_subtitle);
            // $objPHPExcel->getActiveSheet()->setCellValue('A4', $sheet_subtitle1);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            // $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);
            // $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);
            // $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B:Z')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
               foreach(range('A','L') as $columnID) {
            	   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            		   ->setAutoSize(true);
               }
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
            $cellid=0;
            foreach ($data[0] as $h_key=>$h_value) {               
                // echo "<pre>";print_r($h_key);die;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cellid, 3, $h_key);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($cellid, 3)->applyFromArray($title);

                $cellid++;
            }             
            $row = 4;
            
            foreach ($data as  $b_key=>$b_value) {
                $cellid=0;
                foreach ($b_value as  $bb_key=>$bb_value) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cellid, $row, $bb_value);
                //$Amount = $Amount + $order['total_updatedvalue'];
                $cellid++;
                }
                ++$row;
            }
            // $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($title);
          

            $objPHPExcel->setActiveSheetIndex(0);

            $filename = 'Customer_Orders_Pattern.xlsx';

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

    public function download_customer_order_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);
        
        if($this->user->isAccountManager()) {
        $results = $this->model_report_customer->getAccountManagerOrders($data);    
        } else {
        $results = $this->model_report_customer->getOrders($data);
        }

        foreach ($results as $result) {
            $data['customers'][] = [
                'customer' => $result['customer'],
                'email' => $result['email'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'orders' => $result['orders'],
                'products' => $result['products'],
                'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'edit' => $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&customer_id='.$result['customer_id'].$url, 'SSL'),
            ];
        } 
         
        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_customer_order_excel');
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Orders')->setDescription('none');

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
            // if ($data['customers']) {
            //     $sheet_subtitle = 'Company Name : ' . $data['customers'][0]['company'];
            // } else {
            //     $sheet_subtitle = '';
            // }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Orders ');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            if($from!='01-01-1990')
            {
            $html = 'FROM ' . $from . ' TO ' . $to;
            }
            else
            $html = 'Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'No. Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'No. Products');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Total'); 
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
 

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['customers'] as $result) {
                 
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['products']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['total']); 
                // $Amount = $Amount + $result['subtotalvalue'];
                ++$row;
            }
            // $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            // // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Customer_orders.xlsx';

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







}
