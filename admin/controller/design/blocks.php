<?php

class ControllerDesignBlocks extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('design/blocks');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/blocks');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('design/blocks');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/blocks');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $block_id = $this->model_design_blocks->addBlock($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/blocks/edit', 'block_id='.$block_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/blocks/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('design/blocks', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('design/blocks');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/blocks');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_design_blocks->editBlock($this->request->get['block_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/blocks/edit', 'block_id='.$this->request->get['block_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/blocks/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('design/blocks', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('design/blocks');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/blocks');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $block_id) {
                $this->model_design_blocks->deleteBlock($block_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('design/blocks', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('design/blocks', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('design/blocks/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('design/blocks/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['blocks'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $block_total = $this->model_design_blocks->getTotalBlocks();

        $results = $this->model_design_blocks->getBlocks($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['blocks'][] = [
                'block_id' => $result['block_id'],
                'sort_order' => $result['sort_order'],
                'title' => $result['title'].(($result['block_id'] == $this->config->get('config_block_id')) ? $this->language->get('text_default') : null),
                'edit' => $this->url->link('design/blocks/edit', 'token='.$this->session->data['token'].'&block_id='.$result['block_id'].$url, 'SSL'),
            ];
        }

        $data['action'] = $this->url->link('design/blocks/saveheading', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['entry_tagline'] = $this->language->get('entry_tagline');

        $data['entry_sco_title'] = $this->language->get('entry_sco_title');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('design/blocks', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $block_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('design/blocks', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($block_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($block_total - $this->config->get('config_limit_admin'))) ? $block_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $block_total, ceil($block_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/block_list.tpl', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['block_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['entry_name'] = $this->language->get('entry_name');

        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_image'] = $this->language->get('entry_image');

        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = [];
        }

        if (isset($this->error['message'])) {
            $data['error_message'] = $this->error['message'];
        } else {
            $data['error_message'] = [];
        }

        $url = '';

        if (isset($this->request->post['image'])) {
            $image = $this->request->post['image'];
        } elseif (!empty($offer_info)) {
            $image = $offer_info['image'];
        } else {
            $image = '';
        }

        $this->load->model('tool/image');

        if (is_file(DIR_IMAGE.$image)) {
            $data['image'] = $image;
            $data['thumb'] = $this->model_tool_image->resize($image, 100, 100);
        } else {
            $data['image'] = $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('design/blocks', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['block_id'])) {
            $data['action'] = $this->url->link('design/blocks/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('design/blocks/edit', 'token='.$this->session->data['token'].'&block_id='.$this->request->get['block_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('design/blocks', 'token='.$this->session->data['token'].$url, 'SSL');

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['block'])) {
            $data['block'] = $this->request->post['block'];
        } elseif (isset($this->request->get['block_id'])) {
            $data['block'] = $this->model_design_blocks->getBlock($this->request->get['block_id']);
        } else {
            $data['block'] = [];
        }

        //echo "<pre>";print_r($data['block']);print_r($data['thumb']);die;

        // Text Editor
        $data['text_editor'] = $this->config->get('config_text_editor');

        if (empty($data['text_editor'])) {
            $data['text_editor'] = 'tinymce';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/block_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'design/blocks')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        /*if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 64)) {
            $this->error['title'] = $this->language->get('error_title');
        }*/
        foreach ($this->request->post['block'] as $language_id => $value) {
            if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 100)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['description']) < 3)) {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'design/blocks')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/store');
        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $block_id) {
            if ($this->config->get('config_block_id') == $block_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            if ($this->config->get('config_download_status_id') == $block_id) {
                $this->error['warning'] = $this->language->get('error_download');
            }

            $store_total = $this->model_setting_store->getTotalStoresByOrderStatusId($block_id);

            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }

            $order_total = $this->model_sale_order->getTotalOrderHistoriesByOrderStatusId($block_id);

            if ($order_total) {
                $this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
            }
        }

        return !$this->error;
    }

    protected function saveheading()
    {
        if (!$this->user->hasPermission('modify', 'design/blocks')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/store');
        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $block_id) {
            if ($this->config->get('config_block_id') == $block_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            if ($this->config->get('config_download_status_id') == $block_id) {
                $this->error['warning'] = $this->language->get('error_download');
            }

            $store_total = $this->model_setting_store->getTotalStoresByOrderStatusId($block_id);

            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }

            $order_total = $this->model_sale_order->getTotalOrderHistoriesByOrderStatusId($block_id);

            if ($order_total) {
                $this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
            }
        }

        return !$this->error;
    }
}
