<?php

class ControllerApiLandingpagefaq extends Controller
{
    private $error = []; 
     
    public function getFaq() {
        $json = [];
        // $json['status'] = 200;
        // $json['data'] = [];
        // $json['message'] = [];
        $this->load->model('catalog/help');
        $questions = $this->model_catalog_help->getHelps();
        $categories = $this->model_catalog_help->getCategories();

        foreach ($categories as $category) {
            $json[$category['category_id']]['category'] = $category['name'];
        }

        foreach ($questions as $question) {
            $json[$question['category_id']]['questions'][] = $question;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
