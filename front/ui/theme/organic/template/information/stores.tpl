<?= $header ?>
    <?php $store['store_id'] = ACTIVE_STORE_ID ?>
    <div class="store-cart-panel">
        <div class="modal right fade" id="store-cart-side" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="cart-panel-content">
                    </div>
                    <div class="modal-footer">
                        <!-- <p><?= $text_verify_number ?></p> -->
                        <a href="<?php echo $checkout; ?>" id="proceed_to_checkout">
                        
                            <button type="button" class="btn btn-primary btn-block btn-lg" id="proceed_to_checkout_button">
                                <span class="checkout-modal-text"><?= $text_proceed_to_checkout?> </span>
                                <div class="checkout-loader" style="display: none;"></div>
                                
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

 


<div >
      <!--<div class="container">
        <div class="row">
          <div class="ad-info">
          
            <h2>Hurry Up!</h2>
            <h3>Deal of the week</h3>
            <h4>From our family farm right to your doorstep.</h4>
          </div>
        </div>
        <div class="row">
          <div class="hot-deal">
            <div class="box-timer">
              <div class="countbox_1 timer-grid"></div>
            </div>
            <ul class="products-grid">
              <li class="item col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="item-inner">
                  <div class="item-img">
                    <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p16.jpg" alt="Fresh Organic Mustard Leaves "></a>
                      <div class="new-label new-top-left">Hot</div>
                      <div class="item-box-hover">
                        <div class="box-inner">
                          <div class="product-detail-bnt"><a href="#" class="button detail-bnt"><span>Quick View</span></a></div>
                          <div class="actions"><span class="add-to-links"><a href="#" class="link-wishlist" title="Add to Wishlist"><span>Add to Wishlist</span></a> <a href="#" class="link-compare add_to_compare" title="Add to Compare"><span>Add to Compare</span></a></span> </div>
                        </div>
                      </div>
                    </div>
                    <div class="add_cart">
                      <button class="button btn-cart" type="button"><span>Add to Cart</span></button>
                      
                    </div>
                  </div>
                  <div class="item-info">
                    <div class="info-inner">
                      <div class="item-title"><a href="product-detail.html" title="Fresh Organic Mustard Leaves ">Fresh Organic Mustard Leaves </a> </div>
                      <div class="item-content">
                        <div class="rating">
                          <div class="ratings">
                            <div class="rating-box">
                              <div class="rating" style="width:80%"></div>
                            </div>
                            <p class="rating-links"><a href="#">1 Review(s)</a> <span class="separator">|</span> <a href="#">Add Review</a> </p>
                          </div>
                        </div>
                        <div class="item-price">
                          <div class="price-box"><span class="regular-price"><span class="price">$125.00</span> </span> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="item col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="item-inner">
                  <div class="item-img">
                    <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p12.jpg" alt="Fresh Organic Mustard Leaves "></a>
                      <div class="item-box-hover">
                        <div class="box-inner">
                          <div class="product-detail-bnt"><a href="#" class="button detail-bnt"><span>Quick View</span></a></div>
                          <div class="actions"><span class="add-to-links"><a href="#" class="link-wishlist" title="Add to Wishlist"><span>Add to Wishlist</span></a> <a href="#" class="link-compare add_to_compare" title="Add to Compare"><span>Add to Compare</span></a></span> </div>
                        </div>
                      </div>
                    </div>
                    <div class="add_cart">
                      <button class="button btn-cart" type="button"><span>Add to Cart</span></button>
                    </div>
                  </div>
                  <div class="item-info">
                    <div class="info-inner">
                      <div class="item-title"><a href="product-detail.html" title="Fresh Organic Mustard Leaves ">Fresh Organic Mustard Leaves </a> </div>
                      <div class="item-content">
                        <div class="rating">
                          <div class="ratings">
                            <div class="rating-box">
                              <div class="rating" style="width:80%"></div>
                            </div>
                            <p class="rating-links"><a href="#">1 Review(s)</a> <span class="separator">|</span> <a href="#">Add Review</a> </p>
                          </div>
                        </div>
                        <div class="item-price">
                          <div class="price-box"><span class="regular-price"><span class="price">$125.00</span> </span> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="item col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="item-inner">
                  <div class="item-img">
                    <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p21.jpg" alt="Fresh Organic Mustard Leaves "></a>
                      <div class="item-box-hover">
                        <div class="box-inner">
                          <div class="product-detail-bnt"><a href="#" class="button detail-bnt"><span>Quick View</span></a></div>
                          <div class="actions"><span class="add-to-links"><a href="#" class="link-wishlist" title="Add to Wishlist"><span>Add to Wishlist</span></a> <a href="#" class="link-compare add_to_compare" title="Add to Compare"><span>Add to Compare</span></a></span> </div>
                        </div>
                      </div>
                    </div>
                    <div class="add_cart">
                      <button class="button btn-cart" type="button"><span>Add to Cart</span></button>
                    </div>
                  </div>
                  <div class="item-info">
                    <div class="info-inner">
                      <div class="item-title"><a href="product-detail.html" title="Fresh Organic Mustard Leaves ">Fresh Organic Mustard Leaves </a> </div>
                      <div class="item-content">
                        <div class="rating">
                          <div class="ratings">
                            <div class="rating-box">
                              <div class="rating" style="width:80%"></div>
                            </div>
                            <p class="rating-links"><a href="#">1 Review(s)</a> <span class="separator">|</span> <a href="#">Add Review</a> </p>
                          </div>
                        </div>
                        <div class="item-price">
                          <div class="price-box"><span class="regular-price"><span class="price">$125.00</span> </span> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="item col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="item-inner">
                  <div class="item-img">
                    <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p3.jpg" alt="Fresh Organic Mustard Leaves "></a>
                      <div class="sale-label sale-top-right">-40%</div>
                      <div class="item-box-hover">
                        <div class="box-inner">
                          <div class="product-detail-bnt"><a href="#" class="button detail-bnt"><span>Quick View</span></a></div>
                          <div class="actions"><span class="add-to-links"><a href="#" class="link-wishlist" title="Add to Wishlist"><span>Add to Wishlist</span></a> <a href="#" class="link-compare add_to_compare" title="Add to Compare"><span>Add to Compare</span></a></span> </div>
                        </div>
                      </div>
                    </div>
                    <div class="add_cart">
                      <button class="button btn-cart" type="button"><span>Add to Cart</span></button>
                    </div>
                  </div>
                  <div class="item-info">
                    <div class="info-inner">
                      <div class="item-title"><a href="product-detail.html" title="Fresh Organic Mustard Leaves ">Fresh Organic Mustard Leaves </a> </div>
                      <div class="item-content">
                        <div class="rating">
                          <div class="ratings">
                            <div class="rating-box">
                              <div class="rating" style="width:80%"></div>
                            </div>
                            <p class="rating-links"><a href="#">1 Review(s)</a> <span class="separator">|</span> <a href="#">Add Review</a> </p>
                          </div>
                        </div>
                        <div class="item-price">
                          <div class="price-box"><span class="regular-price"><span class="price">$125.00</span> </span> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        </div>-->
     <div class="mid-section">
      <div class="container">
      <div class="row">
        <h3>Fresh organic foods delivery made easy</h3>
        <h2>Special Product</h2>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="block1"> <strong>fresh from our farm</strong>
            <p>We deliver organic fruits & vegetables fresh from our fields to your doorstep.</p>
          </div>
          <div class="block2"> <strong>100% organic Foods</strong>
            <p>Products delivered are 100% organic and fresh from farmers.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="spl-pro"><a href="product-detail.html" title="Fresh Organic Mustard Leaves "><img src="front/ui/theme/organic/images/offer-img.png" alt="Fresh Organic Mustard Leaves "></a>
            
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="block3"> <strong>Good for health</strong>
            <p>Products are good for health as well as economic as , it reduces middle layer of market.</p>
          </div>
          <div class="block4"> <strong>Safe From Pesticides</strong>
            <p>No Pesticides used in yeilding vegetables and fruits makes, 100% pure products.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
    
    
    <?php echo $footer; ?> 
    <?= $login_modal ?>
    <?= $signup_modal ?>
    <?= $forget_modal ?>

    <div class="timeslotModal-popup">
        <div class="modal fade" id="timeslotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" id="popup_product">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <div class="store-find-block">
                        <div class="mydivsssxx">
                            <div class="store-find">
                                
                                <div class="checkout-time-table-new" id="delivery-time-wrapper-new"></div>  
                            </div>
                        </div>
                    </div
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- <div class="banner-popup">
        <div class="modal fade" id="banner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" id="popup_product_<?= $product['product_store_id'] ?>">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <div class="store-find-block">
                        <div class="mydivsss">
                            <div class="store-find">
                                <div class="store-head">
                                    <h1><?= $text_heading_title ?></h1>
                                </div>
                                <div class="checkout-time-table-new" id="delivery-time-wrapper"></div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div> -->


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
                            <b><?= $text_change_location_name ?> : <?= $location_name_full ?></b>
                            
                        <?php } ?>

                        
                    </div>
                    <a href="<?php echo $toHome ?>" class="btn btn-primary"><?= $button_change_locality ?></a>
                    
                </div>
            </div>
        </div>
    </div>

