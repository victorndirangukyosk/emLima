<?php

class ModelShippingPickup extends Model
{
    public function getQuote($cost, $name)
    {
        $this->load->language('shipping/pickup');

        $status = true;

        $method_data = [];

        if ($status) {
            $quote_data = [];

            $quote_data['pickup'] = [
                'code' => 'pickup.pickup',
                'title' => $this->language->get('text_description').'-'.$name,
                'title_with_store' => $this->language->get('text_description').'-'.$name,
                'cost' => 0.00,
                'actual_cost' => 0.00,
                'tax_class_id' => 0,
                'text' => $this->currency->format(0.00),
            ];

            $method_data = [
                'code' => 'pickup',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('pickup_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }

    public function getApiQuote($cost = '', $name = '', $subtotal, $total)
    {
        $this->load->language('shipping/pickup');

        $status = true;

        $method_data = [];

        if ($status) {
            $quote_data = [];

            $quote_data['pickup'] = [
                'code' => 'pickup.pickup',
                'title' => $this->language->get('text_description').'-'.$name,
                'title_with_store' => $this->language->get('text_description').'-'.$name,
                'actual_cost' => 0.00,
                'cost' => 0.00,
                'tax_class_id' => 0,
                'text' => $this->currency->format(0.00),
            ];

            $method_data = [
                'code' => 'pickup',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('pickup_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }
}
