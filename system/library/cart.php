<?php

class Cart {

    private $config;
    private $db;
    private $data = [];

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->customer = $registry->get('customer');
        $this->session = $registry->get('session');
        $this->db = $registry->get('db');
        $this->tax = $registry->get('tax');
        $this->weight = $registry->get('weight');
        $this->load = $registry->get('load');

        if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
            $this->session->data['cart'] = [];
        }

        if (!isset($this->session->data['temp_cart']) || !is_array($this->session->data['temp_cart'])) {
            $this->session->data['temp_cart'] = [];
        }
    }

    public function getStores() {
        $stores = [];
        $store_id = 0;

        foreach ($this->session->data['cart'] as $keys => $data) {
            $product = unserialize(base64_decode($keys));
            if (!in_array($product['store_id'], $stores)) {
                $stores[] = $product['store_id'];
            }
        }

        return $stores;
    }

    public function getCartProducts() {
        $totalQuantity = 0;
        $log = new Log('error.log');
        $log->write('cwen');
        //$log->write($product_query);

        if (CATEGORY_PRICE_ENABLED == true) {
            $this->data = null;
        }

        if (!$this->data) {
            $log->write('pro 1');

            foreach ($this->session->data['cart'] as $keys => $data) {
                $product = unserialize(base64_decode($keys));

                //echo "<pre>";print_r($product);die;

                $product_store_id = $product['product_store_id'];
                $produce_type = $this->session->data['cart'][$keys]['produce_type'];

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
                $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
                $this->db->where('product_description.language_id', (int) $this->config->get('config_language_id'));

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

                    if (CATEGORY_PRICE_ENABLED == true) {
                        $cat_price = $this->getCategoryPriceProduct($product_query->row['product_store_id'], $product['store_id'], $_SESSION['customer_category']);
                        $special_price = isset($cat_price) ? $this->getCategoryPriceProduct($product_query->row['product_store_id'], $product['store_id'], $_SESSION['customer_category']) : $price;
                        $price = $special_price;
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
                        'produce_type' => $produce_type,
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

    public function getProducts() {
        $log = new Log('error.log');
        $log->write('get product');

        if (!$this->data) {
            $log->write('get product if');

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

                if (!empty($data['product_note'])) {
                    $product_note = $data['product_note'];
                } else {
                    $product_note = '';
                }

                if (!empty($data['produce_type'])) {
                    $produce_type = $data['produce_type'];
                } else {
                    $produce_type = '';
                }

                //product_type replacable/not replacable
                //$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)    WHERE p.product_id = '" . (int) $product_id . "' AND p.status = '1'");

                $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
                $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
                $this->db->where('product.status', 1);
                $this->db->where('product_description.language_id', (int) $this->config->get('config_language_id'));
                $this->db->where('product_to_store.product_store_id', $product_store_id);
                $product_query = $this->db->get('product_to_store');

                // $product_query = $this->db->query("
                //     SELECT a.product_id,b.*
                //     FROM `" . DB_PREFIX . "product` a,`" . DB_PREFIX . "product_to_store` b
                //     WHERE a.status='1'
                //     AND b.product_store_id='".$product_store_id."'"
                // );
//                echo "<pre>";print_r($product_query);die;
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
                            $orignal_price = $row['price'];
                        } else {
                            $orignal_price = null;
                            $price = $row['price'];
                        }

                        $image = $row['image'];
                        $store_product_variation_id = $product['store_product_variation_id'];

                        //old below
                        //$variation = ' - '.$row['name'];

                        $variation = ' - ' . $row['unit'];
                    } else {
                        $price = $product_query->row['price'];
                        $orignal_price = $price;

                        // Product Specials
                        //print_r($product_query);
                        if ($product_query->row['special_price'] > 0) {
                            $price = $product_query->row['special_price'];
                        } else {
                            $orignal_price = null;
                        }

                        $store_product_variation_id = 0;
                        $variation = $product_query->row['unit'] ? ' - ' . $product_query->row['unit'] : '';
                        $image = $product_query->row['image'];
                    }

                    if (CATEGORY_PRICE_ENABLED == true) {
                        $cat_price = $this->getCategoryPriceProduct($product_query->row['product_store_id'], $product['store_id'], $_SESSION['customer_category']);
                        $orignal_price = isset($cat_price) ? $this->getCategoryPriceProduct($product_query->row['product_store_id'], $product['store_id'], $_SESSION['customer_category']) : $price;
                        $price = $orignal_price;
                    }

                    $this->data[$keys] = [
                        'key' => $keys,
                        'product_store_id' => $product_query->row['product_store_id'],
                        'store_product_variation_id' => $store_product_variation_id,
                        'store_id' => $product['store_id'],
                        'product_id' => $product_query->row['product_id'],
                        'name' => $product_query->row['name'],
                        'product_type' => $product_type,
                        'produce_type' => $produce_type,
                        'product_note' => $product_note,
                        'unit' => $product_query->row['unit'],
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
                        'special_price' => $orignal_price,
                        'total' => $price * $data['quantity'],
                        'reward' => 0,
                        'points' => 0,
                        'tax_class_id' => $product_query->row['tax_class_id'],
                        'tax_percentage' => $product_query->row['tax_percentage'],
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

        return $this->data;
    }

    public function getRecurringProducts() {
        $recurring_products = [];

        foreach ($this->getProducts() as $key => $value) {
            if ($value['recurring']) {
                $recurring_products[$key] = $value;
            }
        }

        return $recurring_products;
    }

    public function getTaxesByApi($args) {
        $tax_data = [];

        $log = new Log('error.log');
        $log->write('getTaxesByApi');
        $log->write($args);

        foreach ($args['products'] as $product) {
            //$this->load->model( 'assets/product' );

            if (isset($product['store_id'])) {
                $row = $this->db->query('select city_id from ' . DB_PREFIX . 'store WHERE store_id="' . $product['store_id'] . '"')->row;
                if ($row) {
                    $this->tax->setShippingAddress($row['city_id']);
                    $this->tax->setCity($row['city_id']);
                }
            }

            $this->db->select('product_to_store.*,product.*,product_description.*', false);
            $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');
            $this->db->group_by('product_to_store.product_store_id');
            $this->db->where('product_to_store.product_store_id', $product['product_store_id']);
            $productData = $this->db->get('product_to_store')->row;

            $log->write($productData);

            //$log->write($productData['tax_class_id']);

            if (isset($productData['special_price']) && (int) $productData['special_price']) {
                $productData['price'] = $productData['special_price'];
            }

            if (isset($productData['tax_class_id']) && (int) $productData['tax_class_id']) {
                $tax_rates = $this->tax->getRates($productData['price'], $productData['tax_class_id']);

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                        $tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
                    } else {
                        $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
                    }
                }
            }
        }

        $new_tax_structure = [];

        foreach ($tax_data as $key => $value) {
            $new_tax_structure[$this->tax->getRateName($key)] = number_format((float) $value, 2, '.', ''); //round($value,2);
        }

        $tax_data = $new_tax_structure;

        return $tax_data;
    }

    public function addCustom($product_store_id, $qty, $option = [], $recurring_id = 0, $store_id, $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null) {
        $this->data = [];

        $product['product_store_id'] = (int) $product_store_id;

        if ($store_product_variation_id) {
            $product['store_product_variation_id'] = $store_product_variation_id;
        }

        if ($option) {
            $product['option'] = $option;
        }

        if ($recurring_id) {
            $product['recurring_id'] = (int) $recurring_id;
        }

        /* if ($product_type) {
          $product['product_type'] = $product_type;
          } */

        if ($store_id) {
            $product['store_id'] = $store_id;
        } else {
            $product['store_id'] = $this->session->data['config_store_id'];
        }

        $key = base64_encode(serialize($product));

        $log = new Log('error.log');
        $log->write('cart add');
        $log->write($produce_type);

        if (null == $produce_type || 'null' == $produce_type) {
            $log->write('cart add123');

            if ((float) $qty && ((float) $qty > 0)) {
                if (!isset($this->session->data['cart'][$key])) {
                    $this->session->data['cart'][$key]['quantity'] = (float) $qty;
                } else {
                    $this->session->data['cart'][$key]['quantity'] += (float) $qty;
                }

                $this->session->data['cart'][$key]['product_note'] = $product_note;
                $this->session->data['cart'][$key]['produce_type'] = null;
            }
        } else {
            $log->write('cart add456');

            if ((float) $qty && ((float) $qty > 0)) {
                if (!isset($this->session->data['cart'][$key])) {
                    $this->session->data['cart'][$key]['produce_type'][0]['type'] = $produce_type;
                    $this->session->data['cart'][$key]['produce_type'][0]['value'] = $qty;

                    $this->session->data['cart'][$key]['quantity'] = (float) $qty;
                } else {
                    if (!isset($this->session->data['cart'][$key]['produce_type'])) {
                        $this->session->data['cart'][$key]['produce_type'][0]['type'] = $produce_type;
                        $this->session->data['cart'][$key]['produce_type'][0]['value'] = $qty;
                        $this->session->data['cart'][$key]['quantity'] += (float) $qty;
                    } else {
                        $preProduceTypes = $this->session->data['cart'][$key]['produce_type'];

                        $exists = false;
                        $oldquantity = $this->session->data['cart'][$key]['quantity'];
                        $i = 0;
                        foreach ($preProduceTypes as $pt) {
                            if ($pt['type'] == $produce_type) {
                                $exists = true;
                                $oldtypequantity = $pt['value'];
                                $pt['value'] = $qty;
                                $preProduceTypes[$i]['value'] = $qty;
                                $newquantity = $oldquantity - $oldtypequantity + $qty;
                            }
                            ++$i;
                        }
                        if (false == $exists) {
                            $count = count($this->session->data['cart'][$key]['produce_type']);
                            //$count= $count+1;
                            $this->session->data['cart'][$key]['produce_type'][$count]['type'] = $produce_type;
                            $this->session->data['cart'][$key]['produce_type'][$count]['value'] = $qty;
                            $newquantity = $oldquantity + $qty;

                            $this->session->data['cart'][$key]['quantity'] = (float) $newquantity;
                        } else {
                            $this->session->data['cart'][$key]['produce_type'] = $preProduceTypes;
                            $this->session->data['cart'][$key]['quantity'] = (float) $newquantity;
                        }

                        //$data['results'][] = $row;
                    }
                }
            }
            $this->session->data['cart'][$key]['product_note'] = $product_note;
        }

        $cart_data = $key;
        $this->load->controller('checkout/cart/addcartdb', $cart_data);

        return $key;
    }

    public function add($product_store_id, $qty = 1, $option = [], $recurring_id = 0, $store_id = false, $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null) {
        $this->data = [];

        $product['product_store_id'] = (int) $product_store_id;

        if ($store_product_variation_id) {
            $product['store_product_variation_id'] = $store_product_variation_id;
        }

        if ($option) {
            $product['option'] = $option;
        }

        if ($recurring_id) {
            $product['recurring_id'] = (int) $recurring_id;
        }

        /* if ($product_type) {
          $product['product_type'] = $product_type;
          } */

        if ($store_id) {
            $product['store_id'] = $store_id;
        } else {
            $product['store_id'] = $this->session->data['config_store_id'];
        }

        $key = base64_encode(serialize($product));

        $log = new Log('error.log');
        $log->write('cart add');
        $log->write($produce_type);

        if (null == $produce_type || 'null' == $produce_type) {
            $log->write('cart add123');

            if ((float) $qty && ((float) $qty > 0)) {
                if (!isset($this->session->data['cart'][$key])) {
                    $this->session->data['cart'][$key]['quantity'] = (float) $qty;
                } else {
                    $this->session->data['cart'][$key]['quantity'] += (float) $qty;
                }

                $this->session->data['cart'][$key]['product_note'] = $product_note;
                $this->session->data['cart'][$key]['produce_type'] = null;
            }
        } else {
            $log->write('cart add456');

            if ((float) $qty && ((float) $qty > 0)) {
                if (!isset($this->session->data['cart'][$key])) {
                    $this->session->data['cart'][$key]['produce_type'][0]['type'] = $produce_type;
                    $this->session->data['cart'][$key]['produce_type'][0]['value'] = $qty;

                    $this->session->data['cart'][$key]['quantity'] = (float) $qty;
                } else {
                    if (!isset($this->session->data['cart'][$key]['produce_type'])) {
                        $this->session->data['cart'][$key]['produce_type'][0]['type'] = $produce_type;
                        $this->session->data['cart'][$key]['produce_type'][0]['value'] = $qty;
                        $this->session->data['cart'][$key]['quantity'] += (float) $qty;
                    } else {
                        $preProduceTypes = $this->session->data['cart'][$key]['produce_type'];

                        $exists = false;
                        $oldquantity = $this->session->data['cart'][$key]['quantity'];
                        $i = 0;
                        foreach ($preProduceTypes as $pt) {
                            if ($pt['type'] == $produce_type) {
                                $exists = true;
                                $oldtypequantity = $pt['value'];
                                $pt['value'] = $qty;
                                $preProduceTypes[$i]['value'] = $qty;
                                $newquantity = $oldquantity - $oldtypequantity + $qty;
                            }
                            ++$i;
                        }
                        if (false == $exists) {
                            $count = count($this->session->data['cart'][$key]['produce_type']);
                            //$count= $count+1;
                            $this->session->data['cart'][$key]['produce_type'][$count]['type'] = $produce_type;
                            $this->session->data['cart'][$key]['produce_type'][$count]['value'] = $qty;
                            $newquantity = $oldquantity + $qty;

                            $this->session->data['cart'][$key]['quantity'] = (float) $newquantity;
                        } else {
                            $this->session->data['cart'][$key]['produce_type'] = $preProduceTypes;
                            $this->session->data['cart'][$key]['quantity'] = (float) $newquantity;
                        }

                        //$data['results'][] = $row;
                    }
                }
            }
            $this->session->data['cart'][$key]['product_note'] = $product_note;
        }

        $cart_data = $key;
        $this->load->controller('checkout/cart/addcartdb', $cart_data);
        return $key;
    }

    public function updateProductType($key, $product_type = 'replacable') {
        $this->data = [];

        $keys = [];
        $log = new Log('error.log');
        $log->write('***********************************************cart updateProductType content***********************************************');

        $product = unserialize(base64_decode($key));

        if (isset($this->session->data['cart'][$key])) {
            $log->write('is set');
            $this->session->data['cart'][$key]['product_type'] = $product_type;
        }

        return true;
    }

    public function update($key, $qty, $product_note, $produce_type = null) {
        if (null == $produce_type || 'null' == $produce_type) {
            $this->data = [];

            $log = new Log('error.log');
            /* $log->write("cart content");
             */
            if ((float) $qty && ((float) $qty > 0) && isset($this->session->data['cart'][$key])) {
                $this->session->data['cart'][$key]['quantity'] = (float) $qty;
                if ($product_note != null && $product_note != "null") {
                    $this->session->data['cart'][$key]['product_note'] = $product_note;
                }
            } else {
                $this->remove($key);
                $this->removeTempCart($key);
            }

            if ($qty && ($qty > 0)) {
                $this->session->data['temp_cart'][$key]['quantity'] = (float) $qty;
                //$this->session->data['temp_cart'][$key]['ripe'] =   $ripe;
                if ($product_note != null && $product_note != "null") {
                    $this->session->data['temp_cart'][$key]['product_note'] = $product_note;
                }
                $this->session->data['temp_cart'][$key]['produce_type'] = $produce_type;
            } else {
                //$this->session->data['temp_cart'][$key] = (int) $qty;
            }
        } else { //else will called when items are removed of type having produce type
            $log = new Log('error.log');
            $log->write('test123');
            $log->write($produce_type);
            if ($qty > 0) {
                $preProduceTypes = $this->session->data['cart'][$key]['produce_type'];
                $oldquantity = $this->session->data['cart'][$key]['quantity'];
                $i = 0;
                foreach ($preProduceTypes as $pt) {
                    if ($pt['type'] == $produce_type) {
                        $oldtypequantity = $pt['value'];
                        $pt['value'] = $qty;
                        $preProduceTypes[$i]['value'] = $qty;
                        $newquantity = $oldquantity - $oldtypequantity + $qty;
                    }
                    ++$i;
                }

                $this->session->data['cart'][$key]['produce_type'] = $preProduceTypes;
                $this->session->data['cart'][$key]['quantity'] = (float) $newquantity;
                if ($product_note != null && $product_note != "null") {
                    $this->session->data['cart'][$key]['product_note'] = $product_note;
                }
            } else {
                //if produce type is availabe and 0 is passed , deletion should be happen based on produce type
                //not on entire key
            }
        }
        $cart_data = $key;
        $this->load->controller('checkout/cart/addcartdb', $cart_data);
    }

    public function updateProduceType($key, $qty, $produce_type) {
        $log->write('test123');
        $log->write($produce_type);
        $preProduceTypes = $this->session->data['cart'][$key]['produce_type'];
        $oldquantity = $this->session->data['cart'][$key]['quantity'];
        $i = 0;
        foreach ($preProduceTypes as $pt) {
            if ($pt['type'] == $produce_type) {
                $oldtypequantity = $pt['value'];
                $pt['value'] = $qty;
                $preProduceTypes[$i]['value'] = $qty;
                $newquantity = $oldquantity - $oldtypequantity + $qty;
            }
            ++$i;
        }

        $this->session->data['cart'][$key]['produce_type'] = $preProduceTypes;
        $this->session->data['cart'][$key]['quantity'] = (float) $newquantity;
    }

    public function remove($key) {
        $cart_data = $key;
        $this->load->controller('checkout/cart/removecartdb', $cart_data);
        $this->data = [];
        unset($this->session->data['cart'][$key]);
    }

    public function removeTempCart($key) {
        $cart_data = $key;
        $this->load->controller('checkout/cart/removecartdb', $cart_data);
        $this->data = [];

        unset($this->session->data['temp_cart'][$key]);
    }

    public function clear() {
        $this->data = [];

        $this->session->data['cart'] = [];
        $this->session->data['temp_cart'] = [];
        $this->load->controller('checkout/cart/clearcartdb');
    }

    public function clearcart() {
        $this->data = [];

        $this->session->data['cart'] = [];
        $this->session->data['temp_cart'] = [];
    }

    public function getWeight() {
        $weight = 0;

        foreach ($this->getProducts() as $product) {
            if ($product['shipping']) {
                $weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
            }
        }

        return $weight;
    }

    public function getSubTotal($store_id = false) {
        $total = 0;

        $log = new Log('error.log');
        /* $log->write('getSubTotal system_library_cart.php');
          $log->write($this->getProducts());
          $log->write('getSubTotal system_library_cart.php'); */
        foreach ($this->getProducts() as $product) {
            if ($store_id && $product['store_id'] != $store_id) {
                continue;
            }
            $total += $product['total'];
        }

        return $total;
    }

    public function getTaxes() {
        $tax_data = [];

        foreach ($this->getProducts() as $product) {
            if ($product['tax_class_id']) {
                $tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                        $tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
                    } else {
                        $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
                    }
                }
            }
        }

        return $tax_data;
    }

    public function getTaxesByStore($store_id) {
        $tax_data = [];

        foreach ($this->getProducts() as $product) {
            if ($product['tax_class_id'] && $product['store_id'] == $store_id) {
                $tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                        $tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
                    } else {
                        $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
                    }
                }
            }
        }

        return $tax_data;
    }

    public function getTotal() {
        $total = 0;
        //echo '<pre>';print_r($this->getProducts());exit;
        $log = new Log('error.log');
        /* $log->write('getTotal system_library_cart.php');
          $log->write($this->getProducts());
          $log->write('getTotal system_library_cart.php'); */
        if (is_array($this->getProducts())) {
            foreach ($this->getProducts() as $product) {
                $total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
            }
        }
        //echo '<pre>';echo $total;exit;
        return $total;
    }

    public function getTotalForKwikBasket() {
        $store_id = 75;
        $total = 0;
        //echo '<pre>';print_r($this->getProducts());exit;
        $log = new Log('error.log');
        $log->write('getTotal system_library_cart.php');
        $log->write($this->getProducts());
        $log->write('getTotal system_library_cart.php');
        if (is_array($this->getProducts())) {
            foreach ($this->getProducts() as $product) {
                if ($product['store_id'] == $store_id) {
                    $total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
                }
            }
        }
        //echo '<pre>';echo $total;exit;
        return $total;
    }

    public function getTotalByStore($store_id) {
        $total = 0;

        foreach ($this->getProducts() as $product) {
            if ($product['store_id'] == $store_id) {
                $total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
            }
        }

        return $total;
    }

    public function getTotalProductsByStore($store_id) {
        $total = 0;

        foreach ($this->getProducts() as $product) {
            if ($product['store_id'] == $store_id) {
                $total += $product['quantity'];
            }
        }

        return $total;
    }

    public function countProducts() {
        $product_total = 0;

        $products = $this->getProducts();

        if (is_array($products)) {
            foreach ($products as $product) {
                $product_total += $product['quantity'];
            }
        }
        return count($products);
    }

    public function hasProducts() {
        return count($this->session->data['cart']);
    }

    public function hasRecurringProducts() {
        return count($this->getRecurringProducts());
    }

    public function hasStock() {
        $stock = true;

        foreach ($this->getProducts() as $product) {
            if (!$product['stock']) {
                $stock = false;
            }
        }

        return $stock;
    }

    public function hasShipping() {
        $shipping = false;
        // foreach ($this->getProducts() as $product) {
        //     if ($product['shipping']) {
        //         $shipping = true;
        //         break;
        //     }
        // }
        return true;
    }

    public function hasDownload() {
        $download = false;

        foreach ($this->getProducts() as $product) {
            if ($product['download']) {
                $download = true;

                break;
            }
        }

        return $download;
    }

    public function getCategoryPriceProduct($product_store_id, $store_id, $category) {
        //echo $category;exit;
        $product_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE product_store_id = '" . (int) $product_store_id . "' AND store_id = '" . (int) $store_id . "' AND price_category = '" . $category . "' AND status = '1'");

        return $product_query->row['price'];
    }

}
