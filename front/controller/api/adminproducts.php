<?php

class ControllerApiAdminProducts extends Controller
{
    public function getAdminProduct($args = [])
    {
        $this->load->language('api/products');

        $json = [];

        //echo "api/adminproduct";die;

        //echo $args['id'];
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/products');
            //$this->load->model('catalog/product');

            //$product = $this->model_catalog_product->getProduct($args['id']);
            //echo $args['id'];
            $product = $this->model_api_products->getAdminProduct($args['id']);

            //print_r($product);die;
            //echo ("product");
            $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');
            $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

            $product['product_description'] = $this->model_api_products->getProductDescriptions($args['id']);

            //$product['categories'] = $this->model_api_products->getCategories( 0 );

            $category = $this->model_api_products->getProductCategories($args['id']);

            $product['category'] = $category;

            //echo "<pre>";print_r($product);die;
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

            //$extra_images = $this->model_catalog_product->getProductImages($product['product_id']);
            $extra_images = $this->model_api_products->getProductImages($product['product_id']);
            if (!empty($extra_images)) {
                foreach ($extra_images as $extra_image) {
                    $images[] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);
                }
            }

            //echo "<pre>";print_r($images);die;
            foreach ($images as $image) {
                $product['images'][] = $images;
            }

            $json = $product;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAdminProducts($args = [])
    {
        //echo "admin product";die;
        $this->load->language('api/products');

        //echo "api/products";

        //echo "<pre>";print_r($args);die;
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $product_data = [];

            $results = $this->model_api_products->getAdminProducts($args);

            $product_total = $this->model_api_products->getTotalAdminProducts($args);

            $data['products'] = [];

            //$product_data['categories'] = $this->model_api_products->getCategories( 0 );

            $product_data['product_total'] = $product_total;

            //echo "<pre>";print_r($product_data);die;
            if (!empty($results)) {
                $this->load->model('tool/image');
                //$this->load->model('catalog/product');

                foreach ($results as $result) {
                    //echo $result['product_id'];
                    //$product = $this->model_catalog_product->getProduct($result['product_id']);
                    $product = $this->model_api_products->getAdminProduct($result['product_id']);

                    $category = $this->model_api_products->getProductCategories($result['product_id']);

                    if (is_array($product) && count($product) > 0) {
                        //echo "<pre>";print_r($product);die;
                        $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');
                        $product['category'] = $category;
                        $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

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
                            $product['images'][] = $images;
                        }

                        $product_data['products'][] = $product;
                    }
                }
            } else {
                $product_data['products'] = [];
            }

            $json = $product_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAdminTotals($args = [])
    {
        //echo "getAdminTotals";

        $this->load->language('api/products');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $json = $this->model_api_products->getAdminTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editAdminProduct($args = [])
    {
        $log = new Log('error.log');
        $log->write('editAdminProduct');

        $this->load->language('api/products');

        //echo "editAdminProduct";
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');
            // $args['id'] this should be store product id

            $log->write($args);

            //echo "<pre>";print_r($args);die;

            $this->model_api_products->editAdminProduct($args['id'], $args);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteAdminProduct($args = [])
    {
        //echo "DeleteProduct";
        $this->load->language('api/products');

        //echo "<pre>";print_r($args['id']);die;
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $this->model_api_products->deleteAdminProduct($args['id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
