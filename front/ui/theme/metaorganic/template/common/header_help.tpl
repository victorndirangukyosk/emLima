<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="<?= $config_language?>">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <title><?= $title ?></title>
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/images/favicon.ico" rel="icon">
    <!-- BEGIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.min.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.min.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.min.css" rel="stylesheet">
    <!-- END CSS -->

    <!-- Bootstrap -->
    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/organic/css/style.css?v=5.1">

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/mycart.min.css">
    
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <!-- <link href="<?= $base;?>front/ui/theme/mvg/stylesheet/layout.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,400,600,300" /> -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.css">

    <!-- <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?> -->

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/stylesheet/layout_help.css">

    <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base;?>front/ui/javascript/common.js" type="text/javascript"></script>
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script src="<?= $base;?>front/ui/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
     <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/style.min.css" media="all">

	<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/responsive.css" media="all">
</head>

<body>
   <header>
      <div class="header__primary-navigation-item header__primary-navigation-item--more-categories" style="margin-left: 0px;">

                     <div class="header__secondary-navigation-tablet-container"></div>

                     <ul class="header__upper-deck-list" >

                        <?php if(!$is_login){?>
                        <li class="header__upper-deck-item header__upper-deck-item--register">
                           <a data-toggle="modal" data-dismiss="modal" data-target="#signupModal-popup" class="header__upper-deck-item-link register" data-spinner-btn="{showOnSubmit: false}">
                           Register</a>
                        </li>
                        <li class="header__upper-deck-item header__upper-deck-item--signin">
                           <a data-toggle="modal" data-target="#phoneModal" class="header__upper-deck-item-link sign-in" data-spinner-btn="{showOnSubmit: false}" >
                           Sign In</a>
                        </li>
                       <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->

                             <div class="newset" style="margin-top: 20px;"><a class="btn" href="<?= $dashboard ?>" > <span ><?= $full_name ?></span> </a>

                           <div class="dropdownset" style="display:none;">
                            <div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link"
                                href="<?= $dashboard ?>"><i class="fa fa-user"></i>Dashboard &nbsp;<span class="badge badge-pill badge-light">New</span></a></div>
                                 <div class="dropdownsetnew"><a class="header__upper-deck-item-link"
                                href="<?= $account ?>"><i class="fa fa-user"></i>My Account</a></div>
                                 <div class="dropdownsetnew"  ><a class="header__upper-deck-item-link"
                                   href="<?= $wishlist ?>" ><i class="fa fa-shopping-basket"></i>My Basket</a></div>
                               <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $help ?>"><i
                                  class="fa fa-question-circle"></i>Help</a></div>
                            <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $logout ?>"><i
                                  class="fa fa-power-off"></i><?= $text_logout ?></a></div>
                                  <!-- <div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link" href="<?= $account ?>" ><i class="fa fa-user"></i>Profile</a></div>

                                  <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></div>
                                  <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $wishlist ?>" ><i class="fa fa-list-ul"></i><?= $text_my_wishlist?></a></div>
                                    <?php if($this->config->get('config_credit_enabled')) { ?>

                                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $credit ?>" ><i class="fa fa-money"></i><?= $text_my_cash ?></a></div>
                                    <?php } ?>

                                    <div class="dropdownsetnew"><a  class="header__upper-deck-item-link" href="<?= $address ?>" ><i class="fa fa-address-book"></i><?= $label_my_address ?></a></div>
                                   <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="#" class="header__upper-deck-item-link btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></div>
                                    <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></div>
                                    <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></div> -->
                                    </div>
                                    </div>
                                    <div class="butn setui" style="position:relative; z-index:-1000;">
                                       <a  onclick="checkMinimumOrderTotal();">

                                        <div class="btn btn-default mini-cart-button" role="button" data-toggle="modal"
                                                data-target="#store-cart-sides" id="mini-cart-button" 
                                                style="margin-right:10px; margin-top:0px; display:flex; flex-flow: column nowrap;">
                                                <div  style="display:flex; align-items: center;">
                                                  <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;
                                                  <span class="hidden-xs hidden-sm cart-total-amount"><?= $this->
                                                    currency->format($this->cart->getTotal()); ?></span>
                                                </div>
                                            <span class="badge cart-count" style="margin: 4px 0px;"><?= $this->cart->countProducts(); ?> items in cart</span>
                                          </div>
                                          </a>
                                        </div>


                 <?php if($this->config->get('wallet' . '_status')){?>
                     <div class="butn setui" style="position:relative;z-index:-1000; padding-right:0px;">
                    <a href=<?= $wallet_url ?>>
                      <div class="btn mini-wallet-button" role="button"  
                          id="mini-wallet-button"
                        style="margin-right: -10px;margin-top:0px;display:flex;flex-flow: column nowrap;background-color:transparent!important">
                        <div style="display:flex;align-items: center;align-content: center;justify-content: center;">
                          <i class="fa fa-money"></i>&nbsp;&nbsp;                           
                        </div>
                        <span class="badge mini-wallet" style="margin: 8px 0px;">
                           <?= $wallet_amount ?>
                        </span>
                      </div>
                      </a>
                    </div>

                  <?php }?>
                                    </div>
                                    </div>
                       <?php } ?>
                     </ul>
                  </div>
  <div class="header__navigation-container" role="navigation">
                  <div class="header__primary-navigation-outer-wrapper">
                      <div class="header__logo-container">
                        <a href="<?= BASE_URL;?>">
                        <img src="<?=$logo?>">
                        </a>

                     </div>
                     <div class="header__primary-navigation-wrapper">

                        <div class="header__primary-navigation-list">
                         <!--<span class ="organic_logo"><img src="<?=$logo?>"></span>-->
                        </div>
                     </div>



               </div>
  </header>

    <div class="checkout-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="container help-search">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div id='help_search_form' action="#">
                                    <div class="form-group">
                                        <input type="search" placeholder="<?= $text_how_can_help ?>" name="q" class="help_search" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        

                              <!--Cart HTML Start-->
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
  <!--Cart HTML End-->



  
   <!-- Modal -->
    <div class="addressModal">
        <div class="modal fade" id="exampleModal_deliverycharge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <div class="row">
                            <div class="col-md-12">
                              <h2>Accept Delivery Charge</h2>
                            </div>
                            <div class="modal-body">

                             

                            <p style="font-weight: bold; font-size: 12px;"><span id="min_required_free_delivery" style="font-weight: bold; font-size: 12px;"></span>  -  away from minimum order value.  Delivery charge - <span id="min_required_free_delivery_charge" style="font-weight: bold; font-size: 12px;"></span>  will be added<span style="color:#ea7128;"></span></p>
                            </div>
                            <div class="addnews-address-form">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button style="width:40%" id="agree_delivery_charge" name="agree_delivery_charge" type="button" class="btn btn-primary">I AGREE</button>
                                        <button style="width:40%" id="cancel_delivery_charge" name="cancel_delivery_charge" type="button" class="btn btn-grey  cancelbut" data-dismiss="modal">DECLINE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->



        <script>
        $(document).ready(function(){
  $(".newset").mouseleave(function(){
    $(".dropdownset").css("display", "none");
  });
  $(".newset").mouseover(function(){
    $(".dropdownset").css("display", "block");
  });

   $(".dropdownset").mouseleave(function(){
    $(".dropdownset").css("display", "none");
  });
  $(".dropdownset").mouseover(function(){
    $(".dropdownset").css("display", "block");
  });
});
            $(function(){
                $('.help_search').on('keydown', function(e){
                    if(e.keyCode === 13){
                        $q = $('.help_search').val();
                        location = '<?= $this->url->link('information/help/search') ?>&q='+$q;
                    }
                });
            });

             $(document).delegate('#clearcart', 'click', function () {
    var choice = confirm($(this).attr('data-confirm'));

    if (choice) {

      $.ajax({
        url: 'index.php?path=checkout/cart/clear_cart',
        type: 'post',
        data: '',
        dataType: 'json',
        success: function (json) {
          if (json['location']) {
            location = json.redirect;
            location = location;
          }
        }
      });
    }
  });

