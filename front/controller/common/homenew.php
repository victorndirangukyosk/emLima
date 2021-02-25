<?php

class ControllerCommonHomeNew extends Controller {

    public function index() {
        if ($this->customer->isLogged()) {
            $this->load->model('assets/product');
            $this->load->model('tool/image');

            $cachePrice_data = $this->cache->get('category_price_data');
            // echo '<pre>';print_r($cachePrice_data);exit;
            //$results = $this->model_assets_product->getProducts($filter_data);
            $filter_data = array(
                'store_id' => ACTIVE_STORE_ID,
            );
            $results = $this->model_assets_product->getProductsForCategoryPage($filter_data);

            $data['products'] = [];

            // echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
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
                $category_price = 0;

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

                    // echo $s_price.'===>'.$o_price.'==>'.$special_price.'===>'.$price.'</br>';//exit;

                    if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']])) {
                        $cat_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']];
                        $log = new Log('error.log');
                        //$log->write($cat_price);
                        $category_price = $this->currency->format($cat_price);
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
                if (isset($category_price) && isset($o_price) && 0 != $o_price && 0 != $category_price) {
                    $percent_off = (($o_price - $category_price) / $o_price) * 100;
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
                        'category_price' => $category_price,
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
                        'store_id' => $result['store_id'],
                        'variations' => [
                            [
                                'name' => $name,
                                'variation_id' => $result['product_store_id'],
                                'unit' => $result['unit'],
                                'weight' => floatval($result['weight']),
                                'price' => $price,
                                'special' => $special_price,
                                'category_price' => $category_price,
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
            /* echo "<pre>";
              print_r($data['products']);
              die; */
            $data['heading_title'] = 'Category Prices';
            $data['token'] = $this->session->data['token'];
            $data['customer_id'] = $this->customer->getId();
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/onlyHeader');
            $this->response->setOutput($this->load->view('metaorganic/template/account/customer_category_prices.tpl', $data));
        } else {
            $this->response->redirect($this->url->link('account/login/customer'));
        }
    }

}
