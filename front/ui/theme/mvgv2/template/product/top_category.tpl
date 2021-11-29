<?php echo $header; ?>

<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">

    <?php if(isset($offer_show) && $offer_show && count($offer_products['products']) > 0 ) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="product-category-block">
                    <h2><?= $text_offer ?><a href=""> <a style="color: #d60343;margin-left: 7px;font-size: 14px;letter-spacing: 0;text-decoration: underline;" href="<?= $offer_href ?>" id="view-all"><?= $text_view ?></a></h2>
                </div>
            </div>
        </div>
         <div class="row">
             <div class="store-list-wrapper" style="display:block;">
                <?php $products = $offer_products['products'] ?>
                <?php require(DIR_BASE.'front/ui/theme/mvgv2/template/product/product_collection.php'); ?>
            </div>
        </div>
    <?php } ?>

    <?php foreach($categories as $category){ ?>

            <?php if($category['products']){ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="product-category-block">
                            <h2><?= $category['name'] ?><a href=""> <a style="color: #d60343;margin-left: 7px;font-size: 14px;letter-spacing: 0;text-decoration: underline;" href="<?= $category['href'] ?>" id="view-all"><?= $text_view ?></a></h2>
                        </div>
                    </div>
                </div>
                 <div class="row">
                     <div class="store-list-wrapper" style="display:block;">
                        <?php $products = $category['products'] ?>
                        <?php require(DIR_BASE.'front/ui/theme/mvgv2/template/product/product_collection.php'); ?>
                    </div>
                </div>
        <?php } ?>
    <?php } ?>
</div>

        
        </div>
    </div>
</div>

<div class="modal-wrapper"></div> 

    
    <div style="padding-bottom:30px;"></div>
    
    <?php echo $footer; ?> 
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
                        <b><?= $text_change_location_name ?> : <?= $location_name_full ?></b>
                        
                    <?php } ?>
                    
                </div>
                <a href="<?php echo $toHome ?>" class="btn btn-primary"><?= $button_change_locality ?></a>
                <a href="<?php echo $toStore ?>" class="btn btn-default"><?= 
                $button_change_store ?></a>
                
            </div>
        </div>
    </div>
</div>
</div>

<!-- Start store banner -->

<!--<div class="bannerModal_popup">
    <div class="modal fade" id="bannermodal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close close-model" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <center><img src="<?php echo $banner_logo; ?>" alt="" class="img-responsive"></center>
                    
                </div>
            </div>
        </div>
    </div>
</div>-->

<script type="text/javascript">
    
    $(document).delegate('.close-model', 'click', function(){
        console.log("close product block");
            $('#bannermodal').modal('hide');
            $('.modal-backdrop').remove();
    });
</script>

<!-- End store banner -->


    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/slider-carousel.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.plugins.min.js"></script>

    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>


    <script type="text/javascript">

   

    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    // jQuery(function($){
    //     console.log("signup mask");
    //    $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    // });
    /*jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/
    
    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    $(function() {
        console.log("lazy f");
        $('img.lazy').Lazy({
                beforeLoad: function(element) {
                    // called before an elements gets handled
                    console.log("lazy");
                },
                effect: 'fadeIn',
                effectTime : 500,
                visibleOnly: true,
            }
        );
    });

    $(document).ready(function() {

        
        
        if(<?php  echo isset( $show_banner) ? 'true' : 'false' ?>) {

            $('#bannermodal').modal('show');    
        }

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
    
    $(".ddd").on("click", function() {
        var $button = $(this);
        var $input = $button.closest('.sp-quantity').find("input.quntity-input");

        $input.val(function(i, value) {
            return +value + (1 * +$button.data('multi'));
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
     
    </script>
    <?php endif ?>


    

    <script type="text/javascript">

        

  $(document).ready(function () {
    $(document).delegate('.open-popup', 'click', function () {
      $('.open-popup').prop('disabled', true);
      // console.log("product blocks" + $(this).attr('data-id'));
      $.get('index.php?path=product/product/view&product_store_id=' + $(this).attr('data-id') + '&store_id=' + $(this).attr('data-store'), function (data) {
        $('.open-popup').prop('disabled', false);
        $('.modal-wrapper').html(data);
        $('#popupmodal').modal('show');
      });
      $('#product_name').val('');
    });
  });

    </script>
    <script type="text/javascript">
   
    $("#sidebarss").stick_in_parent();


    if(window.screen.availWidth < 450 || window.screen.availHeight < 732) {
        $("#sidebarss").trigger("sticky_kit:detach");
    } else {
        $("#sidebarss").stick_in_parent();
    }

    $('.add-to-list').on('click', function (e) {
        
        console.log("erg");
        data = {
            product_id : $(this).data("id")
        }

        $.ajax({
            url: 'index.php?path=account/wishlist/getProductWislists',
            type: 'post',
            data:data,
            dataType: 'json',
            success: function(json) {
                if (json['status']) {

                    console.log(json);
                    $('#users-list').html(json['html']);
                }
            }
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
</body>

</html>
