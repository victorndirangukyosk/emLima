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
                <div style="display: block;" class="shopping-container">
                    <div class="products-rows">
                        <div class="v4scroll-inner">
                            <div class="products-heading-rows">
                                <h2>
                                    <?= $heading_title ?>
                                </h2>
                            </div>
                            <div class="product-container-fluid">
                                
                                <?php require(DIR_BASE.'front/ui/theme/mvg/template/product/product_collection.php'); ?>
               
                                <div style='display: none;'>
                                    <?= $pagination ?>
                                </div>
                                
                            </div><!-- END .product-container-fluid -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="special-request-container"></div>  
        <div class="modal-wrapper"></div>      
    </div>
</div>

<?php echo $footer; ?>

<script type="text/javascript" src="front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>

<script type="text/javascript">
$(document).ready(function() {
    var $container = $('.product-container-fluid');
    $container.infinitescroll({
        animate:true,
        navSelector  : '.pagination',    // selector for the paged navigation 
        nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.product-details-row',     // selector for all items you'll retrieve
        loading: {
            finishedMsg: 'No more products to load.',
            msgText: 'Loading...',
            img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'
            
        }
    });			
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    console.log("ready in category not");
    $(document).delegate('.product_block', 'click', function(){
        console.log("product block category not top category"+$(this).attr('data-id'));
        $.get('index.php?path=product/product/view&product_store_id='+$(this).attr('data-id'), function(data){
            $('.modal-wrapper').html(data);
            $('.modal').modal('show')
        });
    });
});

</script>
