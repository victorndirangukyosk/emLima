<?php

class ControllerProductProduct extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('product/product');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $this->load->model('assets/category');
        $this->load->model('tool/image');

        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = 0;
        }
        $store_info = $this->model_tool_image->getStore($store_id);

        if (!$store_info) {
            unset($this->session->data['config_store_id']);
            $this->response->redirect($this->url->link('common/home'));
        }

        if (isset($this->request->get['category'])) {
            $path = '';

            $parts = explode('_', (string) $this->request->get['category']);

            $category_id = (int) array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = $path_id;
                } else {
                    $path .= '_'.$path_id;
                }

                $category_info = $this->model_assets_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = [
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'category='.$path),
                    ];
                }
            }

            // Set the last category breadcrumb
            $category_info = $this->model_assets_category->getCategory($category_id);

            if ($category_info) {
                $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort='.$this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order='.$this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page='.$this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit='.$this->request->get['limit'];
                }

                $data['breadcrumbs'][] = [
                    'text' => $category_info['name'],
                    'href' => $this->url->link('product/category', 'category='.$this->request->get['category'].$url),
                ];
            }
        }

        $this->load->model('assets/manufacturer');

        if (isset($this->request->get['manufacturer_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_brand'),
                'href' => $this->url->link('product/manufacturer'),
            ];

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $manufacturer_info = $this->model_assets_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

            if ($manufacturer_info) {
                $data['breadcrumbs'][] = [
                    'text' => $manufacturer_info['name'],
                    'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id='.$this->request->get['manufacturer_id'].$url),
                ];
            }
        }

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search='.$this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag='.$this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description='.$this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id='.$this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category='.$this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_search'),
                'href' => $this->url->link('product/search', $url),
            ];
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('assets/product');

        $product_info = $this->model_assets_product->getProduct($product_id);

        if ($product_info) {
            $url = '';

            if (isset($this->request->get['category'])) {
                $url .= '&category='.$this->request->get['category'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter='.$this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id='.$this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search='.$this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag='.$this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description='.$this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id='.$this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category='.$this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $data['breadcrumbs'][] = [
                'text' => $product_info['name'],
                'href' => $this->url->link('product/product', $url.'&product_id='.$this->request->get['product_id']),
            ];

            $title = empty($product_info['meta_title']) ? $product_info['name'] : $product_info['meta_title'];

            $this->document->setTitle($title);
            $this->document->setDescription($product_info['meta_description']);
            $this->document->setKeywords($product_info['meta_keyword']);
            if (!$this->config->get('config_seo_url')) {
                $this->document->addLink($this->url->link('product/product', 'product_id='.$this->request->get['product_id']), 'canonical');
            }
            $this->document->addScript('front/ui/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('front/ui/javascript/jquery/magnific/magnific-popup.css');
            $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
            $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

            $data['heading_title'] = $product_info['name'];

            $data['text_select'] = $this->language->get('text_select');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_reward'] = $this->language->get('text_reward');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_stock'] = $this->language->get('text_stock');
            $data['text_discount'] = $this->language->get('text_discount');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_option'] = $this->language->get('text_option');
            $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
            $data['text_write'] = $this->language->get('text_write');
            $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
            $data['text_note'] = $this->language->get('text_note');
            $data['text_tags'] = $this->language->get('text_tags');
            $data['text_related'] = $this->language->get('text_related');
            $data['text_loading'] = $this->language->get('text_loading');

            $data['entry_qty'] = $this->language->get('entry_qty');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_review'] = $this->language->get('entry_review');
            $data['entry_rating'] = $this->language->get('entry_rating');
            $data['entry_good'] = $this->language->get('entry_good');
            $data['entry_bad'] = $this->language->get('entry_bad');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_quantity_plus'] = $this->language->get('button_quantity_plus');
            $data['button_quantity_minus'] = $this->language->get('button_quantity_minus');
            $data['button_upload'] = $this->language->get('button_upload');
            $data['button_continue'] = $this->language->get('button_continue');

            $this->load->model('assets/review');

            $data['tab_description'] = $this->language->get('tab_description');
            $data['tab_attribute'] = $this->language->get('tab_attribute');
            $data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

            $data['product_id'] = (int) $this->request->get['product_id'];
            $data['manufacturer'] = $product_info['manufacturer'];
            $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id='.$product_info['manufacturer_id']);
            $data['model'] = $product_info['model'];
            $data['reward'] = $product_info['reward'];
            $data['points'] = $product_info['points'];

            if ($product_info['quantity'] <= 0) {
                $data['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $data['stock'] = $product_info['quantity'];
            } else {
                $data['stock'] = $this->language->get('text_instock');
            }

            $this->load->model('tool/image');

            if ($product_info['image']) {
                $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            } else {
                $data['popup'] = '';
            }

            if ($product_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $data['thumb'] = '';
            }

            $data['images'] = [];

            $results = $this->model_assets_product->getProductImages($this->request->get['product_id']);

            foreach ($results as $result) {
                $data['images'][] = [
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
                ];
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['price'] = false;
            }

            if ((float) $product_info['special']) {
                $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price']);
            } else {
                $data['tax'] = false;
            }

            $discounts = $this->model_assets_product->getProductDiscounts($this->request->get['product_id']);

            $data['discounts'] = [];

            foreach ($discounts as $discount) {
                $data['discounts'][] = [
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))),
                ];
            }

            $data['options'] = [];

            foreach ($this->model_assets_product->getProductOptions($this->request->get['product_id']) as $option) {
                $product_option_value_data = [];

                foreach ($option['product_option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
                        } else {
                            $price = false;
                        }

                        $product_option_value_data[] = [
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price' => $price,
                            'price_prefix' => $option_value['price_prefix'],
                        ];
                    }
                }

                $data['options'][] = [
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required'],
                ];
            }

            if ($product_info['minimum']) {
                $data['minimum'] = $product_info['minimum'];
            } else {
                $data['minimum'] = 1;
            }

            $data['review_status'] = $this->config->get('config_review_status');

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $data['review_guest'] = true;
            } else {
                $data['review_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $data['customer_name'] = $this->customer->getFirstName().'&nbsp;'.$this->customer->getLastName();
            } else {
                $data['customer_name'] = '';
            }

            $data['reviews'] = sprintf($this->language->get('text_reviews'), (int) $product_info['reviews']);
            $data['rating'] = (int) $product_info['rating'];
            $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $data['attribute_groups'] = $this->model_assets_product->getProductAttributes($this->request->get['product_id']);

            $data['products'] = [];

            $results = $this->model_assets_product->getProductRelated($this->request->get['product_id']);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float) $result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int) $result['rating'];
                } else {
                    $rating = false;
                }

                //get qty in cart
                $key = base64_encode(serialize(['product_id' => (int) $result['product_id'], 'store_id' => ($this->session->data['config_store_id']) ? $this->session->data['config_store_id'] : ACTIVE_STORE_ID]));

                if (isset($this->session->data['cart'][$key])) {
                    $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
                } else {
                    $qty_in_cart = 0;
                }

                $data['products'][] = [
                    'product_id' => $result['product_id'],
                    'qty_in_cart' => $qty_in_cart,
                    'default_variation_name' => $result['default_variation_name'],
                    'variations' => $this->model_assets_product->getVariations($result['product_id']),
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')).'..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $rating,
                    'href' => $this->url->link('product/product', 'product_id='.$result['product_id']),
                ];
            }

            $data['tags'] = [];

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = [
                        'tag' => trim($tag),
                        'href' => $this->url->link('product/search', 'tag='.trim($tag)),
                    ];
                }
            }

            $data['telephone_mask'] = $this->config->get('config_telephone_mask');

            if (isset($data['telephone_mask'])) {
                $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
            }

            $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

            if (isset($data['taxnumber_mask'])) {
                $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
            }

            $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
            $data['recurrings'] = $this->model_assets_product->getProfiles($this->request->get['product_id']);

            $this->model_assets_product->updateViewed($this->request->get['product_id']);

            if ($this->config->get('config_google_captcha_status')) {
                $this->document->addScript('https://www.google.com/recaptcha/api.js');

                $data['site_key'] = $this->config->get('config_google_captcha_public');
            } else {
                $data['site_key'] = '';
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/product.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/product.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/product/product.tpl', $data));
            }
        } else {
            $url = '';

            if (isset($this->request->get['category'])) {
                $url .= '&category='.$this->request->get['category'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter='.$this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id='.$this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search='.$this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag='.$this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description='.$this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id='.$this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category='.$this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/product', $url.'&product_id='.$product_id),
            ];

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'].' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function review()
    {
        $this->load->language('product/product');

        $this->load->model('assets/review');

        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = [];

        $review_total = $this->model_assets_review->getTotalReviewsByProductId($this->request->get['product_id']);

        $results = $this->model_assets_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['reviews'][] = [
                'author' => $result['author'],
                'text' => nl2br($result['text']),
                'rating' => (int) $result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = $this->url->link('product/product/review', 'product_id='.$this->request->get['product_id'].'&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/review.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/review.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
        }
    }

    public function write()
    {
        $this->load->language('product/product');

        $json = [];

        if ('POST' == $this->request->server['REQUEST_METHOD']) {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
                $json['error'] = $this->language->get('error_rating');
            }

            if ($this->config->get('config_google_captcha_status') && empty($json['error'])) {
                if (isset($this->request->post['g-recaptcha-response'])) {
                    $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($this->config->get('config_google_captcha_secret')).'&response='.$this->request->post['g-recaptcha-response'].'&remoteip='.$this->request->server['REMOTE_ADDR']);
                    $recaptcha = json_decode($recaptcha, true);

                    if (!$recaptcha['success']) {
                        $json['error'] = $this->language->get('error_captcha');
                    }
                } else {
                    $json['error'] = $this->language->get('error_captcha');
                }
            }

            if (!isset($json['error'])) {
                $this->load->model('assets/review');

                $this->model_assets_review->addReview($this->request->get['product_id'], $this->request->post);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getRecurringDescription()
    {
        $this->language->load('product/product');
        $this->load->model('assets/product');

        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        if (isset($this->request->post['recurring_id'])) {
            $recurring_id = $this->request->post['recurring_id'];
        } else {
            $recurring_id = 0;
        }

        if (isset($this->request->post['quantity'])) {
            $quantity = $this->request->post['quantity'];
        } else {
            $quantity = 1;
        }

        $product_info = $this->model_assets_product->getProduct($product_id);
        $recurring_info = $this->model_assets_product->getProfile($product_id, $recurring_id);

        $json = [];

        if ($product_info && $recurring_info) {
            if (!$json) {
                $frequencies = [
                    'day' => $this->language->get('text_day'),
                    'week' => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month' => $this->language->get('text_month'),
                    'year' => $this->language->get('text_year'),
                ];

                if (1 == $recurring_info['trial_status']) {
                    $price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
                    $trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']).' ';
                } else {
                    $trial_text = '';
                }

                $price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

                if ($recurring_info['duration']) {
                    $text = $trial_text.sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
                } else {
                    $text = $trial_text.sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
                }

                $json['success'] = $text;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function view()
    {
        //echo "<pre>";print_r("erv");die;
        // unset($this->session->data['temp_cart']);
        // echo "<pre>";print_r("ss");die;
        //$data['product']['store_product_variation_id'] = 0;
        $this->load->language('product/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['product_store_id'])) {
            $product_store_id = $this->request->get['product_store_id'];
        } else {
            $product_store_id = 0;
        }

        if ($this->session->data['config_store_id']) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = $this->request->get['store_id'];
        }
        $data['button_add'] = $this->language->get('button_add');
        $data['text_incart'] = $this->language->get('text_incart');
        //recipe
        $this->load->model('assets/product');

        $product_info = $this->model_assets_product->getProductForPopup($product_store_id, false, $store_id);
        /*$log = new Log('error.log');
        $log->write($product_info);*/

        //echo "<pre>";print_r($product_info);die;
        $data['text_unit'] = $this->language->get('text_unit');

        $data['text_product_highlights'] = $this->language->get('text_product_highlights');

        $data['text_description'] = $this->language->get('text_description');

        $data['text_no_description'] = $this->language->get('text_no_description');

        $data['text_add_to_list'] = $this->language->get('text_add_to_list');

        $data['text_features'] = $this->language->get('text_features');

        $data['text_disclaimer'] = $this->language->get('text_disclaimer');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        //echo "<pre>";print_r($product_info);die;

        if ($product_info) {
            if ($product_info['image'] != NULL && file_exists(DIR_IMAGE.$product_info['image'])) {
                $thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else if($product_info['image'] == NULL || !file_exists(DIR_IMAGE.$product_info['image'])) {
                $thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            }

            if ($product_info['image'] != NULL && file_exists(DIR_IMAGE.$product_info['image'])) {
                $zoom_thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height'));
            } else if($product_info['image'] == NULL || !file_exists(DIR_IMAGE.$product_info['image'])) {
                $zoom_thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height'));
            }

            $data['images'] = [];

            $results = $this->model_assets_product->getProductImages($product_info['product_id']);
            $this->load->model('account/wishlist');

            $isWishListID = $this->model_account_wishlist->getWishlistIDCustomerProduct($product_info['product_id']);

            foreach ($results as $result) {
                $data['images'][] = [
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'zoom_popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height')),
                ];
            }
            //get qty in cart
            //$key = base64_encode( serialize( array( 'product_store_id' => (int) $product_info['product_store_id'], 'store_id'=>$this->session->data['config_store_id'] ) ) );
            // $key = base64_encode( serialize( array( 'product_store_id' => (int) $product_info['product_store_id'], 'store_id'=>($this->session->data['config_store_id']) ? $this->session->data['config_store_id'] : $store_id ) ) );

            $key = base64_encode(serialize(['product_store_id' => (int) $product_info['product_store_id'], 'store_id' => $store_id]));
            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['price'] = false;
                }
                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['special_price'] = false;
                }
            } else {
                $s_price = $product_info['special_price'];
                $o_price = $product_info['price'];

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($product_info['price']);
                } else {
                    $product_info['price'] = $product_info['price'];
                }

                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                } else {
                    $product_info['special_price'] = $product_info['special_price'];
                }
            }

            $cachePrice_data = $this->cache->get('category_price_data');
            //echo $product_info['product_store_id'].'====>'.$_SESSION['customer_category'].'===>'.$store_id;
            //echo '<pre>';print_r($cachePrice_data);
            //exit;
            if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id])) {
                //echo $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];//exit;
                $s_price = $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];
                $o_price = $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];
                $product_info['special_price'] = $this->currency->format($s_price);
                $product_info['price'] = $this->currency->format($o_price);
            }
            //echo '<pre>';print_r($product_info);exit;

            if (isset($product_info['pd_name'])) {
                $product_info['name'] = $product_info['pd_name'];
            }

            /*echo $o_price;
            echo "ce";
            echo $s_price;*/
            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (null != $product_info['produce_type'] && '' != $product_info['produce_type']) {
                $producetypes = explode(',', $product_info['produce_type']);
                $producetypesupdated;
                $i = 0;
                foreach ($producetypes as $pt) {
                    $producetypesupdated[$i]['type'] = $pt;
                    $producetypesupdated[$i]['value'] = 0;

                    foreach ($this->session->data['cart'][$key]['produce_type'] as $type) {
                        if ($type['type'] == $pt) {
                            $producetypesupdated[$i]['value'] = $type['value'];
                        }
                    }
                    ++$i;
                }
            }

            $data['product'] = [
                'thumb' => $thumb,
                'zoom_thumb' => $zoom_thumb,
                'key' => $key,
                'product_store_id' => $product_info['product_store_id'],
                'store_id' => $product_info['store_id'],
                'store_product_variation_id' => 0,
                'images' => $data['images'],
                'product_info' => $product_info,
                'percent_off' => number_format($percent_off, 0),
                'popup' => true,
                'actualCart' => 0,
                'default_variation_name' => $product_info['default_variation_name'],
                'variations' => $this->model_assets_product->getVariations($product_info['product_store_id']),
                'produce_type' => (isset($product_info['produce_type']) && ('' != $product_info['produce_type'])) ? $producetypesupdated : null,

                'minimum' => $product_info['min_quantity'] > 0 ? $product_info['min_quantity'] : $product_info['quantity'],
                // 'variations' => array(
                // 	array(
                // 		'variation_id' => $product_info['product_store_id'],
                // 		'unit' => $product_info['unit'],
                // 		'weight' => floatval($product_info['weight']),
                // 		'price' => $price,
                // 		'special' => $special_price
                // 	)
                // ),
                'variations' => $this->model_assets_product->getProductVariationsNew($product_info['name'], $store_id),
                'isWishListID' => $isWishListID,
            ];
            
            $log = new Log('error.log');
            $log->write('product popup');
            $log->write($data['product']);
            $log->write('product popup');
            //echo '<pre>';print_r( $data['product']);exit;
            if (isset($this->session->data['cart'][$key])) {
                $data['product']['qty_in_cart'] = $this->session->data['cart'][$key]['quantity'];
                $data['product']['actualCart'] = 1;
                $data['product']['product_note'] = $this->session->data['cart'][$key]['product_note'];
            } else {
                $data['product']['qty_in_cart'] = 0;
                if (isset($this->session->data['temp_cart'][$key])) {
                    $data['product']['qty_in_cart'] = $this->session->data['temp_cart'][$key]['quantity'];
                    $data['product']['product_note'] = $this->session->data['temp_cart'][$key]['product_note'];
                }
            }
            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/product_popup.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/product_popup.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/product/product_popup.tpl', $data));
            }
        } else {
            return false;
        }
    }
    
    public function order_edit_view()
    {
        //echo "<pre>";print_r("erv");die;
        // unset($this->session->data['temp_cart']);
        // echo "<pre>";print_r("ss");die;
        //$data['product']['store_product_variation_id'] = 0;
        $this->load->language('product/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['product_store_id'])) {
            $product_store_id = $this->request->get['product_store_id'];
        } else {
            $product_store_id = 0;
        }
        
        if (isset($this->request->get['edit_order_id'])) {
            $edit_order_id = $this->request->get['edit_order_id'];
        } else {
            $edit_order_id = 0;
        }

        if ($this->session->data['config_store_id']) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = $this->request->get['store_id'];
        }
        $data['button_add'] = $this->language->get('button_add');
        $data['text_incart'] = $this->language->get('text_incart');
        //recipe
        $this->load->model('assets/product');
        $this->load->model('account/order');

        $product_info = $this->model_assets_product->getProductForPopup($product_store_id, false, $store_id);
        $order_product = $this->model_account_order->getOrderProductsByProductId($edit_order_id, $product_store_id);

        // echo "<pre>";print_r($order_product);die;
        $data['text_unit'] = $this->language->get('text_unit');

        $data['text_product_highlights'] = $this->language->get('text_product_highlights');

        $data['text_description'] = $this->language->get('text_description');

        $data['text_no_description'] = $this->language->get('text_no_description');

        $data['text_add_to_list'] = $this->language->get('text_add_to_list');

        $data['text_features'] = $this->language->get('text_features');

        $data['text_disclaimer'] = $this->language->get('text_disclaimer');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        //echo "<pre>";print_r($product_info);die;

        if ($product_info) {
            if ($product_info['image'] != NULL && file_exists(DIR_IMAGE.$product_info['image'])) {
                $thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else if($product_info['image'] == NULL || !file_exists(DIR_IMAGE.$product_info['image'])) {
                $thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            }

            if ($product_info['image'] != NULL && file_exists(DIR_IMAGE.$product_info['image'])) {
                $zoom_thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height'));
            } else if($product_info['image'] == NULL || !file_exists(DIR_IMAGE.$product_info['image'])) {
                $zoom_thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height'));
            }

            $data['images'] = [];

            $results = $this->model_assets_product->getProductImages($product_info['product_id']);
            $this->load->model('account/wishlist');

            $isWishListID = $this->model_account_wishlist->getWishlistIDCustomerProduct($product_info['product_id']);

            foreach ($results as $result) {
                $data['images'][] = [
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'zoom_popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_zoomimage_thumb_width'), $this->config->get('config_zoomimage_thumb_height')),
                ];
            }
            //get qty in cart
            //$key = base64_encode( serialize( array( 'product_store_id' => (int) $product_info['product_store_id'], 'store_id'=>$this->session->data['config_store_id'] ) ) );
            // $key = base64_encode( serialize( array( 'product_store_id' => (int) $product_info['product_store_id'], 'store_id'=>($this->session->data['config_store_id']) ? $this->session->data['config_store_id'] : $store_id ) ) );

            $key = base64_encode(serialize(['product_store_id' => (int) $product_info['product_store_id'], 'store_id' => $store_id]));
            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['price'] = false;
                }
                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['special_price'] = false;
                }
            } else {
                $s_price = $product_info['special_price'];
                $o_price = $product_info['price'];

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($product_info['price']);
                } else {
                    $product_info['price'] = $product_info['price'];
                }

                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                } else {
                    $product_info['special_price'] = $product_info['special_price'];
                }
            }

            $cachePrice_data = $this->cache->get('category_price_data');
            //echo $product_info['product_store_id'].'====>'.$_SESSION['customer_category'].'===>'.$store_id;
            //echo '<pre>';print_r($cachePrice_data);
            //exit;
            if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id])) {
                //echo $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];//exit;
                $s_price = $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];
                $o_price = $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];
                $product_info['special_price'] = $this->currency->format($s_price);
                $product_info['price'] = $this->currency->format($o_price);
            }
            //echo '<pre>';print_r($product_info);exit;

            if (isset($product_info['pd_name'])) {
                $product_info['name'] = $product_info['pd_name'];
            }

            /*echo $o_price;
            echo "ce";
            echo $s_price;*/
            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (null != $product_info['produce_type'] && '' != $product_info['produce_type']) {
                $producetypes = explode(',', $product_info['produce_type']);
                $producetypesupdated;
                $i = 0;
                foreach ($producetypes as $pt) {
                    $producetypesupdated[$i]['type'] = $pt;
                    $producetypesupdated[$i]['value'] = 0;

                    /*foreach ($this->session->data['cart'][$key]['produce_type'] as $type) {
                        if ($type['type'] == $pt) {
                            $producetypesupdated[$i]['value'] = $type['value'];
                        }
                    }*/
                    ++$i;
                }
            }

            $data['product'] = [
                'thumb' => $thumb,
                'zoom_thumb' => $zoom_thumb,
                'key' => $key,
                'product_store_id' => $product_info['product_store_id'],
                'store_id' => $product_info['store_id'],
                'store_product_variation_id' => 0,
                'images' => $data['images'],
                'product_info' => $product_info,
                'percent_off' => number_format($percent_off, 0),
                'popup' => true,
                'actualCart' => 0,
                'default_variation_name' => $product_info['default_variation_name'],
                'variations' => $this->model_assets_product->getVariations($product_info['product_store_id']),
                'produce_type' => (isset($product_info['produce_type']) && ('' != $product_info['produce_type'])) ? $producetypesupdated : null,

                'minimum' => $product_info['min_quantity'] > 0 ? $product_info['min_quantity'] : $product_info['quantity'],
                // 'variations' => array(
                // 	array(
                // 		'variation_id' => $product_info['product_store_id'],
                // 		'unit' => $product_info['unit'],
                // 		'weight' => floatval($product_info['weight']),
                // 		'price' => $price,
                // 		'special' => $special_price
                // 	)
                // ),
                'variations' => $this->model_assets_product->getEditOrderProductVariationsNew($product_info['name'], $store_id, '', $edit_order_id),
                'isWishListID' => $isWishListID,
            ];
            
            $log = new Log('error.log');
            /*$log->write('product popup');
            $log->write($data['product']);
            $log->write('product popup');*/
            // echo '<pre>';print_r( $data);exit;
            if (isset($order_product) && array_key_exists('quantity', $order_product) && $order_product['quantity'] > 0) {
                $data['product']['qty_in_cart'] = $order_product['quantity'];
                $data['product']['unitvarient'] = $order_product['unit'];
                $data['product']['actualCart'] = 1;
                $data['product']['product_note'] = $order_product['product_note'];
            } else {
                $data['product']['qty_in_cart'] = 0;
                $data['product']['product_note'] = '';
                /*if (isset($this->session->data['temp_cart'][$key])) {
                    $data['product']['qty_in_cart'] = $this->session->data['temp_cart'][$key]['quantity'];
                    $data['product']['product_note'] = $this->session->data['temp_cart'][$key]['product_note'];
                }*/
            }
            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/order_edit_product_popup.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/order_edit_product_popup.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/product/order_edit_product_popup.tpl', $data));
            }
        } else {
            return false;
        }
    }
}
