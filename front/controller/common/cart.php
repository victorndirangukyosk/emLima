<?php

class ControllerCommonCart extends Controller
{
    public function index()
    {
        $this->load->language('common/cart');

        // Totals
        $this->load->model('extension/extension');

        $this->load->model('tool/image');

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = [];

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'].'_status')) {
                    $this->load->model('total/'.$result['code']);

                    $this->{'model_total_'.$result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = [];

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $countProducts = 0;
        $products = [];
        $this->load->model('account/customer');
        //$this->cart->clearcart();
        //$this->model_account_customer->getDBCart();
        $result = $this->cart->getCartProducts();
        //		echo '<pre>';print_r($result);die;
        if ($result) {
            $countProducts = isset($result['quantity']) ? $result['quantity'] : 0;
            $products = isset($result['products']) ? $result['products'] : null;
        }

        $data['button_clear_cart'] = $this->language->get('button_clear_cart');

        if (isset($store_info['logo'])) {
            $data['image'] = $this->model_tool_image->resize($store_info['logo'], 155, 155);
        } else {
            $data['image'] = $this->model_tool_image->resize('placeholder.png', 155, 155);
        }

        $data['text_replacable_title'] = $this->language->get('text_replacable_title');
        $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');

        $data['text_my_cart'] = $this->language->get('text_my_cart');
        $data['text_replacable'] = $this->language->get('text_replacable');
        $data['text_not_replacable'] = $this->language->get('text_not_replacable');

        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_my_cart'] = $this->language->get('text_my_cart');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_items'] = sprintf($this->language->get('text_items'), $countProducts + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_item'] = $this->language->get('text_item');
        $data['text_price'] = $this->language->get('text_price');
        $data['button_view_place'] = $this->language->get('button_view_place');

        $data['button_remove'] = $this->language->get('button_remove');

        $this->load->model('tool/upload');

        $data['products'] = [];
        
        if (is_array($products)) {
            foreach ($products as $product) {
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
                        'type' => $option['type'],
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

                //echo "<pre>";print_r($product);die;
                $data['products'][] = [
                    'key' => $product['key'],
                    'product_store_id' => $product['product_store_id'],
                    'store_product_variation_id' => $product['store_product_variation_id'],
                    'thumb' => $image,
                    'name' => $product['name'],
                    'product_type' => $product['product_type'],
                    'unit' => isset($product['unit']) ? $product['unit'] : '',
                    'model' => $product['model'],
                    'option' => $option_data,
                    'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
                    'quantity' => $product['quantity'],
                    'price' => $price,
                    'total' => $total,
                    'href' => $this->url->link('product/product', 'product_store_id=' . $product['product_store_id']),
                    'minimum' => $product['minimum'] > 0 ? $product['minimum'] : 1,
                ];
            }
        }
        // Gift Voucher
        $data['vouchers'] = [];

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $data['vouchers'][] = [
                    'key' => $key,
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount']),
                ];
            }
        }

        $data['totals'] = [];

