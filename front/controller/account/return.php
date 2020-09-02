<?php

class ControllerAccountReturn extends Controller
{
    private $error = [];

    public function index()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/return', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->load->language('account/return');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/return', $url, 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_report_issue'] = $this->language->get('text_report_issue');
        $data['text_load_more'] = $this->language->get('text_load_more');

        $data['text_go_back'] = $this->language->get('text_go_back');
        $data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
        $data['text_items'] = $this->language->get('text_items');

        $data['text_empty'] = $this->language->get('text_empty');

        $data['column_return_id'] = $this->language->get('column_return_id');
        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_customer'] = $this->language->get('column_customer');

        $data['button_view'] = $this->language->get('button_view');
        $data['button_continue'] = $this->language->get('button_continue');

        $this->load->model('account/return');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['returns'] = [];

        $return_total = $this->model_account_return->getTotalReturns();

        $results = $this->model_account_return->getReturns(($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['returns'][] = [
                'return_id' => $result['return_id'],
                'order_id' => $result['order_id'],
                'name' => $result['firstname'].' '.$result['lastname'],
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                'href' => $this->url->link('account/return/info', 'return_id='.$result['return_id'].$url, 'SSL'),
            ];
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $pagination = new Pagination();
        $pagination->total = $return_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_product_limit');
        $pagination->url = $this->url->link('account/return', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_product_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_product_limit')) > ($return_total - $this->config->get('config_product_limit'))) ? $return_total : ((($page - 1) * $this->config->get('config_product_limit')) + $this->config->get('config_product_limit')), $return_total, ceil($return_total / $this->config->get('config_product_limit')));

        $data['continue'] = $this->url->link('account/account', '', 'SSL');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['text_placed_on'] = $this->language->get('text_placed_on');
        $data['text_view'] = $this->language->get('text_view');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/return_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/return_list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/return_list.tpl', $data));
        }
    }

    public function info()
    {
        $this->load->language('account/return');

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if (isset($this->request->get['return_id'])) {
            $return_id = $this->request->get['return_id'];
        } else {
            $return_id = 0;
        }
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/return/info', 'return_id='.$return_id, 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->model('account/return');

        $return_info = $this->model_account_return->getReturn($return_id);

        $this->load->model('account/order');

        //echo "<pre>";print_r($return_info);die;

        if ($return_info) {
            $data['order_info'] = $this->model_account_order->getOrder($return_info['order_id']);

            //echo "<pre>";print_r($order_info);die;

            $this->document->setTitle($this->language->get('text_return'));

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
            ];

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/return', $url, 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_return'),
                'href' => $this->url->link('account/return/info', 'return_id='.$this->request->get['return_id'].$url, 'SSL'),
            ];

            $data['text_go_back'] = $this->language->get('text_go_back');
            $data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');

            $data['heading_title'] = $this->language->get('text_return');
            $data['text_placed_on'] = $this->language->get('text_placed_on');
            $data['text_return_detail'] = $this->language->get('text_return_detail');
            $data['text_return_id'] = $this->language->get('text_return_id');
            $data['text_order_id'] = $this->language->get('text_order_id');
            $data['text_date_ordered'] = $this->language->get('text_date_ordered');
            $data['text_customer'] = $this->language->get('text_customer');
            $data['text_email'] = $this->language->get('text_email');
            $data['text_telephone'] = $this->language->get('text_telephone');
            $data['text_status'] = $this->language->get('text_status');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_product'] = $this->language->get('text_product');
            $data['text_comment'] = $this->language->get('text_comment');
            $data['text_history'] = $this->language->get('text_history');
            $data['text_quantity'] = $this->language->get('text_quantity');

