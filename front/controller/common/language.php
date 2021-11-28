<?php

class ControllerCommonLanguage extends Controller
{
    public function index()
    {
        //echo "<pre>";print_r("er");die;
        $this->load->language('common/language');

        $data['text_language'] = $this->language->get('text_language');

        $data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

        $log = new Log('error.log');
        $log->write('language ss');

        $log->write($this->session->data['language']);

        //$this->session->data['language'] = 'portu';

        //$this->session->data['language'] = 'fr';
        $data['code'] = $this->session->data['language'];

        $this->load->model('localisation/language');

        $data['languages'] = [];

        $results = $this->model_localisation_language->getLanguages();

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = [
                    'name' => $result['name'],
                    'code' => $result['code'],
                    'image' => $result['image'],
                ];
            }
        }

        $url_data = $this->request->get;
        unset($url_data['lang']);
        unset($url_data['_path_']);

        if (!isset($url_data['path'])) {
            $url_data['path'] = 'common/home';
        }

        if (isset($this->request->post['code'])) {
            //die;
            $this->session->data['language'] = $this->request->post['code'];
        }
        $log->write($this->session->data['language']);

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->request->post['redirect']) && $this->request->post['redirect'] == $server) {
            $log->write('in if');
            $this->response->redirect($server);

            return false;
        }

        $data['base'] = $server;
        $data['redirect'] = urldecode(http_build_query($url_data, '', '&'));

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/language.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/language.tpl', $data);
        } else {
            return $this->load->view('default/template/common/language.tpl', $data);
        }
    }

    public function dropDown()
    {
        //echo "<pre>";print_r("er");die;
        $this->load->language('common/language');

        $data['text_language'] = $this->language->get('text_language');

        $data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

        $log = new Log('error.log');
        $log->write('language ss');

        $log->write($this->session->data['language']);

        //$this->session->data['language'] = 'portu';

        //$this->session->data['language'] = 'fr';
        $data['code'] = $this->session->data['language'];

        $this->load->model('localisation/language');

        $data['languages'] = [];

        $results = $this->model_localisation_language->getLanguages();

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = [
                    'name' => $result['name'],
                    'code' => $result['code'],
                    'image' => $result['image'],
                ];
            }
        }

        $url_data = $this->request->get;
        unset($url_data['lang']);
        unset($url_data['_path_']);

        if (!isset($url_data['path'])) {
            $url_data['path'] = 'common/home';
        }

        if (isset($this->request->post['code'])) {
            //die;
            $this->session->data['language'] = $this->request->post['code'];
        }
        $log->write($this->session->data['language']);

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->request->post['redirect']) && $this->request->post['redirect'] == $server) {
            $log->write('in if');
            $this->response->redirect($server);

            return false;
        }

        $data['base'] = $server;
        $data['redirect'] = urldecode(http_build_query($url_data, '', '&'));

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/language_downdropdown.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/language_downdropdown.tpl', $data);
        } else {
            return $this->load->view('default/template/common/language_downdropdown.tpl', $data);
        }
    }

    public function parallel()
    {
        //echo "<pre>";print_r("er");die;
        $this->load->language('common/language');

        $data['text_language'] = $this->language->get('text_language');

        $data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

        $log = new Log('error.log');
        $log->write('language ss');

        $log->write($this->session->data['language']);

        //$this->session->data['language'] = 'portu';

        //$this->session->data['language'] = 'fr';
        $data['code'] = $this->session->data['language'];

        $this->load->model('localisation/language');

        $data['languages'] = [];

        $results = $this->model_localisation_language->getLanguages();

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = [
                    'name' => $result['name'],
                    'code' => $result['code'],
                    'image' => $result['image'],
                ];
            }
        }

        $url_data = $this->request->get;
        unset($url_data['lang']);
        unset($url_data['_path_']);

        if (!isset($url_data['path'])) {
            $url_data['path'] = 'common/home';
        }

        if (isset($this->request->post['code'])) {
            //die;
            $this->session->data['language'] = $this->request->post['code'];
        }
        $log->write($this->session->data['language']);

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->request->post['redirect']) && $this->request->post['redirect'] == $server) {
            $log->write('in if');
            $this->response->redirect($server);

            return false;
        }

        $data['base'] = $server;
        $data['redirect'] = urldecode(http_build_query($url_data, '', '&'));

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/language_parallel.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/language_parallel.tpl', $data);
        } else {
            return $this->load->view('default/template/common/language_parallel.tpl', $data);
        }
    }

    public function language()
    {
        //die;
        $log = new Log('error.log');
        $log->write('language set ');

        if (isset($this->request->post['code'])) {
            $this->session->data['language'] = $this->request->post['code'];
        }

        if (empty($this->request->post['redirect'])) {
            return;
        }

        parse_str(str_replace('&amp;', '&', $this->request->post['redirect']), $query);

        if ($this->config->get('config_seo_lang_code')) {
            $query['lang'] = $this->session->data['language'];
        }

        $path = $query['path'];
        unset($query['path']);

        $url = '&'.urldecode(http_build_query($query, '', '&'));

        $link = $this->url->link($path, $url, $this->request->server['HTTPS']);

        $log->write($link);

        if (empty($link)) {
            $link = 'index.php?path=common/home';
        }

        $this->response->redirect($link);
    }
}
