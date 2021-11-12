<?php

class ControllerAccountLogout extends Controller {

    public function index() {
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if ($this->customer->isLogged()) {

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('logout', $activity_data);

            $this->trigger->fire('pre.customer.logout');

            $this->customer->logout();
            $this->cart->clear();

            unset($this->session->data['config_store_id']);
            unset($this->session->data['wishlist']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
            unset($this->session->data['redirect']);
            unset($this->session->data['email_sub_user_order_id']);
            unset($this->session->data['email_sub_user_id']);
            unset($this->session->data['email_parent_user_id']);
            unset($this->session->data['order_approval_access']);
            unset($this->session->data['order_approval_access_role']);
            unset($this->session->data['adminlogin']);
            unset($this->session->data['accept_vendor_terms']);
            unset($this->session->data['pezesha_amount_limit']);
            unset($this->session->data['pezesha_customer_amount_limit']);

            setcookie('zipcode', null, time() - 3600, '/');

            unset($this->session->data['config_store_id']);
            $this->trigger->fire('post.customer.logout');

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            /* For Default Page new login */
            unset($this->session->data['customer_id']);
            if (isset($this->session->data['customer_id'])) {
                $this->response->redirect(BASE_URL);
            }

            $this->response->redirect($server);
        }

        $this->load->language('account/logout');

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

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_logout'),
            'href' => $this->url->link('account/logout', '', 'SSL'),
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

        //REDIRECTING TO HOME PAGE AFTER LOGOUT
        $this->response->redirect('/');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    public function checkoutLogout() {
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $json['status'] = true;

        if ($this->customer->isLogged()) {
            //$this->trigger->fire('pre.customer.logout');

            $this->customer->logout();
            //$this->cart->clear();
            //unset($this->session->data['config_store_id']);
            //unset($this->session->data['wishlist']);
            //unset($this->session->data['shipping_address']);
            //unset($this->session->data['shipping_method']);
            //unset($this->session->data['shipping_methods']);
            //unset($this->session->data['payment_address']);
            //unset($this->session->data['payment_method']);
            //unset($this->session->data['payment_methods']);
            //unset($this->session->data['comment']);
            //unset($this->session->data['order_id']);
            //unset($this->session->data['coupon']);
            //unset($this->session->data['reward']);
            //unset($this->session->data['voucher']);
            //unset($this->session->data['vouchers']);
            //unset($this->session->data['config_store_id']);
            //$this->trigger->fire('post.customer.logout');

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