            $data['column_product'] = $this->language->get('column_product');
            $data['column_unit'] = $this->language->get('column_unit');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_opened'] = $this->language->get('column_opened');
            $data['column_reason'] = $this->language->get('column_reason');
            $data['column_action'] = $this->language->get('column_action');
            $data['column_date_added'] = $this->language->get('column_date_added');
            $data['column_status'] = $this->language->get('column_status');
            $data['column_comment'] = $this->language->get('column_comment');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['return_id'] = $return_info['return_id'];
            $data['product_id'] = $return_info['product_id'];
            $data['order_id'] = $return_info['order_id'];
            $data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($return_info['date_ordered']));
            $data['date_added'] = date($this->language->get('date_format'), strtotime($return_info['date_added']));
            $data['firstname'] = $return_info['firstname'];
            $data['lastname'] = $return_info['lastname'];
            $data['email'] = $return_info['email'];
            $data['telephone'] = $return_info['telephone'];
            $data['product'] = $return_info['product'];
            $data['unit'] = $return_info['unit'];
            $data['model'] = $return_info['model'];
            $data['quantity'] = $return_info['quantity'];
            $data['reason'] = $return_info['reason'];
            $data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
            $data['comment'] = nl2br($return_info['comment']);
            $data['action'] = $return_info['action'];

            $data['order_link'] = $this->url->link('account/order/info', 'order_id='.$return_info['order_id'], 'SSL');

            $data['histories'] = [];

            //echo "<pre>";print_r($data['product_id']);die;
            $product_details = $this->model_account_return->getProduct($data['order_id'], $data['product_id']);

            //echo "<pre>";print_r($product_details);die;
            $this->load->model('tool/image');

            if (count($product_details) > 0 && file_exists(DIR_IMAGE.$product_details['image'])) {
                $data['image'] = $this->model_tool_image->resize($product_details['image'], 80, 100);
            } else {
                $data['image'] = $this->model_tool_image->resize('placeholder.png', 80, 100);
            }
            //echo "<pre>";print_r($product_details);die;

            $results = $this->model_account_return->getReturnHistories($this->request->get['return_id']);

            foreach ($results as $result) {
                $data['histories'][] = [
                    'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                    'status' => $result['status'],
                    'comment' => nl2br($result['comment']),
                ];
            }

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['base'] = $server;

            $data['continue'] = $this->url->link('account/return', $url, 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/onlyHeader');

            //echo "<pre>";print_r($data);die;
            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/return_info.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/return_info.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/return_info.tpl', $data));
            }
        } else {
            $this->document->setTitle($this->language->get('text_return'));

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/return', '', 'SSL'),
            ];

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_return'),
                'href' => $this->url->link('account/return/info', 'return_id='.$return_id.$url, 'SSL'),
            ];

            $data['heading_title'] = $this->language->get('text_return');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('account/return', '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/information');

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function add()
    {
        $this->load->language('account/return');

        $this->load->model('account/return');

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $return_id = $this->model_account_return->addReturn($this->request->post);

            //echo "<pre>";print_r($this->request->post);die;

            // Add to activity log
            $this->load->model('account/activity');

            if ($this->customer->isLogged()) {
                $activity_data = [
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                    'return_id' => $return_id,
                ];

                $this->model_account_activity->addActivity('return_account', $activity_data);
            } else {
                $activity_data = [
                    'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
                    'return_id' => $return_id,
                ];

                $this->model_account_activity->addActivity('return_guest', $activity_data);
            }

            $this->session->data['redirect_order_id'] = $this->request->post['order_id'];

            $this->response->redirect($this->url->link('account/return/success', '', 'SSL'));
        }

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/return/add', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_description'] = $this->language->get('text_description');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_date_ordered'] = $this->language->get('entry_date_ordered');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['text_go_back'] = $this->language->get('text_go_back');
        $data['entry_unit'] = $this->language->get('entry_unit');

        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_reason'] = $this->language->get('entry_reason');
        $data['entry_return_action'] = $this->language->get('entry_return_action');
        $data['entry_opened'] = $this->language->get('entry_opened');
        $data['entry_fault_detail'] = $this->language->get('entry_fault_detail');

        $data['button_submit'] = $this->language->get('button_submit');
        $data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            //$data['error_warning'] = $this->error['warning'];
            $this->session->data['error'] = $this->error['warning'];
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['order_id'])) {
            $data['error_order_id'] = $this->error['order_id'];
        } else {
            $data['error_order_id'] = '';
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

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['product'])) {
            $data['error_product'] = $this->error['product'];
        } else {
            $data['error_product'] = '';
        }

        if (isset($this->error['unit'])) {
            $data['error_unit'] = $this->error['unit'];
        } else {
            $data['error_unit'] = '';
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['reason'])) {
            $data['error_reason'] = $this->error['reason'];
        } else {
            $data['error_reason'] = '';
        }

        if (isset($this->error['return_action'])) {
            $data['error_return_action'] = $this->error['return_action'];
        } else {
            $data['error_return_action'] = '';
        }

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        $this->load->model('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
            $order_product = $this->model_account_order->getOrderProductByProductId($this->request->get['order_id'], $this->request->get['product_id']);
        }

        //echo "<pre>";print_r($order_info);die;
        $this->load->model('assets/product');

        if (isset($this->request->get['product_id'])) {
            $product_info = $this->model_assets_product->getDetailproduct($this->request->get['product_id']);
            //print_r($product_info);die;
            $data['product_id'] = $this->request->get['product_id'];
        } else {
            $this->response->redirect($this->url->link('error/not_found'));
        }

        //echo "<pre>";print_r($product_info);die;
        if (isset($this->request->post['order_id'])) {
            $data['order_id'] = $this->request->post['order_id'];
        } elseif (!empty($order_info)) {
            $data['order_id'] = $order_info['order_id'];
        } else {
            $this->response->redirect($this->url->link('error/not_found'));
        }

        $data['action'] = $this->url->link('account/return/add', 'product_id='.$data['product_id'].'&order_id='.$data['order_id'], 'SSL');

        if (isset($this->request->post['date_ordered'])) {
            $data['date_ordered'] = $this->request->post['date_ordered'];
        } elseif (!empty($order_info)) {
            $data['date_ordered'] = date('Y-m-d', strtotime($order_info['order_date']));
        } else {
            $data['date_ordered'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($order_info)) {
            $data['firstname'] = $order_info['firstname'];
        } else {
            $data['firstname'] = $this->customer->getFirstName();
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($order_info)) {
            $data['lastname'] = $order_info['lastname'];
        } else {
            $data['lastname'] = $this->customer->getLastName();
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($order_info)) {
            $data['email'] = $order_info['order_email'];
        } else {
            $data['email'] = $this->customer->getEmail();
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($order_info)) {
            $data['telephone'] = $order_info['order_telephone'];
        } else {
            $data['telephone'] = $this->customer->getTelephone();
        }

        if (isset($this->request->post['product'])) {
            $data['product'] = $this->request->post['product'];
            $data['unit'] = $this->request->post['unit'];
        } elseif (!empty($product_info)) {
            $data['product'] = $product_info['name'];
            $data['unit'] = $product_info['unit'];
        } else {
            $data['product'] = '';
            $data['unit'] = '';
        }

        if (isset($this->request->post['model'])) {
            $data['model'] = $this->request->post['model'];
        } elseif (!empty($product_info)) {
            $data['model'] = $product_info['model'];
        } else {
            $data['model'] = '';
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($order_product)) {
            $data['price'] = $order_product['price'];
        } else {
            $data['price'] = '';
        }

        //echo "<pre>";print_r($order_product);die;
        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $order_product['quantity'];
        } else {
            $data['quantity'] = '';
        }

        if (isset($this->request->post['opened'])) {
            $data['opened'] = $this->request->post['opened'];
        } else {
            $data['opened'] = false;
        }

        if (isset($this->request->post['return_reason_id'])) {
            $data['return_reason_id'] = $this->request->post['return_reason_id'];
        } else {
            $data['return_reason_id'] = '';
        }
        if (isset($this->request->post['return_action_id'])) {
            $data['return_action_id'] = $this->request->post['return_action_id'];
        } else {
            $data['return_action_id'] = '';
        }

        $this->load->model('localisation/return_reason');

        $data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();
        $data['return_actions'] = $this->model_localisation_return_reason->getReturnActions();

        if (isset($this->request->post['comment'])) {
            $data['comment'] = $this->request->post['comment'];
        } else {
            $data['comment'] = '';
        }

        if ($this->config->get('config_google_captcha_status')) {
            $this->document->addScript('https://www.google.com/recaptcha/api.js');

            $data['site_key'] = $this->config->get('config_google_captcha_public');
        } else {
            $data['site_key'] = '';
        }

        if ($this->config->get('config_return_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id='.$this->config->get('config_return_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        if (isset($this->request->post['agree'])) {
            $data['agree'] = $this->request->post['agree'];
        } else {
            $data['agree'] = false;
        }

        $data['back'] = $this->url->link('account/account', '', 'SSL');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        //echo "<pre>";print_r($data['unit']);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/return_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/return_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/return_form.tpl', $data));
        }
    }

    public function multipleproducts()
    {
        $this->load->model('account/order');
        $this->load->model('account/return');
        if (isset($this->request->post['order_id'])) {
            $order_info = $this->model_account_order->getOrder($this->request->post['order_id']);
        }
        $returnProducts = $this->request->post['select-products'];
        /*if(count($returnProducts) == 0){
            $this->response->redirect($this->url->link('account/order/info', 'order_id=' . $this->request->post['order_id'], 'SSL'));
        }*/
        $returnQty = $this->request->post['return_qty'];
        foreach ($returnProducts as $productKey => $product_id) {
            $data = [];
            $order_product = $this->model_account_order->getOrderProductByProductId($this->request->post['order_id'], $product_id);
            $data['product_id'] = $order_product['product_id'];
            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];
            $data['email'] = $order_info['order_email'];
            $data['telephone'] = $order_info['order_telephone'];
            $data['order_id'] = $order_info['order_id'];
            $data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($order_info['order_date']));
            $data['product'] = $order_product['name'];
            $data['unit'] = $order_product['unit'];
            $data['price'] = $order_product['price'];
            $data['model'] = $order_product['model'];
            $data['quantity'] = $returnQty[$productKey];
            $data['return_reason_id'] = $this->request->post['return_reason_id'];
            $data['customer_desired_action'] = $this->request->post['customer_desired_action'];
            $data['opened'] = $this->request->post['opened'];
            $data['comment'] = $this->request->post['comment'];
            //echo '<pre>';print_r($data);exit;
            $return_id = $this->model_account_return->addReturn($data);
            //echo $return_id;exit;
            $this->load->model('account/activity');

            if ($this->customer->isLogged()) {
                $activity_data = [
                            'customer_id' => $this->customer->getId(),
                            'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                            'return_id' => $return_id,
                        ];

                $this->model_account_activity->addActivity('return_account', $activity_data);
            } else {
                $activity_data = [
                            'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
                            'return_id' => $return_id,
                        ];

                $this->model_account_activity->addActivity('return_guest', $activity_data);
            }
        }

        $this->session->data['redirect_order_id'] = $this->request->post['order_id'];
        $this->response->redirect($this->url->link('account/return/success', '', 'SSL'));
    }

    public function success()
    {
        $this->load->language('account/return');

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/return', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_message');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        $data['redirect_url'] = null;
        $data['redirect_url_return'] = true;

        $data['order_id'] = 1;

        $data['redirect_url'] = $this->url->link('account/order/info');
        //$data['redirect_url'] = $this->url->link('account/order/info', 'order_id=' . $data['order_id'], 'SSL');

        //echo "<pre>";print_r($data['redirect_url']);die;
        if (isset($this->session->data['redirect_order_id'])) {
            $data['order_id'] = $this->session->data['redirect_order_id'];

            $data['redirect_url'] = $this->url->link('account/order/info', 'order_id='.$data['order_id'], 'SSL');
            unset($this->session->data['redirect_order_id']);
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    protected function validate()
    {
        if (!$this->request->post['order_id']) {
            $this->error['order_id'] = $this->language->get('error_order_id');
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        // if ((utf8_strlen($this->request->post['product']) < 1) || (utf8_strlen($this->request->post['product']) > 255)) {
        // 	$this->error['product'] = $this->language->get('error_product');
        // }

        if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
            $this->error['model'] = $this->language->get('error_model');
        }

        if (empty($this->request->post['return_reason_id'])) {
            $this->error['reason'] = $this->language->get('error_reason');
        }

        if (empty($this->request->post['return_action_id'])) {
            $this->error['return_action'] = $this->language->get('error_return_action');
        }

        if ($this->config->get('config_google_captcha_status')) {
            $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($this->config->get('config_google_captcha_secret')).'&response='.$this->request->post['g-recaptcha-response'].'&remoteip='.$this->request->server['REMOTE_ADDR']);

            $json = json_decode($json, true);

            if (!$json['success']) {
                $this->error['captcha'] = $this->language->get('error_captcha');
            }
        }

        if ($this->error) {
            $this->error['warning'] = 'Plase check the form carefully!';
        }

        if ($this->config->get('config_return_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

            if ($information_info && !isset($this->request->post['agree'])) {
                $this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }

        return !$this->error;
    }
}
