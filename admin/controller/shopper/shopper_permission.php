<?php

class Controllershoppershopperpermission extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('shopper/shopper_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper_group');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('shopper/shopper_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper_group');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $user_group_id = $this->model_shopper_shopper_group->addUserGroup($this->request->post);

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
                $this->response->redirect($this->url->link('shopper/shopper_permission/edit', 'user_group_id='.$user_group_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('shopper/shopper_permission/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('shopper/shopper_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper_group');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_shopper_shopper_group->editUserGroup($this->request->get['user_group_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('shopper/shopper_permission/edit', 'user_group_id='.$this->request->get['user_group_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('shopper/shopper_permission/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('shopper/shopper_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper_group');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_group_id) {
                $this->model_shopper_shopper_group->deleteUserGroup($user_group_id);
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

            $this->response->redirect($this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url, 'SSL'));
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
            'href' => $this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('shopper/shopper_permission/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('shopper/shopper_permission/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['shopper_groups'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $shopper_group_total = $this->model_shopper_shopper_group->getTotalUserGroups();

        $results = $this->model_shopper_shopper_group->getUserGroups($filter_data);

        foreach ($results as $result) {
            $data['shopper_groups'][] = [
                'user_group_id' => $result['user_group_id'],
                'name' => $result['name'],
                'edit' => $this->url->link('shopper/shopper_permission/edit', 'token='.$this->session->data['token'].'&user_group_id='.$result['user_group_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

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

        $data['sort_name'] = $this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $shopper_group_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($shopper_group_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($shopper_group_total - $this->config->get('config_limit_admin'))) ? $shopper_group_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $shopper_group_total, ceil($shopper_group_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shopper/shopper_group_list.tpl', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['text_form'] = !isset($this->request->get['user_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_access'] = $this->language->get('entry_access');
        $data['entry_modify'] = $this->language->get('entry_modify');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

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
            'href' => $this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['user_group_id'])) {
            $data['action'] = $this->url->link('shopper/shopper_permission/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('shopper/shopper_permission/edit', 'token='.$this->session->data['token'].'&user_group_id='.$this->request->get['user_group_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('shopper/shopper_permission', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['user_group_id']) && 'POST' != $this->request->server['REQUEST_METHOD']) {
            $shopper_group_info = $this->model_shopper_shopper_group->getUserGroup($this->request->get['user_group_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($shopper_group_info)) {
            $data['name'] = $shopper_group_info['name'];
        } else {
            $data['name'] = '';
        }

        $ignore = [
            'common/dashboard',
            'common/startup',
            'common/login',
            'common/orderinfo',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'error/not_found',
            'error/permission',
            'common/footer',
            'common/header',
            'dashboard/activity',
            'dashboard/chart',
            'dashboard/charts',
            'dashboard/customer',
            'dashboard/map',
            'dashboard/online',
            'dashboard/order',
            'dashboard/recent',
            'dashboard/recenttabs',
            'dashboard/sale',
        ];

        $data['permissions'] = [];

        $files = glob(DIR_APPLICATION.'controller/*/*.php');

        foreach ($files as $file) {
            $part = explode('/', dirname($file));

            $permission = end($part).'/'.basename($file, '.php');

            if (!in_array($permission, $ignore)) {
                $data['permissions'][] = $permission;
            }
        }

        if (isset($this->request->post['permission']['access'])) {
            $data['access'] = $this->request->post['permission']['access'];
        } elseif (isset($shopper_group_info['permission']['access'])) {
            $data['access'] = $shopper_group_info['permission']['access'];
        } else {
            $data['access'] = [];
        }

        if (isset($this->request->post['permission']['modify'])) {
            $data['modify'] = $this->request->post['permission']['modify'];
        } elseif (isset($shopper_group_info['permission']['modify'])) {
            $data['modify'] = $shopper_group_info['permission']['modify'];
        } else {
            $data['modify'] = [];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shopper/shopper_group_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'shopper/shopper_permission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'shopper/shopper_permission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('shopper/shopper');

        foreach ($this->request->post['selected'] as $user_group_id) {
            $shopper_total = $this->model_shopper_shopper->getTotalUsersByGroupId($user_group_id);

            if ($shopper_total) {
                $this->error['warning'] = sprintf($this->language->get('error_shopper'), $shopper_total);
            }
        }

        return !$this->error;
    }
}
