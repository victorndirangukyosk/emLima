<?php

class ControllerApiCustomerCart extends Controller {

    private $config;
    private $db;
    private $data = [];

    // added getCart fn

    public function getCart() {
        echo 'getCart';

        $totalQuantity = 0;
        $log = new Log('error.log');
        $log->write('cwen');
        //$log->write($product_query);

        if (!$this->data) {
            $log->write('pro 1');

            echo 'if';

            foreach ($this->session->data['cart'] as $keys => $data) {
                $product = unserialize(base64_decode($keys));

                $product_store_id = $product['product_store_id'];

                $stock = true;

                // Options
                if (!empty($product['option'])) {
                    $options = $product['option'];
                } else {
                    $options = [];
                }

                // Profile
                if (!empty($product['recurring_id'])) {
                    $recurring_id = $product['recurring_id'];
                } else {
                    $recurring_id = 0;
                }

                if (!empty($data['product_type'])) {
                    $product_type = $data['product_type'];
                } else {
                    $product_type = 'replacable';
                }

                //$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)    WHERE p.product_id = '" . (int) $product_id . "' AND p.status = '1'");

                $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
                // $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
                $this->db->where('product.status', 1);
                $this->db->where('product_to_store.product_store_id', $product_store_id);

                $product_query = $this->db->get('product_to_store');

                $log->write('pro 2');
                //$log->write($product_query);

                if ($product_query->num_rows) {
                    //override if cateogry discount defined
                    // $sql  = 'select c.discount from `'.DB_PREFIX.'product_to_category` pc';
                    // $sql .= ' INNER JOIN `'.DB_PREFIX.'category` c on c.category_id = pc.category_id';
                    // $sql .= ' WHERE pc.product_id = "'.$product_id.'"';
                    // $sql .= ' GROUP BY pc.category_id';
                    // $rows = $this->db->query($sql)->rows;
                    // Stock
                    if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $data['quantity'])) {
                        $stock = false;
                    }

                    //override for variation

                    if (!empty($product['store_product_variation_id'])) {
                        //$row = $this->db->query('select * from `'.DB_PREFIX.'product_variation` WHERE id="'.$product['variation_id'].'"')->row;

                        $this->db->join('product', 'product.product_id = variation_to_product_store.variation_id', 'left');
                        $this->db->where('product_variation_store_id', $product['store_product_variation_id'], false);
                        $row = $this->db->get('variation_to_product_store')->row;

                        //override price, image
                        if ($row['special_price']) {
                            $price = $row['special_price'];
                            $special_price = $price;
                        } else {
                            $price = $row['price'];
                            $special_price = null;
                        }

                        $image = $row['image'];
                        $store_product_variation_id = $product['store_product_variation_id'];

                        //old below
                        //$variation = ' - '.$row['name'];

                        $variation = ' - ' . $row['unit'];
                    } else {
                        $price = $product_query->row['price'];
                        $special_price = null;
                        // Product Specials
                        //print_r($product_query);
                        if ($product_query->row['special_price'] > 0) {
                            $price = $product_query->row['special_price'];
                        }

                        $store_product_variation_id = 0;
                        $variation = $product_query->row['unit'] ? ' - ' . $product_query->row['unit'] : '';
                        $image = $product_query->row['image'];
                    }

                    $this->data[$keys] = [
                        'key' => $keys,
                        'product_store_id' => $product_query->row['product_store_id'],
                        'store_product_variation_id' => $store_product_variation_id,
                        'store_id' => $product['store_id'],
                        'product_type' => $product_type,
                        'name' => $product_query->row['name'] . $variation,
                        'model' => $product_query->row['model'],
                        'shipping' => 0,
                        'image' => $image,
                        'option' => [],
                        'download' => [],
                        'quantity' => $data['quantity'],
                        'minimum' => $product_query->row['min_quantity'],
                        'subtract' => $product_query->row['subtract_quantity'],
                        'stock' => $stock,
                        'price' => $price,
                        'special_price' => $special_price,
                        'total' => $price * $data['quantity'],
                        'reward' => 0,
                        'points' => 0,
                        'tax_class_id' => $product_query->row['tax_class_id'],
                        'weight' => 0,
                        'weight_class_id' => 0,
                        'length' => 0,
                        'width' => 0,
                        'height' => 0,
                        'length_class_id' => 0,
                        'recurring' => false,
                    ];
                } else {
                    $this->remove($keys);
                }
            }
        }
        //$log->write($this->data);
        $res['products'] = $this->data;
        $res['quantity'] = $totalQuantity;

        return $res;
    }

    public function addCart() { //add to addCart
        echo 'cart/add';

        $this->load->language('api/cart');
        $json = [];
        if (!isset($this->session->data['api_id'])) {
            $json['error']['warning'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->post['product'])) {
                $this->cart->clear();
                foreach ($this->request->post['product'] as $product) {
                    $option = [];
                    $this->cart->add($product['product_store_id'], $product['quantity'], $option, false, $product['store_id'], $product['store_product_variation_id']);
                    if ($product['store_id']) {
                        $this->session->data['config_store_id'] = $product['store_id'];
                    }
                }
            }
            if (isset($this->request->post['product_store_id']) && isset($this->session->data['config_store_id'])) {
                if ($this->session->data['config_store_id'] != $this->request->post['store_id']) {
                    $json['error']['warning'] = $this->language->get('error_allow_one_store');
                }
            }
            if (isset($this->request->post['product_store_id']) && !isset($json['error']['warning'])) {
                $this->load->model('assets/product');
                if (isset($this->request->post['store_id'])) {
                    $store_id = $this->request->post['store_id'];
                    $this->session->data['config_store_id'] = $store_id;
                } else {
                    $store_id = 0;
                    //$this->session->data['config_store_id'] = $store_id;
                }
                $product_info = $this->model_assets_product->getProduct($this->request->post['product_store_id'], true);

                if ($product_info) {
                    if (isset($this->request->post['quantity'])) {
                        $quantity = $this->request->post['quantity'];
                    } else {
                        $quantity = 1;
                    }

                    if (isset($this->request->post['option'])) {
                        $option = array_filter($this->request->post['option']);
                    } else {
                        $option = [];
                    }

                    $product_options = $this->model_assets_product->getProductOptions($this->request->post['product_store_id']);

                    foreach ($product_options as $product_option) {
                        if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                            $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                        }
                    }

                    if (!isset($json['error']['option'])) {
                        $this->cart->add($this->request->post['product_store_id'], $quantity, $option, false, $store_id, $this->request->post['store_product_variation_id']);

                        $json['success'] = $this->language->get('text_success');

                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['shipping_methods']);
                        unset($this->session->data['payment_method']);
                        unset($this->session->data['payment_methods']);
                    }
                } else {
                    $json['error']['store'] = $this->language->get('error_store');
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function putEdit() { //edit to putEdit
        $this->load->language('api/cart');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->cart->update($this->request->post['key'], $this->request->post['quantity']);

            $json['success'] = $this->language->get('text_success');

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function remove() {
        $this->load->language('api/cart');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Remove
            if (isset($this->request->post['key'])) {
                $this->cart->remove($this->request->post['key']);

                unset($this->session->data['vouchers'][$this->request->post['key']]);

                $json['success'] = $this->language->get('text_success');

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
                unset($this->session->data['reward']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts() {//renamed function products to getProducts
        echo 'cart/products';
        $this->load->language('api/cart');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error']['warning'] = $this->language->get('error_permission');
        } else {
            // Stock
            if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
                $json['error']['stock'] = $this->language->get('error_stock');
            }

            // Products
            $json['products'] = [];

            $products = $this->cart->getProducts();

            foreach ($products as $product) {
                $product_total = 0;

                foreach ($products as $product_2) {
                    if ($product_2['product_store_id'] == $product['product_store_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                if ($product['minimum'] > $product_total) {
                    $json['error']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                }

                $option_data = [];

                foreach ($product['option'] as $option) {
                    $option_data[] = [
                        'product_option_id' => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'name' => $option['name'],
                        'value' => $option['value'],
                        'type' => $option['type'],
                    ];
                }

                $json['products'][] = [
                    'key' => $product['key'],
                    'store_id' => $product['store_id'],
                    'product_store_id' => $product['product_store_id'],
                    'store_product_variation_id' => $product['store_product_variation_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'shipping' => true,
                    'price' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                    'total' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
                    'reward' => $product['reward'],
                ];
            }

            // Totals
            $this->load->model('extension/extension');

            $total_data = [];
            $total = 0;
            $taxes = $this->cart->getTaxes();

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

            $json['totals'] = [];

            foreach ($total_data as $total) {
                $json['totals'][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value']),
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCartProduct() { //add to addCartProducts
        $this->load->language('api/cart');
        $json = [];

        if (isset($this->request->post['product'])) {
            $this->cart->clear();
            foreach ($this->request->post['product'] as $product) {
                $option = [];
                $this->load->model('assets/product');
                $product_info = $this->model_assets_product->getProduct($product['product_store_id'], true);

                $log = new Log('error.log');
                $log->write(sizeof($product_info));
                if (is_array($product_info) && $product_info['status'] == 1) {
                    $log->write($product_info);
                    $this->cart->add($product['product_store_id'], $product['quantity'], $option, false, $product['store_id'], $product['store_product_variation_id'], 'replacable', $product['product_note'], $product['produce_type']);
                    $json['success'] = $this->language->get('text_success');
                }
            }
        }
        $json['status'] = 200;
        $json['session_id'] = $this->session->getId();
        $json['data'] = [];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getApiSessionId() {

        $json['status'] = 200;
        $json['success'] = 'Api Session Id';
        $json['session_id'] = $this->session->getId();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCartProduct() {
        
        $this->load->model('account/customer');
        $this->cart->clearcart();
        $this->model_account_customer->getDBCart();
        $totalQuantity = 0;
        $json = [];
        $log = new Log('error.log');
        $log->write('getCartProducts');
        $log->write($this->cart->getProducts());
        $log->write('getCartProducts');

        foreach ($this->session->data['cart'] as $keys => $data) {

            $product = unserialize(base64_decode($keys));
            $product_store_id = $product['product_store_id'];
            $this->load->model('assets/product');
            $product_info = $this->model_assets_product->getProductWithCategoryPricing($product_store_id, true);
            $tax_info = $this->tax->getRateNameByTaxClassId($product_info['tax_class_id']);
            $tax_name = is_array($tax_info) && $tax_info != NULL && count($tax_info) > 0 ? $tax_info['name'] : NULL;
            $tax_percentage = is_array($tax_info) && $tax_info != NULL && count($tax_info) > 0 ? $tax_info['rate'] : NULL;
            $image = $this->load->controller('api/customer/imagepath', $product_info['image']);
            if($image!=null && $image!="")
            {
                $image=BASE_URL . '/' . $image;
            }
            else
            {
                $image=BASE_URL . '/' . 'image/cache/placeholder-300x300.png';
            }
            if (is_array($product_info) && $product_info['status'] == 1) {
                $this->data[$keys] = [
                    'key' => $keys,
                    'product_id' => $product_info['product_id'],
                    'product_store_id' => $product_info['product_store_id'],
                    'store_product_variation_id' => isset($product_info['store_product_variation_id']) ? $product_info['store_product_variation_id'] : '',
                    'store_id' => $product_info['store_id'],
                    'store_name' => $product_info['store_name'],
                    'product_type' => isset($product_info['product_type']) ? trim($product_info['product_type']) : '',
                    'produce_type' => $data['produce_type'],
                    'product_note' => $data['product_note'],
                    'unit' => $product_info['unit'],
                    'name' => $product_info['name'],
                    'model' => $product_info['model'],
                    'shipping' => 0,
                    // 'image' => BASE_URL . '/' . $image,
                     'image' => $image,
                    'orginal_image' => $product_info['image'],
                    'option' => [],
                    'download' => [],
                    'quantity' => $data['quantity'],
                    'minimum' => $product_info['min_quantity'],
                    'subtract' => $product_info['subtract_quantity'],
                    'stock' => '',
                    'price' => $product_info['price'],
                    'special_price' => $product_info['special_price'],
                    'total' => $product_info['special_price'] * $data['quantity'],
                    'percentage_off' => (($product_info['price'] - $product_info['special_price']) / $product_info['price']) * 100,
                    'reward' => 0,
                    'points' => 0,
                    'tax_name' => $tax_name,
                    'tax_amount' => '',
                    'tax_percentage' => $tax_percentage,
                    'tax_class_id' => $product_info['tax_class_id'],
                    'weight' => 0,
                    'weight_class_id' => 0,
                    'length' => 0,
                    'width' => 0,
                    'height' => 0,
                    'length_class_id' => 0,
                    'recurring' => false,
                ];
            }
        }

        $json['status'] = 200;
        $json['data'] = [];
        $json['products'] = $this->data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
