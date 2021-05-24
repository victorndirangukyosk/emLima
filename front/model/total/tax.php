<?php

class ModelTotalTax extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = '')
    {
        $log = new Log('error.log');
        foreach ($taxes as $key => $value) {
            $log->write('TAX');
            $log->write($key);
            $log->write($value);
            $log->write($total_data);
            $log->write($total);
            $log->write($taxes);
            $log->write($store_id);
            $log->write('TAX');
            if ($value > 0) {
                $total_data[] = [
                    'code' => 'tax',
                    'title' => $this->tax->getRateName($key),
                    'value' => $value,
                    'sort_order' => $this->config->get('tax_sort_order'),
                ];

                $total += $value;
            }
        }
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = '', $args)
    {
        foreach ($taxes as $key => $value) {
            if ($value > 0) {
                $total_data[] = [
                    'code' => 'tax',
                    'title' => $this->tax->getRateName($key),
                    'value' => $value,
                    'sort_order' => $this->config->get('tax_sort_order'),
                ];

                $total += $value;
            }
        }
    }
}
