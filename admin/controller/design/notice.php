<?php

class ControllerDesignNotice extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('design/notice');

        $this->document->setTitle($this->language->get('heading_notice'));

        $this->load->model('design/notice');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('design/notice');

        $this->document->setTitle($this->language->get('heading_notice'));

        $this->load->model('design/notice');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $notice_id = $this->model_design_notice->addNotice($this->request->post);

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
                $this->response->redirect($this->url->link('design/notice/edit', 'notice_id='.$notice_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/notice/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL'));

            $this->response->redirect($this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('design/notice');

        $this->document->setTitle($this->language->get('heading_notice'));

        $this->load->model('design/notice');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_design_notice->editNotice($this->request->get['notice_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('design/notice/edit', 'notice_id='.$this->request->get['notice_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('design/notice/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('design/notice');

        $this->document->setTitle($this->language->get('heading_notice'));

        $this->load->model('design/notice');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $notice_id) {
                $this->model_design_notice->deleteNotice($notice_id);
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

            $this->response->redirect($this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    public function getHeaderPlace($location)
    {
        $p = '';

        $userSearch = explode(',', $location);

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                //echo "<pre>";print_r("er");die;
                try {
                    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.urlencode($location).'&sensor=false&key='.$this->config->get('config_google_server_api_key');

                    //echo "<pre>";print_r($url);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $headers = [
                                 'Cache-Control: no-cache', ];
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                    //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $output = json_decode($response);

                    //echo "<pre>";print_r($output);die;
                    if (isset($output)) {
                        foreach ($output->results[0]->address_components as $addres) {
                            if (isset($addres->types)) {
                                if (in_array('sublocality_level_1', $addres->types)) {
                                    //echo "<pre>";print_r($addres);die;
                                    $p = $addres->long_name;
                                    break;
                                }
                            }
                        }
                        if (isset($output->results[0]->formatted_address)) {
                            $p = $output->results[0]->formatted_address;
                        }
                    }
                } catch (Exception $e) {
                }
            }
        }

        return $p;
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'notice';
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
            'text' => $this->language->get('heading_notice'),
            'href' => $this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('design/notice/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('design/notice/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['notices'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $notice_total = $this->model_design_notice->getTotalNotices();

        $results = $this->model_design_notice->getNotices($filter_data);

        foreach ($results as $result) {
            $place = '';

            $loc = $result['latitude'].','.$result['longitude'];

            $place = $this->getHeaderPlace($loc);

            $loc = strlen($place) > 24 ? substr($place, 0, 24).'...' : $place;

            $data['notices'][] = [
                'notice_id' => $result['notice_id'],
                'notice' => $result['notice'],
                'zipcode' => $loc,
                'status' => $result['status'],
                'edit' => $this->url->link('design/notice/edit', 'token='.$this->session->data['token'].'&notice_id='.$result['notice_id'].$url, 'SSL'),
            ];
        }

        $data['heading_notice'] = $this->language->get('heading_notice');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['column_notice'] = $this->language->get('column_notice');
        $data['column_zipcode'] = $this->language->get('column_zipcode');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_action'] = $this->language->get('column_action');

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

        $url = '';

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_notice'] = $this->url->link('design/notice', 'token='.$this->session->data['token'].'&sort=notice'.$url, 'SSL');
        $data['sort_zipcode'] = $this->url->link('design/notice', 'token='.$this->session->data['token'].'&sort=zipcode'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $notice_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('design/notice', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($notice_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($notice_total - $this->config->get('config_limit_admin'))) ? $notice_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $notice_total, ceil($notice_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/notice_list.tpl', $data));
    }

    protected function getForm()
    {
        $this->document->addScript('https://maps.google.com/maps/api/js?key='.$this->config->get('config_google_api_key').'&sensor=false&libraries=places');
        $this->document->addScript('ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2');

        $data['heading_notice'] = $this->language->get('heading_notice');

        $data['text_form'] = !isset($this->request->get['notice_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_default'] = $this->language->get('text_default');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_notice'] = $this->language->get('entry_notice');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_force'] = $this->language->get('entry_force');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $this->load->model('tool/image');

        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_notice_add'] = $this->language->get('button_notice_add');
        $data['button_remove'] = $this->language->get('button_remove');

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

        if (isset($this->error['notice'])) {
            $data['error_notice'] = $this->error['notice'];
        } else {
            $data['error_notice'] = '';
        }

        if (isset($this->error['zipcode'])) {
            $data['error_zipcode'] = $this->error['zipcode'];
        } else {
            $data['error_zipcode'] = [];
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
            'text' => $this->language->get('heading_notice'),
            'href' => $this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['notice_id'])) {
            $data['action'] = $this->url->link('design/notice/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('design/notice/edit', 'token='.$this->session->data['token'].'&notice_id='.$this->request->get['notice_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('design/notice', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['notice_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $notice_info = $this->model_design_notice->getNotice($this->request->get['notice_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['notice'])) {
            $data['notice'] = $this->request->post['notice'];
        } elseif (!empty($notice_info)) {
            $data['notice'] = $notice_info['notice'];
        } else {
            $data['notice'] = '';
        }

        if (isset($this->request->post['zipcode'])) {
            $data['zipcode'] = $this->request->post['zipcode'];
        } elseif (!empty($notice_info)) {
            $data['zipcode'] = $notice_info['zipcode'];
        } else {
            $data['zipcode'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($notice_info)) {
            $data['status'] = $notice_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['radius'])) {
            $data['radius'] = $this->request->post['radius'];
        } elseif (!empty($notice_info)) {
            $data['radius'] = $notice_info['radius'];
        } else {
            $data['radius'] = '';
        }

        if (isset($this->request->post['latitude'])) {
            $data['latitude'] = $this->request->post['latitude'];
        } elseif (!empty($notice_info)) {
            $data['latitude'] = $notice_info['latitude'];
        } else {
            $data['latitude'] = '';
        }

        if (isset($this->request->post['longitude'])) {
            $data['longitude'] = $this->request->post['longitude'];
        } elseif (!empty($notice_info)) {
            $data['longitude'] = $notice_info['longitude'];
        } else {
            $data['longitude'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image_thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($notice_info) && is_file(DIR_IMAGE.$notice_info['image'])) {
            $data['image_thumb'] = $this->model_tool_image->resize($notice_info['image'], 100, 100);
        } else {
            $data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($notice_info)) {
            $data['image'] = $notice_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['force'])) {
            $data['force'] = $this->request->post['force'];
        } elseif (!empty($notice_info)) {
            $data['force'] = $notice_info['notice_force'];
        } else {
            $data['force'] = '';
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/notice_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'design/notice')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['notice']) < 3) || (utf8_strlen($this->request->post['notice']) > 200)) {
            $this->error['notice'] = $this->language->get('error_notice');
        }

        if ((utf8_strlen($this->request->post['zipcode']) < 3) || (utf8_strlen($this->request->post['zipcode']) > 64)) {
            $this->error['zipcode'] = $this->language->get('error_zipcode');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'design/notice')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
