<?php

class ControllerModuleCategory extends Controller
{
    public function index()
    {
        $this->load->language('module/category');

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->request->get['category'])) {
            $parts = explode('_', (string) $this->request->get['category']);
        } else {
            $parts = [];
        }

        if (isset($parts[0])) {
            $data['category_id'] = $parts[0];
        } else {
            $data['category_id'] = 0;
        }

        if (isset($parts[1])) {
            $data['child_id'] = $parts[1];
        } else {
            $data['child_id'] = 0;
        }

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $data['categories'] = [];

        $categories = $this->model_assets_category->getCategories(0);

        foreach ($categories as $category) {
            $children_data = [];

            if ($category['category_id'] == $data['category_id']) {
                $children = $this->model_assets_category->getCategories($category['category_id']);

                foreach ($children as $child) {
                    $filter_data = ['filter_category_id' => $child['category_id'], 'filter_sub_category' => true];

                    $children_data[] = [
                        'category_id' => $child['category_id'],
                        'name' => $child['name'].($this->config->get('config_product_count') ? ' ('.$this->model_assets_product->getTotalProducts($filter_data).')' : ''),
                        'href' => $this->url->link('product/category', 'category='.$category['category_id'].'_'.$child['category_id']),
                    ];
                }
            }

            $filter_data = [
                'filter_category_id' => $category['category_id'],
                'filter_sub_category' => true,
            ];

            $data['categories'][] = [
                'category_id' => $category['category_id'],
                'name' => $category['name'].($this->config->get('config_product_count') ? ' ('.$this->model_assets_product->getTotalProducts($filter_data).')' : ''),
                'children' => $children_data,
                'href' => $this->url->link('product/category', 'category='.$category['category_id']),
            ];
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/module/category.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/module/category.tpl', $data);
        } else {
            return $this->load->view('default/template/module/category.tpl', $data);
        }
    }
}
