<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="kdt:page" content="store-listing-page">
    <meta http-equiv="content-language" content="<?= $config_language?>">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>

    <title><?= $title ?></title>


     <link href="<?= $base;?>front/ui/theme/metaorganic/assets/images/favicon.ico" rel="icon">
    <!-- BEGIN CSS -->
     <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/innerpage.min.css">
      <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.min.css">
      <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/innerpage1.min.css">

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.min.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.min.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.min.css" rel="stylesheet">

    <!-- END CSS -->


    <!-- Bootstrap -->
    <link href="<?= $base ?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/style.min.css?v=5.2">
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/mycart.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/custom.min.css?v=1.1.0">




    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base; ?>front/ui/javascript/common.js?v=2.0.5" type="text/javascript"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>


    <?php if ($kondutoStatus) { ?>
    <script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>
    <?php } ?>
    <?php include 'assets.php';?>
    <script src="<?= $base;?>front/ui/javascript/easyzoom.js"></script>

</head>

<body>
<div id="preloader"></div>
    <div class="alerter">
        <?php if($notices){ ?>
            <div class="alert alert-info normalalert">
                <?php foreach($notices as $notice){ ?>
                    <p class="notice-text"><?= $notice ?></p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

  <header>
<div class="col-md-12" style="position: relative; z-index: 1040;  padding-bottom: 20px; border-bottom: 1px solid #ea6f28; margin-bottom: 14px;">

      <div class="row" >
       <div class="col-md-2">
                <div class="header__logo-container">
                     <a class="header__logo-link " href="<?= BASE_URL?>">
                        <img src="<?=$logo?>" />

                     </a>

                </div>
      </div>
      <div class="col-md-5">
                <div class="header__search-bar-wrapper">
                  <div id="search-form-wrapper" class="header__search-bar search-form-wrapper">
                     <div class="header__search-title">
                        Search
                        <div class="header__mobile-search-close j-mobile-close-search-trigger"></div>
                     </div>

                     <form id="search-form-form" class="search-form c-position-relative search-form--switch-category-position" action="#" method="get">
                        <ul class="header__search-bar-list header__search-bar-item--before-keyword-field">

                           <li class="header__search-bar-item header__search-bar-item--category search-category-container">
                           <div >
                              <select class="form-control" id="selectedCategory">
                                 <option value="">- Select Categories -</option>
                                  <?php foreach($categories as $categoty){
                                     //print_r($categoty);exit;?>
                                 <option value="<?=$categoty['id']?>" <?php if(isset($this->request->get['filter_category']) && $this->request->get['filter_category'] > 0 && $this->request->get['filter_category'] == $categoty['id']) { echo "selected"; } ?> ><?=$categoty['name']?></option>
                                  <?php } ?>

                              </select>
                           </div>
                           </li>
                           <li class="header__search-bar-item header__search-bar-item--location search-location-all">
                              <div class="header__search-location search-location">
                                    <i class="fas fa-search header__search-location-icon" aria-hidden="true"></i>

                                 <!-- SuggestionWidget  start -->
                                 <div id="search-area-wrp" class="c-sggstnbx header__search-input-wrapper">
                                    <form  id="product-search-form"  class="navbar-form active" role="search" onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
									<div class="input-group">
									<input type="text" name="product_name" id="product_name"  class="header__search-input zipcode-enter" placeholder="Search for your product" />
									<span class="input-group-btn">
									<!--<button type="submit" class="search-btn"> <span class="glyphicon glyphicon-search"> <span class="sr-only">Search</span> </span> </button>-->
									<div class="resp-searchresult">
												<div></div>
											</div>
									</span> </div>
									</form>

                                      <?php /* if($this->config->get('config_store_location') == 'autosuggestion') { ?>
                                              <input name="zipcode" id="searchTextField"  class="header__search-input zipcode-enter" type="text"  required="" alt=""  maxlength="" size="" tabindex="3" placeholder="Find Stores in your Location" highlight="y" strict="y" autocomplete="off">
                                            <?php } else { ?>
                                                <input name="zipcode" id="searchTextField"  class="header__search-input zipcode-enter" type="text"  required="" alt=""  maxlength="" size="" tabindex="3" placeholder="<?= $zipcode_mask ?>" highlight="y" strict="y" autocomplete="off">

                                            <?php } */ ?>



                                            <!--<input type="hidden" name="store_list_url" value="<?=BASE_URL ?>">

                                            <input type="hidden" id="store_location" value="<?= $this->config->get('config_store_location'); ?>">-->




                                 </div>
                              </div>
                           </li>
                           <!--<li class="header__search-bar-item header__search-bar-item--submit search-submit">
                              <button type="submit" tabindex="5" data-spinner-btn="" class="header__search-button">
                                    <i class="fa fa-search header__search-button-icon header__search-button-icon--search" aria-hidden="true"></i>

                                 <span class="header__search-button-text">Search</span>
                              </button>
                           </li>-->

                        </ul>
                     </form>
                  </div>
               </div>
      </div>
      <div class="col-md-5">
            <div class="header__navigation-container" role="navigation">

                  <div class="header__primary-navigation-outer-wrapper">

                     <div class="header__primary-navigation-item header__primary-navigation-item--more-categories" >

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
                        <li class="header__upper-deck-item header__upper-deck-item setcartbtn"><div class="butn setui"> <button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?> items in cart</span>
										<i class="fa fa-shopping-cart"></i>
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></div></li>
                        <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->

                             <div class="newset" style="margin-top: 20px;"><a class="btn" href="<?= $dashboard ?>" > <span ><?= $full_name ?></span> </a>

                           <div class="dropdownset" style="display:none; ">
                                  <div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link"
                                   href="<?= $dashboard ?>" ><i class="fa fa-user"></i>Dashboard &nbsp;<span class="badge badge-pill badge-light">New</span></a></div>
                                  <div class="dropdownsetnew"  ><a class="header__upper-deck-item-link"
                                   href="<?= $account ?>" ><i class="fa fa-user"></i>My Account</a></div>
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
                                    <a href=<?= $checkout_summary ?>>
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
                                    </div>
                                    </div>

                                    
                       <?php } ?>
                     </ul>
                  </div>

               </div>



            </div>
      </div>
      </div>

      </div>




  </header>

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
  $("span.glyphicon.glyphicon-search").click(function(){
    var currentpath  = window.location.href;
    var search_text =  $("input[name=store_search]").val();
    if(search_text == ''){
      alert('Please enter text to search');
    }else{
      window.location.href = currentpath+'&filter='+search_text;
    }

   });

    $("#mini-cart-button").click(function(){
      $("#toTop").show();
     $("#toTop").css('opacity','1.0');
    });
  </script>
