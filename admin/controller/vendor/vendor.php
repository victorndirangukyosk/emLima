<?php

class ControllerVendorVendor extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('vendor/vendor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/vendor');

        $this->getList();
    }

    public function add() {
        $this->load->language('vendor/vendor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/vendor');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $user_id = $this->model_vendor_vendor->addUser($this->request->post);            
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('vendor/vendor/edit', 'user_id=' . $user_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('vendor/vendor/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function deleteExcelStoreMapping(){
        if ($this->user->isVendor() ) {
            $this->error['warning'] = $this->language->get( 'error_permission' );
        }else{
            $id = $this->request->get['id'];
            $this->load->model('vendor/vendor_group' );
            $json = $this->model_vendor_vendor_group->deleteExcelStoreMap($id);
            $this->response->addHeader( 'Content-Type: application/json' );
            $this->response->setOutput( json_encode( $json ) );

        }
    }


    public function edit() {
        $this->load->language('vendor/vendor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/vendor');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            
            $this->model_vendor_vendor->editUser($this->request->get['user_id'], $this->request->post);

            //echo "<pre>";print_r($this->request->post);die;

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('vendor/vendor/edit', 'user_id=' . $this->request->get['user_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('vendor/vendor/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('vendor/vendor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/vendor');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_id) {
                $this->model_vendor_vendor->deleteUser($user_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        
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
            $url .= '&filter_city=' . urlencode(html_entity_decode($this->request->get['filter_city'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_user_name'])) {
            $url .= '&filter_user_name=' . urlencode(html_entity_decode($this->request->get['filter_user_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_user_group'])) {
            $url .= '&filter_user_group=' . urlencode(html_entity_decode($this->request->get['filter_user_group'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_first_name'])) {
            $url .= '&filter_first_name=' . urlencode(html_entity_decode($this->request->get['filter_first_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_last_name'])) {
            $url .= '&filter_last_name=' . urlencode(html_entity_decode($this->request->get['filter_last_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['add'] = $this->url->link('vendor/vendor/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('vendor/vendor/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['vendors'] = array();

        $filter_data = array(
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
            'limit' => $this->config->get('config_limit_admin')
        );

        $vendor_total = $this->model_vendor_vendor->getTotalUsersFilter($filter_data);
        
        $results = $this->model_vendor_vendor->getUsers($filter_data);

        foreach ($results as $result) {
            $data['vendors'][] = array(
                'user_id' => $result['user_id'],
                'username' => $result['username'],
                'city' => $result['city'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'info' => $this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $result['user_id'] . $url, 'SSL'),
                'edit' => $this->url->link('vendor/vendor/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL'),
                'login' =>$this->url->link('vendor/vendor/loginas','token=' . $this->session->data['token'] . '&vendor_id=' . $result['user_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_vendor_group'] = $this->language->get('entry_vendor_group');
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
        $data['column_city'] = $this->language->get('column_city');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
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

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_city'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, 'SSL');
        $data['sort_username'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . '&sort=u.status' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . '&sort=u.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $vendor_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vendor_total - $this->config->get('config_limit_admin'))) ? $vendor_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vendor_total, ceil($vendor_total / $this->config->get('config_limit_admin')));

        $data['filter_user_name'] = $filter_user_name;
        $data['filter_user_group'] = $filter_user_group;
        $data['filter_first_name'] = $filter_first_name;
        $data['filter_last_name'] = $filter_last_name;
        $data['filter_email'] = $filter_email;
        $data['filter_city'] = $filter_city;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data ['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/vendor_list.tpl', $data));
    }

    protected function getForm() {
        
        $this->document->addScript('https://maps.google.com/maps/api/js?key='.$this->config->get('config_google_api_key').'&sensor=false&libraries=places');
        $this->document->addScript('ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_vendor_group'] = $this->language->get('entry_vendor_group');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_invoice'] = $this->language->get('entry_invoice');

        $data['entry_transfer_to_iugu'] = $this->language->get('entry_transfer_to_iugu');
        
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        
        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_commision'] = $this->language->get('tab_commision');
        $data['tab_contact'] = $this->language->get('tab_contact');
        $data['tab_password'] = $this->language->get('tab_password');
        $data['tab_wallet'] = $this->language->get('tab_wallet');

        $data['entry_mobile'] = $this->language->get('entry_mobile'); 
        $data['entry_telephone'] = $this->language->get('entry_telephone'); 
        $data['entry_city'] = $this->language->get('entry_city'); 
        $data['entry_address'] = $this->language->get('entry_address'); 
        
        $data['entry_free_to'] = $this->language->get('entry_free_to'); 
        $data['entry_free_from'] = $this->language->get('entry_free_from'); 
        $data['entry_commision'] = $this->language->get('entry_commision'); 
        $data['entry_fixed_commision'] = $this->language->get('entry_fixed_commision'); 
        $data['entry_tin_no'] = $this->language->get('entry_tin_no'); 
        $data['entry_orderprefix'] = $this->language->get('entry_orderprefix'); 
        $data['entry_display_name'] = $this->language->get('entry_display_name'); 
        $data['entry_delivery_time'] = $this->language->get('entry_delivery_time'); 
        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['column_description'] = $this->language->get('column_description');
        $data['column_invoice'] = $this->language->get('column_invoice');
        $data['column_amount'] = $this->language->get('column_amount');
        
       
        $data['button_credit_add'] = $this->language->get('button_credit_add');
        
        $data['token'] = $this->session->data['token'];
        
        

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
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
        
        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
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

        /*Bank details*/

        if (isset($this->error['bank_account_number'])) {
            $data['error_bank_account_number'] = $this->error['bank_account_number'];
        } else {
            $data['error_bank_account_number'] = '';
        }

        if (isset($this->error['bank_account_name'])) {
            $data['error_bank_account_name'] = $this->error['bank_account_name'];
        } else {
            $data['error_bank_account_name'] = '';
        }

        if (isset($this->error['bank_name'])) {
            $data['error_bank_name'] = $this->error['bank_name'];
        } else {
            $data['error_bank_name'] = '';
        }

        if (isset($this->error['bank_branch_name'])) {
            $data['error_bank_branch_name'] = $this->error['bank_branch_name'];
        } else {
            $data['error_bank_branch_name'] = '';
        }


        /*end*/

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
        
        if (isset($this->error['orderprefix'])) {
            $data['error_orderprefix'] = $this->error['orderprefix'];
        } else {
            $data['error_orderprefix'] = '';
        }
        
        if (isset($this->error['display_name'])) {
            $data['error_display_name'] = $this->error['display_name'];
        } else {
            $data['error_display_name'] = '';
        }
        
        if (isset($this->error['delivery_time'])) {
            $data['error_delivery_time'] = $this->error['delivery_time'];
        } else {
            $data['error_delivery_time'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['user_id'])) {
            $data['action'] = $this->url->link('vendor/vendor/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('vendor/vendor/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $vendor_info = $this->model_vendor_vendor->getUser($this->request->get['user_id']);

            $vendor_bank_info = $this->model_vendor_vendor->getVendorBank($this->request->get['user_id']);

        }

        /*bank */

        if (isset($this->request->post['bank_account_number'])) {
            $data['bank_account_number'] = $this->request->post['bank_account_number'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_account_number'] = $vendor_bank_info['bank_account_number'];
        } else {
            $data['bank_account_number'] = '';
        }

        if (isset($this->request->post['bank_account_name'])) {
            $data['bank_account_name'] = $this->request->post['bank_account_name'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_account_name'] = $vendor_bank_info['bank_account_name'];
        } else {
            $data['bank_account_name'] = '';
        }

        if (isset($this->request->post['bank_name'])) {
            $data['bank_name'] = $this->request->post['bank_name'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_name'] = $vendor_bank_info['bank_name'];
        } else {
            $data['bank_name'] = '';
        }

        if (isset($this->request->post['bank_branch_name'])) {
            $data['bank_branch_name'] = $this->request->post['bank_branch_name'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_branch_name'] = $vendor_bank_info['bank_branch_name'];
        } else {
            $data['bank_branch_name'] = '';
        }


        if (isset($this->request->post['bank_account_type'])) {
            $data['bank_account_type'] = $this->request->post['bank_account_type'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_account_type'] = $vendor_bank_info['bank_account_type'];
        } else {
            $data['bank_account_type'] = '';
        }

        /*bank end*/

        if (isset($this->request->get['user_id'])){
            $data['vendor_id'] = $this->request->get['user_id'];
        } else {
            $data['vendor_id'] = 0;
        }
                
        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } elseif (!empty($vendor_info)) {
            $data['username'] = $vendor_info['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['user_group_id'])) {
            $data['user_group_id'] = $this->request->post['user_group_id'];
        } elseif (!empty($vendor_info)) {
            $data['user_group_id'] = $vendor_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('vendor/vendor_group');

        $data['user_groups'] = $this->model_vendor_vendor_group->getUserGroups();

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } elseif (!empty($vendor_info)) {
            //else if is added ,because while editing of some other fields in screen,
            // password field is asking to enter password again
            $data['password'] = 'default';
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } elseif (!empty($vendor_info)) {
            //else if is added ,because while editing of some other fields in screen,
            // password field is asking to enter password again
            $data['confirm'] = 'default';
        }else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($vendor_info)) {
            $data['firstname'] = $vendor_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($vendor_info)) {
            $data['lastname'] = $vendor_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($vendor_info)) {
            $data['email'] = $vendor_info['email'];
        } else {
            $data['email'] = '';
        }
        
        if (isset($this->request->post['order_notification_emails'])) {
            $data['order_notification_emails'] = $this->request->post['order_notification_emails'];
        } elseif (isset($vendor_info['order_notification_emails'])) {
            $data['order_notification_emails'] = $vendor_info['order_notification_emails'];
        } else {
            $data['order_notification_emails'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($vendor_info)) {
            $data['image'] = $vendor_info['image'];
        } else {
            $data['image'] = '';
        }
        
        if (isset($this->request->post['commision'])) {
            $data['commision'] = $this->request->post['commision'];
        } elseif (!empty($vendor_info)) {
            $data['commision'] = $vendor_info['commision'];
        } else {
            $data['commision'] = '';
        }

        if (isset($this->request->post['fixed_commision'])) {
            $data['fixed_commision'] = $this->request->post['fixed_commision'];
        } elseif (!empty($vendor_info)) {
            $data['fixed_commision'] = $vendor_info['fixed_commision'];
        } else {
            $data['fixed_commision'] = '';
        }

        
        if (isset($this->request->post['free_from'])) {
            $data['free_from'] = $this->request->post['free_from'];
        } elseif (!empty($vendor_info) && $vendor_info['free_from'] != '0000-00-00') {
            $data['free_from'] = $vendor_info['free_from'];
        } else {
            $data['free_from'] = '';
        }
        
        if (isset($this->request->post['free_to'])) {
            $data['free_to'] = $this->request->post['free_to'];
        } elseif (!empty($vendor_info) && $vendor_info['free_to'] != '0000-00-00') {
            $data['free_to'] = $vendor_info['free_to'];
        } else {
            $data['free_to'] = '';
        }
        
        if (isset($this->request->post['tin_no'])) {
            $data['tin_no'] = $this->request->post['tin_no'];
        } elseif (!empty($vendor_info)) {
            $data['tin_no'] = $vendor_info['tin_no'];
        } else {
            $data['tin_no'] = '';
        }
        
        if (isset($this->request->post['orderprefix'])) {
            $data['orderprefix'] = $this->request->post['orderprefix'];
        } elseif (!empty($vendor_info)) {
            $data['orderprefix'] = $vendor_info['orderprefix'];
        } else {
            $data['orderprefix'] = '';
        }
        
        if (isset($this->request->post['display_name'])) {
            $data['display_name'] = $this->request->post['display_name'];
        } elseif (!empty($vendor_info)) {
            $data['display_name'] = $vendor_info['display_name'];
        } else {
            $data['display_name'] = '';
        }
        
        if (isset($this->request->post['delivery_time'])) {
            $data['delivery_time'] = $this->request->post['delivery_time'];
        } elseif (!empty($vendor_info)) {
            $data['delivery_time'] = $vendor_info['delivery_time'];
        } else {
            $data['delivery_time'] = '';
        }
        
        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($vendor_info)) {
            $data['address'] = $vendor_info['address'];
        } else {
            $data['address'] = '';
        }

        if (isset($this->request->post['latitude'])) {
            $data['latitude'] = $this->request->post['latitude'];
        } elseif (!empty($vendor_info)) {
            $data['latitude'] = $vendor_info['latitude'];
        } else {
            $data['latitude'] = '';
        }

        if (isset($this->request->post['longitude'])) {
            $data['longitude'] = $this->request->post['longitude'];
        } elseif (!empty($vendor_info)) {
            $data['longitude'] = $vendor_info['longitude'];
        } else {
            $data['longitude'] = '';
        }

        
        if (isset($this->request->post['city_id'])) {
            $data['city_id'] = $this->request->post['city_id'];
        } elseif (!empty($vendor_info)) {
            $data['city_id'] = $vendor_info['city_id'];
        } else {
            $data['city_id'] = '';
        }
        
        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($vendor_info)) {
            $data['telephone'] = $vendor_info['telephone'];
        } else {
            $data['telephone'] = '';
        }
        
        if (isset($this->request->post['mobile'])) {
            $data['mobile'] = $this->request->post['mobile'];
        } elseif (!empty($vendor_info)) {
            $data['mobile'] = $vendor_info['mobile'];
        } else {
            $data['mobile'] = '';
        }
        
        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($vendor_info) && $vendor_info['image'] && is_file(DIR_IMAGE . $vendor_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($vendor_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($vendor_info)) {
            $data['status'] = $vendor_info['status'];
        } else {
            $data['status'] = 0;
        }
        
        $data['cities'] = $this->model_tool_image->getCities();      

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['publishable_key'] = $this->config->get('stripe_connect_platform_id');//'ca_BoxbgCghYSt10rKtPzsW6WCLQ7nEEXIz';

        $data['stripe_info_exists'] = false;

        //client id : ca_BoxbgCghYSt10rKtPzsW6WCLQ7nEEXIz
        // prod clieb id : ca_Boxbu44NY0Vjs9rGAp1bvmVMtOzTZmhn

        $this->load->model('payment/stripe');

        $data['stripe_info'] = $this->model_payment_stripe->getVendorStripeAccount($data['vendor_id']);

        if($data['stripe_info']) {
            $data['stripe_info_exists'] = true;
        }

        $data['stripe_image'] = $this->model_tool_image->resize('stripe_image.png', 190, 33);

        // Store Mapping
        

        $data['excel_stores'] = $this->model_vendor_vendor_group->getExcelStoreMapping($data['vendor_id']);

        $data['stores'] = $this->model_setting_store->getStores();

        //echo "<pre>";print_r($data['add']);die;
        //echo "<pre>";print_r($data['stores']);die;
        //echo "<pre>";print_r($data['excel_stores']);die;
        /*$data['excel_stores'] = [];
        foreach ( $excel_stores as $excel_store ) {
            
            $data['excel_stores'][] = array(
                'image' => $image,
                'thumb' => $this->model_tool_image->resize( $thumb, 100, 100 ),
                'sort_order' => $excel_store['sort_order'],
                'excel_store_id' => $excel_store['product_image_id'],
               
            );
        }*/
        //end

        $this->response->setOutput($this->load->view('vendor/vendor_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'vendor/vendor')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['username']) < 5) || (utf8_strlen($this->request->post['username']) > 50)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $vendor_info = $this->model_vendor_vendor->getUserByUsername($this->request->post['username']);

        if(empty($this->request->post['address'])){
            $this->error['address'] = $this->language->get('error_address');
        }
        
        if(empty($this->request->post['city_id'])){
            $this->error['city_id'] = $this->language->get('error_city_id');
        }

        if(empty($this->request->post['telephone'])){
            $this->error['telephone'] = $this->language->get('error_telephone');
        }
        
        if(empty($this->request->post['mobile'])){
            $this->error['mobile'] = $this->language->get('error_mobile');
        }
        
        if(empty($this->request->post['display_name'])){
            $this->error['display_name'] = $this->language->get('error_display_name');
        }
        
        /*if(empty($this->request->post['delivery_time'])){
            $this->error['delivery_time'] = $this->language->get('error_delivery_time');
        }*/
        
        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (!isset($this->request->get['user_id'])) {
            if ($vendor_info) {
                $this->error['username'] = $this->language->get('error_exists');
            }
        } else {
            if ($vendor_info && ($this->request->get['user_id'] != $vendor_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }
        
        if ((utf8_strlen(trim($this->request->post['display_name'])) < 5) || (utf8_strlen(trim($this->request->post['display_name'])) > 10)) {
            $this->error['display_name'] = $this->language->get('error_display_name_length');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }
        
        if ((utf8_strlen(trim($this->request->post['orderprefix'])) < 2) || (utf8_strlen(trim($this->request->post['orderprefix'])) > 5)) {
            $this->error['orderprefix'] = 'Order Prefix Length Should Be Between 2 To 5 Characters!';
        }
        
        if (utf8_strlen(trim($this->request->post['orderprefix'])) > 2 && !preg_match("/^[a-zA-Z0-9]+$/", trim($this->request->post['orderprefix']))) {
            $this->error['orderprefix'] = 'Order Prefix Characters Should Be Alpha Numeric Only!';
        }

        /*bank validate*/

        /*if ((utf8_strlen(trim($this->request->post['bank_account_number'])) < 1) || (utf8_strlen(trim($this->request->post['bank_account_number'])) > 32)) {
            $this->error['bank_account_number'] = $this->language->get('error_bank_account_number');
        }

        if ((utf8_strlen(trim($this->request->post['bank_account_name'])) < 1) || (utf8_strlen(trim($this->request->post['bank_account_name'])) > 32)) {
            $this->error['bank_account_name'] = $this->language->get('error_bank_account_name');
        }

        if ((utf8_strlen(trim($this->request->post['bank_name'])) < 1) || (utf8_strlen(trim($this->request->post['bank_name'])) > 32)) {
            $this->error['bank_name'] = $this->language->get('error_bank_name');
        }

        if ((utf8_strlen(trim($this->request->post['bank_branch_name'])) < 1) || (utf8_strlen(trim($this->request->post['bank_branch_name'])) > 32)) {
            $this->error['bank_branch_name'] = $this->language->get('error_bank_branch_name');
        }*/


        /*end*/

        // if ((utf8_strlen(trim($this->request->post['email'])) < 1)) {
        //     $this->error['email'] = $this->language->get('error_email');
        // }

        if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        //echo "<pre>";print_r($this->error);die;


        if($this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }


        
        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/vendor')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['selected'] as $user_id) {
            if ($this->user->getId() == $user_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_user_name']) || isset($this->request->get['filter_user_group']) || isset($this->request->get['filter_first_name']) || isset($this->request->get['filter_last_name']) || isset($this->request->get['filter_email'])) {
            $this->load->model('vendor/vendor');

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

            $filter_data = array(
                'filter_user_name' => $filter_user_name,
                'filter_user_group' => $filter_user_group,
                'filter_first_name' => $filter_first_name,
                'filter_last_name' => $filter_last_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => $limit
            );

            if (empty($filter_user_group)) {
                $results = $this->model_vendor_vendor->getUsers($filter_data);
            } else {
                $this->load->model('vendor/vendor_group');

                $_results = $this->model_vendor_vendor_group->getUserGroups($filter_data);
            }

            if (!empty($results)) {
                foreach ($results as $result) {
                    $json[] = array(
                        'user_id' => $result['user_id'],
                        'username' => $result['username'],
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname'],
                        'email' => $result['email']
                    );
                }
            } else if (!empty($_results)) {
                foreach ($_results as $result) {
                    $json[] = array(
                        'user_group_id' => $result['user_group_id'],
                        'vendor_group' => $result['name']
                    );
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function credit() {
        
        $this->load->language('vendor/vendor_info');

        $this->load->model('vendor/vendor');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'vendor/vendor')) {

            //echo "<pre>";print_r($this->request->post);die;
            $this->model_vendor_vendor->addCredit($this->request->get['vendor_id'], $this->request->post['description'], $this->request->post['amount'], $this->request->post['order_id'], $this->request->post['iugu-transfer'],$this->request->post);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'vendor/vendor')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_invoice'] = $this->language->get('column_invoice');
        $data['column_amount'] = $this->language->get('column_amount');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = array();

        $results = $this->model_vendor_vendor->getCredits($this->request->get['vendor_id'], ($page - 1) * 10, 10);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['credits'][] = array(
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'order_id' => $result['order_id'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'invoice' => $result['invoice']?$this->url->link('sale/order/EditTransactionInvoice', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['id'], 'SSL'):$result['invoice'],
            );
        }

        //echo "<pre>";print_r($data['credits']);die;
        $data['balance'] = $this->currency->format($this->model_vendor_vendor->getCreditTotal($this->request->get['vendor_id']), $this->config->get('config_currency'));

        $credit_total = $this->model_vendor_vendor->getTotalCredits($this->request->get['vendor_id']);

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('vendor/vendor/credit', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->get['vendor_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

        $this->response->setOutput($this->load->view('vendor/vendor_credit.tpl', $data));
    }

    public function info() {

        $this->language->load('vendor/vendor_info');
        
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/vendor');
        $this->load->model('tool/image');
        
        $this->load->model('sale/order');

        $url = '';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
            $url = '&vendor_id=' . $vendor_id;
        } else {
            $this->redirect($this->url->link('error/not_found', 'token=' . $this->session->data['token']));
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['entry_account_id'] = $this->language->get('entry_account_id');
        $data['entry_can_receive'] = $this->language->get('entry_can_receive');
        $data['entry_is_verified'] = $this->language->get('entry_is_verified');
        $data['entry_last_verification_request_status'] = $this->language->get('entry_last_verification_request_status');
        $data['entry_balance'] = $this->language->get('entry_balance');
        $data['entry_balance_available_for_withdraw'] = $this->language->get('entry_balance_available_for_withdraw');
        $data['entry_auto_withdraw'] = $this->language->get('entry_auto_withdraw');
        $data['entry_commission_percent'] = $this->language->get('entry_commission_percent');
        $data['entry_payment_email_notification'] = $this->language->get('entry_payment_email_notification');
        

        $data['heading_title'] = $this->language->get('heading_title');        
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['column_description'] = $this->language->get('column_description');
        $data['column_invoice'] = $this->language->get('column_invoice');
        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_completed_orders'] = $this->language->get('column_completed_orders');
        $data['column_selling'] = $this->language->get('column_selling');
        $data['column_commision'] = $this->language->get('column_commision');

        $data['text_vendor'] = $this->language->get('text_vendor');

        $data['text_create_subaccount'] = $this->language->get('text_create_subaccount');
        $data['text_verify_subaccount'] = $this->language->get('text_verify_subaccount');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_orders'] = $this->language->get('tab_orders');
        $data['tab_stores'] = $this->language->get('tab_stores');
        $data['tab_vendor_data'] = $this->language->get('tab_vendor_data');
        $data['tab_subaccount'] = $this->language->get('tab_subaccount');
        $data['tab_package_info'] = $this->language->get('tab_package_info');
        $data['tab_statistics'] = $this->language->get('tab_statistics');
        $data['tab_wallet'] = $this->language->get('tab_wallet');
        
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_invoice'] = $this->language->get('entry_invoice');

        $data['entry_transfer_to_iugu'] = $this->language->get('entry_transfer_to_iugu');

        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_ip_address'] = $this->language->get('entry_ip_address');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $data['entry_commision'] = $this->language->get('entry_commision');
        $data['entry_account'] = $this->language->get('entry_account');
        $data['entry_business'] = $this->language->get('entry_business');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_tin_no'] = $this->language->get('entry_tin_no');
        $data['entry_orderprefix'] = $this->language->get('entry_orderprefix');
        $data['entry_mobile'] = $this->language->get('entry_mobile');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_store_name'] = $this->language->get('entry_store_name');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_priority'] = $this->language->get('entry_priority');
        $data['entry_activation_date'] = $this->language->get('entry_activation_date');
        $data['entry_active_upto_date'] = $this->language->get('entry_active_upto_date');

        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_heading'] = $this->language->get('text_heading');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_day'] = $this->language->get('text_day');
        $data['text_year'] = $this->language->get('text_year');
        $data['text_month'] = $this->language->get('text_month');      
        

        $data['entry_price_range'] = $this->language->get('entry_price_range'); 
        //$data['error_price_range'] = $this->language->get('error_price_range'); 
        $data['entry_physical_product'] = $this->language->get('entry_physical_product'); 
        $data['physical_product'] = $this->language->get('physical_product'); 
        $data['entry_automatic_transfer'] = $this->language->get('entry_automatic_transfer'); 
        $data['entry_bank'] = $this->language->get('entry_bank'); 
        $data['entry_bank_cc'] = $this->language->get('entry_bank_cc'); 
         
        
        $data['entry_cpf'] = $this->language->get('entry_cpf'); 

        $data['entry_cep'] = $this->language->get('entry_cep'); 
        
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['entry_business_type'] = $this->language->get('entry_business_type');
        $data['entry_person_type'] = $this->language->get('entry_person_type');

        $data['text_individual'] = $this->language->get('text_individual');
        $data['text_joined'] = $this->language->get('text_joined');

         $data['entry_account_type'] = $this->language->get('entry_account_type');

        $data['text_saving'] = $this->language->get('text_saving');
        $data['text_credit'] = $this->language->get('text_credit');

        $data['entry_city'] = $this->language->get('entry_city');
         
         $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_state'] = $this->language->get('entry_state');

        $data['entry_bank_ag'] = $this->language->get('entry_bank_ag');
        $vendor_info = $this->model_tool_image->getVendorDetails($vendor_id);

        //echo "<pre>";print_r($vendor_info);die;
        if (isset($this->error['error_price_range'])) {
            $data['error_price_range'] = $this->error['error_price_range'];
        } else {
            $data['error_price_range'] = '';
        }

        if (isset($this->error['error_bank'])) {
            $data['error_bank'] = $this->error['error_bank'];
        } else {
            $data['error_bank'] = '';
        }
        
        if (isset($this->error['error_bank_cc'])) {
            $data['error_bank_cc'] = $this->error['error_bank_cc'];
        } else {
            $data['error_bank_cc'] = '';
        }

        if (isset($this->error['error_name'])) {
            $data['error_name'] = $this->error['error_name'];
        } else {
            $data['error_name'] = '';
        }
        
        if (isset($this->error['error_address'])) {
            $data['error_address'] = $this->error['error_address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['error_cep'])) {
            $data['error_cep'] = $this->error['error_cep'];
        } else {
            $data['error_cep'] = '';
        }
        

        if (isset($this->error['error_telephone'])) {
            $data['error_telephone'] = $this->error['error_telephone'];
        } else {
            $data['error_telephone'] = '';
        }
        
        if (isset($this->error['error_city'])) {
            $data['error_city'] = $this->error['error_city'];
        } else {
            $data['error_city'] = '';
        }

        if (isset($this->error['error_bank_ag'])) {
            $data['error_bank_ag'] = $this->error['error_bank_ag'];
        } else {
            $data['error_bank_ag'] = '';
        }

        if (isset($this->error['error_state'])) {
            $data['error_state'] = $this->error['error_state'];
        } else {
            $data['error_state'] = '';
        }

        if (isset($this->error['error_cpf'])) {
            $data['error_cpf'] = $this->error['error_cpf'];
        } else {
            $data['error_cpf'] = '';
        }
        

        if (isset($this->request->post['bank'])) {
            $data['bank'] = $this->request->post['bank'];
        } elseif (isset($vendor_id)) {         
            $data['bank'] = isset($vendor_info['bank'])?$vendor_info['bank']:'';            
        } else {
            $data['bank'] = '';
        }

        if (isset($this->request->post['cpf'])) {
            $data['cpf'] = $this->request->post['cpf'];
        } elseif (isset($vendor_id)) {         
            $data['cpf'] = isset($vendor_info['cpf'])?$vendor_info['cpf']:'';            
        } else {
            $data['cpf'] = '';
        }

        if (isset($this->request->post['bank_ag'])) {
            $data['bank_ag'] = $this->request->post['bank_ag'];
        } elseif (isset($vendor_id)) {         
            $data['bank_ag'] = isset($vendor_info['bank_ag'])?$vendor_info['bank_ag']:'';            
        } else {
            $data['bank_ag'] = '';
        }

        if (isset($this->request->post['bank_cc'])) {
            $data['bank_cc'] = $this->request->post['bank_cc'];
        } elseif (isset($vendor_id)) {         
            $data['bank_cc'] = isset($vendor_info['bank_cc'])?$vendor_info['bank_cc']:'';            
        } else {
            $data['bank_cc'] = '';
        }
        

        if (isset($this->request->post['cep'])) {
            $data['cep'] = $this->request->post['cep'];
        } elseif (isset($vendor_id)) {         
            $data['cep'] = isset($vendor_info['zipcode'])?$vendor_info['zipcode']:'';            
        } else {
            $data['cep'] = '';
        }


        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($vendor_id)) {            
            
            $vendor_fullname = $this->model_tool_image->getVendor($vendor_id);
            $data['name'] = isset($vendor_fullname['vendor_name'])?$vendor_fullname['vendor_name']:'';            
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } elseif (isset($vendor_id)) {         
            $data['city'] = isset($vendor_info['city'])?$vendor_info['city']:'';            
        } else {
            $data['city'] = '';
        }
        
        if (isset($this->request->post['state'])) {
            $data['state'] = $this->request->post['state'];
        } elseif (isset($vendor_id)) {         
            $data['state'] = isset($vendor_info['state'])?$vendor_info['state']:'';            
        } else {
            $data['state'] = '';
        }

        if (isset($this->request->post['account_type'])) {
            $data['account_type'] = $this->request->post['account_type'];
        } elseif (isset($vendor_id)) {         
            $data['account_type'] = isset($vendor_info['account_type'])?$vendor_info['account_type']:'';            
        } else {
            $data['account_type'] = '';
        }

        

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (isset($vendor_id)) {         
            $data['telephone'] = isset($vendor_info['telephone'])?$vendor_info['telephone']:'';            
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (isset($vendor_id)) {            
            
                      
            $data['address'] = isset($vendor_info['address'])?$vendor_info['address']:'';            
        } else {
            $data['address'] = '';
        }


        if (isset($this->request->post['automatic_transfer'])) {
            $data['automatic_transfer'] = $this->request->post['automatic_transfer'];
        } elseif (isset($vendor_id)) {            
            $data['automatic_transfer'] = isset($vendor_info['automatic_transfer'])?$vendor_info['automatic_transfer']:'';            
        } else {
            $data['automatic_transfer'] = '';
        }

        if (isset($this->request->post['bank_cc'])) {
            $data['bank_cc'] = $this->request->post['bank_cc'];
        } elseif (isset($vendor_id)) {            
            $data['bank_cc'] = isset($vendor_info['bank_cc'])?$vendor_info['bank_cc']:'';            
        } else {
            $data['bank_cc'] = '';
        }


        if (isset($this->request->post['business_type'])) {
            $data['business_type'] = $this->request->post['business_type'];
        } elseif (isset($vendor_id)) {            
            
            $data['business_type'] = isset($vendor_info['business_type'])?$vendor_info['business_type']:'';            
        } else {
            $data['business_type'] = '';
        }

        if (isset($this->request->post['price_range'])) {
            $data['price_range'] = $this->request->post['price_range'];
        } elseif (isset($vendor_id)) {            
            $data['price_range'] = isset($vendor_info['price_range'])?$vendor_info['price_range']:'';            
        } else {
            $data['price_range'] = '';
        }

        $data['button_credit_add'] = $this->language->get('button_credit_add');
        
        $data['cancel'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'], 'SSL');
         
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }


        //orders 
        $data['orders'] = array();

        $filter_data = array(
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10,
            'vendor_id' => $vendor_id
        );

        $results = $this->model_vendor_vendor->getVendorOrders($filter_data);

        foreach ($results as $result) {

            $data['orders'][] = array(
                'order_id' => $result['order_id'],
                
                'store_id' => $result['store_id'],
                'customer' => $result['customer'],
                'status' =>  $this->model_sale_order->getStatus($result['order_status_id']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'info'=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'] . '&order_id=' . $result['order_id'], 'SSL')
            );
        }

        $data['subaccount_action'] = $this->url->link('vendor/vendor/subaccount', 'token=' . $this->session->data['token'], 'SSL');

        $data['verify_subaccount_action'] = $this->url->link('vendor/vendor/verifySubAccount', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->error['vendor_id'])) {
            $data['error_vendor_id'] = $this->error['vendor_id'];
        } else {
            $data['error_vendor_id'] = '';
        }

        if (isset($this->error['commision'])) {
            $data['error_commision'] = $this->error['commision'];
        } else {
            $data['error_commision'] = '';
        }


        if (isset($this->request->post['vendor_name'])) {
            $data['vendor_name'] = $this->request->post['vendor_name'];
        } elseif (isset($store_info['vendor_id'])) {            
            
            $vendor_info = $this->model_tool_image->getVendor($data['vendor_id']);          

            $data['vendor_name'] = isset($vendor_info['vendor_name'])?$vendor_info['vendor_name']:'';            
        } else {
            $data['vendor_name'] = '';
        }

        //get package 
        $data['package'] = $this->model_sale_order->getVendorToPackages($vendor_id);

        $pagination = new Pagination();
        $pagination->total = $this->model_vendor_vendor->getVendorTotalOrders($filter_data);
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        //echo "<pre>";print_r("ew");die;
        //basic info 
        $data['user'] = $this->model_vendor_vendor->getUser($vendor_id);

        //echo "<pre>";print_r($data['user']);die;
        //store details 
        $data['stores'] = array();
        $stores =  $this->model_sale_order->getStoreDatas($vendor_id);

        foreach ($stores as $store) {
            $store['categories'] = $this->model_vendor_vendor->getStoreCategories($store['store_id']);
            $data['stores'][] = $store;
        }

        $data['statistics'] =  array(
            'orders' => $this->model_sale_order->getTotalOrdersByVendor($vendor_id),
            'selling' => $this->model_sale_order->getTotalSellingByVendor($vendor_id),
            'commision' => $this->model_sale_order->getTotalCommisionByVendor($vendor_id),
        );

        $data['token'] = $this->session->data['token'];

        $data['vendor_id'] = $vendor_id;

        $data['vendor_name'] = $data['user']['firstname']." ".$data['user']['lastname'] ;
        
        $data['subaccount'] = $this->model_vendor_vendor->getSubAccountDetails($vendor_id);

        $data['subaccount_created'] = false;

        $data['verification_sent'] = false;
        $data['subaccount_commision'] = 0;
        //echo "<pre>";print_r($data['subaccount']);die;
        if(count($data['subaccount']) >= 1 && !empty($data['subaccount']['user_token'])) {
            $data['subaccount_created'] = true;

            $data['subaccount_commision'] = $data['subaccount']['commision'];

            $data['verification_status'] = $this->getIuguVerificationStatus($data['subaccount']);


            if(count($data['verification_status']) > 0) {

                if($data['verification_status']['last_verification_request_status']) {
                    //echo "if";
                    $data['verification_sent'] = true;
                }
            //echo "<pre>";print_r($data['verification_status']);die;
            }
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/vendor_info.tpl', $data));
    }
    
    public function city_autocomplete(){
        
        $this->load->model('sale/order');

        $json =  $this->model_sale_order->getCitiesLike($this->request->get['filter_name']);
        header('Content-type: text/json');
        echo json_encode($json);
    }


    public function loginas(){
         $this->load->model('vendor/vendor');
        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = 0;        
        }

        $data['vendor'] = $this->model_vendor_vendor->getUser($vendor_id);
        // validate user is admin or not
        if ($this->user->isLogged()) {
            $this->user->logout();
            unset($this->session->data['user_id']);
            $this->user->loginAsVendor($data['vendor']['username']);
            $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
        }else{
            $this->response->redirect($this->url->link('common/login', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function subaccount() {

        
        $this->load->model('setting/store');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSubAccountForm()) {
            
            //echo "<pre>";print_r($this->request->post);die;
            $this->createSubAccount($this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_subaccount_success');

            $this->response->redirect($this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->post['vendor_id'], 'SSL'));
        }

        $this->session->data['error'] = 'Create SubAccount form has error';

        $this->response->redirect($this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->post['vendor_id'], 'SSL'));

    }

    public function verifySubAccount() {

        
        $this->load->model('setting/store');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateVerifySubAccountForm()) {
            
            //echo "<pre>";print_r("verer");die;
            $result = $this->verifySubAccountAPI($this->request->post);

            if($result['status']) {
                $this->session->data['success'] = $this->language->get('text_verifysubaccount_success');    
            }

            $this->session->data['error'] = 'Error something';//$this->language->get('text_verifysubaccount_success');

           // 'info' => $this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $result['user_id'] . $url, 'SSL'),

            //$this->response->redirect($this->url->link('vendor/vendor', 'token=' . $this->session->data['token'], 'SSL'));
            $this->response->redirect($this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->post['vendor_id'], 'SSL'));
        }

        $this->session->data['error'] = 'Request verification form has error';

        $this->response->redirect($this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->post['vendor_id'], 'SSL'));

        //echo "<pre>";print_r("iotu");die;
    }

    protected function validateSubAccountForm() {

        if (!$this->user->hasPermission('modify', 'setting/store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        //vendor if field only for admin 
        if (!$this->user->isVendor() && !$this->request->post['vendor_id']) {
            $this->error['vendor_id'] = $this->language->get('error_vendor_id');
        }
        
        if (!$this->user->isVendor()) {
            if (!$this->request->post['commision']) {
                $this->error['commision'] = $this->language->get('error_commision');
            }

        }
        
        return !$this->error;
    }

    protected function validateVerifySubAccountForm() {

        if (!$this->user->hasPermission('modify', 'setting/store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        //vendor if field only for admin 
        if (!$this->user->isVendor() && !$this->request->post['vendor_id']) {
            $this->error['vendor_id'] = $this->language->get('error_vendor_id');
        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['price_range']) {
                $this->error['price_range'] = $this->language->get('error_price_range');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['person_type']) {
                $this->error['person_type'] = $this->language->get('error_person_type');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['physical_products']) {
                $this->error['physical_product'] = $this->language->get('error_physical_product');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['business_type']) {
                $this->error['business_type'] = $this->language->get('error_business_type');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['automatic_transfer']) {
                $this->error['automatic_transfer'] = $this->language->get('error_automatic_transfer');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['cpf']) {
                $this->error['cpf'] = $this->language->get('error_cpf');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['bank_cc']) {
                $this->error['bank_cc'] = $this->language->get('error_bank_cc');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['bank']) {
                $this->error['bank'] = $this->language->get('error_bank');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['cep']) {
                $this->error['cep'] = $this->language->get('error_cep');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['city']) {
                $this->error['city'] = $this->language->get('error_city');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['state']) {
                $this->error['state'] = $this->language->get('error_state');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['account_type']) {
                $this->error['account_type'] = $this->language->get('error_account_type');
            }

        }

        if (!$this->user->isVendor()) {
            if (!$this->request->post['bank_ag']) {
                $this->error['bank_ag'] = $this->language->get('error_bank_ag');
            }

        }
     
        return !$this->error;
    }

    public function createSubAccount($data) {

        //live key 67ff0666d626234797f4a6f65095df8c
        // market place live key ea9924eb230ea73962f5269367bdea1c

        $name = $data['vendor_name'];
        $commision = $data['commision'];
        $vendor_id = $data['vendor_id'];

        $data['name'] = $data['vendor_name'];
        $data['commission_percent'] = $data['commision'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.iugu.com/v1/marketplace/create_account");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'ea9924eb230ea73962f5269367bdea1c');

        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //echo "<pre>";print_r($result);die;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        //save data to table iugu_sub_account
        $result = json_decode($result,1);
        $result['vendor_id'] = $vendor_id;
        $result['commision'] = $commision;
        $this->load->model('payment/iugu_subaccount');

        $this->model_payment_iugu_subaccount->saveIuguSubAccount($result);
    }

    public function verifySubAccountAPI($datas) {

        $resp['status'] = false;
        $this->load->model('vendor/vendor');

        $subaccount = $this->model_vendor_vendor->getSubAccountDetails($datas['vendor_id']);

        $vendorDetails = $this->model_vendor_vendor->getVendorDetails($datas['vendor_id']);

        
        if(count($subaccount) >= 1 && $vendorDetails) {
            
            $datas['name'] = $vendorDetails['firstname']. " ". $vendorDetails['lastname'];
            $datas['address'] = $vendorDetails['address'];
            $datas['telephone'] = $vendorDetails['telephone'];

            $subAccountUserToken = $subaccount['user_token'];
            $accountId = $subaccount['account_id'];

            $datas['automatic_validation'] = 1;


            //$accountId = 'F1BC994EC1FF470B9E51E0D173AD630E';
            //$subAccountUserToken = 'b90c050ae13b43e4eca45bf796a73e6c';

            $ch = curl_init();

            // $datas = array(


            //     'price_range'=> 'Entre R$ 50,00 e R$ 500,00',

            //     'physical_products'=>true,

            //     'business_type'=> 'Supermercado online',
            //     'person_type'=>'Pessoa Física',

            //     'automatic_transfer'=> true,
            //     'cpf'=>'091.087.949-44',

            //     'name'=> 'Larissa muchailh villar',
            //     'address'=>'Rua Cel.Dulcidio 1101',

            //     'cep'=> '80420-170',
            //     'city'=>'Curitiba',
            //     'state'=> 'Paraná',
            //     'telephone'=>'+55 (41) 9981-49662',
            //     'bank'=> 'Banco do Brasil',
            //     'bank_ag'=>'1522-9',
            //     'account_type'=> 'Corrente',
            //     'bank_cc'=>'33.845-1',
            //     'automatic_validation' => 1,
            // );


            $data['data'] = $datas;

            curl_setopt($ch, CURLOPT_URL, "https://api.iugu.com/v1/accounts/".$accountId."/request_verification");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $subAccountUserToken);

            $headers = array();
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);

            //echo "<pre>";print_r($result);die;
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);

            $resp['status'] = true;
        }  

        return $resp;      
    }

    public function getIuguVerificationStatus($data) {

        //live key 67ff0666d626234797f4a6f65095df8c
        // market place live key ea9924eb230ea73962f5269367bdea1c

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.iugu.com/v1/accounts/".$data['account_id']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        /*curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);*/
        curl_setopt($ch, CURLOPT_USERPWD, $data['user_token']);

        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        //save data to table iugu_sub_account
        $result = json_decode($result,1);
        
        return $result;
    }

    public function stripeDisconnect() {

        $json['status'] = false;

        if($this->config->get('stripe_environment') == 'live') {
            $api_key = $this->config->get('stripe_live_secret_key');
        } else {
            $api_key = $this->config->get('stripe_test_secret_key');
        }

        // Using curl: https://secure.php.net/manual/en/book.curl.php
        //$api_key = 'sk_test_yTxJsljyEDiJpDD5FiTknr91';
        $publishable_key = $this->config->get('stripe_connect_platform_id');//'ca_BoxbgCghYSt10rKtPzsW6WCLQ7nEEXIz';

        
        $this->load->model('payment/stripe');

        $stripe_info = $this->model_payment_stripe->getVendorStripeAccount($this->request->get['vendor_id']);

        if($stripe_info) {
            
            $stripe_user_id = $stripe_info['stripe_user_id'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://connect.stripe.com/oauth/deauthorize',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_HTTPHEADER => array("Authorization: Bearer $api_key"),
              CURLOPT_POST => true,
              CURLOPT_POSTFIELDS => http_build_query(array(
                'client_id' => $publishable_key,
                'stripe_user_id' => $stripe_user_id,
              ))
            ));
            $curl_res = curl_exec($curl);

            //echo "<pre>";print_r($curl_res);die;
            $json['status'] = true;

            // delete from db stripe_user_id for the vendor
            $this->model_payment_stripe->deleteVendorStripeAccount($this->request->get['vendor_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }
}