<?php

//Email validator
include_once 'EmailAddressValidator.php';

class ControllerMarketingContact extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('marketing/contact');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_default'] = $this->language->get('text_default');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_customer_all'] = $this->language->get('text_customer_all');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_customer_group'] = $this->language->get('text_customer_group');
        $data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_to'] = $this->language->get('entry_to');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_affiliate'] = $this->language->get('entry_affiliate');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_subject'] = $this->language->get('entry_subject');
        $data['entry_message'] = $this->language->get('entry_message');

        $data['help_customer'] = $this->language->get('help_customer');
        $data['help_affiliate'] = $this->language->get('help_affiliate');
        $data['help_product'] = $this->language->get('help_product');

        $data['button_send'] = $this->language->get('button_send');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('marketing/contact', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['cancel'] = $this->url->link('marketing/contact', 'token='.$this->session->data['token'], 'SSL');

        // Text Editor
        $data['text_editor'] = $this->config->get('config_text_editor');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if ($this->config->get('config_sendy_status')) {
            $data['heading_title'] .= ' '.$this->language->get('text_via_sendy');
        }

        if (empty($data['text_editor'])) {
            $data['text_editor'] = 'tinymce';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('marketing/contact.tpl', $data));
    }

    public function send()
    {
        $this->load->language('marketing/contact');

        $json = [];

        if ('POST' == $this->request->server['REQUEST_METHOD']) {
            if (!$this->user->hasPermission('modify', 'marketing/contact')) {
                $json['error']['warning'] = $this->language->get('error_permission');
            }

            if (!$this->request->post['subject']) {
                $json['error']['subject'] = $this->language->get('error_subject');
            }

            if (!$this->request->post['message']) {
                $json['error']['message'] = $this->language->get('error_message');
            }

            if (!$json) {
                $this->load->model('setting/store');

                $store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

                $this->load->model('sale/customer');

                $this->load->model('sale/customer_group');

                $this->load->model('marketing/affiliate');

                $this->load->model('sale/order');

                if (isset($this->request->get['page'])) {
                    $page = $this->request->get['page'];
                } else {
                    $page = 1;
                }

                $email_total = 0;

                $emails = [];

                switch ($this->request->post['to']) {
                    case 'newsletter':
                        $customer_data = [
                            'filter_newsletter' => 1,
                            'start' => ($page - 1) * 10,
                            'limit' => 10,
                        ];

                        $email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

                        $results = $this->model_sale_customer->getCustomers($customer_data);

                        foreach ($results as $result) {
                            $emails[] = $result['email'];
                        }
                        break;
                    case 'customer_all':
                        $customer_data = [
                            'start' => ($page - 1) * 10,
                            'limit' => 10,
                        ];

                        $email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

                        $results = $this->model_sale_customer->getCustomers($customer_data);

                        foreach ($results as $result) {
                            $emails[] = $result['email'];
                        }
                        break;
                    case 'customer_group':
                        $customer_data = [
                            'filter_customer_group_id' => $this->request->post['customer_group_id'],
                            'start' => ($page - 1) * 10,
                            'limit' => 10,
                        ];

                        $email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

                        $results = $this->model_sale_customer->getCustomers($customer_data);

                        foreach ($results as $result) {
                            $emails[$result['customer_id']] = $result['email'];
                        }
                        break;
                    case 'customer':
                        if (!empty($this->request->post['customer'])) {
                            foreach ($this->request->post['customer'] as $customer_id) {
                                $customer_info = $this->model_sale_customer->getCustomer($customer_id);

                                if ($customer_info) {
                                    $emails[] = $customer_info['email'];
                                }
                            }
                        }
                        break;
                    case 'affiliate_all':
                        $affiliate_data = [
                            'start' => ($page - 1) * 10,
                            'limit' => 10,
                        ];

                        $email_total = $this->model_marketing_affiliate->getTotalAffiliates($affiliate_data);

                        $results = $this->model_marketing_affiliate->getAffiliates($affiliate_data);

                        foreach ($results as $result) {
                            $emails[] = $result['email'];
                        }
                        break;
                    case 'affiliate':
                        if (!empty($this->request->post['affiliate'])) {
                            foreach ($this->request->post['affiliate'] as $affiliate_id) {
                                $affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

                                if ($affiliate_info) {
                                    $emails[] = $affiliate_info['email'];
                                }
                            }
                        }
                        break;
                    case 'product':
                        if (isset($this->request->post['product'])) {
                            $email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

                            $results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

                            foreach ($results as $result) {
                                $emails[] = $result['email'];
                            }
                        }
                        break;
                }

                if ($emails) {
                    $start = ($page - 1) * 10;
                    $end = $start + 10;

                    if ($end < $email_total) {
                        $json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);
                    } else {
                        $json['success'] = $this->language->get('text_success');
                    }

                    if ($end < $email_total) {
                        $json['next'] = str_replace('&amp;', '&', $this->url->link('marketing/contact/send', 'token='.$this->session->data['token'].'&page='.($page + 1), 'SSL'));
                    } else {
                        $json['next'] = '';
                    }

                    $message = '<html dir="ltr" lang="en">'."\n";
                    $message .= '  <head>'."\n";
                    $message .= '    <title>'.$this->request->post['subject'].'</title>'."\n";
                    $message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
                    $message .= '  </head>'."\n";
                    $message .= '  <body>'.html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8').'</body>'."\n";
                    $message .= '</html>'."\n";

                    //make entry in list and subscriber table and get list id if setting is on for sendy else default will work

                    if ($this->config->get('config_sendy_status')) {
                        //echo "<pre>";print_r($emails);print_r("true");
                        $list_ids = $this->model_sale_customer->addInSendyListsAndSubscriber($emails);
                        //echo "<pre>";print_r($list_ids);print_r("strue");die;
                        if (isset($list_ids)) {
                            $list_ids = $this->short($list_ids);
                            //echo "if";print_r($list_ids);
                            $mailMessage = $this->sendyApi($this->request->post['subject'], $list_ids, $message);

                            $json['success'] = $mailMessage;
                        }
                    } else {
                        if (isset($store_info)) {
                            $store_name = $store_info['name'];
                        } else {
                            $store_name = $this->config->get('config_name');
                        }

                        foreach ($emails as $email) {
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $mail = new Mail($this->config->get('config_mail'));
                                $mail->setTo($email);
                                $mail->setFrom($this->config->get('config_from_email'));
                                $mail->setSender($store_name);
                                $mail->setSubject($this->request->post['subject']);
                                $mail->setHtml($message);
                                $mail->send();
                            }
                        }
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sendyApi($subject, $list_ids, $html)
    {
        $data['api_key'] = $this->config->get('config_sendy_public_key');
        // $data['html_text'] = '<p>Hi,</p>
        //                         <p><br></p>
        //                         <p>Hope everthing is going good. Did you receive this mail? Do respond.</p>
        //                         <p><br></p>
        //                         <p>Thanks,</p>
        //                         <p>Abhisehk</p>';

        $data['html_text'] = $html;

        $data['from_name'] = $this->config->get('config_sendy_mail_from_name');
        $data['from_email'] = $this->config->get('config_sendy_mail_from');
        $data['reply_to'] = $this->config->get('config_sendy_mail_from');
        $data['title'] = $subject.' - '.$list_ids; //of campaign

        $data['subject'] = $subject; //subject of campagin
        $data['list_ids'] = $list_ids;
        $data['send_campaign'] = 1; //of campaign

        //echo "<pre>";print_r($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_sendy_api_end'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);

        //print_r($result);
        curl_close($curl);
        $this->session->data['success'] = $result;

        return $result;
    }

    //2 way encrypt function

    public function short($in, $to_num = false)
    {
        global $api_key;

        $api_key = $this->config->get('config_sendy_public_key');
        $encryptionMethod = 'AES-256-CBC';

        //check if variable is an email
        $validator = new EmailAddressValidator();
        $is_email = $validator->check_email_address($in) ? true : false;

        if ($to_num) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) { //openssl_decrypt requires at least 5.3.0
                $decrypted = str_replace('892', '/', $in);
                $decrypted = str_replace('763', '+', $decrypted);

                if (function_exists('openssl_encrypt')) {
                    $decrypted = version_compare(PHP_VERSION, '5.3.3') >= 0 ? openssl_decrypt($decrypted, $encryptionMethod, $api_key, 0, '3j9hwG7uj8uvpRAT') : openssl_decrypt($decrypted, $encryptionMethod, $api_key, 0);
                    if (!$decrypted) {
                        return $is_email ? $in : intval($in, 36);
                    }
                } else {
                    return $is_email ? $in : intval($in, 36);
                }

                return '' == $decrypted ? intval($in, 36) : $decrypted;
            } else {
                return $is_email ? $in : intval($in, 36);
            }
        } else {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) { //openssl_encrypt requires at least 5.3.0
                if (function_exists('openssl_encrypt')) {
                    $encrypted = version_compare(PHP_VERSION, '5.3.3') >= 0 ? openssl_encrypt($in, $encryptionMethod, $api_key, 0, '3j9hwG7uj8uvpRAT') : openssl_encrypt($in, $encryptionMethod, $api_key, 0);
                    if (!$encrypted) {
                        return $is_email ? $in : base_convert($in, 10, 36);
                    }
                } else {
                    return $is_email ? $in : base_convert($in, 10, 36);
                }

                $encrypted = str_replace('/', '892', $encrypted);
                $encrypted = str_replace('+', '763', $encrypted);
                $encrypted = str_replace('=', '', $encrypted);

                return $encrypted;
            } else {
                return $is_email ? $in : base_convert($in, 10, 36);
            }
        }
    }
}
