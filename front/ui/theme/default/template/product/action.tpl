
<div style="display: inline-block;" class="qtybtns-addbtnd" id="controller-container">

    <?php if ($product['qty_in_cart']) { ?>                                            
        <div class="inc-dec-quantity">
            <div class="minus-quantity" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-minus"></i>
                </span>
            </div>
            <div class="middle-quantity"><?= $product['qty_in_cart'] ?></div>                                                    
            <div class="plus-quantity" data-key='<?= $product["key"] ?>' data-minimum='<?= $product["minimum"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-plus"></i>
                </span>
            </div>
        </div>
        <p class="info"><?= $text_incart ?></p>       

    <?php } else { ?>

        <div class="inc-dec-quantity">
            <div class="minus-quantity" data-key="" data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-minus"></i>
                </span>
            </div>
            <div class="middle-quantity">1</div>                                                    
            <div class="plus-quantity" data-key="" data-minimum='<?= $product["minimum"] ?>' data-id='<?= $product["product_store_id"] ?>'>
                <span>
                    <i class="fa fa-plus"></i>
                </span>
            </div>
        </div>
        <p class="info" style="display: none;"><?= $text_incart ?></p>
    <?php } ?> 
</div>

<div style="display: inline-block;" class="qtybtns-addbtnd" id="add-btn-container">                                            
    <a style="display: <?= $product['qty_in_cart'] ? 'none' : 'true'; ?>" class="btn btn-primary btn-sm add-cart-btn" id="add-btn" data-variation-id="<?= $product['store_product_variation_id'] ?>" data-id="<?= $product['product_store_id'] ?>">
        <?= $button_add ?>
        <span>
            <i class="fa fa-shopping-cart"></i>
        </span>
    </a>
</div>