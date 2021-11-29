<?php

class ControllerToolClearData extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('tool/clear_data');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('tool/clear_data');
        $this->getForm();
    }

    protected function getForm()
    {
        $data = [];
        $data['heading_title'] = $this->language->get('heading_title');

        $data['tab_reset'] = $this->language->get('tab_reset');
        $data['tab_load'] = $this->language->get('tab_load');

        $data['text_india'] = $this->language->get('text_india');
        $data['text_brazil'] = $this->language->get('text_brazil');
        $data['text_usa'] = $this->language->get('text_usa');

        unset($this->session->data['export_import_error']);
        unset($this->session->data['export_import_nochange']);

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
            if (!empty($this->session->data['export_import_nochange'])) {
                $data['error_warning'] .= "<br />\n".$this->language->get('text_nochange');
            }
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/export_import', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['back'] = $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL');
        $data['button_back'] = $this->language->get('button_back');

        $data['action'] = $this->url->link('tool/clear_data/clear', 'token='.$this->session->data['token'], 'SSL');

        $data['load_action'] = $this->url->link('tool/clear_data/load', 'token='.$this->session->data['token'], 'SSL');

        $data['reset_factory_action'] = $this->url->link('tool/clear_data/resetfactory', 'token='.$this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/clear_data.tpl', $data));
    }

    public function clear()
    {
        $this->load->language('tool/clear_data');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/clear_data');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            if (!$this->user->isVendor() && isset($this->request->post['checked'])) {
                foreach ($this->request->post['checked'] as $key => $value) {
                    // code...
                    $this->model_tool_clear_data->$value();
                }
                $this->session->data['success'] = $this->language->get('text_success');
                $this->response->redirect($this->url->link('tool/clear_data', 'token='.$this->session->data['token'], 'SSL'));
            } else {
                $this->error['warning'] = $this->language->get('text_error');
            }
        }

        $this->getForm();
    }

    public function load()
    {
        $this->load->language('tool/clear_data');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/clear_data');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            if (!$this->user->isVendor() && isset($this->request->post['load_db'])) {
                $result = $this->model_tool_clear_data->loadSql($this->request->post['load_db']);

                if ($result) {
                    $this->session->data['success'] = $this->language->get('text_success_load');
                    $this->response->redirect($this->url->link('tool/clear_data', 'token='.$this->session->data['token'], 'SSL'));
                } else {
                    $this->error['warning'] = $this->language->get('text_error');
                }
            } else {
                $this->error['warning'] = $this->language->get('text_error');
            }
        }

        $this->getForm();
    }

    public function resetfactory()
    {
        $this->load->language('tool/clear_data');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/clear_data');

        echo 'resetfactory';
        die;
        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            if (!$this->user->isVendor() && isset($this->request->post['load_db'])) {
                $result = $this->model_tool_clear_data->loadSql($this->request->post['load_db']);

                if ($result) {
                    $this->session->data['success'] = $this->language->get('text_success_load');
                    $this->response->redirect($this->url->link('tool/clear_data', 'token='.$this->session->data['token'], 'SSL'));
                } else {
                    $this->error['warning'] = $this->language->get('text_error');
                }
            } else {
                $this->error['warning'] = $this->language->get('text_error');
            }
        }

        $this->getForm();
    }
}
