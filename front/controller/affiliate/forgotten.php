<?php

class ControllerAffiliateForgotten extends Controller
{
    private $error = [];

    public function index()
    {
        if ($this->affiliate->isLogged()) {
            $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
        }

        $this->load->language('affiliate/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('affiliate/affiliate');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->language('mail/forgotten');

            $password = substr(md5(mt_rand()), 0, 10);

            $this->model_affiliate_affiliate->editPassword($this->request->post['email'], $password);

            $this->model_affiliate_affiliate->resetPasswordMail($this->request->post['email'], $password);

            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($this->request->post['email']);

            if ($affiliate_info) {
                $this->load->model('affiliate/activity');

                $activity_data = [
                    'affiliate_id' => $affiliate_info['affiliate_id'],
                    'name' => $affiliate_info['firstname'].' '.$affiliate_info['lastname']
                ];

                $this->model_affiliate_activity->addActivity('forgotten', $activity_data);
            }

            $this->response->redirect($this->url->link('affiliate/login', '', 'SSL'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', '', 'SSL')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_forgotten'),
            'href' => $this->url->link('affiliate/forgotten', '', 'SSL')
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_email'] = $this->language->get('text_email');

        $data['entry_email'] = $this->language->get('entry_email');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('affiliate/forgotten', '', 'SSL');

        $data['back'] = $this->url->link('affiliate/login', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/affiliate/forgotten.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/affiliate/forgotten.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/affiliate/forgotten.tpl', $data));
        }
    }

    protected function validate()
    {
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }
}