$("#mini-cart-button").click(function () {
    $("#toTop").show();
    $("#toTop").css('opacity', '1.0');
  });





 $('#agree_delivery_charge').on('click', function(){
        $.ajax({
            url: 'index.php?path=checkout/confirm/AcceptDeliveryCharge',
            type: 'post',
            data: 'accept_terms=true',
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(json) {
                console.log(json);
                if (json['delivery_charge_terms']) {
                   $('#exampleModal_deliverycharge').modal('hide');
                   window.location.href = "<?= $continue.'/index.php?path=checkout/checkoutitems'; ?>";
                }else{
                  $('#exampleModal_deliverycharge').modal('show');
                }
            }
        });
        });




function checkMinimumOrderTotal() {

    $.ajax({
        url: 'index.php?path=checkout/confirm/CheckMinimumOrderTotal',
         type: 'post',
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {
            },
        success: function(json) {
            if (json['min_order_total_reached']=="FALSE") {
                      //$("#proceed_to_checkout").addClass("disabled"); 
                 $('#exampleModal_deliverycharge').modal('show');
                  $('#min_required_free_delivery').text(json['amount_required']);
                  $('#min_required_free_delivery_charge').text(json['delivery_charge']);

                             return false;
                }else{
            //$("#proceed_to_checkout").removeClass("disabled"); 

                 $('#exampleModal_deliverycharge').modal('hide');  
                 $('#min_required_free_delivery').text('');   
                  $('#min_required_free_delivery_charge').text('');

                  window.location.href = "<?= $continue.'/index.php?path=checkout/checkoutitems'; ?>";


                   
                }
        },
        error: function(xhr, ajaxOptions, thrownError) {
        }
    });
}




        </script>
    </div>
  <?= $login_modal ?>
  <?= $signup_modal ?>
  <?= $forget_modal ?>
  <?= $contactus_modal ?>



<style>



modal.left .modal-dialog, .modal.right .modal-dialog {
width: 550px;
}
.mycart-header {
    padding: 20px;
}
 .store-cart-panel .modal-header .close {
     margin-right: -220px;
 }

 .clear-cart {
       margin-right: -180px;
 }

 #agree_delivery_charge {
    width: 49%;
    float: left;
    margin-top: 10px;
    margin-right: 5px;
    }

</style>

<style>

.mini-wallet-button
{
  color: #ea7128!important;
    background-color: transparent!important;
    box-shadow: none;
    border: none!important;
    padding: 6px 14px;
    border-radius: 2px;
    font-size: 15px;
    font-weight: 600;
}

.mini-wallet
{
  background-color:#ea7128
}

.mini-wallet-button:hover {
    color: #ccc !important;
}
</style>