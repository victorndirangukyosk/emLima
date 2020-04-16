<?php

class ControllerProductCategory extends Controller {

	public function index() {

		$this->load->language('product/category');

		$this->load->model('assets/category');

		$this->load->model('assets/product');

		$this->load->model('tool/image');

		$data['account_register'] = $this->load->controller('account/register');
		$data['login_modal'] = $this->load->controller('common/login_modal');
		$data['signup_modal'] = $this->load->controller('common/signup_modal');
		$data['forget_modal'] = $this->load->controller('common/forget_modal');

		$data['telephone_mask'] = $this->config->get('config_telephone_mask');

		if(isset($data['telephone_mask'])) {
			$data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));	
		}

		$data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if(isset($data['taxnumber_mask'])) {
        	$data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));	
        }
        

		$data['lists'] = [];

		if ( $this->customer->isLogged() ) {
           $data['lists'] = $this->model_assets_category->getUserLists();
        }

        $data['text_add_to_list'] = $this->language->get( 'text_add_to_list' );
        $data['text_list_name'] = $this->language->get( 'text_list_name' );
        $data['text_add_to'] = $this->language->get( 'text_add_to' );
        $data['text_confirm'] = $this->language->get( 'text_confirm' );
        $data['text_or'] = $this->language->get( 'text_or' );
        $data['text_enter_list_name'] = $this->language->get( 'text_enter_list_name' );
        $data['text_create_list'] = $this->language->get( 'text_create_list' );


		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if(isset($this->session->data['config_store_id'])) {
			$store_id = $this->session->data['config_store_id'];
		} else {
			$store_id = 0;
		}
		
		$store_info =  $this->model_tool_image->getStore($store_id);     

		if(!$store_info){ 
			unset($this->session->data['config_store_id']);
			$this->response->redirect($this->url->link('common/home'));
		}
		
		

		if (isset($this->request->get['category'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string) $this->request->get['category']);

			$category_id = (int) array_pop($parts);
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int) $path_id;
				} else {
					$path .= '_' . (int) $path_id;
				}

				$category_info = $this->model_assets_category->getCategory($path_id);
				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'category=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$data['location_name'] = '';
		$data['zipcode'] = '';
		if(count($_COOKIE) > 0 && isset($_COOKIE['zipcode']) ) {
		   $data['zipcode'] = $_COOKIE['zipcode'];
		} elseif(count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
		    $data['location_name'] = $this->getHeaderPlace($_COOKIE['location']);
		    $data['location_name_full'] = $data['location_name'];

		    /*if(isset($_COOKIE['location_name'])) {
                $data['location_name'] = $_COOKIE['location_name'];
            }*/
            
		}


		$category_info = $this->model_assets_category->getCategory($category_id);

		$data['toHome'] = $this->url->link('common/home/toHome');
        $data['toStore'] = $this->url->link('common/home/toStore');

		if ($category_info) {
			
			$title = empty($category_info['meta_title']) ? $category_info['name'] : $category_info['meta_title'];

			$this->document->setTitle($title);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);
			if (!$this->config->get('config_seo_url')) {
				$this->document->addLink($this->url->link('product/category', 'category=' . $this->request->get['category']), 'canonical');
			}
			
			$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_categories.css');
			$data['text_change_locality'] = $this->language->get( 'text_change_locality' );
			$data['text_change_location_name'] = $this->language->get('text_change_location_name');
			$data['heading_title'] = $category_info['name'];
			$data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
			$data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
			$data['button_change_locality'] = $this->language->get( 'button_change_locality' );
        	$data['button_change_store'] = $this->language->get( 'button_change_store' );
			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_no_more_products'] = $this->language->get('text_no_more_products');
			
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');
			$data['text_incart'] = $this->language->get( 'text_incart' );
			$data['text_view'] = $this->language->get( 'text_view' );

			$data['error_no_delivery'] = $this->language->get('error_no_delivery');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');
			$data['button_add'] = $this->language->get( 'button_add' );
			$data['button_clear_cart'] = $this->language->get('button_clear_cart');
			$data['button_checkout'] = $this->language->get('button_checkout');
			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_assets_category->getCategories($category_id);

			//echo "<pre>";print_r($results);die;
			//top category 
			if($results){
				foreach ($results as $result) {
					$filter_data = array(
						'filter_category_id' => $result['category_id'],
						'filter_sub_category' => true,
						'start' => 0,
						'limit' => 6
					);

					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));

					$data['categories'][] = array(
						'name' => $result['name'],
						'products' => $this->getProducts($filter_data),
						'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '_' . $result['category_id'] . $url),
						'thumb' => $image
					);
				}
				
				$template = 'top_category.tpl';
			}else{
				$data['products'] = array();

				$filter_data = array(
					'filter_category_id' => $category_id,
					'filter_filter' => $filter,
					'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit
				);

				$product_total = $this->model_assets_product->getTotalProducts($filter_data);

				$data['products'] = $this->getProducts($filter_data);
				
				
				$template = 'category.tpl';
				
				$url = '';

				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
			
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('product/category', 'category=' . $this->request->get['category'] . $url . '&page={page}');

				$data['pagination'] = $pagination->render();

				/*print_r($product_total."ss");
				print_r($page."ss");
				print_r($limit."ss");
				echo "<pre>";print_r($data['pagination']);die;*/
				$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text' => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text' => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text' => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text' => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text' => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text' => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text' => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text' => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text' => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach ($limits as $value) {
				$data['limits'][] = array(
					'text' => $value,
					'value' => $value,
					'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . $url . '&limit=' . $value)
				);
			}
		  
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if(isset($this->session->data['warning'])){
				$data['warning'] = $this->session->data['warning'];
				unset($this->session->data['warning']);
			}else{
			   $data['warning'] = ''; 
			}

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

	        $data['base'] = $server;
        
			$not_delivery = 0;

	        /*if($this->cart->hasProducts()){
	            $zipcode = $_COOKIE['zipcode'];

	            foreach ($this->cart->getStores() as $s ) {
	                $checkStore = $this->model_assets_product->getStoreByZip($zipcode,$s);
	                if (!$checkStore) {
	                    $not_delivery = 1;
	                    break;
	                }
	            }
	        }*/
	        
	        $data['not_delivery'] = $not_delivery;
	        $data['home'] = $this->url->link('common/home');


	        //echo "<pre>";print_r($data['products']);die;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/'.$template)) {

				//Mvgv2/template/product/top_category.tpl
				//echo "<pre>";print_r('/template/product/'.$template);die;
				//echo "<pre>";print_r($data);die;
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/'.$template, $data));
			} else {
				//echo "<pre>s";print_r($this->config->get('config_template') . '/template/product/'.$template);die;
				$this->response->setOutput($this->load->view('default/template/product/'.$template, $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['category'])) {
				$url .= '&category=' . $this->request->get['category'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

			$data['continue'] = $server;

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
			
			$this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');
			
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header/onlyHeader');

			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

	        $data['base'] = $server;


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function getProducts($filter_data){
		
		$this->load->model('assets/product');
		$this->load->model('tool/image');
		
		$results = $this->model_assets_product->getProducts($filter_data);

		$data['products'] = array();
		
		//echo "<pre>";print_r($results);die;	
		foreach ( $results as $result ) {

			// if qty less then 1 dont show product 
			if($result['quantity'] <= 0)
                continue;

			if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
				$image = $this->model_tool_image->resize( $result['image'], $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
			} else {
				$image = $this->model_tool_image->resize( 'placeholder.png', $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
			}
			
			$s_price = 0;
            $o_price = 0;
            
			if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
				//get price html
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
					$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );

					$o_price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

				} else {
					$price = false;
				}
				if ( (float) $result['special_price'] ) {
					$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );

					$s_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

				} else {
					$special_price = false;
				}
			}else {
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
					$price = $this->currency->format( $result['price'] );
				} else {
					$price = $result['price'];
				}

				if ( (float) $result['special_price'] ) {
					$special_price = $this->currency->format( $result['special_price'] );
				} else {
					$special_price = $result['special_price'];
				}

				$s_price = $result['special_price'];
                $o_price = $result['price'];
			}


			//get qty in cart
			$key = base64_encode( serialize( array( 'product_store_id' => (int) $result['product_store_id'], 'store_id'=>$this->session->data['config_store_id'] ) ) );

			if ( isset( $this->session->data['cart'][$key] ) ) {
				$qty_in_cart = $this->session->data['cart'][$key]['quantity'];
			} else {
				$qty_in_cart = 0;
			}

			//$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));
            
            $percent_off = null;
            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

			$data['products'][] = array(
				'key' => $key,
				'qty_in_cart' => $qty_in_cart,
				'variations' => $this->model_assets_product->getVariations( $result['product_store_id'] ),
				'store_product_variation_id' => 0,
				'product_id' => $result['product_id'],
				'product_store_id'=> $result['product_store_id'],
				'default_variation_name' => $result['default_variation_name'],
				'thumb' => $image,
				'name' => $name,
				'unit' => $result['unit'],
				'description' => utf8_substr( strip_tags( html_entity_decode( $result['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get( 'config_product_description_length' ) ) . '..',
				'price' => $price,
				'special' => $special_price,
				'percent_off' => number_format($percent_off,0),
				'tax' => $result['tax_percentage'],
				'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
				'rating' => 0,
				'href' => $this->url->link( 'product/product',  '&product_store_id=' . $result['product_store_id'] )
			);
		}
		
		return $data['products'];
	}

	public function getHeaderPlace($location) {

        if(isset($_COOKIE['location_name']) && !empty($_COOKIE['location_name']) ) { 
            $p = $_COOKIE['location_name'];
        } else {
            
        


            $p = '';

            $userSearch = explode(",", $location);


            if(count($userSearch) >= 2) {
                $validateLat = is_numeric($userSearch[0]);
                $validateLat2 = is_numeric($userSearch[1]);

                $validateLat3 = strpos( $userSearch[0], '.');
                $validateLat4 = strpos( $userSearch[1], '.');

                if($validateLat && $validateLat2 && $validateLat3 && $validateLat4 ) {

                    //echo "<pre>";print_r("er");die;
                    try {
                       
                        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.urlencode($location).'&sensor=false&key='.$this->config->get('config_google_server_api_key');


                        //echo "<pre>";print_r($url);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $headers = array( 
                                     "Cache-Control: no-cache", 
                                    ); 
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                        //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        
                        $response = curl_exec($ch);
                        curl_close($ch);
                        
                        $output = json_decode($response);

                        //echo "<pre>";print_r($output);die;
                        if(isset($output)) {
                            foreach ($output->results[0]->address_components as $addres) {

                                if(isset($addres->types)) {
                                    if(in_array('sublocality_level_1', $addres->types)) {
                                        //echo "<pre>";print_r($addres);die;
                                        $p = $addres->long_name;
                                        break;
                                    }
                                }
                            } 
                            if(isset($output->results[0]->formatted_address)) {
                                $p = $output->results[0]->formatted_address;
                            }

                            $_COOKIE['location_name'] = $p;
                            setcookie('location_name', $p, time() + (86400 * 30 * 30 * 30 * 3), "/");

                        }
                        
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        
        return $p;
    }
    
}
