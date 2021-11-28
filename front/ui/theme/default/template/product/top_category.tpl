<?php echo $header; ?>


<div class="page-container">
    <div class="shopping">
        <div class="container-fluid">
            <div class="col-md-2 sidebar">
                <div class="sticky affix-top">
                    <div style="height: 485px;" class="classification-tree-container">
                        <div style="display: block;" class="classification-tree">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 contents">

                <?php if($warning){ ?>               
                <div class="alert alert-warning" style="margin-bottom: 0px;margin-top: 20px;">
                    <?= $warning ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                
                <div style="display: block;" class="shopping-container">

                    <?php foreach($categories as $category){ ?>
                    
                    <?php if($category['products']){ ?>
                    
                    <div class="products-rows">
                        <div class="v4scroll-inner">
                            <div class="products-heading-rows">
                                <h2>
                                    <?= $category['name'] ?>
                                    <span class="deskview">
                                        <a href="<?= $category['href'] ?>" id="view-all"><?= $text_view ?></a>
                                    </span>
                                    <a href="<?= $category['href'] ?>" id="view-all" class="btn-view-all tab-view"><?= $text_view ?></a>
                                </h2>
                            </div>
                            <div class="product-container-fluid">
                                
                                <?php $products = $category['products'] ?>
                                <?php print_r( $products ); exit; ?>
                                <?php require(DIR_BASE.'front/ui/theme/default/template/product/product_collection.php'); ?>
                                
                            </div><!-- END .product-container-fluid -->
                        </div>
                    </div>
                    
                    <?php } ?>
                    
                    <?php } ?>
                                
                </div>
            </div>
        </div>
        <div class="special-request-container"></div>       
    </div>
</div>

<?php echo $footer; ?>

<style>
.cat-list > li:hover  .drop-menu-2  {
  display: block;
}
.drop-menu-2 li:hover .drop-menu-3{
    display: block;
}
</style>