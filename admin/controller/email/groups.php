<?php

class ControllerEmailGroups extends Controller {
    
    public function index() {
		$this->load->model('email/groups');

		$data = array();
		$data['groups'] = $this->model_email_groups->getGroups();
		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('email/groups.tpl', $data));
	}

}