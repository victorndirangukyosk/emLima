<?php

class ControllerCommonProfile extends Controller {

    public function index() {
        $this->load->language('common/menu');

        $this->load->model('user/user');

        $this->load->model('tool/image');
        $user_info = NULL;
        if ($this->user->getId() != NULL) {
            $user_info = $this->model_user_user->getUser($this->user->getId());
        }
        
        if ($this->user->getFarmerId() != NULL) {
            $user_info = $this->model_user_farmer->getFarmer($this->user->getFarmerId());
        }

        if ($user_info) {
            $data['firstname'] = isset($user_info['firstname']) ? $user_info['firstname'] : $user_info['first_name'];
            $data['lastname'] = isset($user_info['lastname']) ? $user_info['lastname'] : $user_info['last_name'];
            $data['username'] = $user_info['username'];

            $data['user_group'] = $user_info['user_group'];

            if (is_file(DIR_IMAGE . $user_info['image'])) {
                $data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
            } else {
                $data['image'] = $this->model_tool_image->resize('no_image.png', 45, 45);
            }
        } else {
            $data['username'] = '';
            $data['image'] = '';
        }

        return $this->load->view('common/profile.tpl', $data);
    }

}
