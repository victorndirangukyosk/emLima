<?php

class ControllerInformationHelp extends Controller
{
    public function index()
    {
        $this->load->language('information/help');

        $this->load->model('information/help');

        if (isset($this->request->get['category_id'])) {
            $category_id = (int) $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }
        $category_info = $this->model_information_help->getCategory($category_id);

        //show category home
        if ($category_info) {
            $this->category($category_info);
        } else {
            $this->home();
        }
    }

    public function search()
    {
        $this->load->language('information/help');

        $this->load->model('information/help');

        $data['heading_title'] = $title = $this->language->get('heading_title');

        $data['label_text'] = $this->language->get('label_text');

        $data['text_submit'] = $this->language->get('text_submit');
        $data['text_help_center'] = $this->language->get('text_help_center');
        $data['text_help'] = $this->language->get('text_help');
        $data['text_search'] = $this->language->get('text_search');

        $this->document->setTitle($title);

        $this->document->addStyle('front/ui/theme/mvg/stylesheet/layout_help.css');

        if (isset($this->request->get['q'])) {
            $q = $this->request->get['q'];
        } else {
            $q = null;
        }

        $data['result'] = $this->model_information_help->searchData($q);

        $data['header'] = $this->load->controller('common/header/help');

        $data['help'] = $this->url->link('information/help');

        $data['q'] = $q;

        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['reportissue_modal'] = $this->load->controller('information/reportissue');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/help_search.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/help_search.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/help_search.tpl', $data));
        }
    }

    //list cateogries

    public function home()
    {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $title = $this->language->get('heading_title');

        $this->document->setTitle($title);

        $data['breadcrumbs'][] = [
            'text' => $title,
            'href' => $this->url->link('information/help'),
        ];

        $data['heading_title'] = $title;
        $data['label_text'] = $this->language->get('label_text');
        $data['text_submit'] = $this->language->get('text_submit');

        $categories = $this->model_information_help->getCategories();

        //echo "<pre>";print_r($categories);die;
        if ($categories) {
            $data['categories'] = array_chunk($categories, 3);
        } else {
            $data['categories'] = [];
        }

        //echo "<pre>";print_r($categories);die;

        if (is_file(DIR_IMAGE.$this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server.'image/'.$this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/help');

        $data['search'] = $this->url->link('information/help/search');

        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['reportissue_modal'] = $this->load->controller('information/reportissue');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/help.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/help.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/help.tpl', $data));
        }
    }

    //list cateogry question/answer

    public function category($category_info)
    {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $title = empty($category_info['name']) ? $category_info['name'] : $category_info['name'];

        $data['title'] = $title;
        $this->document->setTitle($title);

        if (is_file(DIR_IMAGE.$this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server.'image/'.$this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        $data['label_text'] = $this->language->get('label_text');

        $data['text_submit'] = $this->language->get('text_submit');
        $data['text_help_center'] = $this->language->get('text_help_center');
        $data['text_help'] = $this->language->get('text_help');
        $data['text_search'] = $this->language->get('text_search');

        $data['breadcrumbs'][] = [
            'text' => $category_info['name'],
            'href' => $this->url->link('information/help', 'category_id='.$category_info['category_id']),
        ];

        $data['heading_title'] = $category_info['name'];

        $data['result'] = $this->model_information_help->getData($category_info['category_id']);

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/help');

        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['reportissue_modal'] = $this->load->controller('information/reportissue');

        $data['help'] = $this->url->link('information/help');
        $data['search'] = $this->url->link('information/help/search');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/help_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/help_list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/help_list.tpl', $data));
        }
    }
}
