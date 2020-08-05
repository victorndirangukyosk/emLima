<?php

class ControllerAccountWishList extends Controller {
    private $error = array();

    public function index() {
        
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
            
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->load->language('account/wishlist');

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

        $data['column_wishlist_id'] = $this->language->get('column_wishlist_id');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_total'] = $this->language->get('column_total');
        $data['button_view'] = $this->language->get('button_view');
        $data['text_products_count'] = $this->language->get('text_products_count');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_cancel'] = $this->language->get ('text_cancel');
        $data['text_placed_on'] = $this->language->get('text_placed_on');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_items_ordered'] = $this->language->get('text_items_ordered');
        $data['text_refund_text_part1'] = $this->language->get('text_refund_text_part1');
        $data['text_refund_text_part2'] = $this->language->get('text_refund_text_part2');
        $data['text_refund_text_part3'] = $this->language->get('text_refund_text_part3');
        $data['text_delivery_address'] = $this->language->get('text_delivery_address');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_payment_options'] = $this->language->get('text_payment_options');
        $data['text_view_billing'] = $this->language->get('text_view_billing');
        $data['text_wishlist_id'] = $this->language->get('text_wishlist_id');
        $data['text_report_issue'] = $this->language->get('text_report_issue');
        $data['text_load_more'] = $this->language->get('text_load_more');
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['orders'] = array();

        $this->load->model('account/wishlist');
        
        $wishlist_total = $this->model_account_wishlist->getTotalWishlist();

        //echo "<pre>";print_r($wishlist_total);die;
        $results = $this->model_account_wishlist->getWishlists(($page - 1) * 10, 10);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {

            $wishlist_products =  $this->model_account_wishlist->getWishlistProduct($result['wishlist_id']);
            //echo "<pre>";print_r($wishlist_products);die;
            $totalCount = 0;

            if(!empty($wishlist_products)) {
                $totalCount = count($wishlist_products);
            }

            $data['wishlists'][] = array(
                'wishlist_id'   => $result['wishlist_id'],
                'name'       => $result['name'],
                'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
                'product_count' => $totalCount,
                'products'   => $wishlist_products,
                'href'       => $this->url->link('account/wishlist/info', 'wishlist_id=' . $result['wishlist_id'], 'SSL'),
            );
        } 
        
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $pagination = new Pagination();
        $pagination->total = $wishlist_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        //echo "<pre>";print_r($pagination->render());die;
        $data['results'] = sprintf($this->language->get('text_pagination'), ($wishlist_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($wishlist_total - 10)) ? $wishlist_total : ((($page - 1) * 10) + 10), $wishlist_total, ceil($wishlist_total / 10));

        $data['continue'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/wishlist.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/wishlist.tpl', $data));
        }
    }

