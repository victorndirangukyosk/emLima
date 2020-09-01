<?php

class ControllerAccountGoogle extends Controller
{
    public function index()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (isset($this->request->get['from_chk'])) {
            $this->session->data['redirect'] = $this->url->link('checkout/checkout');
        }

        $url = null;

        if (isset($this->request->get['category'])) {
            $url = $this->request->get['category'];
        }

        if (isset($this->request->get['redirect_url'])) {
            if (false !== ($pos = strpos($this->request->get['redirect_url'], 'path='))) {
                $redirectPath = substr($this->request->get['redirect_url'], $pos + 5);
                if ($url) {
                    $redirectPath .= '&category='.$url;
                }
                $this->session->data['redirect'] = $this->url->link($redirectPath);
            } else {
                //echo "<pre>";print_r($this->request->get['redirect_url']);die;
                $this->session->data['redirect'] = rawurldecode($this->request->get['redirect_url']);
                //https://dev.suacompraonline.com.br/google?redirect_url=/stores?zipcode=80420-170
            }
        }

        if (!class_exists('Google')) {
            require_once DIR_SYSTEM.'vendor/Google/autoload.php';
        }

        $this->googleObject = new Google_Client();

        $this->googleObject->setClientId($this->config->get('config_google_client_id'));
        $this->googleObject->setClientSecret($this->config->get('config_google_client_secret'));

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->googleObject->setRedirectUri($server.'index.php?path=account/google/callback');
        $this->googleObject->setScopes(['https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email']);

        $this->response->redirect($this->googleObject->createAuthUrl());
    }

    public function callback()
    {
        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login'));
        }

        if (!class_exists('Google')) {
            require_once DIR_SYSTEM.'vendor/Google/autoload.php';
            // require_once(DIR_SYSTEM . 'library/vendor/google-api/contrib/Google_Oauth2Service.php');
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->googleObject = new Google_Client();

        $this->googleObject->setClientId($this->config->get('config_google_client_id'));
        $this->googleObject->setClientSecret($this->config->get('config_google_client_secret'));

        $this->googleObject->setRedirectUri($server.'index.php?path=account/google/callback'); //$this->url->link('account/google/callback')
        $this->googleObject->setScopes(['https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email']);

        $oauth2 = new Google_Service_Oauth2($this->googleObject); //Google_Oauth2Service

        $salt = '';

        if (isset($this->request->get['code'])) {
            $this->googleObject->authenticate($this->request->get['code']);
            $token = $this->googleObject->getAccessToken();
        }

        if (isset($token) && isset($oauth2)) {
            $this->googleObject->setAccessToken($token);
            $user = $oauth2->userinfo->get();

            $log = new Log('error.log');
            $log->write('googl user');
            $log->write($user);
            if (empty($user['error'])) {
                if (!$user['verified_email']) {
                    $this->session->data['warning'] = 'Error: Google Validation Not Completed Successfully!';
                    $this->response->redirect($this->url->link('account/login'));
                }

                $this->load->model('account/customer');

                $email = $user['email'];
                $salt = (!empty($salt)) ? $salt : 'qwd2asdaej62ad\0';
                $password = $this->encrypt($user['id'], $salt);

                // 1) Already register with given email
                if ($this->customer->login($email, '', true)) {
                    $this->session->data['just_loggedin'] = true;

                    if (isset($this->session->data['redirect'])) {
                        $this->response->redirect($this->session->data['redirect']);
                        unset($this->session->data['redirect']);
                    } else {
                        $this->response->redirect($this->url->link('account/account'));
                    }

                    // 2) register customer
                } else {
                    $this->request->post['email'] = $email;

                    $this->load->model('account/customer');

                    $data = [
                        'firstname' => isset($user['given_name']) ? $user['given_name'] : '',
                        'lastname' => isset($user['family_name']) ? $user['family_name'] : '',
                        'email' => $user['email'],
                        'telephone' => '',
                        'fax' => '',
                        'password' => $password,
                    ];

                    $this->model_account_customer->addCustomer($data);

                    $this->session->data['just_loggedin'] = true;

                    if ($this->customer->login($email, '', true)) {
                        if (isset($this->session->data['redirect'])) {
                            $this->response->redirect($this->session->data['redirect']);
                            unset($this->session->data['redirect']);
                        } else {
                            $this->response->redirect($this->url->link('account/account'));
                        }
                    }
                }
            }
        }

        $this->session->data['warning'] = 'Error: Google Validation Not Completed Successfully!';
        $this->response->redirect($this->url->link('common/home'));
    }

    public function encrypt($text, $salt)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public function decrypt($text, $salt)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    public function htmlspecialcharsDecode($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->htmlspecialcharsDecode($key)] = $this->htmlspecialcharsDecode($value);
            }
        } else {
            $data = htmlspecialchars_decode($data, ENT_COMPAT);
        }

        return $data;
    }
}
