
<?php
$rows = array_chunk($products, 4);

foreach ($rows as $row) { ?>

<div class="product-details-row">

    <?php foreach ($row as $product) { ?>
        
        <div class="col-md-3 product-box" id="product_<?= $product['product_store_id']; ?>">
            <div class="productlink">
                <img style="margin-top: 12.5px;" src="<?= $product['thumb']; ?>" class="jvimage" />
            </div>
            <div class="product-listings">
                <h5><?= $product['name']; ?></h5>
                <span class="prod-sizeingm" style="display: none;">500 gm</span>
                <div class="form-details" style="display: none;">
                    <div class="selectdropbox">
                        <select class="selectpicker show-menu-arrow"></select>
                    </div>
                </div>
                <?php if ($product['variations']) { ?>

                <div class="form-details">
                    <div class="selectdropbox">
                        <div class="btn-group bootstrap-select">
                            <button data-toggle="dropdown" class="btn dropdown-toggle form-control selectpicker btn-default" type="button" title="50.0 ml">
                                <span class="filter-option pull-left"><?= $product['name']; ?></span>&nbsp;<span class="caret"></span>
                            </button>
                            <div class="dropdown-menu open">
                                <ul role="menu" class="dropdown-menu inner selectpicker">
                                    <li data-original-index="0">
                                        <a data-product-id="<?= $product['product_store_id']; ?>" data-varition-id="0" class="" tabindex="0">
                                            <span class="text"><?= $product['name']; ?></span>
                                            <span class="glyphicon glyphicon-ok check-mark"></span>
                                        </a>
                                    </li>
                                    <?php foreach ($product['variations'] as $variation) { ?>
                                    
                                    <li data-original-index="0">
                                        <a data-product-id="<?= $product['product_store_id']; ?>" data-variation-id="<?= $variation['product_variation_store_id']; ?>" class="" tabindex="0">
                                            <span class="text"><?= $variation['name']; ?></span>
                                            <span class="glyphicon glyphicon-ok check-mark"></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <div class="homeprice">
                    <span class="bold homeprice-rate">
                        <?php if (!$product['special']) { ?>
                            <?php echo $product['price']; ?>
                        <?php } else { ?>
                            <span class="price-new">
                                <?php echo $product['special']; ?>
                            </span>
                            <span class="price-old">
                                <?php echo $product['price']; ?>
                            </span>
                        <?php } ?>
                        <?php if ($product['tax']) { ?>
                            <span class="price-tax">
                                <?php echo $text_tax; ?>
                                <?php echo $product['tax']; ?>
                            </span>
                        <?php } ?>
                    </span>
                </div>
            </div>
            
            <div class="pro-qty-addbtn" data-variation-id="<?= $product['product_variation_store_id']; ?>" id="action_<?= $product['product_variation_store_id']; ?>">

                <?php require 'action.tpl'; ?>
                
            </div>
        </div><!-- END .col-md-3 -->                                    

    <?php } ?>
        
</div><!-- END .product-details-row -->

<?php } ?>
