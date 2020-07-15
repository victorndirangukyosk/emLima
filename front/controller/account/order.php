<?php

class ControllerAccountOrder extends Controller {
	private $error = array();

	public function index() {
		

		$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
			
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
		$data['kondutoStatus'] = $this->config->get('config_konduto_status');

		//setlocale (LC_ALL, "pt_BR");

		$this->load->language('account/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_cancel'] = $this->language->get ('text_cancel');
		$data['text_placed_on'] = $this->language->get('text_placed_on');
		$data['text_view'] = $this->language->get('text_view');
		$data['text_items_ordered'] = $this->language->get('text_items_ordered');
		$data['text_real_items_ordered'] = $this->language->get('text_real_items_ordered');
		$data['text_refund_text_part1'] = $this->language->get('text_refund_text_part1');
		$data['text_refund_text_part2'] = $this->language->get('text_refund_text_part2');
		$data['text_refund_text_part3'] = $this->language->get('text_refund_text_part3');
		$data['text_delivery_address'] = $this->language->get('text_delivery_address');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_payment_options'] = $this->language->get('text_payment_options');
		$data['text_view_billing'] = $this->language->get('text_view_billing');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_report_issue'] = $this->language->get('text_report_issue');
		$data['text_load_more'] = $this->language->get('text_load_more');
		$data['text_view_order'] = $this->language->get('text_view_order');
		
		$data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
		$data['text_coupon_credited'] = $this->language->get('text_coupon_credited');


		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');
		$this->load->model('account/address');

		$order_total = $this->model_account_order->getTotalOrders();

		
		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

	//	echo "<pre>";print_r($results);die;
		foreach ($results as $result) {

			$city_name = $this->model_account_order->getCityName($result['shipping_city_id']);

			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

			$real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);

			$order_total_detail = $this->load->controller('checkout/totals/getTotal',$result['order_id']);

			//echo "<pre>";print_r($product_total);die;
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$shipping_address = null;

			if(isset($result['shipping_address'])) {
				$shipping_address['address'] = $result['shipping_building_name'] .", ". $result['shipping_flat_number'];
				$shipping_address['city'] = $city_name;
				$shipping_address['zipcode'] = $result['shipping_zipcode'];
				//$order_info['shipping_flat_number'].", ".$order_info['shipping_building_name'].", ".$order_info['shipping_landmark'];
			}

			$shipped = false;
			foreach ($this->config->get('config_processing_status') as $key => $value) {
				if($value == $result['order_status_id']) {
					$shipped = true;
					break;
				}
			}
			/*if(!$shipped) {
				
				foreach ($this->config->get('config_complete_status') as $key => $value) {
					if($value == $result['order_status_id']) {
						$shipped = true;
						break;
					}
				}
			}*/

			$realproducts = $this->model_account_order->hasRealOrderProducts($result['order_id']);

			$total = $result['total'];

			$ordertotals = $this->model_account_order->getOrderTotals($result['order_id']);
//  echo "<pre>";print_r($ordertotals);die;
			foreach ($ordertotals as $ordertotal) {
				
				if($ordertotal['code'] == 'total') {
					$total = $ordertotal['value'];
				}
			}

			$this->load->model('sale/order');

			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'shipping_name' => $result['shipping_name'],
				'payment_method' => $result['payment_method'],
				'payment_transaction_id' => $this->model_sale_order->getOrderTransactionId($result['order_id']),
				'shipping_address' => $shipping_address,
				'order_total' => $order_total_detail,
				'store_name' => $result['store_name'],
				'status'     => $result['status'],
				'order_status_color'     => $result['order_status_color'],
				'shipped'     => $shipped,
				'realproducts'     => $realproducts,
				//'pt_date_added' => strftime( "%h %y",strtotime($result['date_added'])),
				'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
				'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),
				'eta_date' => date($this->language->get('date_format'), strtotime($result['delivery_date'])),
				'eta_time' => $result['delivery_timeslot'],
				'products'   => ($product_total + $voucher_total),
				'real_products'   => ($real_product_total + $voucher_total),
				'total'      => $this->currency->format($total, $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
				'real_href'       => $this->url->link('account/order/realinfo', 'order_id=' . $result['order_id'], 'SSL'),
				'accept_reject_href' => $this->url->link('account/order/accept_reject', 'order_id=' . $result['order_id'], 'SSL'),
			);
		} 
		

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		//echo "<pre>";print_r($data['orders']);die;
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header/information');

		//echo "<pre>";print_r($this->config->get('config_shipped_status'));die;

