<?php

class ControllerEmailGroups extends Controller {
    
    public function index() {
		$this->document->setTitle("Bulk Email Groups");

		$this->load->model('email/groups');

		$data = array();
		
		$results = $this->model_email_groups->getGroups();
        foreach ($results as $result) {
            $data['groups'][] = array(
                'group_id' => $result['id'],
                'name' => $result['name'],
                'description' => $result['description'],
                'edit' => $this->url->link('email/groups/edit', 'token=' . $this->session->data['token'] . '&group_id=' . $result['id'], 'SSL')
            );
        }

		$data['add'] = $this->url->link('email/groups/add', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		// echo "<pre>";print_r($data['groups']);die;

		$this->response->setOutput($this->load->view('email/groups.tpl', $data));
	}

	public function add() {
		$this->document->setTitle("Add Email Group");
		$this->load->model('email/groups');

		$this->getForm();
	}

	public function edit() {
		$this->document->setTitle("Edit Email Group");
		$this->load->model('email/groups');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_email_groups->editGroup($this->request->get['group_id'], [
				'name' => $this->request->post['group-name'],
				'description' => $this->request->post['group-description']
			]);
			$this->response->redirect($this->url->link('email/groups', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getForm() {   
		$this->load->model('email/groups');

        $data['text_form'] = !isset($this->request->get['group_id']) ? "New Group" : "Edit Group";

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

        if (isset($this->error['question'])) {
            $data['error_question'] = $this->error['question'];
        } else {
            $data['error_question'] = array();
        }
        
        if (isset($this->error['answer'])) {
            $data['error_answer'] = $this->error['answer'];
        } else {
            $data['error_answer'] = array();
        }

		if(isset($this->request->get['group_id'])) {
			$data['group'] = $this->model_email_groups->getGroupById($this->request->get['group_id']);
		}

        if (!isset($this->request->get['group_id'])) {
            $data['action'] = $this->url->link('email/groups/add', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('email/groups/edit', 'token=' . $this->session->data['token'] . '&group_id=' . $this->request->get['group_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('email/groups', 'token=' . $this->session->data['token'], 'SSL');
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('email/group_form.tpl', $data));
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'email/groups')) {
            $this->error['warning'] = "You do not have permission to modify email groups";
		}

		if (empty($this->request->post['group-name'])) {
			$this->error['group_name'] = "Group Name cannot be empty";
		}

		if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = "Warning!";
        }

        return !$this->error;
	}
}