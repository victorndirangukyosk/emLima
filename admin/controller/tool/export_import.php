<?php

class ControllerToolExportImport extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('tool/export_import');
        $this->getForm();
    }

    public function upload()
    {
        $this->load->language('tool/export_import');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');
        $log = new Log('error.log');
        $log->write('upload 1');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && ($this->validateUploadForm())) {
            $log->write('upload if');

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];
                $incremental = ($this->request->post['incremental']) ? true : false;

                
                    // echo "<pre>dd";print_r($file);
                    // echo "<pre>dd";print_r($incremental);die;

                if (!$this->user->isVendor()) {
                    if (true === $this->model_tool_export_import->upload($file, $this->request->post['incremental'])) {
                        $this->session->data['success'] = $this->language->get('text_success');
                    /*$this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));*/
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                    }
                } else {
                    $this->load->model('tool/export_import_vendor');

                    $log->write('upload if e;s');

                    $res = $this->model_tool_export_import_vendor->upload($file, $this->request->post['incremental']);

                    //echo "<pre>dd";print_r($res);die;
                    if (true === $res) {
                        $this->session->data['success'] = $this->language->get('text_success');
                        $this->response->redirect($this->url->link('tool/export_import', 'token='.$this->session->data['token'], 'SSL'));
                    } elseif (is_array($res)) {
                        //echo "<pre>";print_r("dwe");die;
                        $this->session->data['success'] = $this->language->get('text_success');

                        $downLink = $this->url->link('tool/export_import/downloadModelsNotPresent', 'token='.$this->session->data['token'], 'SSL');

                        //$this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] = "Some products are not available in the system so couldn't be uploaded. <a href=".$downLink.'>Download Excel</a> for such products';

                    /*header("Content-Disposition: attachment; filename=\"models_not_present.xls\"");
                    header("Content-Type: application/vnd.ms-excel;");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    $out = fopen("php://output", 'w');
                    foreach ($res as $data)
                    {
                        fputcsv($out, $data,"\t");
                    }
                    fclose($out);*/
                        //die;
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                    }
                }
            }
        }

        $this->getForm();
    }

    public function prices_import()
    {
        $this->load->language('tool/export_import');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');
        $log = new Log('error.log');
        $log->write('upload 1');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && ($this->validateUploadForm())) {
            $log->write('upload if');

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];
                $incremental = ($this->request->post['incremental']) ? true : false;

                $log->write('upload if e;s');

                $res = $this->model_tool_export_import->uploadCategoryPrices($file, $this->request->post['incremental']);

                //echo "<pre>dd";print_r($res);die;
                if (true === $res) {
                    $this->session->data['success'] = $this->language->get('text_success');
                    $this->response->redirect($this->url->link('tool/export_import', 'token='.$this->session->data['token'], 'SSL'));
                } elseif (is_array($res)) {
                    //echo "<pre>";print_r("dwe");die;
                    $this->session->data['success'] = $this->language->get('text_success');

                    $downLink = $this->url->link('tool/export_import/downloadModelsNotPresent', 'token='.$this->session->data['token'], 'SSL');

                    //$this->error['warning'] = $this->language->get('error_upload');
                    $this->error['warning'] = "Some products are not available in the system so couldn't be uploaded. <a href=".$downLink.'>Download Excel</a> for such products';
                } else {
                    $this->error['warning'] = $this->language->get('error_upload');
                    $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                }
            }
        }

        $this->getForm();
    }

    protected function return_bytes($val)
    {
        $val = trim($val);

        switch (strtolower(substr($val, -1))) {
            case 'm': $val = (int) substr($val, 0, -1) * 1048576; break;
            case 'k': $val = (int) substr($val, 0, -1) * 1024; break;
            case 'g': $val = (int) substr($val, 0, -1) * 1073741824; break;
            case 'b':
                switch (strtolower(substr($val, -2, 1))) {
                    case 'm': $val = (int) substr($val, 0, -2) * 1048576; break;
                    case 'k': $val = (int) substr($val, 0, -2) * 1024; break;
                    case 'g': $val = (int) substr($val, 0, -2) * 1073741824; break;
                    default: break;
                } break;
            default: break;
        }

        return $val;
    }

    public function download()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateDownloadForm()) {
            $export_type = $this->request->post['export_type'];
            $export_type = 'p';

            if (!$this->user->isVendor()) {
                switch ($export_type) {
                    case 'c':
                    case 'p':
                        $min = null;

                        if (isset($this->request->post['min']) && ('' != $this->request->post['min'])) {
                            $min = $this->request->post['min'];
                        }

                        $max = null;
                        if (isset($this->request->post['max']) && ('' != $this->request->post['max'])) {
                            $max = $this->request->post['max'];
                        }

                        if ((null == $min) || (null == $max)) {
                            $this->model_tool_export_import->download($export_type, null, null, null, null);
                        } elseif ('id' == $this->request->post['range_type']) {
                            $this->model_tool_export_import->download($export_type, null, null, $min, $max);
                        } else {
                            $this->model_tool_export_import->download($export_type, $min * ($max - 1 - 1), $min, null, null);
                        }
                        break;
                    case 'o':
                        $this->model_tool_export_import->download('o', null, null, null, null);
                        break;
                    case 'a':
                        $this->model_tool_export_import->download('a', null, null, null, null);
                        break;

                    case 's':
                        $this->load->model('report/excel');
                        $this->model_report_excel->download_store_excel([]);
                        break;

                    /*case 'customer':
                        $this->load->model('report/excel');
                        //$this->model_report_excel->download_customer_excel([]);
                        break;*/

                    default:
                        break;
                }
            } else {
                $this->load->model('tool/export_import_vendor');

                switch ($export_type) {
                    case 'p':

                        $min = null;
                        if (isset($this->request->post['min']) && ('' != $this->request->post['min'])) {
                            $min = $this->request->post['min'];
                        }
                        $max = null;
                        if (isset($this->request->post['max']) && ('' != $this->request->post['max'])) {
                            $max = $this->request->post['max'];
                        }
                        if ((null == $min) || (null == $max)) {
                            $this->model_tool_export_import_vendor->downloadVendor($export_type, null, null, null, null);
                        } elseif ('id' == $this->request->post['range_type']) {
                            $this->model_tool_export_import_vendor->downloadVendor($export_type, null, null, $min, $max);
                        } else {
                            $this->model_tool_export_import_vendor->downloadVendor($export_type, $min * ($max - 1 - 1), $min, null, null);
                        }
                        break;
                    case 's':
                        $this->load->model('report/excel');
                        $this->model_report_excel->download_store_excel([]);
                        break;

                    default:
                        break;
                }
            }

            $this->response->redirect($this->url->link('tool/export_import', 'token='.$this->request->get['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function downloadModelsNotPresent()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import_vendor');

        $resp = $this->db->query('select * from '.DB_PREFIX.'model_not_present')->rows;

        //echo "<pre>";print_r($resp);die;

        $this->model_tool_export_import_vendor->downloadModelsNotPresent('p', null, null, null, null, $resp);

        //echo "<pre>";print_r("ER");die;
        $this->db->query('TRUNCATE '.DB_PREFIX.'model_not_present');

        //$this->getForm();
    }

    public function downloadGeneralProducts()
    {
        //echo "string";die;
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');
        $this->load->model('tool/export_import_vendor');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            // $export_type = $this->request->post['export_type'];

            $export_type = 'p';

            switch ($export_type) {
                    case 'c':
                    case 'p':
                        $min = null;
                        if (isset($this->request->post['min']) && ('' != $this->request->post['min'])) {
                            $min = $this->request->post['min'];
                        }
                        $max = null;
                        if (isset($this->request->post['max']) && ('' != $this->request->post['max'])) {
                            $max = $this->request->post['max'];
                        }
                        if ((null == $min) || (null == $max)) {
                            //$this->model_tool_export_import->download($export_type, null, null, null, null);
                            $this->model_tool_export_import_vendor->downloadGeneralProductsForSample($export_type, null, null, null, null);
                        } elseif ('id' == $this->request->post['range_type']) {
                            //echo "<pre>";print_r($this->request->post);die;
                            //$this->model_tool_export_import->download($export_type, null, null, $min, $max);
                            $this->model_tool_export_import_vendor->downloadGeneralProductsForSample($export_type, null, null, $min, $max);
                        } else {
                            //$this->model_tool_export_import->download($export_type, $min*($max-1-1), $min, null, null);
                            $this->model_tool_export_import_vendor->downloadGeneralProductsForSample($export_type, $min * ($max - 1 - 1), $min, null, null);
                        }
                        break;
                }
            $this->response->redirect($this->url->link('tool/export_import', 'token='.$this->request->get['token'], 'SSL'));
        }
        $this->getForm();
    }

    public function downloadCategoryPricesSheet()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');
        $this->load->model('tool/export_import_vendor');
        $this->model_tool_export_import_vendor->downloadCategoryPricesSample();
    }

    public function settings()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('tool/export_import');
        if (('POST' == $this->request->server['REQUEST_METHOD']) && ($this->validateSettingsForm())) {
            if (!isset($this->request->post['export_import_settings_use_export_cache'])) {
                $this->request->post['export_import_settings_use_export_cache'] = '0';
            }
            if (!isset($this->request->post['export_import_settings_use_import_cache'])) {
                $this->request->post['export_import_settings_use_import_cache'] = '0';
            }
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('export_import', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success_settings');
            $this->response->redirect($this->url->link('tool/export_import', 'token='.$this->session->data['token'], 'SSL'));
        }
        $this->getForm();
    }

    protected function getForm()
    {
        $data = [];
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_export_type_category'] = $this->language->get('text_export_type_category');
        $data['text_export_type_product'] = $this->language->get('text_export_type_product');
        $data['text_export_type_store'] = $this->language->get('text_export_type_store');
        $data['text_export_type_customer'] = $this->language->get('text_export_type_customer');
        $data['text_export_type_poa'] = $this->language->get('text_export_type_poa');
        $data['text_export_type_option'] = $this->language->get('text_export_type_option');
        $data['text_export_type_attribute'] = $this->language->get('text_export_type_attribute');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_loading_notifications'] = $this->language->get('text_loading_notifications');
        $data['text_retry'] = $this->language->get('text_retry');
        $data['text_p'] = $this->language->get('text_p');

        $data['entry_export'] = $this->language->get('entry_export');
        $data['entry_import'] = $this->language->get('entry_import');
        $data['entry_export_type'] = $this->language->get('entry_export_type');
        $data['entry_range_type'] = $this->language->get('entry_range_type');
        $data['entry_start_id'] = $this->language->get('entry_start_id');
        $data['entry_start_index'] = $this->language->get('entry_start_index');
        $data['entry_end_id'] = $this->language->get('entry_end_id');
        $data['entry_end_index'] = $this->language->get('entry_end_index');
        $data['entry_incremental'] = $this->language->get('entry_incremental');
        $data['entry_upload'] = $this->language->get('entry_upload');
        $data['entry_settings_use_option_id'] = $this->language->get('entry_settings_use_option_id');
        $data['entry_settings_use_option_value_id'] = $this->language->get('entry_settings_use_option_value_id');
        $data['entry_settings_use_attribute_group_id'] = $this->language->get('entry_settings_use_attribute_group_id');
        $data['entry_settings_use_attribute_id'] = $this->language->get('entry_settings_use_attribute_id');
        $data['entry_settings_use_export_cache'] = $this->language->get('entry_settings_use_export_cache');
        $data['entry_settings_use_import_cache'] = $this->language->get('entry_settings_use_import_cache');

        $data['tab_export'] = $this->language->get('tab_export');
        $data['tab_import'] = $this->language->get('tab_import');
        $data['tab_settings'] = $this->language->get('tab_settings');
        $data['tab_import_prices'] = $this->language->get('tab_import_prices');
        $data['tab_sample_data'] = $this->language->get('tab_sample_data');

        $data['button_export'] = $this->language->get('button_export');
        $data['button_import'] = $this->language->get('button_import');
        $data['button_settings'] = $this->language->get('button_settings');
        $data['button_export_id'] = $this->language->get('button_export_id');
        $data['button_export_page'] = $this->language->get('button_export_page');

        $data['help_range_type'] = $this->language->get('help_range_type');
        $data['help_incremental_yes'] = $this->language->get('help_incremental_yes');
        $data['help_incremental_no'] = $this->language->get('help_incremental_no');
        $data['help_import'] = $this->language->get('help_import');
        $data['help_format'] = $this->language->get('help_format');

        $data['error_select_file'] = $this->language->get('error_select_file');
        $data['error_post_max_size'] = str_replace('%1', ini_get('post_max_size'), $this->language->get('error_post_max_size'));
        $data['error_upload_max_filesize'] = str_replace('%1', ini_get('upload_max_filesize'), $this->language->get('error_upload_max_filesize'));
        $data['error_id_no_data'] = $this->language->get('error_id_no_data');
        $data['error_page_no_data'] = $this->language->get('error_page_no_data');
        $data['error_param_not_number'] = $this->language->get('error_param_not_number');
        $data['error_notifications'] = $this->language->get('error_notifications');
        $data['error_no_news'] = $this->language->get('error_no_news');
        $data['error_batch_number'] = $this->language->get('error_batch_number');
        $data['error_min_item_id'] = $this->language->get('error_min_item_id');

        if (!empty($this->session->data['export_import_error']['errstr'])) {
            $this->error['warning'] = $this->session->data['export_import_error']['errstr'];
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
            if (!empty($this->session->data['export_import_nochange'])) {
                $data['error_warning'] .= "<br />\n".$this->language->get('text_nochange');
            }
        } else {
            $data['error_warning'] = '';
        }

        unset($this->session->data['export_import_error']);
        unset($this->session->data['export_import_nochange']);

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/export_import', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['back'] = $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL');
        $data['button_back'] = $this->language->get('button_back');
        $data['import'] = $this->url->link('tool/export_import/upload', 'token='.$this->session->data['token'], 'SSL');
        $data['export'] = $this->url->link('tool/export_import/download', 'token='.$this->session->data['token'], 'SSL');
        $data['settings'] = $this->url->link('tool/export_import/settings', 'token='.$this->session->data['token'], 'SSL');
        $data['import_prices'] = $this->url->link('tool/export_import/prices_import', 'token='.$this->session->data['token'], 'SSL');
        $data['post_max_size'] = $this->return_bytes(ini_get('post_max_size'));
        $data['upload_max_filesize'] = $this->return_bytes(ini_get('upload_max_filesize'));

        $data['exportgeneral'] = $this->url->link('tool/export_import/downloadGeneralProducts', 'token='.$this->session->data['token'], 'SSL');
        $data['exportgeneralsuperadmin'] = $this->url->link('tool/export_import/download', 'token='.$this->session->data['token'], 'SSL');
        $data['exportcatprices'] = $this->url->link('tool/export_import/downloadCategoryPricesSheet', 'token='.$this->session->data['token'], 'SSL');
        if (isset($this->request->post['export_type'])) {
            $data['export_type'] = $this->request->post['export_type'];
        } else {
            $data['export_type'] = 'p';
        }

        if (isset($this->request->post['range_type'])) {
            $data['range_type'] = $this->request->post['range_type'];
        } else {
            $data['range_type'] = 'id';
        }

        if (isset($this->request->post['min'])) {
            $data['min'] = $this->request->post['min'];
        } else {
            $data['min'] = '';
        }

        if (isset($this->request->post['max'])) {
            $data['max'] = $this->request->post['max'];
        } else {
            $data['max'] = '';
        }

        if (isset($this->request->post['incremental'])) {
            $data['incremental'] = $this->request->post['incremental'];
        } else {
            $data['incremental'] = '1';
        }

        if (isset($this->request->post['export_import_settings_use_option_id'])) {
            $data['settings_use_option_id'] = $this->request->post['export_import_settings_use_option_id'];
        } elseif ($this->config->get('export_import_settings_use_option_id')) {
            $data['settings_use_option_id'] = '1';
        } else {
            $data['settings_use_option_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_option_value_id'])) {
            $data['settings_use_option_value_id'] = $this->request->post['export_import_settings_use_option_value_id'];
        } elseif ($this->config->get('export_import_settings_use_option_value_id')) {
            $data['settings_use_option_value_id'] = '1';
        } else {
            $data['settings_use_option_value_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_attribute_group_id'])) {
            $data['settings_use_attribute_group_id'] = $this->request->post['export_import_settings_use_attribute_group_id'];
        } elseif ($this->config->get('export_import_settings_use_attribute_group_id')) {
            $data['settings_use_attribute_group_id'] = '1';
        } else {
            $data['settings_use_attribute_group_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_attribute_id'])) {
            $data['settings_use_attribute_id'] = $this->request->post['export_import_settings_use_attribute_id'];
        } elseif ($this->config->get('export_import_settings_use_attribute_id')) {
            $data['settings_use_attribute_id'] = '1';
        } else {
            $data['settings_use_attribute_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_export_cache'])) {
            $data['settings_use_export_cache'] = $this->request->post['export_import_settings_use_export_cache'];
        } elseif ($this->config->get('export_import_settings_use_export_cache')) {
            $data['settings_use_export_cache'] = '1';
        } else {
            $data['settings_use_export_cache'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_import_cache'])) {
            $data['settings_use_import_cache'] = $this->request->post['export_import_settings_use_import_cache'];
        } elseif ($this->config->get('export_import_settings_use_import_cache')) {
            $data['settings_use_import_cache'] = '1';
        } else {
            $data['settings_use_import_cache'] = '0';
        }

        $min_product_id = $this->model_tool_export_import->getMinProductId();
        $max_product_id = $this->model_tool_export_import->getMaxProductId();
        $count_product = $this->model_tool_export_import->getCountProduct();
        $min_category_id = $this->model_tool_export_import->getMinCategoryId();
        $max_category_id = $this->model_tool_export_import->getMaxCategoryId();
        $count_category = $this->model_tool_export_import->getCountCategory();

        $data['min_product_id'] = $min_product_id;
        $data['max_product_id'] = $max_product_id;
        $data['count_product'] = $count_product;
        $data['min_category_id'] = $min_category_id;
        $data['max_category_id'] = $max_category_id;
        $data['count_category'] = $count_category;

        $data['token'] = $this->session->data['token'];

        $this->document->addStyle('ui/stylesheet/export_import.css');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/export_import.tpl', $data));
    }

    protected function validateDownloadForm()
    {
        if (!$this->user->hasPermission('access', 'tool/export_import')) {
            $this->error['warning'] = $this->language->get('error_permission');

            return false;
        }

        return true;
    }

    protected function validateUploadForm()
    {
        if (!$this->user->hasPermission('modify', 'tool/export_import')) {
            $this->error['warning'] = $this->language->get('error_permission');
        } elseif (!isset($this->request->post['incremental'])) {
            $this->error['warning'] = $this->language->get('error_incremental');
        } elseif ('0' != $this->request->post['incremental']) {
            if ('1' != $this->request->post['incremental']) {
                $this->error['warning'] = $this->language->get('error_incremental');
            }
        }

        if (!isset($this->request->files['upload']['name'])) {
            if (isset($this->error['warning'])) {
                $this->error['warning'] .= "<br /\n".$this->language->get('error_upload_name');
            } else {
                $this->error['warning'] = $this->language->get('error_upload_name');
            }
        } else {
            $ext = strtolower(pathinfo($this->request->files['upload']['name'], PATHINFO_EXTENSION));
            if (('xls' != $ext) && ('xlsx' != $ext) && ('ods' != $ext)) {
                if (isset($this->error['warning'])) {
                    $this->error['warning'] .= "<br /\n".$this->language->get('error_upload_ext');
                } else {
                    $this->error['warning'] = $this->language->get('error_upload_ext');
                }
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateSettingsForm()
    {
        if (!$this->user->hasPermission('access', 'tool/export_import')) {
            $this->error['warning'] = $this->language->get('error_permission');

            return false;
        }

        if (empty($this->request->post['export_import_settings_use_option_id'])) {
            $option_names = $this->model_tool_export_import->getOptionNameCounts();
            foreach ($option_names as $option_name) {
                if ($option_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $option_name['name'], $this->language->get('error_option_name'));

                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_option_value_id'])) {
            $option_value_names = $this->model_tool_export_import->getOptionValueNameCounts();
            foreach ($option_value_names as $option_value_name) {
                if ($option_value_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $option_value_name['name'], $this->language->get('error_option_value_name'));

                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_attribute_group_id'])) {
            $attribute_group_names = $this->model_tool_export_import->getAttributeGroupNameCounts();
            foreach ($attribute_group_names as $attribute_group_name) {
                if ($attribute_group_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $attribute_group_name['name'], $this->language->get('error_attribute_group_name'));

                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_attribute_id'])) {
            $attribute_names = $this->model_tool_export_import->getAttributeNameCounts();
            foreach ($attribute_names as $attribute_name) {
                if ($attribute_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $attribute_name['name'], $this->language->get('error_attribute_name'));

                    return false;
                }
            }
        }

        return true;
    }

    public function getNotifications()
    {
        sleep(1); // give the data some "feel" that its not in our system
        $this->load->model('tool/export_import');
        $this->load->language('tool/export_import');
        $response = $this->model_tool_export_import->getNotifications();
        $json = [];
        if (false === $response) {
            $json['message'] = '';
            $json['error'] = $this->language->get('error_notifications');
        } else {
            $json['message'] = $response;
            $json['error'] = '';
        }

        $this->response->setOutput(json_encode($json));
    }
}