    public function info() {
        $this->load->language('account/wishlist');

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
                
        if (isset($this->request->get['wishlist_id'])) {
            $wishlist_id = $this->request->get['wishlist_id'];
        } else {
            $wishlist_id = 0;
        }

        $data['store_selected'] = true;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['home'] = $server;

        $data['store'] = $this->url->link('common/home', '', 'SSL');

        $data['store_id'] = false;

        //if ( !isset($this->session->data['zipcode']) || !isset($this->session->data['config_store_id']) ) {
        // if ( !(count($_COOKIE) > 0 && isset($_COOKIE['zipcode']) ) || !isset($this->session->data['config_store_id']) ) {

        //     if(!isset($_COOKIE['location'])) {
        //         $data['store_selected'] = false;
        //     }
            
        // }

        if(isset($this->session->data['config_store_id'])) {
            $data['store_id'] = $this->session->data['config_store_id'];
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/wishlist/info', 'wishlist_id=' . $wishlist_id, 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->model('account/wishlist');

        $wishlist_info = $this->model_account_wishlist->getWishlist($wishlist_id);

        //echo "<pre>";print_r($wishlist_info);die;
        if ($wishlist_info) {

            $this->document->setTitle($this->language->get('text_wishlist'));

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
                'href' => $this->url->link('account/wishlist', $url, 'SSL')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_wishlist'),
                'href' => $this->url->link('account/wishlist/info', 'wishlist_id=' . $this->request->get['wishlist_id'] . $url, 'SSL')
            );

            $data['text_go_back'] = $this->language->get('text_go_back');
            $data['text_wishlist_id_with_colon'] = $this->language->get('text_wishlist_id_with_colon');
            $data['text_items'] = $this->language->get('text_items');
            $data['text_add_to_cart'] = $this->language->get('text_add_to_cart');
            
            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_wishlist_detail'] = $this->language->get('text_wishlist_detail');
            $data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $data['text_wishlist_id'] = $this->language->get('text_wishlist_id');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_add_selection_to_cart'] = $this->language->get('text_add_selection_to_cart');
            $data['text_add_list_to_cart'] = $this->language->get('text_add_list_to_cart');
            $data['text_store_not_selected'] = $this->language->get('text_store_not_selected');
            
                
            $data['text_shopping'] = $this->language->get('text_shopping');

            
            $data['text_name'] = $this->language->get('text_name');
            $data['text_cancel'] = $this->language->get ('text_cancel');
            
            $data['column_name'] = $this->language->get('column_name');

            $data['column_image'] = $this->language->get('column_image');

            $data['column_unit'] = $this->language->get('column_unit');

            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_action'] = $this->language->get('column_action');
            $data['column_date_added'] = $this->language->get('column_date_added');
            $data['text_not_avialable'] = $this->language->get('text_not_avialable');
            
            $data['button_rewishlist'] = $this->language->get('button_rewishlist');
            $data['button_return'] = $this->language->get('button_return');
            $data['button_continue'] = $this->language->get('button_continue');

            $data['delivered'] = false;

                        
            if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if ($wishlist_info['name']) {
                $data['wishlist_name'] = $wishlist_info['name'];
            } else {
                $data['wishlist_name'] = '';
            }
            
           
            if ($wishlist_info['wishlist_id']) {
                $data['wishlist_id'] = $wishlist_info['wishlist_id'];
            } else {
                $data['wishlist_id'] = null;
            }

            $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
            $data['text_real_amount'] = $this->language->get('text_real_amount');
            
         
            $data['wishlist_id'] = $this->request->get['wishlist_id'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($wishlist_info['date_added']));
            
           
                    
            $this->load->model('assets/product');
            $this->load->model('account/wishlist');
            $this->load->model('tool/upload');

           

            // Products
            $data['products'] = array();
            $data['store_id']=ACTIVE_STORE_ID;
            $products = $this->model_account_wishlist->getWishlistProduct($this->request->get['wishlist_id']);

          //   echo "<pre>";print_r($data);die;

            foreach ($products as $product) {

                //below one we need to send product_store_id
                $fromStore = false;
                $product_store_id = 0;

                // product_store_id 11
                if($data['store_id']) {

                    $productStoreData = $this->model_assets_product->getProductStoreId($product['product_id'],$data['store_id']);

                    //echo "<pre>";print_r($productStoreData);die;

                    if(count($productStoreData) > 0 ) {
                        $product_store_id   = $productStoreData['product_store_id'];
                        $fromStore = true;
                    }

                }
                
                //echo "<pre>";print_r($product_store_id);die;
                $product_info = $this->model_assets_product->getDetailproduct($product_store_id);

                $special_price = 0;
                $price = 0;

                if(count($product_info) > 0) {
                    //echo "<pre>";print_r($product_info);die;

                    if ( (float) $product_info['special_price'] ) {
                        $special_price = $this->currency->format( $product_info['special_price'] );
                    } else {
                        $special_price = $product_info['special_price'];
                    }

                    if ( (float) $product_info['price'] ) {
                        $price = $this->currency->format( $product_info['price'] );
                    } else {
                        $price = $product_info['price'];
                    }

                }
                
                $this->load->model('tool/image');


                $data['products'][] = array(
                    
                    'name'     => isset($product_info['pd_name']) ? $product_info['pd_name']: '',
                    'image'    => $product['image'],
                    'quantity' => $product['quantity'],
                    'product_id' => $product['product_id'],
                    'is_from_active_store' => $fromStore,
                    'product_store_id' => $product_store_id,
                    'unit'     => $product['unit'],
                    'price'     => $price,
                    'special_price'     => $special_price,
                       /* 'store_id'     => $product['store_id'],
                    'model'    => $product['model'],*/
                    
                    /*'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $wishlist_info['currency_code'], $wishlist_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $wishlist_info['currency_code'], $wishlist_info['currency_value']),*/
                );
            }
            
            //echo "<pre>";print_r($data['products']);die;
            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['base'] = $server;
        

            $data['continue'] = $this->url->link('account/wishlist', '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/onlyHeader');

            $data['total_quantity'] = 0;
            foreach ($data['products'] as $product) {
                $data['total_quantity'] += $product['quantity'];
            }
            //echo "<pre>";print_r($data);die;
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlist_info.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/wishlist_info.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/wishlist_info.tpl', $data));
            }
        } else {
            
            $this->document->setTitle($this->language->get('text_order'));

            //$data['heading_title'] = $this->language->get('text_no_order');
            $data['heading_title'] = 'List not found!';

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
                'href' => $this->url->link('account/order/info', 'wishlist_id=' . $wishlist_id, 'SSL')
            );

