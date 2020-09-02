<?php

class ControllerErrorPermission extends Controller
{
    public function index()
    {
        $this->load->language('error/permission');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_permission'] = $this->language->get('text_permission');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('error/permission', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $shopper_group_ids = explode(',', $this->config->get('config_shopper_group_ids'));

        if (in_array($this->user->getGroupId(), $shopper_group_ids)) {
            $data['header'] = $this->load->controller('shopper/common/header');
            $data['footer'] = $this->load->controller('shopper/common/footer');

            $this->response->setOutput($this->load->view('shopper/error/permission.tpl', $data));
        } else {
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('error/permission.tpl', $data));
        }
    }

    public function check()
    {
        if (isset($this->request->get['path'])) {
            $path = '';

            $part = explode('/', $this->request->get['path']);

            if (isset($part[0])) {
                $path .= $part[0];
            }

            if (isset($part[1])) {
                $path .= '/'.$part[1];
            }

            $ignore = [
                'common/dashboard',
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission',
                'dashboard/activity',
                'dashboard/chart',
                'dashboard/charts',
                'dashboard/customer',
                'dashboard/map',
                'dashboard/online',
                'dashboard/order',
                'dashboard/recent',
                'dashboard/recenttabs',
                'dashboard/sale',
            ];

            if (!in_array($path, $ignore) && !$this->user->hasPermission('access', $path)) {
                return new Action('error/permission');
            }
        }
    }
}
