<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="<?= $config_language?>">

    <!-- <meta name="kdt:page" content="checkout-page"> -->
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    
    <?php } ?>
    <title><?= $title ?></title>
     <!-- BEGIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.css" rel="stylesheet">
    <!-- END CSS -->   

    <!-- Bootstrap -->
    
    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.css?v=5.1">
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/abhishek.css"> -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/mycart.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.css">
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="../../https@oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="../../https@oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<!--     <script src="<?= $base;?>front/ui/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script> 

    <script src="<?= $base;?>front/ui/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>  
 -->
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script src="<?= $base;?>front/ui/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
    <script type="text/javascript" src="https://js.iugu.com/v2"></script>
    <!--<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/metaorganic/stylesheet/style.css" media="all">-->
	<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/metaorganic/stylesheet/responsive.css" media="all">
    <script src="<?= $base;?>front/ui/javascript/easyzoom.js"></script>
</head>

<body>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><center><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></center>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
    
    <?php if ($success) { ?>

        <div class="alert alert-success normalalert">
            <p class="notice-text"> <?php echo $success; ?> </p>
        </div>

    <?php } ?>

    
    <div class="overlayed"></div>
    <div class="alerter">
        <?php if($notices){ ?>
            <div class="alert alert-danger normalalert">
                <?php foreach($notices as $notice){ ?>
                    <p class="notice-text"><?= $notice ?></p>
                <?php } ?>
            </div>
        <?php } ?>            
    </div>
    <header>
      
                 <div class="col-md-12" style="position: relative; z-index: 1040;">
      
      <div class="row">
      <div class="col-md-2">
                <div class="header__logo-container">
                     <a class="header__logo-link " href="<?= BASE_URL?>">
                        <img src="<?=$logo?>" />
                       
                     </a>
                     <div itemscope="" class="seo-visible">
                        <a itemprop="url" href="#">Home</a>
                        <img itemprop="logo" src="<?=$logo?>" width="100" height="100">
                     </div>
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
                           <div class="form-group">
                              <select class="form-control" id="selectedCategory">
                                 <option value="">- Select categories-</option>
                                  <?php foreach($categories as $categoty){
                                     //print_r($categoty);exit;?>
                                 <option value="<?=$categoty['id']?>"><?=$categoty['name']?></option>
                                  <?php } ?>
                                
                              </select>
                           </div>
                           </li>
                           <li class="header__search-bar-item header__search-bar-item--location search-location-all">
                              <div class="header__search-location search-location">
                                    <i class="fa fa-map-marker header__search-location-icon" aria-hidden="true"></i>
                                 
                                 <!-- SuggestionWidget  start -->
                                 <div id="search-area-wrp" class="c-sggstnbx header__search-input-wrapper">
                                    <form  id="product-search-form"  class="navbar-form active" role="search" onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
									<div class="input-group">
									<input type="text" name="product_name"   class="header__search-input zipcode-enter" placeholder="Search for your product" />
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
                     
                     <div class="header__primary-navigation-item header__primary-navigation-item--more-categories" style="margin-top: 17px;">
                        
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
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></div></li>
                        <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->
                            
                             <div class="newset"><a class="btn" href="<?= $account ?>" > <span ><?= $full_name ?></span> </a>     
                           
                           <div class="dropdownset" style="display:none; margin-top:3px;">
                                  <div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link" href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></div>
                                  <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $wishlist ?>" ><i class="fa fa-list-ul"></i><?= $text_my_wishlist?></a></div>
                                    <?php if($this->config->get('config_credit_enabled')) { ?>

                                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $credit ?>" ><i class="fa fa-money"></i><?= $text_my_cash ?></a></div>
                                    <?php } ?>

                                    <div class="dropdownsetnew"><a  class="header__upper-deck-item-link" href="<?= $address ?>" ><i class="fa fa-address-book"></i><?= $label_my_address ?></a></div>
                                   <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="#" class="header__upper-deck-item-link btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></div>
                                    <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></div>
                                    <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></div>
                                    </div>
                                    </div> 
                                     <div class="butn setui"><button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button" style="margin-right:10px; margin-top:0px">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></div>
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
  <?= $login_modal ?>
  <?= $signup_modal ?>
  <?= $forget_modal ?>
  <?= $contactus_modal ?>
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
