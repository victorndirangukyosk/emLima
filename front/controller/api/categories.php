<?php

class ControllerApiCategories extends Controller {

    public function getCategory($args = []) {
        $this->load->language('api/categories');
        $json = [];

        if (!isset($this->session->data['api_id']) || !isset($args['id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');

            $category = $this->model_api_categories->getCategory($args);

            $category['name'] = html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8');
            $category['description'] = html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8');

            if ($this->request->server['HTTPS']) {
                $category['image'] = str_replace($this->config->get('config_ssl'), '', $category['image']);
            } else {
                $category['image'] = str_replace($this->config->get('config_url'), '', $category['image']);
            }

            $json = $category;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCategories($args = []) {
        $this->load->model('tool/image');
        $this->load->language('api/categories');
        unset($this->session->data['customer_category']);
        $json = [];

        // if (!isset($this->session->data['api_id'])) {
        //     $json['error'] = $this->language->get('error_permission');
        // } else 
        {
            $this->load->model('api/categories');
            $this->load->model('api/products');
            $this->load->model('assets/product');

            /* $category_data = array();

              $results = $this->model_api_categories->getCategories($args);

              if (!empty($results)) {
              foreach ($results as $result) {
              $result['name'] = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
              $result['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');

              if ($this->request->server['HTTPS']) {
              $result['image'] = str_replace($this->config->get('config_ssl'), '', $result['image']);
              } else {
              $result['image'] = str_replace($this->config->get('config_url'), '', $result['image']);
              }

              $category_data[] = $result;
              }
              }
             */


            // if ($args['parent'] != NULL && $args['parent']>0) {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $args['parent'] . "' AND status = '1'");
            // } else {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $args['customer_id']. "' AND status = '1'");
            // }
            // $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
            // echo "<pre>";print_r($_SESSION['customer_category']);die; 
            $newCat = [];

            $categories = $this->model_api_products->getCategories($args);
            if (isset($args['store_relation'])) {
                foreach ($categories as $cat) {
                    $cat['name'] = htmlspecialchars_decode($cat['name']);
                    $cat['thumb'] = $this->model_tool_image->resize($cat['image'], 300, 300);
                    $cat['thumb_two'] = $this->model_tool_image->resize($cat['image'], 316, 140);
                    $cat['description'] = htmlspecialchars_decode($cat['description']);
                    $products = NULL;
                    $data['filter_category_id'] = $cat['category_id'];
                    $products = $this->model_assets_product->getProductsForGrid($data);
                    $cat['products_count'] = count($products);
                    if (0 == $cat['parent_id']) {
                          if($cat['products_count']>0)
                        array_push($newCat, $cat);
                    }
                }
            } else {
                $categories_store = $this->model_api_categories->getCategoriesbyStore($args);
                $categories = array_filter($categories, function ($item) use ($categories_store) {
                    if (in_array($item['category_id'], $categories_store) && (0 == $item['parent_id'])) {
                        return true;
                    }

                    return false;
                });
            // echo "<pre>";print_r($categories);die; 
                
                foreach ($categories as $cat) {
                    $cat['name'] = htmlspecialchars_decode($cat['name']);
                    $cat['thumb'] = $this->model_tool_image->resize($cat['image'], 300, 300);
                    $cat['thumb_two'] = $this->model_tool_image->resize($cat['image'], 316, 140);
                    $cat['description'] = htmlspecialchars_decode($cat['description']);
                    $products = NULL;
                    $data['filter_category_id'] = $cat['category_id'];
                    $products = $this->model_assets_product->getProductsForGrid($data);
                    $cat['products_count'] = count($products);
                    if($cat['products_count']>0)//if no products, dont display that category
                    {
                    array_push($newCat, $cat);
                    }
                }
            }

            $product['categories'] = $newCat;
            $json = $product;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = []) {
        $this->load->language('api/categories');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');

            $json = $this->model_api_categories->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = []) {
        $vars = [];
        $vars['category'] = $args['id'];

        $this->load->controller('api/products/getproducts', $vars);
    }

}
