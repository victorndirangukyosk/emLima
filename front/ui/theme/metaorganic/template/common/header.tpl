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
     <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/innerpage.css">
      <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.css">
      <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/innerpage1.css">

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.css" rel="stylesheet">
    
    <!-- END CSS -->   
    

    <!-- Bootstrap -->
    <link href="<?= $base ?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/style.css?v=5.2">
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/mycart.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/custom.css?v=1.1.0">

    

    
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
                        <li class="header__upper-deck-item header__upper-deck-item">
                        <div class="butn setui"> <button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></div>
                        </li>
                       <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->
                            
                             <div class="newset"><a class="btn" href="<?= $account ?>" > <span ><?= $full_name ?></span> </a>     
                           
                           <div class="dropdownset" style="display:none;">
                                  <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></div>
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
                                     <div class="butn setui"> <button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></div>
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
                        <div itemscope="" class="seo-visible">
                           <a itemprop="url" href="#">Home</a>
                           <img itemprop="logo" src="<?=$logo?>" width="100" height="100">
                        </div>
                     </div>
                     <div class="header__primary-navigation-wrapper">
                      
                        <div class="header__primary-navigation-list"> 
                       <!--<span class ="organic_logo"><img src="<?=$logo?>"></span>--> 
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
