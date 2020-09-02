<?php

class ControllerCommonSearch extends Controller
{
    public function index()
    {
        $this->load->language('common/search');

        $data['text_search'] = $this->language->get('text_search');

        if (isset($this->request->get['search'])) {
            $data['search'] = $this->request->get['search'];
        } else {
            $data['search'] = '';
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/search.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/search.tpl', $data);
        } else {
            return $this->load->view('default/template/common/search.tpl', $data);
        }
    }

    public function liveSearch()
    {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('assets/product');
            $this->load->model('tool/image');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = [
                'filter_name' => $filter_name, ];

            $results = $this->model_assets_product->getProducts($filter_data);

            foreach ($results as $result) {
                $option_data = [];

                $json[] = [
                    'product_id' => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'href' => $this->url->link('product/product', '&product_id='.$result['product_id']),
                    'searchall' => $this->url->link('product/search', '&search='.$filter_name),
                    'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
                    'image' => $this->model_tool_image->resize($result['image'], '45', '45'),
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