            $data['continue'] = $this->url->link('account/wishlist', '', 'SSL');

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

    public function deleteWishlist() {

        $data['status'] = false;

        $log = new Log('error.log');

        $wishlist_id = isset($this->request->post['wishlist_id'])?$this->request->post['wishlist_id']:null;

        if($wishlist_id) {
              
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->deleteWishlists($wishlist_id);

            $data['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function deleteWishlistProduct() {

        $data['status'] = false;

        $log = new Log('error.log');

        $wishlist_id = isset($this->request->post['wishlist_id'])?$this->request->post['wishlist_id']:null;

        $product_id = isset($this->request->post['product_id'])?$this->request->post['product_id']:null;

        if($wishlist_id && $product_id) {
              
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->deleteWishlistProduct($wishlist_id,$product_id);

            $data['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getProductWislists() {

        $log = new Log('error.log');
        $log = new Log('getProductWislists');
        $log = new Log('getProductWislists');

        $data['status'] = false;

        $this->load->model('account/wishlist');
        $this->load->model('assets/category');

        if ( $this->customer->isLogged() && isset($this->request->post['product_id'])) {
            $lists = $this->model_assets_category->getUserLists();

            

            $log->write($this->request->post['product_id']);

            

            $p ='';

            foreach ($lists as $list) {

                $present = $this->model_account_wishlist->getProductOfWishlist($list['wishlist_id'],$this->request->post['product_id']);

                $log->write($present);
                if($present) {
                    $inp = '<input type="checkbox" class="" name="add_to_list[]" value="'.$list['wishlist_id'].'" checked>';
                } else {
                    $inp = '<input type="checkbox" class="" name="add_to_list[]" value="'.$list['wishlist_id'].'">';
                }

                $p .= '<tr>
                    <td>'.$list['name'].'</td>
                    <td class="">'.$inp.' </td>
                </tr>';

            }

            $data['status'] = true;
            $data['html'] = $p;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    public function updateWishlistProduct() {

        $data['status'] = false;

        $log = new Log('error.log');

        $wishlist_id = isset($this->request->post['wishlist_id'])?$this->request->post['wishlist_id']:null;

        $product_id = isset($this->request->post['product_id'])?$this->request->post['product_id']:null;

        $quantity = isset($this->request->post['quantity'])?$this->request->post['quantity']:null;

        if($wishlist_id && $product_id) {
              
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->updateWishlistProduct($wishlist_id,$product_id,$quantity);

            $data['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function addWishlistProductToCart() {

        $this->load->language('account/wishlist');
        $this->load->model('account/wishlist');

        $data['text_cart_success'] = $this->language->get('text_cart_success');
        $log = new Log('error.log');
        $wishlist_id = $this->request->post['wishlist_id'];
        
        $wishlist_products =  $this->model_account_wishlist->getWishlistProduct($wishlist_id);
        $log->write('Wish List Products');
        $log->write($wishlist_products);
        $log->write('Wish List Products');
        
        if(is_array($wishlist_products) && count($wishlist_products) > 0) {
            foreach ($wishlist_products as $wishlist_product) {
            $log->write('Wish List Products 2');
            $log->write($wishlist_product['product_id']);    
            $log->write('Wish List Products 2');
            $this->load->model('assets/product');
            $store_data = $this->model_assets_product->getProductStoreId($wishlist_product['product_id'], 75);
            $log->write('store details');
            $log->write($store_data);
            $log->write('store details');
            $this->cart->addCustom($store_data['product_store_id'], $wishlist_product['quantity'], $option = array(), $recurring_id = 0, $store_id= false, $store_product_variation_id= false,$product_type = 'replacable',$product_note=null,$produce_type=null);
            }
        }
        $this->model_account_wishlist->deleteWishlists($wishlist_id);
        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
    
    public function addWishlistProductToCartByProduct() {

        $this->load->language('account/wishlist');
        $this->load->model('account/wishlist');

        $data['text_cart_success'] = $this->language->get('text_cart_success');
        $log = new Log('error.log');
        $wishlist_id = $this->request->post['wishlist_id'];
        $product_id = $this->request->post['wishlist_id'];
        
        $wishlist_products =  $this->model_account_wishlist->getProductOfWishlist($wishlist_id, $product_id);
        $log->write('Wish List Products');
        $log->write($wishlist_products);
        $log->write('Wish List Products');
        
        if(is_array($wishlist_products) && count($wishlist_products) > 0) {
            foreach ($wishlist_products as $wishlist_product) {
            $log->write('Wish List Products 2');
            $log->write($wishlist_product['product_id']);    
            $log->write('Wish List Products 2');
            $this->load->model('assets/product');
            $store_data = $this->model_assets_product->getProductStoreId($wishlist_product['product_id'], 75);
            $log->write('store details');
            $log->write($store_data);
            $log->write('store details');
            $this->cart->addCustom($store_data['product_store_id'], $wishlist_product['quantity'], $option = array(), $recurring_id = 0, $store_id= false, $store_product_variation_id= false,$product_type = 'replacable',$product_note=null,$produce_type=null);
            }
        }
        $this->model_account_wishlist->deleteWishlists($wishlist_id);
        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
    
    public function createWishlist() {

  

        $this->load->language('account/wishlist');

        $data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');

        $data['text_error_name_list'] = $this->language->get('text_error_name_list');
        $data['text_error_list'] = $this->language->get('text_error_list');

        $data['message'] = '';
        $data['status'] = false;

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        // $log->write($this->request->post['name']);
        // $log->write($this->request->post['listproductId']);
        $log->write($this->request->get['listproductId']);
        $log->write("createWishlist");
        if( isset($this->request->get['listproductId']) ) {// isset($this->request->post['name']) &&
            //$count = $this->model_account_wishlist->getWishlistPresent($this->request->post['name']);
             $log->write($count);
             $count = $this->model_account_wishlist->getWishlistPresentForCustomer();

            if(!$count) {
                //not present
                $wishlist_id = $this->model_account_wishlist->createWishlist('wishlist');//$this->request->post['name']

                $this->model_account_wishlist->addProductToWishlist($wishlist_id,$this->request->get['listproductId']);

                $data['status'] = true;

                $data['message'] = $data['text_success_created_list'];
            } else {
                //wishlist present
                //$data['message'] = $data['text_error_name_list'];
                $wishlist_id=$count;
                $this->model_account_wishlist->addProductToWishlist($wishlist_id,$this->request->get['listproductId']);
                $data['status'] = true;

                $data['message'] = $data['text_success_created_list'];
            }

        } else {
            $data['message'] = $data['text_error_list'];
        }
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function addProductToWishlist() {


        $this->load->language('account/wishlist');

        $data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');

        $data['text_error_name_list'] = $this->language->get('text_error_name_list');
        $data['text_error_list'] = $this->language->get('text_error_list');

        $data['message'] = $data['text_success_added_in_list'];
        $data['status'] = true;

        $log = new Log('error.log');

        $this->load->model('account/wishlist');
        $this->load->model('assets/category');
        $wishlist_ids = isset($this->request->post['add_to_list'])?$this->request->post['add_to_list']:null;

        if ( $this->customer->isLogged()) {
            $lists = $this->model_assets_category->getUserLists();

            foreach ($lists as $list) {
                $this->model_account_wishlist->deleteWishlistProduct($list['wishlist_id'],$this->request->post['listproductId']);
            }
        }
            
        $log->write($wishlist_ids);
        $log->write($this->request->post['listproductId']);
        $log->write("createWishlist");
        if( isset($this->request->post['listproductId']) && isset($wishlist_ids) ) {

            

            foreach ($wishlist_ids as $wishlist_id) {
                $count = $this->model_account_wishlist->getWishlistPresentById($wishlist_id);

                $log->write($count);
                $log->write("count");
                if($count) {
                    //present

                    $exists = $this->model_account_wishlist->getProductOfWishlist($wishlist_id,$this->request->post['listproductId']);

                    $log->write($exists);
                    $log->write("exists");

                    if(count($exists) > 0 ) {
                        $quantity = $exists['quantity'] + 1;

                        $this->model_account_wishlist->updateWishlistProduct($wishlist_id,$this->request->post['listproductId'],$quantity);
                    } else {
                        $this->model_account_wishlist->addProductToWishlist($wishlist_id,$this->request->post['listproductId']);
                    }

                    $data['status'] = true;

                    $data['message'] = $data['text_success_added_in_list'];
                    
                } else {
                    //wishlist not present
                }
            }
        } else {
            //$data['message'] = $data['text_error_list'];
        }
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }



    public function addProdToWishlist($product_id) {


        $this->load->language('account/wishlist');

        $data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');

        $data['text_error_name_list'] = $this->language->get('text_error_name_list');
        $data['text_error_list'] = $this->language->get('text_error_list');

        $data['message'] = $data['text_success_added_in_list'];
        $data['status'] = true;

        $log = new Log('error.log');

        $this->load->model('account/wishlist');
        $this->load->model('assets/category');      

        if ( $this->customer->isLogged()) {
            $lists = $this->model_assets_category->getUserLists();

            foreach ($lists as $list) {
                $this->model_account_wishlist->deleteWishlistProduct($list['wishlist_id'],$this->request->post['listproductId']);
            }
        }
            
        $log->write($wishlist_ids);
        $log->write($this->request->post['listproductId']);
        $log->write("createWishlist");
        if( isset($this->request->post['listproductId']) && isset($wishlist_ids) ) {

            

            foreach ($wishlist_ids as $wishlist_id) {
                $count = $this->model_account_wishlist->getWishlistPresentById($wishlist_id);

                $log->write($count);
                $log->write("count");
                if($count) {
                    //present

                    $exists = $this->model_account_wishlist->getProductOfWishlist($wishlist_id,$this->request->post['listproductId']);

                    $log->write($exists);
                    $log->write("exists");

                    if(count($exists) > 0 ) {
                        $quantity = $exists['quantity'] + 1;

                        $this->model_account_wishlist->updateWishlistProduct($wishlist_id,$this->request->post['listproductId'],$quantity);
                    } else {
                        $this->model_account_wishlist->addProductToWishlist($wishlist_id,$this->request->post['listproductId']);
                    }

                    $data['status'] = true;

                    $data['message'] = $data['text_success_added_in_list'];
                    
                } else {
                    //wishlist not present
                }
            }
        } else {
            //$data['message'] = $data['text_error_list'];
        }
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function deleteWishlistProductByID() {

        $data['status'] = false;

        $log = new Log('error.log');

        // $wishlist_id = isset($this->request->post['wishlist_id'])?$this->request->post['wishlist_id']:null;

        // $product_id = isset($this->request->post['product_id'])?$this->request->post['product_id']:null;

        // if($wishlist_id && $product_id) {
         if($this->request->get['listproductId']) {
              
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->deleteWishlistProductByID($this->request->get['listproductId']);

            $data['status'] = true;
          }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


}