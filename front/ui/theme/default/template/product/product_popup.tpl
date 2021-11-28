<div class="productModal_popup" id="testID"  style="width: 350px;">
    <div class="modal fade product-details" id="popupmodal" id="store-cart-side" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="margin-top: 10rem;">
            <div class="modal-content  col-md-8 col-md-push-2 pl0 pr0">
                <div class="modal-body" id="popup_product_<?= $product['product_store_id'] ?>">
                    <button type="button" class="close close-model" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="row">
                        <div class="col-md-5" >
                            <div class="product-slider xyz" >
                                <div class="easyzoom easyzoom--overlay" >
                                   <!-- <a href="<?php echo $product['zoom_thumb']; ?>">-->
                                        <img src="<?php echo $product['thumb']; ?>" alt="" style="width: 215px;" />
                                   <!-- </a>-->
                                </div>

                              <!-- <?php foreach($product['images'] as $images) { ?>

                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="<?= $images['zoom_popup']?>">
                                            <img src="<?= $images['popup']?>" alt="" style="width: 250px;height:200px" />
                                        </a>
                                    </div>
                               <?php } ?>-->
                            </div>
                        </div>
                        <div class="col-md-7"  style="margin-top:25px" >
                            
                            
                            <?php $units = []; ?>
                            <h2 class="product_name rating"><?php  echo $product['product_info']['name'] ?></h2>
                            <div class="product-variants-list">
                                <div class="product-variant">
                                    <!-- <button class="product-variant-btn  product-variant-btn-active product_unit" ><?php echo $product['product_info']['unit'] ?></button> -->
                                    <?php if(trim($product['product_info']['unit'])) { ?>                                        
                                        <div class="box-menu">
                                            <a style="pointer-events: none; cursor: default;" data-product-id="<?= $product['product_store_id'] ?>" data-popup-product-id="<?= $product['product_store_id'] ?>"     class="product-variant-btn  product-variant-btn-active product-unit" tabindex="0">
                                                            
                                            </a>
                                                             
                                        </div>
                                        
                                    <?php } else { ?>
                                        <div class="product-variant-label"></div>
                                        <div class="box-menu">
                                            <?php if($product['variations']) { ?>
                                                <?php foreach($product['variations'] as $variation){ array_push($units,$variation['unit']);?>
                                                    <a data-product-id="<?= $variation['product_store_id'] ?>" data-popup-product-id="<?= $product['product_store_id'] ?>"     class="product-variant-btn product-unit" tabindex="0">
                                                        
                                                    </a>
                                                <?php } ?>          
                                            <?php } ?> 
                                        </div>
                                        
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="product-price">
                                <div class="homeprice-rate">
                                   <!--<?php if ($product['product_info']['special_price']  == '0.00' || !isset($product['product_info']['special_price']) || empty($product['product_info']['special_price'])) { ?>
                                       <span class="price-popup"><?php echo $product['product_info']['price']; ?></span>-->
                                  <!--  <?php } else { ?>
                                        <span class="old-price-popup">
                                            MRP: <?php echo $product['product_info']['price']; ?>
                                            
                                        </span>
                                        <span class="price-popup">
                                          <?php echo $product['product_info']['special_price']; ?> 
                                        </span>-->
                                     <!-- <?php } ?>-->
                                </div>
                                
                               <div class="pro-qty-addbtn inc-dec-cart" data-variation-id="<?= $product['store_product_variation_id'] ?>" id="action_<?= $product['product_store_id'] ?>">
                                    <?php require(DIR_BASE.'front/ui/theme/mvgv2/template/product/popup-actionnew.tpl'); ?>
                                </div> 
 
                                
                            </div>
                            <br>                            
                            <!-- <?php if($this->config->get( 'config_product_description_display' )) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="product-features" style="margin-right:30px;">
                                    <table class="table table-bordered">
                                        <h2 class="table-title"><?= $text_product_highlights ?> </h2>
                                        <tbody>
                                            <tr>
                                                <th><?= $text_description ?></th>
                                                <?php if(strlen($product['product_info']['description'])) { ?>
                                                <td class="product_description"> <?php echo html_entity_decode($product['product_info']['description'])?> </td>
                                                    <?php }  else {?>
                                                    <td> <?php  echo $text_no_description;}?> </td>
                                            </tr>                                            
                                                                                       
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                            
                            <div class="product-disclaimer">Product information or packaging displayed may not be current or complete. Always refer to the physical product for the most accurate information and warnings.</div>
                            <div>
                            
                                <span class="product-variant-label"><?php echo $text_add_to_list; ?></span> 
                                <?php if($this->customer->isLogged()) { ?>
                                

                                <a href="#" class="add-to-list list_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="list-btn" data-id="<?= isset($product['product_id']) ? $product['product_id'] : '' ?>"
                                 type="button" data-toggle="modal" data-target="#listModal"  ><img class="add-list-png"   src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png">
                                 </a>

                                <?php } else { ?>
                                    <a href="#"  type="button" data-toggle="modal" data-target="#phoneModal"><img class="add-list-png" src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png"></a>

                                <?php } ?>
                            </div> -->

                        </div>

                    </div>

                    
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).delegate('.close-model', 'click', function(){
        console.log("close product block");
            $('#popupmodal').modal('hide');
            $('.modal-backdrop').remove();
    });

    $(".product-slider").owlCarousel({
        navigation: true, // Show next and prev buttons
        slideSpeed: 1000,
        paginationSpeed: 800,
        singleItem: true,
        pagination: false,
        //autoPlay: true,
        autoPlay: false,
        navigationText: ["<i class='fa fa-angle-left '></i>", "<i class='fa fa-angle-right'></i>"]
    });

    $(document).delegate('.box-menu a', 'click',function(e){

        e.preventDefault();

        console.log("drop down popup");
        $product_id = $(this).attr('data-product-id');   

        $popup_product_id = $(this).attr('data-popup-product-id');   
        
        $('.product-unit').css({'background-color': 'white'});

        //$(this).css({'background-color': "rgba(33, 23, 23, 0.18)"});
        $('.product-variant-btn-active').removeClass('product-variant-btn-active');
        $(this).addClass('product-variant-btn-active'); 


        console.log($product_id+"pro id"+$popup_product_id);
        $title = $(this).find('.text').html();    
        $variation_id = $(this).attr('data-variation-id');
        
        if(typeof $variation_id === "undefined"){
            $variation_id = 0;
        }

        $('#popup_product_'+$popup_product_id+' .xyz').removeClass("product-slider");
        //$('#popup_product_'+$popup_product_id+' .xyz').removeClass("owl-theme");

        $.get('index.php?path=product/store/getVariation&product_id='+$product_id+'&variation_id='+$variation_id, function(data){
            var data = JSON.parse(data);

            console.log(data);
            $('#popup_product_'+$popup_product_id+' .inc-dec-cart').html(data['action_html']);
            $('#popup_product_'+$popup_product_id+' .xyz').html(data['image_html']);
            $('#popup_product_'+$popup_product_id+' .xyz').addClass('product-slider');

            $('#popup_product_'+$popup_product_id+' .homeprice-rate').html(data['price_html']);

            $('#popup_product_'+$popup_product_id+' .filter-option').html($title);        

            //new
            $('#popup_product_'+$popup_product_id+' .product_name').html(data['product_name']);
            $('#popup_product_'+$popup_product_id+' .product_unit').html(data['product_unit']);
            console.log(data['product_description']);
            $('#popup_product_'+$popup_product_id+' .product_description').html(data['product_description']);
            $('#popup_product_'+$popup_product_id+' .unit_description').html(data['product_unit']);

            $(".product-slider").data('owlCarousel').destroy();

            if(data['percent_off']) {
                $('.offer-ratio').html(data['percent_off']+'% OFF ');
            } else {

            }
            
            

            $(".product-slider").owlCarousel({
                navigation: true, // Show next and prev buttons
                slideSpeed: 1000,
                paginationSpeed: 800,
                singleItem: true,
                pagination: false,
                //autoPlay: true,
                //autoPlay: 2000,
                autoPlay: false,
                
                navigationText: ["<i class='fa fa-angle-left '></i>", "<i class='fa fa-angle-right'></i>"]
            });

            //$(".pan").pan();
            // Instantiate EasyZoom instances
           // var $easyzoom = $('.easyzoom').easyZoom();

            // Get an instance API
           // var api = $easyzoom.data('easyZoom');
        });

        return false;
    });

    

    $(document).ready(function(){
       // $(".pan").pan();
    })

    // Instantiate EasyZoom instances
   // var $easyzoom = $('.easyzoom').easyZoom();

    // Get an instance API
  //  var api = $easyzoom.data('easyZoom');

</script>
