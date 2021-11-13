<?php

class ControllerProductStore extends Controller {

    public function index() {
        //echo "cer";die;
        //echo "<pre>";
        //print_r($_COOKIE);die;
        if (!isset($this->session->data['customer_id'])) {
            if (isset($_REQUEST['action']) && ('shop' == $_REQUEST['action'])) {
                $this->response->redirect($this->url->link('account/login/customer'));
            } else {
                /* REMOVED FOR HOME PAGE ON DOMINE NAME
                 * $this->response->redirect($this->url->link('common/home/homepage')); */
                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $data['base'] = $server;
                $data['is_login'] = $this->customer->isLogged();
                $data['f_name'] = $this->customer->getFirstName();
                $data['name'] = $this->customer->getFirstName();
                $data['l_name'] = $this->customer->getLastName();
                $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
                $data['home'] = $this->url->link('common/home');
                $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
                $data['logged'] = $this->customer->isLogged();
                $data['account'] = $this->url->link('account/account', '', 'SSL');
                $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

                $data['register'] = $this->url->link('account/register', '', 'SSL');
                $data['login'] = $this->url->link('account/login', '', 'SSL');
                $data['order'] = $this->url->link('account/order', '', 'SSL');
                $data['credit'] = $this->url->link('account/credit', '', 'SSL');
                $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
                $data['pezesha_loans'] = $this->url->link('account/pezeshaloans', '', 'SSL');
                $data['download'] = $this->url->link('account/download', '', 'SSL');
                $data['logout'] = $this->url->link('account/logout', '', 'SSL');
                $data['shopping_cart'] = $this->url->link('checkout/cart');
                $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
                $data['contact'] = $this->url->link('information/contact');
                $data['telephone'] = $this->config->get('config_telephone');
                $data['refer'] = $this->url->link('account/refer', '', 'SSL');
                $data['reward'] = $this->url->link('account/reward', '', 'SSL');
                $data['footer'] = $this->load->controller('common/footer');
                $data['action'] = $this->url->link('common/home/find_store');
                $data['address'] = $this->url->link('account/address', '', 'SSL');
                $data['help'] = $this->url->link('information/help');
                $data['language'] = $this->load->controller('common/language/dropDown');
                $data['login'] = $this->url->link('account/login', '', 'SSL');
                $data['register'] = $this->url->link('account/register', '', 'SSL');
                $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
                $data['store_name'] = $this->config->get('config_name');

                if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
                    //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
                    $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
                } else {
                    $data['logo'] = 'assets/img/logo.svg';
                }

//    echo "<pre>";print_r($data);die;

                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/index.tpl', $data));
            }
        }

        if (isset($this->request->get['store_id'])) {
            $this->session->data['config_store_id'] = $this->request->get['store_id'];
        }

        /* if ( !(count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) || !isset($this->session->data['config_store_id']) ) {

          if(!isset($_COOKIE['location'])) {
          $this->response->redirect( $this->url->link( 'common/home/toHome' ) );
          }
          } */

