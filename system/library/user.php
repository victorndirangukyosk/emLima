<?php

class User extends SmartObject {

    protected $user_id;
    protected $farmer_id;
    protected $username;
    protected $permission = [];
    protected $db;
    protected $config;
    protected $request;
    protected $session;
    protected $response;

    public function __construct($registry) {
        $this->db = $registry->get('db');
        $this->config = $registry->get('config');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->response = $registry->get('response');

        if (isset($this->session->data['user_id'])) {
            $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE user_id = '" . (int) $this->session->data['user_id'] . "' AND status = '1'");

            if ($user_query->num_rows) {
                $this->user_id = $user_query->row['user_id'];
                $this->username = $user_query->row['username'];
                $this->user_group_id = $user_query->row['user_group_id'];

                $this->db->query('UPDATE ' . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int) $this->session->data['user_id'] . "'");

                $user_group_query = $this->db->query('SELECT permission FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");

                $permissions = unserialize($user_group_query->row['permission']);

                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }
                $user_group_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");
                $this->user_group_name = $user_group_details->row['name'];
                $this->firstname = $user_query->row['firstname'];
                $this->lastname = $user_query->row['lastname'];
            } else {
                $this->logout();
            }
        } elseif (isset($this->session->data['farmer_id'])) {
            $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "farmer WHERE farmer_id = '" . (int) $this->session->data['farmer_id'] . "' AND status = '1'");

            if ($user_query->num_rows) {
                $this->farmer_id = $user_query->row['farmer_id'];
                $this->username = $user_query->row['username'];
                $this->user_group_id = $user_query->row['user_group_id'];

                $this->db->query('UPDATE ' . DB_PREFIX . "farmer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE farmer_id = '" . (int) $this->session->data['farmer_id'] . "'");

                $user_group_query = $this->db->query('SELECT permission FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");

                $permissions = unserialize($user_group_query->row['permission']);

                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }
                $user_group_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");
                $this->user_group_name = $user_group_details->row['name'];
                $this->firstname = $user_query->row['first_name'];
                $this->lastname = $user_query->row['last_name'];
            } else {
                $this->logout();
            }
        } elseif ($this->config->get('config_sec_admin_keyword')) {
            if (isset($this->request->get[$this->config->get('config_sec_admin_keyword')])) {
                return;
            }

            if (isset($this->request->get['path']) and ( 'common/login' == $this->request->get['path']) and ! empty($this->request->post['username']) and ! empty($this->request->post['password'])) {
                return;
            }

            $this->response->redirect(HTTPS_CATALOG);
        }
    }

    public function login($username, $password) {
        $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

        if ($user_query->num_rows) {
            $this->session->data['user_id'] = $user_query->row['user_id'];

            $this->user_id = $user_query->row['user_id'];
            $this->username = $user_query->row['username'];
            $this->user_group_id = $user_query->row['user_group_id'];

            $user_group_query = $this->db->query('SELECT permission FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");

            $permissions = unserialize($user_group_query->row['permission']);

            if (is_array($permissions)) {
                foreach ($permissions as $key => $value) {
                    $this->permission[$key] = $value;
                }
            }

            $user_group_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");
            $this->user_group_name = $user_group_details->row['name'];
            $this->firstname = $user_query->row['firstname'];
            $this->lastname = $user_query->row['lastname'];

            return true;
        } else {
            return false;
        }
    }

    public function farmer($username, $password) {
        $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "farmer WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

        if ($user_query->num_rows) {
            $this->session->data['farmer_id'] = $user_query->row['farmer_id'];

            $this->farmer_id = $user_query->row['farmer_id'];
            $this->username = $user_query->row['username'];
            $this->user_group_id = $user_query->row['user_group_id'];

            $user_group_query = $this->db->query('SELECT permission FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");

            $permissions = unserialize($user_group_query->row['permission']);

            if (is_array($permissions)) {
                foreach ($permissions as $key => $value) {
                    $this->permission[$key] = $value;
                }
            }

            $user_group_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");
            $this->user_group_name = $user_group_details->row['name'];
            $this->firstname = $user_query->row['first_name'];
            $this->lastname = $user_query->row['last_name'];

            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        unset($this->session->data['user_id']);
        unset($this->session->data['farmer_id']);

        $this->user_id = '';
        $this->farmer_id = '';
        $this->username = '';
    }

    public function hasPermission($key, $value) {
        if (isset($this->permission[$key])) {
            return in_array($value, $this->permission[$key]);
        } else {
            return false;
        }
    }

    public function isLogged() {
        return $this->user_id;
    }

    public function isFarmerLogged() {
        return $this->farmer_id;
    }

    public function isVendor() {
        $vendor_group_ids = explode(',', $this->config->get('config_vendor_group_ids'));
        if (in_array($this->user_group_id, $vendor_group_ids)) {
            return true;
        } else {
            return false;
        }
    }

    public function isAccountManager() {
        $account_namager_group_id = $this->config->get('config_account_manager_group_id');
        if ($this->user_group_id == $account_namager_group_id) {
            return true;
        } else {
            return false;
        }
    }


    public function isCustomerExperience() {
        $customer_experience_group_id = $this->config->get('config_customer_experience_group_id');
        if ($this->user_group_id == $customer_experience_group_id) {
            return true;
        } else {
            return false;
        }
    }

    public function isFarmer() {
        $farmer_group_id = $this->config->get('config_farmer_group_id');
        if ($this->user_group_id == $farmer_group_id) {
            return true;
        } else {
            return false;
        }
    }

    public function getId() {
        return $this->user_id;
    }

    public function getFarmerId() {
        return $this->farmer_id;
    }

    public function getUserName() {
        return $this->username;
    }

    public function getGroupId() {
        return $this->user_group_id;
    }

    public function getGroupName() {
        return $this->user_group_name;
    }

    public function getFirstName() {
        return $this->firstname;
    }

    public function getLastName() {
        return $this->lastname;
    }

    public function loginAsVendor($username) {
        $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "'  AND status = '1' ");

        if ($user_query->num_rows) {
            $this->session->data['user_id'] = $user_query->row['user_id'];

            $this->user_id = $user_query->row['user_id'];
            $this->username = $user_query->row['username'];
            $this->user_group_id = $user_query->row['user_group_id'];

            if ($this->isVendor()) {
                $user_group_query = $this->db->query('SELECT permission FROM ' . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");
                $permissions = unserialize($user_group_query->row['permission']);
                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
