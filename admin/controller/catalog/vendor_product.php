<?php

class ControllerCatalogVendorProduct extends Controller {

    private $error = array();

    public function index() {
		$this->load->language( 'catalog/product' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/general' );

		$this->getList();
	}

	public function add() {

		$this->load->language( 'catalog/product' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/vendor_product' );

		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validateForm() ) {
			
			$product_store_id = $this->model_catalog_vendor_product->addProduct( $this->request->post );

			$this->session->data['success'] = $this->language->get( 'text_success' );

			$url = '';

			if ( isset( $this->request->get['filter_name'] ) ) {
				$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_vendor_name'] ) ) {
				$url .= '&filter_vendor_name=' . urlencode( html_entity_decode( $this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_price'] ) ) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if ( isset( $this->request->get['filter_product_id_from'] ) ) {
				$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_product_id_to'] ) ) {
				$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
			}


			if ( isset( $this->request->get['filter_category'] ) ) {
				$url .= '&filter_category=' . $this->request->get['filter_category'];
			}

			if ( isset( $this->request->get['filter_store_id'] ) ) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}

			if ( isset( $this->request->get['filter_status'] ) ) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ( isset( $this->request->get['sort'] ) ) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if ( isset( $this->request->get['order'] ) ) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if ( isset( $this->request->get['page'] ) ) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if ( isset( $this->request->post['button'] ) and $this->request->post['button'] == 'save' ) {
				$this->response->redirect( $this->url->link( 'catalog/vendor_product/edit', 'store_product_id=' . $store_product_id . '&token=' . $this->session->data['token'] . $url, 'SSL' ) );
			}

			if ( isset( $this->request->post['button'] ) and $this->request->post['button'] == 'new' ) {
				$this->response->redirect( $this->url->link( 'catalog/vendor_product/add', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
			}

			$this->response->redirect( $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
		}

		$this->getForm();
	}

	public function edit() {

		$this->load->language( 'catalog/product' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/vendor_product' );

		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validateForm() ) {

			//echo "<pre>";print_r($this->request->post);die;
			$this->model_catalog_vendor_product->editProduct( $this->request->get['store_product_id'], $this->request->post );

			$this->session->data['success'] = $this->language->get( 'text_success' );

			$url = '';

			if ( isset( $this->request->get['filter_name'] ) ) {
				$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_vendor_name'] ) ) {
				$url .= '&filter_vendor_name=' . urlencode( html_entity_decode( $this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_price'] ) ) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if ( isset( $this->request->get['filter_product_id_from'] ) ) {
				$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_product_id_to'] ) ) {
				$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
			}


			if ( isset( $this->request->get['filter_store_id'] ) ) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}

			if ( isset( $this->request->get['filter_status'] ) ) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ( isset( $this->request->get['sort'] ) ) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if ( isset( $this->request->get['order'] ) ) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if ( isset( $this->request->get['page'] ) ) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if ( isset( $this->request->post['button'] ) and $this->request->post['button'] == 'save' ) {
				$this->response->redirect( $this->url->link( 'catalog/vendor_product/edit', 'product_store_id=' . $this->request->get['product_store_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL' ) );
			}

			if ( isset( $this->request->post['button'] ) and $this->request->post['button'] == 'new' ) {
				$this->response->redirect( $this->url->link( 'catalog/vendor_product/add', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
			}

			$this->response->redirect( $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language( 'catalog/product' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/vendor_product' );

		if ( isset( $this->request->post['selected'] ) && $this->validateDelete() ) {
			foreach ( $this->request->post['selected'] as $product_store_id ) {
				$this->model_catalog_vendor_product->deleteProduct( $product_store_id );
			}

			$this->session->data['success'] = $this->language->get( 'text_success' );

			$url = '';

			if ( isset( $this->request->get['filter_name'] ) ) {
				$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_vendor_name'] ) ) {
				$url .= '&filter_vendor_name=' . urlencode( html_entity_decode( $this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_price'] ) ) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if ( isset( $this->request->get['filter_product_id_from'] ) ) {
				$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_product_id_to'] ) ) {
				$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_store_id'] ) ) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}

			if ( isset( $this->request->get['filter_status'] ) ) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ( isset( $this->request->get['sort'] ) ) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if ( isset( $this->request->get['order'] ) ) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if ( isset( $this->request->get['page'] ) ) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect( $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
		}

		$this->getList();
	}

	

	protected function getList() {
		if ( isset( $this->request->get['filter_name'] ) ) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if ( isset( $this->request->get['filter_vendor_name'] ) ) {
			$filter_vendor_name = $this->request->get['filter_vendor_name'];
		} else {
			$filter_vendor_name = null;
		}

		if ( isset( $this->request->get['filter_price'] ) ) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if ( isset( $this->request->get['filter_product_id_from'] ) ) {
			$filter_product_id_from = $this->request->get['filter_product_id_from'];
		} else {
			$filter_product_id_from = null;
		}

		if ( isset( $this->request->get['filter_model'] ) ) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if ( isset( $this->request->get['filter_product_id_to'] ) ) {
			$filter_product_id_to = $this->request->get['filter_product_id_to'];
		} else {
			$filter_product_id_to = null;
		}


		if ( isset( $this->request->get['filter_category'] ) ) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = null;
		}

		if ( isset( $this->request->get['filter_store_id'] ) ) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = null;
		}

		if ( isset( $this->request->get['filter_status'] ) ) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if ( isset( $this->request->get['filter_quantity'] ) ) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if ( isset( $this->request->get['sort'] ) ) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if ( isset( $this->request->get['order'] ) ) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if ( isset( $this->request->get['page'] ) ) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if ( isset( $this->request->get['filter_name'] ) ) {
			$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_vendor_name'] ) ) {
			$url .= '&filter_vendor_name=' . urlencode( html_entity_decode( $this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_price'] ) ) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if ( isset( $this->request->get['filter_product_id_from'] ) ) {
			$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_model'] ) ) {
			$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_product_id_to'] ) ) {
			$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
		}


		if ( isset( $this->request->get['filter_store_id'] ) ) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if ( isset( $this->request->get['filter_status'] ) ) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if ( isset( $this->request->get['filter_quantity'] ) ) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
		if ( isset( $this->request->get['sort'] ) ) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if ( isset( $this->request->get['order'] ) ) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if ( isset( $this->request->get['page'] ) ) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'text_home' ),
			'href' => $this->url->link( 'common/dashboard', 'token=' . $this->session->data['token'], 'SSL' )
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'heading_title' ),
			'href' => $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' )
		);

		$data['add'] = $this->url->link( 'catalog/vendor_product/add', 'token=' . $this->session->data['token'] . $url, 'SSL' );
		$data['copy'] = $this->url->link( 'catalog/vendor_product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL' );
		$data['delete'] = $this->url->link( 'catalog/vendor_product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL' );

		$data['products'] = array();

		$filter_data = array(
			'filter_name' => $filter_name,
			'filter_vendor_name' => $filter_vendor_name,
			'filter_price' => $filter_price,
			'filter_product_id_from' => $filter_product_id_from,
			'filter_model' => $filter_model,
			'filter_product_id_to' => $filter_product_id_to,
			'filter_category' => $filter_category,
			'filter_store_id' => $filter_store_id,
			'filter_status' => $filter_status,
			'filter_quantity'=>$filter_quantity,
			'sort' => $sort,
			'order' => $order,
			'start' => ( $page - 1 ) * $this->config->get( 'config_limit_admin' ),
			'limit' => $this->config->get( 'config_limit_admin' )
		);

		$this->load->model('catalog/vendor_product');
		$this->load->model( 'tool/image' );

		$product_total = $this->model_catalog_vendor_product->getTotalProducts( $filter_data );

		$results = $this->model_catalog_vendor_product->getProducts( $filter_data );

		$this->load->model( 'catalog/category' );
		$data['categories'] = $this->model_catalog_category->getCategories( 0 );

		foreach ( $results as $result ) {
			$category = $this->model_catalog_vendor_product->getProductCategories( $result['product_id'] );

			if ( is_file( DIR_IMAGE . $result['image'] ) ) {
				$image = $this->model_tool_image->resize( $result['image'], 40, 40 );
				$bigimage = $this->model_tool_image->getImage( $result['image'] );
			} else {
				$image = $this->model_tool_image->resize( 'no_image.png', 40, 40 );
				$bigimage = $this->model_tool_image->getImage( 'no_image.png' );
			}

			$data['products'][] = array(
				'store_name'=>$result['store_name'],
				//'vendor_name'=>$result['fs'].' '.$result['ls'],
				'product_store_id' => $result['product_store_id'],
				'product_id' => $result['product_id'],
				'price' => $result['price'],
				'special_price' => $result['special_price'],
				'quantity' => $result['quantity'],
				'image' => $image,
				'bigimage' => $bigimage,
				'name' => $result['product_name'],
				'unit' => $result['unit'],
				//'weight' => $result['weight'],
				'model' => $result['model'],
				'category' => $category,
				'status' => ( $result['sts'] ) ? $this->language->get( 'text_enabled' ) : $this->language->get( 'text_disabled' ),
				'edit' => $this->url->link( 'catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $result['product_store_id'] . $url, 'SSL' )
			);
		}

		if ( $this->user->isVendor() ) {
			$data['is_vendor'] = 1;
		}else {
			$data['is_vendor'] = 0;
		}

		$data['heading_title'] = $this->language->get( 'heading_title' );

		$data['text_list'] = $this->language->get( 'text_list' );
		$data['text_enabled'] = $this->language->get( 'text_enabled' );
		$data['text_disabled'] = $this->language->get( 'text_disabled' );
		$data['text_no_results'] = $this->language->get( 'text_no_results' );
		$data['text_confirm'] = $this->language->get( 'text_confirm' );

		$data['column_image'] = $this->language->get( 'column_image' );
		$data['column_store_name'] = $this->language->get( 'column_store_name' );

		$data['column_unit'] = $this->language->get( 'column_unit' );

		$data['column_name'] = $this->language->get( 'column_name' );
		$data['column_category'] = $this->language->get( 'column_category' );
		$data['column_model'] = $this->language->get( 'column_model' );
		$data['column_product_id'] = $this->language->get( 'column_product_id' );
		$data['column_vproduct_id'] = $this->language->get( 'column_vproduct_id' );
		$data['column_price'] = $this->language->get( 'column_price' );
		$data['column_quantity'] = $this->language->get( 'column_quantity' );
		$data['column_status'] = $this->language->get( 'column_status' );
		$data['column_action'] = $this->language->get( 'column_action' );

		$data['entry_name'] = $this->language->get( 'entry_name' );
		$data['entry_store_name'] = $this->language->get( 'entry_store_name' );
		$data['entry_vendor_name'] = $this->language->get( 'entry_vendor_name' );
		$data['entry_model'] = $this->language->get( 'entry_model' );
		$data['entry_price'] = $this->language->get( 'entry_price' );
		$data['entry_product_id_from'] = $this->language->get('entry_product_id_from');
        $data['entry_product_id_to'] = $this->language->get('entry_product_id_to');

		$data['entry_quantity'] = $this->language->get( 'entry_quantity' );
		$data['entry_status'] = $this->language->get( 'entry_status' );

		$data['button_copy'] = $this->language->get( 'button_copy' );
		$data['button_add'] = $this->language->get( 'button_add' );
		$data['button_edit'] = $this->language->get( 'button_edit' );
		$data['button_delete'] = $this->language->get( 'button_delete' );
		$data['button_filter'] = $this->language->get( 'button_filter' );
		$data['button_show_filter'] = $this->language->get( 'button_show_filter' );
		$data['button_hide_filter'] = $this->language->get( 'button_hide_filter' );
		$data['button_enable'] = $this->language->get( 'button_enable' );
		$data['button_disable'] = $this->language->get( 'button_disable' );

		$data['token'] = $this->session->data['token'];

		if ( isset( $this->error['warning'] ) ) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if ( isset( $this->session->data['warning'] ) ) {
			$data['error_warning'] = $this->session->data['warning'];
			unset( $this->session->data['warning'] );
		}

		if ( isset( $this->session->data['success'] ) ) {
			$data['success'] = $this->session->data['success'];
			unset( $this->session->data['success'] );
		} else {
			$data['success'] = '';
		}

		if ( isset( $this->request->post['selected'] ) ) {
			$data['selected'] = (array) $this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ( isset( $this->request->get['filter_name'] ) ) {
			$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_vendor_name'] ) ) {
			$url .= '&filter_vendor_name=' . urlencode( html_entity_decode( $this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_price'] ) ) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if ( isset( $this->request->get['filter_product_id_from'] ) ) {
			$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_model'] ) ) {
			$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_product_id_to'] ) ) {
			$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_store_id'] ) ) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if ( isset( $this->request->get['filter_status'] ) ) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ( $order == 'ASC' ) {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ( isset( $this->request->get['page'] ) ) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL' );
		$data['sort_model'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL' );

		$data['sort_store'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=st.name' . $url, 'SSL' );


		$data['sort_product_id'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL' );

		$data['sort_vproduct_id'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=ps.product_store_id' . $url, 'SSL' );

		$data['sort_category'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL' );
		$data['sort_price'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL' );
		$data['sort_quantity'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=ps.quantity' . $url, 'SSL' );
		$data['sort_status'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL' );
		$data['sort_order'] = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL' );

		$url = '';

		if ( isset( $this->request->get['filter_name'] ) ) {
			$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_vendor_name'] ) ) {
			$url .= '&filter_vendor_name=' . urlencode( html_entity_decode( $this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_price'] ) ) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if ( isset( $this->request->get['filter_product_id_from'] ) ) {
			$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_model'] ) ) {
			$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_product_id_to'] ) ) {
			$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
		}


		if ( isset( $this->request->get['filter_store_id'] ) ) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if ( isset( $this->request->get['filter_status'] ) ) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ( isset( $this->request->get['filter_category'] ) ) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if ( isset( $this->request->get['filter_quantity'] ) ) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
		

		if ( isset( $this->request->get['sort'] ) ) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if ( isset( $this->request->get['order'] ) ) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get( 'config_limit_admin' );
		$pagination->url = $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL' );

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf( $this->language->get( 'text_pagination' ), ( $product_total ) ? ( ( $page - 1 ) * $this->config->get( 'config_limit_admin' ) ) + 1 : 0, ( ( ( $page - 1 ) * $this->config->get( 'config_limit_admin' ) ) > ( $product_total - $this->config->get( 'config_limit_admin' ) ) ) ? $product_total : ( ( ( $page - 1 ) * $this->config->get( 'config_limit_admin' ) ) + $this->config->get( 'config_limit_admin' ) ), $product_total, ceil( $product_total / $this->config->get( 'config_limit_admin' ) ) );

		$data['filter_name'] = $filter_name;
		$data['filter_vendor_name'] = $filter_vendor_name;
		$data['filter_price'] = $filter_price;
		$data['filter_product_id_from'] = $filter_product_id_from;
		$data['filter_model'] = $filter_model;
		$data['filter_product_id_to'] = $filter_product_id_to;
		$data['filter_category'] = $filter_category;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller( 'common/header' );
		$data['column_left'] = $this->load->controller( 'common/column_left' );
		$data['footer'] = $this->load->controller( 'common/footer' );

		$this->response->setOutput( $this->load->view( 'catalog/vendor_product_lists.tpl', $data ) );
	}


	protected function getForm() {
        $data = $this->language->all();
        // leaving the followings for extension B/C purpose

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['store_product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_amount'] = $this->language->get('text_amount');

        $data['entry_select'] = $this->language->get('entry_select');
        $data['entry_name'] = $this->language->get('entry_name');

		$data['entry_unit'] = $this->language->get('entry_unit');
		$data['entry_weight'] = $this->language->get('entry_weight');

        $data['entry_price'] = $this->language->get('entry_price');

        $data['entry_product_id_from'] = $this->language->get('entry_product_id_from');
        $data['entry_product_id_to'] = $this->language->get('entry_product_id_to');

        $data['entry_special_price'] = $this->language->get('entry_special_price');
	    $data['entry_quantity'] = $this->language->get('entry_quantity');
       	$data['entry_subtract'] = $this->language->get('entry_subtract');
       	$data['entry_minimum'] = $this->language->get('entry_minimum');

        $data['help_tag'] = $this->language->get('help_tag');

        $data['button_save'] = $this->language->get('button_save');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_variations'] = $this->language->get('tab_variations');

       	$data['tab_images'] = $this->language->get('tab_images');

       	$data['no_variation'] = $this->language->get('no_variation');
        
        
        $a = $this->request->get['path'];

		if (strpos($a, 'add') !== false) {
		    $data['edit_mode'] = false;
		} else {
			$data['edit_mode'] = true;
		}
       	 	 	
		//echo "<pre>";print_r($data['edit_mode']);die;
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['unit'])) {
            $data['error_unit'] = $this->error['unit'];
        } else {
            $data['error_unit'] = '';
        }

        if (isset($this->error['weight'])) {
            $data['error_weight'] = $this->error['weight'];
        } else {
            $data['error_weight'] = '';
        }


        if (isset($this->error['price'])) {
            $data['error_price'] = $this->error['price'];
        } else {
            $data['error_price'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if ( isset( $this->request->get['filter_product_id_from'] ) ) {
			$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
		}

		if ( isset( $this->request->get['filter_model'] ) ) {
			$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}


		if ( isset( $this->request->get['filter_product_id_to'] ) ) {
			$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
		}


        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        if (!isset($this->request->get['store_product_id'])) {

            $data['action'] = $this->url->link('catalog/vendor_product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        } else {
	
        	$data['action'] = $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $this->request->get['store_product_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->load->model('catalog/vendor_product');
        if (isset($this->request->get['store_product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
        	
            $product_info = $this->model_catalog_vendor_product->getProduct($this->request->get['store_product_id']);
            if ($this->user->isVendor() && $product_info['vendor_id'] != $this->user->getId()) {
                die('illegal access!');
            }
        }

        //echo "<pre>";print_r($product_info);die;
        $data['is_vendor'] = $this->user->isVendor();

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();


        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        // if (isset($this->request->post['product_store'])) {
        //     $data['product_store'] = $this->request->post['product_store'];
        // } elseif (isset($this->request->get['store_product_id'])) {
        //     $data['product_store'] = $this->model_catalog_vendor_product->getProductStores($this->request->get['store_product_id']);
        // } else {
        //     $data['product_store'] = array(0);
        // }

        if (isset($this->request->post['product_store'])) {
            $data['product_store'] = $this->request->post['product_store'];
        } elseif (!empty($product_info)) {
            $data['product_store'] = $product_info['store_id'];
        } else {
            $data['product_store'] = '';
        }		
        
        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['tax_class_id'])) {
            $data['tax_class_id'] = $this->request->post['tax_class_id'];
        } elseif (!empty($product_info)) {
            $data['tax_class_id'] = $product_info['tax_class_id'];
        } else {
            $data['tax_class_id'] = 0;
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($product_info)) {
            $data['price'] = $product_info['price'];
        } else {
            $data['price'] = '';
        }

         if (isset($this->request->post['special_price'])) {
            $data['special_price'] = $this->request->post['special_price'];
        } elseif (!empty($product_info)) {
            $data['special_price'] = $product_info['special_price'];
        } else {
            $data['special_price'] = '';
        }
        	

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $product_info['quantity'];
        } else {
            $data['quantity'] = 1;
        }

        

        if (isset($this->request->post['subtract'])) {
            $data['subtract'] = $this->request->post['subtract'];
        } elseif (!empty($product_info)) {
            $data['subtract'] = $product_info['subtract_quantity'];
        } else {
            $data['subtract'] ='';
        }

        //echo "<pre>";print_r($data['subtract']);die;

        if (isset($this->request->post['product'])) {
            $data['product'] = $this->request->post['product'];
        } elseif (!empty($product_info)) {
            $data['product'] = $product_info['name'];
        } else {
            $data['product'] ='';
        }

        if(!empty($product_info)) {

        	$p = $this->model_catalog_vendor_product->getProductDetail($product_info['product_id']);

        	//echo "<pre>";print_r($p);die;
        	if($p) {
        		$data['unit'] = $p['unit'];
        	} else {
        		$data['unit'] = '';
        	}
        		
        } else {
        	$data['unit'] = '';	
        }

        /*if(!empty($product_info)) {
        	$data['weight'] = $product_info['weight'];	
        } else {
        	$data['weight'] = '';	
        }*/

        

        if (isset($this->request->post['product_id'])) {
            $data['product_id'] = $this->request->post['product_id'];
        } elseif (!empty($product_info)) {
            $data['product_id'] = $product_info['product_id'];
        } else {
            $data['product_id'] ='';
        }

        if (isset($this->request->post['tax_percentage'])) {
            $data['tax_percentage'] = $this->request->post['tax_percentage'];
        } elseif (!empty($product_info)) {
            $data['tax_percentage'] = $product_info['tax_percentage'];
        } else {
            $data['tax_percentage'] = '';
        }

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $product_info['quantity'];
        } else {
            $data['quantity'] = '';
        }
        if (isset($this->request->post['min_quantity'])) {
            $data['min_quantity'] = $this->request->post['min_quantity'];
        } elseif (!empty($product_info)) {
            $data['min_quantity'] = $product_info['min_quantity'];
        } else {
            $data['min_quantity'] = '';
        }
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_info)) {
            $data['status'] = $product_info['status'];
        } else {
            $data['status'] = '';
        }
        $this->load->model('catalog/general');
       
        if (isset($this->request->get['store_product_id'])) {
			$product_variations = $this->model_catalog_vendor_product->getProductVariations($this->request->get['store_product_id']);

			$data['product_variations'] = array();
			if ($product_variations) {
				foreach ( $product_variations as $product_variation ) {
					$data['product_variations'][] = array(
						'name' => $product_variation['name'],
						'unit' => $product_variation['unit'],
						'product_id' => $product_variation['product_id'],
						'price'=> $product_variation['price'],
						'special_price'=>$product_variation['special_price'],
						'edit' => $this->url->link( 'catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $product_variation['product_store_id'] . $url, 'SSL' )
							
					);
				}
			}else{
				
				if(!empty($product_info)) {
					$product_variations = $this->model_catalog_general->getProductVariations($product_info['product_id']);	
				} else {
					$product_variations = false;
				}
				

				if(!$product_variations){
					$product_variations = array();
				}

				$data['product_variations'] = array();

				foreach ( $product_variations as $product_variation ) {
					$data['product_variations'][] = array(
						'name' => $product_variation['name'],
						'unit' => $product_variation['unit'],
						'product_id' => $product_variation['product_id'],
						'id' => $product_variation['product_id'],
						'price'=>'',
						'special_price'=>'',
						'variation_id'=>'',
						/*'edit' => $this->url->link( 'catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $product_variation['product_store_id'] . $url, 'SSL' )*/
					);
				}
				
			}
		} elseif (isset($this->request->post['product_id'])) {
			$product_variations = $this->model_catalog_general->getProductVariations($this->request->post['product_id']);


			if(!$product_variations){
				$product_variations = array();
			}

			$data['product_variations'] = array();

			foreach ( $product_variations as $product_variation ) {
				$data['product_variations'][] = array(
					'name' => $product_variation['name'],
					'product_id' => $product_variation['product_id'],
					'id' => $product_variation['id'],
					'price'=>'',
					'special_price'=>'',
						
				);
			}
		}else{
			$data['product_variations'] = array();
		}	


        if ( isset( $this->error['product_store'] ) ) {
			$data['error_product_store'] = $this->error['product_store'];
		} else {
			$data['error_product_store'] = '';
		}


        if ( isset( $this->error['product_id'] ) ) {
			$data['error_product_id'] = $this->error['product_id'];
		} else {
			$data['error_product_id'] = '';
		}

		if ( isset( $this->error['price'] ) ) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = '';
		}

		if ( isset( $this->error['variation_price'] ) ) {
			$data['error_variation_price'] = $this->error['variation_price'];
		} else {
			$data['error_variation_price'] = '';
		}
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['column_action'] = $this->language->get( 'column_action' );
        
        $this->response->setOutput($this->load->view('catalog/vendor_product_form.tpl', $data));
    }

    protected function validateForm(){
    	if ( !$this->user->hasPermission( 'modify', 'catalog/vendor_product' ) ) {
			$this->error['warning'] = $this->language->get( 'error_permission' );
		}


		if ( empty( $this->request->post['product_store'] )) {
			$this->error['product_store'] = $this->language->get( 'error_product_store' );
		}
		if ( empty( $this->request->post['product_id'] )) {
			$this->error['product_id'] = $this->language->get( 'error_product_id' );
		}
		if (empty($this->request->post['price'])) {
            $this->error['price'] = $this->language->get('error_price');
        }
        // if ($this->request->post['product_variation']) {
        // 	if (empty($this->request->post['product_variation']['price'])) {
	       //      $this->error['variation_price'] = $this->language->get('error_price');
	       //  }
        // }
		if ( $this->error && !isset( $this->error['warning'] ) ) {
			$this->error['warning'] = $this->language->get( 'error_warning' );
		}

		return !$this->error;

    }	
    
    public function copy() {
		$this->load->language( 'catalog/general' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/vendor_product' );

		if ( isset( $this->request->post['selected'] ) && $this->validateCopy() ) {
			foreach ( $this->request->post['selected'] as $store_product_id ) {
				$this->model_catalog_vendor_product->copyProduct( $store_product_id );
			}

			$this->session->data['success'] = $this->language->get( 'text_success' );

			$url = '';

			if ( isset( $this->request->get['filter_name'] ) ) {
				$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_price'] ) ) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if ( isset( $this->request->get['filter_product_id_from'] ) ) {
				$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_product_id_to'] ) ) {
				$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_quantity'] ) ) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if ( isset( $this->request->get['filter_status'] ) ) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ( isset( $this->request->get['sort'] ) ) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if ( isset( $this->request->get['order'] ) ) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if ( isset( $this->request->get['page'] ) ) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect( $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
		}

		$this->getList();
	}


	public function general_product_copy() {

		$this->load->language( 'catalog/general' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/vendor_product' );


		if ( isset( $this->request->post['selected'] ) && $this->validateCopy() ) {
			foreach ( $this->request->post['selected'] as $product_id ) {
				if($this->model_catalog_vendor_product->copyGeneralProduct( $product_id )) {

					$this->session->data['warning'] = $this->language->get( 'text_exists_some_sell_product' );
				}
			}

			foreach ( $this->request->post['selected'] as $product_id ) {
				$this->model_catalog_vendor_product->copyGeneralProductVariations( $product_id );
			}


			$this->session->data['success'] = $this->language->get( 'text_success' );

			$url = '';

			if ( isset( $this->request->get['filter_name'] ) ) {
				$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_price'] ) ) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if ( isset( $this->request->get['filter_product_id_from'] ) ) {
				$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_product_id_to'] ) ) {
				$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
			}


			if ( isset( $this->request->get['filter_quantity'] ) ) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if ( isset( $this->request->get['filter_status'] ) ) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ( isset( $this->request->get['sort'] ) ) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if ( isset( $this->request->get['order'] ) ) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if ( isset( $this->request->get['page'] ) ) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect( $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
		}

		$this->getList();
	}

	public function all_general_product_copy() {

		$this->load->language( 'catalog/general' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'catalog/vendor_product' );


		if ( isset( $this->request->post['product_store'] ) && $this->validateCopy() ) {
			
			if($this->model_catalog_vendor_product->copyAllGeneralProduct()) {

				$this->session->data['warning'] = $this->language->get( 'text_exists_some_sell_product' );
			}
			
			$this->model_catalog_vendor_product->copyAllGeneralProductVariations( $product_id );
			

			$this->session->data['success'] = $this->language->get( 'text_success' );

			$url = '';

			if ( isset( $this->request->get['filter_name'] ) ) {
				$url .= '&filter_name=' . urlencode( html_entity_decode( $this->request->get['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_price'] ) ) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if ( isset( $this->request->get['filter_product_id_from'] ) ) {
				$url .= '&filter_product_id_from=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_model'] ) ) {
				$url .= '&filter_model=' . urlencode( html_entity_decode( $this->request->get['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}

			if ( isset( $this->request->get['filter_product_id_to'] ) ) {
				$url .= '&filter_product_id_to=' . urlencode( html_entity_decode( $this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8' ) );
			}


			if ( isset( $this->request->get['filter_quantity'] ) ) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if ( isset( $this->request->get['filter_status'] ) ) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ( isset( $this->request->get['sort'] ) ) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if ( isset( $this->request->get['order'] ) ) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if ( isset( $this->request->get['page'] ) ) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect( $this->url->link( 'catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL' ) );
		}

		$this->getList();
	}


	protected function validateDelete() {
		if ( !$this->user->hasPermission( 'modify', 'catalog/vendor_product' ) ) {
			$this->error['warning'] = $this->language->get( 'error_permission' );
		}
		return !$this->error;
	}

	protected function validateCopy() {
		if ( !$this->user->hasPermission( 'modify', 'catalog/vendor_product' ) ) {
			$this->error['warning'] = $this->language->get( 'error_permission' );
		}

		return !$this->error;
	}
    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/general');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_catalog_general->getProducts($filter_data);

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {

                $result['index'] = $result['name'];
                if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                    $result['name'] = end($result['name']);
                }

                $json[] = array(
                    'product_id' => $result['product_id'],
                    'index' => $result['index'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))." - ".$result['unit'],
                    'unit' => $result['unit'],
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $json);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function getVariation(){
		$this->load->model('catalog/general');

		$product_variations = $this->model_catalog_general->getProductVariations($this->request->get['product_id']);

		if(!$product_variations){
			$product_variations = array();
		}

		$data['product_variations'] = array();

		foreach ( $product_variations as $product_variation ) {
			
			$data['product_variations'][] = array(
				'name' => $product_variation['name'],
				'product_id' => $product_variation['product_id'],
				'id' => $product_variation['id'],
			);
		}

		$this->response->setOutput($this->load->view('catalog/variation_form.tpl', $data));
	}


	public function export_excel() {	 



		if ( isset( $this->request->get['filter_name'] ) ) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if ( isset( $this->request->get['filter_vendor_name'] ) ) {
			$filter_vendor_name = $this->request->get['filter_vendor_name'];
		} else {
			$filter_vendor_name = null;
		}

		if ( isset( $this->request->get['filter_price'] ) ) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if ( isset( $this->request->get['filter_product_id_from'] ) ) {
			$filter_product_id_from = $this->request->get['filter_product_id_from'];
		} else {
			$filter_product_id_from = null;
		}

		if ( isset( $this->request->get['filter_model'] ) ) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if ( isset( $this->request->get['filter_product_id_to'] ) ) {
			$filter_product_id_to = $this->request->get['filter_product_id_to'];
		} else {
			$filter_product_id_to = null;
		}


		if ( isset( $this->request->get['filter_category'] ) ) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = null;
		}

		if ( isset( $this->request->get['filter_store_id'] ) ) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = null;
		}

		if ( isset( $this->request->get['filter_status'] ) ) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if ( isset( $this->request->get['filter_quantity'] ) ) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if ( isset( $this->request->get['sort'] ) ) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if ( isset( $this->request->get['order'] ) ) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		// if ( isset( $this->request->get['page'] ) ) {
		// 	$page = $this->request->get['page'];
		// } else {
		// 	$page = 1;
		// }

		 

		 

		$filter_data = array(
			'filter_name' => $filter_name,
			'filter_vendor_name' => $filter_vendor_name,
			'filter_price' => $filter_price,
			'filter_product_id_from' => $filter_product_id_from,
			'filter_model' => $filter_model,
			'filter_product_id_to' => $filter_product_id_to,
			'filter_category' => $filter_category,
			'filter_store_id' => $filter_store_id,
			'filter_status' => $filter_status,
			'filter_quantity'=>$filter_quantity,
			'sort' => $sort,
			'order' => $order,
			'start' => ( $page - 1 ) * $this->config->get( 'config_limit_admin' ),
			'limit' => $this->config->get( 'config_limit_admin' )
		);


// echo "<pre>";print_r($filter_data);die;


        $data = array();
		$this->load->model('report/excel');	
		

		$this->model_report_excel->download_vendorproduct_excel($data,$filter_data);
		
    }

}