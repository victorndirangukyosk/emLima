<?php

class ControllerCatalogRecipeCategory extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('catalog/recipe_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe_category');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('catalog/recipe_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe_category');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $category_id = $this->model_catalog_recipe_category->addCategory($this->request->post);

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
                $this->response->redirect($this->url->link('catalog/recipe_category/edit', 'category_id='.$category_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/recipe_category/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('catalog/recipe_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe_category');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_catalog_recipe_category->editCategory($this->request->get['category_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('catalog/recipe_category/edit', 'category_id='.$this->request->get['category_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/recipe_category/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/recipe_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe_category');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $category_id) {
                $this->model_catalog_recipe_category->deleteCategory($category_id);
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

            $this->response->redirect($this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    public function repair()
    {
        $this->load->language('catalog/recipe_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe_category');

        if ($this->validateRepair()) {
            $this->model_catalog_recipe_category->repairCategories();

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'], 'SSL'));
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/recipe_category/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('catalog/recipe_category/delete', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['repair'] = $this->url->link('catalog/recipe_category/repair', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['categories'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        if (!empty($filter_name) || !empty($filter_status)) {
            $recipe_category_total = $this->model_catalog_recipe_category->getTotalCategoriesFilter($filter_data);
        } else {
            $recipe_category_total = $this->model_catalog_recipe_category->getTotalCategories();
        }

        $results = $this->model_catalog_recipe_category->getCategories($filter_data);

        foreach ($results as $result) {
            $data['categories'][] = [
                'category_id' => $result['category_id'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
                'edit' => $this->url->link('catalog/recipe_category/edit', 'token='.$this->session->data['token'].'&category_id='.$result['category_id'].$url, 'SSL'),
                'delete' => $this->url->link('catalog/recipe_category/delete', 'token='.$this->session->data['token'].'&category_id='.$result['category_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');

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

        $data['sort_name'] = $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].'&sort=sort_order'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

        $pagination = new Pagination();
        $pagination->total = $recipe_category_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;
        $data['token'] = $this->session->data['token'];

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recipe_category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recipe_category_total - $this->config->get('config_limit_admin'))) ? $recipe_category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recipe_category_total, ceil($recipe_category_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/recipe_category_list.tpl', $data));
    }

    protected function getForm()
    {
        $data = $this->language->all();
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_layout'] = $this->language->get('entry_layout');

        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_column'] = $this->language->get('help_column');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_design'] = $this->language->get('tab_design');

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
            $data['error_name'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['category_id'])) {
            $data['action'] = $this->url->link('catalog/recipe_category/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/recipe_category/edit', 'token='.$this->session->data['token'].'&category_id='.$this->request->get['category_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/recipe_category', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['category_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $recipe_category_info = $this->model_catalog_recipe_category->getCategory($this->request->get['category_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($recipe_category_info)) {
            $data['sort_order'] = $recipe_category_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($recipe_category_info)) {
            $data['name'] = $recipe_category_info['name'];
        } else {
            $data['name'] = '';
        }

        // Text Editor
        $data['text_editor'] = $this->config->get('config_text_editor', 'tinymce');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/recipe_category_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/recipe_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 255)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/recipe_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateRepair()
    {
        if (!$this->user->hasPermission('modify', 'catalog/recipe_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/recipe_category');

            $filter_data = [
                'filter_name' => $this->request->get['filter_name'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5,
            ];

            $json = $this->model_catalog_recipe_category->getCategories($filter_data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
