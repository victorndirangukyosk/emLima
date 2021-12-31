<?php

class ControllerPdfPdf extends Controller {

    public function index($data) {
        $data = NULL;
        try {
            $this->load->model('sale/order');
            $rows = $this->model_sale_order->getOrders($data);
            $data['orders'] = $rows;
            require_once DIR_ROOT . '/vendor/autoload.php';
            $pdf = new \mikehaertl\wkhtmlto\Pdf;
            $template = $this->load->view($this->config->get('config_template') . '/template/pdf/pdf.tpl', $data);
            $pdf->addPage($template);
            if (!$pdf->send("KwikBasket_UnPaid_Orders" . $data['customer_id'] . ".pdf")) {
                $error = $pdf->getError();
                echo $error;
                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
