<?php

class ControllerApiCustomerCategory extends Controller {

   
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
            $this->load->model('assets/category');
 
            $newCat = [];

            // $categories = $this->model_api_products->getCategories($args);

            $new_categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);

            $customer_categories = $this->model_assets_category->getCustomerCategoryById(ACTIVE_STORE_ID, 0);
           
            foreach ($customer_categories as $customer_category) {
               $new_categories[] = $customer_category;
           }
           $new_categories = array_map("unserialize", array_unique(array_map("serialize", $new_categories)));

           
            if (isset($args['store_relation'])) {
                // foreach ($categories as $cat) {
                    foreach ($new_categories as $cat) {
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
                // $categories_store = $this->model_api_categories->getCategoriesbyStore($args);
                // $categories = array_filter($categories, function ($item) use ($categories_store) {
                //     if (in_array($item['category_id'], $categories_store) && (0 == $item['parent_id'])) {
                //         return true;
                //     }

                //     return false;
                // });
            // echo "<pre>";print_r($categories);die; 
                
                // foreach ($categories as $cat) {
                    foreach ($new_categories as $cat) {
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

    

}
