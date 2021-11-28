<?php

class ControllerCommonColumnLeft extends Controller
{
    public function index()
    {
        $this->load->model('design/layout');

        if (isset($this->request->get['path'])) {
            $path = (string) $this->request->get['path'];
        } else {
            $path = 'common/home';
        }

        $layout_id = 0;

        if ('product/category' == $path && isset($this->request->get['category'])) {
            $this->load->model('assets/category');

            $category = explode('_', (string) $this->request->get['category']);

            $layout_id = $this->model_assets_category->getCategoryLayoutId(end($category));
        }

        if ('product/product' == $path && isset($this->request->get['product_id'])) {
            $this->load->model('assets/product');

            $layout_id = $this->model_assets_product->getProductLayoutId($this->request->get['product_id']);
        }

        if ('product/manufacturer/info' == $path && isset($this->request->get['manufacturer_id'])) {
            $this->load->model('assets/manufacturer');

            $layout_id = $this->model_assets_manufacturer->getManufacturerLayoutId($this->request->get['manufacturer_id']);
        }

        if ('information/information' == $path && isset($this->request->get['information_id'])) {
            $this->load->model('assets/information');

            $layout_id = $this->model_assets_information->getInformationLayoutId($this->request->get['information_id']);
        }

        if (!$layout_id) {
            $layout_id = $this->model_design_layout->getLayout($path);
        }

        if (!$layout_id) {
            $layout_id = $this->config->get('config_layout_id');
        }

        $this->load->model('extension/module');

        $data['modules'] = [];

        $modules = $this->model_design_layout->getLayoutModules($layout_id, 'column_left');

        foreach ($modules as $module) {
            $part = explode('.', $module['code']);

            if (isset($part[0]) && $this->config->get($part[0].'_status')) {
                $data['modules'][] = $this->load->controller('module/'.$part[0]);
            }

            if (isset($part[1])) {
                $setting_info = $this->model_extension_module->getModule($part[1]);

                if ($setting_info && $setting_info['status']) {
                    $data['modules'][] = $this->load->controller('module/'.$part[0], $setting_info);
                }
            }
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/column_left.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/column_left.tpl', $data);
        } else {
            return $this->load->view('default/template/common/column_left.tpl', $data);
        }
    }
}
