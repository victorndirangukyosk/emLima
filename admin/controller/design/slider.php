<?php

class ControllerDesignSlider extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('design/slider');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/slider');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('design/slider');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/slider');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $slider_id = $this->model_design_slider->addslider($this->request->post);

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
                $this->response->redirect($this->url->link('design/slider/edit', 'slider_id='.$slider_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/slider/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('design/slider', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('design/slider');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/slider');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            //echo "<pre>";print_r($this->request->post);die;

            $this->model_design_slider->editslider($this->request->get['slider_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('design/slider/edit', 'slider_id='.$this->request->get['slider_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/slider/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('design/slider', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('design/slider');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/slider');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $slider_id) {
                $this->model_design_slider->deleteslider($slider_id);
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

            $this->response->redirect($this->url->link('design/slider', 'token='.$this->session->data['token'].$url, 'SSL'));
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
        } elseif (!empty($slider_info)) {
            $data['filter_store_id'] = $slider_info['filter_store_id']; //get filter_store_idname by id
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
            'href' => $this->url->link('design/slider', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('design/slider/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('design/slider/delete', 'token='.$this->session->data['token'].$url, 'SSL');

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
            $slider_total = $this->model_design_slider->getTotalslidersFilter($filter_data);
        } else {
            $slider_total = $this->model_design_slider->getTotalsliders();
        }

        $results = $this->model_design_slider->getsliders($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['sliders'][] = [
                'slider_id' => $result['slider_id'],
                'name' => $result['name'],
                'store_name' => $result['store_name'],
                'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
                'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit' => $this->url->link('design/slider/edit', 'token='.$this->session->data['token'].'&slider_id='.$result['slider_id'].$url, 'SSL'),
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

        $data['sort_name'] = $this->url->link('design/slider', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        $data['sort_discount'] = $this->url->link('design/slider', 'token='.$this->session->data['token'].'&sort=discount'.$url, 'SSL');
        $data['sort_date_start'] = $this->url->link('design/slider', 'token='.$this->session->data['token'].'&sort=date_start'.$url, 'SSL');
        $data['sort_date_end'] = $this->url->link('design/slider', 'token='.$this->session->data['token'].'&sort=date_end'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('design/slider', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $slider_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('design/slider', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($slider_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($slider_total - $this->config->get('config_limit_admin'))) ? $slider_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $slider_total, ceil($slider_total / $this->config->get('config_limit_admin')));

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

        $this->response->setOutput($this->load->view('design/slider_list.tpl', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['slider_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
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

        if (isset($this->request->get['slider_id'])) {
            $data['slider_id'] = $this->request->get['slider_id'];
        } else {
            $data['slider_id'] = 0;
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

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

        if (isset($this->error['date_start'])) {
            $data['error_date_start'] = $this->error['date_start'];
        } else {
            $data['error_date_start'] = '';
        }

        if (isset($this->error['date_end'])) {
            $data['error_date_end'] = $this->error['date_end'];
        } else {
            $data['error_date_end'] = '';
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
            'href' => $this->url->link('design/slider', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['slider_id'])) {
            $data['action'] = $this->url->link('design/slider/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('design/slider/edit', 'token='.$this->session->data['token'].'&slider_id='.$this->request->get['slider_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('design/slider', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['slider_id']) && ('POST' != !$this->request->server['REQUEST_METHOD'])) {
            $slider_info = $this->model_design_slider->getslider($this->request->get['slider_id']);
        }

        //echo "<pre>";print_r($slider_info);die;

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($slider_info)) {
            $data['name'] = $slider_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['store'])) {
            $data['store'] = $this->request->post['store'];
        } elseif (!empty($slider_info)) {
            $data['store'] = $slider_info['store_name']; //get storename by id
        } else {
            $data['store'] = '';
        }

        if (isset($this->request->post['store_id'])) {
            $data['store_id'] = $this->request->post['store_id'];
        } elseif (!empty($slider_info)) {
            $data['store_id'] = $slider_info['store_id']; //get store_idname by id
        } else {
            $data['store_id'] = '';
        }

        $this->load->model('tool/image');

        /*if ( isset( $this->request->post['image'] ) && is_file( DIR_IMAGE . $this->request->post['image'] ) ) {
            $data['thumb'] = $this->model_tool_image->resize( $this->request->post['image'], 100, 100 );
        } elseif ( !empty( $product_info ) && is_file( DIR_IMAGE . $slider_info['image'] ) ) {
            $data['thumb'] = $this->model_tool_image->resize( $slider_info['image'], 100, 100 );
        } else {
            $data['thumb'] = $this->model_tool_image->resize( 'no_image.png', 100, 100 );
        }*/

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['slider_product'])) {
            $sliderData = $this->request->post['slider_product'];
        } elseif (isset($this->request->get['slider_id'])) {
            $sliderData = $this->model_design_slider->getSliderData($this->request->get['slider_id']);
        } else {
            $sliderData = [];
        }

        //echo "<pre>";print_r($sliderData);die;
        $this->load->model('catalog/product');

        $data['slider_images'] = [];

        foreach ($sliderData as $slider_image) {
            if (is_file(DIR_IMAGE.$slider_image['image'])) {
                $image = $slider_image['image'];
                $thumb = $slider_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['slider_images'][] = [
                'image' => $image,
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'link' => $slider_image['link'],
                'id' => $slider_image['id'],
            ];
        }

        //echo "<pre>";print_r($data['slider_product']);die;

        if (isset($this->request->post['slider_store'])) {
            $categories = $this->request->post['slider_store'];
        } elseif (isset($this->request->get['slider_id'])) {
            $categories = $this->model_design_slider->getCouponCategories($this->request->get['slider_id']);
        } else {
            $categories = [];
        }

        $this->load->model('catalog/category');

        $data['product_collection_ids'] = $this->model_design_slider->getProductCollection();
        $data['array_product_collection_ids'] = [];

        foreach ($data['product_collection_ids'] as $key => $value) {
            array_push($data['array_product_collection_ids'], $value['product_collection_id']);
        }
        //echo "<pre>";print_r($data['product_collection_ids']);die;
        $data['slider_store'] = [];

        if (isset($this->request->post['date_start'])) {
            $data['date_start'] = $this->request->post['date_start'];
        } elseif (!empty($slider_info)) {
            $data['date_start'] = ('0000-00-00' != $slider_info['date_start'] ? $slider_info['date_start'] : '');
        } else {
            $data['date_start'] = date('Y-m-d', time());
        }

        if (isset($this->request->post['date_end'])) {
            $data['date_end'] = $this->request->post['date_end'];
        } elseif (!empty($slider_info)) {
            $data['date_end'] = ('0000-00-00' != $slider_info['date_end'] ? $slider_info['date_end'] : '');
        } else {
            $data['date_end'] = date('Y-m-d', strtotime('+1 month'));
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($slider_info)) {
            $data['status'] = $slider_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/slider_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'design/slider')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        /*if ((utf8_strlen($this->request->post['store_name']) < 3) || (utf8_strlen($this->request->post['store_name']) > 128)) {
            $this->error['store_name'] = $this->language->get('error_store_name');
        }*/

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'design/slider')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function history()
    {
        $this->load->language('design/slider');

        $this->load->model('design/slider');

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

        $results = $this->model_design_slider->getCouponHistories($this->request->get['slider_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = [
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'amount' => $result['amount'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $history_total = $this->model_design_slider->getTotalCouponHistories($this->request->get['slider_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('design/slider/history', 'token='.$this->session->data['token'].'&slider_id='.$this->request->get['slider_id'].'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('design/slider_history.tpl', $data));
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('design/slider');

            $filter_data = [
                'filter_name' => empty($this->request->get['filter_name']) ? '' : $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_design_slider->getCoupons($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'slider_id' => $result['slider_id'],
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

    public function deleteImage()
    {
        if (!$this->user->hasPermission('modify', 'design/slider') || $this->user->isVendor()) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else {
            $id = $this->request->get['id'];
            $this->load->model('design/slider');
            $json = $this->model_design_slider->deleteImage($id);
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }
}
