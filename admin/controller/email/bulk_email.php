<?php

class ControllerEmailBulkEmail extends Controller {

    public function index() {
        $this->document->setTitle('Bulk Email Groups');
        $this->load->model('email/groups');

        $data = [];
        $results = $this->model_email_groups->getGroups();
        foreach ($results as $result) {
            $data['groups'][] = [
                'group_id' => $result['id'],
                'name' => $result['name'],
                'description' => $result['description'],
                'customers' => $this->url->link('email/groups/groupCustomers', 'token=' . $this->session->data['token'] . '&group_id=' . $result['id'], 'SSL'),
                'edit' => $this->url->link('email/groups/edit', 'token=' . $this->session->data['token'] . '&group_id=' . $result['id'], 'SSL'),
                'delete' => $this->url->link('email/groups/delete', 'token=' . $this->session->data['token'] . '&group_id=' . $result['id'], 'SSL'),
            ];
        }

        $data['add'] = $this->url->link('email/groups/add', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('email/customer_bulk_email.tpl', $data));
    }

}
