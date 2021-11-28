<?= $header ?>
    
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
    <div style="display:none;background-image: url(image/theme/digital_banner.jpg); background-size:cover; height:610px"> </div>

    <div class="page-breadcrumb hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-section text-center">
                        <h2 class="page-title">
                            <?= $text_choose_store ?>

                            <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                                <strong><?php echo $zipcode ?>,</strong>
                            <?php } ?>
                            

                            <strong><?php echo $city_name ?></strong>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper" id="wrapper">
        <div class="store-company-page">
            <div class="container">

                <?php foreach($store_lists as $store_list){ ?>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <h2 style="color:#43b02a "><?= $store_list['name'] ?></h2></div>
                    </div>
                    <div class="row">
                        <?php foreach($store_list['stores'] as $store) { ?>

                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="store-block">

                                    <?php if(!empty($store['store_open_hours'])) { ?>
                                        <aside class="ribbon">
                                            <!-- <span><?php echo $store['store_open_hours']; ?></span> -->
                                            <span>Same Prices as in Store</span>
                                        </aside>
                                    <?php } ?>
                                    
                                    <div class="store-company">
                                        <a href="<?= $this->url->link('product/store', 'store_id='.$store['store_id']) ?>">
                                            <img src="<?= $store['image']?>" class="img-circle">
                                        </a>
                                    </div>
                                    <div class="store-info">
                                        <h2 class="store-title"><?= $store['name'] ?></h2>
                                        <a href="#" onclick="return timeslots(<?= $store['store_id'] ?>)" style="color: #43b02a"><?= $text_available ?></a>
                                    </div>
                                    <!--<a href="<?= $this->url->link('product/store', 'store_id='.$store['store_id']) ?>" class="btn btn-default"><?php echo $text_shop ?></a>-->
                                </div>
                            </div>
                        <?php } ?>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="store-block">
                                    
                                    <div class="store-company">
                                        <a>
                                            <img src="image/cache/data/stores/more-store-logo-150x150.png" class="img-circle">
                                        </a>
                                    </div>
                                    <div class="store-info">
                                        <h2 class="store-title">More Stores</h2>
                                        <a style="color: #43b02a">Coming soon...</a>
                                    </div>
                                    <!--<a href="<?= $this->url->link('product/store', 'store_id='.$store['store_id']) ?>" class="btn btn-default"><?php echo $text_shop ?></a>-->
                                </div>
                            </div>
                    </div>
                <?php } ?>
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    <script src="<?= $base?>front/ui/javascript/common.js" type="text/javascript"></script>
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

    <!-- Isolated Version of Bootstrap, not needed if your site already uses Bootstrap -->
    
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

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
    
    $('.header-search-form').on('click', function() {
        console.log("seaf click");
        $('body').toggleClass('overflow-y-hidden');
    });
    $('.header-search-form').on('click', function() {
        console.log("seaf click c");
        $('.overlay-body').toggleClass('backdrop');
    });

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
    /*jQuery(function($) {
        console.log("mask");

       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    $(document).delegate('#clearcart', 'click', function() {

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

    $("ul.nav-tabs a").click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    
    function searchStore() {
        console.log("searchStore");
    }

    function timeslots(store_id) {
        console.log("timeslots");

        $('#timeslotModal').modal('show');

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

        $.ajax({
            url: 'index.php?path=information/locations/getStoreDetail&store_id='+store_id+'',
            type: 'get',
            dataType: 'html',
            cache: false,
            async: true,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                $('#delivery-time-wrapper-new').html(html);
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
