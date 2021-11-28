<?php

class ControllerCatalogHelp extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('catalog/help');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/help');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('catalog/help');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/help');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $category_id = $this->model_catalog_help->addHelp($this->request->post);

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
                $this->response->redirect($this->url->link('catalog/help/edit', 'help_id='.$category_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/help/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/help', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('catalog/help');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/help');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_catalog_help->editHelp($this->request->get['help_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('catalog/help/edit', 'help_id='.$this->request->get['help_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/help/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/help', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/help');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/help');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $category_id) {
                $this->model_catalog_help->deleteHelp($category_id);
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

            $this->response->redirect($this->url->link('catalog/help', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    public function repair()
    {
        $this->load->language('catalog/help');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/help');

        if ($this->validateRepair()) {
            $this->model_catalog_help->repairHelp();

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/help', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_question'])) {
            $filter_question = $this->request->get['filter_question'];
        } else {
            $filter_question = null;
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

        if (isset($this->request->get['filter_question'])) {
            $url .= '&filter_question='.urlencode(html_entity_decode($this->request->get['filter_question'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('catalog/help', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/help/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('catalog/help/delete', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['repair'] = $this->url->link('catalog/help/repair', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['helps'] = [];

        $filter_data = [
            'filter_question' => $filter_question,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        if (!empty($filter_question)) {
            $help_total = $this->model_catalog_help->getTotalHelpFilter($filter_data);
        } else {
            $help_total = $this->model_catalog_help->getTotalHelp();
        }

        $results = $this->model_catalog_help->getHelps($filter_data);

        foreach ($results as $result) {
            $data['helps'][] = [
                'help_id' => $result['help_id'],
                'category_id' => $result['category_id'],
                'question' => $result['question'],
                'sort_order' => $result['sort_order'],
                'edit' => $this->url->link('catalog/help/edit', 'token='.$this->session->data['token'].'&help_id='.$result['help_id'].$url, 'SSL'),
                'delete' => $this->url->link('catalog/help/delete', 'token='.$this->session->data['token'].'&help_id='.$result['help_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_question'] = $this->language->get('column_question');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_answer'] = $this->language->get('column_answer');
        $data['column_category'] = $this->language->get('column_category');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_rebuild'] = $this->language->get('button_rebuild');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
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

        $data['sort_question'] = $this->url->link('catalog/help', 'token='.$this->session->data['token'].'&sort=question'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/help', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('catalog/help', 'token='.$this->session->data['token'].'&sort=sort_order'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_question'])) {
            $url .= '&filter_question='.urlencode(html_entity_decode($this->request->get['filter_question'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $help_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/help', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['filter_question'] = $filter_question;
        $data['filter_status'] = $filter_status;
        $data['token'] = $this->session->data['token'];
        $data['results'] = sprintf($this->language->get('text_pagination'), ($help_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($help_total - $this->config->get('config_limit_admin'))) ? $help_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $help_total, ceil($help_total / $this->config->get('config_limit_admin')));
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/help_list.tpl', $data));
    }

    protected function getForm()
    {
        $data = $this->language->all();
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['help_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_layout'] = $this->language->get('entry_layout');

        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_column'] = $this->language->get('help_column');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_design'] = $this->language->get('tab_design');

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

        if (isset($this->error['question'])) {
            $data['error_question'] = $this->error['question'];
        } else {
            $data['error_question'] = [];
        }

        if (isset($this->error['answer'])) {
            $data['error_answer'] = $this->error['answer'];
        } else {
            $data['error_answer'] = [];
        }

        $url = '';

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_question='.urlencode(html_entity_decode($this->request->get['filter_question'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('catalog/help', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['help_id'])) {
            $data['action'] = $this->url->link('catalog/help/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/help/edit', 'token='.$this->session->data['token'].'&help_id='.$this->request->get['help_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/help', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['help_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            //$help_info = $this->model_catalog_help->getHelp($this->request->get['help_id']);
            $data['help'] = $this->model_catalog_help->getHelpDetails($this->request->get['help_id']);
        }

        //echo "<pre>";print_r($data['help']);die;
        $data['token'] = $this->session->data['token'];

        $data['categories'] = $this->model_catalog_help->getCategories();

        // Text Editor
        $data['text_editor'] = $this->config->get('config_text_editor', 'tinymce');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/help_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/help')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['help_category'] as $language_id => $value) {
            // if (empty($value['icon'])) {
            //     $this->error['icon'][$language_id] = $this->language->get('error_icon');
            // }

            if ((utf8_strlen($value['question']) < 2) || (utf8_strlen($value['question']) > 255)) {
                $this->error['question'] = $this->language->get('error_question');
            }

            if (empty($value['answer'])) {
                $this->error['answer'] = $this->language->get('error_answer');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/help')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateRepair()
    {
        if (!$this->user->hasPermission('modify', 'catalog/help')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_question'])) {
            $this->load->model('catalog/help');

            $filter_data = [
                'filter_question' => $this->request->get['filter_question'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5,
            ];

            $json = $this->model_catalog_help->getHelps($filter_data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
