<?php

class Session
{
    public $data = [];

    public function __construct()
    {
        if (!session_id()) {
            ini_set('session.use_only_cookies', '1');
            ini_set('session.use_trans_sid', '0');
            ini_set('session.cookie_httponly', '1');

            session_set_cookie_params(0, '/');
            session_start();
        }

        $this->data = &$_SESSION;
    }

    public function getId()
    {
        return session_id();
    }

    public function start()
    {
        return session_start();
    }

    public function destroy()
    {
        return session_destroy();
    }
}
