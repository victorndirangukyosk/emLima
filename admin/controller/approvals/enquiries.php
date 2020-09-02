 <?php

class ControllerApprovalsEnquiries extends Controller
{
    private $error = [];

    public function index()
    {
        //echo "<pre>";print_r(NEW_HTTPS_ADMIN);die;
        $this->language->load('approvals/enquiries');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('approvals/enquiry');
        $this->getList();
    }

    public function approve()
    {
        $this->language->load('approvals/enquiries');

        $data['heading_text1'] = $this->language->get('heading_text1');

        $data['text_vendor_group'] = $this->language->get('text_vendor_group');
        $data['text_commision'] = $this->language->get('text_commision');

        $data['button_submit'] = $this->language->get('button_submit');

        $this->load->model('approvals/enquiry');

        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            $vendorData = $this->model_approvals_enquiry->getEnquiry($this->request->post['enquiry_id']);

            //echo "<pre>";print_r($data);die;

            $this->session->data['success'] = 'Success: Enquiry Moved To Vendor List Successfully!';

            //echo "<pre>";print_r($vendorData);die;
            if (isset($vendorData['email'])) {
                //$vendorData = HTTPS_ADMIN ;

                // 4 merchant mail and 5 admin mail
                if ($this->request->server['HTTPS']) {
                    $server = NEW_HTTPS_ADMIN;
                } else {
                    $server = NEW_HTTP_ADMIN;
                }

                $vendorData['login_link'] = $server;

                $subject = $this->emailtemplate->getSubject('Seller', 'seller_1', $vendorData);
                $message = $this->emailtemplate->getMessage('Seller', 'seller_1', $vendorData);
                //mishramanjari15@gmail.com

                //echo "<pre>";print_r($message);die;
                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo($vendorData['email']);
                $mail->setFrom($this->config->get('config_from_email'));
                //$mail->setReplyTo($vendorData['email']);
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
                $mail->send();

                $subject = $this->emailtemplate->getSubject('Seller', 'seller_2', $vendorData);
                $message = $this->emailtemplate->getMessage('Seller', 'seller_2', $vendorData);

                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo($this->config->get('config_email'));
                $mail->setFrom($this->config->get('config_from_email'));
                //$mail->setReplyTo($this->request->post['email']);
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }

            $this->model_approvals_enquiry->move($this->request->post);

            echo json_encode(['status' => 1]);
            die();
        }

        $data['action'] = $this->url->link('approvals/enquiries/approve');

        $data['rows'] = $this->model_approvals_enquiry->getVendorGroup($this->config->get('config_vendor_group_ids'));

        $data['enquiry_id'] = $this->request->get['enquiry_id'];
        $this->response->setOutput($this->load->view('approvals/enquiries_approve.tpl', $data));
    }

    public function view()
    {
        $this->language->load('approvals/enquiries_view');
        $this->load->model('approvals/enquiry');

        $data = $this->model_approvals_enquiry->getEnquiry($this->request->get['enquiry_id']);

        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_password'] = $this->language->get('column_password');
        $data['column_username'] = $this->language->get('column_username');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_business'] = $this->language->get('column_business');
        $data['column_type'] = $this->language->get('column_type');
        $data['column_tin_no'] = $this->language->get('column_tin_no');
        $data['column_mobile'] = $this->language->get('column_mobile');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_store_nam'] = $this->language->get('column_store_nam');
        $data['column_about_us'] = $this->language->get('column_about_us');
        $data['column_store_name'] = $this->language->get('column_store_name');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('approvals/enquiries', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('approvals/enquiries/view', 'enquiry_id='.$this->request->get['enquiry_id'].'&token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('approvals/enquries_view.tpl', $data));
    }

    public function delete()
    {
        $this->language->load('approvals/enquiries');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('approvals/enquiry');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $user_id) {
                $this->model_approvals_enquiry->delete($user_id);
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

            $this->response->redirect($this->url->link('approvals/enquiries', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'enquiry_id';
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
            'href' => $this->url->link('approvals/enquiries', 'token='.$this->session->data['token'].$url, 'SSL'),
            'separator' => ' :: ',
        ];

        $data['insert'] = $this->url->link('approvals/enquiries/insert', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('approvals/enquiries/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['users'] = [];

        $data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit'),
        ];

        $this->document->addScript('ui/javascript/jquery/fancybox/jquery.fancybox.js');
        $this->document->addStyle('ui/javascript/jquery/fancybox/jquery.fancybox.css');

        $total = $this->model_approvals_enquiry->getTotal();

        $data['results'] = [];

        $results = $this->model_approvals_enquiry->get($data);

        foreach ($results as $row) {
            $row['view'] = $this->url->link('approvals/enquiries/view', 'enquiry_id='.$row['enquiry_id'].'&token='.$this->session->data['token']);

            $row['approve'] = $this->url->link('approvals/enquiries/approve', 'enquiry_id='.$row['enquiry_id'].'&token='.$this->session->data['token']);

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

        $data['delete'] = $this->url->link('approvals/enquiries/delete', 'token='.$this->session->data['token']);

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
        $pagination->url = $this->url->link('approvals/enquiries', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['pagination_results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        if (isset($this->request->post['selected'])) {
            $data['selected'] = $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        //echo "<pre>";print_r($data);die;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('approvals/enquiries_list.tpl', $data));
    }
}

?>