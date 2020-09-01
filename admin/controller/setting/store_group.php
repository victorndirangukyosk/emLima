<?php

class ControllerSettingStoreGroup extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/store_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store_group');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/store_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store_group');

        //echo "<pre>";print_r($this->request->post);die;
        /*        Array
        (
            [name] => test group
            [logo] => data/Vendors/g9.jpg
            [seo_url] => group-testing
            [stores] => Array
                (
                    [0] => 9
                    [1] => 8
                    [2] => 21
                    [3] => 10
                    [4] => 22
                )

            [status] => 1
            [button] => save
        )*/
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $id = $this->model_setting_store_group->addStoreGroup($this->request->post);

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store_group/edit', 'id='.$id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store_group/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/store_group', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/store_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store_group');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_setting_store_group->editStoreGroup($this->request->get['id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store_group/edit', 'id='.$this->request->get['id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store_group/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/store_group', 'token='.$this->session->data['token'].'&id='.$this->request->get['id'], 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/store_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store_group');

        $this->load->model('setting/setting');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_setting_store_group->deleteStoreGroup($id);

                //  $this->model_setting_setting->deleteSetting('config', $id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/store_group', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'sg.name';
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

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.$this->request->get['filter_name'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/store_group', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('setting/store_group/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('setting/store_group/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['stores'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $results = $this->model_setting_store_group->getStoreGroup($filter_data);
        $total = $this->model_setting_store_group->getTotalStoreGroup($filter_data);

        foreach ($results as $result) {
            $data['stores'][] = [
                'id' => $result['id'],
                'name' => $result['name'],
                'status' => $result['status'],
                'edit' => $this->url->link('setting/store_group/edit', 'token='.$this->session->data['token'].'&id='.$result['id'], 'SSL'),
            ];
        }

        $data['token'] = $this->session->data['token'];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_vendor'] = $this->language->get('entry_vendor');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_add_timeslot'] = $this->language->get('button_add_timeslot');

        $data['text_vendor'] = $this->language->get('text_vendor');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_search'] = $this->language->get('text_search');
        $data['text_description'] = $this->language->get('text_description');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_url'] = $this->language->get('column_url');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_zipcode'] = $this->language->get('column_zipcode');

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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.$this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.$this->request->get['filter_status'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('setting/store_group', 'token='.$this->session->data['token'].'&sort=sg.name'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('setting/store_group', 'token='.$this->session->data['token'].'&sort=sg.status'.$url, 'SSL');

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('setting/store_group', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;

        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        //echo "<pre>";print_r($data['stores']);die;
        $this->response->setOutput($this->load->view('setting/store_group_list.tpl', $data));
    }

    public function getForm()
    {
        $this->document->addScript('https://maps.google.com/maps/api/js?key='.$this->config->get('config_google_api_key').'&sensor=false&libraries=places');

        $this->document->addScript('ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2');

        $data = $this->language->all();

        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_vendor'] = $this->language->get('text_vendor');
        $data['text_search'] = $this->language->get('text_search');
        $data['text_description'] = $this->language->get('text_description');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['column_timeslot'] = $this->language->get('column_timeslot');

        $data['entry_cityzipcodes'] = $this->language->get('entry_cityzipcodes');

        $data['column_sunday'] = $this->language->get('column_sunday');
        $data['column_monday'] = $this->language->get('column_monday');
        $data['column_tuesday'] = $this->language->get('column_tuesday');
        $data['column_wesnesday'] = $this->language->get('column_wesnesday');
        $data['column_thirsday'] = $this->language->get('column_thirsday');
        $data['column_friday'] = $this->language->get('column_friday');
        $data['column_saturday'] = $this->language->get('column_saturday');

        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_secure'] = $this->language->get('entry_secure');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_geocode'] = $this->language->get('entry_geocode');
        $data['entry_add_timeslot'] = $this->language->get('entry_add_timeslot');

        $data['help_url'] = $this->language->get('help_url');
        $data['help_secure'] = $this->language->get('help_secure');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_server'] = $this->language->get('tab_server');
        $data['tab_contact'] = $this->language->get('tab_contact');
        $data['tab_delivery'] = $this->language->get('tab_delivery');
        $data['tab_location'] = $this->language->get('tab_location');
        $data['tab_pickup_timeslot'] = $this->language->get('tab_pickup_timeslot');
        $data['tab_delivery_timeslot'] = $this->language->get('tab_delivery_timeslot');

        $data['entry_commision'] = $this->language->get('entry_commision');
        $data['entry_min_order_amount'] = $this->language->get('entry_min_order_amount');
        $data['entry_min_order_cod'] = $this->language->get('entry_min_order_cod');

        $data['entry_delivery_by_store_owner'] = $this->language->get('entry_delivery_by_store_owner');

        $data['entry_seo_url'] = $this->language->get('entry_seo_url');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/store_group', 'token='.$this->session->data['token'], 'SSL'),
        ];

        if (!isset($this->request->get['id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/store_group/add', 'token='.$this->session->data['token'], 'SSL'),
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/store_group/edit', 'token='.$this->session->data['token'].'&id='.$this->request->get['id'], 'SSL'),
            ];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (!isset($this->request->get['id'])) {
            $data['action'] = $this->url->link('setting/store_group/add', 'token='.$this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('setting/store_group/edit', 'token='.$this->session->data['token'].'&id='.$this->request->get['id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('setting/store_group', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->get['id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $this->load->model('setting/store_group');

            $store_info = $this->model_setting_store_group->getStoreGroupData($this->request->get['id']);
        }
        //echo "<pre>";print_r($store_info);die;
        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($store_info['name'])) {
            $data['name'] = $store_info['name'];
        } else {
            $data['name'] = '';
        }

        //echo "<pre>";print_r($store_info['stores']);die;
        if (isset($this->request->post['zipcode'])) {
            $data['stores'] = $this->request->post['stores'];
        } elseif (isset($store_info)) {
            $data['stores'] = $this->getStoreNames(explode(',', $store_info['stores']));
        } else {
            $data['stores'] = [];
        }

        //echo "<pre>";print_r($data['stores']);die;
        if (isset($this->request->post['logo'])) {
            $data['logo'] = $this->request->post['logo'];
        } elseif (isset($store_info['logo'])) {
            $data['logo'] = $store_info['logo'];
        } else {
            $data['logo'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['logo']) && is_file(DIR_IMAGE.$this->request->post['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['logo'], 100, 100);
        } elseif (isset($store_info['logo']) && is_file(DIR_IMAGE.$store_info['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($store_info['logo'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($store_info['status'])) {
            $data['status'] = $store_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (isset($store_info['seo_url'])) {
            $data['seo_url'] = $store_info['seo_url'];
        } else {
            $data['seo_url'] = '';
        }

        if (isset($this->request->post['store_zipcode'])) {
            $data['store_zipcode'] = $this->request->post['store_zipcode'];
        } elseif (isset($store_info['store_zipcode'])) {
            $data['store_zipcode'] = $store_info['store_zipcode'];
        } else {
            $data['store_zipcode'] = '';
        }

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getTopCategories();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/store_group_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/store_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        $this->load->model('catalog/url_alias');

        if ($this->request->post['seo_url']) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['seo_url']);

            if (empty($this->request->post['seo_url'])) {
                $this->error['seo_url'] = sprintf($this->language->get('error_seo_url_required'));
            }

            if ($url_alias_info && isset($this->request->get['id']) && $url_alias_info['query'] != 'store_group_id='.$this->request->get['id']) {
                $this->error['seo_url'] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['id'])) {
                $this->error['seo_url'] = sprintf($this->language->get('error_seo_url'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'setting/store_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/order');

        /*foreach ($this->request->post['selected'] as $id) {
            if (!$id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $store_total = $this->model_sale_order->getTotalOrdersByStoreId($id);

            //echo "<pre>";print_r($store_total);die;
            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }
        }*/

        return !$this->error;
    }

    public function template()
    {
        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        if (is_file(DIR_IMAGE.'templates/'.basename($this->request->get['template']).'.png')) {
            $this->response->setOutput($server.'image/templates/'.basename($this->request->get['template']).'.png');
        } else {
            $this->response->setOutput($server.'image/no_image.jpg');
        }
    }

    public function country()
    {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete()
    {
        $q = $this->request->get['filter_name'];

        $this->load->model('sale/order');

        $json = $this->model_sale_order->getStoreGroupDetails($q);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function export_excel()
    {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_store_excel($data);
    }

    public function getZipcodesAutocomplete()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }
        if (isset($this->request->get['city_id'])) {
            $city_id = $this->request->get['city_id'];
        } else {
            $city_id = '';
        }

        $this->load->model('localisation/city');

        $json = $this->model_localisation_city->getZipcodeFilteredByCity($filter_name, $city_id);

        echo json_encode($json);
    }

    public function getStoreNames($store_ids)
    {
        $this->load->model('sale/order');

        $temp = [];

        foreach ($store_ids as $id) {
            $name = $this->model_sale_order->getStoreNameById($id);
            array_push($temp, $name);
        }

        return $temp;
    }
}
