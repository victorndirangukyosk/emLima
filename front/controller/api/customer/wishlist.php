<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerWishlist extends Controller
{
    private $error = [];

    public function getUserList()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (true) {
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
                $wishlist_products = $this->model_account_wishlist->getWishlistProduct($result['wishlist_id']);

                $totalCount = 0;

                if (!empty($wishlist_products)) {
                    $totalCount = count($wishlist_products);
                }

                $data['wishlists'][] = [
                    'wishlist_id' => $result['wishlist_id'],
                    'name' => $result['name'],
                    'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
                    'product_count' => $totalCount,
                    'products' => $wishlist_products,
                    'href' => $this->url->link('account/wishlist/info', 'wishlist_id='.$result['wishlist_id'], 'SSL'),
                ];
            }

            $data['results'] = sprintf($this->language->get('text_pagination'), ($wishlist_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($wishlist_total - 10)) ? $wishlist_total : ((($page - 1) * 10) + 10), $wishlist_total, ceil($wishlist_total / 10));

            $data['wishlist_total'] = $wishlist_total;

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addProductToWishlist()
    {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        if (isset($this->request->post['listproductId']) && isset($this->request->post['add_to_list'])) {
            $wishlist_ids = $this->request->post['add_to_list'];

            foreach ($wishlist_ids as $wishlist_id) {
                $count = $this->model_account_wishlist->getWishlistPresentById($wishlist_id);
                $log->write($count);
                $log->write('count');
                if ($count) {
                    //present

                    $exists = $this->model_account_wishlist->getProductOfWishlist($wishlist_id, $this->request->post['listproductId']);

                    $log->write($exists);
                    $log->write('exists');

                    if (count($exists) > 0) {
                        $quantity = $exists['quantity'] + 1;

                        $this->model_account_wishlist->updateWishlistProduct($wishlist_id, $this->request->post['listproductId'], $quantity);
                    } else {
                        $this->model_account_wishlist->addProductToWishlist($wishlist_id, $this->request->post['listproductId']);
                    }
                } else {
                    //wishlist not present

                    //$json['status'] = 10013;

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error_name_list')];

                    //http_response_code(400);
                }
            }

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success_added_in_list')];
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error_list')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editDeleteWishlist($args)
    {
        //echo "<pre>";print_r("editWishlistProduct");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        //echo "<pre>";print_r($args);die;
        if (isset($args['wishlist_id'])) {
            $wishlist_id = $args['wishlist_id'];

            $this->model_account_wishlist->deleteWishlists($wishlist_id);

            $deleted_wishlist = 'Successfully Deleted list!';

            $json['message'][] = ['type' => '', 'body' => $deleted_wishlist];
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error_list')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editDeleteWishlistProduct($args)
    {
        //echo "<pre>";print_r("delete widh");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        //echo "<pre>";print_r($args);die;
        if (isset($args['wishlist_id']) && isset($args['product_id'])) {
            $wishlist_id = $args['wishlist_id'];

            $product_id = $args['product_id'];

            $this->model_account_wishlist->deleteWishlistProduct($wishlist_id, $product_id);

            $deleted_product_from_wishlist = 'Successfully removed product from  list!';
            $json['message'][] = ['type' => '', 'body' => $deleted_product_from_wishlist];
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error_list')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editWishlistProduct($args)
    {
        //echo "<pre>";print_r("editWishlistProduct");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        //echo "<pre>";print_r($args);die;
        if (isset($args['wishlist_id']) && isset($args['product_id']) && isset($args['quantity'])) {
            $log = new Log('error.log');

            $wishlist_id = isset($args['wishlist_id']) ? $args['wishlist_id'] : null;

            $product_id = isset($args['product_id']) ? $args['product_id'] : null;

            $quantity = isset($args['quantity']) ? $args['quantity'] : null;

            $this->model_account_wishlist->updateWishlistProduct($wishlist_id, $product_id, $quantity);

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success_added_in_list')];
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error_list')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCreateWishlistWithProduct($args=[])
    {
        //echo "<pre>";print_r("addCreateWishlistWithProduct");die;

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->language('account/wishlist');
        //  echo "<pre>";print_r($args);die;


        /*$data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');*/

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        $log->write($args['name']);
        $log->write($args['products']);
        $log->write('createWishlist');
        if (isset($args['name']) && isset($args['products'])) {
            $count = $this->model_account_wishlist->getWishlistID($args['name']);
            $log->write($count);

            if (!$count) {
                //not present
                $wishlist_id = $this->model_account_wishlist->createWishlist($args['name']);
                foreach($args['products'] as $product)
                {
                $this->model_account_wishlist->addProductToWishlistWithQuantity($wishlist_id, $product['product_id'], $product['quantity'], $product['product_note']);
                }

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success_created_list')];
            } else {
                //wishlist present

                $wishlist_id = $count;
                // $this->model_account_wishlist->addProductToWishlist($wishlist_id, $this->request->get['listproductId']);
                foreach($args['products'] as $product)
                {
                $this->model_account_wishlist->addProductToWishlistWithQuantity($wishlist_id, $product['product_id'], $product['quantity'], $product['product_note']);
                }
                
                $data['status'] = true; 

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success_created_list')];
 
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error_list')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addWishlistProductToCart()
    {
        $this->load->language('account/wishlist');

        $data['text_cart_success'] = $this->language->get('text_cart_success');

        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getUserListProduct()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (isset($this->request->get['wishlist_id'])) {
            $store_id = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : false;
            $wishlist_id = $this->request->get['wishlist_id'];

            $data['store_selected'] = true;
            $store_id = ACTIVE_STORE_ID;

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
                $data['products'] = [];

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
                    if ($store_id) {
                        $productStoreData = $this->model_assets_product->getProductStoreId($product['product_id'], $store_id);

                        //echo "<pre>";print_r($productStoreData);die;

                        if (count($productStoreData) > 0) {
                            $product_store_id = $productStoreData['product_store_id'];
                        }

                        $productStoreData1 = $this->model_assets_product->getProductStoreIdAvailable($product['product_id'], $store_id);

                        if (count($productStoreData1) > 0) {
                            $fromStore = true;
                        }
                    }

                    //echo "<pre>";print_r($product_store_id);die;
                    $product_info = $this->model_assets_product->getDetailproduct($product_store_id);

                    $name = $product['name'];
                    $s_price = 0;
                    $o_price = 0;
                    $special_price = 0;
                    $price = 0;

                    if (isset($product_info) && count($product_info) > 0) {
                      

                        // if (!$this->config->get('config_inclusiv_tax')) {
                        //     //get price html
                        //     if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        //         $price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));

                        //         $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        //     } else {
                        //         $price = false;
                        //     }
                        //     if ((float) $product_info['special_price']) {
                        //         $special_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));

                        //         $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        //     } else {
                        //         $special_price = false;
                        //     }
                        // } else {
                        //     if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        //         $price = $product_info['price'];
                        //     } else {
                        //         $price = $product_info['price'];
                        //     }

                        //     if ((float) $product_info['special_price']) {
                        //         $special_price = $product_info['special_price'];
                        //     } else {
                        //         $special_price = $product_info['special_price'];
                        //     }

                        //     $s_price = $product_info['special_price'];
                        //     $o_price = $product_info['price'];
                        // }

                        // if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                        //     $percent_off = (($o_price - $s_price) / $o_price) * 100;
                        // }

                        // if (is_null($special_price) || !($special_price + 0)) {
                        //     //$special_price = 0;
                        //     $special_price = $price;
                        // }

                        if ((float) $product_info['special_price']) {
                            $special_price = $this->currency->format($product_info['special_price']);
                        } else {
                            $special_price = $product_info['special_price'];
                        }
    
                        if ((float) $product_info['price']) {
                            $price = $this->currency->format($product_info['price']);
                        } else {
                            $price = $product_info['price'];
                        }
                        

                        if (isset($product_info['pd_name'])) {
                            //$result['name'] = $result['pd_name'];
                            $name = $product_info['pd_name'];
                        }
                    }

                    //echo "<pre>";print_r($product_info);die;
                    $this->load->model('tool/image');

                    $category_status_price_details = $this->model_assets_product->getCategoryPriceStatusByProductStoreId($product_store_id);
                    $log = new Log('error.log');
                    $log->write($category_status_price_details);


                    $data['products'][] = [
                        'name' => html_entity_decode($name),
                        'image' => $product['image'],
                        'quantity' => $product['quantity'],
                        'product_id' => $product['product_id'],
                        'is_from_active_store' => $fromStore,
                        'product_store_id' => $product_store_id,
                        'price' => $price,
                        'special' => $special_price,
                        'percent_off' => number_format($percent_off, 0),
                        'unit' => $product['unit'],
                        'product_info' => $product_info,
                        'left_symbol_currency' => $this->currency->getSymbolLeft(),
                        'right_symbol_currency' => $this->currency->getSymbolRight(),
                        'category_price' => $this->model_assets_product->getCategoryPriceStatusByProductStoreId($product_store_id),
                        'status' => isset($product_info['pd_name']) && count($product_info) > 0 ? 1 : 0,
                        'category_price_status' => is_array($category_status_price_details) && array_key_exists('status', $category_status_price_details) ? $category_status_price_details['status'] : 1,
                        'product_note' => $product['product_note'],
                         
                    ];
                }

                // $data['total_quantity'] = 0;
                // foreach ($data['products'] as $product) {
                //     $data['total_quantity'] += $product['quantity'];
                // }

                $json['data'] = $data;
            } else {
                $json['status'] = 10028;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_wishlist_detail_not_found')];

                http_response_code(400);
            }
        } else {
            $json['status'] = 10027;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_store_not_selected')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
 

    public function getAvailableOrderProducts()
    {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('account/wishlist');
        $this->load->model('account/wishlist');

        $data['text_cart_success'] = "Available previous order products are fecthed";
        $log = new Log('error.log');
        // echo "<pre>";print_r($this->request->post['order_id']);die;
        // $wishlist_id = $this->request->post['wishlist_id'];
         $order_id = $this->request->get['order_id'];
        $log->write($this->request->get['order_id']);
        $log->write('Order List Products');

        $Orderlist_products = $this->model_account_wishlist->getAvailableOrderedProducts($order_id);
        $log->write($Orderlist_products);
        $log->write('Order List Products obtained');

        if (is_array($Orderlist_products) && count($Orderlist_products) > 0) {
        $log->write('Order List Products inner');
        $data['products'] = [];
        $data['store_id'] = ACTIVE_STORE_ID;

            foreach ($Orderlist_products as $Orderlist_product) {
                $log->write('Order List Products 2');
                $log->write($Orderlist_product['product_id']);
                $log->write('Order List Products 2');
                $this->load->model('assets/product');

                $fromStore = false;
                $product_store_id = 0;

                // product_store_id 11
                if ($data['store_id']) {
                    $productStoreData = $this->model_assets_product->getProductStoreId($Orderlist_product['product_id'], $data['store_id']);

                    //echo "<pre>";print_r($productStoreData);die;

                    if (count($productStoreData) > 0) {
                        $product_store_id = $productStoreData['product_store_id'];
                        $fromStore = true;
                    }
                }

                $store_data = $this->model_assets_product->getProductStoreId($Orderlist_product['product_id'], 75);
                $product_info = $this->model_assets_product->getDetailproduct($store_data['product_store_id']);
                $special_price = 0;
                $price = 0;

                if (count($product_info) > 0) {
                    //echo "<pre>";print_r($product_info);die;

                    if ((float) $product_info['special_price']) {
                        $special_price = $this->currency->format($product_info['special_price']);
                    } else {
                        $special_price = $product_info['special_price'];
                    }

                    if ((float) $product_info['price']) {
                        $price = $this->currency->format($product_info['price']);
                    } else {
                        $price = $product_info['price'];
                    }
                }
                $category_status_price_details = $this->model_assets_product->getCategoryPriceStatusByProductStoreId($store_data['product_store_id']);
                $log = new Log('error.log');
                $log->write($category_status_price_details);
                $category_price_status = is_array($category_status_price_details) && array_key_exists('status', $category_status_price_details) ? $category_status_price_details['status'] : 1;
                
                if(true) {// isset($product_info) && count($product_info) > 0 && $category_price_status == 1
                $log->write('store details');
                $log->write($store_data);
                $log->write('store details');
                //in mobile app just display the products, to make order seperate method will be called
                //$this->cart->addCustom($store_data['product_store_id'], $Orderlist_product['quantity'], $option = [], $recurring_id = 0, $store_data['store_id'], $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null);
               
                $data['products'][] = [
                    'name' => isset($product_info['pd_name']) ? $product_info['pd_name'] : $Orderlist_product['name'],
                    'image' => $Orderlist_product['image'],
                    'quantity' => $Orderlist_product['quantity'],
                    'product_id' => $Orderlist_product['product_id'],
                    'is_from_active_store' => $fromStore,
                    'product_store_id' => $product_store_id,
                    'unit' => $Orderlist_product['unit'],
                    'price' => $price,
                    'special_price' => $special_price,
                    'category_price' => $this->model_assets_product->getCategoryPriceStatusByProductStoreId($product_store_id),
                    'status' => isset($product_info['pd_name']) && count($product_info) > 0 ? 1 : 0,
                    'category_price_status' => is_array($category_status_price_details) && array_key_exists('status', $category_status_price_details) ? $category_status_price_details['status'] : 1,
                       /* 'store_id'     => $product['store_id'],
                    'model'    => $product['model'],*/

                    /*'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $wishlist_info['currency_code'], $wishlist_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $wishlist_info['currency_code'], $wishlist_info['currency_value']),*/
                ];
            
            }
            }
        }
        // $this->model_account_wishlist->deleteWishlists($wishlist_id);
        //echo "reg";

        // $data['results'] = sprintf($this->language->get('text_pagination'), ($wishlist_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($wishlist_total - 10)) ? $wishlist_total : ((($page - 1) * 10) + 10), $wishlist_total, ceil($wishlist_total / 10));

        // $data['wishlist_total'] = $wishlist_total;

        $json['data'] = $data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}
