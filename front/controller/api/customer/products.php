<?php


class ControllerApiCustomerProducts extends Controller {
	private $error = array();

	public function getProducts() {

		$json = array();

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

		$this->load->language('api/products');

		$this->load->model('assets/category');

		$this->load->model('assets/product');

		$this->load->model('tool/image');

		if(/*isset($this->request->get['page']) &&*/ isset($this->request->get['store_id']) && isset($this->request->get['category']) ) {

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
				$limit = $this->config->get('config_app_product_limit');
			}

			if(isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			}

			$store_info =  $this->model_tool_image->getStore($store_id);     

			if(!$store_info) { 
				// store not found
				//echo "r";
				$json['status'] = 10005;

	            $json['message'][] = ['type' =>  '' , 'body' =>   $this->language->get('store_not_found') ];

			} else {

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
					}
				} else {
					$category_id = 0;
				}

				$category_info = $this->model_assets_category->getCategory($category_id);

				//$this->config->set('config_language_id',2);
				//echo "<pre>";print_r($category_info);die;
				if ($category_info) {
					
					$data['title'] = empty($category_info['meta_title']) ? $category_info['name'] : $category_info['meta_title'];

					if ($category_info['image']) {
						$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
					} else {
						$data['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));;
					}

					$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
					
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

					$product_total = 0;
					//echo "<pre>";print_r($results);die;
					//top category 
					if($results){
						foreach ($results as $result) {

							$filter_data = array(
								'filter_category_id' => $result['category_id'],
								'filter_sub_category' => true,
								'start' => 0,
								'limit' => $limit
							);

							if(!empty($result['image'])) {
								$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
							} else {
								$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
							}
							
							$data['categories'][] = array(
								'name' => htmlspecialchars_decode($result['name']),
								'products' => $this->getProductsFn($filter_data,$store_id),
								'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '_' . $result['category_id'] . $url),
								'next_category_call_id' => $this->request->get['category'] . '_' . $result['category_id'],
								'thumb' => $image
							);
						}
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

						$filter_data['store_id'] = $store_id;

						$product_total = $this->model_assets_product->getTotalProductsByApi($filter_data);

						$data['products'] = $this->getProductsFn($filter_data,$store_id);
						
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

						//$data['pagination'] = $pagination->render();

						$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
					}

					$url = '';

					if (isset($this->request->get['filter'])) {
						$url .= '&filter=' . $this->request->get['filter'];
					}

					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}

					/*$data['sorts'] = array();

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
					);*/

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

					/*$data['limits'] = array();

					$limits = array_unique(array($this->config->get('config_app_product_limit'), 25, 50, 75, 100));

					sort($limits);

					foreach ($limits as $value) {
						$data['limits'][] = array(
							'text' => $value,
							'value' => $value,
							'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . $url . '&limit=' . $value),
						);
					}*/
				  
					$data['sort'] = $sort;
					$data['order'] = $order;
					$data['limit'] = $limit;
					$data['total_product'] = $product_total;

					
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

			        $json['data'] = $data;

				} else {
					//category not found
					$json['status'] = 10008;

		            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_category_not_found') ];
				}
			}
		} else {
			$store_id = $this->request->get['store_id'];
			$filter_data['store_id'] = $this->request->get['store_id'];
			$filter_data['limit'] = $this->request->get['limit'];
			$data['products'] = $this->getProductsFn($filter_data,$store_id);
			$json['status'] = 200;
			$json['data'] = $data;

			/* $json['status'] = 10010;

            $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

			http_response_code(400);
			*/

		}
		

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

	}

	public function getProductCollectionProducts() {

		$json = array();

        $this->load->language('api/products');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

		if(isset($this->request->get['store_id']) && isset($this->request->get['product_collection_id']) ) {

			$this->load->model('assets/category');

			$this->load->model('assets/product');

			$this->load->model('tool/image');

			
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
			
			$store_id = $this->request->get['store_id'];

			$store_info =  $this->model_tool_image->getStore($store_id);     

			$product_collection_id = $this->request->get['product_collection_id'];
			
			$this->load->model( 'assets/product' );

			//echo "<pre>";print_r($product_collection_id);die;

			$product_collection_info = $this->model_assets_product->getProductCollectionDescriptions( $product_collection_id );


	        //echo "<pre>";print_r($product_collection_info);die;
			if ($product_collection_info) {
				
				$title = empty($product_collection_info['meta_title']) ? $product_collection_info['name'] : $product_collection_info['meta_title'];

				$data['name'] = htmlspecialchars_decode(!empty($product_collection_info['name']) ? $product_collection_info['name'] : '');


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

				$data['products'] = array();

				$filter_data = array(
					'filter_product_collection_id' => $product_collection_id,
					'filter_filter' => $filter,
					'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit,
					'store_id' => $store_id
				);

				$product_total = $this->model_assets_product->getTotalCollectionProductsApi($filter_data);

				//echo "<pre>";print_r($product_total);die;
				$data['products'] = $this->getCollectionProducts($filter_data);
				
				//echo "<pre>";print_r($data['products']);die;
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('product/collection', 'product_collection_id=' . $this->request->get['product_collection_id'] . $url . '&page={page}');

				$data['pagination'] = $pagination->render();

				/*print_r($product_total."ss");
				print_r($page."ss");
				print_r($limit."ss");
				echo "<pre>";print_r($data['pagination']);die;*/
				$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

				$data['total_product'] = $product_total;
				
	        	$json['data'] = $data;
				//return $data;
			} else {
				// not found product_collection details

				$json['status'] = 10012;

			    $json['message'][] = ['type' =>  $this->language->get('text_not_found') , 'body' =>  $this->language->get('text_product_collection_not_found') ];

			}

		} else {

			$json['status'] = 10010;

            $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

            http_response_code(400);

		}

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

	}

	public function getCollectionProducts($filter_data){
		
		$this->load->model('assets/product');
		$this->load->model('tool/image');
		
		$store_id = $filter_data['store_id'];

		$results = $this->model_assets_product->getCollectionProductsApi($filter_data);

		$data['products'] = array();
				
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

			//$result['special_price'] = 10;
			if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
				//get price html
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
					//$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
					$price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

					$o_price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

				} else {
					$price = false;
				}
				if ( (float) $result['special_price'] ) {
					//$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
					$special_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax')) ;

					$s_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

				} else {
					$special_price = false;
				}
			}else {
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
					
					//$price = $result['price'];
					$price = $this->currency->formatWithoutCurrency($result['price']);
				} else {
					$price = $result['price'];
				}

				if ( (float) $result['special_price'] ) {
					
					//$special_price =  $result['special_price'];
					$special_price = $this->currency->formatWithoutCurrency($result['special_price']);
				} else {
					$special_price = $result['special_price'];
				}

				$s_price = $result['special_price'];
                $o_price = $result['price'];

			}

			//$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = htmlspecialchars_decode($result['name']);
            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));
            
            $percent_off = null;
            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if(is_null($special_price) || !($special_price + 0) ) {
            	//$special_price = 0;
            	$special_price = $price;
            }
			            
			$data['products'][] = array(
				/*'key' => $key,
				'qty_in_cart' => $qty_in_cart,*/
				'variations' => $this->model_assets_product->getApiVariations( $result['product_store_id'] ),
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
				'left_symbol_currency'      => $this->currency->getSymbolLeft(),
				'right_symbol_currency'      => $this->currency->getSymbolRight(),
				'tax' => $result['tax_percentage'],
				//'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
				'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
				'rating' => 0,
				'href' => $this->url->link( 'product/product',  '&product_store_id=' . $result['product_store_id'] )
			);
		}
		
		return $data['products'];
	}

	public function addProductSearch() {

    	$json = array();

    	$log = new Log('error.log');
        $log->write('api/getProductSearch');

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

		$this->load->language('api/products');

		$this->load->model('assets/category');

		$this->load->model('assets/product');

		$this->load->model('tool/image');

		if(isset($this->request->post['store_id'])) {

			if(isset($this->request->post['store_id'])) {
				$store_id = $this->request->post['store_id'];
			}

			$store_info =  $this->model_tool_image->getStore($store_id);

	        if(!$store_info) { 
				// store not found
				//echo "r";
				$json['status'] = 10005;

	            $json['message'][] = ['type' =>  '' , 'body' =>   $this->language->get('store_not_found') ];

			} else {

				if (isset($this->request->post['search'])) {
					$search = $this->request->post['search'];
				} else {
					$search = '';
				}

				if (isset($this->request->post['category_id'])) {
					$category_id = $this->request->post['category_id'];
				} else {
					$category_id = 0;
				}

				if (isset($this->request->post['sub_category'])) {
					$sub_category = $this->request->post['sub_category'];
				} else {
					$sub_category = '';
				}

				if (isset($this->request->post['sort'])) {
					$sort = $this->request->post['sort'];
				} else {
					$sort = 'p.sort_order';
				}

				if (isset($this->request->post['order'])) {
					$order = $this->request->post['order'];
				} else {
					$order = 'ASC';
				}

				if (isset($this->request->post['page'])) {
					$page = $this->request->post['page'];
				} else {
					$page = 1;
				}

				if (isset($this->request->post['limit'])) {
					$limit = $this->request->post['limit'];
				} else {
					$limit = $this->config->get('config_app_product_limit');
				}

				
				$url = '';

				if (isset($this->request->post['search'])) {
					$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->post['tag'])) {
					$url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->post['description'])) {
					$url .= '&description=' . $this->request->post['description'];
				}

				if (isset($this->request->post['category_id'])) {
					$url .= '&category_id=' . $this->request->post['category_id'];
				}

				if (isset($this->request->post['sub_category'])) {
					$url .= '&sub_category=' . $this->request->post['sub_category'];
				}

				if (isset($this->request->post['sort'])) {
					$url .= '&sort=' . $this->request->post['sort'];
				}

				if (isset($this->request->post['order'])) {
					$url .= '&order=' . $this->request->post['order'];
				}

				if (isset($this->request->post['page'])) {
					$url .= '&page=' . $this->request->post['page'];
				}

				if (isset($this->request->post['limit'])) {
					$url .= '&limit=' . $this->request->post['limit'];
				}

				$log->write($url);

				$this->load->model('assets/category');

				//$this->config->set('config_language_id',2);

				// 3 Level Category Search
				/*$data['categories'] = array();

				$categories_1 = $this->model_assets_category->getCategories(0);

				foreach ($categories_1 as $category_1) {
					$level_2_data = array();

					$categories_2 = $this->model_assets_category->getCategories($category_1['category_id']);

					foreach ($categories_2 as $category_2) {
						$level_3_data = array();

						$categories_3 = $this->model_assets_category->getCategories($category_2['category_id']);

						foreach ($categories_3 as $category_3) {
							$level_3_data[] = array(
								'category_id' => $category_3['category_id'],
								'name' => $category_3['name'],
							);
						}

						$level_2_data[] = array(
							'category_id' => $category_2['category_id'],
							'name' => $category_2['name'],
							'children' => $level_3_data
						);
					}

					$data['categories'][] = array(
						'category_id' => $category_1['category_id'],
						'name' => $category_1['name'],
						'children' => $level_2_data
					);
				}*/

				$data['products'] = array();

				if (isset($this->request->post['search']) || isset($this->request->post['tag'])) {
					
					$filter_data = array(
						'filter_name' => urldecode($search),
						'filter_name_test' => urldecode($search),
						'filter_tag' => '',
						'filter_category_id' => $category_id,
						'filter_sub_category' => $sub_category,
						'sort' => $sort,
						'order' => $order,
						'start' => ($page - 1) * $limit,
						'limit' => $limit
					);

					$filter_data['store_id'] = $store_id;

					$product_total = $this->model_assets_product->getTotalProductsByApi($filter_data);

					$log = new Log('error.log');
        			$log->write('api/search');
        			$log->write($filter_data);

					$results = $this->model_assets_product->getProductsByApi($filter_data);

					foreach ( $results as $result ) {
						if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
							$image = $this->model_tool_image->resize( $result['image'], $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
						} else {
							$image = $this->model_tool_image->resize( 'placeholder.png', $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
						}

						//$result['special_price'] = 10;

						$s_price = 0;
            			$o_price = 0;

						if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
							//get price html
							if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
								//$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
								$price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax'));

								$o_price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );
							} else {
								$price = false;
							}
							if ( (float) $result['special_price'] ) {
								//$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
								$special_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax'));

								$s_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

							} else {
								$special_price = false;
							}
						}else {
							if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
								
								//$price = $result['price'];
								$price = $this->currency->formatWithoutCurrency($result['price']);
							} else {
								$price = $result['price'];
							}

							if ( (float) $result['special_price'] ) {
								//$special_price = $result['special_price'];
								$special_price = $this->currency->formatWithoutCurrency($result['special_price']);
							} else {
								$special_price = $result['special_price'];
							}

							$s_price = $result['special_price'];
		                	$o_price = $result['price'];

						}


						//get qty in cart
						$key = base64_encode( serialize( array( 'product_store_id' => (int) $result['product_store_id'], 'store_id'=>$store_id ) ) );

						if ( isset( $this->session->data['cart'][$key] ) ) {
							$qty_in_cart = $this->session->data['cart'][$key]['quantity'];
						} else {
							$qty_in_cart = 0;
						}

						//$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
		            	$name = $result['name'];
		            	//$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

						$unit = $result['unit']?$result['unit']:false;

						$percent_off = null;
			            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
			                $percent_off = (($o_price - $s_price) / $o_price) * 100;
			            }


			            if(is_null($special_price) || !($special_price + 0) ) {
			            	//$special_price = 0;
			            	$special_price = $price;
			            }

			            /*$temp['name'] = htmlspecialchars_decode($tempProduct['name'])." - ".$tempProduct['unit'];
            		//$temp['name'] = $tempProduct['name']." - ".$tempProduct['unit'];
            		$temp['unit'] = $tempProduct['unit'];
            		$temp['only_name'] = htmlspecialchars_decode($tempProduct['name']);
            		$temp['product_id'] = $tempProduct['product_id'];
            		$temp['product_store_id'] = $tempProduct['product_store_id'];*/


						$data['products'][] = array(

							'key' => $key,
							'qty_in_cart' => $qty_in_cart,
							'variations' => $this->model_assets_product->getApiVariations( $result['product_store_id'] ),
							'store_product_variation_id' => 0,
							'product_id' => $result['product_id'],
							'product_store_id'=> $result['product_store_id'],
							'default_variation_name' => $result['default_variation_name'],
							'thumb' => $image,
							'name' => htmlspecialchars_decode($name),
							'unit' => $unit,
							'description' => utf8_substr( strip_tags( html_entity_decode( $result['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get( 'config_product_description_length' ) ) . '..',
							'price' => $price,
							'special' => $special_price,
							'percent_off' => number_format($percent_off,0),
							'left_symbol_currency'      => $this->currency->getSymbolLeft(),
							'right_symbol_currency'      => $this->currency->getSymbolRight(),

							'tax' => $result['tax_percentage'],
							//'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
							'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
							'rating' => 0,
							'href' => $this->url->link( 'product/product',  '&product_store_id=' . $result['product_store_id'] )
						);
					}

					$url = '';

					if (isset($this->request->post['search'])) {
						$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->post['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->post['description'])) {
						$url .= '&description=' . $this->request->post['description'];
					}

					if (isset($this->request->post['category_id'])) {
						$url .= '&category_id=' . $this->request->post['category_id'];
					}

					if (isset($this->request->post['sub_category'])) {
						$url .= '&sub_category=' . $this->request->post['sub_category'];
					}

					if (isset($this->request->post['limit'])) {
						$url .= '&limit=' . $this->request->post['limit'];
					}

					$url = '';

					if (isset($this->request->post['search'])) {
						$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->post['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->post['description'])) {
						$url .= '&description=' . $this->request->post['description'];
					}

					if (isset($this->request->post['category_id'])) {
						$url .= '&category_id=' . $this->request->post['category_id'];
					}

					if (isset($this->request->post['sub_category'])) {
						$url .= '&sub_category=' . $this->request->post['sub_category'];
					}

					if (isset($this->request->post['sort'])) {
						$url .= '&sort=' . $this->request->post['sort'];
					}

					if (isset($this->request->post['order'])) {
						$url .= '&order=' . $this->request->post['order'];
					}

					/*$data['limits'] = array();

					$limits = array_unique(array($this->config->get('config_app_product_limit'), 25, 50, 75, 100));

					sort($limits);

					foreach ($limits as $value) {
						$data['limits'][] = array(
							'text' => $value,
							'value' => $value,
							'href' => $this->url->link('product/search', $url . '&limit=' . $value)
						);
					}*/

					$url = '';

					if (isset($this->request->post['search'])) {
						$url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->post['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->post['description'])) {
						$url .= '&description=' . $this->request->post['description'];
					}

					if (isset($this->request->post['category_id'])) {
						$url .= '&category_id=' . $this->request->post['category_id'];
					}

					if (isset($this->request->post['sub_category'])) {
						$url .= '&sub_category=' . $this->request->post['sub_category'];
					}

					if (isset($this->request->post['sort'])) {
						$url .= '&sort=' . $this->request->post['sort'];
					}

					if (isset($this->request->post['order'])) {
						$url .= '&order=' . $this->request->post['order'];
					}

					if (isset($this->request->post['limit'])) {
						$url .= '&limit=' . $this->request->post['limit'];
					}

					$pagination = new Pagination();
					$pagination->total = $product_total;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->url = $this->url->link('product/search', $url . '&page={page}');

					//$data['pagination'] = $pagination->render();

					$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
				}

				$data['search'] = $search;
				// $data['category_id'] = $category_id;
				// $data['sub_category'] = $sub_category;

				$data['sort'] = $sort;
				$data['total_product'] = $product_total;

				$data['order'] = $order;
				$data['limit'] = $limit;

		        if ($this->request->server['HTTPS']) {
		            $server = $this->config->get('config_ssl');
		        } else {
		            $server = $this->config->get('config_url');
		        }

		        $data['base'] = $server;

		        $json['data'] = $data;
			}

		} else {

			$json['status'] = 10010;

            $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

            http_response_code(400);

		}
		

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function getProductSearch() {

    	$json = array();

    	$log = new Log('error.log');
        $log->write('api/getProductSearch');

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

		$this->load->language('api/products');

		$this->load->model('assets/category');

		$this->load->model('assets/product');

		$this->load->model('tool/image');

		if(isset($this->request->get['store_id'])) {

			if(isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			}

			$store_info =  $this->model_tool_image->getStore($store_id);

	        if(!$store_info) { 
				// store not found
				//echo "r";
				$json['status'] = 10005;

	            $json['message'][] = ['type' =>  '' , 'body' =>   $this->language->get('store_not_found') ];

			} else {

				if (isset($this->request->get['search'])) {
					$search = $this->request->get['search'];
				} else {
					$search = '';
				}

				if (isset($this->request->get['category_id'])) {
					$category_id = $this->request->get['category_id'];
				} else {
					$category_id = 0;
				}

				if (isset($this->request->get['sub_category'])) {
					$sub_category = $this->request->get['sub_category'];
				} else {
					$sub_category = '';
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
					$limit = $this->config->get('config_app_product_limit');
				}

				
				$url = '';

				if (isset($this->request->get['search'])) {
					$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}

				if (isset($this->request->get['category_id'])) {
					$url .= '&category_id=' . $this->request->get['category_id'];
				}

				if (isset($this->request->get['sub_category'])) {
					$url .= '&sub_category=' . $this->request->get['sub_category'];
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

				$log->write($url);

				$this->load->model('assets/category');

				//$this->config->set('config_language_id',2);

				// 3 Level Category Search
				/*$data['categories'] = array();

				$categories_1 = $this->model_assets_category->getCategories(0);

				foreach ($categories_1 as $category_1) {
					$level_2_data = array();

					$categories_2 = $this->model_assets_category->getCategories($category_1['category_id']);

					foreach ($categories_2 as $category_2) {
						$level_3_data = array();

						$categories_3 = $this->model_assets_category->getCategories($category_2['category_id']);

						foreach ($categories_3 as $category_3) {
							$level_3_data[] = array(
								'category_id' => $category_3['category_id'],
								'name' => $category_3['name'],
							);
						}

						$level_2_data[] = array(
							'category_id' => $category_2['category_id'],
							'name' => $category_2['name'],
							'children' => $level_3_data
						);
					}

					$data['categories'][] = array(
						'category_id' => $category_1['category_id'],
						'name' => $category_1['name'],
						'children' => $level_2_data
					);
				}*/

				$data['products'] = array();

				if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
					
					$filter_data = array(
						'filter_name' => urldecode($search),
						'filter_name_test' => urldecode($search),
						'filter_tag' => '',
						'filter_category_id' => $category_id,
						'filter_sub_category' => $sub_category,
						'sort' => $sort,
						'order' => $order,
						'start' => ($page - 1) * $limit,
						'limit' => $limit
					);

					$filter_data['store_id'] = $store_id;

					$product_total = $this->model_assets_product->getTotalProductsByApi($filter_data);

					$log = new Log('error.log');
        			$log->write('api/search');
        			$log->write($filter_data);

					$results = $this->model_assets_product->getProductsByApi($filter_data);

					foreach ( $results as $result ) {
						if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
							$image = $this->model_tool_image->resize( $result['image'], $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
						} else {
							$image = $this->model_tool_image->resize( 'placeholder.png', $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
						}

						//$result['special_price'] = 10;

						$s_price = 0;
            			$o_price = 0;

						if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
							//get price html
							if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
								//$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
								$price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax'));

								$o_price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );
							} else {
								$price = false;
							}
							if ( (float) $result['special_price'] ) {
								//$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
								$special_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax'));

								$s_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

							} else {
								$special_price = false;
							}
						}else {
							if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
								
								//$price = $result['price'];
								$price = $this->currency->formatWithoutCurrency($result['price']);
							} else {
								$price = $result['price'];
							}

							if ( (float) $result['special_price'] ) {
								//$special_price = $result['special_price'];
								$special_price = $this->currency->formatWithoutCurrency($result['special_price']);
							} else {
								$special_price = $result['special_price'];
							}

							$s_price = $result['special_price'];
		                	$o_price = $result['price'];

						}


						//get qty in cart
						$key = base64_encode( serialize( array( 'product_store_id' => (int) $result['product_store_id'], 'store_id'=>$store_id ) ) );

						if ( isset( $this->session->data['cart'][$key] ) ) {
							$qty_in_cart = $this->session->data['cart'][$key]['quantity'];
						} else {
							$qty_in_cart = 0;
						}

						//$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
		            	$name = $result['name'];
		            	//$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

						$unit = $result['unit']?$result['unit']:false;

						$percent_off = null;
			            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
			                $percent_off = (($o_price - $s_price) / $o_price) * 100;
			            }


			            if(is_null($special_price) || !($special_price + 0) ) {
			            	//$special_price = 0;
			            	$special_price = $price;
			            }

						$data['products'][] = array(
							'key' => $key,
							'qty_in_cart' => $qty_in_cart,
							'variations' => $this->model_assets_product->getApiVariations( $result['product_store_id'] ),
							'store_product_variation_id' => 0,
							'product_id' => $result['product_id'],
							'product_store_id'=> $result['product_store_id'],
							'default_variation_name' => $result['default_variation_name'],
							'thumb' => $image,
							'name' => htmlspecialchars_decode($name),
							'unit' => $unit,
							'description' => utf8_substr( strip_tags( html_entity_decode( $result['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get( 'config_product_description_length' ) ) . '..',
							'price' => $price,
							'special' => $special_price,
							'percent_off' => number_format($percent_off,0),
							'left_symbol_currency'      => $this->currency->getSymbolLeft(),
							'right_symbol_currency'      => $this->currency->getSymbolRight(),

							'tax' => $result['tax_percentage'],
							//'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
							'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
							'rating' => 0,
							'href' => $this->url->link( 'product/product',  '&product_store_id=' . $result['product_store_id'] )
						);
					}

					$url = '';

					if (isset($this->request->get['search'])) {
						$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['description'])) {
						$url .= '&description=' . $this->request->get['description'];
					}

					if (isset($this->request->get['category_id'])) {
						$url .= '&category_id=' . $this->request->get['category_id'];
					}

					if (isset($this->request->get['sub_category'])) {
						$url .= '&sub_category=' . $this->request->get['sub_category'];
					}

					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}

					$url = '';

					if (isset($this->request->get['search'])) {
						$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['description'])) {
						$url .= '&description=' . $this->request->get['description'];
					}

					if (isset($this->request->get['category_id'])) {
						$url .= '&category_id=' . $this->request->get['category_id'];
					}

					if (isset($this->request->get['sub_category'])) {
						$url .= '&sub_category=' . $this->request->get['sub_category'];
					}

					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					/*$data['limits'] = array();

					$limits = array_unique(array($this->config->get('config_app_product_limit'), 25, 50, 75, 100));

					sort($limits);

					foreach ($limits as $value) {
						$data['limits'][] = array(
							'text' => $value,
							'value' => $value,
							'href' => $this->url->link('product/search', $url . '&limit=' . $value)
						);
					}*/

					$url = '';

					if (isset($this->request->get['search'])) {
						$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['description'])) {
						$url .= '&description=' . $this->request->get['description'];
					}

					if (isset($this->request->get['category_id'])) {
						$url .= '&category_id=' . $this->request->get['category_id'];
					}

					if (isset($this->request->get['sub_category'])) {
						$url .= '&sub_category=' . $this->request->get['sub_category'];
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
					$pagination->url = $this->url->link('product/search', $url . '&page={page}');

					//$data['pagination'] = $pagination->render();

					$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
				}

				$data['search'] = $search;
				// $data['category_id'] = $category_id;
				// $data['sub_category'] = $sub_category;

				$data['sort'] = $sort;
				$data['total_product'] = $product_total;

				$data['order'] = $order;
				$data['limit'] = $limit;

		        if ($this->request->server['HTTPS']) {
		            $server = $this->config->get('config_ssl');
		        } else {
		            $server = $this->config->get('config_url');
		        }

		        $data['base'] = $server;

		        $json['data'] = $data;
			}

		} else {

			$json['status'] = 10010;

            $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

            http_response_code(400);

		}
		

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function getProductsFn($filter_data,$store_id){
		
		$this->load->model('assets/product');
		$this->load->model('tool/image');
		
		$filter_data['store_id'] = $store_id;

		// $results = $this->model_assets_product->getProductsByApi($filter_data);
		if(isset($filter_data['filter_category_id'])){
			$results = $this->model_assets_product->getProductsByApi($filter_data);
		}else{
			$limit = 10;
			if(isset($filter_data['limit'])){
				$limit = $filter_data['limit'];
			}
			$results = $this->model_assets_product->getLatestProductsByStoreId($filter_data['store_id'],$limit);
		}	
     
		//echo "<pre>";print_r($results);die;
		$data['products'] = array();
				
		foreach ( $results as $result ) {

			// if qty less then 1 dont show product 
			if($result['quantity'] <= 0)
                continue;

			if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
				$image = $this->model_tool_image->resize( $result['image'], $this->config->get( 'config_app_image_product_width' ), $this->config->get( 'config_app_image_product_height' ) );
			} else {
				$image = $this->model_tool_image->resize( 'placeholder.png', $this->config->get( 'config_app_image_product_width' ), $this->config->get( 'config_app_image_product_height' ) );
			}
			
			//$result['special_price'] = 10;
			
			$s_price = 0;
            $o_price = 0;

			if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
				//get price html
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
					$price =$this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ));

					$o_price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

				} else {
					$price = false;
				}
				if ( (float) $result['special_price'] ) {
					$special_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax'));

					$s_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

				} else {
					$special_price = false;
				}
			}else {
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
					//$price = $result['price'];
					$price = $this->currency->formatWithoutCurrency($result['price']);
				} else {
					$price = $result['price'];
				}

				if ( (float) $result['special_price'] ) {
					//$special_price = $result['special_price'];
					$special_price = $this->currency->formatWithoutCurrency($result['special_price']);
				} else {
					$special_price = $result['special_price'];
				}

				$s_price = $result['special_price'];
                $o_price = $result['price'];

			}


			//get qty in cart
			$key = base64_encode( serialize( array( 'product_store_id' => (int) $result['product_store_id'], 'store_id'=>$store_id ) ) );

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

            if(is_null($special_price) || !($special_price + 0) ) {
            	//$special_price = 0;
            	$special_price = $price;
            }

			$data['products'][] = array(
				'key' => $key,
				'qty_in_cart' => $qty_in_cart,
				'variations' => $this->model_assets_product->getApiVariations( $result['product_store_id'] ),
				'store_product_variation_id' => 0,
				'product_id' => $result['product_id'],
				'model' => $result['model'],
				'product_store_id'=> $result['product_store_id'],
				'default_variation_name' => $result['default_variation_name'],
				'thumb' => $image,
				'name' => htmlspecialchars_decode($name),
				'unit' => $result['unit'],
				'description' => utf8_substr( strip_tags( html_entity_decode( $result['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get( 'config_product_description_length' ) ) . '..',
				'price' => $price,
				'special' => $special_price,
				'percent_off' => number_format($percent_off,0),
				'left_symbol_currency'      => $this->currency->getSymbolLeft(),
				'right_symbol_currency'      => $this->currency->getSymbolRight(),
				'tax' => $result['tax_percentage'],
				//'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
				'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
				'rating' => 0,
				'href' => $this->url->link( 'product/product',  '&product_store_id=' . $result['product_store_id'] )
			);
		}
		
		return $data['products'];
	}


	public function getProductDetails($args = []) {

		//above should be set once user enters a store

		$json = [];
		//$this->config->set('config_language_id',2);
		$this->load->language('product/product');
		$this->load->language('api/products');

		$json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('tool/image');

        if(isset($this->request->get['product_store_id']) && isset($this->request->get['store_id']) ) {

        	$product_store_id = $this->request->get['product_store_id'];
        	$store_id = $this->request->get['store_id'];

	        $this->load->model( 'assets/product' );

			$product_info = $this->model_assets_product->getProductForPopupByApi( $store_id,$product_store_id );
	        
	        //echo "<pre>";print_r($product_info);die;
			if ( $product_info ) {

	            if ( file_exists( DIR_IMAGE .$product_info['image'] ) ) {
					$thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
				} else {
					$thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
				}

				if ( file_exists( DIR_IMAGE .$product_info['image'] ) ) {
					$product_info['image'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
				} else {
					$product_info['image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
				}

				//$product_info['special_price'] = 10;

	            /*$data['images'] = array();

				$results = $this->model_assets_product->getProductImages( $product_info['product_id'] );

				foreach ( $results as $result ) {

					if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
						$popup = $this->model_tool_image->resize($result['image'], 500, 500 );
						$thumb = $this->model_tool_image->resize($result['image'], 500, 500 );
					} else {
						$popup = $this->model_tool_image->resize('placeholder.png', 500, 500 );
						$thumb = $this->model_tool_image->resize('placeholder.png', 500, 500 );
					}

					$data['images'][] = array(
						'popup' => $popup,
						'thumb' => $thumb
					);
				}*/
	     		//get qty in cart
	            $key = base64_encode( serialize( array( 'product_store_id' => (int) $product_info['product_store_id'], 'store_id'=>$store_id ) ) );

	            $s_price = 0;
            	$o_price = 0;

	            if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
					//get price html
					if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
						$product_info['price'] = $this->tax->calculate( $product_info['price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ));

						$o_price = $this->tax->calculate( $product_info['price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ) );

					} else {
						$product_info['price'] = false;
					}
					if ( (float) $product_info['special_price'] ) {
						$product_info['special_price'] = $this->tax->calculate( $product_info['special_price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ));

						$s_price = $this->tax->calculate( $product_info['special_price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ) );

					} else {
						$product_info['special_price'] = false;
					}
				}else {

					$s_price = $product_info['special_price'];
		            $o_price = $product_info['price'];

					if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
						//$product_info['price'] = $product_info['price'];
						$product_info['price'] = $this->currency->formatWithoutCurrency($product_info['price']);
					} else {
						$product_info['price'] = $product_info['price'];
					}

					if ( (float) $product_info['special_price'] ) {
						//$product_info['special_price'] = $product_info['special_price'];
						$product_info['special_price'] = $this->currency->formatWithoutCurrency($product_info['special_price']);
					} else {
						$product_info['special_price'] = $product_info['special_price'];
					}
				}
	            
	            //echo "<pre>";print_r($product_info);die;
	            if (isset($product_info['pd_name'] ) && !empty($product_info['pd_name'])) {
					$product_info['name'] = htmlspecialchars_decode($product_info['pd_name']);
				} else {
					$product_info['name'] = htmlspecialchars_decode($product_info['name']);
				}

				$percent_off = null;
	            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
	                $percent_off = (($o_price - $s_price) / $o_price) * 100;
	            }

	            /*if(is_null($product_info['special_price'])) {
	            	$product_info['special_price'] = 0;
	            }*/
	            if(is_null($product_info['special_price']) || !($product_info['special_price'] + 0) )  {
	            	$product_info['special_price'] = $product_info['price'];
	            }

	            $product_info['max_qty'] = $product_info['min_quantity'] > 0 ? $product_info['min_quantity'] : $product_info['quantity'];

	            $product_info['percent_off'] = number_format($percent_off,0);
	             
	            

	            $data['product'] = array(
	                'thumb' => $thumb,
	                'key' => $key,
	                'product_store_id' => $product_info['product_store_id'],
	                'store_product_variation_id' => 0,
	                //'images' => $data['images'],
	                'product_info' => $product_info,
	                'left_symbol_currency'      => $this->currency->getSymbolLeft(),
					'right_symbol_currency'      => $this->currency->getSymbolRight(),

	                'percent_off' => number_format($percent_off,0),
	                'default_variation_name' => $product_info['default_variation_name'],
	                'variations' => $this->model_assets_product->getApiVariations( $product_info['product_store_id'] ),
	            );

	            if(is_null($data['product']['variations'])) {

	            	$data['product']['variations'] = [];
	            }

	            //for ($i=0; $i < 3; $i++) { 
	            	
	            	$data['extra_details'][] = array(
						'title' => 'Description',
						'description' => strip_tags(htmlspecialchars_decode($product_info['description']))
					);
	            //}

			    $json['data'] = $data;

			} else {
				//product detail not found
				$json['status'] = 10011;

	            $json['message'][] = ['type' =>  $this->language->get('text_not_found') , 'body' =>  $this->language->get('text_product_detail_not_found') ];

			}
        } else {

        	$json['status'] = 10010;

            $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

            http_response_code(400);
        }

        

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductImages($args = []) {

		//above should be set once user enters a store

		$json = [];

		$data['images'] = array();
		//$this->config->set('config_language_id',2);
		$this->load->language('product/product');
		$this->load->language('api/products');

		$json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('tool/image');

        if(isset($this->request->get['product_store_id']) && isset($this->request->get['store_id']) ) {

        	$product_store_id = $this->request->get['product_store_id'];
        	$store_id = $this->request->get['store_id'];

	        $this->load->model( 'assets/product' );

			$product_info = $this->model_assets_product->getProductForPopupByApi( $store_id,$product_store_id );
	        
			if ( $product_info ) {

	            if ( file_exists( DIR_IMAGE .$product_info['image'] ) ) {
					$thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
					$popup = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
				} else {
					$thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
					$popup = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
				}

				$data['images'][] = array(
					'popup' => $popup,
					'thumb' => $thumb
				);

				$results = $this->model_assets_product->getProductImages( $product_info['product_id'] );

				foreach ( $results as $result ) {

					if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
						$popup = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
						$thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
					} else {
						$popup = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
						$thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height') );
					}

					$data['images'][] = array(
						'popup' => $popup,
						'thumb' => $thumb
					);
				}
	     		
			    $json['data'] = $data;

			} else {
				//product detail not found
				$json['status'] = 10011;

	            $json['message'][] = ['type' =>  $this->language->get('text_not_found') , 'body' =>  $this->language->get('text_product_detail_not_found') ];

			}
        } else {

        	$json['status'] = 10010;

            $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

            http_response_code(400);
        }

        

		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function getSearchProductAutocomplete($args = []) {

    	$json = array();

        $this->load->language('api/errors');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $args['start'] = 0;

        if(isset($args['store_id'])) {

            //echo "<pre>";print_r($args);die;
            $this->load->model('setting/store');
            $this->load->model('assets/product');

        	$autocompleteData =  $this->model_assets_product->getProductsByApi($args);
            
            //echo "<pre>";print_r($autocompleteData);die;
            if ($autocompleteData) {  

            	$finalAutoCompleteData = [];

            	foreach ($autocompleteData as $tempProduct) {
            		$temp['name'] = htmlspecialchars_decode($tempProduct['name'])." - ".$tempProduct['unit'];
            		//$temp['name'] = $tempProduct['name']." - ".$tempProduct['unit'];
            		$temp['unit'] = $tempProduct['unit'];
            		$temp['only_name'] = htmlspecialchars_decode($tempProduct['name']);
            		$temp['product_id'] = $tempProduct['product_id'];
            		$temp['product_store_id'] = $tempProduct['product_store_id'];

            		array_push($finalAutoCompleteData, $temp);
            	}

                $json['data'] = $finalAutoCompleteData;

            } else {

                $json['status'] = 10007;

                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('no_product_match')  ];
            }
        } else {

            $json['status'] = 10005;

            $json['message'][] = ['type' =>  '' , 'body' =>   $this->language->get('store_not_found') ];

            http_response_code(400);
        }
        
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addSearchProductAutocomplete() {

    	$json = array();

        $this->load->language('api/errors');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $args = $this->request->post;
        
        $args['start'] = 0;

        if(isset($args['store_id'])) {

            //echo "<pre>";print_r($args);die;
            $this->load->model('setting/store');
            $this->load->model('assets/product');

        	$autocompleteData =  $this->model_assets_product->getProductsByApi($args);
            
            //echo "<pre>";print_r($autocompleteData);die;
            if ($autocompleteData) {  

            	$finalAutoCompleteData = [];

            	foreach ($autocompleteData as $tempProduct) {
            		$temp['name'] = htmlspecialchars_decode($tempProduct['name'])." - ".$tempProduct['unit'];
            		//$temp['name'] = $tempProduct['name']." - ".$tempProduct['unit'];
            		$temp['unit'] = $tempProduct['unit'];
            		$temp['only_name'] = htmlspecialchars_decode($tempProduct['name']);
            		$temp['product_id'] = $tempProduct['product_id'];
            		$temp['product_store_id'] = $tempProduct['product_store_id'];

            		array_push($finalAutoCompleteData, $temp);
            	}

                $json['data'] = $finalAutoCompleteData;

            } else {

                $json['status'] = 10007;

                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('no_product_match')  ];
            }
        } else {

            $json['status'] = 10005;

            $json['message'][] = ['type' =>  '' , 'body' =>   $this->language->get('store_not_found') ];

            http_response_code(400);
        }
        
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
