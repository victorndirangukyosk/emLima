<?php

class ControllerCommonLogout extends Controller {

    public function index() {
        $this->user->logout();

        unset($this->session->data['token']);

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

        $this->response->redirect($this->url->link('common/login', '', 'SSL'));
    }

}