        foreach ($total_data as $result) {
            $data['totals'][] = [
                'title' => $result['title'],
                'text' => $this->currency->format($result['value']),
            ];
        }
        $data['cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        $data['notices'] = [];
        //echo "<pre>";print_r($data['notices']);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/cart.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/cart.tpl', $data);
        } else {
            return $this->load->view('default/template/common/cart.tpl', $data);
        }
    }

    public function newIndex()
    {
        $this->load->language('common/cart');

        // Totals
        $this->load->model('extension/extension');
        $this->load->model('account/address');
        $this->load->model('tool/image');

        $order_stores = $this->cart->getStores();
        $store_data = [];

        foreach ($order_stores as $os) {
            $store_info = $this->model_account_address->getStoreData($os);
            $store_total = $this->cart->getSubTotal($os);
            $store_data[] = $store_info;
        }

        if (0 == count($order_stores) && isset($this->session->data['config_store_id'])) {
            $store_info = $this->model_account_address->getStoreData($this->session->data['config_store_id']);
        }
        //echo "<pre>";print_r($store_info);die;
        if (isset($store_info) && $store_info['logo']) {
            $data['image'] = $this->model_tool_image->resize($store_info['logo'], 155, 155);
        } else {
            $data['image'] = $this->model_tool_image->resize('placeholder.png', 155, 155);
        }

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = [];

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'].'_status')) {
                    $this->load->model('total/'.$result['code']);

                    $this->{'model_total_'.$result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = [];

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $countProducts = 0;
        $products = [];
        $result = $this->cart->getCartProducts();

        if ($result) {
            $countProducts = isset($result['quantity']) ? $result['quantity'] : 0;
            $products = isset($result['products']) ? $result['products'] : null;
        }

        $data['text_replacable_title'] = $this->language->get('text_replacable_title');
        $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');

        $data['text_comfirm_clear'] = $this->language->get('text_comfirm_clear');

        $data['text_my_cart'] = $this->language->get('text_my_cart');
        $data['text_replacable'] = $this->language->get('text_replacable');
        $data['text_not_replacable'] = $this->language->get('text_not_replacable');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_recurring'] = $this->language->get('text_recurring');

        $data['text_items'] = sprintf($this->language->get('text_items'), $countProducts + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_item'] = $this->language->get('text_item');
        $data['text_price'] = $this->language->get('text_price');
        $data['button_view_place'] = $this->language->get('button_view_place');

        $data['button_clear_cart'] = $this->language->get('button_clear_cart');

        $data['button_remove'] = $this->language->get('button_remove');

        $this->load->model('tool/image');
        $this->load->model('tool/upload');
        $this->load->model('assets/product');
        $data['products'] = [];

        foreach ($products as $product) {
            $max_qty = 1;

            $p_detail = $this->model_assets_product->getProduct($product['product_store_id']);

            if ($p_detail) {
                $max_qty = $p_detail['quantity'];
            }
            
            if ($product['image'] != NULL && file_exists(DIR_IMAGE.$product['image'])) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else if($product['image'] == NULL || !file_exists(DIR_IMAGE.$product['image'])) {
                //$image = '';

                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20).'..' : $value),
                    'type' => $option['type'],
                ];
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            $special_price = null;
            if (!is_null($product['special_price'])) {
                $special_price = $this->currency->format($product['special_price']);
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            $data['products'][] = [
                'key' => $product['key'],
                'product_store_id' => $product['product_store_id'],
                'store_product_variation_id' => $product['store_product_variation_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'product_type' => $product['product_type'],
                'unit' => isset($product['unit']) ? $product['unit'] : '',
                'model' => $product['model'],
                'option' => $option_data,
                'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
                'quantity' => $product['quantity'],
                'price' => $price,
                'original_price' => $special_price,
                'total' => $total,
                'minimum' => $product['minimum'] > 0 ? $product['minimum'] : $max_qty,
                'store_id' => $product['store_id'],
                'produce_type' => $product['produce_type'],
                //'store_name'     => $this->model_account_address->getStoreNameById($product['store_id']),
                'href' => $this->url->link('product/product', 'product_store_id='.$product['product_store_id']),
            ];
        }

        $data['arrs'] = [];

        foreach ($data['products'] as $key => $item) {
            $data['arrs'][$item['store_id']][$key] = $item;
        }

        if (isset($this->session->data['config_store_id']) && array_key_exists($this->session->data['config_store_id'], $data['arrs'])) {
            //echo "<pre>";print_r($data['arrs']);die;
            $tmp[$this->session->data['config_store_id']] = $data['arrs'][$this->session->data['config_store_id']];
            unset($data['arrs'][$this->session->data['config_store_id']]);

            //$tmp[] = $data['arrs'];
            foreach ($data['arrs'] as $key => $value) {
                $tmp[$key] = $value;
            }

            //echo "<pre>";print_r($tmp);die;
            $data['arr'] = $tmp;

        // if(isset($this->session->data['config_store_id'])) {
      //       	$store_info = $this->model_account_address->getStoreData($this->session->data['config_store_id']);
      //       	if (isset($store_info) && $store_info['logo']) {
         //            $data['image'] = $this->model_tool_image->resize($store_info['logo'], 155,155);
         //        } else {
         //            $data['image']= $this->model_tool_image->resize('placeholder.png', 155,155);
         //        }

      //       }

            //echo "<pre>";print_r($data['arr']);die;
        } else {
            $data['arr'] = $data['arrs'];
        }

        if ($this->config->get('config_logo') && is_file(DIR_IMAGE.$this->config->get('config_logo'))) {
            $data['image'] = $this->model_tool_image->resize($this->config->get('config_logo'), 155, 155);
        }

        //echo "<pre>";print_r($data['products']);die;

        // Gift Voucher
        $data['vouchers'] = [];

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $data['vouchers'][] = [
                    'key' => $key,
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount']),
                ];
            }
        }

        $data['totals'] = [];

        foreach ($total_data as $result) {
            $data['totals'][] = [
                'title' => $result['title'],
                'text' => $this->currency->format($result['value']),
            ];
        }
        $data['cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        $data['notices'] = [];
        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/cart.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/cart.tpl', $data);
        } else {
            return $this->load->view('default/template/common/cart.tpl', $data);
        }
    }

    public function info()
    {
        $this->response->setOutput($this->index());
    }

    public function newInfo()
    {
        $this->response->setOutput($this->newIndex());
    }
}
