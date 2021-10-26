<?php

class ControllerReportSaleProductMissing extends Controller
{
    public function excel()//if any changes done to this method, need to update same in Scheduler
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

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-01'));
        }
        
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        $data['filter_store'] = $filter_store_id;
        $data['filter_store_name'] = $filter_store;

        $data['filter_store_id'] = $filter_store_id;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_name'] = $filter_name;
        $data['filter_order_status_id'] = $filter_order_status_id;

        //echo "<pre>";print_r($data);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_saleorderproductmissing($data);
    }

    public function index()
    {
        //echo "<pre>";print_r(date('d-M-y', strtotime('2018-12-26')));die;
        $this->load->language('report/sale_productmissing');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
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
        
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-01'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'week';
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }
        
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.$this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group='.$this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id='.$this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store='.$this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id='.$this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/sale_productmissing', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('report/sale');
        $this->load->model('sale/order');
        $this->load->model('account/order');

        $data['orders'] = [];
        $data['torders'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_store' => $filter_store_id,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        // echo "<pre>";print_r($filter_data);die;

        //$order_total = $this->model_report_sale->getTotalproductmissingOrders($filter_data);

        $order_total = 0;

        // $results = $this->model_report_sale->getstockoutOrders($filter_data);

        $OrignalProducts= $this->model_report_sale->getstockoutOrdersAndProducts($filter_data);

        //  echo "<pre>";print_r($results);die;
        // foreach ($results as $result) {
        //     $is_edited = $this->model_sale_order->hasRealOrderProducts($result['order_id']);

        //     if ($is_edited) {
        //         //continue;
        //         $OrignalProducts = $EditedProducts = $this->model_sale_order->getRealOrderProductsStockOut($result['order_id'], $filter_store_id, $filter_name);
        //     } else {
        //         $OrignalProducts = $this->model_sale_order->getOrderProductsStockOut($result['order_id'], $filter_store_id, $filter_name);
        //     }

            /*echo "<pre>";print_r($OrignalProducts);
            echo "<pre>";print_r($EditedProducts);die;*/
            //as per the today discussion, stock out means total stock ordered or out after deliverty

             foreach ($OrignalProducts as $OrignalProduct) {
            //     // $present = false;

            //     // foreach ($EditedProducts as $EditedProduct) {
            //     //     if(!empty($OrignalProduct['name']) && $OrignalProduct['name'] == $EditedProduct['name'] && $OrignalProduct['unit'] == $EditedProduct['unit']) {
            //     //         $present = true;
            //     //     }
            //     // }!$present &&

            //     if ( !empty($OrignalProduct['name'])) {
                    $data['torders'][] = [
                        'store' => $OrignalProduct['store_name'],
                        'model' => $OrignalProduct['product_id'],
                        'product_name' => $OrignalProduct['name'],
                        'unit' => $OrignalProduct['unit'],
                        'product_id' => $OrignalProduct['product_id'],
                        'product_qty' => (float) $OrignalProduct['quantity'],
                    ];
                    ++$order_total;
            //     }
             }
        // }

        //  echo "<pre>";print_r($data['torders']);die;
        // foreach ($data['torders'] as $torders1) {
        //     $ex = false;

        //     foreach ($data['orders'] as $value1) {
        //         if ($value1['product_name'] == $torders1['product_name'] && $value1['store'] == $torders1['store'] &&  $value1['unit'] == $torders1['unit']) {
        //             $ex = true;
        //         }
        //     }

        //     if (!$ex) {
        //         $sum = (float) 0.0;

        //         foreach ($data['torders'] as $key => $torders2) {
        //             if ($torders1['product_name'] == $torders2['product_name'] && $torders1['store'] == $torders2['store'] && $torders1['unit'] == $torders2['unit']) {
        //                 $sum += (float) $torders2['product_qty'];

        //                 unset($data['torders'][$key]);
        //             }
        //         }

        //         $torders1['product_qty'] = (float) $sum;

                // ++$order_total;

        //         array_push($data['orders'], $torders1);
        //     }
        // }
        $data['orders']=$data['torders'];
        if (isset($this->request->get['download_excel']) && (true == $this->request->get['download_excel'])) {
            $this->load->model('report/excel');
            $this->model_report_excel->download_saleorderproductmissingNew($data);
        }
        //echo "<pre>";print_r($data['orders']);die;
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_date_start'] = $this->language->get('column_date_start');
        $data['column_date_end'] = $this->language->get('column_date_end');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_tax'] = $this->language->get('column_tax');
        $data['column_total'] = $this->language->get('column_total');

        $data['column_barcode'] = $this->language->get('column_barcode');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_unit'] = $this->language->get('column_unit');
        $data['column_ordered_qty'] = $this->language->get('column_ordered_qty');
        $data['column_store'] = $this->language->get('column_store');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_city'] = $this->language->get('entry_city');

        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_name'] = 'Name';
        $data['column_delivery_date'] = $this->language->get('column_delivery_date');
        $data['column_order_no'] = $this->language->get('column_order_no');
        $data['column_no_of_items'] = $this->language->get('column_no_of_items');
        $data['column_subtotal'] = $this->language->get('column_subtotal');
        $data['column_wallet_used'] = $this->language->get('column_wallet_used');
        $data['column_coupon'] = $this->language->get('column_coupon');

        $data['column_reward_points_claimed'] = $this->language->get('column_reward_points_claimed');
        $data['column_delivery_charges'] = $this->language->get('column_delivery_charges');

        $data['column_walletcredited'] = $this->language->get('column_walletcredited');
        $data['column_paymentmethod'] = $this->language->get('column_paymentmethod');
        $data['column_transaction_ID'] = $this->language->get('column_transaction_ID');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['groups'] = [];

        $data['groups'][] = [
            'text' => $this->language->get('text_year'),
            'value' => 'year',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_month'),
            'value' => 'month',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_week'),
            'value' => 'week',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_day'),
            'value' => 'day',
        ];

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store='.$this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id='.$this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }
        
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.$this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group='.$this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id='.$this->request->get['filter_order_status_id'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/sale_productmissing', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_store'] = $filter_store;
        $data['filter_store_id'] = $filter_store_id;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_name'] = $filter_name;
        $data['filter_group'] = $filter_group;
        $data['filter_order_status_id'] = $filter_order_status_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        //as the dynamic pagination will not work for this calculation , applied pagination on array
        $start = ($page - 1) * $this->config->get('config_limit_admin');
        $limit = $this->config->get('config_limit_admin');

        $data['orders'] = array_slice($data['orders'], $start, $limit);
        //echo "<pre>";print_r($data['orders']);die;
        $this->response->setOutput($this->load->view('report/sale_productmissing.tpl', $data));
    }

    public function city_autocomplete()
    {
        $this->load->model('sale/order');

        $json = $this->model_sale_productmissing->getCitiesLike($this->request->get['filter_name']);

        header('Content-type: text/json');
        echo json_encode($json);
    }
}
