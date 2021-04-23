<?php

class ControllerCommonLogout extends Controller {

    public function index() {

        $log = new Log('error.log');
        $this->load->model('user/user_activity');

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
        ];
        $log->write('user logout');

        $this->model_user_user_activity->addActivity('logout', $activity_data);

        $log->write('user logout');
        $this->user->logout();

        unset($this->session->data['token']);

        $this->response->redirect($this->url->link('common/login', '', 'SSL'));
    }

    public function farmer() {

        $log = new Log('error.log');
        $this->load->model('user/farmer_activity');

        $activity_data = [
            'farmer_id' => $this->user->getFarmerId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
        ];
        $log->write('farmer logout');

        $this->model_user_farmer_activity->addActivity('logout', $activity_data);

        $log->write('farmer logout');
        $this->user->logout();

        unset($this->session->data['token']);

        $this->response->redirect($this->url->link('common/farmer', '', 'SSL'));
    }

}
