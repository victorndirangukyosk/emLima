

<div class="sp-quantity" class="qtybtns-addbtnd" id="controller-container">
    
    <?php if ($product['qty_in_cart']) { ?> 

    <a class=" AtcButton_container_popup AtcButton__container___1RZ9c AtcButton__with_counter___3YxLq atc_<?= $product['product_store_id'] ?> AtcButton__small___1a1kH">

    <span class="AtcButton__button_text___VoXuy unique_add_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="add-btn" data-variation-id="<?= $product['store_product_variation_id'] ?>" data-id="<?= $product['product_store_id'] ?>" style="display: <?= $product['qty_in_cart'] ? 'none' : 'block'; ?>"><?php if(isset($button_add)) echo $button_add ?></span>

        <span class="AtcButton__decrement_button___2ov_L minus-quantity unique_minus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="4" viewBox="0 0 12 2"><path d="M0 0h14v2H0z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

        <span class="AtcButton__counter___iR7_X middle-quantity unique_middle_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" id="YToyOntzOjE2OiJwcm9kdWN0X3N0b3JlX2lkIjtpOjI3MDM0O3M6ODoic3RvcmVfaWQiO3M6MToiMiI7fQ==" style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>"><?= $product['qty_in_cart']?></span>

        <span class="AtcButton__increment_button___3j3y8 plus-quantity unique_plus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-minimum='<?= $product["minimum"] ?>' data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="16" viewBox="0 0 12 12"><path d="M5 12V7H0V5h5V0h2v5h5v2H7v5z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

     </a>
    
    <?php } else { ?>
    

        <a class=" AtcButton_container_popup AtcButton__container___1RZ9c AtcButton__with_text___4C5OY atc_<?= $product['product_store_id'] ?> AtcButton__small___1a1kH">

        <span class="AtcButton__button_text___VoXuy unique_add_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="add-btn" data-variation-id="<?= $product['store_product_variation_id'] ?>" data-id="<?= $product['product_store_id'] ?>" style="display: <?= $product['qty_in_cart'] ? 'none' : 'block'; ?>"><?php if(isset($button_add)) echo $button_add ?></span>

        <span class="AtcButton__decrement_button___2ov_L minus-quantity unique_minus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="4" viewBox="0 0 12 2"><path d="M0 0h14v2H0z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

        <span class="AtcButton__counter___iR7_X middle-quantity unique_middle_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" id="YToyOntzOjE2OiJwcm9kdWN0X3N0b3JlX2lkIjtpOjI3MDM0O3M6ODoic3RvcmVfaWQiO3M6MToiMiI7fQ==" style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">1</span>

        <span class="AtcButton__increment_button___3j3y8 plus-quantity unique_plus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-minimum='<?= $product["minimum"] ?>' data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="16" viewBox="0 0 12 12"><path d="M5 12V7H0V5h5V0h2v5h5v2H7v5z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

        </a>

     <?php } ?>  
</div>

