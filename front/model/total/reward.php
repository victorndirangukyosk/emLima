<?php

class ModelTotalReward extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = '')
    {
        $log = new Log('error.log');

        $log->write('getTotalreward');
        if (isset($this->session->data['reward'])) {
            $log->write('getTotalreward reward');
            $this->load->language('total/reward');
            $points = $this->customer->getRewardPoints();

            if ($this->session->data['reward'] > $total) {
                $this->session->data['reward'] = $total;
            }

            if ($this->session->data['reward'] <= $points && $this->session->data['reward'] <= $total) {
                $discount_total = $this->session->data['reward'] * $this->config->get('config_reward_value');

                $log->write($discount_total);
                $log->write($this->config->get('config_reward_value'));
                $log->write('config_reward_value');
                $log->write($store_id);

                if ($store_id) {
                    $log->write('store id if');

                    $numberOfStores = count($this->cart->getStores());

                    $log->write($numberOfStores);

                    $main_total = $this->cart->getSubTotal();

                    $store_total = $this->cart->getSubTotal($store_id);

                    $weightage = ($store_total * 100) / $main_total;

                    $log->write('numberOfStores '.$numberOfStores);

                    if (isset($this->session->data['reward_counter']) && $this->session->data['reward_counter'] >= ($numberOfStores - 1)) {
                        $give_reward = $discount_total - $this->session->data['reward_value'];

                        $log->write('last give_reward xx'.$give_reward);
                        $log->write($this->session->data['reward_counter']);
                        $log->write($this->session->data['reward_value']);

                        unset($this->session->data['reward_counter']);
                        unset($this->session->data['reward_value']);
                    } else {
                        $give_reward = (int) (($discount_total * $weightage) / 100);

                        $log->write('elsegive_reward '.$give_reward);

                        if (isset($this->session->data['reward_counter'])) {
                            ++$this->session->data['reward_counter'];
                        } else {
                            $this->session->data['reward_counter'] = 1;
                        }

                        if (isset($this->session->data['reward_value'])) {
                            $this->session->data['reward_value'] += $give_reward;
                        } else {
                            $this->session->data['reward_value'] = $give_reward;
                        }
                    }

                    //$give_reward = $discount_total;

                    $total_data[] = [
                        'code' => 'reward',
                        //'title' => sprintf($this->language->get('text_reward'), $this->session->data['reward']),
                        'title' => sprintf($this->language->get('text_reward'), $give_reward),

                        'value' => -$give_reward,
                        'sort_order' => $this->config->get('reward_sort_order'),
                    ];
                    $total -= $give_reward;
                } else {
                    $log->write('store id else');

                    $total_data[] = [
                        'code' => 'reward',
                        'title' => sprintf($this->language->get('text_reward'), $this->session->data['reward']),
                        'value' => -$discount_total,
                        'sort_order' => $this->config->get('reward_sort_order'),
                    ];
                    $total -= $discount_total;
                }
            }
        }
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = '', $args)
    {
        $log = new Log('error.log');

        $log->write('getTotalreward');
        //$log->write($args);

        //if ($store_id && false) {
        if ($store_id && isset($args['reward']) && $args['reward']) {
            $log->write('getTotalreward getApiTotal reward');
            $this->load->language('total/reward');
            $points = $this->customer->getRewardPoints();
            if ($args['reward'] <= $points) {
                $discount_total = $args['reward'] * $this->config->get('config_reward_value');

                //$numberOfStores = count($this->cart->getStores());
                $numberOfStores = count($args['stores']);

                $log->write($numberOfStores);

                //$main_total = $this->cart->getSubTotal();
                $main_total = $args['sub_total']; // final subtotal

                $store_total = $args['stores'][$store_id]['total']; //store sub total

                $weightage = ($store_total * 100) / $main_total;

                if (isset($args['reward_counter']) && $args['reward_counter'] >= ($numberOfStores - 1)) {
                    $give_reward = $discount_total - $args['reward_value'];

                    $log->write('last give_reward xx'.$give_reward);
                    $log->write($args['reward_counter']);
                    $log->write($args['reward_value']);
                } else {
                    $give_reward = (int) (($discount_total * $weightage) / 100);

                    $log->write('elsegive_reward '.$give_reward);

                    if (isset($args['reward_counter'])) {
                        ++$args['reward_counter'];
                    } else {
                        $args['reward_counter'] = 1;
                    }

                    if (isset($args['reward_value'])) {
                        $args['reward_value'] += $give_reward;
                    } else {
                        $args['reward_value'] = $give_reward;
                    }
                }

                $total_data[] = [
                    'code' => 'reward',
                    'title' => sprintf($this->language->get('text_reward'), $give_reward),
                    'value' => -$give_reward,
                    'sort_order' => $this->config->get('reward_sort_order'),
                ];

                $total -= $give_reward;
            }
        }
    }

    public function confirm($order_info, $order_total)
    {
        $log = new Log('error.log');

        $this->load->language('total/reward');

        $points = 0;

        $start = strpos($order_total['title'], '(') + 1;
        $end = strrpos($order_total['title'], ')');

        if ($start && $end) {
            $points = substr($order_total['title'], $start, $end - $start);
        }

        $log->write('points');
        if ($points) {
            $this->db->query('INSERT INTO '.DB_PREFIX."customer_reward SET customer_id = '".(int) $order_info['customer_id']."', order_id = '".(int) $order_info['order_id']."', description = '".$this->db->escape(sprintf($this->language->get('text_order_id'), (int) $order_info['order_id']))."', points = '".(float) -$points."', date_added = NOW()");
        }
    }

    public function unconfirm($order_id)
    {
        $log = new Log('error.log');

        $log->write('refund unconfirm ');
        $log->write($order_id);

        // $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "' AND points < 0");
        $this->db->query('DELETE FROM '.DB_PREFIX."customer_reward WHERE order_id = '".(int) $order_id."'");
    }
}
