<?php

class ControllerAccountFacebook extends Controller
{
    public function index()
    {
        $this->load->model('account/facebook');

        $this->load->language('account/account');
        $log = new Log('error.log');
        $url = null;
        if (isset($this->request->get['category'])) {
            $url = $this->request->get['category'];
        }

        $log->write('face 1');

        require DIR_SYSTEM.'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.4',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: '.$e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo 'Error: '.$helper->getError()."\n";
                echo 'Error Code: '.$helper->getErrorCode()."\n";
                echo 'Error Reason: '.$helper->getErrorReason()."\n";
                echo 'Error Description: '.$helper->getErrorDescription()."\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        $log->write('Access Token');
        $log->write($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

        $log->write('Metadata');
        $log->write($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->config->get('config_fb_app_id')); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        $log->write('validateExpiration');
        $log->write($tokenMetadata);

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo '<p>Error getting long-lived access token: '.$helper->getMessage()."</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
            $log->write('Long-lived</h3>');
            $log->write($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.4',
            'default_access_token' => $_SESSION['fb_access_token'],
        ]);

        $log->write($accessToken);
        $log->write('session');
        $log->write($_SESSION['fb_access_token']);
        try {
            // Get the Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            //$response = $fb->get('/me?fields=id,name,email'); //, '{access-token}'
            $response = $fb->get('/me?fields=id,email,name', $_SESSION['fb_access_token']);

            $log->write($response);
            $user = $response->getGraphUser();

            $log->write($user);

            if ($user) {
                $data = [
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'email' => $user->getEmail(),
                ];

                $log->write($data);

                $is_login = false;
                if (isset($data['email'])) {
                    $is_login = $this->model_account_facebook->callback($user);
                }

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $this->session->data['just_loggedin'] = true;

                $log->write($is_login);
                if ($is_login && isset($this->session->data['redirect'])) {
                    $log->write('if 1');
                    $this->response->redirect($this->session->data['redirect']);
                    unset($this->session->data['redirect']);
                } elseif ($is_login) {
                    $log->write('if 2');
                    $this->response->redirect($this->url->link('account/account'));
                } else {
                    $log->write('if 3');

                    $this->session->data['error'] = $this->language->get('error_fb_email');

                    $this->response->redirect($server);
                }
            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: '.$e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;
        }
    }
}
