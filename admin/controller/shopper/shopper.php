<?php

class ControllerShopperShopper extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('shopper/shopper');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('shopper/shopper');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $user_id = $this->model_shopper_shopper->addUser($this->request->post);

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
                $this->response->redirect($this->url->link('shopper/shopper/edit', 'user_id='.$user_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('shopper/shopper/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('shopper/shopper');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_shopper_shopper->editUser($this->request->get['user_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('shopper/shopper/edit', 'user_id='.$this->request->get['user_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('shopper/shopper/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('shopper/shopper');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_id) {
                $this->model_shopper_shopper->deleteUser($user_id);
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

            $this->response->redirect($this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = null;
        }

        if (isset($this->request->get['filter_user_name'])) {
            $filter_user_name = $this->request->get['filter_user_name'];
        } else {
            $filter_user_name = null;
        }

        if (isset($this->request->get['filter_user_group'])) {
            $filter_user_group = $this->request->get['filter_user_group'];
        } else {
            $filter_user_group = null;
        }

        if (isset($this->request->get['filter_first_name'])) {
            $filter_first_name = $this->request->get['filter_first_name'];
        } else {
            $filter_first_name = null;
        }

        if (isset($this->request->get['filter_last_name'])) {
            $filter_last_name = $this->request->get['filter_last_name'];
        } else {
            $filter_last_name = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'username';
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

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.urlencode(html_entity_decode($this->request->get['filter_city'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_user_name'])) {
            $url .= '&filter_user_name='.urlencode(html_entity_decode($this->request->get['filter_user_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_user_group'])) {
            $url .= '&filter_user_group='.urlencode(html_entity_decode($this->request->get['filter_user_group'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_first_name'])) {
            $url .= '&filter_first_name='.urlencode(html_entity_decode($this->request->get['filter_first_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_last_name'])) {
            $url .= '&filter_last_name='.urlencode(html_entity_decode($this->request->get['filter_last_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('shopper/shopper/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('shopper/shopper/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['shoppers'] = [];

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_user_name' => $filter_user_name,
            'filter_user_group' => $filter_user_group,
            'filter_first_name' => $filter_first_name,
            'filter_last_name' => $filter_last_name,
            'filter_email' => $filter_email,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $shopper_total = $this->model_shopper_shopper->getTotalUsersFilter($filter_data);

        $results = $this->model_shopper_shopper->getUsers($filter_data);

        foreach ($results as $result) {
            $data['shoppers'][] = [
                'user_id' => $result['user_id'],
                'username' => $result['username'],
                'city' => $result['city'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'timeslots' => $this->url->link('shopper/shopper/timeslots', 'token='.$this->session->data['token'].'&user_id='.$result['user_id'].$url, 'SSL'),
                'edit' => $this->url->link('shopper/shopper/edit', 'token='.$this->session->data['token'].'&user_id='.$result['user_id'].$url, 'SSL'),
                'today' => $this->url->link('shopper/shopper/today', 'token='.$this->session->data['token'].'&shopper_id='.$result['user_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_city'] = $this->language->get('entry_city');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_username'] = $this->language->get('column_username');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_order_id'] = $this->language->get('column_order_id');

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

        $data['sort_city'] = $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].'&sort=c.name'.$url, 'SSL');
        $data['sort_username'] = $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].'&sort=u.username'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].'&sort=u.status'.$url, 'SSL');
        $data['sort_date_added'] = $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].'&sort=u.date_added'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $shopper_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($shopper_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($shopper_total - $this->config->get('config_limit_admin'))) ? $shopper_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $shopper_total, ceil($shopper_total / $this->config->get('config_limit_admin')));

        $data['filter_user_name'] = $filter_user_name;
        $data['filter_user_group'] = $filter_user_group;
        $data['filter_first_name'] = $filter_first_name;
        $data['filter_last_name'] = $filter_last_name;
        $data['filter_email'] = $filter_email;
        $data['filter_city'] = $filter_city;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shopper/shopper_list.tpl', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_order_id'] = $this->language->get('entry_order_id');

        $data['entry_mobile'] = $this->language->get('entry_mobile');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_address'] = $this->language->get('entry_address');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_contact'] = $this->language->get('tab_contact');
        $data['tab_password'] = $this->language->get('tab_password');
        $data['tab_wallet'] = $this->language->get('tab_wallet');

        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = $this->language->get('column_amount');

        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['button_credit_add'] = $this->language->get('button_credit_add');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['mobile'])) {
            $data['error_mobile'] = $this->error['mobile'];
        } else {
            $data['error_mobile'] = '';
        }

        if (isset($this->error['city_id'])) {
            $data['error_city_id'] = $this->error['city_id'];
        } else {
            $data['error_city_id'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
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
            'href' => $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['token'] = $this->session->data['token'];

        if (!isset($this->request->get['user_id'])) {
            $data['action'] = $this->url->link('shopper/shopper/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('shopper/shopper/edit', 'token='.$this->session->data['token'].'&user_id='.$this->request->get['user_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['user_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $shopper_info = $this->model_shopper_shopper->getUser($this->request->get['user_id']);
        }

        if (isset($this->request->get['user_id'])) {
            $data['shopper_id'] = $this->request->get['user_id'];
        } else {
            $data['shopper_id'] = '';
        }

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } elseif (!empty($shopper_info)) {
            $data['username'] = $shopper_info['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['user_group_id'])) {
            $data['user_group_id'] = $this->request->post['user_group_id'];
        } elseif (!empty($shopper_info)) {
            $data['user_group_id'] = $shopper_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('shopper/shopper_group');

        $data['user_groups'] = $this->model_shopper_shopper_group->getUserGroups();

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($shopper_info)) {
            $data['firstname'] = $shopper_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($shopper_info)) {
            $data['lastname'] = $shopper_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($shopper_info)) {
            $data['email'] = $shopper_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($shopper_info)) {
            $data['address'] = $shopper_info['address'];
        } else {
            $data['address'] = '';
        }

        if (isset($this->request->post['city_id'])) {
            $data['city_id'] = $this->request->post['city_id'];
        } elseif (!empty($shopper_info)) {
            $data['city_id'] = $shopper_info['city_id'];
        } else {
            $data['city_id'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($shopper_info)) {
            $data['telephone'] = $shopper_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['mobile'])) {
            $data['mobile'] = $this->request->post['mobile'];
        } elseif (!empty($shopper_info)) {
            $data['mobile'] = $shopper_info['mobile'];
        } else {
            $data['mobile'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($shopper_info)) {
            $data['image'] = $shopper_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE.$this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($shopper_info) && $shopper_info['image'] && is_file(DIR_IMAGE.$shopper_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($shopper_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($shopper_info)) {
            $data['status'] = $shopper_info['status'];
        } else {
            $data['status'] = 0;
        }

        $this->load->model('localisation/city');

        $data['cities'] = $this->model_localisation_city->getAllCities();
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shopper/shopper_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'shopper/shopper')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['address'])) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if (empty($this->request->post['city_id'])) {
            $this->error['city_id'] = $this->language->get('error_city_id');
        }

        if (empty($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (empty($this->request->post['mobile'])) {
            $this->error['mobile'] = $this->language->get('error_mobile');
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $shopper_info = $this->model_shopper_shopper->getUserByUsername($this->request->post['username']);

        if (!isset($this->request->get['user_id'])) {
            if ($shopper_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($shopper_info && ($this->request->get['user_id'] != $shopper_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if ($this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'shopper/shopper')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['selected'] as $user_id) {
            if ($this->user->getId() == $user_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_user_name']) || isset($this->request->get['filter_user_group']) || isset($this->request->get['filter_first_name']) || isset($this->request->get['filter_last_name']) || isset($this->request->get['filter_email'])) {
            $this->load->model('shopper/shopper');

            if (isset($this->request->get['filter_user_name'])) {
                $filter_user_name = $this->request->get['filter_user_name'];
            } else {
                $filter_user_name = '';
            }

            if (isset($this->request->get['filter_user_group'])) {
                $filter_user_group = $this->request->get['filter_user_group'];
                if (empty($filter_user_group)) {
                    $filter_user_group = '*';
                }
            } else {
                $filter_user_group = '';
            }

            if (isset($this->request->get['filter_first_name'])) {
                $filter_first_name = $this->request->get['filter_first_name'];
            } else {
                $filter_first_name = '';
            }

            if (isset($this->request->get['filter_last_name'])) {
                $filter_last_name = $this->request->get['filter_last_name'];
            } else {
                $filter_last_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = [
                'filter_user_name' => $filter_user_name,
                'filter_user_group' => $filter_user_group,
                'filter_first_name' => $filter_first_name,
                'filter_last_name' => $filter_last_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => $limit,
            ];

            if (empty($filter_user_group)) {
                $results = $this->model_shopper_shopper->getUsers($filter_data);
            } else {
                $this->load->model('shopper/shopper_group');

                $_results = $this->model_shopper_shopper_group->getUserGroups($filter_data);
            }

            if (!empty($results)) {
                foreach ($results as $result) {
                    $json[] = [
                        'user_id' => $result['user_id'],
                        'username' => $result['username'],
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname'],
                        'email' => $result['email'],
                    ];
                }
            } elseif (!empty($_results)) {
                foreach ($_results as $result) {
                    $json[] = [
                        'user_group_id' => $result['user_group_id'],
                        'shopper_group' => $result['name'],
                    ];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function timeslots()
    {
        $this->load->language('shopper/shopper');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/shopper');

        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->request->get['user_id'])) {
            $user_id = $this->request->get['user_id'];
        } else {
            $this->response->redirect($this->url->link('error/not_found', 'token='.$this->session->data['token'], 'SSL'));
        }

        $data['text_heading'] = $this->language->get('text_heading');

        $data['tab_sunday'] = $this->language->get('tab_sunday');
        $data['tab_monday'] = $this->language->get('tab_monday');
        $data['tab_tuesday'] = $this->language->get('tab_tuesday');
        $data['tab_wesnesday'] = $this->language->get('tab_wesnesday');
        $data['tab_thursday'] = $this->language->get('tab_thursday');
        $data['tab_friday'] = $this->language->get('tab_friday');
        $data['tab_saturday'] = $this->language->get('tab_saturday');

        $data['column_from'] = $this->language->get('column_from');
        $data['column_to'] = $this->language->get('column_to');

        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_saveclose'] = $this->language->get('button_saveclose');

        $data['cancel'] = $this->url->link('shopper/shopper', 'token='.$this->session->data['token']);
        $data['action'] = $this->url->link('shopper/shopper/timeslots', 'token='.$this->session->data['token'].'&user_id='.$user_id);

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            $this->model_shopper_shopper->saveTimeslots($user_id, $this->request->post);

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
                $this->response->redirect($this->url->link('shopper/shopper/timeslots', 'user_id='.$user_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('shopper/shopper', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $data['timeslots'] = $this->model_shopper_shopper->getTimeslots($user_id);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shopper/shopper_timeslots.tpl', $data));
    }

    public function credit()
    {
        $this->load->language('shopper/shopper_info');

        $this->load->model('shopper/shopper');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'shopper/shopper')) {
            $this->model_shopper_shopper->addCredit($this->request->get['shopper_id'], $this->request->post['description'], $this->request->post['amount'], $this->request->post['order_id']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'shopper/shopper')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_order_id'] = $this->language->get('column_order_id');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = [];

        $results = $this->model_shopper_shopper->getCredits($this->request->get['shopper_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['credits'][] = [
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'order_id' => $result['order_id'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $data['balance'] = $this->currency->format($this->model_shopper_shopper->getCreditTotal($this->request->get['shopper_id']), $this->config->get('config_currency'));

        $credit_total = $this->model_shopper_shopper->getTotalCredits($this->request->get['shopper_id']);

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('shopper/shopper/credit', 'token='.$this->session->data['token'].'&shopper_id='.$this->request->get['shopper_id'].'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

        $this->response->setOutput($this->load->view('shopper/shopper_credit.tpl', $data));
    }

    public function info()
    {
        $this->language->load('vendor/vendor_info');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/vendor');
        $this->load->model('sale/order');

        $url = '';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
            $url = '&vendor_id='.$vendor_id;
        } else {
            $this->redirect($this->url->link('error/not_found', 'token='.$this->session->data['token']));
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/vendor', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = $this->language->get('column_amount');

        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['button_credit_add'] = $this->language->get('button_credit_add');

        $data['cancel'] = $this->url->link('vendor/vendor', 'token='.$this->session->data['token'], 'SSL');

        //orders
        $data['orders'] = [];

        $filter_data = [
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10,
            'vendor_id' => $vendor_id,
        ];

        $results = $this->model_vendor_vendor->getVendorOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'vendor_order_id' => $result['vendor_order_id'],
                'store_id' => $result['store_id'],
                'customer' => $result['customer'],
                'status' => $this->model_sale_order->getStatus($result['order_status_id']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'info' => $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&store_id='.$result['store_id'].'&order_id='.$result['order_id'], 'SSL'),
            ];
        }

        //get package
        $data['package'] = $this->model_sale_order->getVendorToPackages($vendor_id);

        $pagination = new Pagination();
        $pagination->total = $this->model_vendor_vendor->getVendorTotalOrders($filter_data);
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('vendor/vendor/info', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        //basic info
        $data['user'] = $this->model_vendor_vendor->getUser($vendor_id);

        //store details
        $data['stores'] = [];

        $stores = $this->model_sale_order->getStoreDatas($vendor_id);

        foreach ($stores as $store) {
            $store['categories'] = $this->model_vendor_vendor->getStoreCategories($store['store_id']);
            $data['stores'][] = $store;
        }

        $data['statistics'] = [
            'orders' => $this->model_sale_order->getTotalOrdersByVendor($vendor_id),
            'selling' => $this->model_sale_order->getTotalSellingByVendor($vendor_id),
            'commision' => $this->model_sale_order->getTotalCommisionByVendor($vendor_id), ];

        $data['token'] = $this->session->data['token'];

        $data['vendor_id'] = $vendor_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/vendor_info.tpl', $data));
    }

    public function city_autocomplete()
    {
        $this->load->model('sale/order');

        $json = $this->model_sale_order->getCitiesLike($this->request->get['filter_name']);

        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function today()
    {
        $this->language->load('shopper/today');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_total_commision'] = $this->language->get('text_total_commision');
        $data['text_today_shipped'] = $this->language->get('text_today_shipped');
        $data['text_no_orders'] = $this->language->get('text_no_orders');
        $data['text_today_route'] = $this->language->get('text_today_route');
        $data['column_store'] = $this->language->get('column_store');
        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_vendor_order_id'] = $this->language->get('column_vendor_order_id');
        $data['column_commision'] = $this->language->get('column_commision');

        $this->load->model('shopper/shopper');

        if (isset($this->request->get['shopper_id'])) {
            $shopper_id = $this->request->get['shopper_id'];
        } else {
            $shopper_id = 0;
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Shoppers',
            'href' => $this->url->link('shopper/shopper', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('shopper/shopper/today', 'token='.$this->session->data['token'].'&shopper_id='.$shopper_id, 'SSL'),
            'separator' => ' :: ',
        ];

        //total commision
        $data['commision'] = $this->currency->format($this->model_shopper_shopper->getTodayTotalCommision($shopper_id));

        //orders
        $data['orders'] = [];

        $orders = $this->model_shopper_shopper->getTodayOrders($shopper_id);

        foreach ($orders as $order) {
            $data['orders'][] = [
                'store_name' => $order['store_name'],
                'order_id' => $order['order_id'],
                'vendor_order_id' => $order['vendor_order_id'],
                'shopper_commision' => $this->currency->format($order['shopper_commision'], $order['currency_code'], $order['currency_value']),
            ];
        }

        //route
        $data['route'] = $this->model_shopper_shopper->getTodayRoute($shopper_id);

        //get min, max values for map bounds
        $data['limits'] = $this->model_shopper_shopper->getLimits($shopper_id);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shopper/shopper_today.tpl', $data));
    }
}
