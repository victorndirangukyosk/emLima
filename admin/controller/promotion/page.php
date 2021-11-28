<?php

class ControllerPromotionPage extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('promotion/page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('promotion/page');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('promotion/page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('promotion/page');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            //echo "<pre>";print_r($this->request->post);die;
            $product_collection_id = $this->model_promotion_page->addProductCollection($this->request->post);
            //echo "<pre>";print_r($this->request->post);die;
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
                $this->response->redirect($this->url->link('promotion/page/edit', 'product_collection_id='.$product_collection_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('promotion/page/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('promotion/page', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('promotion/page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('promotion/page');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_promotion_page->editProductCollection($this->request->get['product_collection_id'], $this->request->post);

            //echo "<pre>";print_r($this->request->post);die;
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
                $this->response->redirect($this->url->link('promotion/page/edit', 'product_collection_id='.$this->request->get['product_collection_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('promotion/page/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('promotion/page', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('promotion/page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('promotion/page');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_collection_id) {
                $this->model_promotion_page->deleteProductCollection($product_collection_id);
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

            $this->response->redirect($this->url->link('promotion/page', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = null;
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = null;
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }

        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

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

        if (isset($this->request->post['filter_store_id'])) {
            $data['filter_store_id'] = $this->request->post['filter_store_id'];
        } elseif (!empty($product_collection_info)) {
            $data['filter_store_id'] = $product_collection_info['filter_store_id']; //get filter_store_idname by id
        } else {
            $data['filter_store_id'] = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store='.$this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id='.$this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.$this->request->get['filter_status'];
        }

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
            'href' => $this->url->link('promotion/page', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('promotion/page/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('promotion/page/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['coupons'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_store' => $filter_store_id,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        if (!empty($filter_name) || !empty($filter_date_start) || !empty($filter_date_end) || !empty($filter_store) || !empty($filter_status)) {
            $product_collection_total = $this->model_promotion_page->getTotalProductCollectionFilter($filter_data);
        } else {
            $product_collection_total = $this->model_promotion_page->getTotalProductCollection();
        }

        $results = $this->model_promotion_page->getProductCollection($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['product_collections'][] = [
                'product_collection_id' => $result['product_collection_id'],
                'name' => $result['name'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit' => $this->url->link('promotion/page/edit', 'token='.$this->session->data['token'].'&product_collection_id='.$result['product_collection_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_store_name'] = $this->language->get('column_store_name');
        $data['column_code'] = $this->language->get('column_code');
        $data['column_discount'] = $this->language->get('column_discount');
        $data['column_store'] = $this->language->get('column_store');
        $data['column_date_start'] = $this->language->get('column_date_start');
        $data['column_date_end'] = $this->language->get('column_date_end');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

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

        $data['sort_name'] = $this->url->link('promotion/page', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        $data['sort_discount'] = $this->url->link('promotion/page', 'token='.$this->session->data['token'].'&sort=discount'.$url, 'SSL');
        $data['sort_date_start'] = $this->url->link('promotion/page', 'token='.$this->session->data['token'].'&sort=date_start'.$url, 'SSL');
        $data['sort_date_end'] = $this->url->link('promotion/page', 'token='.$this->session->data['token'].'&sort=date_end'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('promotion/page', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_collection_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('promotion/page', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_collection_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_collection_total - $this->config->get('config_limit_admin'))) ? $product_collection_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_collection_total, ceil($product_collection_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_store'] = $filter_store;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('promotion/page_list.tpl', $data));
    }

    protected function getForm()
    {
        $data = $this->language->all();

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['product_collection_id']) ? 'Add' : 'Edit';
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_percent'] = $this->language->get('text_percent');
        $data['text_amount'] = $this->language->get('text_amount');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_discount'] = $this->language->get('entry_discount');
        $data['entry_logged'] = $this->language->get('entry_logged');
        $data['entry_shipping'] = $this->language->get('entry_shipping');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_uses_total'] = $this->language->get('entry_uses_total');
        $data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['help_code'] = $this->language->get('help_code');
        $data['help_type'] = $this->language->get('help_type');
        $data['help_logged'] = $this->language->get('help_logged');
        $data['help_total'] = $this->language->get('help_total');
        $data['help_category'] = $this->language->get('help_category');
        $data['help_product'] = $this->language->get('help_product');
        $data['help_uses_total'] = $this->language->get('help_uses_total');
        $data['help_uses_customer'] = $this->language->get('help_uses_customer');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_history'] = $this->language->get('tab_history');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['product_collection_id'])) {
            $data['product_collection_id'] = $this->request->get['product_collection_id'];
        } else {
            $data['product_collection_id'] = 0;
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = [];
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('promotion/page', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['product_collection_id'])) {
            $data['action'] = $this->url->link('promotion/page/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('promotion/page/edit', 'token='.$this->session->data['token'].'&product_collection_id='.$this->request->get['product_collection_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('promotion/page', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['product_collection_id']) && ('POST' != !$this->request->server['REQUEST_METHOD'])) {
            $product_collection_info = $this->model_promotion_page->getProductCollectionDetails($this->request->get['product_collection_id']);
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['product_collection_description'])) {
            $data['product_collection_description'] = $this->request->post['product_collection_description'];
        } elseif (isset($this->request->get['product_collection_id'])) {
            $data['product_collection_description'] = $this->model_promotion_page->getProductCollectionDescriptions($this->request->get['product_collection_id']);
        } else {
            $data['product_collection_description'] = [];
        }

        //echo "<pre>";print_r($data['product_collection_description']);die;

        //echo "<pre>";print_r($product_collection_info);die;
        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (!empty($product_collection_info)) {
            $data['seo_url'] = $product_collection_info['seo_url'];
        } else {
            $data['seo_url'] = [];
        }

        if (isset($this->request->post['product_collection_product'])) {
            $products = $this->request->post['product_collection_product'];
        } elseif (isset($this->request->get['product_collection_id'])) {
            $products = $this->model_promotion_page->getProductCollectionProducts($this->request->get['product_collection_id']);
        } else {
            $products = [];
        }

        //echo "<pre>";print_r($products);die;
        $this->load->model('catalog/product');

        $data['product_collection_products'] = [];

        foreach ($products as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                $data['product_collection_products'][] = [
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                ];
            }
        }
        //echo "<pre>";print_r($data['product_collection_product']);die;

        if (isset($this->request->post['product_collection_store'])) {
            $categories = $this->request->post['product_collection_store'];
        } elseif (isset($this->request->get['product_collection_id'])) {
            $categories = $this->model_promotion_page->getCouponCategories($this->request->get['product_collection_id']);
        } else {
            $categories = [];
        }

        $this->load->model('catalog/category');

        $data['product_collection_store'] = [];

        //echo "<pre>";print_r($data);die;

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_collection_info)) {
            $data['status'] = $product_collection_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('promotion/page_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'promotion/page')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('catalog/url_alias');

        foreach ($this->request->post['seo_url'] as $language_id => $value) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($value, $language_id);

            //echo "<pre>";print_r($this->request->post);die;
            //echo "<pre>";print_r($url_alias_info);die;
            if ($url_alias_info && isset($this->request->get['product_collection_id']) && $url_alias_info['query'] != 'product_collection_id='.$this->request->get['product_collection_id']) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['product_collection_id'])) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }
        }

        foreach ($this->request->post['product_collection_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 362)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'promotion/page')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function history()
    {
        $this->load->language('promotion/page');

        $this->load->model('promotion/page');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_date_added'] = $this->language->get('column_date_added');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['histories'] = [];

        $results = $this->model_promotion_page->getCouponHistories($this->request->get['product_collection_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = [
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'amount' => $result['amount'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $history_total = $this->model_promotion_page->getTotalCouponHistories($this->request->get['product_collection_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('promotion/page/history', 'token='.$this->session->data['token'].'&product_collection_id='.$this->request->get['product_collection_id'].'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('promotion/page_history.tpl', $data));
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('promotion/page');

            $filter_data = [
                'filter_name' => empty($this->request->get['filter_name']) ? '' : $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_promotion_page->getCoupons($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'product_collection_id' => $result['product_collection_id'],
                    'name' => $result['name'],
                    'code' => $result['code'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
