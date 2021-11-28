<?php

class ControllerCommonMaintenance extends Controller
{
    public function index()
    {
        if ($this->config->get('config_coming_soon')) {
            $path = '';

            if (isset($this->request->get['path'])) {
                $part = explode('/', $this->request->get['path']);

                if (isset($part[0])) {
                    $path .= $part[0];
                }
            }

            // Show site if logged in as admin
            $this->load->library('user');

            $this->user = new User($this->registry);

            if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->request->isAjax()) {
                return new Action('common/coming_soon/send_mail');
            }

            if (('payment' != $path && 'api' != $path) && !$this->user->isLogged()) {
                return new Action('common/coming_soon/info');
            }

            //$this->request->get['path'] = 'common/coming_soon/info';
        }

        if ($this->config->get('config_maintenance')) {
            $path = '';

            if (isset($this->request->get['path'])) {
                $part = explode('/', $this->request->get['path']);

                if (isset($part[0])) {
                    $path .= $part[0];
                }
            }

            // Show site if logged in as admin
            $this->load->library('user');

            $this->user = new User($this->registry);

            if (('payment' != $path && 'api' != $path) && !$this->user->isLogged()) {
                return new Action('common/maintenance/info');
            }
        }
    }

    public function info()
    {
        $this->load->language('common/maintenance');

        $this->document->setTitle($this->language->get('heading_title'));

        if ('HTTP/1.1' == $this->request->server['SERVER_PROTOCOL']) {
            $this->response->addHeader('HTTP/1.1 503 Service Unavailable');
        } else {
            $this->response->addHeader('HTTP/1.0 503 Service Unavailable');
        }

        $this->response->addHeader('Retry-After: 3600');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_maintenance'),
            'href' => $this->url->link('common/maintenance'),
        ];

        $data['message'] = $this->language->get('text_message');

        $data['header'] = $this->load->controller('common/header/onlyHeader');
        $data['footer'] = $this->load->controller('common/footer');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/maintenance.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/maintenance.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/maintenance.tpl', $data));
        }
    }
}
