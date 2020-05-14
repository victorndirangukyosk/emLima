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
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.css">

    

    
    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base; ?>front/ui/javascript/common.js?v=2.0.5" type="text/javascript"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
    

    <?php if ($kondutoStatus) { ?>
    <script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>
    <?php } ?>
    <?php include 'assets.php';?>
    
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
  
    <header style="position: relative; z-index: 1040;  padding-bottom: 20px; border-bottom: 1px solid #ea6f28; margin-bottom: 14px;">
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
                        <li class="header__upper-deck-item header__upper-deck-item--signin">
                          <a href="<?= BASE_URL?>/checkout"><button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></a>
                        </li>
                        <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->
                            
                             <div class="newset"><a class="btn" href="<?= $account ?>" > <span ><?= $full_name ?></span> </a>     
                           
                           <div class="dropdownset" style="display:none; margin-top:-1px">
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
                                     <div class="butn setui"> <a href="<?= BASE_URL?>/checkout"><button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></a></div>
                                    </div>
                                    </div>
                       <?php } ?>
                     </ul>
                  </div>
      <div class="header__navigation-container" role="navigation">
                  <div class="header__primary-navigation-outer-wrapper">
                      <div class="header__logo-container">
                        <a href="<?= BASE_URL;?>">
                        <img src="<?=$logo?>" >
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
  
 <!-- Organic theme slider start --->
  <div id="thmg-slider-slideshow" class="thmg-slider-slideshow">
      <div class="container">
        <div id='thm_slider_wrapper' class='thm_slider_wrapper fullwidthbanner-container' >
          <div id='thm-rev-slider' class='rev_slider fullwidthabanner'>
            <ul>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='front/ui/theme/organic/images/maize.png'><img src='front/ui/theme/organic/images/maize.png'  data-bgposition='center top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='180'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Look</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='260'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>100%</span> Organic</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='480'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href='<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID) ?>' class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='380'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>Farm Fresh Produce Right to Your Door</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='front/ui/theme/organic/images/veg.png'><img src='front/ui/theme/organic/images/veg.png'  data-bgposition='center top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image1" />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='180'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Food</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='260'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'>Simply <span>delicious</span></div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='480'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href='<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID) ?>' class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='380'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>We supply highly quality organic products</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='front/ui/theme/organic/images/fru.png'><img src='front/ui/theme/organic/images/fru.png'  data-bgposition='center top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='180'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Look</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='260'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>100%</span> Organic</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='480'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href='<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID) ?>' class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='380'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>Farm Fresh Produce Right to Your Door</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='front/ui/theme/organic/images/mix.png'><img src='front/ui/theme/organic/images/mix.png'  data-bgposition='center top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='180'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Look</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='260'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>100%</span> Organic</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='480'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href='<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID) ?>' class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='380'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>Farm Fresh Produce Right to Your Door</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='front/ui/theme/organic/images/grain.png'><img src='front/ui/theme/organic/images/grain.png'  data-bgposition='center top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='180'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Look</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='260'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>100%</span> Organic</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='480'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href='<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID) ?>' class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='380'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>Farm Fresh Produce Right to Your Door</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='front/ui/theme/organic/images/mix1.png'><img src='front/ui/theme/organic/images/mix1.png'  data-bgposition='center top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='180'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Look</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='260'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>100%</span> Organic</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='480'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href='<?= $this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID) ?>' class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='380'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>Farm Fresh Produce Right to Your Door</div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Organic theme slider End --->
    <!--<div style="background-image: url(image/theme/digital_banner.jpg); background-size:cover; height:610px"> </div>-->
    
    <!--Category slider Start-->
    <div class="top-cate">
      <div class="featured-pro container">
        <div class="row">
          <div class="slider-items-products">
            <div id="top-categories" class="product-flexslider hidden-buttons">
              <div class="slider-items slider-width-col4 products-grid">
               <?php foreach($categories as $categoty){
                  $link_array = explode('/',$categoty['href']);
                   $page_link = end($link_array);
                 //echo '<pre>';print_r($go_to_store);exit; ?>
                 <div class="item"> <a href="<?=$this->url->link('product/store', 'store_id='.ACTIVE_STORE_ID).'?cat='.$page_link?>">
                  <div class="pro-img"><img src="<?=$categoty['thumb']?>" alt="Fresh Organic Mustard Leaves ">
                    <div class="pro-info"><h3><?=$categoty['name']?></h3></div>
                  </div>
                  </a> 
                </div>
               <?php }?>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Category silder End-->
  <?php  
  //echo'<pre>';print_r($categories);exit; ?>
    <!-- Latest Products Starts -->
    <?php /* ?>
    <section class=" wow bounceInUp animated">
      <div class="best-pro slider-items-products container">
        <div class="new_title">
        <img src="front/ui/theme/organic/images/icon2.png" alt="icon">
          <h2>Best Seller</h2>
        </div>
        <div id="best-seller" class="product-flexslider hidden-buttons">
          <div class="slider-items slider-width-col4 products-grid">
            <div class="item">
              <div class="item-inner">
                <div class="item-img">
                  <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p27.jpg" alt="Fresh Organic Mustard Leaves "></a>
                    <div class="new-label new-top-left">Hot</div>
                    <div class="sale-label sale-top-left">-15%</div>
                    <div class="item-box-hover">
                      <div class="box-inner">
                        <div class="product-detail-bnt"><a class="button detail-bnt"><span>Quick View</span></a></div>
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
                        <div class="price-box"><span class="regular-price" ><span class="price">$125.00</span> </span> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Item -->
            <div class="item">
              <div class="item-inner">
                <div class="item-img">
                  <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p17.jpg" alt="Fresh Organic Mustard Leaves "></a>
                    <div class="item-box-hover">
                      <div class="box-inner">
                        <div class="product-detail-bnt"><a class="button detail-bnt"><span>Quick View</span></a></div>
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
                        <div class="price-box"><span class="regular-price" ><span class="price">$125.00</span> </span> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Item --> 
            
            <!-- Item -->
            <div class="item">
              <div class="item-inner">
                <div class="item-img">
                  <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p7.jpg" alt="Fresh Organic Mustard Leaves "></a>
                    <div class="item-box-hover">
                      <div class="box-inner">
                        <div class="product-detail-bnt"><a class="button detail-bnt"><span>Quick View</span></a></div>
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
                        <div class="price-box"><span class="regular-price" ><span class="price">$125.00</span> </span> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Item -->
            
            <div class="item">
              <div class="item-inner">
                <div class="item-img">
                  <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p26.jpg" alt="Fresh Organic Mustard Leaves "></a>
                    <div class="sale-label sale-top-left">Sale</div>
                    <div class="item-box-hover">
                      <div class="box-inner">
                        <div class="product-detail-bnt"><a class="button detail-bnt"><span>Quick View</span></a></div>
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
                        <div class="price-box"><span class="regular-price" ><span class="price">$125.00</span> </span> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Item -->
            <div class="item">
              <div class="item-inner">
                <div class="item-img">
                  <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p5.jpg" alt="Fresh Organic Mustard Leaves "></a>
                    <div class="new-label new-top-left">New</div>
                    <div class="item-box-hover">
                      <div class="box-inner">
                        <div class="product-detail-bnt"><a class="button detail-bnt"><span>Quick View</span></a></div>
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
                        <div class="price-box"><span class="regular-price" ><span class="price">$125.00</span> </span> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Item --> 
            
            <!-- Item -->
            <div class="item">
              <div class="item-inner">
                <div class="item-img">
                  <div class="item-img-info"><a href="product-detail.html" title="Fresh Organic Mustard Leaves " class="product-image"><img src="front/ui/theme/organic/products-images/p6.jpg" alt="Fresh Organic Mustard Leaves "></a>
                    <div class="new-label new-top-left">New</div>
                    <div class="item-box-hover">
                      <div class="box-inner">
                        <div class="product-detail-bnt"><a class="button detail-bnt"><span>Quick View</span></a></div>
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
                        <div class="price-box"><span class="regular-price" ><span class="price">$125.00</span> </span> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Item --> 
          </div>
        </div>
      </div>
    </section>

    <?php */ ?>

    <!--- latest products end --->

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

    $(document).ready(function () {
    //select the POPUP FRAME and show it
    // getCookie('bannerClosed');
     if(getCookie('bannerClosed')!='true'){
          // setTimeout(function(){$("#popup").show('slow').fadeIn()},2000);
         
     }

    //close the POPUP if the button with id="close" is clicked
    $("#close").on("click", function (e) {
        e.preventDefault();
        $("#popup").fadeOut(1000);
        setCookie('bannerClosed',true);
    });
    $("button.btn.banner-reg-btn").on("click", function (e) {
        e.preventDefault();
        $("#popup").fadeOut(1000);
        setCookie('bannerClosed',true);
    });
    
    });
  </script>
