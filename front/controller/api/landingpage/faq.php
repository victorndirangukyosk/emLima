<?php

class ControllerApiLandingpagefaq extends Controller
{
    private $error = []; 
     
    public function getFaq() {
        $json['categories'] = [];

        $this->load->model('catalog/help');
        $questions = $this->model_catalog_help->getHelps();
        $categories = $this->model_catalog_help->getCategories();

        foreach ($categories as $category) {
            $categoryQuestions = [];

            foreach($questions as $question) {
                if ($question['category_id'] == $category['category_id']) {
                    $categoryQuestions[] = $question;
                }
            }

            $json['categories'][] = [
                'category_id' => $category['category_id'],
                'category' => $category['name'],
                'questions' => $categoryQuestions
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
