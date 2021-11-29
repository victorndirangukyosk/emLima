<?php

class ControllerCatalogPackages extends Controller
{
    private $error = [];

    public function info()
    {
        $this->language->load('catalog/packages');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/packages');

        if (isset($this->request->get['package_id'])) {
            $package_id = $this->request->get['package_id'];
        } else {
            $this->response->redirect($this->url->link('error/not_found', 'token='.$this->session->data['token']));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/packages', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Info',
            'href' => $this->url->link('catalog/packages/info', 'package_id='.$package_id.'&token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        //basic info
        $data['package'] = $this->model_catalog_packages->getPackage($package_id);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/package_info.tpl', $data));
    }

    public function index()
    {
        $this->language->load('catalog/packages');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/packages');

        $this->getList();
    }

    public function insert()
    {
        $this->language->load('catalog/packages');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/packages');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $package_id = $this->model_catalog_packages->add($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/packages/update', 'package_id='.$package_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/packages/insert', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function update()
    {
        $this->language->load('catalog/packages');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/packages');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_catalog_packages->edit($this->request->get['package_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL'));
                /*$this->response->redirect( $this->url->link( 'catalog/packages/update', 'package_id=' . $this->request->get['package_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL' ) );*/
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/packages/insert', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL'));
        }
        $this->getForm();
    }

    public function delete()
    {
        $this->language->load('catalog/packages');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/packages');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_catalog_packages->delete($id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit'),
        ];

        $total = $this->model_catalog_packages->getTotal();

        $results = $this->model_catalog_packages->getPackages($data);

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $data['results'] = [];

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL'),
            'separator' => ' :: ',
        ];

        $data['insert'] = $this->url->link('catalog/packages/insert', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('catalog/packages/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        foreach ($results as $result) {
            $action = [];

            /*$action[] = array(
                'text' => 'Info',
                'href' => $this->url->link('catalog/packages/info', 'token=' . $this->session->data['token'] . '&package_id=' . $result['package_id'] . $url, 'SSL')
            );*/

            $result['edit'] = $this->url->link('catalog/packages/update', 'token='.$this->session->data['token'].'&package_id='.$result['package_id'].$url, 'SSL');
            $result['selected'] = isset($this->request->post['selected']) && in_array($result['package_id'], $this->request->post['selected']);

            $data['results'][] = $result;
        }

        $url = '';

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/packages', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        $data['sort_amount'] = $this->url->link('catalog/packages', 'token='.$this->session->data['token'].'&sort=amount'.$url, 'SSL');

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_action'] = $this->language->get('column_action');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_benefits'] = $this->language->get('column_benefits');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_priority'] = $this->language->get('column_priority');
        $data['column_date'] = $this->language->get('column_date');

        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        $data['page_results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/package_list.tpl', $data));
    }

    protected function getForm()
    {
        $this->language->load('catalog/packages');

        $this->load->model('catalog/packages');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_free_month'] = $this->language->get('entry_free_month');
        $data['entry_priority'] = $this->language->get('entry_priority');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['amount'])) {
            $data['error_amount'] = $this->error['amount'];
        } else {
            $data['error_amount'] = '';
        }

        if (isset($this->error['free_month'])) {
            $data['error_free_month'] = $this->error['free_month'];
        } else {
            $data['error_free_month'] = '';
        }

        if (isset($this->error['free_year'])) {
            $data['error_free_year'] = $this->error['free_year'];
        } else {
            $data['error_free_year'] = '';
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL'),
            'separator' => ' :: ',
        ];

        if (!isset($this->request->get['package_id'])) {
            $data['action'] = $this->url->link('catalog/packages/insert', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/packages/update', 'token='.$this->session->data['token'].'&package_id='.$this->request->get['package_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/packages', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['package_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $package_info = $this->model_catalog_packages->getPackage($this->request->get['package_id']);
            $data['text_form'] = $this->language->get('text_form_edit');
        } else {
            $data['text_form'] = $this->language->get('text_form_add');
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($package_info)) {
            $data['name'] = $package_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['amount'])) {
            $data['amount'] = $this->request->post['amount'];
        } elseif (!empty($package_info)) {
            $data['amount'] = $package_info['amount'];
        } else {
            $data['amount'] = '';
        }

        if (isset($this->request->post['free_month'])) {
            $data['free_month'] = $this->request->post['free_month'];
        } elseif (!empty($package_info)) {
            $data['free_month'] = $package_info['free_month'];
        } else {
            $data['free_month'] = '';
        }

        if (isset($this->request->post['free_year'])) {
            $data['free_year'] = $this->request->post['free_year'];
        } elseif (!empty($package_info)) {
            $data['free_year'] = $package_info['free_year'];
        } else {
            $data['free_year'] = '';
        }

        if (isset($this->request->post['priority'])) {
            $data['priority'] = $this->request->post['priority'];
        } elseif (!empty($package_info)) {
            $data['priority'] = $package_info['priority'];
        } else {
            $data['priority'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($package_info)) {
            $data['status'] = $package_info['status'];
        } else {
            $data['status'] = 0;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/package_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/packages')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 20)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/packages')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['selected'] as $vendor_id) {
            if ($this->user->getId() == $vendor_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
