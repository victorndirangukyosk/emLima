<?php echo $header; ?>

<div class="page-container">
    <div class="shopping">
        <div class="container-fluid">
            <div class="col-md-12 contents">
                <div style="display: block;" class="shopping-container">
                    <div class="products-rows">
                        <div class="v4scroll-inner">
                            <div class="products-heading-rows">
                                <h2>
                                    <?= $heading_title ?>
                                </h2>
                            </div>
                            
                            <hr />
                            
                            <div class="list-categories">  
                                
                                <?php if($category_id==0){ ?>
                                <div class="instacart-list-tab instacart-list-tab-active">                                    
                                    <a href="<?= $this->url->link('product/recipe') ?>" class="instacart-list-tab-text">                                        
                                       <?= $text_popular ?>
                                    </a>
                                </div>
                                <?php }else{ ?>
                                <div class="instacart-list-tab">                                                                        
                                    <a href="<?= $this->url->link('product/recipe') ?>" class="instacart-list-tab-text">
                                       <?= $text_popular ?>
                                    </a>                                                                        
                                </div>
                                <?php } ?>
                                
                                <?php foreach($categories as $category){ ?>
                                
                                <?php if($category_id == $category['category_id']){ ?>
                                <div class="instacart-list-tab instacart-list-tab-active">
                                    <a href="<?= $this->url->link('product/recipe', 'category_id='.$category['category_id']) ?>" class="instacart-list-tab-text">
                                       <?= $category['name'] ?>
                                    </a>
                                </div>
                                <?php }else{ ?>
                                <div class="instacart-list-tab">
                                    <a href="<?= $this->url->link('product/recipe', 'category_id='.$category['category_id']) ?>" class="instacart-list-tab-text">
                                       <?= $category['name'] ?>
                                    </a>
                                </div>
                                <?php } ?>
                                
                                <?php } ?>
                            </div>

                            <div class="recipe-list product-container-fluid">
                                
                                <?php if($recipes){ ?>
                                
                                <div class="product-details-row">

                                <div class="row">
                                        
                                <?php foreach ($recipes as $recipe) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 recipe_block" data-id="<?= $recipe['recipe_id'] ?>">
                                        <a class="recipe" style="background-image: url('<?= $recipe['thumb'] ?>')">
                                            <div class="content">
                                              <span class="title"><?= $recipe['title'] ?></span>
                                              <span class="user">By <?= $recipe['author'] ?></span>
                                            </div>
                                        </a>
                                    </div><!-- END .col-md-3 -->                                    
                                <?php } ?>
                                
                                </div>
                                
                                </div><!-- END .product-details-row -->

                                <?php }else{ ?>
                                
                                <p class='alert alert-info'><?= $text_no_results ?></p>
                                
                                <?php } ?>
                                
                                <div style='display: none;'>
                                    <?= $pagination ?>
                                </div>
                                                                
                            </div><!-- END .product-container-fluid -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-wrapper"></div>       
    </div>
</div>

<?php echo $footer; ?>

<script type="text/javascript" src="front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>

<script type="text/javascript">
$(document).ready(function() {
    
    $(document).delegate('.recipe_block', 'click', function(){
        console.log("product block");
        $.get('index.php?path=product/recipe/view&recipe_id='+$(this).attr('data-id'), function(data){
            $('.modal-wrapper').html(data);
            $('.modal').modal('show')
        });
    });
    
    $('.cat-list').css('display','none');
    
    $('.categories-list-container').on('mouseenter', function(){
        $('.cat-list').css('display','block');
    });
    
    $('.categories-list-container').on('mouseleave', function(){
        $('.cat-list').css('display','none');
    });

    var $container = $('.recipe-list');
    
    $container.infinitescroll({
        animate:true,
        navSelector  : '.pagination',    // selector for the paged navigation 
        nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.product-details-row',     // selector for all items you'll retrieve
        loading: {
            finishedMsg: 'No more recipes!',
            msgText: 'Loading...',
            img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'
            
        }
    });			
});
</script>
