<?php

class ControllerModuleFilter extends Controller
{
    public function index()
    {
        if (isset($this->request->get['category'])) {
            $parts = explode('_', (string) $this->request->get['category']);
        } else {
            $parts = [];
        }

        $category_id = end($parts);

        $this->load->model('assets/category');

        $category_info = $this->model_assets_category->getCategory($category_id);

        if ($category_info) {
            $this->load->language('module/filter');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['button_filter'] = $this->language->get('button_filter');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'category='.$this->request->get['category'].$url));

            if (isset($this->request->get['filter'])) {
                $data['filter_category'] = explode(',', $this->request->get['filter']);
            } else {
                $data['filter_category'] = [];
            }

            $this->load->model('assets/product');

            $data['filter_groups'] = [];

            $filter_groups = $this->model_assets_category->getCategoryFilters($category_id);

            if ($filter_groups) {
                foreach ($filter_groups as $filter_group) {
                    $childen_data = [];

                    foreach ($filter_group['filter'] as $filter) {
                        $filter_data = [
                            'filter_category_id' => $category_id,
                            'filter_filter' => $filter['filter_id'],
                        ];

                        $childen_data[] = [
                            'filter_id' => $filter['filter_id'],
                            'name' => $filter['name'].($this->config->get('config_product_count') ? ' ('.$this->model_assets_product->getTotalProducts($filter_data).')' : ''),
                        ];
                    }

                    $data['filter_groups'][] = [
                        'filter_group_id' => $filter_group['filter_group_id'],
                        'name' => $filter_group['name'],
                        'filter' => $childen_data,
                    ];
                }

                if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/module/filter.tpl')) {
                    return $this->load->view($this->config->get('config_template').'/template/module/filter.tpl', $data);
                } else {
                    return $this->load->view('default/template/module/filter.tpl', $data);
                }
            }
        }
    }
}
