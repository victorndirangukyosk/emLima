<?php

class ControllerCommonUpdate extends Controller
{
    public function index()
    {
        $this->language->load('common/update');

        $data = $this->language->all();

        $this->document->setTitle($data['heading_title']);

        if (!$this->validate('access')) {
            exit();
        }

        if (isset($this->session->data['msg_success'])) {
            $data['text_success'] = $this->session->data['msg_success'];
            unset($this->session->data['msg_success']);
        }

        if (isset($this->session->data['msg_error'])) {
            $data['text_error'] = $this->session->data['msg_error'];
            unset($this->session->data['msg_error']);
        }

        if (!extension_loaded('xml')) {
            $data['text_error'] = $this->language->get('error_xml');
        }

        if (!extension_loaded('zip')) {
            $data['text_error'] = $this->language->get('error_zip');
        }

        $data['token'] = $this->session->data['token'];

        $data['check'] = $this->url->link('common/update/check', 'token='.$this->session->data['token'], 'SSL');
        $data['update'] = $this->url->link('common/update/update', 'token='.$this->session->data['token'], 'SSL');

        $addon = new Addon($this->registry);
        $data['addons'] = $addon->getAddons();

        $data['updates'] = $this->update->getUpdates();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/update.tpl', $data));
    }

    public function check()
    {
        if ($this->validate('modify')) {
            $this->load->model('common/update');

            // Check
            if (!$this->model_common_update->check()) {
                $this->session->data['msg_error'] = $this->language->get('text_check_error');
            } else {
                $this->session->data['msg_success'] = $this->language->get('text_check_success');
            }
        }

        // Return
        $this->response->redirect($this->url->link('common/update', 'token='.$this->session->data['token'], 'SSL'));
    }

    public function update()
    {
        $this->language->load('common/update');

        if ($this->validate('modify') and !empty($this->request->get['product_id'])) {
            $this->load->model('common/update');

            // Update
            if (!$this->model_common_update->update()) {
                $this->session->data['msg_error'] = $this->language->get('text_update_error');
            } else {
                $this->session->data['msg_success'] = $this->language->get('text_update_success');

                $this->model_common_update->check();
            }
        }

        // Return
        $this->response->redirect($this->url->link('common/update', 'token='.$this->session->data['token'], 'SSL'));
    }

    protected function validate($type)
    {
        if (!$this->user->hasPermission($type, 'common/update')) {
            $error['warning'] = $this->language->get('error_permission');
            echo json_encode($error);
        }

        if (empty($error['warning'])) {
            return true;
        } else {
            return false;
        }
    }
}
