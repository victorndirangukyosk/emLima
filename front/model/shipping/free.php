<?php

class ModelShippingFree extends Model
{
    public function getQuote()
    {
        $this->load->language('shipping/free');

        $status = true;

        if ($this->cart->getSubTotal() < $this->config->get('free_total')) {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $quote_data = [];

            $quote_data['free'] = [
                'code' => 'free.free',
                'title' => $this->language->get('text_description'),
                'cost' => 0.00,
                'tax_class_id' => 0,
                'text' => $this->currency->format(0.00),
            ];

            $method_data = [
                'code' => 'free',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('free_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }

    public function getApiQuote()
    {
        $this->load->language('shipping/free');

        $status = true;

        if ($this->cart->getSubTotal() < $this->config->get('free_total')) {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $quote_data = [];

            $quote_data['free'] = [
                'code' => 'free.free',
                'title' => $this->language->get('text_description'),
                'cost' => 0.00,
                'tax_class_id' => 0,
                'text' => $this->currency->format(0.00),
            ];

            $method_data = [
                'code' => 'free',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('free_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }
}
