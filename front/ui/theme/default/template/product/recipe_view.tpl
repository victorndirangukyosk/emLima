<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content" style="padding: 0">
            <div class="modal-body" style="padding: 0">
                <div style="background-image: url(image/<?= $recipe['image'] ?>);" class="instacart-list-header">
                    
                    <?php if($recipe['video']){ ?>
                    <div class="instacart-list-header-links">
                        <a target="_blank" class="instacart-list-favorite" href="<?= $recipe['video'] ?>">
                            <div class="fa fa-video-camera"></div>
                            <div class="text">View on utube</div>
                        </a>
                    </div>
                    <?php } ?>
                    
                    <div class="instacart-list-header-details">
                        <div class="instacart-list-header-title">
                            <div class="instacart-list-view-mode">
                                <?= $recipe['title'] ?>
                            </div>
                        </div>
                        <div class="instacart-list-header-autor">
                            <a class="instacart-list-header-name">
                                by <?= $recipe['author'] ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="instacart-list-tab-containers row">
                    
                    <div class="col-lg-12">

                        <div class="col-lg-6 instacart-list-container-products instacart-list-tab-content-active instacart-list-tab-content">
                            <div class="instacart-list-short-description">
                                <div class="instacart-list-view-mode">
                                    <br />
                                    <p><?= $recipe['description'] ?></p>
                                </div>
                            </div>
                            <div class="instacart-list-products">                                                  
                                <?php foreach($products as $product){ ?>
                                <div class="instacart-list-product instacart-list-product-has-children">
                                    <div class="instacart-list-product-content">
                                        <div class="instacart-list-product-details">
                                            <div class="instacart-list-product-image">
                                                <img src="<?= $product['thumb'] ?>"></div>
                                            <div class="instacart-list-product-name">                                                
                                                <span class="name"><?= $product['name'] ?></span>
                                            </div>
                                            <div class="instacart-list-product-size">
                                                <span class="size"><?= $product['quantity'] ?></span>
                                            </div>
                                        </div>
                                        <?php if($product['items']){ ?>
                                        <a class="instacart-list-product-toggle">
                                            <div class="fa fa-chevron-down"></div>
                                        </a>
                                        <?php } ?>
                                    </div>
                                    <div class="instacart-list-products-children">
                                        <?php foreach($product['items'] as $item){ ?>
                                        <div data-product-id="<?= $item['product_id'] ?>" class="instacart-list-product">
                                            <div class="instacart-list-product-content">
                                                <div class="instacart-list-product-details">
                                                    <div class="instacart-list-product-image">
                                                        <img src="<?= $item['thumb'] ?>">
                                                        <?php if($item['qty_in_cart']){ ?>
                                                            <span class="incart">
                                                                 <?php echo $item['qty_in_cart']; ?>    
                                                            </span>
                                                        <?php }else{ ?>
                                                            <span class="incart" style="display: none"></span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="instacart-list-product-name">
                                                        <?= $item['name'] ?>
                                                    </div>
                                                    <div class="instacart-list-product-size">
                                                        <?php if($item['special']){ ?>
                                                            <span style="text-decoration: line-through"> <?= $item['price'] ?></span>
                                                            <span class="price-new"> <?= $item['special'] ?></span>
                                                        <?php } else{ ?>                                                            
                                                            <span class="price"> <?= $item['price'] ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="instacart-list-adding-and-removing">
                                                    
                                                    <?php if($item['qty_in_cart']){ ?>
                                                    <a class="instacart-list-product-remove-from-cart" data-key='<?= $item["key"] ?>'>
                                                        <div class="fa fa-minus"></div>
                                                    </a>
                                                    <?php }else{ ?>
                                                    <a class="instacart-list-product-remove-from-cart" data-key='' style="display: none;">
                                                        <div class="fa fa-minus"></div>
                                                    </a>
                                                    <?php } ?>
                                                    
                                                    <a class="instacart-list-product-add" data-key='<?= $item["key"] ?>'>
                                                        <div class="fa fa-plus"></div>
                                                        Add
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-lg-6 instacart-list-directions instacart-list-tab-content">
                            <div class="instacart-list-view-mode">
                                <div class="instacart-list-description">
                                    <h4>Directions:</h4>
                                    <?php  echo html_entity_decode($recipe['directions']) ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
    .categories-list{
        z-index: 100;
    }
</style>