<?php 

class ControllerAccountFeedback extends Controller {

    private $error = [];

    public function index() {
          
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/feedback', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        // $this->load->language('account/feedback');
        // $this->document->setTitle($this->language->get('heading_title')); 
        // $this->load->model('account/feedback');
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/feedback', '', 'SSL'),
        ];

        $data['heading_title'] = 'Feedback Form';
        $data['text_no_results'] = 'No results!';        
            

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        $data['base'] = $server;
        $data['action'] = $this->url->link('account/feedback', '', 'SSL'); 
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyheader');
        $data['home'] = $this->url->link('common/home/toHome');
        // echo "<pre>";print_r($data);die;
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/feedback.tpl', $data));
    }
 
    public function saveFeedback()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/feedback', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }         
        // $this->load->language('account/feedback');
        // $this->document->setTitle($this->language->get('heading_title'));
 
        $this->load->model('account/feedback');

        if (('POST' == $this->request->server['REQUEST_METHOD'])  ) {
            $this->model_account_feedback->saveFeedback($this->request->post);
            $this->session->data['success'] = "Thanks for your feedback";  
            // $this->response->redirect($this->url->link('account/account', '', 'SSL'));


        }

        // $this->getForm();
    }
}
