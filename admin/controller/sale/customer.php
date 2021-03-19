<?php

class ControllerSaleCustomer extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $this->load->language('account/address');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        // $this->load->model('account/address');
    }

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_excel($data);
    }

    public function add() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->request->post['source'] = 'WEB';
            $customer_id = $this->model_sale_customer->addCustomer($this->request->post);



            if (!empty($data['send_email'])) {
                /* EMAIL SENDING WHEN CREATING USER FROM ADMIN PORTAL */

                $t = $this->model_sale_customer->sendCustomerRegisterMail($this->request->post);
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Customer created successfully!';

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'customer_id' => $customer_id,
            ];
            $log->write('customer add');

            $this->model_user_user_activity->addActivity('customer_add', $activity_data);

            $log->write('customer add');

            $url = '';

            if (isset($this->request->get['filter_company'])) {
                $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_customer_group_id'])) {
                $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }
            
            if (isset($this->request->get['filter_parent_customer'])) {
                $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
            }
            
            if (isset($this->request->get['filter_parent_customer_id'])) {
                $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
            }
            
            if (isset($this->request->get['filter_account_manager_name'])) {
                $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
            }
            
            if (isset($this->request->get['filter_account_manager_id'])) {
                $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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
                $this->response->redirect($this->url->link('sale/customer/edit', 'customer_id=' . $customer_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('sale/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $date = $this->request->post['dob'];
            if (!empty($date)) {
                //echo "<pre>";print_r($date);die;

                $date = DateTime::createFromFormat('d/m/Y', $date);

                $this->request->post['dob'] = $date->format('Y-m-d');
            } else {
                $this->request->post['dob'] = null;
            }

            //echo "<pre>";print_r($this->request->post);die;
            $this->model_sale_customer->editCustomer($this->request->get['customer_id'], $this->request->post);
            $customer_device_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
            if(is_array($customer_device_info) && array_key_exists('customer_id', $customer_device_info) && array_key_exists('device_id', $customer_device_info) && $customer_device_info['customer_id'] > 0 && $customer_device_info['device_id'] != NULL) {
            //$sen['customer_id'] = '';
            //$ret = $this->emailtemplate->sendDynamicPushNotification($customer_device_info['customer_id'], $customer_device_info['device_id'], 'Customer Category Prices Updated', 'Customer Category Prices Updated', $sen);
            $this->load->model('account/customer');
            $this->model_account_customer->sendCustomerByCategoryPriceNotification($customer_device_info);
            }
            $this->session->data['success'] = $this->language->get('text_success');
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'customer_id' => $this->request->get['customer_id'],
            ];
            $log->write('customer edit');

            $this->model_user_user_activity->addActivity('customer_edit', $activity_data);

            $log->write('customer edit');

            $url = '';

            if (isset($this->request->get['filter_company'])) {
                $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_customer_group_id'])) {
                $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }
            
            if (isset($this->request->get['filter_parent_customer'])) {
                $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
            }
            
            if (isset($this->request->get['filter_parent_customer_id'])) {
                $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
            }
            
            if (isset($this->request->get['filter_account_manager_name'])) {
                $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
            }
            
            if (isset($this->request->get['filter_account_manager_id'])) {
                $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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
                $this->response->redirect($this->url->link('sale/customer/edit', 'customer_id=' . $this->request->get['customer_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('sale/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $customer_id) {
                $this->model_sale_customer->deleteCustomer($customer_id);

                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'customer_id' => $customer_id,
                ];
                $log->write('customer delete');

                $this->model_user_user_activity->addActivity('customer_delete', $activity_data);

                $log->write('customer delete');
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Customer(s) deleted successfully!';

            $url = '';

            if (isset($this->request->get['filter_company'])) {
                $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_customer_group_id'])) {
                $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }
            
            if (isset($this->request->get['filter_parent_customer'])) {
                $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
            }
            
            if (isset($this->request->get['filter_parent_customer_id'])) {
                $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
            }
            
            if (isset($this->request->get['filter_account_manager_name'])) {
                $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
            }
            
            if (isset($this->request->get['filter_account_manager_id'])) {
                $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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

            $this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function approve() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        $customers = [];

        if (isset($this->request->post['selected'])) {
            $customers = $this->request->post['selected'];
        } elseif (isset($this->request->get['customer_id'])) {
            $customers[] = $this->request->get['customer_id'];
        }

        if ($customers && $this->validateApprove()) {
            $this->model_sale_customer->approve($this->request->get['customer_id']);
            
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'customer_id' => $this->request->get['customer_id'],
            ];
            $log->write('customer approve');

            $this->model_user_user_activity->addActivity('customer_account_approved', $activity_data);

            $log->write('customer approve');

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_company'])) {
                $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_customer_group_id'])) {
                $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }
            
            if (isset($this->request->get['filter_parent_customer'])) {
                $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
            }
            
            if (isset($this->request->get['filter_parent_customer_id'])) {
                $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
            }
            
            if (isset($this->request->get['filter_account_manager_name'])) {
                $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
            }
            
            if (isset($this->request->get['filter_account_manager_id'])) {
                $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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

            $this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function unlock() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        if (isset($this->request->get['email']) && $this->validateUnlock()) {
            $this->model_sale_customer->deleteLoginAttempts($this->request->get['email']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';


            if (isset($this->request->get['filter_company'])) {
                $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_customer_group_id'])) {
                $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_approved'])) {
                $url .= '&filter_approved=' . $this->request->get['filter_approved'];
            }

            if (isset($this->request->get['filter_ip'])) {
                $url .= '&filter_ip=' . $this->request->get['filter_ip'];
            }
            
            if (isset($this->request->get['filter_parent_customer'])) {
                $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
            }
            
            if (isset($this->request->get['filter_parent_customer_id'])) {
                $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
            }
            
            if (isset($this->request->get['filter_account_manager_name'])) {
                $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
            }
            
            if (isset($this->request->get['filter_sub_customer_show'])) {
                $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
            }
            
            if (isset($this->request->get['filter_account_manager_id'])) {
                $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
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

            $this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        $this->load->language('sale/customer');


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_telephone'])) {
            $filter_telephone = $this->request->get['filter_telephone'];
        } else {
            $filter_telephone = null;
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $filter_customer_group_id = $this->request->get['filter_customer_group_id'];
        } else {
            $filter_customer_group_id = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_approved'])) {
            $filter_approved = $this->request->get['filter_approved'];
        } else {
            $filter_approved = null;
        }

        if (isset($this->request->get['filter_ip'])) {
            $filter_ip = $this->request->get['filter_ip'];
        } else {
            $filter_ip = null;
        }
        
        if (isset($this->request->get['filter_parent_customer'])) {
            $filter_parent_customer = $this->request->get['filter_parent_customer'];
        } else {
            $filter_parent_customer = null;
        }
        
        if (isset($this->request->get['filter_parent_customer_id'])) {
            $filter_parent_customer_id = $this->request->get['filter_parent_customer_id'];
        } else {
            $filter_parent_customer_id = null;
        }
        
        if (isset($this->request->get['filter_account_manager_name'])) {
            $filter_account_manager_name = $this->request->get['filter_account_manager_name'];
        } else {
            $filter_account_manager_name = null;
        }
        
        if (isset($this->request->get['filter_account_manager_id'])) {
            $filter_account_manager_id = $this->request->get['filter_account_manager_id'];
        } else {
            $filter_account_manager_id = null;
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
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

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }
        
        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }
        
        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }
        
        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }
        
        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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
            'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('sale/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['customers'] = [];

        $filter_data = [
            'filter_company' => $filter_company,
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_telephone' => $filter_telephone,
            'filter_customer_group_id' => $filter_customer_group_id,
            'filter_status' => $filter_status,
            'filter_approved' => $filter_approved,
            'filter_date_added' => $filter_date_added,
            'filter_ip' => $filter_ip,
            'filter_parent_customer' => $filter_parent_customer,
            'filter_parent_customer_id' => $filter_parent_customer_id,
            'filter_account_manager_name' => $filter_account_manager_name,
            'filter_account_manager_id' => $filter_account_manager_id,
            'filter_sub_customer_show' => $filter_sub_customer_show,
            'filter_monthyear_added' => $this->request->get['filter_monthyear_added'],
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $customer_total = $this->model_sale_customer->getTotalCustomers($filter_data);

        $results = $this->model_sale_customer->getCustomers($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if (!$result['approved']) {
                $approve = $this->url->link('sale/customer/approve', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL');
            } else {
                $approve = '';
            }

            $login_info = $this->model_sale_customer->getTotalLoginAttempts($result['email']);

            if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
                $unlock = $this->url->link('sale/customer/unlock', 'token=' . $this->session->data['token'] . '&email=' . $result['email'] . $url, 'SSL');
            } else {
                $unlock = '';
            }

            $country_code = '+' . $this->config->get('config_telephone_code');
            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }

            $data['customers'][] = [
                'customer_id' => $result['customer_id'],
                'name' => $result['name'],
                'company_name' => $result['company_name'],
                'email' => $result['email'],
                'telephone' => $country_code . $result['telephone'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'source' => $result['source'],
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'approve' => $approve,
                'unlock' => $unlock,
                'edit' => $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
                'customer_view' => $this->url->link('sale/customer/view_customer', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
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
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_approved'] = $this->language->get('column_approved');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['entry_company_name'] = $this->language->get('entry_company_name');
        $data['entry_company_address'] = $this->language->get('entry_company_address');

        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_parent_customer'] = 'Parent Customer Name';
        $data['entry_account_manager_name'] = 'Account Manager Name';
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_approve'] = $this->language->get('button_approve');
        $data['button_verify'] = $this->language->get('button_verify');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_unlock'] = $this->language->get('button_unlock');

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



        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }
        
        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }
        
        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }
        
        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }
        
        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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

        $data['sort_name'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_customer_group'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_ip'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }
        
        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }
        
        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }
        
        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }
        
        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_company'] = $filter_company;
        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_telephone'] = $filter_telephone;
        $data['filter_customer_group_id'] = $filter_customer_group_id;
        $data['filter_status'] = $filter_status;
        $data['filter_approved'] = $filter_approved;
        $data['filter_ip'] = $filter_ip;
        $data['filter_parent_customer'] = $filter_parent_customer;
        $data['filter_parent_customer_id'] = $filter_parent_customer_id;
        $data['filter_account_manager_name'] = $filter_account_manager_name;
        $data['filter_account_manager_id'] = $filter_account_manager_id;
        $data['filter_sub_customer_show'] = $filter_sub_customer_show;
        $data['filter_date_added'] = $filter_date_added;

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->load->model('user/accountmanager');
        $data['accountmanagers'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->response->setOutput($this->load->view('sale/customer_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_referred_by'] = $this->language->get('entry_referred_by');

        $data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');
        $data['entry_gender'] = $this->language->get('entry_gender');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['entry_company_name'] = $this->language->get('entry_company_name');
        $data['entry_company_address'] = $this->language->get('entry_company_address');

        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_safe'] = $this->language->get('entry_safe');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_default'] = $this->language->get('entry_default');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_points'] = $this->language->get('entry_points');
        $data['entry_send_email'] = $this->language->get('entry_send_email');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_contact_no'] = $this->language->get('entry_contact_no');
        $data['entry_address'] = $this->language->get('entry_address');

        $data['help_safe'] = $this->language->get('help_safe');
        $data['help_points'] = $this->language->get('help_points');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_address_add'] = $this->language->get('button_address_add');
        $data['button_history_add'] = $this->language->get('button_history_add');
        $data['button_credit_add'] = $this->language->get('button_credit_add');
        $data['button_reward_add'] = $this->language->get('button_reward_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_address'] = $this->language->get('tab_address');
        $data['tab_history'] = $this->language->get('tab_history');
        $data['tab_credit'] = $this->language->get('tab_credit');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_referral'] = $this->language->get('tab_referral');
        $data['tab_sub_customer'] = $this->language->get('tab_sub_customer');
        $data['tab_ip'] = $this->language->get('tab_ip');

        $data['token'] = $this->session->data['token'];

        $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');
        $data['text_stree_society_office'] = $this->language->get('text_stree_society_office');
        $data['label_zipcode'] = $this->language->get('label_zipcode');
        $data['text_locality'] = $this->language->get('text_locality');

        if (isset($this->request->get['customer_id'])) {
            $data['customer_id'] = $this->request->get['customer_id'];
        } else {
            $data['customer_id'] = 0;
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

        if (isset($this->error['dob'])) {
            $data['error_dob'] = $this->error['dob'];
        } else {
            $data['error_dob'] = '';
        }

        if (isset($this->error['gender'])) {
            $data['error_gender'] = $this->error['gender'];
        } else {
            $data['error_gender'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['company_name'])) {
            $data['error_company_name'] = $this->error['company_name'];
        } else {
            $data['error_company_name'] = '';
        }

        if (isset($this->error['company_address'])) {
            $data['error_company_address'] = $this->error['company_address'];
        } else {
            $data['error_company_address'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
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

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = [];
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }
        
        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }
        
        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }
        
        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }
        
        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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
            'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['customer_id'])) {
            $data['action'] = $this->url->link('sale/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['parent_user_name'] = NULL;
        $data['parent_user_email'] = NULL;
        $data['parent_user_phone'] = NULL;
        if (isset($this->request->get['customer_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
            $data['customer_category'] = $customer_info['customer_category'];
            $data['customer_category_disabled'] = '';
            $customer_parent_info = $this->model_sale_customer->getCustomerParentDetails($this->request->get['customer_id']);
            $customer_account_manager_info = $this->model_sale_customer->getCustomerAccountManagerDetails($this->request->get['customer_id']);
            if ($customer_parent_info != NULL) {
                $data['parent_user_name'] = $customer_parent_info['firstname'] . '' . $customer_parent_info['lastname'];
                $data['parent_user_email'] = $customer_parent_info['email'];
                $data['parent_user_phone'] = $customer_parent_info['telephone'];
                $data['customer_category'] = $customer_parent_info['customer_category'];
                $data['customer_category_disabled'] = 'disabled';
            }
            
            if ($customer_account_manager_info != NULL) {
                $data['account_manager_name'] = $customer_account_manager_info['firstname'] . '' . $customer_account_manager_info['lastname'];
                $data['account_manager_email'] = $customer_account_manager_info['email'];
                $data['account_manager_phone'] = $customer_account_manager_info['telephone'];
            }

            //$log = new Log('error.log');
            //$log->write($customer_parent_info);
        }

        //echo "<pre>";print_r($customer_info);die;
        $this->load->model('sale/customer_group');
        $filter_data = [
            'filter_parent' => $this->request->get['customer_id'],
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1000,
        ];
        $data['sub_users'] = $this->model_sale_customer->getSubCustomers($filter_data);
        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        $data['price_categories'] = $this->model_sale_customer_group->getPriceCategories();

        if (isset($this->request->post['company_name'])) {
            $data['company_name'] = $this->request->post['company_name'];
        } elseif (!empty($customer_info)) {
            $data['company_name'] = $customer_info['company_name'];
        } else {
            $data['company_name'] = '';
        }

        if (isset($this->request->post['company_address'])) {
            $data['company_address'] = $this->request->post['company_address'];
        } elseif (!empty($customer_info)) {
            $data['company_address'] = $customer_info['company_address'];
        } else {
            $data['company_address'] = '';
        }

        if (isset($this->request->post['customer_group_id'])) {
            $data['customer_group_id'] = $this->request->post['customer_group_id'];
        } elseif (!empty($customer_info)) {
            $data['customer_group_id'] = $customer_info['customer_group_id'];
        } else {
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        if (isset($this->request->post['customer_category'])) {
            $data['customer_category'] = $this->request->post['customer_category'];
        } elseif (!empty($customer_info) && $customer_parent_info == NULL) {
            $data['customer_category'] = $customer_info['customer_category'];
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($customer_info)) {
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($customer_info)) {
            $data['lastname'] = $customer_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($customer_info)) {
            $data['telephone'] = $customer_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif (!empty($customer_info)) {
            $data['fax'] = $customer_info['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->request->post['sex'])) {
            $data['gender'] = $this->request->post['sex'];
        } elseif (!empty($customer_info)) {
            $data['gender'] = $customer_info['gender'];
        } else {
            $data['gender'] = '';
        }

        if (isset($this->request->post['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($this->request->post['dob']));
        } elseif (!empty($customer_info['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
        } else {
            $data['dob'] = null;
        }

        //echo "<pre>";print_r($data);die;

        if (isset($this->request->post['send_email'])) {
            $data['send_email'] = $this->request->post['send_email'];
        } elseif (!empty($customer_info)) {
            $data['send_email'] = $customer_info['email'];
        } else {
            $data['send_email'] = '';
        }

        $data['show_send_email'] = '';

        if (!isset($this->request->get['customer_id'])) {
            $data['show_send_email'] = true;
        }

        if (isset($this->request->post['custom_field'])) {
            $data['account_custom_field'] = $this->request->post['custom_field'];
        } elseif (!empty($customer_info)) {
            $data['account_custom_field'] = unserialize($customer_info['custom_field']);
        } else {
            $data['account_custom_field'] = [];
        }

        if (isset($this->request->post['newsletter'])) {
            $data['newsletter'] = $this->request->post['newsletter'];
        } elseif (!empty($customer_info)) {
            $data['newsletter'] = $customer_info['newsletter'];
        } else {
            $data['newsletter'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($customer_info)) {
            $data['status'] = $customer_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['approved'])) {
            $data['approved'] = $this->request->post['approved'];
        } elseif (!empty($customer_info)) {
            $data['approved'] = $customer_info['approved'];
        } else {
            $data['approved'] = true;
        }

        if (isset($this->request->post['safe'])) {
            $data['safe'] = $this->request->post['safe'];
        } elseif (!empty($customer_info)) {
            $data['safe'] = $customer_info['safe'];
        } else {
            $data['safe'] = 0;
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } elseif (!empty($customer_info)) {
            //else if is added ,because while editing of some other fields in screen,
            // password field is asking to enter password again
            $data['password'] = 'default';
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } elseif (!empty($customer_info)) {
            $data['confirm'] = 'default';
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['address'])) {
            $data['addresses'] = $this->request->post['address'];
        } elseif (isset($this->request->get['customer_id'])) {
            $data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
        } else {
            $data['addresses'] = [];
        }

        $data['referee'] = [];
        if (!empty($customer_info)) {
            $data['referee'] = $this->model_sale_customer->getCustomer($customer_info['refree_user_id']);

            $data['referee_link'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_info['refree_user_id'], 'SSL');
        }

        //echo "<pre>";print_r($data);die;
        //echo "<pre>";print_r($this->request->post);die;
        if (isset($this->request->post['address_id'])) {
            $data['address_id'] = $this->request->post['address_id'];
        } elseif (!empty($customer_info)) {
            $data['address_id'] = $customer_info['address_id'];
        } else {
            $data['address_id'] = '';
        }
        $data['source'] = $customer_info['source'];


        if (isset($this->request->post['SAP_customer_no'])) {
            $data['SAP_customer_no'] = $this->request->post['SAP_customer_no'];
        } elseif (!empty($customer_info)) {
            $data['SAP_customer_no'] = $customer_info['SAP_customer_no'];
        } else {
            $data['SAP_customer_no'] = '';
        }

        // $data['SAP_customer_no'] = $customer_info['SAP_customer_no'];
        $this->load->model('sale/customer_group');

        //echo "<pre>";print_r($data);die;
        $data['cities'] = $this->model_sale_customer_group->getCities();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/customer_form.tpl', $data));
    }

    protected function validateForm() {
        //echo "<pre>";print_r($this->request->post);die;
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['company_name']) < 1)) {
            $this->error['company_name'] = $this->language->get('error_company_name');
        }
        if ((utf8_strlen($this->request->post['company_address']) < 1)) {
            $this->error['company_address'] = $this->language->get('error_company_address');
        }

        $customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->post['email']);

        if (!isset($this->request->get['customer_id'])) {
            if ($customer_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['customer_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if (isset($this->request->post['address'])) {
            foreach ($this->request->post['address'] as $key => $value) {
                if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 32)) {
                    $this->error['address'][$key]['name'] = $this->language->get('error_name');
                }

                /* if (empty($value['zipcode']) || !is_numeric($value['zipcode'])) {
                  $this->error['address'][$key]['zipcode'] = $this->language->get('error_zipcode');
                  } */

                // if (empty($value['flat_number'])) {
                //     $this->error['address'][$key]['flat_number'] = $this->language->get('error_flat_number');
                // }
                // if (empty($value['building_name'])) {
                //     $this->error['address'][$key]['building_name'] = $this->language->get('error_building_name');
                // }
                // if (empty($value['landmark'])) {
                //     $this->error['address'][$key]['landmark'] = $this->language->get('error_landmark');
                // }
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateApprove() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateUnlock() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateHistory() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['comment']) || utf8_strlen($this->request->post['comment']) < 1) {
            $this->error['warning'] = $this->language->get('error_comment');
        }

        return !$this->error;
    }

    public function login() {
        $json = [];

        if (isset($this->request->get['customer_id'])) {
            $customer_id = $this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        $this->load->model('sale/customer');

        $customer_info = $this->model_sale_customer->getCustomer($customer_id);

        if ($customer_info) {
            $token = md5(mt_rand());

            $this->model_sale_customer->editToken($customer_id, $token);

            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
            } else {
                $store_id = 0;
            }

            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($store_id);
            
            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $customer_info['customer_id'],
                'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
            ];

            $this->model_account_activity->addActivity('login', $activity_data);
            
            $this->session->data['order_approval_access'] = $customer_info['order_approval_access'];
            $this->session->data['order_approval_access_role'] = $customer_info['order_approval_access_role'];

            if ($store_info) {
                $this->response->redirect($store_info['url'] . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
            } else {
                $this->response->redirect(HTTP_CATALOG . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
            }
        } else {
            $this->load->language('error/not_found');

            $this->document->setTitle($this->language->get('heading_title'));

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_not_found'] = $this->language->get('text_not_found');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
            ];

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
        }
    }

    public function history() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateHistory()) {
            $this->model_sale_customer->addHistory($this->request->get['customer_id'], $this->request->post['comment']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_comment'] = $this->language->get('column_comment');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['histories'] = [];

        $results = $this->model_sale_customer->getHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = [
                'comment' => $result['comment'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $history_total = $this->model_sale_customer->getTotalHistories($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_history.tpl', $data));
    }

    public function credit() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'sale/customer')) {
            $this->model_sale_customer->addCredit($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'sale/customer')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = $this->language->get('column_amount');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = [];

        $results = $this->model_sale_customer->getCredits($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['credits'][] = [
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $data['balance'] = $this->currency->format($this->model_sale_customer->getCreditTotal($this->request->get['customer_id']), $this->config->get('config_currency'));

        $credit_total = $this->model_sale_customer->getTotalCredits($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/credit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_credit.tpl', $data));
    }

    public function reward() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'sale/customer')) {
            $this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'sale/customer')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_points'] = $this->language->get('column_points');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['rewards'] = [];

        $results = $this->model_sale_customer->getRewards($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['rewards'][] = [
                'points' => $result['points'],
                'description' => $result['description'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $data['balance'] = $this->model_sale_customer->getRewardTotal($this->request->get['customer_id']);

        $reward_total = $this->model_sale_customer->getTotalRewards($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $reward_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/reward', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($reward_total - 10)) ? $reward_total : ((($page - 1) * 10) + 10), $reward_total, ceil($reward_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_reward.tpl', $data));
    }

    public function ip() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['ips'] = [];

        $results = $this->model_sale_customer->getIps($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $ban_ip_total = $this->model_sale_customer->getTotalBanIpsByIp($result['ip']);

            $data['ips'][] = [
                'ip' => $result['ip'],
                'total' => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
                'date_added' => date('d/m/y', strtotime($result['date_added'])),
                'filter_ip' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
                'ban_ip' => $ban_ip_total,
            ];
        }

        $ip_total = $this->model_sale_customer->getTotalIps($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $ip_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/ip', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($ip_total - 10)) ? $ip_total : ((($page - 1) * 10) + 10), $ip_total, ceil($ip_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_ip.tpl', $data));
    }
    
    public function customerviewip() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['ips'] = [];

        $results = $this->model_sale_customer->getIps($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $ban_ip_total = $this->model_sale_customer->getTotalBanIpsByIp($result['ip']);

            $data['ips'][] = [
                'ip' => $result['ip'],
                'total' => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
                'date_added' => date('d/m/y', strtotime($result['date_added'])),
                'filter_ip' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
                'ban_ip' => $ban_ip_total,
            ];
        }

        $ip_total = $this->model_sale_customer->getTotalIps($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $ip_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/ip', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($ip_total - 10)) ? $ip_total : ((($page - 1) * 10) + 10), $ip_total, ceil($ip_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_view_ip.tpl', $data));
    }

    public function addBanIp() {
        $this->load->language('sale/customer');

        $json = [];

        if (isset($this->request->post['ip'])) {
            if (!$this->user->hasPermission('modify', 'sale/customer')) {
                $json['error'] = $this->language->get('error_permission');
            } else {
                $this->load->model('sale/customer');

                $this->model_sale_customer->addBanIp($this->request->post['ip']);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeBanIp() {
        $this->load->language('sale/customer');

        $json = [];

        if (isset($this->request->post['ip'])) {
            if (!$this->user->hasPermission('modify', 'sale/customer')) {
                $json['error'] = $this->language->get('error_permission');
            } else {
                $this->load->model('sale/customer');

                $this->model_sale_customer->removeBanIp($this->request->post['ip']);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getCustomers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'customer_id' => $result['customer_id'],
                    'customer_group_id' => $result['customer_group_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'customer_group' => $result['customer_group'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone'],
                    'fax' => $result['fax'],
                    'custom_field' => unserialize($result['custom_field']),
                    'address' => $this->model_sale_customer->getAddresses($result['customer_id']),
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

    public function autocompletebyCompany() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_company'])) {
                $filter_company = $this->request->get['filter_company'];
            } else {
                $filter_company = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_company' => $filter_company,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getCustomers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'customer_id' => $result['customer_id'],
                    'customer_group_id' => $result['customer_group_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'customer_group' => $result['customer_group'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone'],
                    'fax' => $result['fax'],
                    'custom_field' => unserialize($result['custom_field']),
                    'address' => $this->model_sale_customer->getAddresses($result['customer_id']),
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

    public function customfield() {
        $json = [];

        $this->load->model('sale/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id'])) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_sale_custom_field->getCustomFields(['filter_customer_group_id' => $customer_group_id]);

        foreach ($custom_fields as $custom_field) {
            $json[] = [
                'custom_field_id' => $custom_field['custom_field_id'],
                'required' => empty($custom_field['required']) || 0 == $custom_field['required'] ? false : true,
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function address() {
        $json = [];

        if (!empty($this->request->get['address_id'])) {
            $this->load->model('sale/customer');

            $json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function country() {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function referral() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'sale/customer')) {
            $this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'sale/customer')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_user_added'] = $this->language->get('column_user_added');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_points'] = $this->language->get('column_points');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['referrals'] = [];

        $results = $this->model_sale_customer->getReferrals($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['referrals'][] = [
                'customer_id' => $result['customer_id'],
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        //echo "<pre>";print_r($data['referrals']);die;

        $reward_total = $this->model_sale_customer->getTotalReferrals($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $reward_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/referral', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($reward_total - 10)) ? $reward_total : ((($page - 1) * 10) + 10), $reward_total, ceil($reward_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_referrals.tpl', $data));
    }

    public function autocompletecompany() {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getCompanies($filter_data);
            foreach ($results as $result) {
                $json[] = [
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
    
    public function autocompleteparentcustomer() {
        $json = [];

        if (isset($this->request->get['filter_parent_customer'])) {
            if (isset($this->request->get['filter_parent_customer'])) {
                $filter_parent_customer = $this->request->get['filter_parent_customer'];
            } else {
                $filter_parent_customer = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_parent_customer' => $filter_parent_customer,
                'start' => 0,
                'limit' => 5,
            ];
            
            $log = new Log('error.log');
            $results = $this->model_sale_customer->getParentCustomers($filter_data);
            foreach ($results as $result) {
                $json[] = [
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'customer_id' => $result['customer_id'],
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
    public function view_customer() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_referred_by'] = $this->language->get('entry_referred_by');

        $data['text_form'] = $this->language->get('text_view');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');
        $data['entry_gender'] = $this->language->get('entry_gender');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['entry_company_name'] = $this->language->get('entry_company_name');
        $data['entry_company_address'] = $this->language->get('entry_company_address');

        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_safe'] = $this->language->get('entry_safe');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_default'] = $this->language->get('entry_default');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_points'] = $this->language->get('entry_points');
        $data['entry_send_email'] = $this->language->get('entry_send_email');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_contact_no'] = $this->language->get('entry_contact_no');
        $data['entry_address'] = $this->language->get('entry_address');

        $data['help_safe'] = $this->language->get('help_safe');
        $data['help_points'] = $this->language->get('help_points');

        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_address_add'] = $this->language->get('button_address_add');
        $data['button_history_add'] = $this->language->get('button_history_add');
        $data['button_credit_add'] = $this->language->get('button_credit_add');
        $data['button_reward_add'] = $this->language->get('button_reward_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_address'] = $this->language->get('tab_address');
        $data['tab_history'] = $this->language->get('tab_history');
        $data['tab_credit'] = $this->language->get('tab_credit');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_referral'] = $this->language->get('tab_referral');
        $data['tab_sub_customer'] = $this->language->get('tab_sub_customer');
        $data['tab_ip'] = $this->language->get('tab_ip');

        $data['token'] = $this->session->data['token'];

        $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');
        $data['text_stree_society_office'] = $this->language->get('text_stree_society_office');
        $data['label_zipcode'] = $this->language->get('label_zipcode');
        $data['text_locality'] = $this->language->get('text_locality');

        if (isset($this->request->get['customer_id'])) {
            $data['customer_id'] = $this->request->get['customer_id'];
        } else {
            $data['customer_id'] = 0;
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

        $url = '';

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }
        
        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }
        
        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }
        
        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }
        
        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }
        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
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
            'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['customer_id'])) {
            $data['action'] = $this->url->link('sale/customer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['parent_user_name'] = NULL;
        $data['parent_user_email'] = NULL;
        $data['parent_user_phone'] = NULL;
        if (isset($this->request->get['customer_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
            $data['customer_category'] = $customer_info['customer_category'];
            $data['customer_category_disabled'] = '';
            $customer_parent_info = $this->model_sale_customer->getCustomerParentDetails($this->request->get['customer_id']);
            $customer_account_manager_info = $this->model_sale_customer->getCustomerAccountManagerDetails($this->request->get['customer_id']);
            if ($customer_parent_info != NULL) {
                $data['parent_user_name'] = $customer_parent_info['firstname'] . '' . $customer_parent_info['lastname'];
                $data['parent_user_email'] = $customer_parent_info['email'];
                $data['parent_user_phone'] = $customer_parent_info['telephone'];
                $data['customer_category'] = $customer_parent_info['customer_category'];
                $data['customer_category_disabled'] = 'disabled';
            }

            if ($customer_account_manager_info != NULL) {
                $data['account_manager_name'] = $customer_account_manager_info['firstname'] . '' . $customer_account_manager_info['lastname'];
                $data['account_manager_email'] = $customer_account_manager_info['email'];
                $data['account_manager_phone'] = $customer_account_manager_info['telephone'];
            }

            //$log = new Log('error.log');
            //$log->write($customer_parent_info);
        }

        $this->load->model('sale/customer_group');
        $filter_data = [
            'filter_parent' => $this->request->get['customer_id'],
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1000,
        ];
        $data['sub_users'] = $this->model_sale_customer->getSubCustomers($filter_data);
        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        $data['price_categories'] = $this->model_sale_customer_group->getPriceCategories();
        
        $data['company_name'] = $customer_info['company_name'];
        $data['company_address'] = $customer_info['company_address'];
        
        $this->load->model('sale/customer_group');
        $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($customer_info['customer_group_id']);
        $data['customer_group_id'] = $customer_info['customer_group_id'];
        $data['customer_group_info'] = $customer_group_info;
        $data['firstname'] = $customer_info['firstname'];
        $data['lastname'] = $customer_info['lastname'];
        $data['email'] = $customer_info['email'];        
        $data['telephone'] = $customer_info['telephone'];
        $data['fax'] = $customer_info['fax'];
        $data['gender'] = $customer_info['gender'];        
        $data['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
        $data['send_email'] = $customer_info['email'];
        $data['show_send_email'] = true;
        $data['account_custom_field'] = unserialize($customer_info['custom_field']);
        $data['newsletter'] = $customer_info['newsletter'];
        $data['status'] = $customer_info['status'];
        $data['approved'] = $customer_info['approved'];
        $data['safe'] = $customer_info['safe'];
        $data['source'] = $customer_info['source'];
        $data['SAP_customer_no'] = $customer_info['SAP_customer_no'];
        $data['latitude'] = $customer_info['latitude'];
        $data['longitude'] = $customer_info['longitude'];
        
        $data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
        $data['address_id'] = $customer_info['address_id'];

        $this->load->model('sale/customer_group');

        $data['cities'] = $this->model_sale_customer_group->getCities();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/customer_view.tpl', $data));
    }
    
    public function autocompleteaccountmanager() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_telephone'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }
            
            if (isset($this->request->get['filter_telephone'])) {
                $filter_telephone = $this->request->get['filter_telephone'];
            } else {
                $filter_telephone = '';
            }

            $this->load->model('user/user');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_telephone' => $filter_telephone,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_user_user->getAccountManagerUsers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'user_id' => $result['user_id'],
                    'user_group_id' => $result['user_group_id'],
                    'username' => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8')),
                    'name' => $result['name'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone']
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
