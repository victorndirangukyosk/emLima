<?php

class ControllerApiCategories extends Controller
{

    public function getCategory($args = array())
    {
        $this->load->language('api/categories');
        $json = array();

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
    
    public function getCategories($args = array())
    {
		$this->load->model('tool/image');
        $this->load->language('api/categories');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');
            $this->load->model('api/products');


            /*$category_data = array();

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

            $newCat = [];

            $categories = $this->model_api_products->getCategories($args);
            if(isset($args['store_relation'])){
                foreach ($categories as $cat) {
                    $cat['name'] = htmlspecialchars_decode($cat['name']);
                    $cat['thumb'] = $this->model_tool_image->resize($cat['image'], 300, 300);
                    $cat['description'] = htmlspecialchars_decode($cat['description']);
                    if($cat['parent_id']==0){
                    array_push($newCat, $cat);
                    }
                }
            }else{
                $categories_store = $this->model_api_categories->getCategoriesbyStore($args);
                $categories = array_filter($categories, function ($item) use ($categories_store) {
                    if(in_array($item['category_id'], $categories_store) && ($item['parent_id']==0))
                    {
                    return true;
                    }
                    return false;
                });
                foreach ($categories as $cat) {
                    $cat['name'] = htmlspecialchars_decode($cat['name']);
                    $cat['thumb'] = $this->model_tool_image->resize($cat['image'], 300, 300);
                    $cat['description'] = htmlspecialchars_decode($cat['description']);
                    array_push($newCat, $cat);
                }
            }

            $product['categories'] = $newCat;
            $json = $product;

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/categories');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');

            $json = $this->model_api_categories->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = array())
    {
        $vars = array();
        $vars['category'] = $args['id'];

        $this->load->controller('api/products/getproducts', $vars);
    }
}