        $this->load->language('product/store');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_categories.css');

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

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
            unset($this->session->data['config_store_id']);
        } else {
            $store_id = 0;
        }

        $store_info = $this->model_tool_image->getStore($store_id);
        $store_info['store_open_hours'] = $this->model_tool_image->getStoreOpenHours($store_id, date('w'));

        if (!$store_info) {
            unset($this->session->data['config_store_id']);
            $this->response->redirect($this->url->link('common/home'));
        }

        $title = isset($store_info['name']) ? $store_info['name'] : '';
        $data['current_store'] = $store_id;
        $data['heading_title'] = isset($store_info['name']) ? $store_info['name'] : '';
        $data['store_info'] = $store_info;
        //echo '<pre>';print_r($store_info);exit;

        $this->document->setTitle($title);

        $data['text_add_to_list'] = $this->language->get('text_add_to_list');
        $data['text_list_name'] = $this->language->get('text_list_name');
        $data['text_add_to'] = $this->language->get('text_add_to');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_enter_list_name'] = $this->language->get('text_enter_list_name');
        $data['text_create_list'] = $this->language->get('text_create_list');

        $data['location_name'] = '';
        $data['zipcode'] = '';
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $data['zipcode'] = $_COOKIE['zipcode'];
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $data['location_name'] = $this->getHeaderPlace($_COOKIE['location']);
            $data['location_name_full'] = $data['location_name'];

            /* if(isset($_COOKIE['location_name'])) {
              $data['location_name'] = $_COOKIE['location_name'];
              $data['location_name_full'] = $_COOKIE['location_name'];
              } */
        }

        $data['text_refine'] = $this->language->get('text_refine');
        $data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
        $data['text_change_location_name'] = $this->language->get('text_change_location_name');
        $data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');
        $data['text_incart'] = $this->language->get('text_incart');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_offer'] = $this->language->get('text_offer');
        $data['text_deliver'] = $this->language->get('text_deliver');
        $data['text_change_locality'] = $this->language->get('text_change_locality');

        $data['error_no_delivery'] = $this->language->get('error_no_delivery');

        $data['button_change'] = $this->language->get('button_change');
        $data['button_cart'] = $this->language->get('button_cart');

        $data['button_change_locality'] = $this->language->get('button_change_locality');
        $data['button_change_store'] = $this->language->get('button_change_store');

        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_clear_cart'] = $this->language->get('button_clear_cart');
        $data['button_checkout'] = $this->language->get('button_checkout');

        $data['toHome'] = $this->url->link('common/home/toHome');
        $data['toStore'] = $this->url->link('common/home/toStore');

        $data['lists'] = [];

        if (!$this->customer->isLogged()) {
            $data['checkout_link'] = $this->url->link('checkout/checkout');
        } else {
            //get listes
            $data['lists'] = $this->model_assets_category->getUserLists();
            $data['checkout_link'] = $this->url->link('checkout/checkout#collapseTwo');
        }

        //echo "<pre>";print_r($data['lists']);die;
        if (isset($store_info['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($store_info['logo'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
        } else {
            $data['thumb'] = '';
        }

        if (isset($store_info['banner_logo'])) {
            $data['banner_logo'] = $this->model_tool_image->resize($store_info['banner_logo'], 800, 450);
        } else {
            $data['banner_logo'] = $this->model_tool_image->resize('placeholder.png', 800, 450);
        }

        //echo "<pre>";print_r($store_info);die;
        if (isset($store_info['banner_logo_status'])) {
            //echo "<pre>";print_r("Ce");die;
            if (isset($this->session->data['show_banner']) && !$this->session->data['show_banner']) {
                
            } else {
                $this->session->data['show_banner'] = false;
                $data['show_banner'] = true;
            }
        }

        $data['description'] = '';
        //html_entity_decode($store_info['description'], ENT_QUOTES, 'UTF-8');
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

        $data['categories'] = [];

        //$results = $this->model_assets_category->getCategoryByStore(0);
        $results = $this->model_assets_category->getCategoryByStoreId($store_id, 0);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $filter_data = [
                'filter_category_id' => $result['category_id'],
                'filter_sub_category' => true,
                'start' => 0,
                'limit' => 500,
                'store_id' => $store_id,
            ];

            $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));

            $data['categories'][] = [
                'name' => $result['name'],
                'products' => $this->getProducts($filter_data),
                'href' => $this->url->link('product/category', 'category=' . $result['category_id'] . $url),
                'thumb' => $image,
            ];

