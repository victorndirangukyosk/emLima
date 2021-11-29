
<div style="display: inline-block; text-align: center;" class="qtybtns-addbtnd" id="controller-container">
    
    <?php if ($product['qty_in_cart']) { ?>                                            
        <div class="inc-dec-quantity" id="<?= $product["product_store_id"] ?>-<?= $product['store_product_variation_id'] ?>">
            <div class="minus-quantity" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-minus"></i>
                </span>
            </div>
            <div class="middle-quantity" id='<?= $product["key"] ?>'><?= $product['qty_in_cart'] ?></div>                                                    
            <div class="plus-quantity" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-plus"></i>
                </span>
            </div>
        </div>
        <p class="info"><?php if(isset($text_incart)) $text_incart ?></p>       
        <p class="error-msg" ></p>
        
    <?php } else { ?>

        <div class="inc-dec-quantity" id="<?= $product["product_store_id"] ?>-<?= $product['store_product_variation_id'] ?>">
            <div class="minus-quantity" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-minus"></i>
                </span>
            </div>
            <div class="middle-quantity">1</div>                                                    
            <div class="plus-quantity" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-plus"></i>
                </span>
            </div>
        </div>
        <p class="info" style="display: none;"><?php if(isset($text_incart)) $text_incart ?></p>
        <p class="error-msg" ></p>
        
    <?php } ?> 
</div>

<div style="display: inline-block;" class="qtybtns-addbtnd" id="add-btn-container">    
            <a style="display: <?= $product['actualCart'] ? 'none' : 'block'; ?>" class="add-cart-btn btn-add btn-orange-addbtn" id="add-btn" data-variation-id="<?= $product['store_product_variation_id'] ?>" data-id="<?= $product['product_store_id'] ?>">
            <?php if(isset($button_add)) $button_add ?>
                    <span>
                        <i class="fa fa-shopping-cart"></i>
                    </span>
            </a>
</div>
