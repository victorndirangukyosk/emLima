<?php echo $header; ?>
<?php //echo '<pre>';print_r($products);exit;?>

<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 product_scroll">
    <div class="row">
        <div class="col-md-12">
            <div class="product-category-block">
                <h2><?php echo $text_search; ?></h2>
            </div>
        </div>
    </div>
    
          <div class="category-products">
            <?php if(count($products)>0){?>
            <ul class="products-grid">
            <?php 
                foreach($products as $product) {
                //echo '<pre>';print_r($product);
              ?>
              <li class="item col-lg-3 col-md-3 col-sm-3 col-xs-6">
                              <div class="item-inner">
                              <div class="item-img">
                                <div class="item-img-info"><a href="<?=$product['href']?>" title="<?=$product['name']?>" class="product-image"><img src="<?=$product['thumb']?>" alt="<?=$product['name']?>"></a>
                                
                                  <div class="item-box-hover">
                                    <div class="box-inner product-block" data-id="<?= $product['product_store_id'] ?>">
                                      <div class="product-detail-bnt product-img product-description" data-id="<?= $product['product_store_id'] ?>" ></div>
                                      <!--<div class="actions"><span class="add-to-links"><a href="#" class="link-wishlist" title="Add to Wishlist"><span>Add to Wishlist</span></a> <a href="#" class="link-compare add_to_compare" title="Add to Compare"><span>Add to Compare</span></a></span> </div>-->
                                      
                                    </div>
                                  </div>
                                </div>
                                <div class="pro-qty-addbtn" data-variation-id="<?= $product['product_variation_store_id'] ?>" id="action_<?= $product['product_variation_store_id'] ?>">

                                   <?php require 'action.tpl'; ?>
                
                                 </div>
                                
                              <div class="item-info">
                                <div class="info-inner">
                                  <div class="item-title"><?=$product['name']?></div>
                                  <div class="item-content">
                                    <!--<div class="rating">
                                      <div class="ratings">
                                        <div class="rating-box">
                                          <div class="rating" style="width:80%"></div>
                                        </div>
                                        <p class="rating-links"><a href="#">1 Review(s)</a> <span class="separator">|</span> <a href="#">Add Review</a> </p>
                                      </div>
                                    </div>-->
                                    <div class="item-price">
                                      <div class="price-box"><span class="regular-price">Price : <span class="price"><?=$product['price']?></span> </span> </div>
                                      <?php if($product['special']){ ?>
                                      <div class="price-box"><span class="regular-price">Special Price:<span class="price"><?=$product['special']?></span> </span> </div>
                                      <?php } ?>
                                    </div>
									
                                  </div>
                                </div>
                              </div>
                            </div>
                      
              </li>
                <!--- Product Details Modal Start --->
                <div id="product_<?=$product['product_id']?>" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body class="col-lg-2 col-md-4 col-sm-6 col-xs-6 nopadding product-details" style="border-right: 1px solid rgb(215, 220, 214);">
                      <div>
                
            <?php /*echo "<pre>";print_r($product);die;*/ if(isset($product['percent_off']) && $product['percent_off'] != '0.00') { ?>

                <span class="spacial-offer"> <?php echo $product['percent_off'].'% OFF';?></span>
            <?php } ?>
                

            <?php if($this->customer->isLogged()) { ?>
            

            <a href="#" class="add-to-list list_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="list-btn" data-id="<?= $product['product_id'] ?>"
             type="button" data-toggle="modal" data-target="#listModal"  ><img class="add-list-png"   src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png">
             </a>

            <?php } else { ?>
                <a href="#"  class="add-to-list" type="button" data-toggle="modal" data-target="#phoneModal"><img class="add-list-png" src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png"></a>

            <?php } ?>
        </div>
                        <div class="product-block"  data-id="<?= $product['product_store_id'] ?>">

            <div class="product-img product-description open-popup" data-id="<?= $product['product_store_id'] ?>" data-id="<?= $product['product_store_id'] ?>">
                <img class="lazy" data-src="<?= $product['thumb'] ?>" alt="">
            </div>
            <div class="product-description" data-id="<?= $product['product_store_id'] ?>">
                

                <h3 class="open-popup" data-id="<?= $product['product_store_id'] ?>">

                    <a class="product-title"><?= $product['name']?></a>
                </h3>

                <?php if(trim($product['unit'])){ ?>
                    <p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span class="small-info"><?= $product['unit'] ?></span></p>
                <?php } else { ?>
                    <p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span class="small-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                <?php } ?>

                <div class="product-price">
                    <?php if ( $product['special'] == '0.00' || empty(trim($product['special']))) { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>" style="display: none";>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                            <?php echo $product['price']; ?>
                        </span>
                    <?php } else { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>">
                            <?php echo $product['price']; ?>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                            <?php echo $product['special']; ?>
                        </span>
                    <?php } ?>
                    <div class="pro-qty-addbtn" data-variation-id="<?= $product['store_product_variation_id'] ?>" id="action_<?= $product['product_store_id'] ?>">
                        
                        <?php require 'action.tpl'; ?>
                    </div>
                </div>
                
            </div>
        </div>
                      </div>
                      <div class="modal-footer">
                        Footer
                      </div>
                    </div>

                  </div>
                </div>
                <!--- Product Details Modal End --->
              <?php }?>
             
            </ul>
            <?php }else{ ?>
             <center> <h2> There are no products to list in this category. </h2></center>
            <?php }?>
          </div>
            
   
    <center class="loader-gif" style="margin-top: 40px">
    </center>
</div>
<!-- <div class="row" style="padding-bottom:30px;"></div> -->
        </div>
    </div>
</div>
<div class="modal-wrapper"></div> 
<div style="padding-bottom:30px;"></div>
<?php //echo $footer; ?> 
<?= $login_modal ?>
<?= $signup_modal ?>
<?= $forget_modal ?>
<div class="listModal-popup">
    <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="uncheckAll()"><span aria-hidden="true">&times;</span></button>
                    <div class="store-find-block">
                        <div class="mydivsssx">
                            <div class="store-find">
                                <div class="store-head">
                                    <h1><?= $text_add_to_list ?></h1>
                                </div>

                                <div id="list-message-success" style="color: green;">
                                </div>

                                <div id="list-message-error" style="color: red;">
                                </div>

                                <form id="add-in-list" action="" method="post" enctype="multipart/form-data">

                                    <table class="table table-striped">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center;"><?= $text_list_name ?></th>
                                            <th style="text-align: center;"><?= $text_add_to ?> </th>
                                          </tr>
                                        </thead>
                                        <tbody id="users-list">
                                            <?php foreach ($lists as $list) { ?>
                                              <tr>
                                                <td><?= $list['name'] ?></td>
                                                <td class=""> <input type="checkbox" class="" name="add_to_list[]" value="<?= $list['wishlist_id'] ?>"></td>
                                              </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <input type="hidden" name="listproductId" class="listproductId" value=""/>

                                    <button id="add-in-list-button" type="button" name="next" class="btn btn-default btn-lg">
                                        <span class="add-in-list-modal-text"><?= $text_confirm ?> </span>
                                        <div class="add-in-list-loader" style="display: none;"></div>
                                    </button>
                                </form>

                               
                                <p class="seperator"><?= $text_or ?> </p>
                                <div class="social-login-section">
                                    <form id="list-create-form" action="" method="post" enctype="multipart/form-data" class="form">
                                        
                                        <input type="hidden" name="listproductId" class="listproductId" value=""/>
                                        <div class="row">
                                            <div class="col-sm-9 form-group required">
                                                <input id="list-name" name="name" type="text" placeholder="<?= $text_enter_list_name ?>" class="form-control input-bg" required>
                                            </div>
                                            
                                            
                                            <div class="col-sm-3 form-group">
                                                <button id="list-create-button" type="button" name="next" class="btn btn-default btn-lg">
                                                        <span class="list-create-modal-text"><?= $text_create_list ?> </span>
                                                        <div class="list-create-loader" style="display: none;"></div>
                                                </button>
                                            </div> 
                                            
                                        </div>
                                        
                                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="changelocationModal">
    <div class="modal fade" id="useraddress-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <div class="exclamation-icon"><i class="fa fa-exclamation-circle fa-4x"></i></div>
                 <div class="changelocationModal-content">
                    <h2><?= $text_change_locality ?></h2>
                    <?php if($this->config->get('config_multi_store')) { ?>
                        <p><?= $text_only_on_change_locality_warning ?></p>
                    <?php } else { ?>
                        <p><?= $text_change_locality_warning ?></p>
                    <?php } ?> 

                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                        <b> <?= $text_change_location_name ?> : <?= $zipcode ?></b>
                    <?php } else { ?>
                        <b><?= $text_change_location_name ?> : <?= $location_name ?></b>
                        
                    <?php } ?>
                            
                </div>
                <a href="<?php echo $toHome ?>" class="btn btn-primary"><?= $button_change_locality ?></a>
                <a href="<?php echo $toStore ?>" class="btn btn-default"><?= 
                $button_change_store ?></a>
                
            </div>
        </div>
    </div>
</div>
    
<script type="text/javascript" src="<?= $base; ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
    <!-- <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.lazy.plugins.min.js"></script> -->

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.plugins.min.js"></script>


    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>

    <!-- <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/mvgv2/css/bootstrap-iso.css" /> -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    
<!--     <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/mvgv2/css/bootstrap-datepicker3.css"/>
 -->
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    

<script type="text/javascript">

$('.date-dob').datepicker({
    pickTime: false,
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    autoclose: true,
});
/*jQuery(function($){
    console.log("signup mask");
   $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
});*/
jQuery(function($){
    console.log("mask");
   $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
});

jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

$(function() {
    console.log("lazy f");
    $('img.lazy').Lazy({
            beforeLoad: function(element) {
                console.log("lazy");
            },
            effect: 'show',
            effectTime : 700,
            visibleOnly: true,
        }
    );
});

$(document).ready(function() {
    console.log("scroller");
    var $container = $('.store-list-wrapper');
    $container.infinitescroll({
        animate: false,
        navSelector  : '.pagination',    // selector for the paged navigation 
        nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.product-details',     // selector for all items you'll retrieve
        loading: {
            finishedMsg: '<h2><?php echo $text_no_more_products ?></h2>',
            msgText: ' ',
            img: '<?= $base ?>image/theme/ring.gif',
            selector: '.loader-gif',
            
        }
    },

    // Function called once the elements are retrieved
    function(new_elts) {

        $('img.lazy').Lazy({
                beforeLoad: function(element) {
                    // called before an elements gets handled
                    console.log("lazy");
                },
                effect: 'show',
                effectTime : 700,
                visibleOnly : true
                //visibleOnly: true,
            }
        );
    });     
});

</script>

<script type="text/javascript">

    $(document).ready(function() {
        $('[data-toggle="offcanvas"]').click(function() {
            $('.row-offcanvas').toggleClass('active')
        });

        $('[data-toggle="tooltip"]').tooltip(); 
    });
    $('.header-search-form').on('click', function() {
        $('body').toggleClass('overflow-y-hidden');
    });
    $('.header-search-form').on('click', function() {
        $('.overlay-body').toggleClass('backdrop');
    });

    $(document).ready(function() {
        console.log("search page popup");
        $(document).delegate('.open-popup', 'click', function(){
            
            console.log("search product blocks"+$(this).attr('data-id'));
            $.get('index.php?path=product/product/view&product_store_id='+$(this).attr('data-id'), function(data){
                $('.modal-wrapper').html(data);
                $('#popupmodal').modal('show');
            });
        });
    });

    $(document).delegate('#clearcart', 'click', function(){
        var choice = confirm($(this).attr('data-confirm'));

        if(choice) {

            $.ajax({
                url: 'index.php?path=checkout/cart/clear_cart',
                type: 'post',
                data:'',
                dataType: 'json',
                success: function(json) {
                if (json['location']) {

                    location = json.redirect;
                    location = location;
                }}
            });
        }
    });
</script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/slider-carousel.js"></script>
</body>

</html>
