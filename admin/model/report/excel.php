<?php

require_once DIR_ROOT . '/vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf;

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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products Purchased Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, round($result['total'] ?? 0, 2));

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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sale Shipping Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            if ('1990-01-01' == $data['filter_date_start']) {
                $html = 'START TO ' . $to;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sale Payment Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            if ('1990-01-01' == $data['filter_date_start']) {
                $html = 'START TO ' . $to;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

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

        $rows = $this->model_report_sale_transaction->getOrdersNew($data);

        $i = 0;
        foreach ($rows as $result) {
            $sub_total = 0;
            $latest_total = 0;
            $latest_total = $this->model_report_sale_transaction->getOrderExactTotal($result['order_id']);

            // $totals = $this->model_report_sale_transaction->getOrderTotals($result['order_id']);
            // //echo "<pre>";print_r($totals);die;
            // foreach ($totals as $total) {
            //     if ('sub_total' == $total['code']) {
            //         $sub_total = $total['value'];
            //         //break;
            //     }
            //     if ('total' == $total['code']) {
            //         $latest_total = $total['value'];
            //         //break;
            //     }
            // }
            // $transaction_id = '';
            // $order_transaction_data = $this->model_report_sale_transaction->getOrderTransactionId($result['order_id']);
            // if (count($order_transaction_data) > 0) {
            //     $transaction_id = trim($order_transaction_data['transaction_id']);
            // }
            // $rows[$i]['subtotal'] = $this->currency->format($sub_total);
            // $rows[$i]['total'] = $this->currency->format($latest_total);
            $rows[$i]['total'] = round($latest_total, 2);
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
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->currency->format($result['total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, round($result['total'], 2));

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
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
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
            $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->getAlignment()->setWrapText(true);

            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ?: $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName;
                $sheet_subtitle_order = 'Order #' . $order['order_id'];
                $sheet_subtitle = $order['shipping_address'];
                // $sheet_subtitle_1 = $order['comment'];
                //commented it , because as per the request it shoulb be shown after products and it may change
                //instead of removing code
                //take new variable
                $sheet_subtitle_1 = "";
                $sheet_subtitle_1_new = $order['comment'];

                // $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date of Delivery : ' . $order['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('B1', $order['delivery_date']);
                //$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Delivery Timeslot');
                //$objPHPExcel->getActiveSheet()->setCellValue('D1', $order['delivery_timeslot']);


                $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Order Status');
                $objPHPExcel->getActiveSheet()->setCellValue('D3', $order['status']);
                // $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Delivery Timeslot :' . $order['delivery_timeslot']);


                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle_order);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A4', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Delivery Timeslot :' . $order['delivery_timeslot']);
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
                $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $row = 7;
                $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
                $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');

                if ($sheet_subtitle_1 != "" && $sheet_subtitle_1 != null) {//this if condition not changing
                    $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');
                    $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Order Note : ' . $sheet_subtitle_1);
                    $row = 6;
                    $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Product Note');
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Product Note');
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                }

                $objPHPExcel->getActiveSheet()->getStyle('A1:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                // foreach (range('A', 'L') as $columnID) {
                //     if($columnID!='B')
                //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                //             ->setAutoSize(true);
                // }
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
                $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
                // $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(25);


                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['product_note']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');

                    ++$row;
                }
                if ($sheet_subtitle_1_new != "" && $sheet_subtitle_1_new != null && $row > 6) {
                    // $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Order Note : ' . $sheet_subtitle_1_new);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $sheet_subtitle_1_new);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->getAlignment()->setWrapText(true);

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

    public function mail_consolidated_order_sheet_excel($data, $name = '') {
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
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
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
            $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->getAlignment()->setWrapText(true);

            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ?: $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName;
                $sheet_subtitle_order = 'Order #' . $order['order_id'];
                $sheet_subtitle = $order['shipping_address'];
                // $sheet_subtitle_1 = $order['comment'];
                //commented it , because as per the request it shoulb be shown after products and it may change
                //instead of removing code
                //take new variable
                $sheet_subtitle_1 = "";
                $sheet_subtitle_1_new = $order['comment'];

                // $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date of Delivery : ' . $order['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('B1', $order['delivery_date']);
                //$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Delivery Timeslot');
                //$objPHPExcel->getActiveSheet()->setCellValue('D1', $order['delivery_timeslot']);

                $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Order Status');
                $objPHPExcel->getActiveSheet()->setCellValue('D3', $order['status']);
                // $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Delivery Timeslot :' . $order['delivery_timeslot']);

                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle_order);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A4', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Delivery Timeslot :' . $order['delivery_timeslot']);
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
                $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $row = 7;
                $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
                $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
                $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');

                if ($sheet_subtitle_1 != "" && $sheet_subtitle_1 != null) {//this if condition not changing
                    $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');
                    $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Order Note : ' . $sheet_subtitle_1);
                    $row = 6;
                    $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Product Note');
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Product Note');
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                }

                $objPHPExcel->getActiveSheet()->getStyle('A1:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                // foreach (range('A', 'L') as $columnID) {
                //     if($columnID!='B')
                //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                //             ->setAutoSize(true);
                // }
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
                $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
                // $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(25);


                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['product_note']);
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');

                    ++$row;
                }

                if ($sheet_subtitle_1_new != "" && $sheet_subtitle_1_new != null && $row > 6) {
                    // $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Order Note : ' . $sheet_subtitle_1_new);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $sheet_subtitle_1_new);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                }

                $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->getAlignment()->setWrapText(true);

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
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file
                }
            }
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['deliverydate'] = $deliveryDate;
            $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            if ($name != "") {
                $subject = $subject . ' evening';
            }
            // $subject = "Consolidated Order Sheet";
            // $message = "Please find the attachment.  <br>";
            // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('consolidatedorder');

            if (strpos($email, "@") == false) {//if mail Id not set in define.php
                $email = "sridivya.talluri@technobraingroup.com";
            }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
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

            $tax = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FF0000',
                    ],
                ]
            ];

            $sheet_title = 'Consolidated Customer Orders';
            $sheet_subtitle = 'Delivered on: ' . $data['orders'][0]['delivery_date'];

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Inv No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order Status.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'SAP Customer No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'SAP Doc No.');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Amount');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data['consolidation'] as $order) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $order['delivery_date']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $order['invoice_no']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $order['order_status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $order['SAP_customer_no']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $order['SAP_document_no']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $order['amount']);

                if (isset($order['color']) && $order['color'] > 0) {
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($tax);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $row)->applyFromArray($tax);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $row)->applyFromArray($tax);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($tax);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $row)->applyFromArray($tax);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($tax);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($tax);
                }

                ++$row;
            }

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['consolidation']['total']);

            // Individual customer orders
            $sheetIndex = 1;
            $delivery_charge = 0;
            $delivery_charge_vat = 0;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ?: $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }
                $delivery_charge = $order['delivery_charge'];
                $delivery_charge_vat = $order['delivery_charge_vat'];
                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
                $sheet_subtitle = 'Calculation Sheet ' . $order['delivery_date'];
                $delivery_timeslot = 'Delivery Timeslot : ' . $order['delivery_timeslot'];
                $orderstatus = 'Order Status : ' . $order['status'];
                $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $delivery_timeslot);
                $objPHPExcel->getActiveSheet()->setCellValue('A4', $orderstatus);
                $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:G4');
                $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                            ->setAutoSize(true);
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Product Name');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'Quantity');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'Weight Change');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 5, 'Unit Price');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 5, 'Total');

                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 5)->applyFromArray($title);

                $row = 6;
                $totalOrderAmount = 0;
                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['quantity_updated']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $product['unit_updated']);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $product['price']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $product['price_value']);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total_updated']);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total_value']);

                    // $totalOrderAmount += $product['total_updatedvalue'];
                    $totalOrderAmount += $product['total_value'];

                    ++$row;
                }
                if (isset($delivery_charge)) {
                    $totalOrderAmountFull = $totalOrderAmount + $delivery_charge_vat + $delivery_charge;
                } else {
                    $totalOrderAmountFull = $totalOrderAmount;
                }
                // $totalOrderAmount = $this->currency->format($totalOrderAmount);
                // $totalOrderAmountFull = $this->currency->format($totalOrderAmountFull);
                // $delivery_charge = $this->currency->format($delivery_charge);
                // $delivery_charge_vat = $this->currency->format($delivery_charge_vat);

                $totalOrderAmount = $totalOrderAmount;
                $totalOrderAmountFull = $totalOrderAmountFull;
                $delivery_charge = $delivery_charge;
                $delivery_charge_vat = $delivery_charge_vat;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Sub-Total ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totalOrderAmount);

                // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray('');
                // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray('');
                ++$row;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Standard Delivery ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $delivery_charge);

                ++$row;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'VAT on Standard Delivery ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $delivery_charge_vat);

                ++$row;
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Total ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totalOrderAmountFull);
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

    public function download_consolidated_products_calculation_sheet_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Order Products Sheet')
                    ->setDescription('none');

            // Consolidated Customer Orders
            // $objPHPExcel->setActiveSheetIndex(0);
            // $objPHPExcel->getActiveSheet()->setTitle('Consolidated');

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

            $tax = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FF0000',
                    ],
                ]
            ];

            $sheet_title = 'Consolidated Customer Order Products';
            $sheet_subtitle = 'Delivered on: ' . $data['orders'][0]['delivery_date'];

            // $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
            // $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            // $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);
            // $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
            // $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // foreach (range('A', 'L') as $columnID) {
            //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            //             ->setAutoSize(true);
            // }
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date');
            // // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Company');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Inv No.');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order Status.');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'SAP Customer No.');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'SAP Doc No.');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Amount');
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // $row = 5;
            // foreach ($data['consolidation'] as $order) {
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $order['delivery_date']);
            //     // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['customer']);
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['company_name']);
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $order['invoice_no']);
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $order['order_status']);
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $order['SAP_customer_no']);
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $order['SAP_document_no']);
            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $order['amount']);
            //     if (isset($order['color']) && $order['color'] > 0) {
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($tax);
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $row)->applyFromArray($tax);
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $row)->applyFromArray($tax);
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($tax);
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $row)->applyFromArray($tax);
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($tax);
            //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($tax);
            //     }
            //     ++$row;
            // }
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Total');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['consolidation']['total']);
            // Individual customer orders
            // $sheetIndex = 1;
            $sheetIndex = 0;
            $delivery_charge = 0;
            $delivery_charge_vat = 0;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ?: $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }
                $delivery_charge = $order['delivery_charge'];
                $delivery_charge_vat = $order['delivery_charge_vat'];
                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName . ' Order #' . $order['order_id'];
                $sheet_subtitle = 'Delivery Date ' . $order['delivery_date'];
                $delivery_timeslot = 'Delivery Timeslot : ' . $order['delivery_timeslot'];
                $orderstatus = 'Order Status : ' . $order['status'];
                $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $delivery_timeslot);
                $objPHPExcel->getActiveSheet()->setCellValue('A4', $orderstatus);
                $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:G4');
                $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                            ->setAutoSize(true);
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Product Name');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'Quantity');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'Weight Change');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'UOM');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 5, 'Unit Price');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 5, 'Total');

                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 5)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 5)->applyFromArray($title);

                $row = 6;
                $totalOrderAmount = 0;
                foreach ($order['products'] as $product) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['quantity_updated']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $product['unit_updated']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $product['price_value']);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total_updated']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $product['total_value']);

                    // $totalOrderAmount += $product['total_updatedvalue'];
                    $totalOrderAmount += $product['total_value'];

                    ++$row;
                }
                if (isset($delivery_charge)) {
                    $totalOrderAmountFull = $totalOrderAmount + $delivery_charge_vat + $delivery_charge;
                } else {
                    $totalOrderAmountFull = $totalOrderAmount;
                }
                // $totalOrderAmount = $this->currency->format($totalOrderAmount);
                // $totalOrderAmountFull = $this->currency->format($totalOrderAmountFull);
                // $delivery_charge = $this->currency->format($delivery_charge);
                // $delivery_charge_vat = $this->currency->format($delivery_charge_vat);


                $totalOrderAmount = ($totalOrderAmount);
                $totalOrderAmountFull = ($totalOrderAmountFull);
                $delivery_charge = ($delivery_charge);
                $delivery_charge_vat = ($delivery_charge_vat);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Sub-Total ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totalOrderAmount);

                // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray('');
                // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray('');
                ++$row;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Standard Delivery ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $delivery_charge);

                ++$row;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'VAT on Standard Delivery ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $delivery_charge_vat);

                ++$row;
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Total ');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $totalOrderAmountFull);
                ++$sheetIndex;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $deliveryDate = $data['orders'][0]['delivery_date'];
            $filename = 'KB_Product_Sheet_' . $deliveryDate . '.xlsx';

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

    public function download_orders_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('report/sale');
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

            $vendor = $data['filter_vendor'];

            if (empty($data['filter_vendor'])) {
                $vendor = 'Combined';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');

            $objPHPExcel->getActiveSheet()->mergeCells('A4:H4');

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Vendor : ' . $vendor);

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 6, 'Payment Method');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 6, 'Payment Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 6, 'Payment Reference Number');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 6)->applyFromArray($title);

            // Fetching the table data
            $row = 10;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $order_transaction_id = $this->getOrderTransactionIdExists($result['order_id']);
                $paid = NULL;
                if (isset($result['paid']) && $result['paid'] == 'Y') {
                    $paid = 'PAID';
                } elseif (isset($result['paid']) && $result['paid'] == 'N') {
                    $paid = 'UN PAID';
                } elseif (isset($result['paid']) && $result['paid'] == 'P') {
                    $paid = 'PARTIALLY PAID';
                }
                /* $log = new Log('error.log');
                  $log->write($order_transaction_id); */
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['shipping_address']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->currency->format($result['total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($result['total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['delivery_timeslot']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $paid);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $order_transaction_id);
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

    public function download_pezesha_orders_receivables_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/vendor_order');
        $this->load->model('report/sale');
        $this->load->model('sale/order');
        $results = $this->model_sale_order->getPezeshaOrders($data);
        $this->load->model('vendor/vendor');

        foreach ($results as $result) {
            $sub_total = 0;
            $total = 0;

            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
            $missing_products = $this->model_sale_order->getMissingProductsByOrderId($result['order_id']);
            $store_details = $this->model_vendor_vendor->getVendorByStoreId($result['store_id']);
            $vendor_details = $this->model_vendor_vendor->getVendorDetails($store_details['vendor_id']);

            // echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('sub_total' == $total['code']) {
                    $sub_total = $total['value'];
                }
                if ('total' == $total['code']) {
                    $total = $total['value'];
                }
            }
            $result['customer'] = $result['firstname'] . " " . $result['lastname'];
            if ($this->user->isVendor()) {
                $result['customer'] = strtok($result['firstname'], ' ');
            }

            // if ($result['company_name']) {
            //     $result['company_name'] = ' (' . $result['company_name'] . ')';
            // } else {
            //     // $result['company_name'] = "(NA)";
            // }


            if ($result['paid'] == 'Y') {
                $amount_paid = $this->currency->format($total, $this->config->get('config_currency'));
            } else if ($result['paid'] == 'P') {
                $amount_paid = $this->currency->format($result['amount_partialy_paid'], $this->config->get('config_currency'));
            } else {
                $amount_paid = 0;
            }

            $vendor_total = $this->currency->format(($result['total'] - ($result['total'] * $result['commission']) / 100), $this->config->get('config_currency'));
            $this->load->model('localisation/order_status');
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer_id' => $result['customer_id'],
                'delivery_id' => $result['delivery_id'],
                'order_prefix' => $vendor_details['orderprefix'] != '' ? $vendor_details['orderprefix'] . '-' : '',
                'vendor_name' => $vendor_details['lastname'],
                'customer' => $result['customer'],
                'company_name' => $result['company_name'],
                'status' => $result['status'],
                'payment_method' => $result['payment_method'],
                'shipping_method' => $result['shipping_method'],
                'shipping_address' => $result['shipping_address'],
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'delivery_timeslot' => $result['delivery_timeslot'],
                'store' => $result['store_name'],
                'order_status_id' => $result['order_status_id'],
                'vendor_order_status_id' => $result['vendor_order_status_id'],
                'order_status_color' => $result['color'],
                'city' => $result['city'],
                'vendor_total' => $vendor_total,
                'total' => $this->currency->format($total, $result['currency_code'], $result['currency_value']),
                'sub_total' => $this->currency->format($sub_total, $result['currency_code'], $result['currency_value']),
                'sub_total_custom' => $sub_total, $result['currency_code'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                // 'view' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                // 'invoice' => $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                // 'products_list' => $this->url->link('sale/order/productlist', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                // 'order_spreadsheet' => $this->url->link('sale/order/orderCalculationSheet', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                // 'shipping' => $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                // 'edit' => $this->url->link('sale/editinvoice/EditInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                // 'delete' => $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'po_number' => $result['po_number'],
                'SAP_customer_no' => $result['SAP_customer_no'],
                'SAP_doc_no' => $result['SAP_doc_no'],
                // 'all_order_statuses' => $this->model_localisation_order_status->getOrderStatuses(),
                'paid' => $result['paid'],
                'amount_partialy_paid' => $result['amount_partialy_paid'],
                'delivery_charges' => $result['delivery_charges'],
                'missing_products_count' => count($missing_products),
                'mpesa_reference' => $result['mpesa_reference'],
                'created_at' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
                'amount_paid' => $amount_paid,
            ];
        }

        $rows = $data['orders'];
        // echo "<pre>";print_r($rows);
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Pezesha Orders')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:M2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Pyzesha Orders Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            // $vendor = $data['filter_vendor'];
            // if (empty($data['filter_vendor'])) {
            //     $vendor = 'Combined';
            // }

            $objPHPExcel->getActiveSheet()->mergeCells('A3:M3');

            $objPHPExcel->getActiveSheet()->mergeCells('A4:M4');

            $objPHPExcel->getActiveSheet()->setCellValue('A4', '');

            $objPHPExcel->getActiveSheet()->getStyle('A1:M4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'N') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Customer ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 6, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 6, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 6, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 6, 'Amount Paid');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 6, 'Date of payment');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 6, 'Payment Method');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 6, 'Paid To ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 6, 'Payment Transaction ID');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 6)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(12, 6)->applyFromArray($title);

            // Fetching the table data
            $row = 7;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['delivery_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, ($result['total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, ($result['amount_paid']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['created_at']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['paid_to']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['mpesa_reference']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="pezesha_orders_sheet.xlsx"');
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
                $total = 0;

                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $tot) {
                    if ('sub_total' == $tot['code']) {
                        $sub_total = $tot['value'];
                        //break;
                    }
                    if ('total' == $tot['code']) {
                        $total = $tot['value'];
                        break;
                    }
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($result['delivery_date'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $products_qty);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, round($total, 2));

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
        // //echo "<pre>";print_r($data);die;
        // $results = $this->model_report_sale->getstockoutOrders($data);
        $OrignalProducts = $this->model_report_sale->getstockoutOrdersAndProducts($data);

        $data['orders'] = [];
        // $data['torders'] = [];
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

        // foreach ($results as $result) {
        //     $is_edited = $this->model_sale_order->hasRealOrderProducts($result['order_id']);
        //     if ($is_edited) {
        //         //continue;
        //         //$OrignalProducts  = $this->model_sale_order->getRealOrderProducts($result['order_id']);
        //         $OrignalProducts = $this->model_sale_order->getRealOrderProductsStockOut($result['order_id'], $data['filter_store'], $data['filter_name']);
        //     } else {
        //         //$OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);
        //         $OrignalProducts = $this->model_sale_order->getOrderProductsStockOut($result['order_id'], $data['filter_store'], $data['filter_name']);
        //     }
        //     // $EditedProducts = $this->model_sale_order->getRealOrderProducts($result['order_id']);
        //     // $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);
        //     /* echo "<pre>";print_r($OrignalProducts);
        //       echo "<pre>";print_r($EditedProducts);die; */
        //     foreach ($OrignalProducts as $OrignalProduct) {
        //         // $present = false;
        //         // foreach ($EditedProducts as $EditedProduct) {
        //         //     if(!empty($OrignalProduct['name']) && $OrignalProduct['name'] == $EditedProduct['name'] && $OrignalProduct['unit'] == $EditedProduct['unit']) {
        //         //         $present = true;
        //         //     }
        //         // }!$present &&
        //         if (!empty($OrignalProduct['name'])) {
        //             $data['torders'][] = [
        //                 'store' => $result['store_name'],
        //                 'model' => $OrignalProduct['model'],
        //                 'product_name' => $OrignalProduct['name'],
        //                 'unit' => $OrignalProduct['unit'],
        //                 'product_qty' => (float) ($OrignalProduct['quantity']),
        //             ];
        //         }
        //     }
        // }
        // //echo "<pre>";print_r($data['torders']);die;
        // foreach ($data['torders'] as $torders1) {
        //     $ex = false;
        //     foreach ($data['orders'] as $value1) {
        //         if ($value1['product_name'] == $torders1['product_name'] && $value1['store'] == $torders1['store'] && $value1['unit'] == $torders1['unit']) {
        //             $ex = true;
        //         }
        //     }
        //     if (!$ex) {
        //         $sum = (float) 0.00;
        //         foreach ($data['torders'] as $key => $torders2) {
        //             if ($torders1['product_name'] == $torders2['product_name'] && $torders1['store'] == $torders2['store'] && $torders1['unit'] == $torders2['unit']) {
        //                 $sum += (float) $torders2['product_qty'];
        //                 unset($data['torders'][$key]);
        //             }
        //         }
        //         $torders1['product_qty'] = (float) $sum;
        //         ++$order_total;
        //         array_push($data['orders'], $torders1);
        //     }
        // }


        foreach ($OrignalProducts as $OrignalProduct) {

            $total = $OrignalProduct['total'] + $OrignalProduct['tax'];
            $product_total_average = ($total / $OrignalProduct['quantity']);

            $data['torders'][] = [
                'store' => $OrignalProduct['store_name'],
                'model' => $OrignalProduct['product_id'],
                'product_name' => $OrignalProduct['name'],
                'unit' => $OrignalProduct['unit'],
                'product_id' => $OrignalProduct['product_id'],
                // 'product_qty' => (float) $OrignalProduct['quantity'],
                // 'product_total' => (float) $total,
                // 'product_total_average' => (float) $product_total_average,
                'product_qty' => round($OrignalProduct['quantity'], 2),
                'product_total' => round($total, 2),
                'product_total_average' => round($product_total_average, 2),
            ];
            ++$order_total;
        }
        // $rows = $data['orders'];
        $rows = $data['torders'];

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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:G2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
            $objPHPExcel->getActiveSheet()->getStyle('A1:G6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d/m/Y', strtotime($data['filter_date_start']));
            $to = date('d/m/Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
            $html1 = 'STOCK OUT PRODUCTS';

            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html1);

            $objPHPExcel->getActiveSheet()->setCellValue('A4', $html);

            $storename = $data['filter_store_name'];

            if (empty($data['filter_store_name'])) {
                $storename = 'Combined';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A4:G4');
            $objPHPExcel->getActiveSheet()->mergeCells('A5:G5');

            $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Store Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'Product Name');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Ordered Qty');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 6, 'Total Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 6, 'Avg. Sale Price');

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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['product_total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['product_total_average']);

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

    public function download_driver_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('drivers/drivers');
        $rows = $this->model_drivers_drivers->getDrivers($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Drivers')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:G2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Drivers ');
            $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Driver Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Telephone');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Driving Licence');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['driver_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['telephone']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['driving_licence']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['status'] ? 'Enabled' : 'Disabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="drivers.xlsx"');
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

    public function download_executive_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('executives/executives');
        $rows = $this->model_executives_executives->getExecutives($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Executives')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Executives ');
            $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Executive Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Telephone');
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Driving Licence');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Added');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['delivery_executive_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['telephone']);
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['driving_licence']);
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

            header('Content-Disposition: attachment;filename="executives.xlsx"');
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

    public function download_orderprocessinggroup_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $rows = $this->model_orderprocessinggroup_orderprocessinggroup->getOrderProcessingGroups($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Order Processing Groups')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Order Processing Groups');
            $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order Processing Group Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Description');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Status');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Date Created');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Updated');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_processing_group_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_processing_group_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['description']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['status'] ? 'Enabled' : 'Disabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($result['created_at'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($result['updated_at'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="orderprocessinggroups.xlsx"');
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

    public function download_orderprocessors_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $this->load->model('orderprocessinggroup/orderprocessor');
        $rows = $this->model_orderprocessinggroup_orderprocessor->getOrderProcessors($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Order Processors')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Order Processors');
            $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order Processor Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Status');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Date Created');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Updated');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_processor_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['order_processing_group_name']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['status'] ? 'Enabled' : 'Disabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($result['date_updated,'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="orderprocessors.xlsx"');
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

        // echo "<pre>";print_r($rows);die;

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
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray(['font' => ['bold' => true], 'color' => [
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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'DOB');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'gender');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Telephone');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Approved');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Date Added');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Source');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {


                if ($result['company_name']) {
                    $result['company_name'] = ' (' . $result['company_name'] . ')';
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer_id']);
                if ($result['company_name'])
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name'] . PHP_EOL . $result['company_name']);
                else
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['dob'] == '0000-00-00 00:00:00' ? '' : $result['dob']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['gender']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['telephone']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['status'] ? 'Enabled' : 'Disabled');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['approved']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['customer_group']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['source']);
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

    public function download_dispatch_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('dispatchplanning/dispatchplanning');
        $rows = $this->model_dispatchplanning_dispatchplanning->getAllDispatchesExcel($data);

        // echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('DispatchPlanning')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Dispatch Planning ');
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Dispatch Planning Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Vehicle');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Driver');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Delivery Executive');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Delivery Timeslot');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Created At');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Updated At');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['registration_number']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['driver_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['delivery_executive_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_time_slot']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, date($this->language->get('date_format_short'), strtotime($result['created_at'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, date($this->language->get('date_format_short'), strtotime($result['updated_at'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="DispatchPlanning.xlsx"');
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

    public function download_farmer_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('user/farmer');
        $rows = $this->model_user_farmer->getFarmers($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Farmers')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:M2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Farmers ');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Farmer Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'UserName');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Mobile');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Farmer Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Irrigation Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Location');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Description');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Farm Size');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, 'Farm Size Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, 'Organization');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, 'Created Date');

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(12, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['farmer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['mobile']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['farmer_type']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['irrigation_type']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['location']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['description']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['farm_size']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['farm_size_type']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['organization']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['created_at']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="Farmers.xlsx"');
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

    public function download_supplier_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('user/supplier');
        $rows = $this->model_user_supplier->getSuppliers($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Suppliers')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Suppliers ');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Supplier Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'UserName');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Mobile');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Location');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Description');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Organization');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Created Date');

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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['farmer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['mobile']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['location']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['description']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['organization']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['created_at']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="Suppliers.xlsx"');
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

    public function download_farmer_transactions_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('user/farmer_transactions');
        $rows = $this->model_user_farmer_transactions->getFarmers($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Farmer Transactions')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:M2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Farmer Transactions ');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Farmer Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Email');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'UserName');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Mobile');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Organization');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'UOM');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, 'Created Date');

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['farmer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['mobile']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['organization']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['quantity'] * $result['price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['created_at']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="FarmerTransactions.xlsx"');
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
            $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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

    public function download_customer_experience_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('user/customerexperience');
        $rows = $this->model_user_customerexperience->getCustomerExperience($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('CustomerExperience')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CustomerExperience');
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer Experience Id');
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

            header('Content-Disposition: attachment;filename="customerexperience.xlsx"');
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

    public function download_inventoryhistoryexcel($data, $filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('catalog/vendor_product');

        $rows = $this->model_catalog_vendor_product->getProductInventoryHistory($filter_data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Inventory History')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:O2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Inventory History ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:O2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);


             */

            foreach (range('A', 'S') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'General Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Product Store ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Procured Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Rejected Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Previous Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Updated Quantity');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Previous Buying Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Buying Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Previous Source');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, 'Source');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, 'Updated By');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, 'User Role');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, 'Date Added');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 4, 'Date Time');

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 4)->applyFromArray($title);

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(12, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(13, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(14, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['procured_qty']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['rejected_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['prev_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['current_qty']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['prev_buying_price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['buying_price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['prev_source']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['source']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['added_user']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['added_user_role']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, date($this->language->get('time_format'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="inventory_history.xlsx"');
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

    public function download_inventorypricehistoryexcel($data, $filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('catalog/vendor_product');

        $rows = $this->model_catalog_vendor_product->getProductInventoryPriceHistory($filter_data);

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Inventory Price History')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Inventory Price History ');
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'General Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Product Store ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Buying Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Previous Buying Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Source');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Previous Source');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Updated By');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'User Role');
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['buying_price']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['prev_buying_price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['source']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['prev_source']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['added_user']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['added_user_role']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['date_added']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="inventory_price_history.xlsx"');
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
        // [price] => 145.00
        // [special_price] => 120.00

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
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, '');
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['special_price']);
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

    public function account_manager_download_dashboard_excel($data) {
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
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Account Manager Name : ' . $data['account_manager_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Dashboard Information ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray(['font' => ['bold' => true], 'color' => [
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
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, str_replace('KES', ' ', $order['total_updated']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, round($order['total_updatedvalue'], 2));
                $Amount = $Amount + $order['total_updatedvalue'];
                ++$row;
            }
            // $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $Amount = round($Amount, 2);
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

    //mail_download_customer_statement_excel
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
                if ('total' == $total['code']) {
                    $sub_total = $total['value'];
                    break;
                }
            }

            $transcation_id = $this->model_sale_order->getOrderTransactionIdandDate($result['order_id']);
            if (!empty($transcation_id)) {
                $transaction_id_value = $transcation_id['transaction_id'];
                $transaction_id_date = $transcation_id['created_at'];
            } else {
                $transaction_id_value = '';
                $transaction_id_date = '';
            }

            if ($result['paid'] == 'N') {
                //check transaction Id Exists are not// if exists, it is paid order,
                // $transcation_id = $this->model_sale_order->getOrderTransactionId($result['order_id']);
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
                'customer_id' => $result['customer_id'],
                'payment_method' => $result['payment_method'],
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
                'subtotalvalue' => round($sub_total, 2),
                'po_number' => $result['po_number'],
                'subtotal' => str_replace('KES', ' ', $this->currency->format($sub_total)),
                'SAP_customer_no' => $result['SAP_customer_no'],
                'paid' => $result['paid'],
                'amountpaid' => round($result['amountpaid'], 2),
                'pendingamount' => round($result['pendingamount'], 2),
                'pendingamountvalue' => ($result['pendingamount']),
                'paid_to' => $result['paid_to'],
                'transaction_id' => $transaction_id_value,
                'transaction_id_date' => $transaction_id_date ? date($this->language->get('date_format_short'), strtotime($transaction_id_date)) : '',
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
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'P') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Delivery Date');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'P.O. Number');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Amount Paid');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Pending Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, 'Date of payment');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, 'Payment Method');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, 'Paid To');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, 'Payment Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 4, 'Payment Transactio ID');

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
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(12, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(13, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(14, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 7;
            $Amount = 0;
            $PendingAmountTotal = 0;
            foreach ($data['customers'] as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */
                $log->write('RESULT download_customer_statement_excel');
                $log->write($result);
                $log->write('RESULT download_customer_statement_excel');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['po_number']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['subtotalvalue']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['amountpaid']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['pendingamount']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['transaction_id_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['paid_to']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $result['paid']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $result['transaction_id']);

                $Amount = $Amount + $result['subtotalvalue'];
                $PendingAmountTotal = $PendingAmountTotal + $result['pendingamountvalue'];
                ++$row;
            }
            $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            // $PendingAmount = str_replace('KES', ' ', $this->currency->format($PendingAmount));
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $Amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $PendingAmountTotal);

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

    //download_customer_statement_excel
    public function mail_download_customer_statement_excel($data, $dt, $pdf = 0) {

        $log = new Log('error.log');
        echo "<pre>";
        print_r(date('l', $dt));
        echo "<pre>";
        print_r(date('d', $dt));
        if (date('l', $dt) != 'Sunday' && date('d', $dt) != '01' && date('d', $dt) != '16') {//weekly
            echo "<pre>";
            print_r('No execution today');
            return;
        } else {
            if (date('d', $dt) == '01') {
                echo "<pre>";
                print_r($dt);

                $dtp = date("Y-m-d", strtotime("-1 days", $dt));
                echo "<pre>";
                print_r($dtp);
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
        echo "<pre>";
        print_r($data);

        $this->load->model('report/customer');
        $customerswithOrders = $this->model_report_customer->getCustomerWithOrders($data);

        //Firstly get all customers
        echo "<pre>";
        print_r($customerswithOrders);
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
                $Amount_ordervalue_grand = 0;
                $Amount_paid_grand = 0;
                $Amount_pending_grand = 0;

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
                        $result['paid'] = 'Partially Paid';
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

                    $Amount_ordervalue_grand = $Amount_ordervalue_grand + $sub_total;
                    $Amount_paid_grand = $Amount_paid_grand + $result['amountpaid'];
                    $Amount_pending_grand = $Amount_pending_grand + $result['pendingamount'];
                }
                // echo "<pre>";print_r($data['customers']);die;
                if ($data['customers'] != null) {
                    $data['customers'][0]['Amount_ordervalue_grand'] = $Amount_ordervalue_grand;
                    $data['customers'][0]['Amount_paid_grand'] = $Amount_paid_grand;
                    $data['customers'][0]['Amount_pending_grand'] = $Amount_pending_grand;
                }
                echo "<pre>";
                print_r($data);

                $data['token'] = $this->session->data['token'];
                //  $this->response->setOutput($this->load->view('report/customer_statement_pdf.tpl', $data));
                //  return;

                if ($pdf == 1) {


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
                        //   $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/report/customer_statement_pdf.tpl', $data));

                        echo "<pre>";
                        print_r("111111111111111111");

                        $pageOptions = array(
                            'javascript-delay' => 2000,
                            'encoding' => 'UTF-8',
                        );
                        $pdf->addPage($template, $pageOptions);

                        // if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                        //     mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
                        // }
                        // unlink($filename);
                        $folder_path = DIR_UPLOAD . 'schedulertemp';
                        $files = glob($folder_path . '/*');
                        // Deleting all the files in the list
                        foreach ($files as $file) {
                            if (is_file($file))
                                unlink($file); // Delete the given file
                        }
                        echo "<pre>";
                        print_r("222222222222222222");

                        if (!$pdf->saveAs(DIR_UPLOAD . 'schedulertemp/' . "Customer_order_statement_" . $data['customers'][0]['customer'] . ".pdf")) {
                            $errors = $pdf->getError();
                            echo $errors;
                            die;
                        }

                        echo "<pre>";
                        print_r("333333333333333333");

                        // if (!$pdf->send("Customer_order_statement_" . $data['customers'][0]['customer'] . ".pdf")) {
                        //     $error = $pdf->getError();
                        //     echo $error;
                        //     die;
                        // }// download pdf commented , as it is coming as text


                        $filename = 'Customer_order_statement_' . $data['customers'][0]['customer'] . '.pdf';

                        echo "<pre>";
                        print_r($filename);
                        echo "<pre>";
                        print_r('$filename');

                        #region mail sending
                        $maildata['customer_name'] = $data['filter_customer'];
                        $maildata['start_date'] = $data['filter_date_start'];
                        $maildata['end_date'] = $data['filter_date_end'];
                        $maildata['email'] = $data['filter_customer_email'];
                        // $maildata['end_date'] = $data['filter_date_end'];

                        $subject = $this->emailtemplate->getSubject('customerstatement', 'customerstatement_25', $maildata);
                        $message = $this->emailtemplate->getMessage('customerstatement', 'customerstatement_25', $maildata);

                        // $subject = "Consolidated Order Sheet";
                        // $message = "Please find the attachment.  <br>";
                        // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
                        $this->load->model('setting/setting');
                        $bccemail = $this->model_setting_setting->getEmailSetting('financeteam');
                        // $email =$data['filter_customer_email'];
                        // $email_contacts = $this->model_report_customer->getcustomercontacts($data['filter_customer_id']);
                        // foreach($email_contacts as $econtact)
                        // {
                        //     $email=$email.';'.$econtact['email'];
                        // }
                        $email = 'stalluri@technobraingroup.com';
                        $log->write('customer Statement Emails ' . $email . ' ' . 'CC mails' . $bccemail);

                        echo "<pre>";
                        print_r($email);
                        // if (strpos($email, "@") == false) {//if mail Id not set in define.php
                        //     $email = "sridivya.talluri@technobraingroup.com";
                        // }
                        // $bccemail = "sridivya.talluri@technobraingroup.com";
                        //   echo "<pre>";print_r($email);die;
                        $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
                        $mail = new Mail($this->config->get('config_mail'));
                        $mail->setTo($email);
                        // $mail->setBcc($bccemail);
                        $mail->setCc($bccemail);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setSubject($subject);
                        $mail->setHTML($message);
                        $mail->addAttachment($filepath);
                        $mail->send();
                        #endregion
                    } catch (Exception $e) {
                        $errstr = $e->getMessage();
                        $errline = $e->getLine();
                        $errfile = $e->getFile();
                        $errno = $e->getCode();
                        $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_customer_statement_excel');
                        $this->log->write('Error in Automatic PDF Statement');
                    }
                    //   exit;//for testing purpose one mail is enough, will uncomment later
                } else {


                    try {

                        $this->load->library('excel');
                        $this->load->library('iofactory');

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
                        // else
                        //     $from = str_replace("/", "-", $order_start_date);

                        $to = date('d-m-Y', strtotime($data['filter_date_end']));
                        $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
                        $html = 'FROM ' . $from . ' TO ' . $to;

                        $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
                        $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
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
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Amount Paid');
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Pending Amount');
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Payment Status');

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
                        $row = 7;
                        $Amount = 0;
                        $PendingAmountTotal = 0;
                        foreach ($data['customers'] as $result) {
                            /* if($result['pt']) {
                              $amount = $result['pt'];
                              }else{
                              $amount = 0;
                              } */
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['company']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['order_id']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['date_added']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['delivery_date']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['po_number']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['subtotal']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['amountpaid']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['pendingamount']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['paid']);
                            $Amount = $Amount + $result['subtotalvalue'];
                            $PendingAmountTotal = $PendingAmountTotal + $result['pendingamountvalue'];
                            ++$row;
                        }
                        $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, $row)->applyFromArray($title);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $Amount);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $PendingAmountTotal);
                        $objPHPExcel->setActiveSheetIndex(0);
                        //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
                        // Sending headers to force the user to download the file
                        //header('Content-Type: application/vnd.ms-excel');
                        //header("Content-type: application/octet-stream");
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                        $filename = 'Customer_order_statement_' . $data['customers'][0]['customer'] . '.xlsx';

                        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        // header('Content-Disposition: attachment;filename="' . $filename . '"');
                        // header('Cache-Control: max-age=0');
                        // $objWriter->save('php://output');



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
                        $files = glob($folder_path . '/*');
                        // Deleting all the files in the list
                        foreach ($files as $file) {
                            if (is_file($file))
                                unlink($file); // Delete the given file
                        }
                        // echo "<pre>";print_r($file);;
                        $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

                        #region mail sending
                        $maildata['customer_name'] = $data['filter_customer'];
                        $maildata['start_date'] = $data['filter_date_start'];
                        $maildata['end_date'] = $data['filter_date_end'];
                        $maildata['email'] = $data['filter_customer_email'];
                        // $maildata['end_date'] = $data['filter_date_end'];

                        $subject = $this->emailtemplate->getSubject('customerstatement', 'customerstatement_25', $maildata);
                        $message = $this->emailtemplate->getMessage('customerstatement', 'customerstatement_25', $maildata);

                        // $subject = "Consolidated Order Sheet";
                        // $message = "Please find the attachment.  <br>";
                        // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
                        $this->load->model('setting/setting');
                        $bccemail = $this->model_setting_setting->getEmailSetting('financeteam');
                        // $email =$data['filter_customer_email'];
                        // $email_contacts = $this->model_report_customer->getcustomercontacts($data['filter_customer_id']);
                        // foreach($email_contacts as $econtact)
                        // {
                        //     $email=$email.';'.$econtact['email'];
                        // }
                        $email = 'stalluri@technobraingroup.com';
                        $log->write('customer Statement Emails ' . $email . ' ' . 'CC mails' . $bccemail);

                        echo "<pre>";
                        print_r($email);
                        // if (strpos($email, "@") == false) {//if mail Id not set in define.php
                        //     $email = "sridivya.talluri@technobraingroup.com";
                        // }
                        // $bccemail = "sridivya.talluri@technobraingroup.com";
                        //   echo "<pre>";print_r($email);die;
                        $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
                        $mail = new Mail($this->config->get('config_mail'));
                        $mail->setTo($email);
                        // $mail->setBcc($bccemail);
                        $mail->setCc($bccemail);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setSubject($subject);
                        $mail->setHTML($message);
                        $mail->addAttachment($filepath);
                        $mail->send();
                        #endregion
                        // $data['customers'][]=null;//empty the previous
                        // exit;
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

            $objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            // $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_title0);
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_subtitle);
            // $objPHPExcel->getActiveSheet()->setCellValue('A4', $sheet_subtitle1);
            $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C:Z')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
            $cellid = 0;
            foreach ($data[0] as $h_key => $h_value) {
                // echo "<pre>";print_r($h_key);die;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cellid, 3, $h_key);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($cellid, 3)->applyFromArray($title);

                $cellid++;
            }
            $row = 4;

            foreach ($data as $b_key => $b_value) {
                $cellid = 0;
                foreach ($b_value as $bb_key => $bb_value) {
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

    public function download_customer_order_count_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Customer Orders Count')
                    ->setDescription('none');

            // Consolidated Customer Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Orders Count');

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

            $sheet_title = 'Customer Orders Count';
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

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
            $cellid = 0;
            foreach ($data[0] as $h_key => $h_value) {
                // echo "<pre>";print_r($h_key);die;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cellid, 3, $h_key);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($cellid, 3)->applyFromArray($title);

                $cellid++;
            }
            $row = 4;

            foreach ($data as $b_key => $b_value) {
                $cellid = 0;
                foreach ($b_value as $bb_key => $bb_value) {
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

            $filename = 'Customer_Orders_Count.xlsx';

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

        if ($this->user->isAccountManager()) {
            $results = $this->model_report_customer->getAccountManagerOrders($data);
        } else {
            $results = $this->model_report_customer->getOrders($data);
        }

        foreach ($results as $result) {
            $data['customers'][] = [
                'customer' => $result['customer'],
                'company' => $result['company'],
                'email' => $result['email'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'orders' => $result['orders'],
                'products' => $result['products'],
                'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'total_value' => round($result['total'], 2),
                'edit' => $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
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
            if ($from != '01-01-1990') {
                $html = 'FROM ' . $from . ' TO ' . $to;
            } else
                $html = 'Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Company');
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

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['products']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['total_value']);
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

    public function download_customer_activity_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');

        $results = $this->model_report_customer->getCustomerActivities($data);
        $this->load->model('sale/order');

        foreach ($results as $result) {
            $comment = vsprintf($this->language->get('text_' . $result['key']), unserialize($result['data']));

            $find = [
                'customer_id=',
                'order_id=',
            ];

            $replace = [
                '', '',
                    // $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&customer_id=', 'SSL'),
                    // $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&order_id=', 'SSL'),
            ];
            $comment = str_replace($find, $replace, $comment);
            $comt = preg_replace("/<\/?a( [^>]*)?>/i", "", $comment);
            $data['activities'][] = [
                'company_name' => $result['company_name'],
                'email' => $result['email'],
                'comment' => $comt,
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_added_time' => date($this->language->get('time_format'), strtotime($result['date_added'])),
                'order_id' => $result['order_id'],
            ];
        }
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Activities')->setDescription('none');

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

            $sheet_subtitle = '';

            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Activities');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            // $from = date('d-m-Y', strtotime($data['filter_date_start']));
            // $to = date('d-m-Y', strtotime($data['filter_date_end']));
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            // $html = 'FROM ' . $from . ' TO ' . $to;
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer Email');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Comment');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'IP');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Time');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Order ID');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;

            foreach ($data['activities'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['email']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['comment']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['ip']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['date_added_time']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['order_id']);

                ++$row;
            }

            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);


            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $log = new Log('error.log');
            $log->write($data['customers'][0]['customer'] . 'RESULT2 download_customer_activity_excel');
            $log->write('download_customer_activity_excel');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Customer_Activity.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_customer_activity_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_farmer_activity_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('user/farmer');

        $results = $this->model_user_farmer->getFarmerActivities($data);
        $this->load->model('sale/order');

        foreach ($results as $result) {
            $comment = vsprintf($this->language->get('text_' . $result['key']), unserialize($result['data']));

            $find = [
                'farmer_id=',
            ];

            $replace = [
                '', '',
                    // $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&customer_id=', 'SSL'),
                    // $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&order_id=', 'SSL'),
            ];
            $comment = str_replace($find, $replace, $comment);
            $comt = preg_replace("/<\/?a( [^>]*)?>/i", "", $comment);
            $data['activities'][] = [
                'organization' => $result['organization'],
                'farmer_name' => $result['first_name'] . ' ' . $result['last_name'],
                'email' => $result['email'],
                'comment' => $comt,
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
            ];
        }
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Farmer Activities')->setDescription('none');

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

            $sheet_subtitle = '';

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Farmer Activities');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            // $from = date('d-m-Y', strtotime($data['filter_date_start']));
            // $to = date('d-m-Y', strtotime($data['filter_date_end']));
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            // $html = 'FROM ' . $from . ' TO ' . $to;
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Organization');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Farmer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Farmer Email');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Comment');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'IP');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;

            foreach ($data['activities'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['organization']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['farmer_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['comment']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['ip']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['date_added']);

                ++$row;
            }

            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);


            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Farmer_Activity.xlsx';

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
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_sale_ordertransaction_excel($filter_data) {
        // echo "<pre>";print_r($filter_data);die;
        $this->load->library('excel');
        $this->load->library('iofactory');
        // $this->load->model('sale/transaction');
        // if($this->user->isAccountManager()) {
        // $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        // $results = $this->model_report_customer->getOrders($data);
        // }
        $this->load->model('sale/transactions');
        $results = $this->model_sale_transactions->getTransactions($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_ids'],
                'no_of_products' => $result['no_of_products'],
                'customer' => $result['firstname'] . ' ' . $result['lastname'],
                'total' => $this->currency->format($result['total']),
                'totalvalue' => $result['total'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }


        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_sale_ordertransaction_excel');
        //  echo "<pre>";print_r($data['orders']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Sale Order Transactions')->setDescription('none');

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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sale Order Transactions');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            if ($from != null) {
                $from = date('d-m-Y', strtotime($data['filter_date_added']));
                $html = 'Date Added ' . $from;
            }


            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order IDs');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'No. of Product');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Date Added');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['orders'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['no_of_products']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['totalvalue']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $Amount = $Amount + $result['totalvalue'];
                ++$row;
            }
            //  $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $Amount = $this->currency->format($Amount);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Grand Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Sale_order_Transactions.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_sale_ordertransaction_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function mail_download_saleorderproductmissing($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('sale/order');
        $this->load->model('account/order');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        //$rows = $this->model_report_sale->getOrdersCommission($data);
        //echo "<pre>";print_r($data);die;
        // $results = $this->model_report_sale->getstockoutOrders($data);
        $OrignalProducts = $this->model_report_sale->getstockoutOrdersAndProducts($data);

        $data['orders'] = [];
        // $data['torders'] = [];
        //echo "<pre>";print_r($results);die;
        // /* foreach ($results as $result) {
        //   $is_edited = $this->model_sale_order->hasRealOrderProducts($result['order_id']);
        //   if(!$is_edited) {
        //   continue;
        //   }
        //   $EditedProducts = $this->model_sale_order->getRealOrderProducts($result['order_id']);
        //   $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);
        //   foreach ($OrignalProducts as $OrignalProduct) {
        //   $present = false;
        //   foreach ($EditedProducts as $EditedProduct) {
        //   if(!empty($OrignalProduct['name']) && $OrignalProduct['name'] == $EditedProduct['name']) {
        //   $present = true;
        //   }
        //   }
        //   if(!$present && !empty($OrignalProduct['name'])) {
        //   $data['torders'][] = array(
        //   'store' => $result['store_name'],
        //   'model' => $OrignalProduct['model'],
        //   'product_name' => $OrignalProduct['name'],
        //   'unit' => $OrignalProduct['unit'],
        //   'product_qty' => $OrignalProduct['quantity'],
        //   );
        //   }
        //   }
        //   }
        //   foreach ($data['torders'] as $torders1) {
        //   $ex = false;
        //   foreach ($data['orders'] as $value1) {
        //   if($value1['model'] == $torders1['model'] && $value1['store'] == $torders1['store']) {
        //   $ex = true;
        //   }
        //   }
        //   if(!$ex) {
        //   $sum = 0;
        //   foreach ($data['torders'] as $key => $torders2) {
        //   if($torders1['model'] == $torders2['model'] && $torders1['store'] == $torders2['store']) {
        //   $sum += $torders2['product_qty'];
        //   unset($data['torders'][$key]);
        //   }
        //   }
        //   $torders1['product_qty'] = $sum;
        //   array_push($data['orders'], $torders1);
        //   }
        //   }
        //  */
        // foreach ($results as $result) {
        //     $is_edited = $this->model_sale_order->hasRealOrderProducts($result['order_id']);
        //     if ($is_edited) {
        //         //continue;
        //         $OrignalProducts = $this->model_sale_order->getRealOrderProducts($result['order_id']);
        //     } else {
        //         $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);
        //     }
        //     // $EditedProducts = $this->model_sale_order->getRealOrderProducts($result['order_id']);
        //     // $OrignalProducts = $this->model_sale_order->getOrderProducts($result['order_id']);
        //     /* echo "<pre>";print_r($OrignalProducts);
        //       echo "<pre>";print_r($EditedProducts);die; */
        //     foreach ($OrignalProducts as $OrignalProduct) {
        //         // $present = false;
        //         // foreach ($EditedProducts as $EditedProduct) {
        //         //     if(!empty($OrignalProduct['name']) && $OrignalProduct['name'] == $EditedProduct['name'] && $OrignalProduct['unit'] == $EditedProduct['unit']) {
        //         //         $present = true;
        //         //     }
        //         // }!$present &&
        //         if (!empty($OrignalProduct['name'])) {
        //             $data['torders'][] = [
        //                 'store' => $result['store_name'],
        //                 'model' => $OrignalProduct['model'],
        //                 'product_name' => $OrignalProduct['name'],
        //                 'unit' => $OrignalProduct['unit'],
        //                 'product_qty' => (float) ($OrignalProduct['quantity']),
        //             ];
        //         }
        //     }
        // }
        // //echo "<pre>";print_r($data['torders']);die;
        // foreach ($data['torders'] as $torders1) {
        //     $ex = false;
        //     foreach ($data['orders'] as $value1) {
        //         if ($value1['product_name'] == $torders1['product_name'] && $value1['store'] == $torders1['store'] && $value1['unit'] == $torders1['unit']) {
        //             $ex = true;
        //         }
        //     }
        //     if (!$ex) {
        //         $sum = (float) 0.00;
        //         foreach ($data['torders'] as $key => $torders2) {
        //             if ($torders1['product_name'] == $torders2['product_name'] && $torders1['store'] == $torders2['store'] && $torders1['unit'] == $torders2['unit']) {
        //                 $sum += (float) $torders2['product_qty'];
        //                 unset($data['torders'][$key]);
        //             }
        //         }
        //         $torders1['product_qty'] = (float) $sum;
        //         // ++$order_total;
        //         array_push($data['orders'], $torders1);
        //     }
        // }
        // $rows = $data['orders'];

        foreach ($OrignalProducts as $OrignalProduct) {
            $data['torders'][] = [
                'store' => $OrignalProduct['store_name'],
                'model' => $OrignalProduct['product_id'],
                'product_name' => $OrignalProduct['name'],
                'unit' => $OrignalProduct['unit'],
                'product_id' => $OrignalProduct['product_id'],
                'product_qty' => (float) $OrignalProduct['quantity'],
            ];
            ++$order_total;
        }
        $rows = $data['torders'];

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
            //  echo "<pre>";print_r($to);die;
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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Product ID');
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
            $filename = "stock_out_products.xlsx";
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="stock_out_products.xlsx"');
            // header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');



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
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['fromdate'] = $from;
            $maildata['todate'] = $to;
            $subject = $this->emailtemplate->getSubject('StockOut', 'StockOut_1', $maildata);
            $message = $this->emailtemplate->getMessage('StockOut', 'StockOut_1', $maildata);

            // $subject = "Consolidated Order Sheet";
            // $message = "Please find the attachment.  <br>";
            // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('stockout');

            if (strpos($email, "@") == false) {//if mail Id not set
                //$email = "sridivya.talluri@technobraingroup.com";
                $log = new Log('error.log');
                $this->log->write('Email Id not configured to send Stock out monthly report');
            }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
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

    public function download_sale_order_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');

        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_sale_order_excel');
        // echo "<pre>";print_r($data['orders']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Sales Orders')->setDescription('none');

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
            if ($data['orders']) {
                $sheet_subtitle = "";
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sales Orders');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A2', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Date Start');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Date End');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'No. Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'No. Products');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Tax');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['orders'] as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */
                $log->write('RESULT download_sale_order_excel');
                $log->write($result);
                $log->write('RESULT download_sale_order_excel');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['date_start']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['date_end']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['products']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['tax']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, round($result['tax_value'], 2));
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, round($result['totalvalue'], 2));
                $Amount = $Amount + $result['totalvalue'];
                ++$row;
            }
            // $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $Amount = round($Amount, 2);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Sale_order.xlsx';

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

    public function download_inventory_daily_prices_excel($data) {
        //	    echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Inventory Daily Prices')
                    ->setDescription('none');

            // Consolidated Customer Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Daily Prices');

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

            $sheet_title = 'Inventory Daily Prices';

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

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A:Z')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
            $cellid = 0;
            foreach ($data[0] as $h_key => $h_value) {
                // echo "<pre>";print_r($h_key);die;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cellid, 3, $h_key);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($cellid, 3)->applyFromArray($title);

                $cellid++;
            }
            $row = 4;

            foreach ($data as $b_key => $b_value) {
                $cellid = 0;
                foreach ($b_value as $bb_key => $bb_value) {
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

            $filename = 'Inventory_Daily_Prices.xlsx';

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

    public function download_customer_orderplaced_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);
        // if($this->user->isAccountManager()) {//Account manager code not added
        // $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        $results = $this->model_report_customer->getOrdersPlaced($data);
        // }

        foreach ($results as $result) {
            $data['customers'][] = [
                'customer' => $result['customer'],
                'company' => $result['company'],
                'email' => $result['email'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'orders' => $result['orders'],
                'products' => $result['products'],
                'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'total_value' => round($result['total'], 2),
                    // 'edit' => $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&customer_id='.$result['customer_id'].$url, 'SSL'),
            ];
        }

        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_customer_orderplaced_excel');
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

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Orders');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            if ($from != '01-01-1990') {
                $html = 'Customers Registered Between ' . $from . ' And ' . $to;
            } else
                $html = 'Customers Registered Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'No. Orders');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'No. Products');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Total');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['customers'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['orders']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['products']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['total_value']);
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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_customer_orderplaced_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_customer_boughtproducts_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);
        // if($this->user->isAccountManager()) {//not available for account managers
        // $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        $results = $this->model_report_customer->getboughtproductswithRealOrders($data);
        // }

        foreach ($results as $result) {
            $data['customers'][] = [
                'company' => $result['company'],
                // 'customer' => $result['customer'],not listed in query
                'name' => $result['name'],
                'unit' => $result['unit'],
                'quantity' => $result['quantity'],
            ];
        }

        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_customer_boughtproducts_excel');
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Bought Products')->setDescription('none');

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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Consumption Data ');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            if ($from != '01-01-1990') {
                $html = 'FROM ' . $from . ' TO ' . $to;
            } else
                $html = 'Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['customers'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['quantity']);
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

    public function download_vehicle_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('vehicles/vehicles');
        $this->load->model('vehicles/vehicles');
        $rows = $this->model_vehicles_vehicles->getVehicles($data);

        //echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Vehicles')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vehicles');
            $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray(['font' => ['bold' => true], 'color' => [
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Vehicle Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Make');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Model');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Registration Number');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Registration Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Registration Validity Upto');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Status');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Date Created');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['vehicle_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['make']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['model']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['registration_number']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($result['registration_date'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($result['registration_validity'])));

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['status'] ? 'Enabled' : 'Disabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="vehicles.xlsx"');
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

    public function download_customer_onboarded_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);
        // if($this->user->isAccountManager()) {//Account manager code not added
        // $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        $results = $this->model_report_customer->getCustomersOnboarded($data);
        // }

        foreach ($results as $result) {
            $data['customers'][] = [
                'customer' => $result['customer'],
                'company' => $result['company'],
                'order_id' => $result['order_id'],
                // 'order_date' => $result['date_added'],
                'order_date' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    // 'email' => $result['email'],
                    // 'customer_group' => $result['customer_group'],
                    // 'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                    // 'orders' => $result['orders'],
                    // 'products' => $result['products'],
                    // 'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                    //     // 'edit' => $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&customer_id='.$result['customer_id'].$url, 'SSL'),
            ];
        }

        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_customer_onboarded_excel');
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customers Onboarded')->setDescription('none');

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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers Onboarded');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            if ($from != '01-01-1990') {
                $html = 'Customers Onboarded Between ' . $from . ' And ' . $to;
            } else
                $html = 'Customers Onboarded Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Order_ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Order_Date');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Total');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['customers'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['order_date']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['total']);
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
            $filename = 'Customers_Onboarded.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_customers_onboarded_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_customer_unordered_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        $results = $this->model_report_customer->getCustomersUnordered($data);

        foreach ($results as $result) {
            $data['customers'][] = [
                'customer' => $result['customer'],
                'company' => $result['company'],
                'email' => $result['email'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'approved' => ($result['approved'] ? Yes : No),
            ];
        }

        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_unorderd_customer_excel');
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Unordered Customers')->setDescription('none');

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

            $objPHPExcel->getActiveSheet()->mergeCells('A1:F3');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unordered Customers');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            // $from = date('d-m-Y', strtotime($data['filter_date_start']));
            // $to = date('d-m-Y', strtotime($data['filter_date_end']));
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            // if ($from != '01-01-1990') {
            //     $html = 'Customers Onboarded Between ' . $from . ' And ' . $to;
            // } else
            //     $html = 'Customers Onboarded Till Date : ' . $to;
            $html = '';

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'E-Mail');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Approved');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Added');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['customers'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['email']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['approved']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['date_added']);
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
            $filename = 'Unordered_Customers.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_unordered_customers_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_feedback_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('sale/customer_feedback');
        // $rows = $this->model_sale_customer->getCustomers($data);
        $rows = $data['customer_feedbacks'];

        //  echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customers_Feedback')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers Feedback');
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Rating');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Feedback Type');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Comments');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Order_Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Raised On');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Accepted By');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Closed Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Closed Comments');

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
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
            // $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
            foreach ($rows as $result) {

                // $lfcr=$result['customer_name'].length
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer_name'] . PHP_EOL . $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['rating']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['feedback_type']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['comments']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['created_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['accepted_user']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['closed_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['closed_comments']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="customer_feedback.xlsx"');
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

    public function download_sale_order_receivables_excel($filter_data) {
        // echo "<pre>";print_r($filter_data);die;
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('sale/order_receivables');

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_customer_group'])) {
            $filter_customer_group = $this->request->get['filter_customer_group'];
        } else {
            $filter_customer_group = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }


        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_customer_group' => $filter_customer_group,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
        ];

        if ('' != $filter_customer  || '' != $filter_order_id || '' != $filter_customer_group) {
            // if ('' != $filter_customer || '' != $filter_company ) {
            // $order_total = $this->model_sale_transactions->getTotaltransactions($filter_data);
            $order_total_grandTotal = $this->model_sale_order_receivables->getTotalOrderReceivablesAndGrandTotal($filter_data);

            //    echo'<pre>';print_r($order_total_grandTotal['total']);exit;

            $order_total = $order_total_grandTotal['total'];
            $amount = $order_total_grandTotal['GrandTotal'];
            $results = $this->model_sale_order_receivables->getOrderReceivables($filter_data);
        } else {
            $order_total_grandTotal = null;
            $order_total = 0;
            $amount = 0;
            $results = null;
        }

        $this->load->model('sale/order');
        foreach ($results as $result) {
            // $amount=$amount+$result['total'];
            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            // echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'code' => $total['code'],
                    // 'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    'text' => $this->currency->format($total['value']),
                ];

                if ('total' == $total['code']) {
                    $result['total'] = $total['value'];
                }
            }
            if ($result['company']) {
                $result['company'] = ' (' . $result['company'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                // 'no_of_products' => $result['no_of_products'],
                'customer' => $result['firstname'] . ' ' . $result['lastname'],
                'company' => $result['company'],
                'total' => $this->currency->format($result['total']),
                'total_value' => ($result['total']),
                // 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'grand_total' => $this->currency->format($amount),
                'total_pages' => $totalPages,
                // o.paid,o.amount_partialy_paid
                'paid' => $result['paid'],
                'amount_partialy_paid_value' => $result['amount_partialy_paid'],
                'amount_partialy_paid' => $result['amount_partialy_paid'] ? $this->currency->format($result['amount_partialy_paid']) : '',
                'pending_amount' => $this->currency->format($result['total'] - $result['amount_partialy_paid']),
                'pending_amount_value' => ($result['total'] - $result['amount_partialy_paid']),
            ];
        }
        // echo'<pre>';print_r($data['orders']);exit;


        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_payment_receivables_excel');
        //  echo "<pre>";print_r($data['orders']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Payment Receivables')->setDescription('none');

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

            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Payment Receivables');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            // if ($from != null) {
            //     $from = date('d-m-Y', strtotime($data['filter_date_added']));
            //     $html = 'Date Added ' . $from;
            // }
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order IDs');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Paid Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Pending Amount');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['orders'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer'] . PHP_EOL . $result['company']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['total_value']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['amount_partialy_paid_value']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['pending_amount_value']);
                $Amount = $Amount + $result['pending_amount_value'];
                ++$row;
            }
            //  $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $Amount = $this->currency->format($Amount);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Grand Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Payments_Receivables.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_payment_receivables_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_report_payment_receivables_excel($filter_data) {
        // echo "<pre>";print_r($filter_data);die;
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('sale/order_receivables');

        // $order_total = $this->model_sale_transactions->getTotaltransactions($filter_data);

        $results = $this->model_sale_order_receivables->getOrderReceivables($filter_data);

        $this->load->model('sale/order');
        foreach ($results as $result) {
            // $amount=$amount+$result['total'];
            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            // echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'code' => $total['code'],
                    // 'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    'text' => $this->currency->format($total['value']),
                ];

                if ('total' == $total['code']) {
                    $result['total'] = $total['value'];
                }
            }
            if ($result['company']) {
                $result['company'] = ' (' . $result['company'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                // 'no_of_products' => $result['no_of_products'],
                'customer' => $result['firstname'] . ' ' . $result['lastname'],
                'company' => $result['company'],
                'total' => $this->currency->format($result['total']),
                'total_value' => ($result['total']),
                // 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'grand_total' => $this->currency->format($amount),
                'total_pages' => $totalPages,
                // o.paid,o.amount_partialy_paid
                'paid' => $result['paid'],
                'amount_partialy_paid_value' => $result['amount_partialy_paid'],
                'amount_partialy_paid' => $result['amount_partialy_paid'] ? $this->currency->format($result['amount_partialy_paid']) : '',
                'pending_amount' => $this->currency->format($result['total'] - $result['amount_partialy_paid']),
                'pending_amount_value' => ($result['total'] - $result['amount_partialy_paid']),
            ];
        }
        // echo'<pre>';print_r($data['orders']);exit;


        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_payment_receivables_excel');
        //  echo "<pre>";print_r($data['orders']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Payment Receivables')->setDescription('none');

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

            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Payment Receivables');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            // if ($from != null) {
            //     $from = date('d-m-Y', strtotime($data['filter_date_added']));
            //     $html = 'Date Added ' . $from;
            // }
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order IDs');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Paid Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Pending Amount');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['orders'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer'] . PHP_EOL . $result['company']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['total_value']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['amount_partialy_paid_value']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['pending_amount_value']);
                $Amount = $Amount + $result['pending_amount_value'];
                ++$row;
            }
            //  $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $Amount = $this->currency->format($Amount);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Grand Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Payments_Receivables.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_payment_receivables_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_sale_order_receivables_success_excel($filter_data) {
        // echo "<pre>";print_r($filter_data);die;
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->model('sale/order_receivables');

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }


        if (isset($this->request->get['filter_customer_group'])) {
            $filter_customer_group = $this->request->get['filter_customer_group'];
        } else {
            $filter_customer_group = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }


        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_customer_group' => $filter_customer_group,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
        ];

        // if ('' != $filter_customer || '' != $filter_company) {
        if ('' != $filter_customer  || '' != $filter_order_id || '' != $filter_customer_group) {

            // $order_total = $this->model_sale_transactions->getTotaltransactions($filter_data);
            $order_total_grandTotal = $this->model_sale_order_receivables->getTotalSuccessfulOrderReceivablesAndGrandTotal($filter_data);

            //    echo'<pre>';print_r($order_total_grandTotal['total']);exit;

            $order_total = $order_total_grandTotal['total'];
            $amount = $order_total_grandTotal['GrandTotal'];
            $results = $this->model_sale_order_receivables->getSuccessfulOrderReceivables($filter_data);
        } else {
            $order_total_grandTotal = null;
            $order_total = 0;
            $amount = 0;
            $results = null;
        }

        $this->load->model('sale/order');
        foreach ($results as $result) {
            // $amount=$amount+$result['total'];
            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            // echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'code' => $total['code'],
                        // 'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                        // 'text' => $this->currency->format($total['value']),
                ];

                if ('total' == $total['code']) {
                    $result['total'] = $total['value'];
                }
            }
            if ($result['company']) {
                $result['company'] = ' (' . $result['company'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer_id' => $result['customer_id'],
                'transaction_id' => $result['transaction_id'],
                // 'no_of_products' => $result['no_of_products'],
                'customer' => $result['firstname'] . ' ' . $result['lastname'],
                'company' => $result['company'],
                'total' => $this->currency->format($result['total']),
                'total_value' => ($result['total']),
                // 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'grand_total' => $this->currency->format($amount),
                'total_pages' => $totalPages,
                // o.paid,o.amount_partialy_paid
                'paid' => $result['paid'],
                'amount_partialy_paid_value' => $result['amount_partialy_paid'],
                'amount_partialy_paid' => $result['amount_partialy_paid'] ? $this->currency->format($result['amount_partialy_paid']) : '',
                // 'pending_amount' => $this->currency->format($result['total'] - $result['amount_partialy_paid']),
                'pending_amount' => $this->currency->format($result['amount_partialy_paid']>0?round(($result['total']-$result['amount_partialy_paid']),2):0),                
                'pending_amount_value' => ($result['amount_partialy_paid']>0?round(($result['total']-$result['amount_partialy_paid']),2):0),
                'paid_to' => $result['paid_to'],
            ];
        }
        // echo'<pre>';print_r($data['orders']);exit;


        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_payment_receivables_success_excel');
        //  echo "<pre>";print_r($data['orders']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Payments Received')->setDescription('none');

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

            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Payments Received');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
            // if ($from != null) {
            //     $from = date('d-m-Y', strtotime($data['filter_date_added']));
            //     $html = 'Date Added ' . $from;
            // }
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order IDs');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Paid');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Pending Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Transaction ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Paid To');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['orders'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer'] . PHP_EOL . $result['company']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, round($result['total_value'], 2));
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['amount_partialy_paid']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['paid']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, round($result['pending_amount_value'], 2));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['transaction_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['paid_to']);
                // $Amount = $Amount + $result['pending_amount_value'];
                ++$row;
            }
            //  $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $Amount = $this->currency->format($Amount);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Grand Total');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Payments_Received.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_payment_receivables_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function mail_consolidated_sale_order_excel($data) {
        // echo "<pre>";print_r($data);die;

        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Delivery Sheet')->setDescription('none');

            // Consolidated Product Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Sales Orders');

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

            $sheet_title = 'Sales Orders';
            $sheet_subtitle = 'To be delivered on: ' . $data[0]['delivery_date'];

            // $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            // $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);
            // $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);
            // $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
            //         'rgb' => '51AB66',
            // ]]);

            $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );

            $styleOrderReceived = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFFF00')
                )
            );

            $styleOrderInTransit = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00ff00')
                )
            );

            $styleOrderProcessing = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFA500')
                )
            );
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);

            // $objPHPExcel->getActiveSheet()->getStyle('A1:D')->applyFromArray($styleArray);
            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
                // $objPHPExcel->getActiveSheet()->getStyle($columnID)->applyFromArray($styleArray);
            }

            // $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            // $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
            // $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
            // $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);


            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Vendor');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Customer ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Date Added');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Delivery Timeslot');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);

            $row = 5;
            foreach ($data as $order) {
                // echo "<pre>";print_r($order);die;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $order['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['vendor_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $order['customer'] . PHP_EOL . $order['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $order['status']);
                if ($order['status'] == "Order Recieved") {
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->applyFromArray($styleOrderReceived);
                } else if ($order['status'] == "In Transit") {
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->applyFromArray($styleOrderInTransit);
                } else if ($order['status'] = "Order Processing") {
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->applyFromArray($styleOrderProcessing);
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $order['total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $order['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $order['delivery_date_value']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $order['delivery_timeslot']);
                ++$row;
            }
            // $objPHPExcel->getActiveSheet()->getStyle('A1:H' . $row)->applyFromArray($styleArray);
            // $objPHPExcel->getActiveSheet()->getStyle('A1:H' . $row)->getAlignment()->setWrapText(true);



            $objPHPExcel->setActiveSheetIndex(0);

            $deliveryDate = $data[0]['delivery_date'];
            $filename = 'Sales_Orders_' . $deliveryDate . '.xlsx';

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
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file); // Delete the given file
            }
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['deliverydate'] = $deliveryDate;
            $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            $subject = "Sales Orders : " . $deliveryDate;
            $message = str_replace('consolidated order sheet', 'sales orders', $message);
            $message = str_replace('Consolidated Order Sheet', 'Sales Orders', $message);
            // $message = "Please find the attachment.  <br>";
            // $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('consolidatedorder');

            if (strpos($email, "@") == false) {//if mail Id not set in define.php
                $email = "sridivya.talluri@technobraingroup.com";
            }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
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
            echo "<pre>";
            print_r($e);
            ;

            exit;
            return;
        }
    }

    public function download_customer_wallet_excel($data) {


        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('sale/customer');

        // echo "<pre>";print_r($data);die;
        // if ($this->user->isAccountManager()) {
        //     $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        $results = $this->model_sale_customer->getAllCredits($data);
        // }

        foreach ($results as $result) {
            $transaction_ID = "";
            if (isset($result['transaction_id']) && $result['transaction_id'] != "") {
                $transaction_ID = '#Transaction ID ' . $result['transaction_id'];
            }
            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            }
            $data['customers'][] = [
                // 'customer_id' => $result['customer_id'],
                'name' => $result['name'],
                'company' => $result['company_name'],
                // 'email' => $result['email'],
                'amount' => $result['amount'],
                'description' => $result['description'] . ' ' . $transaction_ID,
                // 'transaction_id' => $result['transaction_id'],
                'order_id' => $result['order_id'],
                'customer_credit_id' => $result['customer_credit_id'],
                // 'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_added_time' => date($this->language->get('time_format'), strtotime($result['date_added'])),
            ];
        }

        $log = new Log('error.log');
        $log->write($data['customers'] . 'download_customer_wallet_excel');
        //   echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Wallet')->setDescription('none');

            //PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);

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

            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers Wallet ');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            // $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Description');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Date Added');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Time Added');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);

            // Fetching the table data
            $row = 4;
            $Amount = 0;
            foreach ($data['customers'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name'] . PHP_EOL . $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['amount']);
                if ($result['amount'] > 0) {

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Credit');
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Debit');
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['description']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['date_added_time']);
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
            $filename = 'Customers_Wallet.xlsx';

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

    public function mail_customer_unpaid_order_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $rows = $this->model_sale_order->getUnpaidOrders($data);
        $sendingDate = $data['sendingDate'];

        // echo "<pre>";print_r($rows);exit;
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // echo "<pre>";print_r(strtotime($sendingDate));
                // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                $result['ageing'] = round($datediff / (60 * 60 * 24));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="unpaid_orders_sheet.xlsx"');
            // header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');

            $filename = 'KB_Unpaid_Order_Sheet_POD_' . $sendingDate . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp';
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file
                }
            }
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['deliverydate'] = $sendingDate;
            // $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            // if ($name != "") {
            $subject = 'Unpaid Orders';
            // }
            // $subject = "Consolidated Order Sheet";
            $message = str_replace('consolidated', 'unpaid', $message);
            $message = str_replace('Consolidated', 'Unpaid', $message);
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('financeteam');

            if (strpos($email, "@") == false) {//if mail Id not set in define.php
                $email = "sridivya.talluri@technobraingroup.com";
            }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->addAttachment($filepath);
            $mail->send();
            #endregion
            exit;
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "<pre>";
            print_r($e->getFile());
            echo "<pre>";
            print_r($e->getCode());
            echo "<pre>";
            print_r($e->getLine());

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

    public function download_customer_financial_statement_excel_old($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');

        $results = $this->model_report_customer->getCustomerFinancialStatementByID($data);
        $result_group = $this->model_report_customer->getCustomerFinancialStatementByGroup($data);

        $this->load->model('sale/order');

        $data['customers'] = [];

        foreach ($results as $result) {
            // $products_qty = 0;
            // if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
            //     $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
            // } else {
            //     $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
            // }
            // $sub_total = 0;
            // $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
            // // echo "<pre>";print_r($totals);die;
            // foreach ($totals as $total) {
            //     if ('total' == $total['code']) {
            //         $sub_total = $total['value'];
            //         break;
            //     }
            // }
            // if ($result['paid'] == 'N') {
            //     //check transaction Id Exists are not// if exists, it is paid order,
            //     $transcation_id = $this->model_sale_order->getOrderTransactionId($result['order_id']);
            //     if (!empty($transcation_id)) {
            //         $result['paid'] = 'Paid';
            //         $result['amountpaid'] = $sub_total;
            //         $result['pendingamount'] = $sub_total - $result['amountpaid'];
            //     } else {
            //         $result['paid'] = 'Pending';
            //         $result['amountpaid'] = 0;
            //         $result['pendingamount'] = $sub_total - $result['amountpaid'];
            //     }
            // } else if ($result['paid'] == 'P') {
            //     // $result['paid']=$result['paid'].'(Amount Paid :'.$result['amount_partialy_paid'] .')';
            //     $result['paid'] = 'Few Amount Paid';
            //     $result['amountpaid'] = $result['amount_partialy_paid'];
            //     $result['pendingamount'] = $sub_total - $result['amountpaid'];
            // } else if ($result['paid'] == 'Y') {
            //     // $result['paid']=$result['paid'].'(Amount Paid :'.$result['amount_partialy_paid'] .')';
            //     $result['paid'] = 'Paid';
            //     $result['amountpaid'] = $sub_total;
            //     $result['pendingamount'] = $sub_total - $result['amountpaid'];
            // }

            if ($result['credit_debit'] == 'Credit') {
                $result['reference_document'] = '';
                $result['total'] = '-' . $result['total'];
                $result['updated_total'] = '-' . $result['updated_total'];
            } else {
                $result['reference_document'] = 'KB' . $result['order_id'];
            }
            $data['orders'][] = [
                'company' => $result['company_name'],
                'fiscal_year' => $result['fiscal_year'],
                'posting_date' => $result['posting_date'],
                'reference_document' => $result['reference_document'],
                'Document_type' => $result['Document_type'],
                'credit_debit' => $result['credit_debit'],
                'currency' => $result['currency'],
                'customer' => $result['customer'],
                'email' => $result['email'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'order_id' => $result['order_id'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'updated_total' => number_format($result['updated_total'], 2),
                    // 'paid'=> $result['paid'],
                    // 'amountpaid'=> number_format($result['amountpaid'],2),
                    // 'pendingamount'=> number_format($result['pendingamount'],2),
            ];
        }


        $data['years'] = $this->model_report_customer->getYears($data);

        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_customer_financial_statement_excel');
        // echo "<pre>";print_r($data['customers']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Financial Statement')->setDescription('none');

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
            if ($data['orders']) {
                $sheet_subtitle = 'Company Name : ' . $data['orders'][0]['company'];
                // $sheet_subtitle_sap = $data['customers'][0]['SAP_customer_no'];
                // $order_start_date = $data['customers'][0]['date_added'];
            } else {
                $sheet_subtitle = $sheet_subtitle_sap = '';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Financial Statement');
            // $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SAP Customer Number');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            // $objPHPExcel->getActiveSheet()->setCellValue('E1', $sheet_subtitle_sap);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            // if (!empty($data['filter_date_start']))
            //     $from = date('d-m-Y', strtotime($data['filter_date_start']));
            // else
            //     $from = str_replace("/", "-", $order_start_date);
            // try{
            // if(strpos($data['filter_date_start'], '1990'))
            // $from=$order_start_date ;
            // $from=str_replace("/","-",$order_start_date);
            // }
            // catch(Exception $ex)
            // {
            // }
            // $to = date('d-m-Y', strtotime($data['filter_date_end']));
            // $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            // $html = 'FROM ' . $from . ' TO ' . $to;
            // $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            // $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            // $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            // $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            $start = 4;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $start, 'Fiscal Year');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start, 'Posting Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start, 'Document Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start, 'Reference Document');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start, 'Debit/Credit');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start, 'Currency Key');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $start, 'Amount In Local Currency');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $start, 'Text');


            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $start)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, $start)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, $start)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, $start)->applyFromArray($title);
            //   echo "<pre>";print_r($data['years']);die;
            // Fetching the table data
            $row = 5;
            $Amount = 0;
            $PendingAmountTotal = 0;
            foreach ($data['orders'] as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */


                $log->write('RESULT download_customer_financial_statement_excel');
                $log->write($result);
                $log->write('RESULT download_customer_financial_statement_excel');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['fiscal_year']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['posting_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['Document_type']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['reference_document']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['credit_debit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['currency']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['updated_total']);

                // $Amount = $Amount + $result['subtotalvalue'];
                // $PendingAmountTotal = $PendingAmountTotal + $result['pendingamountvalue'];
                ++$row;
            }
            // $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            // $PendingAmount = str_replace('KES', ' ', $this->currency->format($PendingAmount));
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $Amount);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $PendingAmountTotal);
            //Starttttttttttttt
            // Individual customer orders
            $sheetIndex = 1;
            $objPHPExcel->createSheet($sheetIndex);
            $objPHPExcel->setActiveSheetIndex($sheetIndex);
            $worksheetName = 'Summary';
            // A fatal error is thrown for worksheet titles with more than 30 character
            if (strlen($worksheetName) > 30) {
                $worksheetName = substr($worksheetName, 0, 27) . '...';
            }

            $objPHPExcel->getActiveSheet()->setTitle($worksheetName);
            $sheet_title = $worksheetName;

            $row = 3;
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');

            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary  ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Sum of amount in local currency');
            //$objPHPExcel->g   etActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, 'Credit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 2, 'Debit ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, 'Grand TOtal');

            $objPHPExcel->getActiveSheet()->getStyle('A1:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            // $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // foreach (range('A', 'L') as $columnID) {
            //     if($columnID!='B')
            //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            //             ->setAutoSize(true);
            // }
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);

            $Amount = 0;

            // $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
            // $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false);
            foreach ($data['years'] as $year) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $year['year']);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
                $row++;
                $jan = $feb = $mar = $apr = $may = $jun = $jul = $aug = $sep = $oct = $nov = $dec = 0;
                $jan_value = $feb_value = $mar_value = $apr_value = $may_value = $jun_value = $jul_value = $aug_value = $sep_value = $oct_value = $nov_value = $dec_value = 0;
                foreach ($result_group as $result) {
                    // echo "<pre>";print_r($year['year'] );die;
                    // echo "<pre>";print_r($result);die;

                    if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '1') {
                        if ($jan == 0) {
                            $jan = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Jan');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $jan_value = $jan_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $jan_value = $jan_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $jan_value);

                        $Amount = $Amount + $result['updated_total'];
                        // $PendingAmountTotal = $PendingAmountTotal + $result['pendingamountvalue'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '2') {
                        if ($feb == 0) {
                            $feb = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Feb');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $feb_value = $feb_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $feb_value = $feb_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $feb_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '3') {
                        // echo "<pre>";print_r($result);die;

                        if ($mar == 0) {
                            $mar = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Mar');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $mar_value = $mar_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $mar_value = $mar_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $mar_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '4') {
                        if ($apr == 0) {
                            $apr = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Apr');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $apr_value = $apr_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $apr_value = $apr_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $apr_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '5') {
                        if ($may == 0) {
                            $may = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'May');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $may_value = $may_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $may_value = $may_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $may_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '6') {
                        if ($jun == 0) {
                            $jun = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Jun');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $jun_value = $jun_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $jun_value = $jun_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $jun_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '7') {
                        if ($jul == 0) {
                            $jul = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Jul');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $jul_value = $jul_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $jul_value = $jul_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $jul_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '8') {
                        if ($aug == 0) {
                            $aug = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Aug');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $aug_value = $aug_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $aug_value = $aug_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $aug_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '9') {
                        if ($sep == 0) {
                            $sep = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Sep');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $sep_value = $sep_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $sep_value = $sep_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sep_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '10') {
                        if ($oct == 0) {
                            $oct = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Oct');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $oct_value = $oct_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $oct_value = $oct_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $oct_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year && $result['fiscal_month'] == '11') {
                        if ($nov == 0) {
                            $nov = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Nov');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $nov_value = $nov_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $nov_value = $nov_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $nov_value);

                        $Amount = $Amount + $result['updated_total'];
                    } else if ($result['fiscal_year'] == $year['year'] && $result['fiscal_month'] == '12') {
                        if ($dec == 0) {
                            $dec = 1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Dec');
                        }
                        if ($result['credit_debit'] == 'Credit') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['updated_total']);
                            $dec_value = $dec_value + $result['updated_total'];
                        } else {//debit
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                            $dec_value = $dec_value + $result['updated_total'];
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $dec_value);

                        $Amount = $Amount + $result['updated_total'];
                    }
                    $row++;
                }
            }
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setOutlineLevel(1);
            // // $objPHPExcel->getension('A')->setCollapsed(true);
            //     $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setVisible(false);
            //end region
            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $log->write($data['orders'][0]['customer'] . 'RESULT2 download_customer_statement_excel');
            $log->write('download_customer_statement_excel');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Customer_financial_statement_' . $data['orders'][0]['customer'] . '.xlsx';

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

    public function download_customer_financial_statement_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/customer');
        $results = $this->model_report_customer->getCustomerFinancialStatementByID($data);
        $result_group1 = $this->model_report_customer->getCustomerFinancialStatementByGroup1($data);
        $result_group2 = $this->model_report_customer->getCustomerFinancialStatementByGroup2($data);

        // echo "<pre>";print_r($result_group1);
        // echo "<pre>";print_r($result_group2);

        $this->load->model('sale/order');
        $data['customers'] = [];
        foreach ($results as $result) {
            if ($result['credit_debit'] == 'Credit') {
                $result['reference_document'] = '';
                $result['total'] = '-' . $result['total'];
                $result['updated_total'] = '-' . $result['updated_total'];
            } else {
                $result['reference_document'] = 'KB' . $result['order_id'];
            }
            $data['orders'][] = [
                'company' => $result['company_name'],
                'fiscal_year' => $result['fiscal_year'],
                'posting_date' => $result['posting_date'],
                'reference_document' => $result['reference_document'],
                'Document_type' => $result['Document_type'],
                'credit_debit' => $result['credit_debit'],
                'currency' => $result['currency'],
                'customer' => $result['customer'],
                'email' => $result['email'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'order_id' => $result['order_id'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                // 'updated_total' => number_format($result['updated_total'], 2),
                'updated_total' => round($result['updated_total'], 2),
                    // 'paid'=> $result['paid'],
                    // 'amountpaid'=> number_format($result['amountpaid'],2),
                    // 'pendingamount'=> number_format($result['pendingamount'],2),
            ];
        }

        // $data['years'] = $this->model_report_customer->getYears($data);
        $log = new Log('error.log');
        $log->write($data['orders'] . 'download_customer_financial_statement_excel');
        try {
            // set appropriate timeout limit
            set_time_limit(3500);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customer Financial Statement')->setDescription('none');
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
                        'rgb' => '4390df',
                    ],
                ],
            ];
            if ($data['orders']) {
                $sheet_subtitle = 'Company Name : ' . $data['orders'][0]['company'];
            } else {
                $sheet_subtitle = $sheet_subtitle_sap = '';
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer Financial Statement');
            // $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SAP Customer Number');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            // $objPHPExcel->getActiveSheet()->setCellValue('E1', $sheet_subtitle_sap);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            // $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            // $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            // $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            $start = 4;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $start, 'Fiscal Year');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start, 'Posting Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start, 'Document Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start, 'Reference Document');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start, 'Debit/Credit');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start, 'Currency Key');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $start, 'Amount In Local Currency');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $start, 'Text');


            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, $start)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $start)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, $start)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, $start)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, $start)->applyFromArray($title);
            //   echo "<pre>";print_r($data['years']);die;
            // Fetching the table data
            $row = 5;
            // $Amount = 0;
            // $PendingAmountTotal = 0;
            foreach ($data['orders'] as $result) {

                $log->write('RESULT download_customer_financial_statement_excel');
                $log->write($result);
                $log->write('RESULT download_customer_financial_statement_excel');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['fiscal_year']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['posting_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['Document_type']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['reference_document']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['credit_debit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['currency']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['updated_total']);

                // $Amount = $Amount + $result['subtotalvalue'];
                // $PendingAmountTotal = $PendingAmountTotal + $result['pendingamountvalue'];
                ++$row;
            }
            // $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            // $PendingAmount = str_replace('KES', ' ', $this->currency->format($PendingAmount));
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, $row)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $Amount);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $PendingAmountTotal);
            //Starttttttttttttt
            // Summary
            $sheetIndex = 1;
            $objPHPExcel->createSheet($sheetIndex);
            $objPHPExcel->setActiveSheetIndex($sheetIndex);
            $worksheetName = 'Summary';
            // A fatal error is thrown for worksheet titles with more than 30 character
            if (strlen($worksheetName) > 30) {
                $worksheetName = substr($worksheetName, 0, 27) . '...';
            }

            $objPHPExcel->getActiveSheet()->setTitle($worksheetName);
            $sheet_title = $worksheetName;

            $row = 3;
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            // $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary  ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Year');
            //$objPHPExcel->g   etActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');
            // echo "<pre>";print_r($data['customers']);die;

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, 'Month');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 2, 'Credit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, 'Debit ');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 2, 'Grand Total');

            // $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            // $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // foreach (range('A', 'L') as $columnID) {
            //     if($columnID!='B')
            //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            //             ->setAutoSize(true);
            // }
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(25);

            $Amount = 0;

            // $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
            // $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false);
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setOutlineLevel(1);
            // // $objPHPExcel->getension('A')->setCollapsed(true);
            //     $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setVisible(false);


            foreach ($result_group2 as $result2) {
                $exist = false;
                foreach ($result_group1 as $result1) {
                    if ($result1['fiscal_year'] == $result2['fiscal_year'] && $result1['fiscal_month'] == $result2['fiscal_month']) {
                        $exist = true;
                    }
                }
                if ($exist == false) {

                    $result_group1[] = [
                        'fiscal_year' => $result2['fiscal_year'],
                        'fiscal_month' => $result2['fiscal_month'],
                        'total' => 0,
                        'updated_total' => 0,
                        'wallet_total' => -$result2['updated_total'],
                    ];
                    // echo "<pre>";print_r( $result_group1);die;
                }
            }


            foreach ($result_group1 as $result1) {
                $wallet_Total = $result1['wallet_total'];
                foreach ($result_group2 as $result2) {
                    if ($result1['fiscal_year'] == $result2['fiscal_year'] && $result1['fiscal_month'] == $result2['fiscal_month']) {
                        $wallet_Total = $result2['updated_total'];
                        // echo "<pre>";print_r($wallet_Total);die;
                    }
                }

                $data['summary'][] = [
                    'fiscal_year' => $result1['fiscal_year'],
                    'fiscal_month' => $result1['fiscal_month'],
                    'total' => $result1['total'],
                    'updated_total' => $result1['updated_total'],
                    'wallet_total' => -$wallet_Total,
                ];

                // echo "<pre>";print_r($wallet_Total);
            }
            // $sort_order = [];
            // $sort_order[0]='fiscal_year';
            // $sort_order[$key]='fiscal_month';
            // echo "<pre>";print_r($result_group1);
            $fiscal_year = array_column($data['summary'], 'fiscal_year');
            $fiscal_month = array_column($data['summary'], 'fiscal_month');
            array_multisort($fiscal_year, SORT_ASC, $fiscal_month, SORT_ASC, $data['summary']);

            // echo "<pre>";print_r($data['summary']);die;

            $Amount_credit = 0;
            $Amount_debit = 0;
            $Amount_grand = 0;
            // $PendingAmountTotal = 0;
            foreach ($data['summary'] as $result) {

                $log->write('SUMMARY download_customer_financial_statement_excel');
                $log->write($result);
                $log->write('SUMMARY download_customer_financial_statement_excel');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['fiscal_year']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date("F", mktime(0, 0, 0, $result['fiscal_month'], 10)));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['wallet_total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['updated_total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['updated_total'] + $result['wallet_total']);

                $Amount_credit = $Amount_credit + $result['wallet_total'];
                $Amount_debit = $Amount_debit + $result['updated_total'];
                $Amount_grand = $Amount_grand + $result['wallet_total'] + $result['updated_total'];
                // $PendingAmountTotal = $PendingAmountTotal + $result['pendingamountvalue'];
                ++$row;
            }
            // $Amount_credit = str_replace('KES', ' ', $this->currency->format($Amount_credit));
            $Amount_credit = ($Amount_credit);
            $Amount_debit = ($Amount_debit);
            $Amount_grand = ($Amount_grand);
            // $PendingAmount = str_replace('KES', ' ', $this->currency->format($PendingAmount));
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Grand Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $Amount_credit);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $Amount_debit);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $Amount_grand);

            $objPHPExcel->getActiveSheet()->getStyle(2, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle(3, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle(4, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //end region
            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $log->write($data['orders'][0]['customer'] . 'RESULT2 download_customer_statement_excel');
            $log->write('download_customer_statement_excel');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Customer_financial_statement_' . $data['orders'][0]['customer'] . '.xlsx';

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

    public function download_consolidated_order_sheet_excel_category($data) {
        // echo "<pre>";print_r($data);die;

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

            $sheet_title = 'Consolidated Products By Category';
            $sheet_subtitle = 'To be delivered on: ' . $data['orders'][0]['delivery_date'];

            // $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            // $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
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
            // $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);

            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Category');

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
            foreach ($data['uniquecategory_ids'] as $cat) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Category : ' . $cat['category_name']);
                //merge row
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                ++$row;
                foreach ($data['products'] as $product) {
                    if ($product['product_category_id'] == $cat['category_id']) {
                        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['product_category']);

                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, '');
                        ++$row;
                    }
                }
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $row)->getAlignment()->setWrapText(true);

            // Individual customer orders
            $sheetIndex = 1;
            foreach ($data['orders'] as $order) {
                $objPHPExcel->createSheet($sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);

                $worksheetName = $order['company_name'] ?: $order['firstname'] . ' ' . $order['lastname'];

                // A fatal error is thrown for worksheet titles with more than 30 character
                if (strlen($worksheetName) > 30) {
                    $worksheetName = substr($worksheetName, 0, 27) . '...';
                }

                $objPHPExcel->getActiveSheet()->setTitle($worksheetName);

                $sheet_title = $worksheetName;
                $sheet_subtitle_order = 'Order #' . $order['order_id'];
                $sheet_subtitle = $order['shipping_address'];
                // $sheet_subtitle_1 = $order['comment'];
                //commented it , because as per the request it shoulb be shown after products and it may change
                //instead of removing code
                //take new variable
                $sheet_subtitle_1 = "";
                $sheet_subtitle_1_new = $order['comment'];

                // $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                // $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date of Delivery : ' . $order['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('B1', $order['delivery_date']);
                //$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Delivery Timeslot');
                //$objPHPExcel->getActiveSheet()->setCellValue('D1', $order['delivery_timeslot']);


                $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Order Status');
                $objPHPExcel->getActiveSheet()->setCellValue('D3', $order['status']);
                // $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Delivery Timeslot :' . $order['delivery_timeslot']);


                $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle_order);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $sheet_title);
                $objPHPExcel->getActiveSheet()->setCellValue('A4', $sheet_subtitle);
                $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Delivery Timeslot :' . $order['delivery_timeslot']);
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
                $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);

                $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                        'rgb' => '51AB66',
                ]]);
                $row = 7;
                $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
                $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
                $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');

                if ($sheet_subtitle_1 != "" && $sheet_subtitle_1 != null) {//this if condition not changing
                    $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');
                    $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Order Note : ' . $sheet_subtitle_1);
                    $row = 6;
                    $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Product Note');
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, 'Product Name');
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Produce Type');

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, 'Quantity');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, 'UOM ');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Product Note');
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'Source');
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 5)->applyFromArray($title);
                    // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 5)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
                    // //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
                }

                $objPHPExcel->getActiveSheet()->getStyle('A1:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                // foreach (range('A', 'L') as $columnID) {
                //     if($columnID!='B')
                //     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                //             ->setAutoSize(true);
                // }
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(11);
                $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(25);
                // $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(25);
                foreach ($order['categories'] as $cat) {

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Category : ' . $cat['category_name']);
                    //merge row
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);

                    ++$row;

                    foreach ($order['products'] as $product) {
                        if ($product['category_id'] == $cat['category_id']) {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $product['name']);
                            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['produce_type']);

                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['quantity']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['unit']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $product['product_note']);
                            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '');

                            ++$row;
                        }
                    }
                }
                if ($sheet_subtitle_1_new != "" && $sheet_subtitle_1_new != null && $row > 6) {
                    // $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Order Note : ' . $sheet_subtitle_1_new);
                    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $sheet_subtitle_1_new);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)->applyFromArray(['font' => ['bold' => true], 'color' => [
                            'rgb' => '51AB66',
                    ]]);
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:D' . $row)->getAlignment()->setWrapText(true);

                ++$sheetIndex;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $deliveryDate = $data['orders'][0]['delivery_date'];
            $filename = 'KB_Order_Sheet_Category' . $deliveryDate . '.xlsx';

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

    public function download_missing_order_products_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Missing Order Products Sheet')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Missing Order Products Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Product');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Missing quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Delivery Time Slot');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;

            $i = 1;
            foreach ($data as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['quantity_required']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['delivery_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['delivery_timeslot']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="MissingOrderProductsSheet.xlsx"');
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

    public function download_missing_order_products_excel_report($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Missing Order Products Sheet')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F3');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Missing Order Products Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Product');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Missing quantity');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Delivery Date');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Delivery Time Slot');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 5;

            $i = 1;
            foreach ($data as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['quantity_required']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['delivery_date']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['delivery_timeslot']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="MissingOrderProductsSheet.xlsx"');
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

    public function download_customer_order_monthly_average_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customers Monthly Average')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers Monthly Average order value');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Payment Terms');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Monthly Average');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;

            $i = 1;
            foreach ($data as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['customer_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['payment_terms']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['monthly_average']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="Customer_Orders_Monthly Average.xlsx"');
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

    public function download_categorypricelist_excel($data, $filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('catalog/vendor_product');

        // $rows = $this->model_catalog_vendor_product->getProducts("");
        $rows = $data;

        // echo "<pre>";print_r($data);die;


        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Price Category :' . $filter_data['filter_category_price'])->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Price Category : ' . $filter_data['filter_category_price']);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Category Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['category_price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['category_price_status'] ? 'Enabled' : 'Disabled');
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="category_price_list.xlsx"');
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

    public function download_categorypricelist_item_excel($data, $filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('catalog/vendor_product');

        // $rows = $this->model_catalog_vendor_product->getProducts("");
        $rows = $data;

        // echo "<pre>";print_r($data);die;


        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Price Category :' . $filter_data['filter_category_price'])->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:G2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Price Categories of Item :' . $filter_data['filter_name']);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Category');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['price_category']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['status']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="category_price_list_of_Item.xlsx"');
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

    public function download_product_wastage_excel($filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('inventory/wastage');
        $this->load->model('inventory/inventory_wastage');

        $rows = $this->model_inventory_inventory_wastage->getProductsByGroup($filter_data);

        // echo "<pre>";print_r($filter_data);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Product Wastage Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products Wastage Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($filter_data['filter_date_added']));
            $to = date('d-m-Y', strtotime($filter_data['filter_date_added_to']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $html = 'FROM ' . $from . ' TO ' . $to;
            if (isset($filter_data['filter_date_added']) && isset($filter_data['filter_date_added_to'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', '');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Vendor Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Date');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Wastage Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Avg. Buying Price');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['wastage_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, ($result['avg_buying_price'] == null ? 'NA' : round($result['avg_buying_price'], 2)));

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="product_wastage_report.xlsx"');
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

    public function download_product_wastage_excel_consolidated($filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('inventory/wastage');
        $this->load->model('inventory/inventory_wastage');

        $rows = $this->model_inventory_inventory_wastage->getProductsByGroup($filter_data);

        // echo "<pre>";print_r($filter_data);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Product Wastage Consolidated Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products Wastage Consolidated Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($filter_data['filter_date_added']));
            $to = date('d-m-Y', strtotime($filter_data['filter_date_added_to']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            $html = 'FROM ' . $from . ' TO ' . $to;
            if (isset($filter_data['filter_date_added']) && isset($filter_data['filter_date_added_to'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', '');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Vendor Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Unit');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Wastage Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Avg. Buying Price');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['wastage_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($result['avg_buying_price'] == null ? 'NA' : round($result['avg_buying_price'], 2)));

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="product_wastage_consolidated_report.xlsx"');
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

    public function download_product_wastage_excel_list($filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('inventory/wastage');
        $this->load->model('inventory/inventory_wastage');

        $rows = $this->model_inventory_inventory_wastage->getProducts($filter_data);

        // echo "<pre>";print_r($filter_data);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Product Wastage Report')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products Wastage Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($filter_data['filter_date_added']));
            $to = date('d-m-Y', strtotime($filter_data['filter_date_added_to']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            if (isset($filter_data['filter_date_added']) && isset($filter_data['filter_date_added_to'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', '');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Store ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Unit');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Wastage Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Date Added');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Time');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Added By');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Cumulative Wastage');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Avg. Buying Price');

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

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['wastage_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($result['date_added'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('time_format'), strtotime($result['date_added'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['added_by_user']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['cumulative_wastage']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['avg_buying_price'] ?? 'NA');

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="product_wastage_list.xlsx"');
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

    public function download_product_consumption_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/product');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);
        // if($this->user->isAccountManager()) {//not available for account managers
        // $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        $results = $this->model_report_product->getproductsconsumption($data);
        // }

        $this->load->model('sale/order');
        if (is_array($results) && count($results) > 0) {

            foreach ($results as $result) {
                $data['products'][] = [
                    // 'company' => $result['company'],
                    'order_date' => $result['date_added'],
                    'order_id' => $result['order_id'],
                    'customer' => $result['customer'],
                    'customer_status' => $result['customer_status'] == 0 ? 'Disabled' : 'Enabled',
                    'name' => $result['name'],
                    'unit' => $result['unit'],
                    'quantity' => $result['quantity'],
                    'status' => $result['status'],
                    'payment_terms' => $result['payment_terms'],
                ];
            }
        }

        $log = new Log('error.log');
        // $log->write($data['products'] . 'download_product_consumption_excel');
        // echo "<pre>";print_r($data['products']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Products Consumption')->setDescription('none');

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

            $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            // $to = date('d-m-Y', strtotime($data['filter_date_end']));
            if ($from != '01-01-1970') {
                $html = 'ON ' . $from; //. ' TO ' . $to
            }
            //  else
            //     $html = 'Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Product Consumption Data ');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Order Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Customer Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Payment Terms');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Status');
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
            $Amount = 0;
            foreach ($data['products'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['payment_terms']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['status']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Products_Consumption.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_products_consumption_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_missing_products_summary_excel_report($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Missing Order Products Sheet')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F3');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Missing Products Summary');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Product Store ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Missing Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Revenue Lost');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Delivery Date');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Delivery Time Slot');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 5;

            $i = 1;
            foreach ($data as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['quantity_required']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['total']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['delivery_date']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['delivery_timeslot']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="MissingProductsSummary.xlsx"');
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

    public function download_product_received_sold_excel($filter_data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('inventory/wastage');
        $this->load->model('inventory/product_received_sold');

        $results = $this->model_inventory_product_received_sold->getProductsReceivedSold($filter_data);
        // echo '<pre>';print_r($results);die;


        foreach ($results as $result) {



            $data['products'][] = [
                // 'product_store_id' => $result['product_id'],
                // 'product_id' => $result['product_id'],
                'name' => $result['name'], //product_name
                'unit' => $result['unit'],
                'procured_qty' => $result['procured_qty'],
                'rejected_qty' => $result['rejected_qty'],
                'sold_qty' => $result['quantity'],
                'revenue' => round($result['revenue'], 2),
                'priceperItem' => $result['priceperItem'],
                'Totalprice' => $result['Totalprice'],
                    // 'date_added' => $result['date_added'],
                    // 'added_by_user' => $result['added_by_user'],
                    // 'cumulative_wastage' => $result['cumulative_wastage'],
                    // 'date_added' => $result['date_added'],
            ];
        }




        $rows = $data['products'];

        // echo "<pre>";print_r($filter_data);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Product Received and Sold')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products Received and Sold');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($filter_data['filter_date_added']));
            $to = date('d-m-Y', strtotime($filter_data['filter_date_added_to']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
            $html = 'FROM ' . $from . ' TO ' . $to;
            if (isset($filter_data['filter_date_added']) && isset($filter_data['filter_date_added_to'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A3', '');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Sold Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Revenue');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Procured Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Rejected Quantity');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Buying Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Total Price');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['product_store_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['sold_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['revenue']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['procured_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['rejected_qty']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['priceperItem']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['Totalprice']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="product_received_sold_report.xlsx"');
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

    public function download_receivables_summary_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $results = $this->model_sale_order->getReceivablesSummary($data);

        if (is_array($results) && count($results) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            foreach ($results as $result) {

                $payment_terms = $result['payment_terms'];
                $isPezesha_customer = false;
                $isPezesha_customer = $this->model_sale_order->isPezeshaCustomer($result['customer_id']);

                if ($isPezesha_customer == true) {
                    $payment_terms = 'Pezesha';
                }


                $data['orders'][] = [
                    'company' => $result['company_name'],
                    'customer' => $result['customer'],
                    'payment_terms' => $payment_terms,
                    'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                    'order_total' => round(($result['order_total'] - $result['partialy_paid']), 2),
                        // 'updated_total' => number_format($result['updated_total'],2),
                        // 'paid'=> $result['paid'],
                        // 'amountpaid'=> number_format($result['amountpaid'],2),
                        // 'pendingamount'=> number_format($result['pendingamount'],2),
                ];
            }
        }
        $rows = $data['orders'];
        // echo "<pre>";print_r($rows);exit;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Summary Sheet')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Receivables Summary');
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Sum of Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Payment Terms');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // // echo "<pre>";print_r(strtotime($sendingDate));
                // // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                // $result['ageing'] = round($datediff / (60 * 60 * 24));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['order_total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['payment_terms']);

                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->currency->format($result['order_total']));
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['ageing']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="receivables_summary_sheet.xlsx"');
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

    public function download_receivables_ageing_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $results = $this->model_sale_order->getReceivablesAgeing($filter_data);
        $results_customers = $this->model_sale_order->getReceivablesAgeing_customers($filter_data);

        $this->load->model('sale/order');
        $customer_total = count($results_customers);

        if (is_array($results) && $customer_total > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');

            foreach ($results_customers as $res_cust) {


                $payment_term_diff = 0;
                $total = 0;
                $not_due = 0;
                $sum_30 = 0;
                $sum_60 = 0;
                $sum_90 = 0;
                $sum_180 = 0;
                $sum_360 = 0;
                $sum_360_greater = 0;

                if ($res_cust['payment_terms'] == 'Payment On Delivery') {
                    $payment_term_diff = 0;
                } else if ($res_cust['payment_terms'] == '7 Days Credit') {
                    $payment_term_diff = 7;
                } else if ($res_cust['payment_terms'] == '15 Days Credit') {
                    $payment_term_diff = 15;
                } else if ($res_cust['payment_terms'] == '30 Days Credit') {
                    $payment_term_diff = 30;
                }

                foreach ($results as $result) {
                    if ($res_cust['customer'] == $result['customer']) {
                        $total = $total + ($result['order_total'] - $result['partialy_paid']);
                        if ($result['datediff'] <= $payment_term_diff && $payment_term_diff != 0) {
                            $not_due = $not_due + ($result['order_total'] - $result['partialy_paid']);
                        } else if ($result['datediff'] >= 0 && $result['datediff'] <= 30) {
                            $sum_30 = $sum_30 + ($result['order_total'] - $result['partialy_paid']);
                        } else if ($result['datediff'] >= 31 && $result['datediff'] <= 60) {
                            $sum_60 = $sum_60 + ($result['order_total'] - $result['partialy_paid']);
                        } else if ($result['datediff'] >= 61 && $result['datediff'] <= 90) {
                            $sum_90 = $sum_90 + ($result['order_total'] - $result['partialy_paid']);
                        } else if ($result['datediff'] >= 91 && $result['datediff'] <= 180) {
                            $sum_180 = $sum_180 + ($result['order_total'] - $result['partialy_paid']);
                        } else if ($result['datediff'] >= 181 && $result['datediff'] <= 360) {
                            $sum_360 = $sum_360 + ($result['order_total'] - $result['partialy_paid']);
                        } else if ($result['datediff'] >= 361) {
                            $sum_360_greater = $sum_360_greater + ($result['order_total'] - $result['partialy_paid']);
                        }
                    }
                }

                $data['orders'][] = [
                    'company' => $res_cust['company_name'],
                    'customer' => $res_cust['customer'],
                    'payment_terms' => $res_cust['payment_terms'],
                    'total' => $total,
                    // 'order_total' => round(($result['order_total']-$result['partialy_paid']),2),
                    'not_due' => $not_due,
                    'sum_30' => $sum_30,
                    'sum_60' => $sum_60,
                    'sum_90' => $sum_90,
                    'sum_180' => $sum_180,
                    'sum_360' => $sum_360,
                    'sum_360_greater' => $sum_360_greater,
                ];
            }
        }
        $rows = $data['orders'];
        // echo "<pre>";print_r($rows);exit;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Summary Sheet')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Receivables Ageing');
            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Company');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Customer');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Sum of Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Sum not Due');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Sum of 0-30');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Sum of 31-60');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Sum of 61-90');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'Sum of 91-180');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'Sum of 181-360');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'Sum of >360');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // // echo "<pre>";print_r(strtotime($sendingDate));
                // // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                // $result['ageing'] = round($datediff / (60 * 60 * 24));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['company']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['total']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['not_due']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['sum_30']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['sum_60']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['sum_90']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['sum_180']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['sum_360']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['sum_360_greater']);

                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->currency->format($result['order_total']));
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['ageing']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="receivables_ageing_sheet.xlsx"');
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

    public function download_customer_master_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/customer');
        $rows = $this->model_report_customer->getCustomers($data);

        // echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Customers Master')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customers Master');
            $objPHPExcel->getActiveSheet()->getStyle('A4:C4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Payment Terms');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {

                $result['status'] = ($result['status'] == 0 ? 'Disabled' : 'Enabled');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['status']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['payment_terms']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="customers_Master.xlsx"');
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

    public function download_inventory_purchased_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale');
        $this->load->model('report/product');

        $rows = $this->model_report_product->getInventoryPurchased($data);

        //echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Inventory Purchased')->setDescription('none');
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Inventory Purchased Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            $to = date('d-m-Y', strtotime($data['filter_date_end']));
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $html = 'FROM ' . $from . ' TO ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            /* $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20); */

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Supplier Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'GRL no');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Unit');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Price');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['source']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['quantity']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['price']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, round($result['total'] ?? 0, 2));

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            /* $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel'); */

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            header('Content-Disposition: attachment;filename="inventory_purchased_report.xlsx"');
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

    public function download_companies_sales_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        $this->load->model('report/sale');
        //$customer_total = $this->model_report_customer->getTotalCustomerOrders($filter_data);
        // if($this->user->isAccountManager()) {//not available for account managers
        // $results = $this->model_report_customer->getAccountManagerOrders($data);
        // } else {
        $results = $this->model_report_sale->getSalesByCompanies($data);
        // }

        $this->load->model('sale/order');
        if (is_array($results) && count($results) > 0) {

            foreach ($results as $result) {
                $data['products'][] = [
                    // 'company' => $result['company'],
                    'order_date' => $result['date_added'],
                    'order_id' => $result['order_id'],
                    'customer' => $result['customer'],
                    'company' => $result['company'],
                    'customer_status' => $result['customer_status'] == 0 ? 'Disabled' : 'Enabled',
                    // 'name' => $result['name'],
                    // 'unit' => $result['unit'],
                    // 'quantity' => $result['quantity'],
                    'status' => $result['status'],
                    'payment_terms' => $result['payment_terms'],
                ];
            }
        }

        $log = new Log('error.log');
        // $log->write($data['products'] . 'download_product_consumption_excel');
        // echo "<pre>";print_r($data['products']);die;
        try {
            // set appropriate timeout limit
            set_time_limit(3500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Companies Sales')->setDescription('none');

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

            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
            $from = date('d-m-Y', strtotime($data['filter_date_start']));
            // $to = date('d-m-Y', strtotime($data['filter_date_end']));
            if ($from != '01-01-1970') {
                $html = 'ON ' . $from; //. ' TO ' . $to
            }
            //  else
            //     $html = 'Till Date : ' . $to;

            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sales by Companies');
            // $objPHPExcel->getActiveSheet()->setCellValue('A2', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');

            $objPHPExcel->getActiveSheet()->setCellValue('A3', $html);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Order Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Customer Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Customer Status');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Payment Terms');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Product Name');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Unit');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Status');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 4)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 4)->applyFromArray($title);
            // Fetching the table data
            $row = 5;
            $Amount = 0;
            foreach ($data['products'] as $result) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_date']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['customer']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['payment_terms']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['status']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $result['unit']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['quantity']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['status']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $filename = 'Sales By Companies.xlsx';

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
            $log->write($errstr . ' ' . $errline . ' ' . $errfile . ' ' . $errno . ' ' . 'download_sales_companies_excel');
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function download_sale_daily_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        $this->load->language('report/income');
        $this->load->model('report/sale_daily');
        $this->load->model('report/product');

        $results = $this->model_report_sale_daily->getOrdersNew($data);

        foreach ($results as $result) {


            $data['orders'][] = [
                'order_id' => $result['order_id'],
                // 'customer' => $result['customer'],
                // 'company' => $result['company'],
                'status' => $result['status'],
                // 'payment_method' => $result['payment_method'],
                // 'shipping_method' => $result['shipping_method'],
                // 'subtotal' => $this->currency->format($sub_total),
                // 'total' => $this->currency->format($latest_total),
                'amount' => round($result['amount'], 2), //$latest_total
                'amount_paid' => ($result['paid'] == 'Y' ? round($result['amount'], 2) : round($result['amount_partialy_paid'], 2)), //$latest_total
                'balance' => ($result['paid'] == 'Y' ? 0 : (($result['amount'] - $result['amount_partialy_paid']))),
                // 'store' => $result['store_name'],
                // 'order_status_id' => $result['order_status_id'],
                // 'order_status_color' => $result['color'],
                // 'city' => $result['city'],
                //'transaction_id' => $transaction_id,
                'transaction_id' => $result['transaction_id'],
                // 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                //'order_date' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                    // 'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                    // 'shipping_code' => $result['shipping_code'],
                    // 'view' => $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&order_id='.$result['order_id'].$url, 'SSL'),
                    // 'edit' => $this->url->link('sale/order/EditInvoice', 'token='.$this->session->data['token'].'&order_id='.$result['order_id'].$url, 'SSL'),
                    // 'delete' => $this->url->link('sale/order/delete', 'token='.$this->session->data['token'].'&order_id='.$result['order_id'].$url, 'SSL'),
            ];
        }

        $rows = $data['orders'];
        // echo "<pre>";print_r($rows);die;
        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Sales Daily Report')->setDescription('none');

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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sales Daily Report');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray(['font' => ['bold' => true], 'color' => [
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
            $objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Delivery Status');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Delivery Date');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, ' Amount Received');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Balance');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {



                /* if($result['pt']) {
                  $amount = $result['pt'];
                  }else{
                  $amount = 0;
                  } */
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['status']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['amount']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($result['delivery_date'])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['amount_paid']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['balance']);

                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            // Sending headers to force the user to download the file
            //header('Content-Type: application/vnd.ms-excel');
            //header("Content-type: application/octet-stream");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="sales_daily_report.xlsx"');
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

    public function mail_customer_unpaid_order_kibanda_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $rows = $this->model_sale_order->getUnpaidOrders_Kibanda($data);
        $sendingDate = $data['sendingDate'];

        // echo "<pre>";print_r($rows);exit;
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:M2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'M') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'Paid Amount');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Paid To');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'Payment Mode');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'Reference Number');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, 'Payment Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, 'Balance');

            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(12, 3)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // echo "<pre>";print_r(strtotime($sendingDate));
                // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                $result['ageing'] = round($datediff / (60 * 60 * 24));
                $result['balance'] = round((($result['order_total'] ?? 0) - ($result['amount_partialy_paid'] ?? 0)), 2);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['amount_partialy_paid'] ?? 0);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['transaction_id'] ?? '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['created_at'] ?? '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['balance']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="unpaid_orders_sheet.xlsx"');
            // header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');

            $filename = 'KB_Kibanda_Unpaid_Order_Sheet_' . $sendingDate . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp';
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file
                }
            }
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['deliverydate'] = $sendingDate;
            // $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            // if ($name != "") {
            $subject = 'Unpaid Orders';
            // }
            // $subject = "Consolidated Order Sheet";
            $message = str_replace('consolidated', 'kibanda unpaid', $message);
            $message = str_replace('Consolidated', 'Kibanda Unpaid', $message);
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('financeteam');

            if (strpos($email, "@") == false) {//if mail Id not set in define.php
                $email = "sridivya.talluri@technobraingroup.com";
            }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->addAttachment($filepath);
            $mail->send();
            #endregion
            exit;
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "<pre>";
            print_r($e->getFile());
            echo "<pre>";
            print_r($e->getCode());
            echo "<pre>";
            print_r($e->getLine());

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


    public function mail_customer_unpaid_order_pezesha_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $rows = $this->model_sale_order->getUnpaidOrders_Pezesha($data);
        $sendingDate = $data['sendingDate'];

        // echo "<pre>";print_r($rows);exit;
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:M2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'M') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'Paid Amount');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Paid To');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'Payment Mode');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'Reference Number');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, 'Payment Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, 'Balance');

            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(10, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(11, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(12, 3)->applyFromArray($title);
            // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // echo "<pre>";print_r(strtotime($sendingDate));
                // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                $result['ageing'] = round($datediff / (60 * 60 * 24));
                $result['balance'] = round((($result['order_total'] ?? 0) - ($result['amount_partialy_paid'] ?? 0)), 2);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['amount_partialy_paid'] ?? 0);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['payment_method']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['transaction_id'] ?? '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['created_at'] ?? '');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['balance']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="unpaid_orders_sheet.xlsx"');
            // header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');

            $filename = 'KB_Pezesha_Unpaid_Order_Sheet_' . $sendingDate . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp';
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file
                }
            }
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['deliverydate'] = $sendingDate;
            // $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            // if ($name != "") {
            $subject = 'Unpaid Orders';
            // }
            // $subject = "Consolidated Order Sheet";
            $message = str_replace('consolidated', 'Pezesha unpaid', $message);
            $message = str_replace('Consolidated', 'Pezesha Unpaid', $message);
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('financeteam');

            // if (strpos($email, "@") == false) {//if mail Id not set in define.php
            //     $email = "sridivya.talluri@technobraingroup.com";
            // }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->addAttachment($filepath);
            $mail->send();
            #endregion
            exit;
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "<pre>";
            print_r($e->getFile());
            echo "<pre>";
            print_r($e->getCode());
            echo "<pre>";
            print_r($e->getLine());

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

    public function getOrderTransactionIdExists($order_id) {

        $sql2 = 'SELECT mpesa_reference FROM ' . DB_PREFIX . "pezesha_loan_recceivables WHERE order_id = '" . (int) $order_id . "'";
        $query2 = $this->db->query($sql2);

        if (isset($query2->row)) {
            /* $log = new Log('error.log');
              $log->write('order model');
              $log->write($query->row);
              $log->write('order model'); */
            if (array_key_exists('mpesa_reference', $query2->row)) {
                return $query2->row['mpesa_reference'];
            }
        }

        $sql = 'SELECT transaction_id FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);

        if (isset($query->row)) {
            /* $log = new Log('error.log');
              $log->write('order model');
              $log->write($query->row);
              $log->write('order model'); */
            if (array_key_exists('transaction_id', $query->row)) {
                return $query->row['transaction_id'];
            } else {
                return '';
            }
        }

        return null;
    }

    public function mail_customer_all_unpaid_order_excel($data,$data_kibanda,$data_pezesha) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $rows = $this->model_sale_order->getUnpaidOrders($data);
        $sendingDate = $data['sendingDate'];


        if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
            mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
        }
        // unlink($filename);
        $folder_path = DIR_UPLOAD . 'schedulertemp';

        
        $filename = 'KB_Unpaid_Order_Sheet_POD_' . $sendingDate . '.xlsx';
        $filename1 = 'KB_Unpaid_Order_Sheet_Kibanda_' . $sendingDate . '.xlsx';
        $filename2 = 'KB_Unpaid_Order_Sheet_Pezesha_' . $sendingDate . '.xlsx';
        // $filename = 'KB_Pezesha_Unpaid_Order_Sheet_' . $sendingDate . '.xlsx';



        // echo "<pre>";print_r($rows);exit;
        try {
            // set appropriate timeout limit
            #region 1
            set_time_limit(2800);

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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // echo "<pre>";print_r(strtotime($sendingDate));
                // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                $result['ageing'] = round($datediff / (60 * 60 * 24));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);


            #end region 1

            #region 2
            
            set_time_limit(1800);

        $rows_kibanda = $this->model_sale_order->getUnpaidOrders_Kibanda($data_kibanda);
        
        $objPHPExcel1 = new PHPExcel();
        $objPHPExcel1->getProperties()->setTitle('Orders Sheet1')->setDescription('none');
        $objPHPExcel1->setActiveSheetIndex(0);

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
        $objPHPExcel1->getActiveSheet()->mergeCells('A1:M2');
        $objPHPExcel1->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet Kibanda');
        $objPHPExcel1->getActiveSheet()->getStyle('A1:M2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                'rgb' => '4390df',
        ]]);

        //subtitle


        $objPHPExcel1->getActiveSheet()->getStyle('A1:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel1->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        foreach (range('A', 'M') as $columnID) {
            $objPHPExcel1->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
        }

        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Name');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'Paid Amount');
        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Paid To');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'Payment Mode');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'Reference Number');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(11, 3, 'Payment Date');
        $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(12, 3, 'Balance');

        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(8, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(9, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(10, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(11, 3)->applyFromArray($title);
        $objPHPExcel1->getActiveSheet()->getStyleByColumnAndRow(12, 3)->applyFromArray($title);
        // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
        // Fetching the table data
        $row = 4;

        //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
        $i = 1;
        foreach ($rows_kibanda as $result) {

            $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

            if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                $result['company_name'] = $result['customer'];
            }
            // echo "<pre>";print_r(strtotime($result['delivery_date']));
            // echo "<pre>";print_r(strtotime($sendingDate));
            // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
            $result['ageing'] = round($datediff / (60 * 60 * 24));
            $result['balance'] = round((($result['order_total'] ?? 0) - ($result['amount_partialy_paid'] ?? 0)), 2);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['amount_partialy_paid'] ?? 0);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['payment_method']);
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['transaction_id'] ?? '');
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['created_at'] ?? '');
            $objPHPExcel1->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['balance']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
            $i++;
            ++$row;
        }

        $objPHPExcel1->setActiveSheetIndex(0);
        $objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, 'Excel2007');
        
       
        $objWriter1->save(DIR_UPLOAD . 'schedulertemp/' . $filename1);
        

        #end region 2

        #region 3

        $rows2 = $this->model_sale_order->getUnpaidOrders_Pezesha($data_pezesha);

        $objPHPExcel2 = new PHPExcel();
        $objPHPExcel2->getProperties()->setTitle('Orders Sheet')->setDescription('none');
        $objPHPExcel2->setActiveSheetIndex(0);

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
        $objPHPExcel2->getActiveSheet()->mergeCells('A1:M2');
        $objPHPExcel2->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet');
        $objPHPExcel2->getActiveSheet()->getStyle('A1:M2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                'rgb' => '4390df',
        ]]);

        //subtitle


        $objPHPExcel2->getActiveSheet()->getStyle('A1:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel2->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        foreach (range('A', 'M') as $columnID) {
            $objPHPExcel2->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
        }

        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Name');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'Paid Amount');
        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Paid To');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'Payment Mode');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'Reference Number');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(11, 3, 'Payment Date');
        $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(12, 3, 'Balance');

        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(8, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(9, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(10, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(11, 3)->applyFromArray($title);
        $objPHPExcel2->getActiveSheet()->getStyleByColumnAndRow(12, 3)->applyFromArray($title);
        // $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
        // Fetching the table data
        $row = 4;

        //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
        $i = 1;
        foreach ($rows2 as $result) {

            $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

            if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                $result['company_name'] = $result['customer'];
            }
            // echo "<pre>";print_r(strtotime($result['delivery_date']));
            // echo "<pre>";print_r(strtotime($sendingDate));
            // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
            $result['ageing'] = round($datediff / (60 * 60 * 24));
            $result['balance'] = round((($result['order_total'] ?? 0) - ($result['amount_partialy_paid'] ?? 0)), 2);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $result['amount_partialy_paid'] ?? 0);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result['payment_method']);
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result['transaction_id'] ?? '');
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result['created_at'] ?? '');
            $objPHPExcel2->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $result['balance']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
            $i++;
            ++$row;
        }

        $objPHPExcel2->setActiveSheetIndex(0);

        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="unpaid_orders_sheet.xlsx"');
        // header('Cache-Control: max-age=0');
        // $objWriter->save('php://output');

        $objWriter2 = PHPExcel_IOFactory::createWriter($objPHPExcel2, 'Excel2007');
        $objWriter2->save(DIR_UPLOAD . 'schedulertemp/' . $filename2);

        #endregion

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="unpaid_orders_sheet.xlsx"');
            // header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');

           
          

            #region mail sending
            $maildata['deliverydate'] = $sendingDate;
            // $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            // if ($name != "") {
            $subject = 'Unpaid Orders'; 

            // }
            // $subject = "Consolidated Order Sheet";
            $message = str_replace('consolidated', 'unpaid', $message);
            $message = str_replace('Consolidated', 'Unpaid', $message);
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('financeteam');

            // if (strpos($email, "@") == false) {//if mail Id not set in define.php
            //     $email = "sridivya.talluri@technobraingroup.com";
            // }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $filepath1 = DIR_UPLOAD . 'schedulertemp/' . $filename1;
            $filepath2 = DIR_UPLOAD . 'schedulertemp/' . $filename2;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->addAttachment($filepath);
            $mail->addAttachment($filepath1);
            $mail->addAttachment($filepath2);
            // $mail->send();
            $mail->sendMail();
            #endregion


            
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file
                }
            }
            // echo "<pre>";print_r($file);;

            exit;
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "<pre>";
            print_r($e->getFile());
            echo "<pre>";
            print_r($e->getCode());
            echo "<pre>";
            print_r($e->getLine());

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


    public function mail_customer_unpaid_order_other_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');
        // echo "<pre>";print_r($data);

        $this->load->language('report/vendor_order');
        $this->load->model('sale/order');
        $rows = $this->model_sale_order->getUnpaidOrders_other($data);
        $sendingDate = $data['sendingDate'];

        // echo "<pre>";print_r($rows);exit;
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
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unpaid Orders Sheet Other');
            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '4390df',
            ]]);

            //subtitle


            $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'S.NO');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Order ID');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, 'Company Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'Customer Group');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'Order Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'Delivery Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'Order Value');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Ageing  (No. of days due)');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'Order Status');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, 3)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, 3)->applyFromArray($title);
            // Fetching the table data
            $row = 4;

            //echo "<pre>";print_r($data['filter_date_end']."er".$data['filter_date_start']);
            $i = 1;
            foreach ($rows as $result) {

                $datediff = strtotime($sendingDate) - strtotime($result['delivery_date']);

                if ($result['company_name'] == '' || $result['company_name'] == NULL || $result['company_name'] == 'Individual ' || $result['company_name'] == 'Individual' || $result['company_name'] == 'N/A' || $result['company_name'] == 'n/a') {
                    $result['company_name'] = $result['customer'];
                }
                // echo "<pre>";print_r(strtotime($result['delivery_date']));
                // echo "<pre>";print_r(strtotime($sendingDate));
                // echo "<pre>";print_r(round($datediff / (60 * 60 * 24)));exit;
                $result['ageing'] = round($datediff / (60 * 60 * 24));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $i);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['order_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['company_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['customer_group']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['date_added']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['delivery_date']);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->currency->format($result['order_total']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['ageing']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
                $i++;
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="unpaid_orders_sheet.xlsx"');
            // header('Cache-Control: max-age=0');
            // $objWriter->save('php://output');

            $filename = 'KB_Unpaid_Order_Sheet_Other_' . $sendingDate . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp';
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file
                }
            }
            // echo "<pre>";print_r($file);;
            $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);

            #region mail sending
            $maildata['deliverydate'] = $sendingDate;
            // $subject = $this->emailtemplate->getSubject('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);
            $message = $this->emailtemplate->getMessage('ConsolidatedOrderSheet', 'ConsolidatedOrderSheet_1', $maildata);

            // if ($name != "") {
            $subject = 'Unpaid Orders';
            // }
            // $subject = "Consolidated Order Sheet";
            $message = str_replace('consolidated', 'other unpaid', $message);
            $message = str_replace('Consolidated', 'Other Unpaid', $message);
            $this->load->model('setting/setting');
            $email = $this->model_setting_setting->getEmailSetting('financeteam');

            // if (strpos($email, "@") == false) {//if mail Id not set in define.php
            //     $email = "sridivya.talluri@technobraingroup.com";
            // }
            // $bccemail = "sridivya.talluri@technobraingroup.com";
            //   echo "<pre>";print_r($email);die;
            $filepath = DIR_UPLOAD . 'schedulertemp/' . $filename;
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($email);
            // $mail->setBCC($bccemail);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->addAttachment($filepath);
            $mail->send();
            #endregion
            exit;
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "<pre>";
            print_r($e->getFile());
            echo "<pre>";
            print_r($e->getCode());
            echo "<pre>";
            print_r($e->getLine());

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