//            echo "<pre>";print_r($data['categories']);die;
        }

        $template = 'top_category.tpl';

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['sorts'] = [];

        $data['sorts'][] = [
            'text' => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href' => $this->url->link('product/store', 'sort=p.sort_order&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href' => $this->url->link('product/store', 'sort=pd.name&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href' => $this->url->link('product/store', 'sort=pd.name&order=DESC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href' => $this->url->link('product/store', 'sort=p.price&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href' => $this->url->link('product/store', 'sort=p.price&order=DESC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href' => $this->url->link('product/store', 'sort=p.model&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href' => $this->url->link('product/store', 'sort=p.model&order=DESC' . $url),
        ];

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

        $data['limits'] = [];

        $limits = array_unique([$this->config->get('config_product_limit'), 25, 50, 75, 100]);

        sort($limits);

        foreach ($limits as $value) {
            $data['limits'][] = [
                'text' => $value,
                'value' => $value,
                'href' => $this->url->link('product/store', $url . '&limit=' . $value),
            ];
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

        $data['account_register'] = $this->load->controller('account/register');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');
        /* add Contact modal */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        $not_delivery = 0;
        /* if($this->cart->hasProducts()){
          $zipcode = $_COOKIE['zipcode'];

          foreach ($this->cart->getStores() as $s ) {
          $checkStore = $this->model_assets_product->getStoreByZip($zipcode,$s);
          if (!$checkStore) {
          $not_delivery = 1;
          break;
          }
          }
          } */

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        $data['offer_show'] = $this->config->get('config_offer_status');

        $data['offer_href'] = $this->url->link('product/offers');

        //$data['offer_products'] = $this->model_assets_product->getOffersByStore($store_id);

        $filter_data = [
            'filter_store_id' => $store_id,
            'start' => 0,
            'limit' => 6,
        ];

        $data['offer_products'] = [
            //'products' => $this->getOfferProducts( $filter_data )
            'products' => $this->getOfferProductsBySpecialPrice($filter_data),
        ];

        //echo "<pre>";print_r($data['offer_products']);die;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['not_delivery'] = $not_delivery;
        $data['change_store'] = $this->url->link('common/home/show_home', '', 'SSL');
        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/' . $template)) {
            //mvgv2/template/product/top_category.tpl
            // echo "<pre>";print_r($this->config->get( 'config_template' ) . '/template/product/'.$template);die;
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/' . $template, $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/' . $template, $data));
        }
    }

    //get variation html
    public function getVariation() {
        $product_store_id = $this->request->get['product_id'];
        $store_product_variation_id = $this->request->get['variation_id'];

        $this->load->model('assets/product');
        $this->load->model('tool/image');
        $this->load->language('product/store');

        $data['button_add'] = $this->language->get('button_add');
        //get variation
        $variation = $this->model_assets_product->getVariation($store_product_variation_id);
        //get product

        $data['product'] = $this->model_assets_product->getProduct($product_store_id);

        $s_price = 0;
        $o_price = 0;
        $percent_off = null;
        $json['percent_off'] = null;

        $json = [];
        if ($variation) {
            //get image

            $data['images'] = [];

            if ($variation['image']) {
                $thumb = $this->model_tool_image->resize($product_info['image'], 362, 317);
            } else {
                $thumb = $this->model_tool_image->resize('placeholder.png', 362, 317);
            }

            $data['images'][] = [
                'popup' => $thumb,
                'thumb' => $thumb,
            ];

            if ($variation['image']) {
                $json['image'] = $this->model_tool_image->resize($variation['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $json['image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            if ($variation['name']) {
                $json['product_unit'] = $variation['unit'] ? $variation['unit'] : false;

                $json['product_name'] = $variation['name'];
            } else {
                $json['product_name'] = false;
                $json['product_unit'] = false;
            }

            $data['product']['store_product_variation_id'] = $store_product_variation_id;

            //get qty in cart

            $data['product']['key'] = $key = base64_encode(serialize(['product_store_id' => (int) $data['product']['product_store_id'], 'store_product_variation_id' => (int) $store_product_variation_id, 'store_id' => $this->session->data['config_store_id']]));

            //get price html
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($variation['price'], $data['product']['tax_class_id'], $this->config->get('config_tax')));

                $o_price = $this->tax->calculate($variation['price'], $data['product']['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }

            if ((float) $variation['special_price']) {
                $special = $this->currency->format($this->tax->calculate($variation['special_price'], $data['product']['tax_class_id'], $this->config->get('config_tax')));

                $s_price = $this->tax->calculate($variation['special_price'], $data['product']['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = false;
            }

            $tax = $this->currency->format((float) $variation['special_price'] ? $variation['special_price'] : $variation['price']);

            $json['price_html'] = '';

            if (!$special) {
                $json['price_html'] .= '<span class="price-popup">';
                $json['price_html'] .= $price;
                $json['price_html'] .= '</span>';
            } else {
                $json['price_html'] .= '<span class="old-price-popup">';
                $json['price_html'] .= $price;
                $json['price_html'] .= '</span>';
                $json['price_html'] .= '&nbsp;<br><span class="price-popup">';
                $json['price_html'] .= $special;
                $json['price_html'] .= '</span>';
            }

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;

                $json['percent_off'] = number_format($percent_off, 0);
            }

            /* if ( $tax ) {
              $json['price_html'] .= '<span class="price-tax">';
              $json['price_html'] .= 'Ex Tax:&nbsp;';
              $json['price_html'] .= $tax;
              $json['price_html'] .= '</span>';
              } */
        } else {
            //get image
            $data['images'] = [];

            if (isset($data['product']['image'])) {
                $json['image'] = $this->model_tool_image->resize($data['product']['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

                $json['zoom_image'] = $this->model_tool_image->resize($data['product']['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height'));
            } else {
                $json['image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

                $json['zoom_image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height'));
            }

            $data['images'][] = [
                'popup' => $json['image'],
                'thumb' => $json['image'],
                'zoom_thumb' => $json['zoom_image'],
                'zoom_popup' => $json['zoom_image'],
            ];

            $results = $this->model_assets_product->getProductImages($data['product']['product_id']);
            foreach ($results as $result) {
                $data['images'][] = [
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'zoom_thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height')),
                    'zoom_popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height')),
                ];
            }

            if (isset($data['product']['name'])) {
                $json['product_unit'] = $data['product']['unit'] ? $data['product']['unit'] : false;

                $json['product_name'] = $data['product']['name'];

                $json['product_description'] = $data['product']['description'] ? html_entity_decode($data['product']['description']) : false;
            } else {
                $json['product_name'] = false;
                $json['product_description'] = false;
                $json['product_unit'] = false;
            }

            $data['product']['store_product_variation_id'] = 0;

            $data['product']['minimum'] = $data['product']['min_quantity'] > 0 ? $data['product']['min_quantity'] : $data['product']['quantity'];

            //get qty in cart
            $data['product']['key'] = $key = base64_encode(serialize(['product_store_id' => (int) $data['product']['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));

            //get price html
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($data['product']['price']);

                $o_price = $data['product']['price'];
            } else {
                $price = 0;
            }

            //special price html
            if ((float) $data['product']['special_price']) {
                $special = $this->currency->format($data['product']['special_price']);

                $s_price = $data['product']['special_price'];
            } else {
                $special = 0;
            }

            //if category discount define override special price
            $discount = $this->model_assets_product->getCategoryDiscount($data['product']['product_id'], $data['product']['price']);

            if ($discount) {
                $special = $this->currency->format($this->tax->calculate($discount, $data['product']['tax_class_id'], $this->config->get('config_tax')));
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $data['product']['special_price'] ? $data['product']['special_price'] : $data['product']['price']);
            } else {
                $tax = 0;
            }

            $json['price_html'] = '';

            if (!$special) {
                $json['price_html'] .= '<span class="price-popup">';
                $json['price_html'] .= $price;
                $json['price_html'] .= '</span>';
            } else {
                $json['price_html'] .= '<span class="old-price-popup">';
                $json['price_html'] .= $price;
                $json['price_html'] .= '</span>';
                $json['price_html'] .= '&nbsp;<br><span class="price-popup">';
                $json['price_html'] .= $special;
                $json['price_html'] .= '</span>';
            }

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;

                $json['percent_off'] = number_format($percent_off, 0);
            }
        }

        $json['image_html'] = '';
        foreach ($data['images'] as $images) {
            $json['image_html'] .= '<div class="easyzoom easyzoom--overlay">
                                        <a href="' . $images['zoom_popup'] . '">
                                            <img src="' . $images['popup'] . '" alt="" />
                                        </a>
                                    </div>';
        }

        if (isset($this->session->data['cart'][$key])) {
            $data['product']['qty_in_cart'] = $this->session->data['cart'][$key]['quantity'];
        } else {
            $data['product']['qty_in_cart'] = 0;
        }

        //echo "<pre>";print_r($json);die;
        //get action html
        $json['action_html'] = $this->load->view($this->config->get('config_template') . '/template/product/popup_unit_action.tpl', $data);

        echo json_encode($json);
    }

    public function getProducts($filter_data) {
        $cachePrice_data = $this->cache->get('category_price_data');
        $this->load->model('assets/product');
        $this->load->model('tool/image');
        $this->load->model('user/user');

        //$results = $this->model_assets_product->getProducts($filter_data);
        //$results = $this->model_assets_product->getProductsForHomePage($filter_data);
        $results = $this->model_assets_product->getProductsForCategoryPage($filter_data);
//        echo '<pre>';print_r($results); die;

        $data['products'] = [];

        foreach ($results as $result) {
            $vendor_details = $this->model_user_user->getUser($result['merchant_id']);
            // if qty less then 1 dont show product
            //REMOVED QUANTITY CHECK CONDITION
            /* if ($result['quantity'] <= 0) {
              continue;
              } */

            $log = new Log('error.log');
            if ($result['image'] != NULL && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else if ($result['image'] == NULL || !file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            //get qty in cart
            if (!empty($this->session->data['config_store_id'])) {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));
            } else {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $filter_data['store_id']]));
            }
            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            // Avoid adding duplicates for similar products with different variations

            $productNames = array_column($data['products'], 'name');
            if (false !== array_search($result['name'], $productNames)) {
                // Add variation to existing product
                $productIndex = array_search($result['name'], $productNames);
                // TODO: Check for product variation duplicates
                $data['products'][$productIndex][variations][] = [
                    'variation_id' => $result['product_store_id'],
                    'unit' => $result['unit'],
                    'weight' => floatval($result['weight']),
                    'price' => $price,
                    'special' => $special_price,
                ];
            } else {
                // Add as new product
                $data['products'][] = [
                    'key' => $key,
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'variations' => [
                        [
                            'variation_id' => $result['product_store_id'],
                            'unit' => $result['unit'],
                            'weight' => floatval($result['weight']),
                            'price' => $price,
                            'special' => $special_price,
                        ],
                    ],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                    'vendor_display_name' => $vendor_details['display_name'],
                ];
            }
        }

        return $data['products'];
    }

    public function getOfferProductsBySpecialPrice($filter_data) {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $results = $this->model_assets_product->getOfferProductsBySpecialPrice($filter_data);

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        if (is_array($results)) {
            foreach ($results as $result) {
                if ($result['quantity'] <= 0) {
                    continue;
                }

                if (file_exists(DIR_IMAGE . $result['image'])) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                }

                //if category discount define override special price
                $discount = '';

                $s_price = 0;
                $o_price = 0;

                if (!$this->config->get('config_inclusiv_tax')) {
                    //get price html
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                        $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $price = false;
                    }
                    if ((float) $result['special_price']) {
                        $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                        $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $special_price = false;
                    }
                } else {
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($result['price']);
                    } else {
                        $price = $result['price'];
                    }

                    if ((float) $result['special_price']) {
                        $special_price = $this->currency->format($result['special_price']);
                    } else {
                        $special_price = $result['special_price'];
                    }

                    $s_price = $result['special_price'];
                    $o_price = $result['price'];
                }

                //get qty in cart
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => isset($this->session->data['config_store_id']) ? $this->session->data['config_store_id'] : '']));

                if (isset($this->session->data['cart'][$key])) {
                    $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
                } else {
                    $qty_in_cart = 0;
                }

                //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
                $name = $result['name'];

                if (isset($result['pd_name'])) {
                    $name = $result['pd_name'];
                }

                //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

                $percent_off = null;
                if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                    $percent_off = (($o_price - $s_price) / $o_price) * 100;
                }

                $data['products'][] = [
                    'key' => $key,
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'unit' => $result['unit'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special_price,
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                ];
            }
        }
        return $data['products'];
    }

    public function getOfferProducts($filter_data) {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $results = $this->model_assets_product->getOfferProducts($filter_data);

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        foreach ($results as $result) {
            if ($result['quantity'] <= 0) {
                continue;
            }

            if (file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];
            }

            //get qty in cart
            $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));

            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];

            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            $data['products'][] = [
                'key' => $key,
                'qty_in_cart' => $qty_in_cart,
                'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                'store_product_variation_id' => 0,
                'product_id' => $result['product_id'],
                'product_store_id' => $result['product_store_id'],
                'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => $name,
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off, 0),
                'tax' => $result['tax_percentage'],
                'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
            ];
        }

        return $data['products'];
    }

    public function toStoreList() {
        unset($this->session->data['config_store_id']);
        $this->response->redirect($this->url->link('common/home/show_home'));
    }

    public function getHeaderPlace($location) {
        if (isset($_COOKIE['location_name']) && !empty($_COOKIE['location_name'])) {
            $p = $_COOKIE['location_name'];
        } else {
            $p = '';

            $userSearch = explode(',', $location);

            if (count($userSearch) >= 2) {
                $validateLat = is_numeric($userSearch[0]);
                $validateLat2 = is_numeric($userSearch[1]);

                $validateLat3 = strpos($userSearch[0], '.');
                $validateLat4 = strpos($userSearch[1], '.');

                if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                    //echo "<pre>";print_r("er");die;
                    try {
                        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . urlencode($location) . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                        //echo "<pre>";print_r($url);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $headers = [
                            'Cache-Control: no-cache',
                        ];
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
                        if (isset($output)) {
                            foreach ($output->results[0]->address_components as $addres) {
                                if (isset($addres->types)) {
                                    if (in_array('sublocality_level_1', $addres->types)) {
                                        //echo "<pre>";print_r($addres);die;
                                        $p = $addres->long_name;
                                        break;
                                    }
                                }
                            }
                            if (isset($output->results[0]->formatted_address)) {
                                $p = $output->results[0]->formatted_address;
                            }

                            $_COOKIE['location_name'] = $p;
                            setcookie('location_name', $p, time() + (86400 * 30 * 30 * 30 * 3), '/');
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }

        return $p;
    }

    public function featuredproducts() {
        //echo "cer";die;
        //echo "<pre>";
        //print_r($_COOKIE);die;
        if (!isset($this->session->data['customer_id'])) {
            if (isset($_REQUEST['action']) && ('shop' == $_REQUEST['action'])) {
                $this->response->redirect($this->url->link('account/login/customer'));
            } else {
                /* REMOVED FOR HOME PAGE ON DOMINE NAME
                 * $this->response->redirect($this->url->link('common/home/homepage')); */
                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $data['base'] = $server;
                $data['is_login'] = $this->customer->isLogged();
                $data['f_name'] = $this->customer->getFirstName();
                $data['name'] = $this->customer->getFirstName();
                $data['l_name'] = $this->customer->getLastName();
                $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
                $data['home'] = $this->url->link('common/home');
                $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
                $data['logged'] = $this->customer->isLogged();
                $data['account'] = $this->url->link('account/account', '', 'SSL');
                $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

                $data['register'] = $this->url->link('account/register', '', 'SSL');
                $data['login'] = $this->url->link('account/login', '', 'SSL');
                $data['order'] = $this->url->link('account/order', '', 'SSL');
                $data['credit'] = $this->url->link('account/credit', '', 'SSL');
                $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
                $data['pezesha_loans'] = $this->url->link('account/pezeshaloans', '', 'SSL');
                $data['download'] = $this->url->link('account/download', '', 'SSL');
                $data['logout'] = $this->url->link('account/logout', '', 'SSL');
                $data['shopping_cart'] = $this->url->link('checkout/cart');
                $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
                $data['contact'] = $this->url->link('information/contact');
                $data['telephone'] = $this->config->get('config_telephone');
                $data['refer'] = $this->url->link('account/refer', '', 'SSL');
                $data['reward'] = $this->url->link('account/reward', '', 'SSL');
                $data['footer'] = $this->load->controller('common/footer');
                $data['action'] = $this->url->link('common/home/find_store');
                $data['address'] = $this->url->link('account/address', '', 'SSL');
                $data['help'] = $this->url->link('information/help');
                $data['language'] = $this->load->controller('common/language/dropDown');
                $data['login'] = $this->url->link('account/login', '', 'SSL');
                $data['register'] = $this->url->link('account/register', '', 'SSL');
                $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
                $data['store_name'] = $this->config->get('config_name');

                if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
                    //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
                    $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
                } else {
                    $data['logo'] = 'assets/img/logo.svg';
                }

//    echo "<pre>";print_r($data);die;

                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/index.tpl', $data));
            }
        }

        if (isset($this->request->get['store_id'])) {
            $this->session->data['config_store_id'] = $this->request->get['store_id'];
        }

        /* if ( !(count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) || !isset($this->session->data['config_store_id']) ) {

          if(!isset($_COOKIE['location'])) {
          $this->response->redirect( $this->url->link( 'common/home/toHome' ) );
          }
          } */

        $this->load->language('product/store');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_categories.css');

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

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
            unset($this->session->data['config_store_id']);
        } else {
            $store_id = 0;
        }

        $store_info = $this->model_tool_image->getStore($store_id);
        $store_info['store_open_hours'] = $this->model_tool_image->getStoreOpenHours($store_id, date('w'));

        if (!$store_info) {
            unset($this->session->data['config_store_id']);
            $this->response->redirect($this->url->link('common/home'));
        }

        $title = isset($store_info['name']) ? $store_info['name'] : '';
        $data['current_store'] = $store_id;
        $data['heading_title'] = isset($store_info['name']) ? $store_info['name'] : '';
        $data['store_info'] = $store_info;
        //echo '<pre>';print_r($store_info);exit;

        $this->document->setTitle($title);

        $data['text_add_to_list'] = $this->language->get('text_add_to_list');
        $data['text_list_name'] = $this->language->get('text_list_name');
        $data['text_add_to'] = $this->language->get('text_add_to');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_enter_list_name'] = $this->language->get('text_enter_list_name');
        $data['text_create_list'] = $this->language->get('text_create_list');

        $data['location_name'] = '';
        $data['zipcode'] = '';
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $data['zipcode'] = $_COOKIE['zipcode'];
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $data['location_name'] = $this->getHeaderPlace($_COOKIE['location']);
            $data['location_name_full'] = $data['location_name'];

            /* if(isset($_COOKIE['location_name'])) {
              $data['location_name'] = $_COOKIE['location_name'];
              $data['location_name_full'] = $_COOKIE['location_name'];
              } */
        }

        $data['text_refine'] = $this->language->get('text_refine');
        $data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
        $data['text_change_location_name'] = $this->language->get('text_change_location_name');
        $data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');
        $data['text_incart'] = $this->language->get('text_incart');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_offer'] = $this->language->get('text_offer');
        $data['text_deliver'] = $this->language->get('text_deliver');
        $data['text_change_locality'] = $this->language->get('text_change_locality');

        $data['error_no_delivery'] = $this->language->get('error_no_delivery');

        $data['button_change'] = $this->language->get('button_change');
        $data['button_cart'] = $this->language->get('button_cart');

        $data['button_change_locality'] = $this->language->get('button_change_locality');
        $data['button_change_store'] = $this->language->get('button_change_store');

        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_clear_cart'] = $this->language->get('button_clear_cart');
        $data['button_checkout'] = $this->language->get('button_checkout');

        $data['toHome'] = $this->url->link('common/home/toHome');
        $data['toStore'] = $this->url->link('common/home/toStore');

        $data['lists'] = [];

        if (!$this->customer->isLogged()) {
            $data['checkout_link'] = $this->url->link('checkout/checkout');
        } else {
            //get listes
            $data['lists'] = $this->model_assets_category->getUserLists();
            $data['checkout_link'] = $this->url->link('checkout/checkout#collapseTwo');
        }

        //echo "<pre>";print_r($data['lists']);die;
        if (isset($store_info['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($store_info['logo'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
        } else {
            $data['thumb'] = '';
        }

        if (isset($store_info['banner_logo'])) {
            $data['banner_logo'] = $this->model_tool_image->resize($store_info['banner_logo'], 800, 450);
        } else {
            $data['banner_logo'] = $this->model_tool_image->resize('placeholder.png', 800, 450);
        }

        //echo "<pre>";print_r($store_info);die;
        if (isset($store_info['banner_logo_status'])) {
            //echo "<pre>";print_r("Ce");die;
            if (isset($this->session->data['show_banner']) && !$this->session->data['show_banner']) {
                
            } else {
                $this->session->data['show_banner'] = false;
                $data['show_banner'] = true;
            }
        }

        $data['description'] = '';
        //html_entity_decode($store_info['description'], ENT_QUOTES, 'UTF-8');
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

        $data['categories'] = [];

        //$results = $this->model_assets_category->getCategoryByStore(0);
        $results = $this->model_assets_category->getCategoryByStoreId($store_id, 0);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $filter_data = [
                'filter_category_id' => $result['category_id'],
                'filter_sub_category' => true,
                'start' => 0,
                'limit' => 500,
                'store_id' => $store_id,
            ];

            $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));

            $data['categories'][] = [
                'name' => $result['name'],
                'products' => $this->getProducts($filter_data),
                'href' => $this->url->link('product/category', 'category=' . $result['category_id'] . $url),
                'thumb' => $image,
            ];

