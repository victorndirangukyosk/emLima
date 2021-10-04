<?php

class ControllerCheckoutCart extends Controller {

    public function index() {
        if (!$this->request->isAjax()) {
            $this->response->redirect($this->url->link('common/home/toHome'));
        }

        $this->load->language('checkout/cart');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home'),
        ];

        $data['breadcrumbs'][] = [
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('heading_title'),
        ];

        if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
            $data['heading_title'] = $this->language->get('heading_title');
            $data['heading_text'] = $this->language->get('heading_text');

            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');
            $this->document->addScript('front/ui/javascript/checkout_cart.js');

            $data['text_apply_reward_points'] = $this->language->get('text_apply_reward_points');

            $data['entry_reward_points'] = sprintf($this->language->get('entry_reward_points'), '' . $this->customer->getRewardPoints() . '');
            $data['button_add'] = $this->language->get('button_add');

            $data['text_recurring_item'] = $this->language->get('text_recurring_item');
            $data['text_next'] = $this->language->get('text_next');
            $data['text_next_choice'] = $this->language->get('text_next_choice');
            $data['text_cart'] = $this->language->get('text_cart');
            $data['text_signin'] = $this->language->get('text_signin');
            $data['text_place_order'] = $this->language->get('text_place_order');
            $data['text_more'] = $this->language->get('text_more');
            $data['keep_shopping'] = $this->language->get('keep_shopping');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['text_coupon'] = $this->language->get('text_coupon');
            $data['title_availability'] = $this->language->get('title_availability');
            $data['title_price'] = $this->language->get('title_price');
            $data['title_text1'] = $this->language->get('title_text1');
            $data['title_text2'] = $this->language->get('title_text2');
            $data['text_restriction1'] = $this->language->get('text_restriction1');
            $data['text_restriction2'] = $this->language->get('text_restriction2');

            $data['column_image'] = $this->language->get('column_image');
            $data['column_name'] = $this->language->get('column_name');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_subtotal'] = $this->language->get('column_subtotal');
            $data['column_items'] = $this->language->get('column_items');

            $data['column_unit'] = $this->language->get('column_unit');

            $data['is_login'] = $this->customer->isLogged();
            $data['button_update'] = $this->language->get('button_update');
            $data['button_remove'] = $this->language->get('button_remove');
            $data['button_shopping'] = $this->language->get('button_shopping');
            $data['button_checkout'] = $this->language->get('button_checkout');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_place_order'] = $this->language->get('button_place_order');

