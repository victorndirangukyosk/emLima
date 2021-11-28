<?php

$rows = array_chunk($products, 4);

foreach ($rows as $row) { ?>

<div class="product-details-row">

    <?php foreach ($row as $product) { ?>
        <div class="col-md-3 product-box" id="product_<?= $product['product_store_id']; ?>">
            <div class="product_block" data-id="<?= $product['product_store_id']; ?>" >
                <div class="productlink">
               
                <img style="margin-top: 12.5px;" src="<?= $product['thumb']; ?>" class="jvimage" />
            </div>
            <div class="product-listings" >
                <h5 class="product_name"><?= $product['name']; ?></h5>

                <span class="product_unit" style="color:grey;"> <?= $product['unit']; ?></span>

                <div class="form-details" style="display: none;">
                    <div class="selectdropbox">
                        <select class="selectpicker show-menu-arrow"></select>
                    </div>
                </div>
                <div class="homeprice">
                    <span class="bold homeprice-rate">
                        <?php if ('0.00' == $product['special']) { ?>
                            <?php echo $product['price']; ?>
                        <?php } else { ?>
                            <span class="price-new">
                                <?php echo $product['special']; ?>
                            </span>
                            <span class="price-old">
                                <?php echo $product['price']; ?>
                            </span>
                        <?php } ?>
                            <!-- //if ($product['tax']) { -->
                            <!--  <span class="price-tax">
                                <?php //echo $text_tax;?>
                                <?php //echo $product['tax'];?>
                            </span> -->
                             <!-- // } -->
                    </span>
                </div>
            </div>
            </div>
            
            
            <div class="pro-qty-addbtn" data-variation-id="<?= $product['store_product_variation_id']; ?>" id="action_<?= $product['product_store_id']; ?>">

                <?php require 'action.tpl'; ?>
                
            </div>
        </div><!-- END .col-md-3 -->                                    

    <?php } ?>
        
</div><!-- END .product-details-row -->

<?php } ?>


