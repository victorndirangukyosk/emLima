<?php

require_once DIR_SYSTEM . '/vendor/konduto/vendor/autoload.php';

//require_once DIR_SYSTEM.'/vendor/mpesa-php-sdk-master/vendor/autoload.php';

require_once DIR_SYSTEM . '/vendor/fcp-php/autoload.php';

require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION . '/controller/api/settings.php';

require_once DIR_SYSTEM . '/vendor/pesapal/OAuth.php';

class Controlleraccounttransactions extends Controller {

    private $error = [];

    public function index() {
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['redirect_coming'] = false;

        $this->document->addStyle('/front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');
        $this->document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
        $this->document->addScript('https://www.js-tutorials.com/demos/jquery_bootstrap_pagination_example_demo/jquery.twbsPagination.min.js');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/profileinfo', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/edit');
        $this->load->language('account/account');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('account/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_account_customer->addEditCustomerInfo($this->customer->getId(), $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];
            $log = new Log('error.log');
            $log->write('account profileinfo');

            $this->model_account_activity->addActivity('profileinfo', $activity_data);

            $log->write('account profileinfo');

            $this->response->redirect($this->url->link('account/profileinfo', '', 'SSL'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');

        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirmpassword'] = $this->language->get('entry_confirmpassword');

        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_requirement'] = $this->language->get('entry_requirement');
        $data['entry_mandatory_products'] = $this->language->get('entry_mandatory_products');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_save'] = $this->language->get('button_save');

        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_my_logout'] = $this->language->get('text_my_logout');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_membership'] = $this->language->get('text_membership');
        $data['text_change_password'] = $this->language->get('text_change_password');

        $data['button_become_member'] = $this->language->get('button_become_member');

        $data['label_name'] = $this->language->get('label_name');
        $data['label_contact_no'] = $this->language->get('label_contact_no');
        $data['label_address'] = $this->language->get('label_address');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
        $data['pezesha_loans'] = $this->url->link('account/pezeshaloans', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $customer_info = $this->model_account_customer->getCustomerOtherInfo($this->customer->getId());
            // echo '<pre>';print_r($customer_info);exit;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['location'])) {
            $data['error_location'] = $this->error['location'];
        } else {
            $data['error_location'] = '';
        }

        if (isset($this->error['requirement'])) {
            $data['error_requirement'] = $this->error['requirement'];
        } else {
            $data['error_requirement'] = '';
        }

        if (isset($this->error['mandatory_products'])) {
            $data['error_mandatory_products'] = $this->error['mandatory_products'];
        } else {
            $data['error_mandatory_products'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } else {
            $data['location'] = $customer_info['location'];
        }

        if (isset($this->request->post['requirement'])) {
            $data['requirement'] = $this->request->post['requirement'];
        } else {
            $data['requirement'] = $customer_info['requirement_per_week'];
        }
        if (isset($this->request->post['mandatory_products'])) {
            $data['mandatory_products'] = $this->request->post['mandatory_products'];
        } else {
            $data['mandatory_products'] = $customer_info['mandatory_veg_fruits'];
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['action'] = $this->url->link('account/profileinfo', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['account_edit'] = $this->load->controller('account/edit');
        $data['pay'] = $this->url->link('account/transactions', 'pay=online');
        $data['home'] = $this->url->link('common/home/toHome');
        //$data['telephone'] =  $this->formatTelephone($this->customer->getTelephone());
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');

        $data['orders'] = [];

        $this->load->model('account/order');
        $order_total = $this->model_account_order->getTotalOrders();

        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = true);
        $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Wallet Payment', 'Pezesha'];
        $statusCancelledFilter = ['Cancelled'];
        $statusSucessFilter = ['Delivered', 'Partially Delivered'];
        $statusPendingFilter = ['Cancelled', 'Delivered', 'Refunded', 'Returned', 'Partially Delivered'];
        //$results_pending = $this->model_account_order->getOrders(($page - 1) * 10, 10,$PaymentFilter,$statusPendingFilter,$In=false);
        //$results_success = $this->model_account_order->getOrders(($page - 1) * 10, 10,$PaymentFilter,$statusPendingFilter,$In=true);
        //$results_cancelled = $this->model_account_order->getOrders(($page - 1) * 10, 10,$PaymentFilter,$statusCancelledFilter,$In=true);
        $data['pending_transactions'] = [];
        $data['success_transactions'] = [];
        $data['cancelled_transactions'] = [];
        //echo "<pre>";print_r($results_orders);die;
        $totalPendingAmount = 0;
        $totalWalletAmount = 0;
        if (count($results_orders) > 0) {
            foreach ($results_orders as $order) {
                $this->load->model('sale/order');
                $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                if (in_array($order['payment_method'], $PaymentFilter)) {
                    //  echo "<pre>";print_r($order);die;

                    if (!empty($order['transcation_id']) && !in_array($order['status'], $statusCancelledFilter)) {
                        //if(in_array($order['status'],$statusSucessFilter) && !empty($order['transcation_id'])){
                        $data['success_transactions'][] = $order;
                    } elseif (in_array($order['status'], $statusCancelledFilter)) {
                        $data['cancelled_transactions'][] = $order;
                    } elseif (!in_array($order['status'], $statusCancelledFilter)) {
                        $totalPendingAmount = $totalPendingAmount + $order['total'];
                        $data['pending_order_id'][] = $order['order_id'];
                        $data['pending_transactions'][] = $order;
                    }
                }
            }
        }
        $this->load->model('account/credit');
        $totalWalletAmount = $this->model_account_credit->getTotalAmount();
        //  echo "<pre>";print_r($data['pending_transactions']);die;
        $data['total_pending_amount'] = $totalPendingAmount;
        $data['total_wallet_amount'] = $totalWalletAmount;
        // $data['total_wallet_amount'] = $this->currency->format($totalWalletAmount);

        $data['pending_order_id'] = implode('--', $data['pending_order_id']);
        $data['payment_interswitch'] = $this->load->controller('payment/interswitch');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/my_transactions.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/my_transactions.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/my_transactions.tpl', $data));
        }
    }

    protected function validate() {
        //print_r($this->request->post);die;
        $this->load->language('account/edit');

        if ((utf8_strlen(trim($this->request->post['location'])) < 1) || (utf8_strlen(trim($this->request->post['location'])) > 32)) {
            $this->error['location'] = $this->language->get('error_location');
        }

        if ((utf8_strlen(trim($this->request->post['requirement'])) < 1)) {
            $this->error['requirement'] = $this->language->get('error_requirement');
        }

        if ((utf8_strlen(trim($this->request->post['mandatory_products'])) < 1)) {
            $this->error['mandatory_products'] = $this->language->get('error_mandatory_products');
        }

        return !$this->error;
    }

    public function pendingtransactions() {
        $data['orders'] = [];

        $this->load->model('account/order');
        $this->load->model('payment/pesapal');
        $order_total = $this->model_account_order->getTotalOrders();

        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = true);
        $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Wallet Payment', 'Pezesha'];
        $statusCancelledFilter = ['Cancelled'];
        $statusSucessFilter = ['Delivered', 'Partially Delivered'];
        $statusPendingFilter = ['Cancelled', 'Delivered', 'Refunded', 'Returned', 'Partially Delivered'];
        $data['pending_transactions'] = [];
        $data['success_transactions'] = [];
        $data['cancelled_transactions'] = [];
        //echo "<pre>";print_r($results_orders);die;
        $totalPendingAmount = 0;
        $totalWalletAmount = 0;
        if (count($results_orders) > 0) {
            foreach ($results_orders as $order) {
                $this->load->model('sale/order');
                $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                //echo "<pre>";print_r($order);die;
                if (in_array($order['payment_method'], $PaymentFilter)) {
                    if (!empty($order['transcation_id']) && $order['paid'] == 'Y' && !in_array($order['status'], $statusCancelledFilter)) {
                        //if(in_array($order['status'],$statusSucessFilter) && !empty($order['transcation_id'])){
                        if (is_array($order) && array_key_exists('value', $order)) {
                            $order['total_currency'] = $this->currency->format($order['value']);
                        }
                        $data['success_transactions'][] = $order;
                    } elseif (in_array($order['status'], $statusCancelledFilter)) {
                        if (is_array($order) && array_key_exists('value', $order)) {
                            $order['total_currency'] = $this->currency->format($order['value']);
                        }
                        $data['cancelled_transactions'][] = $order;
                    } elseif (!in_array($order['status'], $statusCancelledFilter)) {
                        if (is_array($order) && array_key_exists('value', $order)) {
                            $order['total_currency'] = $this->currency->format($order['value']);
                            $order['pending_amount'] = $order['value'] - $order['amount_partialy_paid'];
                            $order['pending_amount_currency'] = $this->currency->format($order['pending_amount']);
                        }
                        /* $log = new Log('error.log');
                          $log->write('NON NUMERIC');
                          $log->write($totalPendingAmount);
                          $log->write($order['total']);
                          $log->write('NON NUMERIC'); */

                        if ($order['pending_amount'] > 0) {
                            $totalPendingAmount = $totalPendingAmount + $order['pending_amount'];
                        } else {
                            $totalPendingAmount = $totalPendingAmount + $order['value'];
                        }

                        //$totalPendingAmount = $this->currency->format($totalPendingAmount);
                        $data['pending_order_id'][] = $order['order_id'];
                        $data['pending_transactions'][] = $order;
                    }
                }
            }
        }
        $this->load->model('account/credit');
        $totalWalletAmount = $this->model_account_credit->getTotalAmount();

        //echo "<pre>";print_r($data);die;
        $data['total_pending_amount'] = $this->currency->format($totalPendingAmount);
        $data['total_wallet_amount'] = $this->currency->format($totalWalletAmount);
        $data['pending_order_id'] = implode('--', $data['pending_order_id']);
        $pay_other_amount = $this->model_payment_pesapal->getPesapalOtherAmount($this->customer->getId());
        $data['success_transactions_pay_other_amount'] = $pay_other_amount;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function successfulltransactions() {
        $data['orders'] = [];

        $this->load->model('account/order');
        $order_total = $this->model_account_order->getTotalOrders();

        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = true);
        $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Wallet Payment', 'Pezesha'];
        $statusCancelledFilter = ['Cancelled'];
        $statusSucessFilter = ['Delivered', 'Partially Delivered'];
        $statusPendingFilter = ['Cancelled', 'Delivered', 'Refunded', 'Returned', 'Partially Delivered'];
        $data['pending_transactions'] = [];
        $data['success_transactions'] = [];
        $data['cancelled_transactions'] = [];
        //echo "<pre>";print_r($results_orders);die;
        $totalPendingAmount = 0;
        $totalWalletAmount = 0;
        if (count($results_orders) > 0) {
            foreach ($results_orders as $order) {
                $this->load->model('sale/order');
                $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                //echo "<pre>";print_r($order);die;
                if (in_array($order['payment_method'], $PaymentFilter)) {
                    if (!empty($order['transcation_id']) && !in_array($order['status'], $statusCancelledFilter)) {
                        //if(in_array($order['status'],$statusSucessFilter) && !empty($order['transcation_id'])){
                        $data['success_transactions'][] = $order;
                    } elseif (in_array($order['status'], $statusCancelledFilter)) {
                        $data['cancelled_transactions'][] = $order;
                    } elseif (!in_array($order['status'], $statusCancelledFilter)) {
                        $totalPendingAmount = $totalPendingAmount + $order['total'];
                        $totalPendingAmount = $this->currency->format($totalPendingAmount);
                        $data['pending_order_id'][] = $order['order_id'];
                        $data['pending_transactions'][] = $order;
                    }
                }
            }
        }
        $this->load->model('account/credit');
        $totalWalletAmount = $this->model_account_credit->getTotalAmount();

        //echo "<pre>";print_r($data);die;
        $data['total_pending_amount'] = $this->currency->format($totalPendingAmount);
        $data['total_wallet_amount'] = $this->currency->format($totalWalletAmount);
        $data['pending_order_id'] = implode('--', $data['pending_order_id']);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function cancelledtransactions() {
        $data['orders'] = [];

        $this->load->model('account/order');
        $order_total = $this->model_account_order->getTotalOrders();

        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = true);
        $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Wallet Payment', 'Pezesha'];
        $statusCancelledFilter = ['Cancelled'];
        $statusSucessFilter = ['Delivered', 'Partially Delivered'];
        $statusPendingFilter = ['Cancelled', 'Delivered', 'Refunded', 'Returned', 'Partially Delivered'];
        $data['pending_transactions'] = [];
        $data['success_transactions'] = [];
        $data['cancelled_transactions'] = [];
        //echo "<pre>";print_r($results_orders);die;
        $totalPendingAmount = 0;
        $totalWalletAmount = 0;
        if (count($results_orders) > 0) {
            foreach ($results_orders as $order) {
                $this->load->model('sale/order');
                $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                //echo "<pre>";print_r($order);die;
                if (in_array($order['payment_method'], $PaymentFilter)) {
                    if (!empty($order['transcation_id']) && !in_array($order['status'], $statusCancelledFilter)) {
                        //if(in_array($order['status'],$statusSucessFilter) && !empty($order['transcation_id'])){
                        $data['success_transactions'][] = $order;
                    } elseif (in_array($order['status'], $statusCancelledFilter)) {
                        $data['cancelled_transactions'][] = $order;
                    } elseif (!in_array($order['status'], $statusCancelledFilter)) {
                        $totalPendingAmount = $totalPendingAmount + $order['total'];
                        $totalPendingAmount = $this->currency->format($totalPendingAmount);
                        $data['pending_order_id'][] = $order['order_id'];
                        $data['pending_transactions'][] = $order;
                    }
                }
            }
        }
        $this->load->model('account/credit');
        $totalWalletAmount = $this->model_account_credit->getTotalAmount();
        //echo "<pre>";print_r($data);die;
        $data['total_pending_amount'] = $this->currency->format($totalPendingAmount);
        $data['total_wallet_amount'] = $this->currency->format($totalWalletAmount);
        $data['pending_order_id'] = implode('--', $data['pending_order_id']);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function pesapal() {
        //print_r($this->request->post);die;
        $bulk_orders = NULL;
        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $log = new Log('error.log');

        if ($this->request->post['order_id'] != NULL && $this->request->post['payment_type'] == NULL) {

            $order_id = $this->request->post['order_id'];

            $log = new Log('error.log');
            $log->write('Pesapal Order ID');
            $log->write($order_id);
            $log->write('Pesapal Order ID');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
            $log->write('Pesapal Creds Customer Info');
            $log->write($customer_info);
            $log->write('Pesapal Creds Customer Info');

            $log->write('Pesapal Order Info');
            $log->write($order_info);
            $log->write('Pesapal Order Info');

            if (count($order_info) > 0) {
                if ($order_info['amount_partialy_paid'] > 0)
                    $amount = (int) ($order_info['total'] - $order_info['amount_partialy_paid']);
                else
                    $amount = (int) ($order_info['total']);
            }

            $this->model_checkout_order->UpdatePaymentMethod($order_id, 'PesaPal', 'pesapal');
        }
        if ($this->request->post['order_id'] == NULL && $this->request->post['payment_type'] != NULL && $this->request->post['payment_type'] == 'pay_other') {
            $log = new Log('error.log');
            $log->write('Pesapal Pay Other Amount');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $order_id = $this->customer->getId() . 'KBCUST';
            $amount = $this->request->post['amount'];
            $this->session->data['pay_other_amount'] = $amount;
        }
        if ($this->request->post['order_id'] != NULL && $this->request->post['payment_type'] != NULL && $this->request->post['payment_type'] == 'pay_full') {
            $order_id_array = explode("--", $this->request->post['order_id']);
            $bulk_orders = $this->request->post['order_id'];
            $order_id = implode(",", $order_id_array);

            $log = new Log('error.log');
            $log->write('Pesapal Order ID');
            $log->write($order_id_array);
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $log->write('Pesapal Creds Customer Info');
            $log->write($customer_info);
            $log->write('Pesapal Creds Customer Info');

            if (is_array($order_id_array) && count($order_id_array) > 0) {
                foreach ($order_id_array as $order_id_arr) {
                    $log->write('Pesapal Order ID FOREACH');
                    $log->write($order_id_arr);
                    $log->write('Pesapal Order ID FOREACH');
                    $this->model_checkout_order->UpdatePaymentMethod($order_id_arr, 'PesaPal', 'pesapal');
                }
            }
            $amount = $this->request->post['amount'];
        }
        if ($this->request->post['order_id'] != NULL && $this->request->post['payment_type'] != NULL && $this->request->post['payment_type'] == 'pay_selected_order') {
            $log->write($this->request->post['order_id']);
            $log->write($this->request->post['payment_type']);
            $log->write($this->request->post['amount']);

            $order_id = implode(",", $this->request->post['order_id']);
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $amount = $this->request->post['amount'];
            if (is_array($this->request->post['order_id']) && count($this->request->post['order_id']) > 0) {
                foreach ($this->request->post['order_id'] as $order_id_arr) {
                    $log->write('Pesapal Order ID FOREACH pay_selected_order');
                    $log->write($order_id_arr);
                    $log->write('Pesapal Order ID FOREACH pay_selected_order');
                    $this->model_checkout_order->UpdatePaymentMethod($order_id_arr, 'PesaPal', 'pesapal');
                }
            }
        }
        $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);
        //pesapal params
        $token = $params = null;

        /*
          PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and
          when you are ready to go live change to https://www.pesapal.com.
         */
        $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $iframelink = 'https://www.pesapal.com/api/PostPesapalDirectOrderV4'; //change to
        //https://www.pesapal.com/API/PostPesapalDirectOrderV4 when you are ready to go live!
        //get form details
        $transaction_fee = 0;
        $percentage = 3.5;
        $transaction_fee = ($percentage / 100) * $amount;
        $amount = str_replace(',', '', $amount + + $transaction_fee);
        $log->write('TRANSACTION FEE');
        $log->write($transaction_fee);
        $log->write($amount);
        //$amount = 100;
        $amount = number_format($amount, 2); //format amount to 2 decimal places

        $desc = $customer_info['company_name'] . '-' . $customer_info['firstname'] . '-' . $customer_info['lastname'] . '-' . $order_id;
        $type = 'MERCHANT'; //default value = MERCHANT
        $reference = $order_id . '-' . time() . '-' . $customer_info['customer_id']; //unique order id of the transaction, generated by merchant
        if ($this->request->post['payment_type'] != NULL && $this->request->post['payment_type'] == 'pay_other') {
            $this->session->data['pay_other_reference'] = $reference;
        }
        $first_name = $customer_info['firstname'];
        $last_name = $customer_info['lastname'];
        $email = $customer_info['email'];
        $phonenumber = '+254' . $customer_info['telephone']; //ONE of email or phonenumber is required
        $Currency = 'KES';

        $callback_url = $this->url->link('account/transactions/status', '', 'SSL'); //redirect url, the page that will handle the response from pesapal.

        $post_xml = '<?xml version="1.0" encoding="utf-8"?><PesapalDirectOrderInfo xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" Amount="' . $amount . '" Description="' . $desc . '" Type="' . $type . '" Reference="' . $reference . '" FirstName="' . $first_name . '" LastName="' . $last_name . '" Email="' . $email . '" PhoneNumber="' . $phonenumber . '" xmlns="http://www.pesapal.com" />';
        $post_xml = htmlentities($post_xml);

        $consumer = new OAuthConsumer($consumer_key, $consumer_secret, $callback_url);
        //print_r($consumer);
        //post transaction to pesapal
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $iframelink, $params);
        $iframe_src->set_parameter('oauth_callback', $callback_url);
        $iframe_src->set_parameter('pesapal_request_data', $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        //display pesapal - iframe and pass iframe_src
        $log->write($iframe_src);
        $data['iframe'] = $iframe_src;

        echo '<iframe src=' . $iframe_src . ' width="100%" height="700px"  scrolling="no" frameBorder="0"><p>Browser unable to load iFrame</p></iframe>';
    }

    public function interswitch() {
        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/interswitch');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        if ($this->request->post['payment_type'] == 'pay_full') {
            $this->request->post['order_id'] = explode('--', $this->request->post['order_id']);
        }

        $order_ids = array();
        $amount = 0;
        foreach ($this->request->post['order_id'] as $key => $value) {
            $order_ids[] = $value;
            $order_id = $value;

            $order_info = $this->model_checkout_order->getOrder($value);
            if (count($order_info) > 0) {
                $amount += (int) ($order_info['total'] - $order_info['amount_partialy_paid']);
            }
        }

        foreach ($this->request->post['order_id'] as $key => $value) {
            $order_info = $this->model_checkout_order->getOrder($value);
            $interswitch_data_ref = base64_encode($this->customer->getId() . '_' . $amount . '_' . date("Y-m-d h:i:s"));
            $this->model_payment_interswitch->AddOrderTransaction($order_info['order_id'], $interswitch_data_ref);
        }

        $order_ids_string = NULL;
        if (is_array($order_ids) && count($order_ids) > 0) {
            $order_ids_string = implode('-', $order_ids);
            $log = new Log('error.log');
            $log->write('order_ids');
            $log->write($order_ids_string);
            $log->write($order_ids);
            $log->write($order_id);
            $log->write('order_ids');
        }
        $data['customer_number'] = $this->customer->getTelephone();

        $interswitch_creds = $this->model_setting_setting->getSetting('interswitch', 0);
        $data['interswitch_merchant_code'] = $interswitch_creds['interswitch_merchant_code'];
        $data['interswitch_pay_item_id'] = $interswitch_creds['interswitch_pay_item_id'];
        $data['interswitch_data_ref'] = $interswitch_data_ref;
        $data['interswitch_customer_id'] = $this->customer->getId();
        $data['interswitch_customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
        //$data['interswitch_amount'] = $amount * 100;
        //$data['interswitch_amount'] = $this->cart->getTotal() * 100;
        /* FOR KWIKBASKET ORDERS */
        $data['interswitch_amount'] = $amount * 100;
        $log = new Log('error.log');
        $log->write($interswitch_creds['interswitch_merchant_code']);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/interswitch_transaction.tpl')) {
            return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/interswitch_transaction.tpl', $data));
        } else {
            return $this->response->setOutput($this->load->view('default/template/account/interswitch_transaction.tpl', $data));
        }
    }

