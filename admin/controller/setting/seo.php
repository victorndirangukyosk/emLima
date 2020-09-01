<?php

class ControllerSettingSeo extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/seo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/seo');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/seo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/seo');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $url_alias_id = $this->model_setting_seo->add($this->request->post);

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
                $this->response->redirect($this->url->link('setting/seo/edit', 'url_alias_id='.$url_alias_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/seo/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/seo', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/seo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/seo');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $id = $this->model_setting_seo->edit($this->request->post);

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
                $this->response->redirect($this->url->link('setting/seo/edit', 'url_alias_id='.$id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/seo/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/seo', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/seo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/seo');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $url_alias_id) {
                $this->model_setting_seo->delete($url_alias_id);
            }

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

            $this->response->redirect($this->url->link('setting/seo', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_query'])) {
            $filter_query = $this->request->get['filter_query'];
        } else {
            $filter_query = null;
        }

        if (isset($this->request->get['filter_keyword'])) {
            $filter_keyword = $this->request->get['filter_keyword'];
        } else {
            $filter_keyword = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'query';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_query'])) {
            $url .= '&filter_query='.urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_keyword'])) {
            $url .= '&filter_keyword='.urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_first_name'])) {
            $url .= '&filter_first_name='.urlencode(html_entity_decode($this->request->get['filter_first_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_last_name'])) {
            $url .= '&filter_last_name='.urlencode(html_entity_decode($this->request->get['filter_last_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
        }

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
            'href' => $this->url->link('setting/seo', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('setting/seo/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('setting/seo/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['rows'] = [];

        $filter_data = [
            'filter_query' => $filter_query,
            'filter_keyword' => $filter_keyword,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        if (!empty($filter_query) || !empty($filter_keyword)) {
            $total = $this->model_setting_seo->getTotalFilter($filter_data);
        } else {
            $total = $this->model_setting_seo->getTotal();
        }

        //echo "<pre>";print_r($filter_data);die;
        $results = $this->model_setting_seo->getAlias($filter_data);

        foreach ($results as $result) {
            $data['rows'][] = [
                'url_alias_id' => $result['url_alias_id'],
                'query' => $result['query'],
                            'keyword' => $result['keyword'],
                'edit' => $this->url->link('setting/seo/edit', 'token='.$this->session->data['token'].'&url_alias_id='.$result['url_alias_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_query'] = $this->language->get('entry_query');
        $data['entry_keyword'] = $this->language->get('entry_keyword');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_username'] = $this->language->get('column_username');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_query'] = $this->language->get('column_query');
        $data['column_keyword'] = $this->language->get('column_keyword');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

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

        $url = '';

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_query'] = $this->url->link('setting/seo', 'token='.$this->session->data['token'].'&sort=query'.$url, 'SSL');
        $data['sort_keyword'] = $this->url->link('setting/seo', 'token='.$this->session->data['token'].'&sort=keyword'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('setting/seo', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['filter_query'] = $filter_query;
        $data['filter_keyword'] = $filter_keyword;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/seo_list.tpl', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['url_alias_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_url_part'] = $this->language->get('text_url_part');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_query_url'] = $this->language->get('entry_query_url');
        $data['entry_keyword'] = $this->language->get('entry_keyword');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

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

        if (isset($this->error['keywords'])) {
            $data['error_keywords'] = $this->error['keywords'];
        } else {
            $data['error_keywords'] = '';
        }

        if (isset($this->error['query'])) {
            $data['error_query'] = $this->error['query'];
        } else {
            $data['error_query'] = '';
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
            'href' => $this->url->link('setting/seo', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['url_alias_id'])) {
            $data['action'] = $this->url->link('setting/seo/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('setting/seo/edit', 'token='.$this->session->data['token'].'&url_alias_id='.$this->request->get['url_alias_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('setting/seo', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['url_alias_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $alias_info = $this->model_setting_seo->get($this->request->get['url_alias_id']);
        }

        if (isset($this->request->post['query'])) {
            $data['query'] = $this->request->post['query'];
        } elseif (!empty($alias_info)) {
            $data['query'] = $alias_info['query'];
        } else {
            $data['query'] = '';
        }

        if (isset($this->request->post['keywords'])) {
            $data['keywords'] = $this->request->post['keywords'];
        } elseif (!empty($alias_info)) {
            $data['keywords'] = $this->model_setting_seo->getKeywords($alias_info['query']);
        } else {
            $data['keywords'] = [];
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/seo_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/seo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['query'])) {
            $this->error['query'] = $this->language->get('error_query');
        }

        $this->load->model('setting/seo');

        foreach ($this->request->post['keywords'] as $language_id => $value) {
            if (!$value) {
                $this->error['keywords'][$language_id] = $this->language->get('error_keywords');
            }
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'setting/seo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['selected'] as $url_alias_id) {
            if ($this->user->getId() == $url_alias_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_query']) || isset($this->request->get['filter_keyword']) || isset($this->request->get['filter_first_name']) || isset($this->request->get['filter_last_name']) || isset($this->request->get['filter_email'])) {
            $this->load->model('setting/seo');

            if (isset($this->request->get['filter_query'])) {
                $filter_query = $this->request->get['filter_query'];
            } else {
                $filter_query = '';
            }

            if (isset($this->request->get['filter_keyword'])) {
                $filter_keyword = $this->request->get['filter_keyword'];
                if (empty($filter_keyword)) {
                    $filter_keyword = '*';
                }
            } else {
                $filter_keyword = '';
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
                'filter_query' => $filter_query,
                'filter_keyword' => $filter_keyword,
                'filter_first_name' => $filter_first_name,
                'filter_last_name' => $filter_last_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => $limit,
            ];

            if (empty($filter_keyword)) {
                $results = $this->model_setting_seo->getAlias($filter_data);
            } else {
                $this->load->model('setting/seo_group');

                $_results = $this->model_setting_seo_group->getUserGroups($filter_data);
            }

            if (!empty($results)) {
                foreach ($results as $result) {
                    $json[] = [
                            'url_alias_id' => $result['url_alias_id'],
                            'username' => $result['username'],
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname'],
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
