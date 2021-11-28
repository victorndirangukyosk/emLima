<?php

require_once DIR_SYSTEM.'elfinder/elFinderConnector.class.php';
require_once DIR_SYSTEM.'elfinder/elFinder.class.php';
require_once DIR_SYSTEM.'elfinder/elFinderVolumeDriver.class.php';
require_once DIR_SYSTEM.'elfinder/elFinderVolumeLocalFileSystem.class.php';

class ControllerToolFilemanager extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('tool/file_manager');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $this->document->breadcrumbs = [];

        $this->validate();

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/file_manager', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['fileSystem'] = $this->url->link('tool/file_manager/runFileSystem', 'token='.$this->session->data['token'], 'SSL');

        $this->document->addStyle('http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css', 'stylesheet', '');

        $this->document->addStyle('ui/stylesheet/elfinder.min.css', 'stylesheet', '');
        // $this->document->addStyle('ui/stylesheet/theme.css', 'stylesheet', '');

        $this->document->addScript('ui/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('ui/javascript/elfinder/jquery.browser.js');
        $this->document->addScript('ui/javascript/elfinder/elfinder.min.js');
        $this->document->addScript('ui/javascript/elfinder/proxy/elFinderSupportVer1.js');

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

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/file_manager.tpl', $data));
    }

    public function runFileSystem()
    {
        if ($this->user->isVendor()) {
            $oldmask = umask(0);
            mkdir(DIR_ROOT.'image/vendor/'.$this->user->getId(), 0777);
            umask($oldmask);

            $opts = [
                'debug' => true,
                'roots' => [
                    [
                        'driver' => 'LocalFileSystem',
                        'path' => DIR_ROOT.'image/vendor/'.$this->user->getId(),
                        'URL' => HTTP_CATALOG.'image/vendor/'.$this->user->getId(),
                        'accessControl' => 'access', //disable and hide dot starting files (OPTIONAL)
                    ],
                ],
            ];
        } else {
            $opts = [
                // 'debug' => true,
                'roots' => [
                    [
                        'driver' => 'LocalFileSystem',
                        'path' => DIR_ROOT,
                        'URL' => HTTP_CATALOG,
                        'accessControl' => 'access',      //disable and hide dot starting files (OPTIONAL)
                    ],
                ],
            ];
        }

        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }

    public function access($attr, $path, $data, $volume)
    {
        return 0 === strpos(basename($path), '.')       // if file/folder begins with '.' (dot)
                ? !('read' == $attr || 'write' == $attr)    // set read+write to false, other (locked+hidden) set to true
                : null;                                    // else elFinder decide it itself
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'tool/file_manager')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!extension_loaded('zip')) {
            $this->error['warning'] = $this->language->get('error_zip');
        }

        return !$this->error;
    }
}