            if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
                $data['error_warning'] = $this->language->get('error_stock');
            } elseif (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
                $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
            } else {
                $data['attention'] = '';
            }

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            $data['action'] = 'index.php?path=checkout/cart/edit';
            //$this->url->link('checkout/cart/edit');

            if ($this->config->get('config_cart_weight')) {
                $data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            } else {
                $data['weight'] = '';
            }

            $this->load->model('tool/image');
            $this->load->model('tool/upload');

            $data['products'] = [];

            $products = $this->cart->getProducts();

            foreach ($products as $product) {
                $product_total = 0;

                foreach ($products as $product_2) {
                    if ($product_2['product_store_id'] == $product['product_store_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                if ($product['minimum'] > $product_total) {
                    $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                }

                if ($product['image']) {
                    $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
                } else {
                    $image = '';
                }

                $option_data = [];

                foreach ($product['option'] as $option) {
                    if ('file' != $option['type']) {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = [
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                    ];
                }

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
                } else {
                    $total = false;
                }

                $recurring = '';

                if ($product['recurring']) {
                    $frequencies = [
                        'day' => $this->language->get('text_day'),
                        'week' => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month' => $this->language->get('text_month'),
                        'year' => $this->language->get('text_year'),
                    ];

                    if ($product['recurring']['trial']) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }

                    if ($product['recurring']['duration']) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }

                $data['products'][] = [
                    'key' => $product['key'],
                    'product_store_id' => $product['product_store_id'],
                    'thumb' => $image,
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'model' => $product['model'],
                    'product_type' => $product['product_type'],
                    'option' => $option_data,
                    'recurring' => $recurring,
                    'quantity' => $product['quantity'],
                    'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                    'price' => $price,
                    'total' => $total,
                    'href' => $this->url->link('product/product', 'product_store_id=' . $product['product_store_id']),
                ];
            }

            // Gift Voucher
            $data['vouchers'] = [];

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $key => $voucher) {
                    $data['vouchers'][] = [
                        'key' => $key,
                        'description' => $voucher['description'],
                        'amount' => $this->currency->format($voucher['amount']),
                        'remove' => $this->url->link('checkout/cart', 'remove=' . $key),
                    ];
                }
            }

            // Totals
            $this->load->model('extension/extension');

            $total_data = [];
            $total = 0;
            $taxes = $this->cart->getTaxes();

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);
                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                $sort_order = [];

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }

            $data['totals'] = [];

            foreach ($total_data as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value']),
                ];
            }

            $data['continue'] = $this->url->link('common/home');

            $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

            $data['cities'] = $this->model_extension_extension->getCities();

            $order_stores = $this->cart->getStores();
            $min_order_or_not = [];

            foreach ($order_stores as $os) {
                $store_info = $this->model_extension_extension->getStoreAllData($os);

                $store_total = $this->cart->getSubTotal($os);
                $store_city = $store_info['city_id'];
                if ($this->cart->getTotalProductsByStore($os) && $this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
                    $min_order_or_not['store'] = $store_info['name'];
                    $min_order_or_not['amount'] = $this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal();
                    $data['error_warning'] = sprintf($this->language->get('error_minimum'), $min_order_or_not['store'], $this->currency->format($this->config->get('config_active_store_minimum_order_amount')));
                }
            }

            $city = $this->model_extension_extension->getCityById($store_city);

            if ($city) {
                $data['tax_city'] = $city['name'];
            } else {
                $data['tax_city'] = '';
            }

            $this->load->model('extension/extension');

            $data['checkout_buttons'] = [];
            $data['dont_allow'] = isset($min_order_or_not['store']) ? true : false;
            $data['home'] = $this->url->link('common/home');
            $data['coupon'] = $this->load->controller('checkout/coupon');
            $data['voucher'] = $this->load->controller('checkout/voucher');
            $data['reward'] = $this->load->controller('checkout/reward');
            $data['shipping'] = $this->load->controller('checkout/shipping');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/information');

            //echo "<pre>";print_r($data['products']);die;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/cart.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/checkout/cart.tpl', $data));
            }
        } else {
            $data['heading_title'] = $this->language->get('heading_title');

            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

            $data['text_error'] = $this->language->get('text_empty');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            unset($this->session->data['success']);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/information');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function add() {
        $cachePrice_data = $this->cache->get('category_price_data');
        $this->load->language('checkout/cart');
        //echo $this->request->post['quantity'];
        $json = [];

        if (isset($this->request->post['product_id'])) {
            $product_store_id = (int) $this->request->post['product_id'];
        } else {
            $product_store_id = 0;
        }

        if (isset($this->request->post['variation_id'])) {
            $variation_id = (int) $this->request->post['variation_id'];
        } else {
            $variation_id = 0;
        }

        if (isset($this->request->post['product_type'])) {
            $product_type = (int) $this->request->post['product_type'];
        } else {
            $product_type = 'replacable';
        }
        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = $this->request->post['store_id'];
        }
        // if (isset($this->session->data['ripe'])) {
        //     $ripe = $this->session->data['ripe'];
        // } else {
        //     $ripe = $this->request->post['ripe'];
        // }

        $log = new Log('error.log');

        $log->write('PROD notes INFO');

        if (isset($this->session->data['product_note'])) {
            $product_note = $this->session->data['product_note'];
        } else {
            $product_note = $this->request->post['product_note'];
        }

        $log->write($product_note);
        $log->write($product_store_id);

        if (isset($this->session->data['produce_type'])) {
            $produce_type = $this->session->data['produce_type'];
        } else {
            $produce_type = $this->request->post['produce_type'];
        }

        $log->write('END PROD notes INFO');
        /* console.log('ripasdsfdsfe');
          console.log($ripe); */

        $this->load->model('assets/product');

        $product_info = $this->model_assets_product->getProduct($product_store_id, false, $store_id);

        /* $log->write('PROD INFO');
          $log->write($product_info);
          $log->write('PROD INFO'); */

        if ($product_info) {
            if (isset($this->request->post['quantity'])) {
                $quantity = $this->request->post['quantity'];
            } else {
                $quantity = $product_info['min_quantity'] ? $product_info['min_quantity'] : 1;
            }

            if (isset($this->request->post['option'])) {
                $option = array_filter($this->request->post['option']);
            } else {
                $option = [];
            }

            //below model query is required?

            $product_options = $this->model_assets_product->getProductOptions($this->request->post['product_id']);

            foreach ($product_options as $product_option) {
                if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                    $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                }
            }

            if (isset($this->request->post['recurring_id'])) {
                $recurring_id = $this->request->post['recurring_id'];
            } else {
                $recurring_id = 0;
            }

            $recurrings = $this->model_assets_product->getProfiles($product_info['product_store_id']);

            if ($recurrings) {
                $recurring_ids = [];

                foreach ($recurrings as $recurring) {
                    $recurring_ids[] = $recurring['recurring_id'];
                }

                if (!in_array($recurring_id, $recurring_ids)) {
                    $json['error']['recurring'] = $this->language->get('error_recurring_required');
                }
            }

            if (!$json) {
                $json['key'] = $this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id, $store_id, $variation_id, $product_type, $product_note, $produce_type);

                $json['product_store_id'] = $this->request->post['product_id'];

                $json['product_type'] = $product_type;

                $json['product_note'] = $product_note;

                $json['produce_type'] = $produce_type;

                $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_store_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);

                // Totals
                $this->load->model('extension/extension');

                $total_data = [];
                $total = 0;
                //echo $total;exit;
                $taxes = $this->cart->getTaxes();

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $sort_order = [];

                    $results = $this->model_extension_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);
                    //print_r($results);
                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('total/' . $result['code']);

                            $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                            // print_r($result['code']);
                            // print_r($total);
                        }
                    }

                    //
                    //die;
                    $sort_order = [];

                    foreach ($total_data as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $total_data);
                }

                $json['count_products'] = $this->cart->countProducts();
                $json['total_amount'] = $this->currency->format($this->cart->getTotal());
                /* if( CATEGORY_PRICE_ENABLED == true){
                  $json['total_amount'] = $this->currency->format($this->cart->getTotal());
                  } */
                //$json['total_amount'] = $this->currency->format($total);
                $json['total'] = sprintf($this->language->get('text_items'), $json['count_products'] + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
            } else {
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_store_id=' . $this->request->post['product_id']));
            }
        }
        //if(isset($this->session->data['zipcode'])){
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $this->load->model('assets/category');
            $data['notices'] = [];
            //$rows = $this->model_assets_category->getNoticeData($this->session->data['zipcode']);

            $rows = $this->model_assets_category->getNoticeData($_COOKIE['zipcode']);
            foreach ($rows as $row) {
                $data['notices'][] = $row['notice'];
            }
            $p = null;
            foreach ($data['notices'] as $notice) {
                $p .= '<p>' . $notice . '</p>';
            }
            $json['jsnotice'] = $p;
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $p = null;

            /* $addressTmp = $this->getZipcode($_COOKIE['location']);

              $data['zipcode'] = $addressTmp?$addressTmp:''; */

            $this->load->model('assets/category');
            $data['notices'] = [];

            /* $rows = $this->model_assets_category->getNoticeData($data['zipcode']);
              foreach($rows as $row){
              $data['notices'][] = $row['notice'];
              }

              $p = null;
              foreach($data['notices'] as $notice){
              $p .= "<p>".$notice."</p>";
              } */
            $json['jsnotice'] = $p;
        } else {
            $data['zipcode'] = '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateProductType() {
        $this->load->language('checkout/cart');

        $json = [];

        $json['location'] = 'module';

        /// Update
        //echo $this->request->post['quantity'];
        $keys = $this->cart->updateProductType($this->request->post['key'], $this->request->post['product_type']);

        $log = new Log('error.log');
        $log->write('keys');
        $log->write($keys);
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['reward']);

        $json['oldKey'] = $keys['oldKey'];
        $json['newKey'] = $keys['newKey'];
        $json['count_products'] = $this->cart->countProducts();
        $json['total_amount'] = $this->currency->format($this->cart->getTotal());

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //update for single product
    public function update() {
        $this->load->language('checkout/cart');

        $json = [];

        $json['location'] = 'module';

        /// Update

        $ripe = isset($this->request->post['ripe']) && $this->request->post['ripe'] != NULL ? $this->request->post['ripe'] : NULL;
        $product_note = isset($this->request->post['product_note']) && $this->request->post['product_note'] != NULL ? $this->request->post['product_note'] : NULL;
        $produce_type = isset($this->request->post['produce_type']) && $this->request->post['produce_type'] != NULL ? $this->request->post['produce_type'] : NULL;
        /* console.log('ripe');
          console.log($ripe); */

        //echo $this->request->post['ripe'];
        $this->cart->update($this->request->post['key'], $this->request->post['quantity'], $product_note, $produce_type);
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['reward']);

        $json['count_products'] = $this->cart->countProducts();
        $json['total_amount'] = $this->currency->format($this->cart->getTotal());

        $json['product_note'] = $product_note;
        $json['produce_type'] = $produce_type;
        //get product id
        $product = unserialize(base64_decode($this->request->post['key']));

        if (isset($product['product_store_id'])) {
            $json['product_store_id'] = $product['product_store_id'];
            $json['quantity'] = $this->request->post['quantity'];
        }

        if (isset($product['variation_id'])) {
            $json['variation_id'] = $product['variation_id'];
        } else {
            $json['variation_id'] = 0;
        }

        // Validate minimum quantity requirements.
        $products_cart = $this->cart->getProducts();

        $product_total_count = 0;
        $product_total_amount = 0;

        $data['products_details'] = [];
        $this->load->model('tool/image');
        foreach ($products_cart as $product_cart) {
            if ($product_cart['key'] === $this->request->post['key']) {
                $product_total = 0;

                foreach ($products_cart as $product_2) {
                    if ($product_2['product_store_id'] == $product_cart['product_store_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_cart['price'], $product_cart['tax_class_id'], $this->config->get('config_tax')));
                    $price_without_currency_code = $this->tax->calculate($product_cart['price'], $product_cart['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $total = $this->currency->format($this->tax->calculate($product_cart['price'], $product_cart['tax_class_id'], $this->config->get('config_tax')) * $product_cart['quantity']);
                } else {
                    $total = false;
                }
                $product_total_count += $product_cart['quantity'];
                $product_total_amount += $product_cart['total'];

                $data['products_details'] = [
                    'key' => $product_cart['key'],
                    'product_store_id' => $product_cart['product_store_id'],
                    'name' => $product_cart['name'],
                    'product_type' => $product_cart['product_type'],
                    'produce_type' => $product_cart['produce_type'],
                    'product_note' => $product_cart['product_note'],
                    'unit' => $product_cart['unit'],
                    'model' => $product_cart['model'],
                    'quantity' => $product_cart['quantity'],
                    'stock' => $product_cart['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward' => ($product_cart['reward'] ? sprintf($this->language->get('text_points'), $product_cart['reward']) : ''),
                    'price' => $price,
                    'orginal_price' => $this->currency->format(($price_without_currency_code * $product_cart['quantity']) - (($price_without_currency_code - $product_cart['price']) * $product_cart['quantity'])),
                    'tax' => $this->currency->format(($price_without_currency_code - $product_cart['price']) * $product_cart['quantity']),
                    'total' => $total,
                    'store_id' => $product_cart['store_id'],
                ];
            }
        }

        $json['products_details'] = $data['products_details'];
        $log = new Log('error.log');
        $log->write('products_details');
        $log->write($data['products_details']);
        $log->write('products_details');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //update for single product
    public function multiupdate() {
        $log = new Log('error.log');
        $log->write('products_multiupdate');
        $log->write($this->request->post['products']);
        $log->write('products_multiupdate');
        $this->load->language('checkout/cart');

        $json = [];

        $json['location'] = 'module';

        /// Update
        foreach ($this->request->post['products'] as $update_prod) {
            $ripe = isset($update_prod['ripe']) && $update_prod['ripe'] != NULL ? $update_prod['ripe'] : NULL;
            $product_note = isset($update_prod['product_note']) && $update_prod['product_note'] != NULL ? $update_prod['product_note'] : NULL;
            $produce_type = isset($update_prod['produce_type']) && $update_prod['produce_type'] != NULL ? $update_prod['produce_type'] : NULL;
            $product_key = isset($update_prod['key']) && $update_prod['key'] != NULL ? $update_prod['key'] : NULL;
            $product_quantity = isset($update_prod['quantity']) && $update_prod['quantity'] != NULL ? $update_prod['quantity'] : NULL;
            /* console.log('ripe');
              console.log($ripe); */

            //echo $this->request->post['ripe'];
            $this->cart->update($product_key, $product_quantity, $product_note, $produce_type);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);
        }

        $json['count_products'] = $this->cart->countProducts();
        $json['total_amount'] = $this->currency->format($this->cart->getTotal());

        // Validate minimum quantity requirements.
        $products_cart = $this->cart->getProducts();

        $product_total_count = 0;
        $product_total_amount = 0;

        $data['products_details'] = [];
        $this->load->model('tool/image');
        foreach ($products_cart as $product_cart) {
            $product_total = 0;

            foreach ($products_cart as $product_2) {
                if ($product_2['product_store_id'] == $product_cart['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product_cart['price'], $product_cart['tax_class_id'], $this->config->get('config_tax')));
                $price_without_currency_code = $this->tax->calculate($product_cart['price'], $product_cart['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product_cart['price'], $product_cart['tax_class_id'], $this->config->get('config_tax')) * $product_cart['quantity']);
            } else {
                $total = false;
            }
            $product_total_count += $product_cart['quantity'];
            $product_total_amount += $product_cart['total'];

            $data['products_details'][] = [
                'key' => $product_cart['key'],
                'product_store_id' => $product_cart['product_store_id'],
                'name' => $product_cart['name'],
                'product_type' => $product_cart['product_type'],
                'produce_type' => $product_cart['produce_type'],
                'product_note' => $product_cart['product_note'],
                'unit' => $product_cart['unit'],
                'model' => $product_cart['model'],
                'quantity' => $product_cart['quantity'],
                'stock' => $product_cart['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product_cart['reward'] ? sprintf($this->language->get('text_points'), $product_cart['reward']) : ''),
                'price' => $price,
                'orginal_price' => $this->currency->format(($price_without_currency_code * $product_cart['quantity']) - (($price_without_currency_code - $product_cart['price']) * $product_cart['quantity'])),
                'tax' => $this->currency->format(($price_without_currency_code - $product_cart['price']) * $product_cart['quantity']),
                'total' => $total,
                'store_id' => $product_cart['store_id'],
            ];
        }

        $json['products_details'] = $data['products_details'];
        $log = new Log('error.log');
        $log->write('products_details');
        $log->write($data['products_details']);
        $log->write('products_details');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //add new , to add variation and ripe/un ripe
    public function addnew() {
        $this->load->language('checkout/cart');

        $json = [];

        if (isset($this->request->post['product_id'])) {
            $product_store_id = (int) $this->request->post['product_id'];
        } else {
            $product_store_id = 0;
        }

        if (isset($this->request->post['variation_id'])) {
            $variation_id = (int) $this->request->post['variation_id'];
        } else {
            $variation_id = 0;
        }

        if (isset($this->request->post['product_type'])) {
            $product_type = (int) $this->request->post['product_type'];
        } else {
            $product_type = 'replacable';
        }
        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = $this->request->post['store_id'];
        }

        $this->load->model('assets/product');

        $product_info = $this->model_assets_product->getProduct($product_store_id, false, $store_id);

        if ($product_info) {
            if (isset($this->request->post['quantity'])) {
                $quantity = $this->request->post['quantity'];
            } else {
                $quantity = $product_info['min_quantity'] ? $product_info['min_quantity'] : 1;
            }

            if (isset($this->request->post['option'])) {
                $option = array_filter($this->request->post['option']);
            } else {
                $option = [];
            }

            //below model query is required?

            $product_options = $this->model_assets_product->getProductOptions($this->request->post['product_id']);

            foreach ($product_options as $product_option) {
                if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                    $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                }
            }

            if (isset($this->request->post['recurring_id'])) {
                $recurring_id = $this->request->post['recurring_id'];
            } else {
                $recurring_id = 0;
            }

            $recurrings = $this->model_assets_product->getProfiles($product_info['product_store_id']);

            if ($recurrings) {
                $recurring_ids = [];

                foreach ($recurrings as $recurring) {
                    $recurring_ids[] = $recurring['recurring_id'];
                }

                if (!in_array($recurring_id, $recurring_ids)) {
                    $json['error']['recurring'] = $this->language->get('error_recurring_required');
                }
            }

            if (!$json) {
                $json['key'] = $this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id, $store_id, $variation_id, $product_type);

                $json['product_store_id'] = $this->request->post['product_id'];

                $json['product_type'] = $product_type;

                $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_store_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);

                // Totals
                $this->load->model('extension/extension');

                $total_data = [];
                $total = 0;
                $taxes = $this->cart->getTaxes();

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $sort_order = [];

                    $results = $this->model_extension_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);

                    //print_r($results);
                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('total/' . $result['code']);

                            $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                            // print_r($result['code']);
                            // print_r($total);
                        }
                    }

                    //
                    //die;
                    $sort_order = [];

                    foreach ($total_data as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $total_data);
                }

                $json['count_products'] = $this->cart->countProducts();

                $json['total_amount'] = $this->currency->format($this->cart->getTotal());
                //$json['total_amount'] = $this->currency->format($total);
                $json['total'] = sprintf($this->language->get('text_items'), $json['count_products'] + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
            } else {
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_store_id=' . $this->request->post['product_id']));
            }
        }
        //if(isset($this->session->data['zipcode'])){
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $this->load->model('assets/category');
            $data['notices'] = [];
            //$rows = $this->model_assets_category->getNoticeData($this->session->data['zipcode']);

            $rows = $this->model_assets_category->getNoticeData($_COOKIE['zipcode']);
            foreach ($rows as $row) {
                $data['notices'][] = $row['notice'];
            }
            $p = null;
            foreach ($data['notices'] as $notice) {
                $p .= '<p>' . $notice . '</p>';
            }
            $json['jsnotice'] = $p;
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $p = null;

            /* $addressTmp = $this->getZipcode($_COOKIE['location']);

              $data['zipcode'] = $addressTmp?$addressTmp:''; */

            $this->load->model('assets/category');
            $data['notices'] = [];

            /* $rows = $this->model_assets_category->getNoticeData($data['zipcode']);
              foreach($rows as $row){
              $data['notices'][] = $row['notice'];
              }

              $p = null;
              foreach($data['notices'] as $notice){
              $p .= "<p>".$notice."</p>";
              } */
            $json['jsnotice'] = $p;
        } else {
            $data['zipcode'] = '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //update new , to add variation and ripe/un ripe
    public function updatenew() {
        $this->load->language('checkout/cart');

        $json = [];

        $json['location'] = 'module';

        /// Update
        //echo $this->request->post['quantity'];
        $this->cart->update($this->request->post['key'], $this->request->post['quantity']);
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['reward']);

        $json['count_products'] = $this->cart->countProducts();
        $json['total_amount'] = $this->currency->format($this->cart->getTotal());
        //get product id
        $product = unserialize(base64_decode($this->request->post['key']));

        if (isset($product['product_store_id'])) {
            $json['product_store_id'] = $product['product_store_id'];
            $json['quantity'] = $this->request->post['quantity'];
        }

        if (isset($product['variation_id'])) {
            $json['variation_id'] = $product['variation_id'];
        } else {
            $json['variation_id'] = 0;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //mass update from cart
    public function edit() {
        $json = [];

        $checkCart = strpos($_SERVER['HTTP_REFERER'], 'checkout/cart');
        $checkCheckout = strpos($_SERVER['HTTP_REFERER'], 'checkout/checkout');

        if (false !== $checkCart || false !== $checkCheckout) {
            $json['location'] = 'cart-checkout';
        } else {
            $json['location'] = 'module';
        }

        // Update
        if (!empty($this->request->post['quantity'])) {
            foreach ($this->request->post['quantity'] as $key => $value) {
                $this->cart->update($key, $value);
            }
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $json['count_products'] = $this->cart->countProducts();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function remove() {
        $this->load->language('checkout/cart');

        $json = [];

        $checkCart = strpos($_SERVER['HTTP_REFERER'], 'checkout/cart');
        $checkCheckout = strpos($_SERVER['HTTP_REFERER'], 'checkout/checkout');

        if (false !== $checkCart || false !== $checkCheckout) {
            $json['location'] = 'cart-checkout';
        } else {
            $json['location'] = 'module';
        }

        // Remove
        if (isset($this->request->post['key'])) {
            $this->cart->remove($this->request->post['key']);

            $this->cart->removeTempCart($this->request->post['key']);

            unset($this->session->data['vouchers'][$this->request->post['key']]);

            // $this->session->data['success'] = $this->language->get('text_remove');

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            // Totals
            $this->load->model('extension/extension');

            $total_data = [];
            $total = 0;
            $taxes = $this->cart->getTaxes();

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);

                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                $sort_order = [];

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }

            $json['count_products'] = $this->cart->countProducts();
            $json['total_amount'] = $this->currency->format($this->cart->getTotal());

            $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));

            //get product id
            $product = unserialize(base64_decode($this->request->post['key']));

            if (isset($product['variation_id'])) {
                $json['variation_id'] = $product['variation_id'];
            } else {
                $json['variation_id'] = 0;
            }

            if (isset($product['product_store_id'])) {
                $json['product_store_id'] = $product['product_store_id'];
            }
        }

        $json['error'] = '';
        $json['count_products'] = $this->cart->countProducts();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clear_cart() {
        $this->cart->clear();

        unset($this->session->data['coupon']);
        $json['location'] = $this->url->link('account/register');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save_basket() {
        $log = new Log('error.log');
        $log->write('Save List');
        $log->write($this->request->post['list_name']);
        $log->write('Save List');
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            $log->write('PRODUCT');
            $log->write($product);
            $log->write('PRODUCT');
            $this->addProdToWishlist($product['product_id'], $product['quantity'], $this->request->post['list_name']);
        }
        $this->cart->clear();

        unset($this->session->data['coupon']);
        $json['location'] = $this->url->link('account/wishlist');
        $log->write('Wish List URL');
        $log->write($json['location']);
        $log->write('Wish List Url');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addProdToWishlist($product_id, $quantity, $list_name) {
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

        if ($this->customer->isLogged()) {
            $lists = $this->model_assets_category->getUserLists();

            if (is_array($lists) && count($lists) > 0) {
                foreach ($lists as $list) {
                    $this->model_account_wishlist->deleteWishlistProduct($list['wishlist_id'], $this->request->post['listproductId']);
                }
            }

            $wishlist_exists = $this->model_account_wishlist->CheckSaveBasketExits($list_name);
            $log->write('WISHLIST EXISTS WITH NAME');
            $log->write($quantity);
            $log->write($wishlist_exists);
            $log->write('WISHLIST EXISTS WITH NAME');
            if (is_array($wishlist_exists) && count($wishlist_exists) > 0) {
                $wishlist_id = $wishlist_exists['wishlist_id'];
            } else {
                $wishlist_id = $this->model_account_wishlist->createWishlist($list_name);
            }
            $this->model_account_wishlist->addProductToWishlistWithQuantity($wishlist_id, $product_id, $quantity);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function tax_location() {
        if (isset($this->request->post['city_id'])) {
            $city_id = $this->request->post['city_id'];
        } else {
            $city_id = '';
        }

        $query = $this->model_extension_extension->getCityByIdQuery($city_id);

        if ($query->num_rows) {
            $this->session->data['city_id'] = $city_id;
            $this->session->data['shipping_city_id'] = $city_id;
            unset($this->session->data['shipping_address']);
        }
    }

    public function hasStock() {
        $this->load->language('checkout/cart');

        $json = [];
        $stock = true;
        if (!empty($this->request->post['key'])) {
            $key = $this->request->post['key'];
            $quantity = $this->request->post['quantity'];
            $product = unserialize(base64_decode($key));

            $store_id = $product['store_id'];
            $product_store_id = $product['product_store_id'];

            //$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
            $this->db->where('product_to_store.product_store_id', $product_store_id);
            $this->db->where('product_to_store.status', 1);
            $this->db->where('product_to_store.store_id', $store_id);

            $product_query = $this->db->get('product_to_store');

            if ($product_query->num_rows) {
                if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
                    $json['error'] = $this->language->get('error_stock_max');
                    $stock = false;
                } else {
                    $json['error'] = '';
                    $stock = true;
                }
            } else {
                //product disabled
                $json['error'] = $this->language->get('error_stock_max');
                $stock = false;
            }
        }
        $json['stock'] = $stock;
        //echo "<pre>";print_r($product_query);die;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getZipcode($address) {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output1 = json_decode($response);

            //Get latitude and longitute from json data
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output2 = json_decode($response);

            if (!empty($output2)) {
                $addressComponents = $output2->results[0]->address_components;
                foreach ($addressComponents as $addrComp) {
                    if ('postal_code' == $addrComp->types[0]) {
                        //Return the zipcode
                        return $addrComp->long_name;
                    }
                }

                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPlace($location) {
        $p = '';

        $userSearch = explode(',', $location);

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $location . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                //echo "<pre>";print_r($url);die;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $headers = [
                    'Cache-Control: no-cache',
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

                $response = curl_exec($ch);

                //echo "<pre>";print_r($response);die;

                curl_close($ch);
                $output = json_decode($response);

                //print_r($output);die;

                if (isset($output)) {
                    $p = $output->results[0]->formatted_address;
                }
            }
        }

        return $p;
    }

    public function removeothervendorproductsfromcart() {
        $json = [];
        $json['products_removed'] = FALSE;
        $log = new Log('error.log');
        $log->write($this->cart->countProducts());
        $previous_count = $this->cart->countProducts();
        foreach ($this->cart->getProducts() as $store_products) {
            /* FOR KWIKBASKET ORDERS */
            if ($store_products['store_id'] > 75) {
                $log->write('CheckOtherVendorOrderExists');
                $log->write($store_products['key']);
                $this->cart->remove($store_products['key']);
                $log->write('CheckOtherVendorOrderExists');
            }
        }
        $log->write($this->cart->countProducts());
        $present_count = $this->cart->countProducts();
        if ($previous_count > $present_count) {
            $json['products_removed'] = TRUE;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addcartdb($key) {
        $log = new Log('error.log');
        $this->load->model('account/customer');
        foreach ($this->cart->getProducts() as $cart_product) {
            if ($cart_product['key'] == $key) {
                $option = NULL;
                if (is_array($cart_product['option']) && count($cart_product['option']) > 0) {
                    $option = implode("-", $cart_product['option']);
                }
                $log->write('quantity');
                $log->write($cart_product['quantity']);
                $log->write('quantity');
                $this->model_account_customer->AddToCart($cart_product['product_store_id'], $cart_product['quantity'], $option, $cart_product['recurring'], $cart_product['store_id'], $cart_product['store_product_variation_id'], $cart_product['product_type'], $cart_product['product_note'], $cart_product['produce_type'], $cart_product['product_id']);
            }
        }
    }

    public function clearcartdb() {
        $log = new Log('error.log');
        $this->load->model('account/customer');
        $this->model_account_customer->ClearCart();
    }

}
