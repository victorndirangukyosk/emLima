<?php

class ControllerModuleStore extends Controller
{
    public function index()
    {
        $status = true;

        if ($this->config->get('store_admin')) {
            $this->load->library('user');

            $this->user = new User($this->registry);

            $status = $this->user->isLogged();
        }

        if ($status) {
            $this->load->language('module/store');

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_store'] = $this->language->get('text_store');

            $data['store_id'] = $this->config->get('config_store_id');

            $data['stores'] = [];

            $data['stores'][] = [
                'store_id' => 0,
                'name' => $this->language->get('text_default'),
                'url' => $server.'index.php?path=common/home&session_id='.$this->session->getId(),
            ];

            $this->load->model('setting/store');

            $results = $this->model_setting_store->getStores();

            foreach ($results as $result) {
                $data['stores'][] = [
                    'store_id' => $result['store_id'],
                    'name' => $result['name'],
                    'url' => $result['url'].'index.php?path=common/home&session_id='.$this->session->getId(),
                ];
            }

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/module/store.tpl')) {
                return $this->load->view($this->config->get('config_template').'/template/module/store.tpl', $data);
            } else {
                return $this->load->view('default/template/module/store.tpl', $data);
            }
        }
    }
}
