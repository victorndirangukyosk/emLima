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
                                <?php require(DIR_BASE.'front/ui/theme/mvg/template/product/product_collection.php'); ?>
                                
                            </div><!-- END .product-container-fluid -->
                        </div>
                    </div>
                    
                    <?php } ?>
                    
                    <?php } ?>
                                
                </div>
            </div>
        </div>
        <div class="special-request-container"></div>   
        <div class="modal-wrapper"></div>     
    </div>
</div>

<?php echo $footer; ?>
<?php if ($not_delivery): ?>

<div id="notallowed-others" class="modal fade " aria-hidden="true">
    <div class="modal-dialog  modal-sm">
        <div class="modal-content" style="height:150px">
            <div class="modal-header">
                <h4 class="modal-title"><?= $error_no_delivery ?></h4>
            </div>
            <div class="modal-body text-center">
                <a href="#" id="clearcart" class="btn btn-danger btn-lg"><?= $button_clear_cart ?></a>
                <a href="index.php?path=checkout/checkout" class="btn btn-success btn-lg"><?= $button_checkout ?></a>
            </div><!-- END .modal-body -->
        </div><!-- END .modal-content -->
    </div><!-- END .modal-dialog -->
</div>
<script type="text/javascript">
 $('#notallowed-others').modal({backdrop: 'static', keyboard: false}); 
 $(document).delegate('#clearcart', 'click', function(){

        console.log("clearcart");
        $.ajax({
            url: 'index.php?path=checkout/cart/clear_cart',
            type: 'post',
            data:'',
            dataType: 'json',
            success: function(json) {
                if (json['location']) {
                    location = json.redirect;
                    location = location;
                }

            }
        });

    });
</script>
<?php endif ?>

<script type="text/javascript">
$(document).ready(function() {
    console.log("ready in top_category");
    $(document).delegate('.product_block', 'click', function(){
        console.log("product blocks"+$(this).attr('data-id'));
        $.get('index.php?path=product/product/view&product_store_id='+$(this).attr('data-id'), function(data){
            $('.modal-wrapper').html(data);
            $('.modal').modal('show')
        });
    });
});

</script>
<style>
.cat-list > li:hover  .drop-menu-2  {
  display: block;
}
.drop-menu-2 li:hover .drop-menu-3{
    display: block;
}
</style>