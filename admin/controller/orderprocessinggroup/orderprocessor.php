<?php

class ControllerOrderProcessingGroupOrderProcessor extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('orderprocessinggroup/orderprocessor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $this->load->model('orderprocessinggroup/orderprocessor');

        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
    }

    public function add() {
        $this->load->language('orderprocessinggroup/orderprocessor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $this->load->model('orderprocessinggroup/orderprocessor');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $order_processor_id = $this->model_orderprocessor_orderprocessor->addOrderProcessor($this->request->post);

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Order Processor Created Successfully!';

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'order_processor_id' => $order_processor_id,
            ];
            $log->write('order processor add');

            $this->model_user_user_activity->addActivity('order_processor_add', $activity_data);

            $log->write('order processor add');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_order_processing_group_id'])) {
                $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessor/edit', 'order_processor_id=' . $order_processor_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessor/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    protected function getList() {
        $this->load->language('orderprocessinggroup/orderprocessor');

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

        if (isset($this->request->get['filter_order_processing_group_id'])) {
            $filter_order_processing_group_id = $this->request->get['filter_order_processing_group_id'];
        } else {
            $filter_order_processing_group_id = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
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
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_order_processing_group_id'])) {
            $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('orderprocessinggroup/orderprocessor/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('orderprocessinggroup/orderprocessor/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['orderprocessors'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'filter_date_added' => $filter_date_added,
            'filter_order_processing_group_id' => $filter_order_processing_group_id,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $orderprocessor_total = $this->model_orderprocessinggroup_orderprocessor->getTotalOrderProcessors($filter_data);

        $results = $this->model_orderprocessinggroup_orderprocessor->getOrderProcessors($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if (!$result['status']) {
                $status = $this->url->link('orderprocessinggroup/orderprocessor/approve', 'token=' . $this->session->data['token'] . '&order_processor_id=' . $result['order_processor_id'] . $url, 'SSL');
            } else {
                $status = '';
            }


            $data['orderprocessors'][] = [
                'order_processor_id' => $result['order_processor_id'],
                'name' => $result['name'],
                'order_processing_group_id' => $result['order_processing_group_id'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'created_at' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
                'edit' => $this->url->link('orderprocessinggroup/orderprocessinggroup_list/edit', 'token=' . $this->session->data['token'] . '&order_processing_group_id=' . $result['order_processing_group_id'] . $url, 'SSL'),
                'orderprocessor_view' => $this->url->link('orderprocessinggroup/orderprocessor/view_orderprocessor', 'token=' . $this->session->data['token'] . '&order_processor_id=' . $result['order_processor_id'] . $url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('short_entry_name');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_verify'] = $this->language->get('button_verify');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_order_processing_group_id'])) {
            $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . '&sort=c.created_at' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_order_processing_group_id'])) {
            $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $orderprocessor_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($orderprocessor_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($orderprocessor_total - $this->config->get('config_limit_admin'))) ? $orderprocessor_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $orderprocessor_total, ceil($orderprocessor_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('orderprocessinggroup/orderprocessor_list.tpl', $data));
    }

    protected function getForm() {
        
        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $this->load->model('orderprocessinggroup/orderprocessor');
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_referred_by'] = $this->language->get('entry_referred_by');

        $data['text_form'] = !isset($this->request->get['order_processor_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_name'] = $this->language->get('short_entry_name');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_groupname'] = $this->language->get('entry_groupname');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_description'] = $this->language->get('entry_description');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');

        $data['token'] = $this->session->data['token'];


        if (isset($this->request->get['order_processor_id'])) {
            $data['order_processor_id'] = $this->request->get['order_processor_id'];
        } else {
            $data['order_processor_id'] = 0;
        }

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
            $data['error_name'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_order_processing_group_id'])) {
            $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['order_processor_id'])) {
            $data['action'] = $this->url->link('orderprocessinggroup/orderprocessor/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('orderprocessinggroup/orderprocessor/edit', 'token=' . $this->session->data['token'] . '&order_processor_id=' . $this->request->get['order_processor_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . $url, 'SSL');
        if (isset($this->request->get['order_processor_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $orderprocessor_info = $this->model_orderprocessinggroup_orderprocessor->getOrderProcessor($this->request->get['order_processor_id']);
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($orderprocessor_info)) {
            $data['firstname'] = $orderprocessor_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($orderprocessor_info)) {
            $data['lastname'] = $orderprocessor_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        //echo "<pre>";print_r($data);die;
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($orderprocessor_info)) {
            $data['status'] = $orderprocessor_info['status'];
        } else {
            $data['status'] = true;
        }
        
        $data['order_processing_groups'] = $this->model_orderprocessinggroup_orderprocessinggroup->getOrderProcessingGroups();
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->response->setOutput($this->load->view('orderprocessinggroup/orderprocessor_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'orderprocessinggroup/orderprocessor')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        $orderprocessor_info = $this->model_orderprocessinggroup_orderprocessor->getOrderProcessorByName($this->request->post['firstname'] . ' ' . $this->request->post['lastname']);

        if (!isset($this->request->get['order_processor_id'])) {
            if ($orderprocessor_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($orderprocessor_info && ($this->request->get['order_processor_id'] != $orderprocessor_info['order_processor_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function edit() {
        $this->load->language('orderprocessinggroup/orderprocessor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $this->load->model('orderprocessinggroup/orderprocessor');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_orderprocessinggroup_orderprocessor->editOrderProcessor($this->request->get['order_processor_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'order_processor_id' => $this->request->get['order_processor_id'],
            ];
            $log->write('orderprocessor edit');

            $this->model_user_user_activity->addActivity('orderprocessor_edit', $activity_data);

            $log->write('orderprocessor edit');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_order_processing_group_id'])) {
                $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessor/edit', 'order_processor_id=' . $this->request->get['order_processor_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessor/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function autocompletebyOrderProcessingGroupName() {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $this->load->model('orderprocessinggroup/orderprocessinggroup');
            $this->load->model('orderprocessinggroup/orderprocessor');

            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_orderprocessinggroup_orderprocessor->getOrderProcessors($filter_data);

            foreach ($results as $result) {

                $json[] = [
                    'order_processor_id' => $result['order_processor_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
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

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_orderprocessors_excel($data);
    }

    public function delete() {
        $this->load->language('executives/executives');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $this->load->model('orderprocessinggroup/orderprocessors');
        
        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $order_processor_id) {
                $this->model_orderprocessinggroup_orderprocessor->deleteOrderProcessor($order_processor_id);

                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'order_processor_id' => $order_processor_id,
                ];
                $log->write('order processor delete');

                $this->model_user_user_activity->addActivity('order_processor_delete', $activity_data);

                $log->write('order processor delete');
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Order Processor(s) Deleted Successfully!';

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
            
            if (isset($this->request->get['filter_order_processing_group_id'])) {
                $url .= '&filter_order_processing_group_id=' . $this->request->get['filter_order_processing_group_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('orderprocessinggroup/orderprocessinggroup_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'orderprocessinggroup/orderprocessinggroup_list')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function getAllDeliveryExecutives() {

        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $results = $this->model_orderprocessinggroup_orderprocessinggroup->getOrderProcessingGroups();

        foreach ($results as $result) {
            $json[] = [
                'order_processing_group_id' => $result['order_processing_group_id'],
                'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
            ];
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
