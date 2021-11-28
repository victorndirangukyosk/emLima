<?php

class EventAppPplogin extends Event
{
    public function postCustomerLogout(&$data)
    {
        $this->load->controller('module/pp_login/logout', $data);
    }
}
