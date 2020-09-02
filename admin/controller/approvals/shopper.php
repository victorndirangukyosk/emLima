<?php

class ControllerApprovalsShopper extends Controller
{
    private $error = [];

    public function index()
    {
        $this->language->load('approvals/shopper');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('approvals/shopper');
        $this->getList();
    }

    public function approve()
    {
        $this->language->load('approvals/shopper');

        $data['heading_text1'] = $this->language->get('heading_text1');

        $data['text_shopper_group'] = $this->language->get('text_shopper_group');

        $data['button_submit'] = $this->language->get('button_submit');

        $this->load->model('approvals/shopper');

        if ($this->request->post) {
            $this->load->model('approvals/shopper');
            $this->model_approvals_shopper->move($this->request->post);
            $this->session->data['success'] = 'Success: Shooper Approved Successfully!';
            echo json_encode(['status' => 1]);
            die();
        }

        $data['rows'] = $this->model_approvals_shopper->getUserGroup($this->config->get('config_shopper_group_ids'));
        $data['shopper_id'] = $this->request->get['shopper_id'];
        $this->response->setOutput($this->load->view('approvals/shopper_approve.tpl', $data));
    }

    public function view()
    {
        $this->language->load('approvals/shopper_view');
        $this->load->model('approvals/shopper');

        $data = $this->model_approvals_shopper->getCityAndShopper($this->request->get['shopper_id']);

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_password'] = $this->language->get('column_password');
        $data['column_username'] = $this->language->get('column_username');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_mobile'] = $this->language->get('column_mobile');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_name'] = $this->language->get('column_name');

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('approvals/shopper', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('approvals/shopper/view', 'shopper_id='.$this->request->get['shopper_id'].'&token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('approvals/shopper_view.tpl', $data));
    }

    public function delete()
    {
        $this->language->load('approvals/shopper');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('approvals/shopper');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $user_id) {
                $this->model_approvals_shopper->delete($user_id);
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

            $this->response->redirect($this->url->link('approvals/shopper', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'shopper_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

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

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('approvals/shopper', 'token='.$this->session->data['token'].$url, 'SSL'),
            'separator' => ' :: ',
        ];

        $data['insert'] = $this->url->link('approvals/shopper/insert', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('approvals/shopper/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['users'] = [];

        $data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit'),
        ];

        $this->document->addScript('ui/javascript/jquery/fancybox/jquery.fancybox.js');
        $this->document->addStyle('ui/javascript/jquery/fancybox/jquery.fancybox.css');

        $total = $this->model_approvals_shopper->getTotal();

        $data['results'] = [];

        $results = $this->model_approvals_shopper->get($data);

        foreach ($results as $row) {
            $row['view'] = $this->url->link('approvals/shopper/view', 'shopper_id='.$row['shopper_id'].'&token='.$this->session->data['token']);
            $row['approve'] = $this->url->link('approvals/shopper/approve', 'shopper_id='.$row['shopper_id'].'&token='.$this->session->data['token']);
            $data['results'][] = $row;
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_text'] = $this->language->get('heading_text');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_delete'] = $this->language->get('button_delete');

        $data['delete'] = $this->url->link('approvals/shopper/delete', 'token='.$this->session->data['token']);

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

        $url = '';

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('approvals/shopper', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['pagination_results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        if (isset($this->request->post['selected'])) {
            $data['selected'] = $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('approvals/shopper_list.tpl', $data));
    }
}
