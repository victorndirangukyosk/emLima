<?php
 

class ControllerSettingJobPosition extends Controller
{
    private $error = [];

    public function index()
    {

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/jobposition');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/jobposition');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $job_id = $this->model_setting_jobposition->addJobPosition($this->request->post);

            $this->load->model('setting/setting');
 
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/jobposition', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/jobposition');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/jobposition');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_setting_jobposition->editJobPosition($this->request->get['job_id'], $this->request->post);

            $this->load->model('setting/setting');

           // !empty($this->request->post['date_added']) ?: $this->request->post['date_added'] = $this->request->post['config_name'];
 
            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/jobposition/edit', 'job_id='.$this->request->get['job_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/jobposition/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/jobposition/edit', 'job_id='.$this->request->get['job_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/jobposition/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/jobposition', 'token='.$this->session->data['token'].'&job_id='.$this->request->get['job_id'], 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/jobposition');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/jobposition');

        $this->load->model('setting/setting');

        if (isset($this->request->post['selected'])  ) {
            foreach ($this->request->post['selected'] as $job_id) {
                $this->model_setting_jobposition->deleteJobPosition($job_id);
 
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/jobposition', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {

        $this->load->language('setting/jobposition');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/jobposition');

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/jobposition', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['add'] = $this->url->link('setting/jobposition/add', 'token='.$this->session->data['token'], 'SSL');
        $data['delete'] = $this->url->link('setting/jobposition/delete', 'token='.$this->session->data['token'], 'SSL');

        $data['jobpositions'] = [];

        $jobposition_total = $this->model_setting_jobposition->getTotalJobPositions();

        $results = $this->model_setting_jobposition->getJobPositions();

        foreach ($results as $result) {
            $data['jobpositions'][] = [
                'job_id' => $result['job_id'],
                'job_category' => $result['job_category'],
                'job_type' => $result['job_type'],
                'job_location' => $result['job_location'],
                'skills' => $result['skills'],
                'experience' => $result['experience'],
                'roles_responsibilities' => $result['roles_responsibilities'],
                'otherinfo_1' => $result['otherinfo_1'],
                'otherinfo_2' => $result['otherinfo_2'],
                'date_added' => $result['date_added'],
                'status' => $result['status'],
                'sort_order' => $result['sort_order'],

                'edit' => $this->url->link('setting/jobposition/edit', 'token='.$this->session->data['token'].'&job_id='.$result['job_id'], 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_disable'] = $this->language->get('text_disable');

        
        $data['column_job_category'] = $this->language->get('column_job_category');
        $data['column_job_type'] = $this->language->get('column_job_type');
        $data['column_job_location'] = $this->language->get('column_job_location');
        $data['column_skills'] = $this->language->get('column_skills');
        $data['column_experience'] = $this->language->get('column_experience');
        $data['column_roles_responsibilities'] = $this->language->get('column_roles_responsibilities');
        $data['column_otherinfo_1'] = $this->language->get('column_otherinfo_1');
        $data['column_otherinfo_2'] = $this->language->get('column_otherinfo_2');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_message'] = $this->language->get('column_message');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/jobposition_list.tpl', $data));
    }

    public function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_jobpositions'] = $this->language->get('text_jobpositions');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        

        $data['entry_name'] = $this->language->get('column_name');
        $data['entry_summary'] = $this->language->get('column_summary');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_disable'] = $this->language->get('text_disable');

        $data['column_job_category'] = $this->language->get('column_job_category');
        $data['column_job_type'] = $this->language->get('column_job_type');
        $data['column_job_location'] = $this->language->get('column_job_location');
        $data['column_skills'] = $this->language->get('column_skills');
        $data['column_experience'] = $this->language->get('column_experience');
        $data['column_otherinfo_2'] = $this->language->get('column_otherinfo_2');
        $data['column_customers_requested'] = $this->language->get('column_customers_requested');
        $data['column_no_of_customers_onboarded'] = $this->language->get('column_no_of_customers_onboarded');
        $data['column_roles_responsibilities'] = $this->language->get('column_roles_responsibilities');
        $data['column_otherinfo_1'] = $this->language->get('column_otherinfo_1');
 
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['job_category'])) {
            $data['error_job_category'] = $this->error['job_category'];
        } else {
            $data['error_job_category'] = '';
        }

        if (isset($this->error['job_type'])) {
            $data['error_job_type'] = $this->error['job_type'];
        } else {
            $data['error_job_type'] = '';
        }

        if (isset($this->error['roles_responsibilities'])) {
            $data['error_roles_responsibilities'] = $this->error['roles_responsibilities'];
        } else {
            $data['error_roles_responsibilities'] = '';
        }

        if (isset($this->error['job_location'])) {
            $data['error_job_location'] = $this->error['job_location'];
        } else {
            $data['error_job_location'] = '';
        }

        if (isset($this->error['skills'])) {
            $data['error_skills'] = $this->error['skills'];
        } else {
            $data['error_skills'] = '';
        }



        if (isset($this->error['experience'])) {
            $data['error_experience'] = $this->error['experience'];
        } else {
            $data['error_experience'] = '';
        }



        if (isset($this->error['otherinfo_2'])) {
            $data['error_otherinfo_2'] = $this->error['otherinfo_2'];
        } else {
            $data['error_otherinfo_2'] = '';
        }

        if (isset($this->error['otherinfo_1'])) {
            $data['error_otherinfo_1'] = $this->error['otherinfo_1'];
        } else {
            $data['error_otherinfo_1'] = '';
        }



        if (isset($this->error['customers_requested'])) {
            $data['error_customers_requested'] = $this->error['customers_requested'];
        } else {
            $data['error_customers_requested'] = '';
        }


        if (isset($this->error['no_of_customers_onboarded'])) {
            $data['error_no_of_customers_onboarded'] = $this->error['no_of_customers_onboarded'];
        } else {
            $data['error_no_of_customers_onboarded'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/jobposition', 'token='.$this->session->data['token'], 'SSL'),
        ];

        if (!isset($this->request->get['job_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/jobposition/add', 'token='.$this->session->data['token'], 'SSL'),
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/jobposition/edit', 'token='.$this->session->data['token'].'&job_id='.$this->request->get['job_id'], 'SSL'),
            ];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (!isset($this->request->get['job_id'])) {
            $data['action'] = $this->url->link('setting/jobposition/add', 'token='.$this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('setting/jobposition/edit', 'token='.$this->session->data['token'].'&job_id='.$this->request->get['job_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('setting/jobposition', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->get['job_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $this->load->model('setting/jobposition');
             
            $jobposition_info = $this->model_setting_jobposition->getJobPosition($this->request->get['job_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['job_type'])) {
            $data['job_type'] = $this->request->post['job_type'];
        } elseif (isset($jobposition_info['job_type'])) {
            $data['job_type'] = $jobposition_info['job_type'];
        } else {
            $data['job_type'] = '';
        }

        if (isset($this->request->post['job_category'])) {
            $data['job_category'] = $this->request->post['job_category'];
        } elseif (isset($jobposition_info['job_category'])) {
            $data['job_category'] = $jobposition_info['job_category'];
        } else {
            $data['job_category'] = '';
        }


        if (isset($this->request->post['job_location'])) {
            $data['job_location'] = $this->request->post['job_location'];
        } elseif (isset($jobposition_info['job_location'])) {
            $data['job_location'] = $jobposition_info['job_location'];
        } else {
            $data['job_location'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (isset($jobposition_info)) {
            $data['sort_order'] = $jobposition_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($jobposition_info)) {
            $data['status'] = $jobposition_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['skills'])) {
            $data['skills'] = $this->request->post['skills'];
        } elseif (isset($jobposition_info)) {
            $data['skills'] = $jobposition_info['skills'];
        } else {
            $data['skills'] = '';
        }

        if (isset($this->request->post['experience'])) {
            $data['experience'] = $this->request->post['experience'];
        } elseif (isset($jobposition_info)) {
            $data['experience'] = $jobposition_info['experience'];
        } else {
            $data['experience'] = '';
        }
        if (isset($this->request->post['roles_responsibilities'])) {
            $data['roles_responsibilities'] = $this->request->post['roles_responsibilities'];
        } elseif (isset($jobposition_info)) {
            $data['roles_responsibilities'] = $jobposition_info['roles_responsibilities'];
        } else {
            $data['roles_responsibilities'] = '';
        }

        if (isset($this->request->post['otherinfo_1'])) {
            $data['otherinfo_1'] = $this->request->post['otherinfo_1'];
        } elseif (isset($jobposition_info)) {
            $data['otherinfo_1'] = $jobposition_info['otherinfo_1'];
        } else {
            $data['otherinfo_1'] = '';
        }

        if (isset($this->request->post['otherinfo_2'])) {
            $data['otherinfo_2'] = $this->request->post['otherinfo_2'];
        } elseif (isset($jobposition_info)) {
            $data['otherinfo_2'] = $jobposition_info['otherinfo_2'];
        } else {
            $data['otherinfo_2'] = '';
        }

       

        

        $this->load->model('tool/image'); 

        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/jobposition_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/jobposition')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['job_category'] ) {
            $this->error['job_category'] = $this->language->get('error_job_category');
        }

        if (!$this->request->post['job_type']) {
            $this->error['job_type'] = $this->language->get('error_job_type');
        }

        if (!$this->request->post['skills']) {
            $this->error['skills'] = $this->language->get('error_skills');
        }

        if (!$this->request->post['experience']) {
            $this->error['experience'] = $this->language->get('error_experience');
        }

        if ((utf8_strlen($this->request->post['roles_responsibilities']) < 10) || (utf8_strlen($this->request->post['summary']) > 500)) {
            $this->error['roles_responsibilities'] = $this->language->get('error_roles_responsibilities');
        }

        if (!$this->request->post['job_location']) {
            $this->error['job_location'] = $this->language->get('error_job_location');
        } 

        

        // if (!$this->request->post['otherinfo_2']) {
        //     $this->error['otherinfo_2'] = $this->language->get('error_otherinfo_2');
        // }  
        
        

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

   

    
   
}
