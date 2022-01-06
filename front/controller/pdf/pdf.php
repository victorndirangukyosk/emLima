<?php

class ControllerPdfPdf extends Controller {

    public function index($data) {
        try {
            $log = new Log('error.log');
            $log->write('pdf_data');
            $log->write($data);
            $log->write('pdf_data');
            $new_data = NULL;
            $dat = NULL;
            require_once DIR_ROOT . '/vendor/autoload.php';
            $this->load->model('sale/order');
            foreach ($data['pending_order_id'] as $key => $value) {
                $pending_orders = implode(",", $value);
                $new_data['filter_order_id_array'] = $pending_orders;
                $pending_order_customer_id = $key;
                $rows = $this->model_sale_order->getOrders($new_data);
                $dat['orders'] = $rows;
                $pdf = new \mikehaertl\wkhtmlto\Pdf;
                $template = $this->load->view($this->config->get('config_template') . '/template/pdf/pdf.tpl', $dat);
                $pdf->addPage($template);
                $filename = "KWIKBASKET_UNPAID_ORDERS_" . $pending_order_customer_id . ".pdf";
                if (!$pdf->saveAs(DIR_ROOT . 'scheduler_downloads' . '/' . $filename)) {
                    $error = $pdf->getError();
                    echo $error;
                    die;
                }
                sleep(3);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
