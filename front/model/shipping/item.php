<?php

class ModelShippingItem extends Model
{
    public function getQuote()
    {
        $this->load->language('shipping/item');

        $status = true;

        $method_data = [];

        if ($status) {
            $items = 0;

            foreach ($this->cart->getProducts() as $product) {
                if ($product['shipping']) {
                    $items += $product['quantity'];
                }
            }

            $quote_data = [];

            $quote_data['item'] = [
                'code' => 'item.item',
                'title' => $this->language->get('text_description'),
                'cost' => $this->config->get('item_cost') * $items,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text' => $this->currency->format($this->tax->calculate($this->config->get('item_cost') * $items, $this->config->get('item_tax_class_id'), $this->config->get('config_tax'))),
            ];

            $method_data = [
                'code' => 'item',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('item_sort_order'),
                'error' => false,
            ];
        }

        return $method_data;
    }
}
