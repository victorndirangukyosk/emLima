<?php

class ControllerCommonFileManager extends Controller
{
    public function index()
    {
        $this->load->language('common/filemanager');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = rtrim(str_replace(['../', '..\\', '..', '*'], '', $this->request->get['filter_name']), '/');
        } else {
            $filter_name = null;
        }

        // Make sure we have the correct directory
        if ($this->user->isVendor()) {
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'vendor/'.$this->user->getId().str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'vendor/'.$this->user->getId();
            }
        } else {
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'data/'.str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'data';
            }
        }

        //create directory if not exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['images'] = [];

        $this->load->model('tool/image');

        // Get directories
        $directories = glob($directory.'/'.$filter_name.'*', GLOB_ONLYDIR);

        if (!$directories) {
            $directories = [];
        }

        // Get files
        $files = glob($directory.'/'.$filter_name.'*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF,svg}', GLOB_BRACE);

        if (!$files) {
            $files = [];
        }

        // Merge directories and files
        $images = array_merge($directories, $files);

        // Get total number of files and directories
        $image_total = count($images);

        // Split the array based on current page number and max number of items per page of 10
        $images = array_splice($images, ($page - 1) * 8, 8);

        foreach ($images as $image) {
            $name = str_split(basename($image), 14);

            if (is_dir($image)) {
                $url = '';

                if (isset($this->request->get['target'])) {
                    $url .= '&target='.$this->request->get['target'];
                }

                if (isset($this->request->get['thumb'])) {
                    $url .= '&thumb='.$this->request->get['thumb'];
                }

                $data['images'][] = [
                    'thumb' => '',
                    'name' => implode(' ', $name),
                    'type' => 'directory',
                    'path' => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                    'href' => $this->url->link('common/filemanager', 'token='.$this->session->data['token'].'&directory='.urlencode(utf8_substr($image, utf8_strlen(DIR_IMAGE.'data/'))).$url, 'SSL'),
                ];
            } elseif (is_file($image)) {
                // Find which protocol to use to pass the full image link back
                if ($this->request->server['HTTPS']) {
                    $server = HTTPS_CATALOG;
                } else {
                    $server = HTTP_CATALOG;
                }

                $data['images'][] = [
                    'thumb' => $this->model_tool_image->resize(utf8_substr($image, utf8_strlen(DIR_IMAGE)), 100, 100),
                    'name' => implode(' ', $name),
                    'type' => 'image',
                    'path' => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                    'href' => $server.'image/'.utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                ];
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_search'] = $this->language->get('entry_search');
        $data['entry_folder'] = $this->language->get('entry_folder');

        $data['button_parent'] = $this->language->get('button_parent');
        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_folder'] = $this->language->get('button_folder');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_search'] = $this->language->get('button_search');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['directory'])) {
            $data['directory'] = urlencode($this->request->get['directory']);
        } else {
            $data['directory'] = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $data['filter_name'] = $this->request->get['filter_name'];
        } else {
            $data['filter_name'] = '';
        }

        // Return the target ID for the file manager to set the value
        if (isset($this->request->get['target'])) {
            $data['target'] = $this->request->get['target'];
        } else {
            $data['target'] = '';
        }

        // Return the thumbnail for the file manager to show a thumbnail
        if (isset($this->request->get['thumb'])) {
            $data['thumb'] = $this->request->get['thumb'];
        } else {
            $data['thumb'] = '';
        }

        // Return the thumbnail for the file manager to show a thumbnail
        if (isset($this->request->get['customizer'])) {
            $data['customizer'] = $this->request->get['customizer'];
        } else {
            $data['customizer'] = '';
        }
        // Parent
        $url = '';

        if (isset($this->request->get['directory'])) {
            $pos = strrpos($this->request->get['directory'], '/');

            if ($pos) {
                $url .= '&directory='.urlencode(substr($this->request->get['directory'], 0, $pos));
            }
        }

        if (isset($this->request->get['target'])) {
            $url .= '&target='.$this->request->get['target'];
        }

        if (isset($this->request->get['thumb'])) {
            $url .= '&thumb='.$this->request->get['thumb'];
        }

        $data['parent'] = $this->url->link('common/filemanager', 'token='.$this->session->data['token'].$url, 'SSL');

        // Refresh
        $url = '';

        if (isset($this->request->get['directory'])) {
            $url .= '&directory='.urlencode($this->request->get['directory']);
        }

        if (isset($this->request->get['target'])) {
            $url .= '&target='.$this->request->get['target'];
        }

        if (isset($this->request->get['thumb'])) {
            $url .= '&thumb='.$this->request->get['thumb'];
        }

        $data['refresh'] = $this->url->link('common/filemanager', 'token='.$this->session->data['token'].$url, 'SSL');

        $url = '';

        if (isset($this->request->get['directory'])) {
            $url .= '&directory='.urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['target'])) {
            $url .= '&target='.$this->request->get['target'];
        }

        if (isset($this->request->get['thumb'])) {
            $url .= '&thumb='.$this->request->get['thumb'];
        }

        $pagination = new Pagination();
        $pagination->total = $image_total;
        $pagination->page = $page;
        $pagination->limit = 8;
        $pagination->url = $this->url->link('common/filemanager', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $this->response->setOutput($this->load->view('common/filemanager.tpl', $data));
    }

    public function upload()
    {
        $this->load->language('common/filemanager');

        $json = [];

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Make sure we have the correct directory
        // Make sure we have the correct directory

        if ($this->user->isVendor()) {
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'vendor/'.$this->user->getId().str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'vendor/'.$this->user->getId();
            }
        } else {
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'data/'.str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'data';
            }
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

                // Allowed file extension types
                $allowed = [
                    'jpg',
                    'jpeg',
                    'gif',
                    'png',
                    'svg',
                ];

                if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = [
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif',
                    'image/svg+xml',
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
            $log = new Log('error.log');
            $log->write($directory);
            move_uploaded_file($this->request->files['file']['tmp_name'], $directory.'/'.$filename);

            $json['success'] = $this->language->get('text_uploaded');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function folder()
    {
        $this->load->language('common/filemanager');

        $json = [];

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Make sure we have the correct directory
        // Make sure we have the correct directory

        if ($this->user->isVendor()) {
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'vendor/'.$this->user->getId().str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'vendor/'.$this->user->getId();
            }
        } else {
            if (isset($this->request->get['directory'])) {
                $directory = rtrim(DIR_IMAGE.'data/'.str_replace(['../', '..\\', '..'], '', $this->request->get['directory']), '/');
            } else {
                $directory = DIR_IMAGE.'data';
            }
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
            // Sanitize the folder name
            $folder = str_replace(['../', '..\\', '..'], '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

            // Validate the filename length
            if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
                $json['error'] = $this->language->get('error_folder');
            }

            // Check if directory already exists or not
            if (is_dir($directory.'/'.$folder)) {
                $json['error'] = $this->language->get('error_exists');
            }
        }

        if (!$json) {
            $this->filesystem->mkdir($directory.'/'.$folder);

            $json['success'] = $this->language->get('text_directory');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete()
    {
        $this->load->language('common/filemanager');

        $json = [];

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['path'])) {
            $paths = $this->request->post['path'];
        } else {
            $paths = [];
        }

        // Loop through each path to run validations
        foreach ($paths as $path) {
            $path = rtrim(DIR_IMAGE.str_replace(['../', '..\\', '..'], '', $path), '/');

            // Check path exsists
            if ($path == DIR_IMAGE.'data') {
                $json['error'] = $this->language->get('error_delete');

                break;
            }
        }

        if (!$json) {
            // Loop through each path
            foreach ($paths as $path) {
                $path = rtrim(DIR_IMAGE.str_replace(['../', '..\\', '..'], '', $path), '/');

                // Delete file or directory
                $this->filesystem->remove($path);
            }

            $json['success'] = $this->language->get('text_delete');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
