<?php

class ControllerEmailtemplateEmailtemplate extends Controller {

    public function index() {
        
    }

    public function getOrderProductListTemplate($dat) {
        $log = new Log('error.log');
        $data['products'] = [];
        $this->load->model('account/order');
        $order_info = $this->model_account_order->getOrder($dat['order_id']);
        $products = $this->model_account_order->getOrderProducts($dat['order_id']);
        $totals = $this->model_account_order->getOrderTotals($dat['order_id']);

        foreach ($products as $product) {
            $data['products'][] = [
                'product_id' => $product['product_id'],
                'store_id' => $product['store_id'],
                'vendor_id' => $product['vendor_id'],
                'product_note' => $product['product_note'],
                'name' => $product['name'],
                'unit' => $product['unit'],
                'model' => $product['model'],
                'product_type' => $product['product_type'],
                'return_id' => $product['return_id'],
                'quantity' => $product['quantity'],
                'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
            ];
        }
        $html = '';
        $html .= '<table class="table table-bordered">';
        $html .= '<thead><tr><th>S.NO</th><th>PRODUCT NAME</th><th>UNIT PRICE</th><th>UNIT</th><th>QUANTITY</th><th>TOTAL</th></tr></thead>';
        $html .= '<tbody>';
        $count = 1;
        foreach ($products as $product) {
            $html .= '<tr>
            <td>' . $count . '</td>
            <td>' . $product['name'] . '</td>
            <td>' . $product['price'] . '</td>
            <td>' . $product['unit'] . '</td>
            <td>' . $product['quantity'] . '</td>
            <td>' . $product['total'] . '</td>
        </tr>';
            $count++;
        }
        foreach ($totals as $total) {
            $html .= '<tr><td colspan="2"></td><td>' . $total['title'] . '</td><td>' . $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']) . '</td></tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }

    public function getOrderTotalTemplate($dat) {

        $data['totals'] = [];

        $order_info = $this->model_account_order->getOrder($dat['order_id']);
        $totals = $this->model_account_order->getOrderTotals($dat['order_id']);

        foreach ($totals as $total) {
            $data['totals'][] = [
                'title' => $total['title'],
                'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
            ];
        }
    }

}