		if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/order_list.tpl', $data));
		}
	}

	


	public function info() {
		$redirectNotLogin = true;
		$this->load->language('account/order');
		$this->load->language('account/return');

		$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
				
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		if(is_numeric($order_id) == false){
			$order_id = base64_decode(trim($order_id));
			$order_id = preg_replace('/[^A-Za-z0-9\-]/', '', $order_id);
			$this->request->get['order_id'] = $order_id;
			$redirectNotLogin = false;
			//$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'));
		}

		$data['kondutoStatus'] = $this->config->get('config_konduto_status');
		
		$data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (!$this->customer->isLogged() && ($redirectNotLogin == true)) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');
        if($redirectNotLogin == false){
		   $order_info = $this->model_account_order->getOrder($order_id,true);
		}else{
			$order_info = $this->model_account_order->getOrder($order_id);
		}
		//echo "<pre>";print_r($order_info);die;

		$data['cashback_condition'] = $this->language->get('cashback_condition');

		if ($order_info) {

			$data['cashbackAmount'] = $this->currency->format(0);

			$coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

	        if(count($coupon_history_data) > 0) {
	            $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount'])); 
	        }

	        

			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['text_go_back'] = $this->language->get('text_go_back');
			$data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
			$data['text_items'] = $this->language->get('text_items');
			$data['text_products'] = $this->language->get('text_products');
			
			$data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
			$data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_processing'] = $this->language->get('text_processing');
			$data['text_shipped'] = $this->language->get('text_shipped');
			$data['text_delivered'] = $this->language->get('text_delivered');
			$data['text_name'] = $this->language->get('text_name');
			$data['text_contact_no'] = $this->language->get('text_contact_no');
			$data['text_estimated_datetime'] = $this->language->get ('text_estimated_datetime');
			$data['text_cancel'] = $this->language->get ('text_cancel');
			
			$data['column_name'] = $this->language->get('column_name');

			$data['column_image'] = $this->language->get('column_image');

			$data['column_unit'] = $this->language->get('column_unit');

			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			$data['delivered'] = false;
			$data['coupon_cashback'] = false;

			$data['can_return'] = false;

			if(isset($order_info['date_modified'])) {

				
				$start = date('Y-m-d H:i:s');
				
				//echo "<pre>";print_r($order_info['date_modified']);die;
				//$end = date_create($order_info['date_modified']);
				$end =$order_info['date_modified'];

				$timeFirst  = strtotime($start);
				$timeSecond = strtotime($end);

				//echo "<pre>";print_r($start."Cer");print_r($end);die;
				$differenceInSeconds = $timeFirst - $timeSecond;

				//echo "<pre>";print_r($this->config->get('config_return_timeout'));die;
				if($differenceInSeconds <= $this->config->get('config_return_timeout')) {
					$data['can_return'] = true;
				}
				//echo "<pre>";print_r($differenceInSeconds);die;

			}
			

			foreach ($this->config->get('config_complete_status') as $key => $value) {
				if($value == $order_info['order_status_id']) {
					$data['delivered'] = true;
					$data['coupon_cashback'] = true;
					break;
				}
			}

						
			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}
			
			if ($order_info['settlement_amount']) {
				$data['settlement_amount'] = $this->currency->format($order_info['settlement_amount']);
			} else {
				$data['settlement_amount'] = null;
			}

			$data['text_rating'] = $this->language->get('text_rating');
			$data['text_review'] = $this->language->get('text_review');
            $data['text_send'] = $this->language->get('text_send');

			$data['text_send_rating'] = $this->language->get('text_send_rating');
			$data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
			$data['text_completed'] = $this->language->get('text_completed');
        	$data['text_cancelled'] = $this->language->get('text_cancelled');

        	$data['text_not_avialable'] = $this->language->get('text_not_avialable');
        	$data['text_picked'] = $this->language->get('text_picked');
        	$data['text_replaced'] = $this->language->get('text_replaced');
        	$data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
        	$data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
        	$data['text_real_amount'] = $this->language->get('text_real_amount');
        	
        	
			$data['text_replacable_title'] = $this->language->get('text_replacable_title');
            $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');
			$data['text_replacable'] = $this->language->get('text_replacable');
        	$data['text_not_replacable'] = $this->language->get('text_not_replacable');
			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
			
			$data['payment_method'] = $order_info['payment_method'];

			$data['shipping_name'] = $order_info['shipping_name'];
			$data['shipping_contact_no'] = $order_info['shipping_contact_no'];

			$data['shipping_address'] = $order_info['shipping_flat_number'].", ".$order_info['shipping_building_name'].", ".$order_info['shipping_landmark'];

			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_city'] = $order_info['shipping_city'];
				
			$data['delivery_timeslot'] = $order_info['delivery_timeslot'];

			$data['order_status_id'] = $order_info['order_status_id'];
				
			$data['delivery_date'] = $order_info['delivery_date'];
			
			$data['store_name'] = $order_info['store_name'];
			$data['store_address'] = $order_info['store_address'];
			$data['status']	= $order_info['status'];
					
			$this->load->model('assets/product');
			$this->load->model('tool/upload');

			$data['email'] = $this->config->get('config_delivery_username'); 
	        $data['password'] = $this->config->get('config_delivery_secret'); 

	        $data['delivery_id'] =  $order_info['delivery_id'];//"del_XPeEGFX3Hc4ZeWg5";//

	        $data['rating'] =  is_null($order_info['rating'])?0:$order_info['rating'];//"del_XPeEGFX3Hc4ZeWg5";//

	        //echo "<pre>";print_r($data['rating']);die;
	        //$data['delivery_id'] =  26;
	        $data['shopper_link'] = $this->config->get('config_shopper_link').'/storage/';

	        $data['products_status'] = [];
	        $data['delivery_data'] = [];
	        
	        $log = new Log('error.log');
	        
	        if(isset($data['delivery_id'])) {
	        	$response = $this->load->controller('deliversystem/deliversystem/getToken',$data);
		        

		        if($response['status']) {
		            $data['token'] = $response['token']; 
		            $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus',$data);

		            //echo "<pre>";print_r($productStatus);die;
		            $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus',$data);
		            //echo "<pre>";print_r($resp);die;
		            //$data['delivery_id'] = '';
		            if(!$resp['status'] || isset($resp['error'])) {
		            	$data['delivery_data'] = [];


		            } else {
		            	$data['delivery_data'] = $resp['data'][0];

		            	//delivery_data->delivery_id
		            }

		            if(!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
		            	$data['products_status'] = [];
		            } else {
		            	$data['products_status'] = $productStatus['data']	;
		            }

					$log->write('order log');
					$log->write($data['products_status']);


		            //echo "<pre>";print_r($data['products_status']);die;
		        }
	        }
	        


			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

			//echo "<pre>";print_r($products);die;
			$returnProductCount = 0;
			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$this->load->model('tool/image');

				if ( file_exists( DIR_IMAGE .$product['image'] ) ) {
		            $image = $this->model_tool_image->resize( $product['image'], 80, 100 );
		        } else {
		            $image = $this->model_tool_image->resize( 'placeholder.png', 80,100 );
		        }

		        $return_status = '';

		        if(isset($product['return_id']) && !is_null($product['return_id'])) {

		        	$this->load->model('account/return');

		        	//$returnDetails = $this->model_account_return->getReturnHistories($product['return_id']);
		        	$returnDetails = $this->model_account_return->getReturn($product['return_id']);

		        	if(count($returnDetails) > 0) {
		        		$return_status = $returnDetails['status'];	
		        	}
					
		        }else{
					$returnProductCount = $returnProductCount +1;
				}
		        
				$data['products'][] = array(
				    'product_id' => $product['product_id'],
					'store_id'     => $product['store_id'],
					'vendor_id'     => $product['vendor_id'],
					'name'     => $product['name'],
					'unit'     => $product['unit'],
					'model'    => $product['model'],
					'product_type'    => $product['product_type'],
					'image'    => $image,
					'option'   => $option_data,
					'return_id'    => $product['return_id'],
					'return_status'    => $return_status,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
				);
				
			}

			$log->write($data['products']);
			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			$data['newTotal'] = $this->currency->format(0);

			//echo "<pre>";print_r($totals);die;
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);

				if($total['code'] == 'sub_total') {
					$data['subtotal'] = $total['value'];
					
				}
				if($total['code'] == 'total') {
					$temptotal = $total['value'];
				}

				$data['plain_settlement_amount'] = $order_info['settlement_amount'];
				if(isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {

					$data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
				}
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

	        $data['base'] = $server;
	        
			
			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/orderSummaryHeader');

			$data['total_products'] = count($data['products']);
			$data['total_quantity'] = 0;
			foreach ($data['products'] as $product) {
				$data['total_quantity'] += $product['quantity'];
			}

			$data['show_rating'] = false;
			$data['take_rating'] = false;

			if( in_array($data['order_status_id'] , $this->config->get( 'config_complete_status' )) ) {
				$data['show_rating'] = true;

				if( is_null($data['rating']) || empty($data['rating'])) {
					$data['take_rating'] = true;
				}
			}
			

			$this->load->model('localisation/return_reason');
			$data['entry_reason'] = $this->language->get('entry_reason');
	        $data['entry_return_action'] = 'Desired Action';
            $data['entry_opened'] = $this->language->get('entry_opened');
		    $data['entry_fault_detail'] = $this->language->get('entry_fault_detail');
			$data['text_yes'] = $this->language->get('text_yes');
		    $data['text_no'] = $this->language->get('text_no');
			$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();
			$data['return_actions'] = $this->model_localisation_return_reason->getReturnActions();
			$data['button_submit'] = $this->language->get('button_submit');
		    $data['button_back'] = $this->language->get('button_back');
			$data['action'] = $this->url->link('account/return/multipleproducts', '', 'SSL');
			$data['returnProductCount'] = $returnProductCount;
			if ($this->config->get('config_return_id')) {
			$this->load->model('assets/information');

			$information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_return_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
			} else {
				$data['text_agree'] = '';
			}
			//echo "<pre>";print_r($data);die;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/order_info.tpl', $data));
			}
		} else {
			
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_no_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/orderSummaryHeader');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function realinfo() {
		$this->load->language('account/order');

		$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
				
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		$data['kondutoStatus'] = $this->config->get('config_konduto_status');
		
		$data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		$data['cashback_condition'] = $this->language->get('cashback_condition');

		if ($order_info) {

			$data['cashbackAmount'] = $this->currency->format(0);

			$coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

	        if(count($coupon_history_data) > 0) {
	            $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount'])); 
	        }

	        

			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['text_go_back'] = $this->language->get('text_go_back');
			$data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
			$data['text_items'] = $this->language->get('text_items');
			
			
			$data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
			$data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_processing'] = $this->language->get('text_processing');
			$data['text_shipped'] = $this->language->get('text_shipped');
			$data['text_delivered'] = $this->language->get('text_delivered');
			$data['text_name'] = $this->language->get('text_name');
			$data['text_contact_no'] = $this->language->get('text_contact_no');
			$data['text_estimated_datetime'] = $this->language->get ('text_estimated_datetime');
			$data['text_cancel'] = $this->language->get ('text_cancel');
			
			$data['column_name'] = $this->language->get('column_name');

			$data['column_image'] = $this->language->get('column_image');

			$data['column_unit'] = $this->language->get('column_unit');

			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			$data['delivered'] = false;
			$data['coupon_cashback'] = false;

			foreach ($this->config->get('config_complete_status') as $key => $value) {
				if($value == $order_info['order_status_id']) {
					$data['delivered'] = true;
					$data['coupon_cashback'] = true;
					break;
				}
			}

						
			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}
			
			if ($order_info['settlement_amount']) {
				$data['settlement_amount'] = $this->currency->format($order_info['settlement_amount']);
			} else {
				$data['settlement_amount'] = null;
			}

			$data['text_rating'] = $this->language->get('text_rating');
			$data['text_review'] = $this->language->get('text_review');
            $data['text_send'] = $this->language->get('text_send');

			$data['text_send_rating'] = $this->language->get('text_send_rating');
			$data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
			$data['text_completed'] = $this->language->get('text_completed');
        	$data['text_cancelled'] = $this->language->get('text_cancelled');
        	$data['text_not_avialable'] = $this->language->get('text_not_avialable');
        	$data['text_picked'] = $this->language->get('text_picked');

        	$data['text_replaced'] = $this->language->get('text_replaced');

        	$data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
        	$data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
        	$data['text_real_amount'] = $this->language->get('text_real_amount');
        	
        	
			$data['text_replacable_title'] = $this->language->get('text_replacable_title');
            $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');
			$data['text_replacable'] = $this->language->get('text_replacable');
        	$data['text_not_replacable'] = $this->language->get('text_not_replacable');
			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
			
			$data['payment_method'] = $order_info['payment_method'];

			$data['shipping_name'] = $order_info['shipping_name'];
			$data['shipping_contact_no'] = $order_info['shipping_contact_no'];

			$data['shipping_address'] = $order_info['shipping_flat_number'].", ".$order_info['shipping_building_name'].", ".$order_info['shipping_landmark'];

			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_city'] = $order_info['shipping_city'];
				
			$data['delivery_timeslot'] = $order_info['delivery_timeslot'];

			$data['order_status_id'] = $order_info['order_status_id'];
				
			$data['delivery_date'] = $order_info['delivery_date'];
			
			$data['store_name'] = $order_info['store_name'];
			$data['store_address'] = $order_info['store_address'];
			$data['status']	= $order_info['status'];
					
			$this->load->model('assets/product');
			$this->load->model('tool/upload');

			$data['email'] = $this->config->get('config_delivery_username'); 
	        $data['password'] = $this->config->get('config_delivery_secret'); 

	        $data['delivery_id'] =  $order_info['delivery_id'];//"del_XPeEGFX3Hc4ZeWg5";//
	        //$data['delivery_id'] =  26;
	        $data['shopper_link'] = $this->config->get('config_shopper_link').'/storage/';

	        $data['products_status'] = [];
	        $data['delivery_data'] = [];
	        
	        $log = new Log('error.log');
	        
	        if(isset($data['delivery_id'])) {
	        	$response = $this->load->controller('deliversystem/deliversystem/getToken',$data);
		        

		        if($response['status']) {
		            $data['token'] = $response['token']; 
		            $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus',$data);

		            //echo "<pre>";print_r($productStatus);die;
		            $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus',$data);
		            //echo "<pre>";print_r($resp);die;
		            $data['delivery_id'] = '';
		            if(!$resp['status'] || isset($resp['error'])) {
		            	$data['delivery_data'] = [];


		            } else {
		            	$data['delivery_data'] = $resp['data'][0];

		            	//delivery_data->delivery_id
		            }

		            if(!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
		            	$data['products_status'] = [];
		            } else {
		            	$data['products_status'] = $productStatus['data']	;
		            }

					$log->write('order log');
					$log->write($data['products_status']);


		            //echo "<pre>";print_r($data['products_status']);die;
		        }
	        }
	        


			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getRealOrderProducts($this->request->get['order_id']);

//			echo "<pre>";print_r($products);die;
			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$this->load->model('tool/image');

				if ( isset($product['image']) && file_exists( DIR_IMAGE .$product['image'] ) ) {
		            $image = $this->model_tool_image->resize( $product['image'], 80, 100 );
		        } else {
		            $image = $this->model_tool_image->resize( 'placeholder.png', 80,100 );
		        }
		        

				$data['products'][] = array(
					'store_id'     => $product['store_id'],
					'vendor_id'     => $product['vendor_id'],
					'name'     => $product['name'],
					'unit'     => $product['unit'],
					'model'    => $product['model'],
					'product_type'    => $product['product_type'],
					//'product_type'    => $product['product_type'],
					'image'    => $image,
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$log->write($data['products']);
			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			$data['newTotal'] = $this->currency->format(0);

			//echo "<pre>";print_r($totals);die;
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);

				if($total['code'] == 'sub_total') {
					$data['subtotal'] = $total['value'];
					
				}
				if($total['code'] == 'total') {
					$temptotal = $total['value'];
				}

				$data['plain_settlement_amount'] = $order_info['settlement_amount'];
				if(isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {

					$data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
				}
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

	        $data['base'] = $server;
	        
			
			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/onlyHeader');
			$data['text_products'] = $this->language->get('text_products');
			$data['total_products'] = count($data['products']);
			$data['total_quantity'] = 0;
			foreach ($data['products'] as $product) {
				$data['total_quantity'] += $product['quantity'];
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/real_order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/real_order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/real_order_info.tpl', $data));
			}
		} else {
			
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_no_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/onlyHeader');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}


	public function reorder() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			if (isset($this->request->get['order_product_id'])) {
				$order_product_id = $this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

			$order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

			if ($order_product_info) {
				$this->load->model('assets/product');

				$product_info = $this->model_assets_product->getProduct($order_product_info['product_id']);

				if ($product_info) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}

					$this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data, false, $order_product_info['store_id']);

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $product_info['name'], $this->url->link('checkout/cart'));
					//$this->url->link('product/product', 'product_id=' . $product_info['product_id']),

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				} else {
					$this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
				}
			}
		}

		$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id));
	}

	public function refundCancelOrder() {

        require_once DIR_SYSTEM . 'library/Iugu.php';
        
        $data['status'] = false;

        $log = new Log('error.log');

        $order_id = isset($this->request->post['order_id'])?$this->request->post['order_id']:null;

        if($order_id) {
        	
        	$data['settlement_tab'] = false;

	        $this->load->model('sale/order');
	        $this->load->model('checkout/order');
	        /*$iuguData =  $this->model_sale_order->getOrderIugu($order_id);

	        $log->write('refundCancelOrder');
	        $log->write($iuguData);
	        if($iuguData) {

	            $invoiceId = $iuguData['invoice_id'];

	            Iugu::setApiKey($this->config->get('iugu_token'));


	            $invoice = Iugu_Invoice::fetch($invoiceId);
	            $resp = $invoice->refund();

	            $log->write('refundAPI');
	            $log->write($resp);

				if($resp) {
	            
	            } else {
	                $data['status'] = false;
	            }
	        }*/

	        //update order status as cancelled
	        $order_info = $this->model_checkout_order->getOrder($order_id);

	        $log->write($order_id);

	        $notify = true;
	        $comment = 'Order ID #'.$order_id.' Cancelled';

	        $this->load->model('localisation/order_status');

	        $order_status = $this->model_localisation_order_status->getOrderStatuses();

	        $order_status_id = false;
	        foreach ($order_status as $order_state) {
	            if(strtolower($order_state['name']) == 'cancelled' || 'cancelada' == strtolower($order_state['name'])) {
	                $order_status_id = $order_state['order_status_id'];
	                break;
	            }
	        }

	        $log->write($order_status_id);
	        if ($order_info && $order_status_id) {
	            $log->write('if order his');
	            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify);

	            $data['status'] = true;
	        } else {
	            $data['status'] = false;
	        }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
       
    }

    public function can_return() {
		$this->load->language('account/order');

		$resp['can_return'] = false;

		$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
				
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {

			

			if(isset($order_info['date_modified'])) {

				
				$start = date('Y-m-d H:i:s');
				
				//echo "<pre>";print_r($order_info['date_modified']);die;
				//$end = date_create($order_info['date_modified']);
				$end =$order_info['date_modified'];

				$timeFirst  = strtotime($start);
				$timeSecond = strtotime($end);

				//echo "<pre>";print_r($start."Cer");print_r($end);die;
				$differenceInSeconds = $timeFirst - $timeSecond;

				//echo "<pre>";print_r($this->config->get('config_return_timeout'));die;
				if($differenceInSeconds <= $this->config->get('config_return_timeout')) {
					$resp['can_return'] = true;

				}
				//echo "<pre>";print_r($differenceInSeconds);die;

			}
		}
		//$resp['can_return'] = false;

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($resp));

	}
	
	public function accept_delivery_submit() {
		try{
		$this->load->language('account/order');

		$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
				
		$order_id = $this->request->post['order_id'];
		$products =  $this->request->post['products'];
		$return_replace_count = $this->request->post['return_replace'];
        foreach($products as $product){
			$product_id = $product[0];
			$action = $product[2];
			$action_note = $product[3];
			//echo "UPDATE `" . DB_PREFIX . "order_product` SET 	on_delivery_action = '" . $action . "', delivery_action_note = '" . $action_note . "' WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'";
			//echo '<br>';
			$this->db->query( "UPDATE `" . DB_PREFIX . "order_product` SET 	on_delivery_action = '" . $action . "', delivery_action_note = '" . $action_note . "' WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'" );
		}

		if($return_replace_count > 0){
			$orderStatus = 'Partially Delivered';
		}else{
			$orderStatus = 'Delivered';
		}

		$sql = "SELECT order_status_id FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND name='".$orderStatus."'";
		$query = $this->db->query($sql);
		$order_status_id = $query->row['order_status_id'];
		//echo "Order_status_id "+$order_status_id;
		$comment = "Automatic status change on Accept Delivery";

		//echo "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'";
		//echo "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', comment = '" . $this->db->escape( $comment ) . "', date_added = NOW()";
		//exit;
		$this->db->query( "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'" );
		$this->db->query( "INSERT INTO `" . DB_PREFIX . "order_history` SET order_id = '" . (int) $order_id . "', comment = '" . $this->db->escape( $comment ) . "', date_added = NOW()" );
		  $resp['status'] = true;
	    }catch(Exception $e){
	      $resp['status'] = false;
		}
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($resp));
	}

	
	public function accept_reject() {
	
		//echo '<pre>';print_r($_REQUEST);exit;
		$this->load->language('account/order');
		$this->load->language('account/return');

		$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
				
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		$data['kondutoStatus'] = $this->config->get('config_konduto_status');
		
		$data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		$data['cashback_condition'] = $this->language->get('cashback_condition');

		if ($order_info) {

			$data['cashbackAmount'] = $this->currency->format(0);

			$coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

	        if(count($coupon_history_data) > 0) {
	            $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount'])); 
	        }

	        

			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['text_go_back'] = $this->language->get('text_go_back');
			$data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
			$data['text_items'] = $this->language->get('text_items');
			$data['text_products'] = $this->language->get('text_products');
			
			$data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
			$data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_processing'] = $this->language->get('text_processing');
			$data['text_shipped'] = $this->language->get('text_shipped');
			$data['text_delivered'] = $this->language->get('text_delivered');
			$data['text_name'] = $this->language->get('text_name');
			$data['text_contact_no'] = $this->language->get('text_contact_no');
			$data['text_estimated_datetime'] = $this->language->get ('text_estimated_datetime');
			$data['text_cancel'] = $this->language->get ('text_cancel');
			
			$data['column_name'] = $this->language->get('column_name');

			$data['column_image'] = $this->language->get('column_image');

			$data['column_unit'] = $this->language->get('column_unit');

			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			$data['delivered'] = false;
			$data['coupon_cashback'] = false;

			$data['can_return'] = false;

			if(isset($order_info['date_modified'])) {

				
				$start = date('Y-m-d H:i:s');
				
				//echo "<pre>";print_r($order_info['date_modified']);die;
				//$end = date_create($order_info['date_modified']);
				$end =$order_info['date_modified'];

				$timeFirst  = strtotime($start);
				$timeSecond = strtotime($end);

				//echo "<pre>";print_r($start."Cer");print_r($end);die;
				$differenceInSeconds = $timeFirst - $timeSecond;

				//echo "<pre>";print_r($this->config->get('config_return_timeout'));die;
				if($differenceInSeconds <= $this->config->get('config_return_timeout')) {
					$data['can_return'] = true;
				}
				//echo "<pre>";print_r($differenceInSeconds);die;

			}
			

			foreach ($this->config->get('config_complete_status') as $key => $value) {
				if($value == $order_info['order_status_id']) {
					$data['delivered'] = true;
					$data['coupon_cashback'] = true;
					break;
				}
			}

						
			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}
			
			if ($order_info['settlement_amount']) {
				$data['settlement_amount'] = $this->currency->format($order_info['settlement_amount']);
			} else {
				$data['settlement_amount'] = null;
			}

			$data['text_rating'] = $this->language->get('text_rating');
			$data['text_review'] = $this->language->get('text_review');
            $data['text_send'] = $this->language->get('text_send');

			$data['text_send_rating'] = $this->language->get('text_send_rating');
			$data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
			$data['text_completed'] = $this->language->get('text_completed');
        	$data['text_cancelled'] = $this->language->get('text_cancelled');

        	$data['text_not_avialable'] = $this->language->get('text_not_avialable');
        	$data['text_picked'] = $this->language->get('text_picked');
        	$data['text_replaced'] = $this->language->get('text_replaced');
        	$data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
        	$data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
        	$data['text_real_amount'] = $this->language->get('text_real_amount');
        	
        	
			$data['text_replacable_title'] = $this->language->get('text_replacable_title');
            $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');
			$data['text_replacable'] = $this->language->get('text_replacable');
        	$data['text_not_replacable'] = $this->language->get('text_not_replacable');
			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
			
			$data['payment_method'] = $order_info['payment_method'];

			$data['shipping_name'] = $order_info['shipping_name'];
			$data['shipping_contact_no'] = $order_info['shipping_contact_no'];

			$data['shipping_address'] = $order_info['shipping_flat_number'].", ".$order_info['shipping_building_name'].", ".$order_info['shipping_landmark'];

			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_city'] = $order_info['shipping_city'];
				
			$data['delivery_timeslot'] = $order_info['delivery_timeslot'];

			$data['order_status_id'] = $order_info['order_status_id'];
				
			$data['delivery_date'] = $order_info['delivery_date'];
			
			$data['store_name'] = $order_info['store_name'];
			$data['store_address'] = $order_info['store_address'];
			$data['status']	= $order_info['status'];
					
			$this->load->model('assets/product');
			$this->load->model('tool/upload');

			$data['email'] = $this->config->get('config_delivery_username'); 
	        $data['password'] = $this->config->get('config_delivery_secret'); 

	        $data['delivery_id'] =  $order_info['delivery_id'];//"del_XPeEGFX3Hc4ZeWg5";//

	        $data['rating'] =  is_null($order_info['rating'])?0:$order_info['rating'];//"del_XPeEGFX3Hc4ZeWg5";//

	        //echo "<pre>";print_r($data['rating']);die;
	        //$data['delivery_id'] =  26;
	        $data['shopper_link'] = $this->config->get('config_shopper_link').'/storage/';

	        $data['products_status'] = [];
	        $data['delivery_data'] = [];
	        
	        $log = new Log('error.log');
	        
	        if(isset($data['delivery_id'])) {
	        	$response = $this->load->controller('deliversystem/deliversystem/getToken',$data);
		        

		        if($response['status']) {
		            $data['token'] = $response['token']; 
		            $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus',$data);

		            //echo "<pre>";print_r($productStatus);die;
		            $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus',$data);
		            //echo "<pre>";print_r($resp);die;
		            //$data['delivery_id'] = '';
		            if(!$resp['status'] || isset($resp['error'])) {
		            	$data['delivery_data'] = [];


		            } else {
		            	$data['delivery_data'] = $resp['data'][0];

		            	//delivery_data->delivery_id
		            }

		            if(!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
		            	$data['products_status'] = [];
		            } else {
		            	$data['products_status'] = $productStatus['data']	;
		            }

					$log->write('order log');
					$log->write($data['products_status']);


		            //echo "<pre>";print_r($data['products_status']);die;
		        }
	        }
	        


			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

			//echo "<pre>";print_r($products);die;
			$returnProductCount = 0;
			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$this->load->model('tool/image');

				if ( file_exists( DIR_IMAGE .$product['image'] ) ) {
		            $image = $this->model_tool_image->resize( $product['image'], 80, 100 );
		        } else {
		            $image = $this->model_tool_image->resize( 'placeholder.png', 80,100 );
		        }

		        $return_status = '';

		        if(isset($product['return_id']) && !is_null($product['return_id'])) {

		        	$this->load->model('account/return');

		        	//$returnDetails = $this->model_account_return->getReturnHistories($product['return_id']);
		        	$returnDetails = $this->model_account_return->getReturn($product['return_id']);

		        	if(count($returnDetails) > 0) {
		        		$return_status = $returnDetails['status'];	
		        	}
					
		        }else{
					$returnProductCount = $returnProductCount +1;
				}
		        
				$data['products'][] = array(
				    'product_id' => $product['product_id'],
					'store_id'     => $product['store_id'],
					'vendor_id'     => $product['vendor_id'],
					'name'     => $product['name'],
					'unit'     => $product['unit'],
					'model'    => $product['model'],
					'product_type'    => $product['product_type'],
					'image'    => $image,
					'option'   => $option_data,
					'return_id'    => $product['return_id'],
					'return_status'    => $return_status,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
				);
				
			}

			$log->write($data['products']);
			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			$data['newTotal'] = $this->currency->format(0);

			//echo "<pre>";print_r($totals);die;
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);

				if($total['code'] == 'sub_total') {
					$data['subtotal'] = $total['value'];
					
				}
				if($total['code'] == 'total') {
					$temptotal = $total['value'];
				}

				$data['plain_settlement_amount'] = $order_info['settlement_amount'];
				if(isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {

					$data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
				}
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

	        $data['base'] = $server;
	        
			
			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/onlyHeader');

			$data['total_products'] = count($data['products']);
			$data['total_quantity'] = 0;
			foreach ($data['products'] as $product) {
				$data['total_quantity'] += $product['quantity'];
			}

			$data['show_rating'] = false;
			$data['take_rating'] = false;

			if( in_array($data['order_status_id'] , $this->config->get( 'config_complete_status' )) ) {
				$data['show_rating'] = true;

				if( is_null($data['rating']) || empty($data['rating'])) {
					$data['take_rating'] = true;
				}
			}
			

			$this->load->model('localisation/return_reason');
			$data['entry_reason'] = $this->language->get('entry_reason');
	        $data['entry_return_action'] = 'Desired Action';
            $data['entry_opened'] = $this->language->get('entry_opened');
		    $data['entry_fault_detail'] = $this->language->get('entry_fault_detail');
			$data['text_yes'] = $this->language->get('text_yes');
		    $data['text_no'] = $this->language->get('text_no');
			$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();
			$data['return_actions'] = $this->model_localisation_return_reason->getReturnActions();
			$data['button_submit'] = $this->language->get('button_submit');
		    $data['button_back'] = $this->language->get('button_back');
			$data['action'] = $this->url->link('account/return/multipleproducts', '', 'SSL');
			$data['returnProductCount'] = $returnProductCount;
			if ($this->config->get('config_return_id')) {
			$this->load->model('assets/information');

			$information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_return_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
			} else {
				$data['text_agree'] = '';
			}
			//echo "<pre>";print_r($data);die;
			
			// Payment Methods
			$mpesaOnline = false;
			$method_data = array();

			$this->load->model('extension/extension');
	
			$results = $this->model_extension_extension->getExtensions('payment');
			
			//echo "<pre>";print_r($results);die;  
			$recurring = $this->cart->hasRecurringProducts();
	
			foreach ($results as $result) {
		  
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('payment/' . $result['code']);
	
					$method = $this->{'model_payment_' . $result['code']}->getMethod($total);
					
					if ($method) {
						if ($recurring) {
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}
			$sort_order = array();
			
			//echo "<pre>";print_r($method_data);die;  
	
			foreach ($method_data as $key => $value) {
				if($key == 'mpesa'){
					$mpesaOnline = true;
				}
				$sort_order[$key] = $value['sort_order'];
			}
			
			//echo "<pre>===";print_r($mpesaOnline);die;  
			array_multisort($sort_order, SORT_ASC, $method_data);
			$data['mpesaOnline'] = $mpesaOnline;
			$data['account'] = $this->url->link('account/order');
			$data['continue'] = $this->url->link('checkout/success');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_accept_delivery.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_accept_delivery.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/order_accept_delivery	.tpl', $data));
			}
		} else {
			
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_no_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/onlyHeader');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

}