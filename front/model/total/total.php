<?php

class ModelTotalTotal extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = '')
    {
        $this->load->language('total/total');

        $total_data[] = [
            'code' => 'total',
            'title' => $this->language->get('text_total'),
            'value' => max(0, $total),
            'sort_order' => $this->config->get('total_sort_order'),
        ];
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = '', $args)
    {
        $this->load->language('total/total');

        $total_data[] = [
            'code' => 'total',
            'title' => $this->language->get('text_total'),
            'value' => max(0, $total),
            'sort_order' => $this->config->get('total_sort_order'),
        ];
    }
}
