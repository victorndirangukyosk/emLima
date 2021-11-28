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
    
    <!-- Bootstrap -->
    <link href="<?= $base ?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/style.css?v=5.2">
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/mycart.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/custom.css?v=1.1.0">

    

    
    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base; ?>front/ui/javascript/common.js?v=2.0.5" type="text/javascript"></script>
    <script src="<?= $base; ?>front/ui/theme/mvgv2/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
    

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
  
    <header>
    <div id="header">
      <div class="container">
        <div class="header-container row">
          <div class="logo">    <a href="<?=(isset($_SERVER['HTTPS']) ? 'https' : 'http' ). "://" . $_SERVER['SERVER_NAME']; ?>" class="hidden-xs hidden-sm header_item_content">
          <img src="<?= $logo ?>" alt=""><!--<span class="logo-text">Emlima</span>-->
          </a>
                 </div>
         
          
          <!--row-->
          
          <div class="fl-header-right">
            <div class="fl-links">
              <div class="no-js">
               <?php if($is_login) { ?>
               <a title="Company" class="clicker"></a>
               <?php }else{ ?>
                <div class=top-left-login-signup>Login / Signup</div>
               <?php } ?>

                <div class="fl-nav-links">
                  
                  <ul class="links">
					          <li>My Account</li>
                     <?php if($is_login) { ?>
                              <li>
                                <div class="user-profile"><span class="user-profile-img"><img src="<?= $base ?>front/ui/theme/mvgv2/images/user-profile.png"></span>
                                    <a href="<?= $account ?>" > <span class="user-name"><?= $full_name ?></span> </a>
                                </div>
                            </li>
                            <li><a href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></li>
                            <li><a href="<?= $address ?>" ><i class="fa fa-address-book"></i><?= $label_my_address ?></a></li>
                            <?php if($this->config->get('config_credit_enabled')) { ?>

                                <li><a href="<?= $credit ?>" ><i class="fa fa-money"></i><?= $text_my_cash ?></a></li>
                            <?php } ?>
                            <!--<li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>-->
                            <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                            <li><a href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></li>

                     <?php }else {?>
                      <li><a href="#"  type="button" data-toggle="modal" data-target="#phoneModal"><i class="fa fa-sign-in"></i><?= $text_sign_in ?></a></li>
                      <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#signupModal-popup"><i class="fa fa-user-plus"></i><?= $text_register ?></a></li>
                      <!--<li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>-->
                      <li class="last"><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
          
            <div class="fl-cart-contain">
             <div class="header_item">
                <div class="store-cart-action header-item">
                    <button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
                        <span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
                        <i class="fa fa-shopping-cart"></i> 
                        <span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
                    </button>
                </div>
            </div>
            <?php /* ?>
              <div class="mini-cart">
                <div class="basket"> <a href="shopping-cart.html"><span> 2 </span></a> </div>
                <div class="fl-mini-cart-content" style="display: none;">
                  <div class="block-subtitle">
                    <div class="top-subtotal">2 items, <span class="price">$259.99</span> </div>
                    <!--top-subtotal--> 
                    <!--pull-right--> 
                  </div>
                  <!--block-subtitle-->
                  <ul class="mini-products-list" id="cart-sidebar">
                    <li class="item first">
                      <div class="item-inner"><a class="product-image" title="timi &amp; leslie Sophia Diaper Bag, Lemon Yellow/Shadow White" href="#l"><img alt="timi &amp; leslie Sophia Diaper Bag, Lemon Yellow/Shadow White" src="products-images/p4.jpg"></a>
                        <div class="product-details">
                          <div class="access"><a class="btn-remove1" title="Remove This Item" href="#">Remove</a> <a class="btn-edit" title="Edit item" href="#"><i class="icon-pencil"></i><span class="hidden">Edit item</span></a> </div>
                          <!--access--> 
                          <strong>1</strong> x <span class="price">$179.99</span>
                          <p class="product-name"><a href="product-detail.html">Fresh Organic Mustard Leaves</a></p>
                        </div>
                      </div>
                    </li>
                    <li class="item last">
                      <div class="item-inner"><a class="product-image" title="JP Lizzy Satchel Designer Diaper Bag - Slate Citron" href="#"><img alt="JP Lizzy Satchel Designer Diaper Bag - Slate Citron" src="products-images/p3.jpg"></a>
                        <div class="product-details">
                          <div class="access"><a class="btn-remove1" title="Remove This Item" href="#">Remove</a> <a class="btn-edit" title="Edit item" href="#"><i class="icon-pencil"></i><span class="hidden">Edit item</span></a> </div>
                          <!--access--> 
                          <strong>1</strong> x <span class="price">$80.00</span>
                          <p class="product-name"><a href="product-detail.html">Fresh Organic Mustard Leaves</a></p>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <div class="actions">
                    <button class="btn-checkout" title="Checkout" type="button" onClick="window.location=checkout.html"><span>Checkout</span></button>
                  </div>
                  <!--actions--> 
                </div>
                <!--fl-mini-cart-content--> 
              </div>
               <?php */ ?>
            </div>
            <!--mini-cart-->
           
            <div class="collapse navbar-collapse">
              <form  id="product-search-form"  class="navbar-form active" role="search" onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
                <div class="input-group">
                  <input type="text" name="product_name"  placeholder="Search for your product" />
                  <span class="input-group-btn">
                  <button type="submit" class="search-btn"> <span class="glyphicon glyphicon-search"> <span class="sr-only">Search</span> </span> </button>
                    <div class="resp-searchresult">
                                    <div></div>
                                </div>
                  </span> </div>
              </form>
              <!--<form class="navbar-form active" role="search" onsubmit="location='<?= $link ?>&filter=' + $('input[name=\'store_search\']').val(); return false;">
                <div class="input-group">
                  <input type="text" class="form-control" name="store_search" placeholder="<?= $text_search_store ?>" value="<?= $filter?>" >
                  <span class="input-group-btn">
                  <button type="submit" class="search-btn"> <span class="glyphicon glyphicon-search"> <span class="sr-only">Search</span> </span> </button>
                  </span> </div>
              </form>-->
            </div>
            <!--links--> 
          </div>
        </div>
      </div>
    </div>
    <!-- Popup on Home Page start -->
     <div id="popup" style="display:none" class="popup panel panel-primary">
       <!--<button type="button" id="close" class="close" aria-label="Close">
       <span aria-hidden="true">&times;</span>
       </button>-->
       
        <!-- and here comes the image -->
        <img width="955px" src="front/ui/theme/organic/images/register-pop-up-image.png">
            <button type="button" data-toggle="modal" data-target="#phoneModal" class="btn banner-reg-btn">Register Now</button> 
             <button type="button" id="close" class="btn banner-reg-close">Skip</button> 
            <!-- Now this is the button which closes the popup-->

      </div>
       <!-- Popup on Home Page End -->
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