//            echo "<pre>";print_r($data['categories']);die;
        }

        $template = 'featured_products.tpl';

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['sorts'] = [];

        $data['sorts'][] = [
            'text' => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href' => $this->url->link('product/store', 'sort=p.sort_order&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href' => $this->url->link('product/store', 'sort=pd.name&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href' => $this->url->link('product/store', 'sort=pd.name&order=DESC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href' => $this->url->link('product/store', 'sort=p.price&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href' => $this->url->link('product/store', 'sort=p.price&order=DESC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href' => $this->url->link('product/store', 'sort=p.model&order=ASC' . $url),
        ];

        $data['sorts'][] = [
            'text' => $this->language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href' => $this->url->link('product/store', 'sort=p.model&order=DESC' . $url),
        ];

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

        $data['limits'] = [];

        $limits = array_unique([$this->config->get('config_product_limit'), 25, 50, 75, 100]);

        sort($limits);

        foreach ($limits as $value) {
            $data['limits'][] = [
                'text' => $value,
                'value' => $value,
                'href' => $this->url->link('product/store', $url . '&limit=' . $value),
            ];
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

        $data['account_register'] = $this->load->controller('account/register');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');
        /* add Contact modal */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        $not_delivery = 0;
        /* if($this->cart->hasProducts()){
          $zipcode = $_COOKIE['zipcode'];

          foreach ($this->cart->getStores() as $s ) {
          $checkStore = $this->model_assets_product->getStoreByZip($zipcode,$s);
          if (!$checkStore) {
          $not_delivery = 1;
          break;
          }
          }
          } */

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        $data['offer_show'] = $this->config->get('config_offer_status');

        $data['offer_href'] = $this->url->link('product/offers');

        //$data['offer_products'] = $this->model_assets_product->getOffersByStore($store_id);

        $filter_data = [
            'filter_store_id' => $store_id,
            'start' => 0,
            'limit' => 6,
        ];

        $data['offer_products'] = [
            //'products' => $this->getOfferProducts( $filter_data )
            'products' => $this->getOfferProductsBySpecialPrice($filter_data),
        ];

        //echo "<pre>";print_r($data['offer_products']);die;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['not_delivery'] = $not_delivery;
        $data['change_store'] = $this->url->link('common/home/show_home', '', 'SSL');
        $data['products'] = $this->getMostBoughtProducts();
        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/' . $template)) {
            //mvgv2/template/product/top_category.tpl
            // echo "<pre>";print_r($this->config->get( 'config_template' ) . '/template/product/'.$template);die;
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/' . $template, $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/' . $template, $data));
        }
    }

    public function getMostBoughtProducts() {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $cachePrice_data = $this->cache->get('category_price_data');
        //echo '<pre>';print_r(ACTIVE_STORE_ID);exit;
        $results = $this->model_assets_product->getMostBoughtProducts(ACTIVE_STORE_ID, $this->customer->getId(), null);

        $data['products'] = [];

        //  echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            // if qty less then 1 dont show product
            if ($result['quantity'] <= 0) {
                continue;
            }

            if ($result['image'] != NULL && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else if ($result['image'] == NULL || !file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price

            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                //echo $s_price.'===>'.$o_price.'==>'.$special_price.'===>'.$price;//exit;

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            //get qty in cart
            if (!empty($this->session->data['config_store_id'])) {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));
            } else {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $result['store_id']]));
            }
            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            // Avoid adding duplicates for similar products with different variations

            $productNames = array_column($data['products'], 'name');
            if (false !== array_search($result['name'], $productNames)) {
                // Add variation to existing product
                $productIndex = array_search($result['name'], $productNames);
                // TODO: Check for product variation duplicates
                $data['products'][$productIndex][variations][] = [
                    'variation_id' => $result['product_store_id'],
                    'unit' => $result['unit'],
                    'weight' => floatval($result['weight']),
                    'price' => $price,
                    'special' => $special_price,
                ];
            } else {
                // Add as new product
                $data['products'][] = [
                    'key' => $key,
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'store_id' => $result['store_id'],
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'variations' => [
                        [
                            'variation_id' => $result['product_store_id'],
                            'unit' => $result['unit'],
                            'weight' => floatval($result['weight']),
                            'price' => $price,
                            'special' => $special_price,
                        ],
                    ],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                ];
            }
        }
        //echo "<pre>";print_r($data['products']);die;

        return $data['products'];
    }

}
