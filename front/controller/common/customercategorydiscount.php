<?php

class ControllerCommonCustomercategorydiscount extends Controller {

    public function index($data) {

        $cacheDiscount_data = $this->cache->get('category_discount_data');
        $log = new Log('error.log');

        $category_discount = 0;
        $new_special_price = 0;
        $product_price = [];

        if (CATEGORY_DISCOUNT_ENABLED == true && isset($cacheDiscount_data) && isset($cacheDiscount_data[$data['product_store_id'] . '_' . $this->customer->getCustomerDiscountCategory() . '_' . $data['store_id']])) {
            $category_discount = $cacheDiscount_data[$data['product_store_id'] . '_' . $this->customer->getCustomerDiscountCategory() . '_' . $data['store_id']];

            if ($category_discount != NULL && $category_discount > 0) {
                $discount_amount = ($category_discount / 100) * $data['special_price'];
                $new_special_price = $data['special_price'] - $discount_amount;
            }

            $product_price['discount_percentage'] = $category_discount;
            $product_price['discount_amount'] = $new_special_price;
            $product_price['special_price'] = $data['special_price'];

            return $product_price;
        }
    }

}
