<?php
$target_dir = "uploads/";

class ControllerSettingNewfeature extends Controller
{
    private $error = [];

    public function index()
    {

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/newfeature');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/newfeature');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $newfeature_id = $this->model_setting_newfeature->addNewfeature($this->request->post);

            $this->load->model('setting/setting');
 
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/newfeature', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/newfeature');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/newfeature');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_setting_newfeature->editNewfeature($this->request->get['newfeature_id'], $this->request->post);

            $this->load->model('setting/setting');

           // !empty($this->request->post['date_added']) ?: $this->request->post['date_added'] = $this->request->post['config_name'];
 
            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/newfeature/edit', 'newfeature_id='.$this->request->get['newfeature_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/newfeature/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/newfeature/edit', 'newfeature_id='.$this->request->get['newfeature_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/newfeature/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/newfeature', 'token='.$this->session->data['token'].'&newfeature_id='.$this->request->get['newfeature_id'], 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/newfeature');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/newfeature');

        $this->load->model('setting/setting');

        if (isset($this->request->post['selected'])  ) {
            foreach ($this->request->post['selected'] as $newfeature_id) {
                $this->model_setting_newfeature->deleteNewfeature($newfeature_id);
 
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/newfeature', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {

        $this->load->language('setting/newfeature');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/newfeature');

        $url = '';

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
            'href' => $this->url->link('setting/newfeature', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['add'] = $this->url->link('setting/newfeature/add', 'token='.$this->session->data['token'], 'SSL');
        $data['delete'] = $this->url->link('setting/newfeature/delete', 'token='.$this->session->data['token'], 'SSL');

        $data['newfeatures'] = [];

        $newfeature_total = $this->model_setting_newfeature->getTotalNewfeatures();

        $results = $this->model_setting_newfeature->getNewfeatures();

        foreach ($results as $result) {
            $data['newfeatures'][] = [
                'newfeature_id' => $result['newfeature_id'],
                'name' => $result['user_name'],
                'summary' => $result['summary'],
                'detail_description' => $result['detail_description'],
                'additional_requirement' => $result['additional_requirement'],
                'File' => $result['File'],
                'business_impact' => $result['business_impact'],
                'is_customer_requirement' => $result['is_customer_requirement'],
                'customer_name' => $result['customer_name'],
                'customers_requested' => $result['customers_requested'],
                'no_of_customers_onboarded' => $result['no_of_customers_onboarded'],

                'edit' => $this->url->link('setting/newfeature/edit', 'token='.$this->session->data['token'].'&newfeature_id='.$result['newfeature_id'], 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_disable'] = $this->language->get('text_disable');

        
        $data['column_name'] = $this->language->get('column_name');
        $data['column_summary'] = $this->language->get('column_summary');
        $data['column_detail_description'] = $this->language->get('column_detail_description');
        $data['column_additional_requirement'] = $this->language->get('column_additional_requirement');
        $data['column_File'] = $this->language->get('column_File');
        $data['column_business_impact'] = $this->language->get('column_business_impact');
        $data['column_is_customer_requirement'] = $this->language->get('column_is_customer_requirement');
        $data['column_customer_name'] = $this->language->get('column_customer_name');

        $data['column_action'] = $this->language->get('column_action');
        $data['column_message'] = $this->language->get('column_message');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_status'] = $this->language->get('column_status');

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

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/newfeature_list.tpl', $data));
    }

    public function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_newfeatures'] = $this->language->get('text_newfeatures');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        

        $data['entry_name'] = $this->language->get('column_name');
        $data['entry_summary'] = $this->language->get('column_summary');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_disable'] = $this->language->get('text_disable');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_summary'] = $this->language->get('column_summary');
        $data['column_detail_description'] = $this->language->get('column_detail_description');
        $data['column_additional_requirement'] = $this->language->get('column_additional_requirement');
        $data['column_File'] = $this->language->get('column_File');
        $data['column_customer_name'] = $this->language->get('column_customer_name');
        $data['column_customers_requested'] = $this->language->get('column_customers_requested');
        $data['column_no_of_customers_onboarded'] = $this->language->get('column_no_of_customers_onboarded');
        $data['column_business_impact'] = $this->language->get('column_business_impact');
        $data['column_is_customer_requirement'] = $this->language->get('column_is_customer_requirement');
 
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

        if (isset($this->error['business_impact'])) {
            $data['error_business_impact'] = $this->error['business_impact'];
        } else {
            $data['error_business_impact'] = '';
        }

        if (isset($this->error['detail_description'])) {
            $data['error_detail_description'] = $this->error['detail_description'];
        } else {
            $data['error_detail_description'] = '';
        }

        if (isset($this->error['summary'])) {
            $data['error_summary'] = $this->error['summary'];
        } else {
            $data['error_summary'] = '';
        }



        if (isset($this->error['file'])) {
            $data['error_file'] = $this->error['file'];
        } else {
            $data['error_file'] = '';
        }



        if (isset($this->error['customer_name'])) {
            $data['error_customer_name'] = $this->error['customer_name'];
        } else {
            $data['error_customer_name'] = '';
        }

        if (isset($this->error['is_customer_requirement'])) {
            $data['error_is_customer_requirement'] = $this->error['is_customer_requirement'];
        } else {
            $data['error_is_customer_requirement'] = '';
        }



        if (isset($this->error['customers_requested'])) {
            $data['error_customers_requested'] = $this->error['customers_requested'];
        } else {
            $data['error_customers_requested'] = '';
        }


        if (isset($this->error['no_of_customers_onboarded'])) {
            $data['error_no_of_customers_onboarded'] = $this->error['no_of_customers_onboarded'];
        } else {
            $data['error_no_of_customers_onboarded'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/newfeature', 'token='.$this->session->data['token'], 'SSL'),
        ];

        if (!isset($this->request->get['newfeature_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/newfeature/add', 'token='.$this->session->data['token'], 'SSL'),
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/newfeature/edit', 'token='.$this->session->data['token'].'&newfeature_id='.$this->request->get['newfeature_id'], 'SSL'),
            ];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (!isset($this->request->get['newfeature_id'])) {
            $data['action'] = $this->url->link('setting/newfeature/add', 'token='.$this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('setting/newfeature/edit', 'token='.$this->session->data['token'].'&newfeature_id='.$this->request->get['newfeature_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('setting/newfeature', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->get['newfeature_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $this->load->model('setting/newfeature');
             
            $newfeature_info = $this->model_setting_newfeature->getNewfeature($this->request->get['newfeature_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($newfeature_info['user_name'])) {
            $data['name'] = $newfeature_info['user_name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['detail_description'])) {
            $data['detail_description'] = $this->request->post['detail_description'];
        } elseif (isset($newfeature_info['detail_description'])) {
            $data['detail_description'] = $newfeature_info['detail_description'];
        } else {
            $data['detail_description'] = '';
        }

        if (isset($this->request->post['summary'])) {
            $data['summary'] = $this->request->post['summary'];
        } elseif (isset($newfeature_info)) {
            $data['summary'] = $newfeature_info['summary'];
        } else {
            $data['summary'] = '';
        }

        if (isset($this->request->post['additional_requirement'])) {
            $data['additional_requirement'] = $this->request->post['summary'];
        } elseif (isset($newfeature_info)) {
            $data['additional_requirement'] = $newfeature_info['additional_requirement'];
        } else {
            $data['additional_requirement'] = '';
        }

        if (isset($this->request->post['File'])) {
            $data['File'] = $this->request->post['File'];
        } elseif (isset($newfeature_info)) {
            $data['File'] = $newfeature_info['File'];
        } else {
            $data['File'] = '';
        }
        if (isset($this->request->post['business_impact'])) {
            $data['business_impact'] = $this->request->post['business_impact'];
        } elseif (isset($newfeature_info)) {
            $data['business_impact'] = $newfeature_info['business_impact'];
        } else {
            $data['business_impact'] = '';
        }

        if (isset($this->request->post['is_customer_requirement'])) {
            $data['is_customer_requirement'] = $this->request->post['is_customer_requirement'];
        } elseif (isset($newfeature_info)) {
            $data['is_customer_requirement'] = $newfeature_info['is_customer_requirement'];
        } else {
            $data['is_customer_requirement'] = '';
        }

        if (isset($this->request->post['customer_name'])) {
            $data['customer_name'] = $this->request->post['customer_name'];
        } elseif (isset($newfeature_info)) {
            $data['customer_name'] = $newfeature_info['customer_name'];
        } else {
            $data['customer_name'] = '';
        }

        if (isset($this->request->post['customers_requested'])) {
            $data['customers_requested'] = $this->request->post['customers_requested'];
        } elseif (isset($newfeature_info)) {
            $data['customers_requested'] = $newfeature_info['customers_requested'];
        } else {
            $data['customers_requested'] = '';
        }

        if (isset($this->request->post['no_of_customers_onboarded'])) {
            $data['no_of_customers_onboarded'] = $this->request->post['no_of_customers_onboarded'];
        } elseif (isset($newfeature_info)) {
            $data['no_of_customers_onboarded'] = $newfeature_info['no_of_customers_onboarded'];
        } else {
            $data['no_of_customers_onboarded'] = '';
        }

        

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE.$this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (isset($newfeature_info['image']) && is_file(DIR_IMAGE.$newfeature_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($newfeature_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/newfeature_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/newfeature')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['summary']) < 10) || (utf8_strlen($this->request->post['summary']) > 500)) {
            $this->error['summary'] = $this->language->get('error_summary');
        }

        if (!$this->request->post['detail_description']) {
            $this->error['detail_description'] = $this->language->get('error_detail_description');
        } 

        // if (!$this->request->post['file']) {
        //     $this->error['error_file'] = $this->language->get('error_file');
        // } 


        // if (!$this->request->post['additional_requirement']) {
        //     $this->error['additional_requirement'] = $this->language->get('error_additional_requirement');
        // } 

        if (!$this->request->post['customer_name']) {
            $this->error['customer_name'] = $this->language->get('error_customer_name');
        }  
        
        if (!$this->request->post['customers_requested']) {
            $this->error['customers_requested'] = $this->language->get('error_customers_requested');
        } 

        if (!$this->request->post['no_of_customers_onboarded']) {
            $this->error['no_of_customers_onboarded'] = $this->language->get('error_no_of_customers_onboarded');
        } 

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

   

    
    public function uploadAdditionlRequirement()
    {   

        $target_file = $target_dir . basename($_FILES["additional_requirement"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
        // $check = getimagesize($_FILES["additional_requirement"]["tmp_name"]);
        // if($check !== false) {
        //     echo "File is an image - " . $check["mime"] . ".";
        //     $uploadOk = 1;
        // } else {
        //     echo "File is not an image.";
        //     $uploadOk = 0;
        // }
        }
     // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
          }

          // Check file size
        if ($_FILES["additional_requirement"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }


            // Allow certain file formats
            if($imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx"
            && $imageFileType != "ppt" ) {
            echo "Sorry, only Word, Excel, PPT, PDF files are allowed.";
            $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["additional_requirement"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["additional_requirement"]["name"])). " has been uploaded.";
                } else {
                echo "Sorry, there was an error uploading your file.";
                }
            }



        $json = []; 
        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        } 
        // Make sure we have the correct directory
        // Make sure we have the correct directory 
        
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'data/'.str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'data';
            }
         

        //create directory if not exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Check its a directory
        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file types: Word, Excel, PPT, PDF
                $allowed = [
                    'docx',
                    'doc',
                    'xlsx',
                    'xls',
                    'pdf',
                    'ppt',
                ];

                if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = [
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/pdf',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                ];

                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if (UPLOAD_ERR_OK != $this->request->files['file']['error']) {
                    $json['error'] = $this->language->get('error_upload_'.$this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            move_uploaded_file($this->request->files['file']['tmp_name'], $directory.'/'.$filename);

            $json['success'] = $this->language->get('text_uploaded');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
