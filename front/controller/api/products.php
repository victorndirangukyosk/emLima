<?php

class ControllerApiProducts extends Controller
{
    public function addVendorproduct($args = [])
    {
        $this->load->language('api/products');
        $json = [];
        $this->load->model('api/products');

        $product_id = $this->model_api_products->addProduct($args);
        if ($product_id) {
            $json['success'] = 'Product added successfully';
            $json['status'] = 200;
            $json['data'] = $args;
        } else {
            $json['status'] = 400;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAutocomplete($args)
    {
        $log = new Log('error.log');
        $log->write('getAutocomplete');
        $log->write($args);

        $json = [];

        if (isset($args['filter_name'])) {
            $this->load->model('api/products');

            if (isset($args['filter_name'])) {
                $filter_name = $args['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 5;
            }

            if (isset($args['store_id'])) {
                $store_id = $args['store_id'];
            } else {
                $store_id = 5;
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => $limit,
                'store_id' => $store_id,
            ];

            $log->write($filter_data);

            //$results = $this->model_api_products->getProducts($filter_data);
            $results = $this->model_api_products->getProducts($args);
            $log->write($results);

            foreach ($results as $result) {
                $json[] = [
                    'product_id' => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'unit' => $result['unit'],
                ];
            }
        }

        $log->write('json o/p');
        $log->write($json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProduct($args = [])
    {
        $this->load->language('api/products');

        $json = [];

        //echo "api/product";

        //echo $args['id'];
        if (!isset($this->session->data['api_id']) || !isset($args['store_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/products');
            //$this->load->model('catalog/product');

            //$product = $this->model_catalog_product->getProduct($args['id']);
            //echo $args['id'];
            $product = $this->model_api_products->getProduct($args['id'], $args['store_id']);

            //print_r($product);die;
            //echo ("product");
            $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');

            $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

            $currency_value = false;

            if (isset($args['currency_code'])) {
                $currency_code = $args['currency_code'];
            } else {
                $currency_code = $this->config->get('config_currency');
            }

            $product['nice_price'] = $this->currency->format($product['price'], $currency_code, $currency_value);

            $images = [];
            $zoomimages = [];
            $product['images'] = [];

            $thumb_width = $this->config->get('config_image_thumb_width', 300);
            $thumb_height = $this->config->get('config_image_thumb_height', 300);

            $thumb_zoomwidth = $this->config->get('config_zoomimage_thumb_width', 600);
            $thumb_zoomheight = $this->config->get('config_zoomimage_thumb_height', 600);
            $tmpImg = $product['image'];

            if (!empty($product['image'])) {
                $images[] = $this->model_tool_image->resize($product['image'], $thumb_width, $thumb_height);

                $zoomimages[] = $this->model_tool_image->resize($tmpImg, $thumb_zoomwidth, $thumb_zoomheight);
            } else {
                $images[] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
                $zoomimages[] = $this->model_tool_image->resize('placeholder.png', $thumb_zoomwidth, $thumb_zoomheight);
            }
            unset($product['image']);

            //$extra_images = $this->model_catalog_product->getProductImages($product['product_id']);
            $extra_images = $this->model_api_products->getProductImages($product['product_id']);
            if (!empty($extra_images)) {
                foreach ($extra_images as $extra_image) {
                    $images[] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);

                    $zoomimages[] = $this->model_tool_image->resize($extra_image['image'], $thumb_zoomwidth, $thumb_zoomheight);

                    //$product['images'][] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);
                }
            }

            //echo "<pre>";print_r($images);die;
            foreach ($images as $image) {
                $product['images'][] = $image;
            }

            foreach ($zoomimages as $zmimage) {
                $product['zoom_images'][] = $zmimage;
            }

            $json = $product;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = [])
    {
        $this->load->language('api/products');

        //echo "api/products";

        //echo "<pre>";print_r($args);die;
        $json = [];

        if (!isset($this->session->data['api_id']) || !isset($args['store_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else
         {
            $this->load->model('api/products');

            $product_data = [];

            $results = $this->model_api_products->getProducts($args);

            $product_total = $this->model_api_products->getTotalProducts($args);

            $product_data['product_total'] = $product_total;
            $product_data['products'] = [];

            $product_data['categories'] = $this->model_api_products->getCategories(0);

            //echo "<pre>";print_r($results);die;
            if (!empty($results)) {
                $this->load->model('tool/image');
                //$this->load->model('catalog/product');

                foreach ($results as $result) {
                    //echo $result['product_id'];
                    //$product = $this->model_catalog_product->getProduct($result['product_id']);
                    $product = $this->model_api_products->getProduct($result['product_id'], $args['store_id']);

                    if (is_array($product) && count($product) > 0) {
                        //echo "<pre>";print_r($product);die;
                        $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');
                        $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

                        $product['model'] = $result['model'];

                        $currency_value = false;

                        if (isset($args['currency_code'])) {
                            $currency_code = $args['currency_code'];
                        } else {
                            $currency_code = $this->config->get('config_currency');
                        }

                        $product['nice_price'] = $this->currency->format($product['price'], $currency_code, $currency_value);

                        if (0 == $product['special_price'] || 0.00 == $product['special_price'] || is_null($product['special_price'])) {
                            $product['special_price'] = $product['price'];
                            $product['nice_special_price'] = $this->currency->format($product['special_price'], $currency_code, $currency_value);
                        } else {
                            $product['nice_special_price'] = $this->currency->format($product['special_price'], $currency_code, $currency_value);
                        }

                        $images = [];
                        $product['images'] = [];

                        $thumb_width = $this->config->get('config_image_thumb_width', 300);
                        $thumb_height = $this->config->get('config_image_thumb_height', 300);

                        if (!empty($product['image'])) {
                            $images[] = $this->model_tool_image->resize($product['image'], $thumb_width, $thumb_height);
                        } else {
                            $images[] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
                        }
                        unset($product['image']);

                        //$extra_images = $this->model_catalog_product->getProductImages($result['product_id']);
                        $extra_images = $this->model_api_products->getProductImages($result['product_id']);

                        if (!empty($extra_images)) {
                            foreach ($extra_images as $extra_image) {
                                $images[] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);
                            }
                        }

                        foreach ($images as $image) {
                            /*if ($this->request->server['HTTPS']) {
                                $product['images'][] = str_replace($this->config->get('config_ssl'), '', $image);
                            } else {
                                $product['images'][] = str_replace($this->config->get('config_url'), '', $image);
                            }*/
                            $product['images'][] = $image;
                        }

                        $product_data['products'][] = $product;
                    }
                }
            }

            //echo "<pre>";print_r($product_data);die;
            $json = $product_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = [])
    {
        //echo "getTotals";

        $this->load->language('api/products');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $json = $this->model_api_products->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editProduct($args = [])
    {
        $this->load->language('api/products');
        $log = new Log('error.log');
        $log->write('editProduct  api');
        $log->write($args);
        //echo "editProduct";
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');
            // $args['id'] this should be store product id

            //echo "<pre>";print_r($args);die;

            $this->model_api_products->editProduct($args['id'], $args);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteProduct($args = [])
    {
        $log = new Log('error.log');
        $log->write('deleteProduct  api');
        $log->write($args);
        //echo "DeleteProduct";
        $this->load->language('api/products');

        //echo "<pre>";print_r($args['id']);die;
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $this->model_api_products->deleteProduct($args['id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