<?php if ($kondutoStatus) { ?>


<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);


    var page_category = 'store-listing-page';
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                    clear = true;
               }
             if (clear) {
            clearInterval(intervalID);
         }
        },
        period);
        })(page_category);


</script>

<?php } ?>
    

    <script type="text/javascript">
    
    jQuery('.header-search-form').on('click', function() {
        console.log("seaf click");
        jQuery('body').toggleClass('overflow-y-hidden');
    });
    jQuery('.header-search-form').on('click', function() {
        console.log("seaf click c");
        jQuery('.overlay-body').toggleClass('backdrop');
    });

    jQuery('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    /*jQuery(function($){
        console.log("signup mask");
       $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    });*/
    /*jQuery(function($) {
        console.log("mask");

       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    /*jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });*/

    jQuery(document).delegate('#clearcart', 'click', function() {

        var choice = confirm(jQuery(this).attr('data-confirm'));

        if(choice) {

            jQuery.ajax({
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

    jQuery("ul.nav-tabs a").click(function (e) {
      e.preventDefault();
      jQuery(this).tab('show');
    });
    
    function searchStore() {
        console.log("searchStore");
    }

    function timeslots(store_id) {
        console.log("timeslots");

        jQuery('#timeslotModal').modal('show');

        /*$.ajax({
            url: 'index.php?path=checkout/delivery_time/getRawTimeslot&shipping_method=normal.normal&store_id='+store_id+'',
            type: 'get',
            dataType: 'html',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                $('#delivery-time-wrapper').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });*/

        jQuery.ajax({
            url: 'index.php?path=information/locations/getStoreDetail&store_id='+store_id+'',
            type: 'get',
            dataType: 'html',
            cache: false,
            async: true,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                jQuery('#delivery-time-wrapper-new').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });



        return false;
    }

</script>

</body>

</html>
