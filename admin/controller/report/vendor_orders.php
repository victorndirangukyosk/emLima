<?php
class ControllerReportVendorOrders extends Controller { 
		
	public function getUserByName($name) {

        if($name) {
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];    
        }
        
    }

    public function getStoreIdByName($name) {

        if($name) {
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE name LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['store_id'];    
        }
        
    }

    
	//download excel
	public function excel(){

		if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
		} else {
				$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
		} else {
				$filter_date_end = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
				$filter_order_status_id = 0;
		}
		
		if (isset($this->request->get['filter_city'])) {
				$filter_city = $this->request->get['filter_city'];
		} else {
				$filter_city = '';
		}

		if (isset($this->request->get['filter_vendor'])) {
				$filter_vendor = $this->request->get['filter_vendor'];
		} else {
				$filter_vendor = '';
		}

		if (isset($this->request->get['filter_store'])) {
				$filter_store = $this->request->get['filter_store'];
		} else {
				$filter_store = '';
		}


		
		$data = array(
			'filter_city' => $filter_city,
			'filter_vendor' => $this->getUserByName($filter_vendor),
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_store'            => $this->getStoreIdByName($filter_store),
			'filter_store_name'            => $filter_store,
		);

		$this->load->model('report/excel');
		$this->model_report_excel->download_report_vendor_orders_excel($data);
	}
		
	public function index() {  
		$this->language->load('report/vendor_orders');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
				
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	
				
		if (isset($this->request->get['filter_city'])) {
				$filter_city = $this->request->get['filter_city'];
		} else {
				$filter_city = '';
		}

		if (isset($this->request->get['filter_vendor'])) {
				$filter_vendor = $this->request->get['filter_vendor'];
		} else {
				$filter_vendor = '';
		}

		if (isset($this->request->get['filter_vendor_id'])) {
				$filter_vendor_id = $this->request->get['filter_vendor_id'];
		} else {
				$filter_vendor_id = '';
		}

		if (isset($this->request->get['filter_store'])) {
				$filter_store = $this->request->get['filter_store'];
		} else {
				$filter_store = '';
		}

		if(isset($this->request->get['sort'])){
			$sort = $this->request->get['sort'];
		}else{
			$sort = '';
		}
		
		if(isset($this->request->get['order'])){
			$order = $this->request->get['order'];
		}else{
			$order = '';
		}                 
				
		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_vendor_id'])) {
			$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/sale');
		$this->load->model('sale/order');

		$data['vendor_orders'] = array();

		$filter_data = array(
			'filter_city'   	=> $filter_city, 
			'filter_vendor'   	=> $this->getUserByName($filter_vendor),     
			'filter_date_start'	=> $filter_date_start, 
			'filter_date_end'	=> $filter_date_end, 
			'filter_order_status_id'=> $filter_order_status_id,
			'filter_store'            => $this->getStoreIdByName($filter_store),
			'filter_group'          => $filter_group,
			'sort'                  => $sort,
			'order'                 => $order,
			'start'                 => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                 => $this->config->get('config_limit_admin')
		);

		//echo "<pre>";print_r($filter_data);die;

		$order_total = $this->model_report_sale->getTotalReportVendorOrders($filter_data);
		$results = $this->model_report_sale->getReportVendorOrders($filter_data);
		//$order_total = count($results);

		//echo "<pre>";print_r($results);die;
		foreach ($results as $result) {

			$products_qty = 0;

			if($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {

                $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);

            } else {

                $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
            }

            $sub_total = 0;

            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if($total['code'] == 'sub_total') {
                    $sub_total = $total['value'];
                    break;
                }
            }


            //echo "<pre>";print_r($products);die;

			$data['vendor_orders'][] = array(

				'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
				'order_id' => $result['order_id'],
				'products' => $products_qty,
				'subtotal'     => $this->currency->format($sub_total),				
				'total'     => $this->currency->format($result['total']),				
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['text_list'] = $this->language->get('text_list');                 
		$data['button_show_filter'] = $this->language->get('button_show_filter');                 
		$data['button_hide_filter'] = $this->language->get('button_hide_filter');    
				
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');

		$data['column_delivery_date'] = $this->language->get('column_delivery_date');

		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_subtotal'] = $this->language->get('column_subtotal');
		$data['column_vendor'] = $this->language->get('column_vendor');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_store_name'] = $this->language->get('entry_store_name');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
		}
		
		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_vendor_id'])) {
			$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
				
		if (isset($this->request->get['sort'])) {
			$data['sort'] = $this->request->get['sort'];
		}else{
			$data['sort'] = 'total';
		}
		
		if (isset($this->request->get['order'])) {
			$data['order'] = $this->request->get['order'];
		}else{
			$data['order'] = 'DESC';
		}
		
		$data['sort_orders'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=orders' . $url, 'SSL');
		$data['sort_products'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=products' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=total' . $url, 'SSL');
		$data['sort_subtotal'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=subtotal' . $url, 'SSL');
				
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();		

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
				
		$data['filter_city'] = $filter_city;
		$data['filter_vendor'] = $filter_vendor;
		$data['filter_store'] = $filter_store;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;		
				$data['filter_order_status_id'] = $filter_order_status_id;		
		$data['filter_group'] = $filter_group;
				
				$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/vendor_orders.tpl', $data));
	}
}