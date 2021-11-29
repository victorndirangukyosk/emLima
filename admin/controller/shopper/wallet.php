<?php

class ControllerShopperWallet extends Controller {

    public function index() {
        
        $this->load->language('shopper/shopper');

        $this->load->model('shopper/shopper');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_wallet'] = $this->language->get('text_wallet');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_order_id'] = $this->language->get('column_order_id');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = array();

        $results = $this->model_shopper_shopper->getCredits($this->user->getId(), ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['credits'][] = array(
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'order_id' => $result['order_id'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $data['balance'] = $this->currency->format($this->model_shopper_shopper->getCreditTotal($this->user->getId()), $this->config->get('config_currency'));

        $credit_total = $this->model_shopper_shopper->getTotalCredits($this->user->getId());

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('shopper/wallet', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->user->getId() . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));
        
        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');
        
        $this->response->setOutput($this->load->view('shopper/credit.tpl', $data));
    }
}