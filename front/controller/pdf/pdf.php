<?php

class ControllerPdfPdf extends Controller {

    public function index($data) {
        $dat = NULL;
        try {
            $log = new Log('error.log');
            $new_data = NULL;
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
                if (!$pdf->send("KwikBasket_UnPaid_Orders" . $pending_order_customer_id . ".pdf")) {
                    $error = $pdf->getError();
                    echo $error;
                    die;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
