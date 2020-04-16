
<div class="sp-quantity product-quantity" class="mini-qtybtns-addbtnd" id="controller-container">
    <?php if ($product['quantity']) { ?> 
    <div class="inc-dec-quantity" id="<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>">           
        <input type="button" class="sp-minus fff mini-minus-quantity ddd" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' id="minus" value="-"/>
        <span class="sp-input middle-quantity quntity-input product-count" id='<?= $product["key"] ?>'>
            <?= $product['quantity'] ?>
        </span>

        
        <?php if ($product['quantity'] >= $product['minimum']) { ?> 
            <span data-tooltip="Maximum quantity per order for this product reached">
                <input type="button" class="sp-plus fff mini-plus-quantity ddd" data-minimum='<?= $product["minimum"] ?>' data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' id="plus"  value="+"/>
            </span>
        <?php } else { ?>
            
                <input type="button" class="sp-plus fff mini-plus-quantity ddd" data-minimum='<?= $product["minimum"] ?>' data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' id="plus"  value="+"/>
           
        <?php  } ?>
        
    </div>
    <p class="error-msg" ></p>
    <?php } ?>
    
</div>


