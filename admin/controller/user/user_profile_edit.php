<?php

class ControllerUserUserProfileEdit extends Controller
{
    private $error = [];

     

   

    public function edit()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('user/user_profile_edit/edit', 'user_id='.$this->request->get['user_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

           

            $this->response->redirect($this->url->link('user/user_profile_edit/edit', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    
     
    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['token'] = $this->session->data['token'];

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

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
            'href' => $this->url->link('user/user_profile_edit/edit', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['user_id'])) {
            $data['action'] = "";
        } else {
            $data['action'] = $this->url->link('user/user_profile_edit/edit', 'token='.$this->session->data['token'].'&user_id='.$this->request->get['user_id'].$url, 'SSL');
        }


        if (isset($this->request->get['user_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $user_info = $this->model_user_user->getUser($this->request->get['user_id']);
        }

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } elseif (!empty($user_info)) {
            $data['username'] = $user_info['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['user_group_id'])) {
            $data['user_group_id'] = $this->request->post['user_group_id'];
        } elseif (!empty($user_info)) {
            $data['user_group_id'] = $user_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('user/user_group');

        $data['user_groups'] = $this->model_user_user_group->getUserGroups();

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($user_info)) {
            $data['firstname'] = $user_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($user_info)) {
            $data['lastname'] = $user_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($user_info)) {
            $data['email'] = $user_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($user_info)) {
            $data['image'] = $user_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE.$this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($user_info) && $user_info['image'] && is_file(DIR_IMAGE.$user_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($user_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($user_info)) {
            $data['status'] = $user_info['status'];
        } else {
            $data['status'] = 0;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_form_edit.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'user/user_profile_edit')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

        if (!isset($this->request->get['user_id'])) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        // if($this->error) {
        //     $this->error['warning'] = $this->language->get('error_warning');
        // }

        return !$this->error;
    }

   
    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_user_name']) || isset($this->request->get['filter_user_group']) || isset($this->request->get['filter_first_name']) || isset($this->request->get['filter_last_name']) || isset($this->request->get['filter_email'])) {
            $this->load->model('user/user');

            if (isset($this->request->get['filter_user_name'])) {
                $filter_user_name = $this->request->get['filter_user_name'];
            } else {
                $filter_user_name = '';
            }
            
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_user_group'])) {
                $filter_user_group = $this->request->get['filter_user_group'];
                if (empty($filter_user_group)) {
                    $filter_user_group = '*';
                }
            } else {
                $filter_user_group = '';
            }

            if (isset($this->request->get['filter_first_name'])) {
                $filter_first_name = $this->request->get['filter_first_name'];
            } else {
                $filter_first_name = '';
            }

            if (isset($this->request->get['filter_last_name'])) {
                $filter_last_name = $this->request->get['filter_last_name'];
            } else {
                $filter_last_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_user_name' => $filter_user_name,
                'filter_user_group' => $filter_user_group,
                'filter_first_name' => $filter_first_name,
                'filter_last_name' => $filter_last_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => $limit,
            ];

            if (empty($filter_user_group)) {
                $results = $this->model_user_user->getUsers($filter_data);
            } else {
                $this->load->model('user/user_group');

                $_results = $this->model_user_user_group->getUserGroups($filter_data);
            }

            if (!empty($results)) {
                foreach ($results as $result) {
                    $json[] = [
                        'user_id' => $result['user_id'],
                        'username' => $result['username'],
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname'],
                        'name' => $result['name'],
                        'email' => $result['email'],
                    ];
                }
            } elseif (!empty($_results)) {
                foreach ($_results as $result) {
                    $json[] = [
                        'user_group_id' => $result['user_group_id'],
                        'user_group' => $result['name'],
                    ];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
