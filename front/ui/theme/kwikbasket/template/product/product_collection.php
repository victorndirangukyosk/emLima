<?php
$rows = array_chunk($products, 6);

foreach ($rows as $row) { ?>
<?php foreach ($row as $product) { ?>


    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 nopadding product-details" style="border-right: 1px solid rgb(215, 220, 214);">

        <div>
                
            <?php /*echo "<pre>";print_r($product);die;*/ if (isset($product['percent_off']) && '0.00' != $product['percent_off']) { ?>

                <span class="spacial-offer"> <?php echo $product['percent_off'].'% OFF'; ?></span>
            <?php } ?>
                

            <?php if ($this->customer->isLogged()) { ?>
            

            <a href="#" class="add-to-list list_button<?= $product['product_store_id']; ?>-<?= $product['store_product_variation_id']; ?>" id="list-btn" data-id="<?= $product['product_id']; ?>"
             type="button" data-toggle="modal" data-target="#listModal"  ><img class="add-list-png"   src="<?= $base; ?>front/ui/theme/mvgv2/images/list-icon.png">
             </a>

            <?php } else { ?>
                <a href="#"  class="add-to-list" type="button" data-toggle="modal" data-target="#phoneModal"><img class="add-list-png" src="<?= $base; ?>front/ui/theme/mvgv2/images/list-icon.png"></a>

            <?php } ?>
        </div>
        <div class="product-block"  data-id="<?= $product['product_store_id']; ?>">

            <div class="product-img product-description open-popup" data-id="<?= $product['product_store_id']; ?>" data-id="<?= $product['product_store_id']; ?>">
                <img class="lazy" data-src="<?= $product['thumb']; ?>" alt="">
            </div>
            <div class="product-description" data-id="<?= $product['product_store_id']; ?>">
                

                <h3 class="open-popup" data-id="<?= $product['product_store_id']; ?>">

                    <a class="product-title"><?= $product['name']; ?></a>
                </h3>

                <?php if (trim($product['unit'])) { ?>
                    <p class="product-info open-popup" data-id="<?= $product['product_store_id']; ?>"><span class="small-info"><?= $product['unit']; ?></span></p>
                <?php } else { ?>
                    <p class="product-info open-popup" data-id="<?= $product['product_store_id']; ?>"><span class="small-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                <?php } ?>

                <div class="product-price">
                    <?php if ('0.00' == $product['special'] || empty(trim($product['special']))) { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id']; ?>" style="display: none";>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id']; ?>">
                            <?php echo $product['price']; ?>
                        </span>
                    <?php } else { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id']; ?>">
                            <?php echo $product['price']; ?>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id']; ?>">
                            <?php echo $product['special']; ?>
                        </span>
                    <?php } ?>
                    <div class="pro-qty-addbtn" data-variation-id="<?= $product['store_product_variation_id']; ?>" id="action_<?= $product['product_store_id']; ?>">
                        
                        <?php require 'action.tpl'; ?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
<?php } ?>  

<?php } ?>

<script type="text/javascript">
    $('.product-details').last().css({'border-right': '1px solid #d7dcd6'});
</script>