    public function InterswitchPaymentResponse() {
        $json = [];
        $log = new Log('error.log');
        $log->write('interswitch payment response');
        $log->write($this->request->post['payment_response']);
        $log->write(base64_decode($this->request->post['payment_response']['txnref']));
        $txn_ref = base64_decode($this->request->post['payment_response']['txnref']);
        $txn_refl = explode('_', $txn_ref);
        $order_id = $txn_refl[1];
        $customer_id = $txn_refl[0];

        $payment_gateway_description = $this->request->post['payment_response']['desc'];
        $payment_reference_number = $this->request->post['payment_response']['payRef'];
        $banking_reference_number = $this->request->post['payment_response']['retRef'];
        $transaction_reference_number = $this->request->post['payment_response']['txnref'];
        $approved_amount = $this->request->post['payment_response']['apprAmt'];
        $payment_gateway_amount = $this->request->post['payment_response']['amount'];
        $card_number = $this->request->post['payment_response']['cardNum'];
        $mac = $this->request->post['payment_response']['mac'];
        $response_code = $this->request->post['payment_response']['resp'];
        $status = $this->request->post['payment_response']['resp'] == 00 ? 'COMPLETED' : 'FAILED';

        $log->write($customer_id);
        $log->write($order_id);

        $this->load->language('payment/interswitch');
        $this->load->model('setting/setting');
        $this->load->model('payment/interswitch');
        $this->load->model('payment/interswitch_response');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $interswitch_orders = $this->model_payment_interswitch->getInterswitchByPaymentReference($this->request->post['payment_response']['txnref']);

        $log->write('interswitch_orders');
        $log->write($interswitch_orders);
        $log->write('interswitch_orders');

        if (is_array($interswitch_orders) && count($interswitch_orders) > 0) {
            foreach ($interswitch_orders as $interswitch_order) {
                $order_id = $interswitch_order['order_id'];

                $log->write('interswitch_orders_loop');
                $log->write($order_id);
                $log->write('interswitch_orders_loop');

                $order_info = $this->model_checkout_order->getOrder($order_id);
                $this->model_payment_interswitch_response->Saveresponse($order_info['customer_id'], $order_id, json_encode($this->request->post['payment_response']));
                $this->model_payment_interswitch_response->SaveResponseIndv($customer_id, $order_id, $payment_gateway_description, $payment_reference_number, $banking_reference_number, $transaction_reference_number, $approved_amount, $payment_gateway_amount, $card_number, $mac, $response_code, $status);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

                if ('00' == $this->request->post['payment_response']['resp'] && 'Z6' != $this->request->post['payment_response']['resp']) {
                    $this->model_payment_interswitch->OrderTransaction($order_id, $payment_reference_number);
                    $this->model_payment_interswitch->addOrderHistoryTransaction($order_id, $this->config->get('interswitch_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'Interswitch', 'interswitch');
                }

                if ('00' != $this->request->post['payment_response']['resp'] && 'Z6' != $this->request->post['payment_response']['resp']) {
                    $this->model_payment_interswitch->addOrderHistoryTransaction($order_id, $this->config->get('interswitch_failed_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'Interswitch', 'interswitch');
                }
            }
        }

        if ('00' == $this->request->post['payment_response']['resp'] && 'Z6' != $this->request->post['payment_response']['resp']) {
            $json['message'] = $payment_gateway_description;
            $json['redirect_url'] = $this->url->link('account/transactions');
        }

        if ('00' != $this->request->post['payment_response']['resp'] && 'Z6' != $this->request->post['payment_response']['resp']) {
            $json['message'] = $payment_gateway_description;
            $json['redirect_url'] = $this->url->link('account/transactions');
        }

        $log->write('interswitch payment response');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function interswitchstatus() {
        $this->response->redirect($this->url->link('account/transactions'));
    }

    public function status() {
        $log = new Log('error.log');

        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $log->write('PESAPAL CALL BACK');
        $transaction_tracking_id = $this->request->get['pesapal_transaction_tracking_id'];
        $merchant_reference = $this->request->get['pesapal_merchant_reference'];
        $log->write($transaction_tracking_id);
        $log->write($merchant_reference);
        $log->write('PESAPAL CALL BACK');
        $order_details = explode('-', $merchant_reference);
        if (is_array($order_details)) {
            $order_id = $order_details[0];
        }

        $log->write('Pesapal Order ID From Transactions Page');
        $log->write($order_id);
        $log->write('Pesapal Order ID From Transactions Page');

        if (strpos($order_id, 'KBCUST') !== false) {
            $log->write($order_id . 'TRUE');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $log->write('Pesapal Creds Customer Info');
            $log->write($customer_info);
            $log->write('Pesapal Creds Customer Info');
            $transaction_tracking_id = $this->request->get['pesapal_transaction_tracking_id'];
            $merchant_reference = $this->request->get['pesapal_merchant_reference'];
            $customer_id = $customer_info['customer_id'];
            $this->model_payment_pesapal->insertOrderTransactionIdPesapalOther(NULL, $transaction_tracking_id, $merchant_reference, $customer_id, $this->session->data['pay_other_amount']);
            $status = $this->ipinlistenercustom('CHANGE', $transaction_tracking_id, $merchant_reference, $order_id);
            unset($this->session->data['pay_other_amount']);
            unset($this->session->data['pay_other_reference']);
        } else {
            $ord_arr = explode(",", $order_id);
            foreach ($ord_arr as $ord_ar) {
                $order_info = $this->model_checkout_order->getOrder($ord_ar);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                $log->write('Pesapal Creds Customer Info');
                $log->write($customer_info);
                $log->write('Pesapal Creds Customer Info');

                $log->write('Pesapal Order Info');
                $log->write($order_info);
                $log->write('Pesapal Order Info');

                if (count($order_info) > 0) {
                    $amount = (int) ($order_info['total']);
                }

                $transaction_tracking_id = $this->request->get['pesapal_transaction_tracking_id'];
                $merchant_reference = $this->request->get['pesapal_merchant_reference'];
                $customer_id = $customer_info['customer_id'];
                $this->model_payment_pesapal->insertOrderTransactionIdPesapal($ord_ar, $transaction_tracking_id, $merchant_reference, $customer_id);
                $this->model_payment_pesapal->OrderTransaction($ord_ar, $transaction_tracking_id);
                $status = $this->ipinlistenercustom('CHANGE', $transaction_tracking_id, $merchant_reference, $ord_ar);
            }
        }

        if ('COMPLETED' == $status) {
            $this->response->redirect($this->url->link('checkout/pesapalsuccess'));
        }

        if ('COMPLETED' != $status || null == $status) {
            $this->response->redirect($this->url->link('checkout/success/pesapalfailed'));
        }
    }

    public function ipinlistenercustom($pesapalNotification, $pesapalTrackingId, $pesapal_merchant_reference, $order_id) {
        $status = null;
        $log = new Log('error.log');
        $log->write('ipinlistener');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);
        if (strpos($order_id, 'KBCUST') !== false) {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $customer_id = $customer_info['customer_id'];
        } else {
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
            $customer_id = $customer_info['customer_id'];
        }

        $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
        //'https://demo.pesapal.com/api/querypaymentstatus'; //change to
        //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!
        // Parameters sent to you by PesaPal IPN
        $pesapalNotification = $pesapalNotification;
        $pesapalTrackingId = $pesapalTrackingId;
        $pesapal_merchant_reference = $pesapal_merchant_reference;

        /* $pesapalNotification = $this->request->get['pesapal_notification_type'];
          $pesapalTrackingId = $this->request->get['pesapal_transaction_tracking_id'];
          $pesapal_merchant_reference = $this->request->get['pesapal_merchant_reference']; */

        if ('CHANGE' == $pesapalNotification && '' != $pesapalTrackingId) {
            $log->write('ipinlistener');
            $token = $params = null;
            $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
            $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

            //get transaction status
            $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $statusrequestAPI, $params);
            $request_status->set_parameter('pesapal_merchant_reference', $pesapal_merchant_reference);
            $request_status->set_parameter('pesapal_transaction_tracking_id', $pesapalTrackingId);
            $request_status->sign_request($signature_method, $consumer, $token);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (defined('CURL_PROXY_REQUIRED')) {
                if (CURL_PROXY_REQUIRED == 'True') {
                    $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && 'FALSE' == strtoupper(CURL_PROXY_TUNNEL_FLAG)) ? false : true;
                    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                }
            }

            $response = curl_exec($ch);
            $log->write($response);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header = $headerArray[count($headerArray) - 1];

            //transaction status
            $elements = preg_split('/=/', substr($response, $header_size));
            $status = $elements[1];
            $log->write('ORDER STATUS');
            $log->write($status);
            $log->write('ORDER STATUS');
            curl_close($ch);

            if (strpos($order_id, 'KBCUST') !== false) {
                $order_info = $this->model_checkout_order->getOrder($order_id);
                if ($response != null && $status != null && $status == 'FAILED') {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } elseif ($response != null && $status != null && $status == 'PENDING') {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } elseif ($response != null && $status != null && $status == 'COMPLETED') {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } else {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                }
            } else {
                $order_info = $this->model_checkout_order->getOrder($order_id);
                if ($response != null && $status != null && $status == 'FAILED') {
                    $this->model_payment_pesapal->addOrderHistoryFailed($order_id, $this->config->get('pesapal_failed_order_status_id'), $customer_id, 'customer', $order_info['paid']);
                    $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } elseif ($response != null && $status != null && $status == 'PENDING') {
                    $this->model_payment_pesapal->addOrderHistoryFailed($order_id, $this->config->get('pesapal_pending_order_status_id'), $customer_id, 'customer', $order_info['paid']);
                    $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } elseif ($response != null && $status != null && $status == 'COMPLETED') {
                    $this->model_payment_pesapal->addOrderHistory($order_id, $this->config->get('pesapal_order_status_id'), $order_info['paid']);
                    $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                }
            }
        }
        echo $status;
    }

    public function wallet() {
        $json = [];
        $json['success'] = "";
        $json['error'] = "";
        try {
            $this->load->model('payment/wallet');
            $this->load->model('checkout/order');

            if ($this->request->post['payment_type'] == 'pay_full') {
                $this->request->post['order_id'] = explode('--', $this->request->post['order_id']);
            }
            $log = new Log('error.log');
            $log->write('Transaction screen-Wallet deduction for orders ');
            // echo '<pre>';print_r($this->request->post['order_id']);exit;
            //wallet amount check is doing in tpl screen itself
            foreach ($this->request->post['order_id'] as $key => $value) {
                $log->write($value);
                $order_id = $value;
                $amount = 0;
                $order_info = $this->model_checkout_order->getOrder($value);
                if (count($order_info) > 0) {
                    $amount = ($order_info['total'] - $order_info['amount_partialy_paid']);
                }

                $this->model_payment_wallet->addTransactionCredit($this->customer->getId(), 'Wallet Amount Deduction #' . $order_id . ' ', $amount, $order_id);

                // Add to activity log
                $this->load->model('account/activity');
                $activity_data = [
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                ];

                $this->model_account_activity->addActivity('login', $activity_data);
            }
            $json['success'] = "Transactions successfully updated!";
        } catch (exception $ex) {
            $json['error'] = "Transaction Failed";
            $log = new Log('error.log');
            $log->write('Transaction screen-Wallet deduction for orders ');
            $log->write($ex);
        } finally {



            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

}
