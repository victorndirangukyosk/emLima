<?php

class ControllerSettingStore extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');
        $this->load->model('tool/export_import');
        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $store_id = $this->model_setting_store->addStore($this->request->post);

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if (!$this->user->isVendor()) {
                    if ($this->model_tool_export_import->uploadCityZipcode($file, $store_id)) {
                        $this->session->data['success'] = $this->language->get('text_success');
                    /*$this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));*/
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                    }
                }
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store/edit', 'store_id='.$store_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/store', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');
        $this->load->model('tool/export_import');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            //echo "<pre>";print_r($this->request->post);die;
            $this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if (!$this->user->isVendor()) {
                    if ($this->model_tool_export_import->uploadCityZipcode($file, $this->request->get['store_id'])) {
                        $this->session->data['success'] = $this->language->get('text_success');
                    /*$this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));*/
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                    }
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store/edit', 'store_id='.$this->request->get['store_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/store', 'token='.$this->session->data['token'].'&store_id='.$this->request->get['store_id'], 'SSL'));
        }

        $this->getForm();
    }

    public function duplicate()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        $this->load->model('catalog/url_alias');

        //echo "<pre>";print_r($this->request->post);die;
        $oldStoreData = $this->model_setting_store->getStore($this->request->get['store_id']);

        $storeCategories = $this->model_setting_store->getStoreCategoryIds($this->request->get['store_id']);

        $pickupTimeslots = $this->model_setting_store->getPickupTimeslotsForDuplicate($this->request->get['store_id']);

        $deliveryTimeslots = $this->model_setting_store->getDeliveryTimeslotsForDuplicate($this->request->get['store_id']);

        $productToStoreForDuplicate = $this->model_setting_store->getProductToStoreForDuplicate($this->request->get['store_id']);

        $openHours = $this->model_setting_store->getOpenHoursOfStore($this->request->get['store_id']);

        if (count($oldStoreData) > 0) {
            $oldStoreData['name'] = $oldStoreData['name'].' (duplicate) ';
            $oldStoreData['seo_url'] = $oldStoreData['seo_url'].'-duplicate';
            $oldStoreData['store_type_ids'] = explode(',', $oldStoreData['store_type_ids']);
            $oldStoreData['city_zipcodes'] = explode(',', $oldStoreData['zipcode']);
            $oldStoreData['storeCategories'] = $storeCategories;
            $oldStoreData['pickup_timeslots'] = $pickupTimeslots;
            $oldStoreData['delivery_timeslots'] = $deliveryTimeslots;
            $oldStoreData['productToStore'] = $productToStoreForDuplicate;
            $oldStoreData['openHours'] = $openHours;
        }

        //echo "<pre>";print_r($oldStoreData);die;
        $this->model_setting_store->addDuplicateStore($oldStoreData);

        $this->session->data['success'] = $this->language->get('text_duplicated_success');

        $this->response->redirect($this->url->link('setting/store', 'token='.$this->session->data['token'].'&store_id='.$this->request->get['store_id'], 'SSL'));

        $this->getList();
    }

    public function delete()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        $this->load->model('setting/setting');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $store_id) {
                $this->model_setting_store->deleteStore($store_id);

                //  $this->model_setting_setting->deleteSetting('config', $store_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/store', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
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

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.$this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.$this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.$this->request->get['filter_vendor'];
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
            'href' => $this->url->link('setting/store', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('setting/store/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('setting/store/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['stores'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_city' => $filter_city,
            'filter_date_added' => $filter_date_added,
            'filter_vendor' => $filter_vendor,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $results = $this->model_setting_store->getStores($filter_data);
        $total = $this->model_setting_store->getTotalStores($filter_data);

        foreach ($results as $result) {
            $data['stores'][] = [
                'store_id' => $result['store_id'],
                'name' => $result['name'],
                'vendor_name' => $this->model_setting_store->getVendorDetails($result['vendor_id']),
                'vendor_link' => $this->url->link('vendor/vendor/info', 'token='.$this->session->data['token'].'&vendor_id='.$result['vendor_id'], 'SSL'),
                'city' => $result['city'],
                'address' => $result['address'],
                'zipcode' => $result['zipcode'],
                'serviceable_radius' => $result['serviceable_radius'],
                'status' => $result['status'],
                'edit' => $this->url->link('setting/store/edit', 'token='.$this->session->data['token'].'&store_id='.$result['store_id'], 'SSL'),
                'duplicate' => $this->url->link('setting/store/duplicate', 'token='.$this->session->data['token'].'&store_id='.$result['store_id'], 'SSL'),
            ];
        }
        //echo "<pre>";print_r($data['stores']);die;

        $data['token'] = $this->session->data['token'];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_vendor_name'] = $this->language->get('column_vendor_name');
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
        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['entry_store_type'] = $this->language->get('entry_store_type');

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

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.$this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.$this->request->get['filter_vendor'];
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

        $data['sort_name'] = $this->url->link('setting/store', 'token='.$this->session->data['token'].'&sort=s.name'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('setting/store', 'token='.$this->session->data['token'].'&sort=s.status'.$url, 'SSL');

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('setting/store', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_name'] = $filter_name;
        $data['filter_vendor'] = $filter_vendor;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/store_list.tpl', $data));
    }

    public function getForm()
    {
        $this->document->addScript('https://maps.google.com/maps/api/js?key='.$this->config->get('config_google_api_key').'&sensor=false&libraries=places');

        $this->document->addScript('ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2');

        $data = $this->language->all();

        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['store_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_vendor'] = $this->language->get('text_vendor');
        $data['text_search'] = $this->language->get('text_search');
        $data['text_description'] = $this->language->get('text_description');
        $data['text_import_zipcode'] = $this->language->get('text_import_zipcode');
        $data['text_export_zipcode'] = $this->language->get('text_export_zipcode');
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
        $data['entry_category'] = $this->language->get('entry_category');

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
        $data['tab_open_hours'] = $this->language->get('tab_open_hours');

        $data['entry_commision'] = $this->language->get('entry_commision');
        $data['entry_fixed_commision'] = $this->language->get('entry_fixed_commision');
        $data['entry_tax_number'] = $this->language->get('entry_tax_number');
        $data['entry_min_order_amount'] = $this->language->get('entry_min_order_amount');
        $data['entry_min_order_cod'] = $this->language->get('entry_min_order_cod');

        $data['entry_delivery_by_store_owner'] = $this->language->get('entry_delivery_by_store_owner');

        $data['entry_pickup_delivery'] = $this->language->get('entry_pickup_delivery');

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

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['vendor_id'])) {
            $data['error_vendor_id'] = $this->error['vendor_id'];
        } else {
            $data['error_vendor_id'] = '';
        }

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = '';
        }

        if (isset($this->error['delivery_time_diff'])) {
            $data['error_delivery_time_diff'] = $this->error['delivery_time_diff'];
        } else {
            $data['error_delivery_time_diff'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['about_us'])) {
            $data['error_about_us'] = $this->error['about_us'];
        } else {
            $data['error_about_us'] = '';
        }

        if (isset($this->error['commision'])) {
            $data['error_commision'] = $this->error['commision'];
        } else {
            $data['error_commision'] = '';
        }

        if (isset($this->error['category_commission'])) {
            $data['error_category_commission'] = $this->error['category_commission'];
        } else {
            $data['error_category_commission'] = '';
        }

        if (isset($this->error['tax'])) {
            $data['error_tax'] = $this->error['tax'];
        } else {
            $data['error_tax'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/store', 'token='.$this->session->data['token'], 'SSL'),
        ];

        if (!isset($this->request->get['store_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/store/add', 'token='.$this->session->data['token'], 'SSL'),
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/store/edit', 'token='.$this->session->data['token'].'&store_id='.$this->request->get['store_id'], 'SSL'),
            ];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (!isset($this->request->get['store_id'])) {
            $data['action'] = $this->url->link('setting/store/add', 'token='.$this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('setting/store/edit', 'token='.$this->session->data['token'].'&store_id='.$this->request->get['store_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('setting/store', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->get['store_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($this->request->get['store_id']);
        }
        //echo "<pre>";print_r($store_info);die;
        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['delivery_by_owner'])) {
            $data['delivery_by_owner'] = $this->request->post['delivery_by_owner'];
        } elseif (isset($store_info['delivery_by_owner'])) {
            $data['delivery_by_owner'] = $store_info['delivery_by_owner'];
        } else {
            $data['delivery_by_owner'] = '';
        }

        if (isset($this->request->post['pickup_delivery'])) {
            $data['pickup_delivery'] = $this->request->post['pickup_delivery'];
        } elseif (isset($store_info['store_pickup_timeslots'])) {
            $data['pickup_delivery'] = $store_info['store_pickup_timeslots'];
        } else {
            $data['pickup_delivery'] = '';
        }

        if (isset($this->request->post['commission_type'])) {
            $data['commission_type'] = $this->request->post['commission_type'];
        } elseif (isset($store_info['commission_type'])) {
            $data['commission_type'] = $store_info['commission_type'];
        } else {
            $data['commission_type'] = 'store';
        }

        /* START */

        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        $this->load->model('tool/image');

        $data['pickupEnabled'] = false;
        $data['storeDeliveryEnabled'] = false;
        $data['storeDeliveryExtensionEnabled'] = false;
        $data['storePickupExtensionEnabled'] = false;
        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ('pickup' == $result['code'] && $this->config->get($result['code'].'_status') && $data['pickup_delivery']) {
                $data['pickupEnabled'] = true;
            }

            if ('pickup' == $result['code'] && $this->config->get($result['code'].'_status')) {
                $data['storePickupExtensionEnabled'] = true;
            }

            if ('store_delivery' == $result['code'] && $this->config->get($result['code'].'_status') && $data['delivery_by_owner']) {
                $data['storeDeliveryEnabled'] = true;
            }

            if ('store_delivery' == $result['code'] && $this->config->get($result['code'].'_status')) {
                $data['storeDeliveryExtensionEnabled'] = true;
            }
        }

        /* END */

        if (isset($this->request->post['city_id'])) {
            $data['city_id'] = $this->request->post['city_id'];
        } elseif (isset($store_info['city_id'])) {
            $data['city_id'] = $store_info['city_id'];
        } else {
            $data['city_id'] = '';
        }

        if (isset($this->request->post['store_type_ids'])) {
            $data['store_type_ids'] = $this->request->post['store_type_ids'];
        } elseif (isset($store_info['store_type_ids'])) {
            $data['store_type_ids'] = explode(',', $store_info['store_type_ids']);
        } else {
            $data['store_type_ids'] = [];
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif (isset($store_info['fax'])) {
            $data['fax'] = $store_info['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (isset($store_info['telephone'])) {
            $data['telephone'] = $store_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (isset($store_info['email'])) {
            $data['email'] = $store_info['email'];
        } else {
            $data['email'] = '';
        }
        
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($store_info['name'])) {
            $data['name'] = $store_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['order_notification_emails'])) {
            $data['order_notification_emails'] = $this->request->post['order_notification_emails'];
        } elseif (isset($store_info['order_notification_emails'])) {
            $data['order_notification_emails'] = $store_info['order_notification_emails'];
        } else {
            $data['order_notification_emails'] = '';
        }

        if (isset($this->request->post['banner_logo_status'])) {
            $data['banner_logo_status'] = $this->request->post['banner_logo_status'];
        } elseif (isset($store_info['banner_logo_status'])) {
            $data['banner_logo_status'] = $store_info['banner_logo_status'];
        } else {
            $data['banner_logo_status'] = 0;
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (isset($store_info['address'])) {
            $data['address'] = $store_info['address'];
        } else {
            $data['address'] = '';
        }

        if (isset($this->request->post['about_us'])) {
            $data['about_us'] = $this->request->post['about_us'];
        } elseif (isset($store_info['about_us'])) {
            $data['about_us'] = $store_info['about_us'];
        } else {
            $data['about_us'] = '';
        }

        if (isset($this->request->post['pickup_notes'])) {
            $data['pickup_notes'] = $this->request->post['pickup_notes'];
        } elseif (isset($store_info['pickup_notes'])) {
            $data['pickup_notes'] = $store_info['pickup_notes'];
        } else {
            $data['pickup_notes'] = '';
        }

        if (isset($this->request->post['city_zipcodes'])) {
            $data['city_zipcodes'] = $this->request->post['city_zipcodes'];
        } elseif (isset($store_info)) {
            $data['city_zipcodes'] = $this->model_setting_store->getZipList($store_info['store_id']);
        } else {
            $data['city_zipcodes'] = [];
        }

        if (empty($data['city_zipcodes'])) {
            $data['city_zipcodes'] = [];
        }

        //echo "<pre>";print_r($data['city_zipcodes']);die;
        if (isset($this->request->post['delivery_time_diff'])) {
            $data['delivery_time_diff'] = $this->request->post['delivery_time_diff'];
        } elseif (isset($store_info['delivery_time_diff'])) {
            $data['delivery_time_diff'] = $store_info['delivery_time_diff'];
        } else {
            $data['delivery_time_diff'] = '';
        }

        if (isset($this->request->post['delivery_date_time_status'])) {
            $data['delivery_date_time_status'] = $this->request->post['delivery_date_time_status'];
        } elseif (isset($store_info['delivery_date_time_status'])) {
            $data['delivery_date_time_status'] = $store_info['delivery_date_time_status'];
        } else {
            $data['delivery_date_time_status'] = '';
        }

        if (!$this->user->isVendor()) {
            if (isset($this->request->post['commision'])) {
                $data['commision'] = $this->request->post['commision'];
            } elseif (isset($store_info['commision'])) {
                $data['commision'] = $store_info['commision'];
            } else {
                $data['commision'] = '';
            }
        }

        if (!$this->user->isVendor()) {
            if (isset($this->request->post['fixed_commision'])) {
                $data['fixed_commision'] = $this->request->post['fixed_commision'];
            } elseif (isset($store_info['fixed_commision'])) {
                $data['fixed_commision'] = $store_info['fixed_commision'];
            } else {
                $data['fixed_commision'] = '';
            }
        }

        if (isset($this->request->post['tax'])) {
            $data['tax'] = $this->request->post['tax'];
        } elseif (isset($store_info['tax'])) {
            $data['tax'] = $store_info['tax'];
        } else {
            $data['tax'] = '';
        }

        if (isset($this->request->post['min_order_amount'])) {
            $data['min_order_amount'] = $this->request->post['min_order_amount'];
        } elseif (isset($store_info['min_order_amount'])) {
            $data['min_order_amount'] = $store_info['min_order_amount'];
        } else {
            $data['min_order_amount'] = '';
        }

        if (isset($this->request->post['serviceable_radius'])) {
            $data['serviceable_radius'] = $this->request->post['serviceable_radius'];
        } elseif (isset($store_info['serviceable_radius'])) {
            $data['serviceable_radius'] = $store_info['serviceable_radius'];
        } else {
            $data['serviceable_radius'] = '';
        }

        if (isset($this->request->post['min_order_cod'])) {
            $data['min_order_cod'] = $this->request->post['min_order_cod'];
        } elseif (isset($store_info['min_order_cod'])) {
            $data['min_order_cod'] = $store_info['min_order_cod'];
        } else {
            $data['min_order_cod'] = '';
        }

        if (isset($this->request->post['min_order_cod'])) {
            $data['min_order_cod'] = $this->request->post['min_order_cod'];
        } elseif (isset($store_info['min_order_cod'])) {
            $data['min_order_cod'] = $store_info['min_order_cod'];
        } else {
            $data['min_order_cod'] = '';
        }

        if (isset($this->request->post['logo'])) {
            $data['logo'] = $this->request->post['logo'];
        } elseif (isset($store_info['logo'])) {
            $data['logo'] = $store_info['logo'];
        } else {
            $data['logo'] = '';
        }

        if (isset($this->request->post['big_logo'])) {
            $data['big_logo'] = $this->request->post['big_logo'];
        } elseif (isset($store_info['big_logo'])) {
            $data['big_logo'] = $store_info['big_logo'];
        } else {
            $data['big_logo'] = '';
        }

        if (isset($this->request->post['banner_logo'])) {
            $data['banner_logo'] = $this->request->post['banner_logo'];
        } elseif (isset($store_info['banner_logo'])) {
            $data['banner_logo'] = $store_info['banner_logo'];
        } else {
            $data['banner_logo'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['logo']) && is_file(DIR_IMAGE.$this->request->post['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['logo'], 100, 100);
        } elseif (isset($store_info['logo']) && is_file(DIR_IMAGE.$store_info['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($store_info['logo'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['big_logo']) && is_file(DIR_IMAGE.$this->request->post['big_logo'])) {
            $data['big_thumb'] = $this->model_tool_image->resize($this->request->post['big_logo'], 100, 100);
        } elseif (isset($store_info['big_logo']) && is_file(DIR_IMAGE.$store_info['big_logo'])) {
            $data['big_thumb'] = $this->model_tool_image->resize($store_info['big_logo'], 100, 100);
        } else {
            $data['big_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['banner_logo']) && is_file(DIR_IMAGE.$this->request->post['banner_logo'])) {
            $data['banner_thumb'] = $this->model_tool_image->resize($this->request->post['banner_logo'], 100, 100);
        } elseif (isset($store_info['banner_logo']) && is_file(DIR_IMAGE.$store_info['banner_logo'])) {
            $data['banner_thumb'] = $this->model_tool_image->resize($store_info['banner_logo'], 100, 100);
        } else {
            $data['banner_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['vendor_id'])) {
            $data['vendor_id'] = $this->request->post['vendor_id'];
        } elseif (isset($store_info['vendor_id'])) {
            $data['vendor_id'] = $store_info['vendor_id'];
        } else {
            $data['vendor_id'] = '';
        }

        if (isset($this->request->post['vendor_name'])) {
            $data['vendor_name'] = $this->request->post['vendor_name'];
        } elseif (isset($store_info['vendor_id'])) {
            $vendor_info = $this->model_tool_image->getVendor($data['vendor_id']);

            $data['vendor_name'] = isset($vendor_info['vendor_name']) ? $vendor_info['vendor_name'] : '';
        } else {
            $data['vendor_name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($store_info['status'])) {
            $data['status'] = $store_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['date_added'])) {
            $data['date_added'] = $this->request->post['date_added'];
        } elseif (isset($store_info['date_added'])) {
            $data['date_added'] = $store_info['date_added'];
        } else {
            $data['date_added'] = '';
        }

        if (isset($this->request->post['min_order_cod'])) {
            $data['min_order_cod'] = $this->request->post['min_order_cod'];
        } elseif (isset($store_info['min_order_cod'])) {
            $data['min_order_cod'] = $store_info['min_order_cod'];
        } else {
            $data['min_order_cod'] = '';
        }
        // if (isset($this->request->post['commision'])) {
        //     $data['commision'] = $this->request->post['commision'];
        // } elseif (isset($store_info['commision'])) {
        //     $data['commision'] = $store_info['commision'];
        // } else {
        //     $data['commision'] = '';
        // }

        if (isset($this->request->post['cost_of_delivery'])) {
            $data['cost_of_delivery'] = $this->request->post['cost_of_delivery'];
        } elseif (isset($store_info['cost_of_delivery'])) {
            $data['cost_of_delivery'] = $store_info['cost_of_delivery'];
        } else {
            $data['cost_of_delivery'] = '';
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

        if (isset($this->request->post['latitude'])) {
            $data['latitude'] = $this->request->post['latitude'];
        } elseif (isset($store_info['latitude'])) {
            $data['latitude'] = $store_info['latitude'];
        } else {
            $data['latitude'] = 46.15242437752303;
        }

        if (isset($this->request->post['longitude'])) {
            $data['longitude'] = $this->request->post['longitude'];
        } elseif (isset($store_info['longitude'])) {
            $data['longitude'] = $store_info['longitude'];
        } else {
            $data['longitude'] = 2.7470703125;
        }

        if (isset($this->request->post['delivery_timeslots'])) {
            $data['delivery_timeslots'] = [];

            //get delivery_timeslots
            if (!empty($this->request->post['delivery_timeslots'][0])) {
                foreach ($this->request->post['delivery_timeslots'][0] as $timeslot => $temp) {
                    $data['delivery_timeslots'][] = [
                        'timeslot' => $timeslot,
                        0 => $this->request->post['delivery_timeslots'][0][$timeslot],
                        1 => $this->request->post['delivery_timeslots'][1][$timeslot],
                        2 => $this->request->post['delivery_timeslots'][2][$timeslot],
                        3 => $this->request->post['delivery_timeslots'][3][$timeslot],
                        4 => $this->request->post['delivery_timeslots'][4][$timeslot],
                        5 => $this->request->post['delivery_timeslots'][5][$timeslot],
                        6 => $this->request->post['delivery_timeslots'][6][$timeslot],
                    ];
                }
            }
        } elseif (isset($store_info['store_id'])) {
            $delivery_timeslots = $this->model_setting_store->getDeliveryTimeslots($store_info['store_id']);

            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            $temp = $delivery_timeslots;

            foreach ($temp as $temp1) {
                $temp2 = explode('-', $temp1['timeslot']);

                if (false !== strpos($temp2[0], 'am')) {
                    array_push($amTimeslot, $temp1);
                } else {
                    if ('12' == substr($temp2[0], 0, 2)) {
                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }
            }

            foreach ($inPmfirstTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            //echo "<pre>";print_r($temp);print_r($amTimeslot);

            //echo "<pre>";print_r($amTimeslot);die;

            $delivery_timeslots = $amTimeslot;

            $data['delivery_timeslots'] = [];

            foreach ($delivery_timeslots as $timeslot) {
                $data['delivery_timeslots'][] = [
                    'timeslot' => $timeslot['timeslot'],
                    0 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 0, $timeslot['store_id']),
                    1 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 1, $timeslot['store_id']),
                    2 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 2, $timeslot['store_id']),
                    3 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 3, $timeslot['store_id']),
                    4 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 4, $timeslot['store_id']),
                    5 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 5, $timeslot['store_id']),
                    6 => $this->model_setting_store->getDeliveryStatus($timeslot['timeslot'], 6, $timeslot['store_id']),
                ];
            }
        } else {
            $data['delivery_timeslots'] = [];
        }

        if (isset($this->request->post['open_hours'])) {
            $data['open_hours'] = [];

            //get open_hours
            if (!empty($this->request->post['open_hours'][0])) {
                foreach ($this->request->post['open_hours'][0] as $timeslot => $temp) {
                    $data['open_hours'][] = [
                        'timeslot' => $timeslot,
                        0 => $this->request->post['open_hours'][0][$timeslot],
                        1 => $this->request->post['open_hours'][1][$timeslot],
                        2 => $this->request->post['open_hours'][2][$timeslot],
                        3 => $this->request->post['open_hours'][3][$timeslot],
                        4 => $this->request->post['open_hours'][4][$timeslot],
                        5 => $this->request->post['open_hours'][5][$timeslot],
                        6 => $this->request->post['open_hours'][6][$timeslot],
                    ];
                }
            }
        } elseif (isset($store_info['store_id'])) {
            $open_hours = $this->model_setting_store->getOpenHours($store_info['store_id']);

            $data['open_hours'] = [];

            foreach ($open_hours as $timeslot) {
                $data['open_hours'][] = [
                    'timeslot' => $timeslot['timeslot'],
                    0 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 0, $timeslot['store_id']),
                    1 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 1, $timeslot['store_id']),
                    2 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 2, $timeslot['store_id']),
                    3 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 3, $timeslot['store_id']),
                    4 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 4, $timeslot['store_id']),
                    5 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 5, $timeslot['store_id']),
                    6 => $this->model_setting_store->getOpenHoursStatus($timeslot['timeslot'], 6, $timeslot['store_id']),
                ];
            }
        } else {
            $data['open_hours'] = [];
        }

        if (isset($this->request->post['pickup_timeslots'])) {
            $data['pickup_timeslots'] = [];

            //get pickup_timeslots
            if (!empty($this->request->post['pickup_timeslots'][0])) {
                foreach ($this->request->post['pickup_timeslots'][0] as $timeslot => $temp) {
                    $data['pickup_timeslots'][] = [
                        'timeslot' => $timeslot,
                        0 => $this->request->post['pickup_timeslots'][0][$timeslot],
                        1 => $this->request->post['pickup_timeslots'][1][$timeslot],
                        2 => $this->request->post['pickup_timeslots'][2][$timeslot],
                        3 => $this->request->post['pickup_timeslots'][3][$timeslot],
                        4 => $this->request->post['pickup_timeslots'][4][$timeslot],
                        5 => $this->request->post['pickup_timeslots'][5][$timeslot],
                        6 => $this->request->post['pickup_timeslots'][6][$timeslot],
                    ];
                }
            }
        } elseif (isset($store_info['store_id'])) {
            $pickup_timeslots = $this->model_setting_store->getPickupTimeslots($store_info['store_id']);

            $data['pickup_timeslots'] = [];

            foreach ($pickup_timeslots as $timeslot) {
                $data['pickup_timeslots'][] = [
                    'timeslot' => $timeslot['timeslot'],
                    0 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 0, $timeslot['store_id']),
                    1 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 1, $timeslot['store_id']),
                    2 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 2, $timeslot['store_id']),
                    3 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 3, $timeslot['store_id']),
                    4 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 4, $timeslot['store_id']),
                    5 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 5, $timeslot['store_id']),
                    6 => $this->model_setting_store->getPickupStatus($timeslot['timeslot'], 6, $timeslot['store_id']),
                ];
            }
        } else {
            $data['pickup_timeslots'] = [];
        }

        $this->load->model('catalog/category');

        // start of top category
        $data['top_categories'] = $this->model_catalog_category->getTopCategories();

        //echo "<pre>";print_r($data['top_categories']);die;
        $category_for_store = [];
        $data['store_categories_commission'] = [];
        $data['store_top_categories'] = [];

        if (isset($this->request->get['store_id'])) {
            $store_categories_commission = $this->model_catalog_category->getStoreCategoriesCommission($this->request->get['store_id']);

            foreach ($store_categories_commission as $key => $value) {
                // code...
                $data['store_categories_commission'][$value['category_id']] = $value;
            }

            $data['store_categories'] = $this->model_catalog_category->getCategoryByStore($this->request->get['store_id']);

            foreach ($data['top_categories'] as $value) {
                // code...
                foreach ($data['store_categories'] as $tmp_key => $tmp_value) {
                    if ($tmp_value['category_id'] == $value['category_id']) {
                        $data['store_top_categories'][] = $value;
                    }
                }
            }
            //echo "<pre>";print_r($data['store_top_categories']);die;

            foreach ($data['store_categories'] as $cat) {
                array_push($category_for_store, $cat['category_id']);
            }

            $category_for_store = implode("','", $category_for_store);
            $category_for_store = "['".$category_for_store."']";

            $data['category_for_store'] = $category_for_store;
        } else {
            $data['store_categories'] = null;
        }

        //echo "<pre>";print_r($data['category_for_store']);die;
        //$category_for_store ="['1']";
        // end of top category

        //start of store type

        $data['categories'] = $this->model_catalog_category->getStoreTypes();

        $this->load->model('localisation/city');

        $data['cities'] = $this->model_localisation_city->getAllCities();
        //$data['city_zipcodes'] = $this->model_localisation_city->getAllZipcodeByCity(4);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/store_form.tpl', $data));
    }

    protected function validateForm()
    {
        //echo "<pre>";print_r($this->request->post);die;
        if (!$this->user->hasPermission('modify', 'setting/store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        //vendor if field only for admin
        if (!$this->user->isVendor() && !$this->request->post['vendor_id']) {
            $this->error['vendor_id'] = $this->language->get('error_vendor_id');
        }

        if (!$this->request->post['name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['telephone']) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ((utf8_strlen($this->request->post['address']) < 3) || (utf8_strlen($this->request->post['address']) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        /*if ((utf8_strlen($this->request->post['about_us']) < 3) || (utf8_strlen($this->request->post['about_us']) > 256)) {
            $this->error['about_us'] = $this->language->get('error_about_us');
        }*/

        //echo "<pre>";print_r($this->request->post);die;
        if ($this->request->post['delivery_by_owner'] && !$this->request->post['delivery_time_diff']) {
            $this->error['delivery_time_diff'] = $this->language->get('error_delivery_time_diff');
        }
        if (!$this->user->isVendor()) {
            if (!$this->request->post['commision']) {
                $this->error['commision'] = $this->language->get('error_commision');
            }
        }

        $this->load->model('catalog/url_alias');

        //echo "<pre>";print_r($this->request->post['category_commission']);die;
        if (isset($this->request->post['commission_type']) && 'store' != $this->request->post['commission_type'] && count($this->request->post['category_commission']) > 0) {
            foreach ($this->request->post['category_commission'] as $key => $value) {
                if (empty($value['commission']) || empty($value['fixed_commission'])) {
                    $this->error['category_commission'] = sprintf($this->language->get('error_commision'));
                    //break;
                }
            }
        }

        if ($this->request->post['seo_url']) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['seo_url']);

            if (empty($this->request->post['seo_url'])) {
                $this->error['seo_url'] = sprintf($this->language->get('error_seo_url_required'));
            }

            if ($url_alias_info && isset($this->request->get['store_id']) && $url_alias_info['query'] != 'store_id='.$this->request->get['store_id']) {
                $this->error['seo_url'] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['store_id'])) {
                $this->error['seo_url'] = sprintf($this->language->get('error_seo_url'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'setting/store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $store_id) {
            if (!$store_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);

            //echo "<pre>";print_r($store_total);die;
            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }
        }

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

        $json = $this->model_sale_order->getStoreDetails($q);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function vendor_autocomplete()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $this->load->model('sale/order');

        $json = $this->model_sale_order->getVendorUserData($filter_name);

        echo json_encode($json);
    }

    public function city_autocomplete()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $this->load->model('sale/order');

        $json = $this->model_sale_order->getCitiesLikeWithLimit($filter_name, 5);

        echo json_encode($json);
    }

    public function export_excel()
    {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_store_excel($data);
    }

    public function export_city_zipcodes()
    {
        $data = [];
        if (isset($this->request->get['city_id'])) {
            $data['city_id'] = $this->request->get['city_id'];
        } else {
            $data['city_id'] = '';
        }
        $this->load->model('report/excel');

        $city = $this->model_report_excel->getCity($data['city_id']);

        $data['city_name'] = $city['name'];

        $this->model_report_excel->download_cityzipcode_excel($data);
    }

    public function getZipcodes()
    {
        if (isset($this->request->get['city_id'])) {
            $city_id = $this->request->get['city_id'];
        } else {
            $city_id = '';
        }

        $this->load->model('localisation/city');

        $json = $this->model_localisation_city->getAllZipcodeByCity($city_id);
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
}
