<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

use \Konduto\Core\Konduto;
use \Konduto\Models;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerWishlist extends Controller {

    private $error = array();

    public function getUserList() {
      
        $json = array();
        
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if( true ) {

            //$store_id = $this->request->get['store_id'];

            $this->load->language('account/wishlist');
	        
	        $this->load->model('account/wishlist');

	        if (isset($this->request->get['page'])) {
	            $page = $this->request->get['page'];
	        } else {
	            $page = 1;
	        }
	        
	        $wishlist_total = $this->model_account_wishlist->getTotalWishlist();

	        $results = $this->model_account_wishlist->getWishlists(($page - 1) * 10, 10);

            $data['wishlists'] = [];
            
	        //echo "<pre>";print_r($results);die;
	        foreach ($results as $result) {

	            $wishlist_products =  $this->model_account_wishlist->getWishlistProduct($result['wishlist_id']);
	            
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
	        
	        $data['results'] = sprintf($this->language->get('text_pagination'), ($wishlist_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($wishlist_total - 10)) ? $wishlist_total : ((($page - 1) * 10) + 10), $wishlist_total, ceil($wishlist_total / 10));

            $data['wishlist_total'] = $wishlist_total;

            $json['data'] = $data;

        }  else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_not_loggedin') ];

            http_response_code(400);
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addProductToWishlist() {


        $json = array();
        
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');
        
        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        if( isset($this->request->post['listproductId']) && isset($this->request->post['add_to_list']) ) {

            $wishlist_ids = $this->request->post['add_to_list'];

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

                } else {
                    //wishlist not present

                    //$json['status'] = 10013;

                    $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_name_list') ];

                    //http_response_code(400);
                }
            }

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_success_added_in_list') ];

        } else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_list') ];

            http_response_code(400);

        }
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function editDeleteWishlist($args) {

        //echo "<pre>";print_r("editWishlistProduct");die;
        $json = array();
        
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');
        
        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        //echo "<pre>";print_r($args);die;
        if( isset($args['wishlist_id'])) {

            $wishlist_id = $args['wishlist_id'];

            $this->model_account_wishlist->deleteWishlists($wishlist_id);
            
            $deleted_wishlist = 'Successfully Deleted list!';

            $json['message'][] = ['type' =>  '' , 'body' =>  $deleted_wishlist ];

        } else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_list') ];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));   
    }

    public function editDeleteWishlistProduct($args) {

        //echo "<pre>";print_r("delete widh");die;
        $json = array();
        
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');
        
        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        //echo "<pre>";print_r($args);die;
        if( isset($args['wishlist_id']) && isset($args['product_id']) ) {

            $wishlist_id = $args['wishlist_id'];

            $product_id = $args['product_id'];


            $this->model_account_wishlist->deleteWishlistProduct($wishlist_id,$product_id);

            $deleted_product_from_wishlist = 'Successfully removed product from  list!';
            $json['message'][] = ['type' =>  '' , 'body' =>  $deleted_product_from_wishlist ];

        } else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_list') ];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editWishlistProduct($args) {

        //echo "<pre>";print_r("editWishlistProduct");die;
        $json = array();
        
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');
        
        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        //echo "<pre>";print_r($args);die;
        if( isset($args['wishlist_id']) && isset($args['product_id'])  && isset($args['quantity']) ) {

            $log = new Log('error.log');

            $wishlist_id = isset($args['wishlist_id'])?$args['wishlist_id']:null;

            $product_id = isset($args['product_id'])?$args['product_id']:null;

            $quantity = isset($args['quantity'])?$args['quantity']:null;

           
            $this->model_account_wishlist->updateWishlistProduct($wishlist_id,$product_id,$quantity);

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_success_added_in_list') ];

        } else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_list') ];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function addCreateWishlistWithProduct($args) {

        //echo "<pre>";print_r("addCreateWishlistWithProduct");die;

        $json = array();
        
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');

        /*$data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');*/
        
        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        $log->write($this->request->post['name']);
        $log->write($this->request->post['listproductId']);
        $log->write("createWishlist");
        if( isset($this->request->post['name']) && isset($this->request->post['listproductId']) ) {
            $count = $this->model_account_wishlist->getWishlistPresent($this->request->post['name']);
            $log->write($count);

            if(!$count) {
                //not present
                $wishlist_id = $this->model_account_wishlist->createWishlist($this->request->post['name']);

                $this->model_account_wishlist->addProductToWishlist($wishlist_id,$this->request->post['listproductId']);

                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_success_created_list') ];

            } else {
                //wishlist present

                $json['status'] = 10013;

                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_name_list') ];

                http_response_code(400);

            }

        } else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_error_list') ];

            http_response_code(400);

        }
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function addWishlistProductToCart() {

        $this->load->language('account/wishlist');

        $data['text_cart_success'] = $this->language->get('text_cart_success');

        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    public function getUserListProduct() {
        $json = array();
        
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if(isset($this->request->get['wishlist_id'])) {

            $store_id = isset($this->request->get['store_id'])?$this->request->get['store_id']:false;
            $wishlist_id = $this->request->get['wishlist_id'];

            $data['store_selected'] = true;

            $this->load->model('account/wishlist');

            $wishlist_info = $this->model_account_wishlist->getWishlist($wishlist_id);

            //echo "<pre>";print_r($wishlist_info);die;
            if ($wishlist_info) {

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

                $data['text_not_available'] = $this->language->get('text_not_available');
                
                $data['date_added'] = date($this->language->get('date_format_short'), strtotime($wishlist_info['date_added']));
                        
                $this->load->model('assets/product');
                $this->load->model('account/wishlist');
                $this->load->model('tool/upload');

                // Products
                $data['products'] = array();

                $products = $this->model_account_wishlist->getWishlistProduct($this->request->get['wishlist_id']);

                //echo "<pre>";print_r($products);die;

                foreach ($products as $product) {

                    //below one we need to send product_store_id
                    $fromStore = false;
                    $product_store_id = 0;
                    $price = 0;
                    $special_price = 0;
                    $percent_off = 0;


                    // product_store_id 11
                    if($store_id) {

                        $productStoreData = $this->model_assets_product->getProductStoreId($product['product_id'],$store_id);

                        //echo "<pre>";print_r($productStoreData);die;

                        if(count($productStoreData) > 0 ) {
                            $product_store_id   = $productStoreData['product_store_id'];
                        }

                        $productStoreData1 = $this->model_assets_product->getProductStoreIdAvailable($product['product_id'],$store_id);

                        if(count($productStoreData1) > 0 ) {
                            $fromStore = true;
                        }

                        
                    }
                    
                    //echo "<pre>";print_r($product_store_id);die;
                    $product_info = $this->model_assets_product->getDetailproduct($product_store_id);

                    $name= $product['name'];
                    
                    if(isset($product_info) && count($product_info) > 0 ) {

                        $s_price = 0;
                        $o_price = 0;

                        if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
                            //get price html
                            if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
                                $price = $this->tax->calculate( $product_info['price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ));

                                $o_price = $this->tax->calculate( $product_info['price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ) );

                            } else {
                                $price = false;
                            }
                            if ( (float) $product_info['special_price'] ) {
                                $special_price = $this->tax->calculate( $product_info['special_price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ));

                                $s_price = $this->tax->calculate( $product_info['special_price'], $product_info['tax_class_id'], $this->config->get( 'config_tax' ) );

                            } else {
                                $special_price = false;
                            }
                        }else {
                            if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
                                $price = $product_info['price'];
                            } else {
                                $price = $product_info['price'];
                            }

                            if ( (float) $product_info['special_price'] ) {
                                $special_price = $product_info['special_price'];
                            } else {
                                $special_price = $product_info['special_price'];
                            }

                            $s_price = $product_info['special_price'];
                            $o_price = $product_info['price'];

                        }

                        if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
                            $percent_off = (($o_price - $s_price) / $o_price) * 100;
                        } 

                        if(is_null($special_price) || !($special_price + 0) ) {
                            //$special_price = 0;
                            $special_price = $price;
                        }

                        

                        if (isset($product_info['pd_name'] ) ) {
                            //$result['name'] = $result['pd_name'];
                            $name = $product_info['pd_name'];
                        }

                    }

                    


                    //echo "<pre>";print_r($product_info);die;
                    $this->load->model('tool/image');


                    $data['products'][] = array(
                        
                        'name'     => html_entity_decode($name),
                        'image'    => $product['image'],
                        'quantity' => $product['quantity'],
                        'product_id' => $product['product_id'],
                        'is_from_active_store' => $fromStore,
                        'product_store_id' => $product_store_id,
                        'price' => $price,
                        'special' => $special_price,
                        'percent_off' => number_format($percent_off,0),
                        'unit'     => $product['unit'],
                        'product_info' => $product_info,
                        'left_symbol_currency'      => $this->currency->getSymbolLeft(),
                        'right_symbol_currency'      => $this->currency->getSymbolRight(),
                    );
                }

                // $data['total_quantity'] = 0;
                // foreach ($data['products'] as $product) {
                //     $data['total_quantity'] += $product['quantity'];
                // }

                $json['data'] = $data;

            } else {
                
                $json['status'] = 10028;

                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_wishlist_detail_not_found') ];

                http_response_code(400);     
            }

            
        }  else {

            $json['status'] = 10027;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_store_not_selected') ];

            http_response_code(400);
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
