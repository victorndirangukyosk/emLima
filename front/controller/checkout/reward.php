<?php

class ControllerCheckoutReward extends Controller
{
    public function index()
    {
        $points = $this->customer->getRewardPoints();

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        if ($points && $points_total && $this->config->get('reward_status')) {
            $this->load->language('checkout/reward');

            $data['heading_title'] = sprintf($this->language->get('heading_title'), $points);

            $data['text_loading'] = $this->language->get('text_loading');

            $data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);

            $data['button_reward'] = $this->language->get('button_reward');

            if (isset($this->session->data['reward'])) {
                $data['reward'] = $this->session->data['reward'];
            } else {
                $data['reward'] = '';
            }

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/reward.tpl')) {
                return $this->load->view($this->config->get('config_template').'/template/checkout/reward.tpl', $data);
            } else {
                return $this->load->view('default/template/checkout/reward.tpl', $data);
            }
        }
    }

    public function reward()
    {
        $this->load->language('checkout/reward');

        $json = [];

        $points = $this->customer->getRewardPoints();

        if (empty($this->request->post['reward'])) {
            $json['error'] = $this->language->get('error_reward');
        }

        if ($this->request->post['reward'] > $points) {
            $json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
        }

        if (!$json) {
            $total = 0;

            $total = $this->load->controller('checkout/totals/totalData');
            $this->load->language('checkout/reward');

            /*foreach ($order_total['totals'] as $key => $value) {
                if (strpos($value['title'], 'Reward') !== false) {
                    $total = abs($value['value']);
                    break;
                }

            }*/
            //echo "<pre>";print_r($order_total);die;
            //echo "<pre>";print_r($total);die;
            if ($total && abs($this->request->post['reward']) > $total) {
                $this->session->data['reward'] = $total;
                $this->request->post['reward'] = $total;
            }

            $this->session->data['reward'] = abs($this->request->post['reward']);

            $json['success'] = sprintf($this->language->get('text_success'), $this->request->post['reward'], $this->customer->getRewardPoints());
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
