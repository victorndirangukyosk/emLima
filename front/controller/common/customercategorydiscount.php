<?php

class ControllerCommonCustomercategorydiscount extends Controller {

    public function index($result) {

        $data = NULL;
        $cache_discount_data = $this->cache->get('category_discount_data');

        $category_s_discount = isset($cache_discount_data[$result['product_store_id'] . '_' . $this->customer->getCustomerDiscountCategory() . '_' . $result['store_id']]) ? $cache_discount_data[$result['product_store_id'] . '_' . $this->customer->getCustomerDiscountCategory() . '_' . $result['store_id']] : 0;
        $category_o_discount = isset($cache_discount_data[$result['product_store_id'] . '_' . $this->customer->getCustomerDiscountCategory() . '_' . $result['store_id']]) ? $cache_discount_data[$result['product_store_id'] . '_' . $this->customer->getCustomerDiscountCategory() . '_' . $result['store_id']] : 0;

        if ($category_s_discount != NULL && $category_s_discount > 0) {

            $discount_price = $result['special_price'] - ($result['special_price'] * ($category_s_discount / 100));

            $data['price'] = $result['price'];
            $data['special_price'] = $result['special_price'];
            $data['discount_price'] = $this->currency->format(sprintf("%.2f", $discount_price));
            $data['discount_percentage'] = sprintf("%.2f", $category_s_discount);
        } else {
            $discount_price = $result['special_price'] - ($result['special_price'] * ($category_s_discount / 100));

            $data['price'] = $result['price'];
            $data['special_price'] = $result['special_price'];
            $data['discount_price'] = $this->currency->format(sprintf("%.2f", $discount_price));
            $data['discount_percentage'] = sprintf("%.2f", $category_s_discount);
        }
        return $data;
    }

}
