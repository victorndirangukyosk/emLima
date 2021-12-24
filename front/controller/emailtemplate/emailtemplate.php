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
        $html .= '<table class="table table-bordered" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border-collapse: collapse!important;border-spacing: 0;background-color: transparent;width: 100%;max-width: 100%;margin-bottom: 20px;border: 1px solid #ddd;">';
        $html .= '<thead class="thead-bg" style="background: #EC7122;color: #fff;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;display: table-header-group;">'
                . '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">S.NO</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">PRODUCT NAME</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">UNIT PRICE</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">UNIT</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">QUANTITY</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">TOTAL</th>'
                . '</tr>'
                . '</thead>';
        $html .= '<tbody style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">';
        $count = 1;
        foreach ($products as $product) {
            $html .= '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">
            <th scope="row" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $count . '</th>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['name'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['price'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['unit'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['quantity'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['total'] . '</td>
        </tr>';
            $count++;
        }
        foreach ($totals as $total) {
            $html .= '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">'
                    . '<th colspan="4" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"></th>'
                    . '<td style="text-align: right;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"><strong style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">' . $total['title'] . '</strong></td>'
                    . '<td style="text-align: right;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"><strong style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">' . $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']) . '</strong></td>'
                    . '</tr>';
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
