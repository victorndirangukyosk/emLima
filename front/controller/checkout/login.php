<?php

class ControllerCheckoutLogin extends Controller
{
    private $error = [];

    public function index()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }

        $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ('payment' == $this->config->get('config_tax_customer')) {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ('shipping' == $this->config->get('config_tax_customer')) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
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

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_signin'] = $this->language->get('text_signin');
        $data['text_place_order'] = $this->language->get('text_place_order');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_facebook'] = $this->language->get('button_facebook');
        $data['button_google'] = $this->language->get('button_google');
        $data['button_signin'] = $this->language->get('button_signin');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('checkout/login', '', 'SSL');
        $data['register'] = $this->url->link('checkout/register', '', 'SSL');

        //get fb login url
        require DIR_SYSTEM.'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5', //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $helper = $fb->getRedirectLoginHelper();

        $data['facebook'] = $helper->getLoginUrl($server.'index.php?path=account/facebook', ['email']);

        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');
        
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        
        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }
        
        $data['store_name'] = $this->config->get('config_name');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/login.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/checkout/login.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/login.tpl', $data));
        }
    }

    protected function validate()
    {
        $this->trigger->fire('pre.customer.login');

        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

        if ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

                $this->trigger->fire('post.customer.login');
            }
        }

        return !$this->error;
    }

    public function save()
    {
        $this->load->language('checkout/checkout');

        $json = [];

        if ($this->customer->isLogged()) {
            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            $this->customer->logout();
        }

        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        if (!$json) {
            $this->load->model('account/customer');

            // Check how many login attempts have been made.
            $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

            if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
                $json['error']['warning'] = $this->language->get('error_attempts');
            }

            // Check if customer has been approved.
            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if ($customer_info && !$customer_info['approved']) {
                $json['error']['warning'] = $this->language->get('error_approved');
            }

            if (!isset($json['error'])) {
                if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                    $json['error']['warning'] = $this->language->get('error_login');

                    $this->model_account_customer->addLoginAttempt($this->request->post['email']);
                } else {
                    $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
                }
            }
        }

        if (!$json) {
            unset($this->session->data['guest']);

            $this->load->model('account/address');

            if ('payment' == $this->config->get('config_tax_customer')) {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ('shipping' == $this->config->get('config_tax_customer')) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('login', $activity_data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function login()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }

        $this->load->language('account/login');

        //$this->document->setTitle($this->language->get('heading_title'));

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ('payment' == $this->config->get('config_tax_customer')) {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ('shipping' == $this->config->get('config_tax_customer')) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
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

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_signin'] = $this->language->get('text_signin');
        $data['text_place_order'] = $this->language->get('text_place_order');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_facebook'] = $this->language->get('button_facebook');
        $data['button_google'] = $this->language->get('button_google');
        $data['button_signin'] = $this->language->get('button_signin');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('checkout/login', '', 'SSL');
        $data['register'] = $this->url->link('checkout/register', '', 'SSL');

        //get fb login url
        require DIR_SYSTEM.'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5', //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);

        $helper = $fb->getRedirectLoginHelper();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['facebook'] = $helper->getLoginUrl($server.'index.php?path=account/facebook', ['email']);

        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/loginform.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/checkout/loginform.tpl', $data);
        } else {
            return $this->load->view('default/template/checkout/loginform.tpl', $data);
        }
    }
}
