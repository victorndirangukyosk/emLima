<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="kdt:page" content="home-page"> 

<meta http-equiv="content-language" content="<?= $config_language?>">
    
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<title><?= $heading_title ?></title>
<?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
	
<link rel="shortcut icon" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/favicon.png" type="image/x-icon">
<link rel="icon" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/favicon.png" type="image/x-icon">

<!-- CSS Style -->
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/font-awesome.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/revslider.css" >
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/owl.theme.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/jquery.bxslider.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/jquery.mobile-menu.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/style.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/responsive.css" media="all">
<link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600,800,400' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i,900" rel="stylesheet">
</head>

<body>
<div id="preloader"></div>
<div id="page">

  <header>
    
    <div id="header">
      <div class="container">
        <div class="header-container row">
          <div class="logo"> <a href="index.html" title="index">
            <div><img src="<?=$logo?>" alt="logo"></div>
            </a> </div>
          <div class="fl-nav-menu">
            <nav>
              <div class="mm-toggle-wrap">
                <div class="mm-toggle"><i class="icon-align-justify"></i><span class="mm-label">Menu</span> </div>
              </div>
              <div class="nav-inner"> 
                <!-- BEGIN NAV -->
                <ul id="nav" class="hidden-xs">
                  <li  data-link ="home"> <a class="level-top" ><span>Home</span></a></li>
                  <li  data-link ="about"> <a class="level-top"  ><span>About Us</span></a> </li>
                  <li data-link ="whom"> <a class="level-top" ><span>Who We Serve</span></a> </li>
                  <li data-link ="works"> <a class="level-top"><span>How It Works</span></a> </li>
                  
                  <li data-link ="contact"> <a class="level-top"><span>Contact Us</span></a> </li>
                </ul>
                <!--nav--> 
              </div>
            </nav>
          </div>
          
          <!--row-->
          
          <div class="fl-header-right">
            <div class="fl-links">
              <div class="no-js"> <a href="<?= $base;?>?action=shop" title="Shop Now" class="clicker"></a>
               
              </div>
            </div>
            <!--mini-cart-->
            
            <!--links--> 
          </div>
        </div>
      </div>
    </div>
  </header>
  <!--container-->
  
  <div class="content" >
    <div id="thmg-slider-slideshow" class="thmg-slider-slideshow" id="home">
      <div class="container">
        <div id='thm_slider_wrapper' class='thm_slider_wrapper fullwidthbanner-container' >
          <div id='thm-rev-slider' class='rev_slider fullwidthabanner'>
            <ul>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/slide-img1.jpg'><img src='<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/slide-img1.jpg'  data-bgposition='left top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image1" />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='270'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Fresh Produce</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='350'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'>Directly From <span>Farm</span></div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='570'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href="<?= $base;?>?action=shop" class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='470'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>We supply high quality farm fresh produce</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/slide-img2.png'><img src='<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/slide-img2.png'  data-bgposition='left top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='270'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>Hand Picked</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='350'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>Quality</span> Check</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='570'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href="<?= $base;?>?action=shop" class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='470'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>We pick fresh and quality produce from farms</div>
                </div>
              </li>
              <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/slide-img3.png'><img src='<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/slide-img3.png'  data-bgposition='left top'  data-bgfit='cover' data-bgrepeat='no-repeat' alt="slider-image2"  />
                <div class="info">
                  <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='0'  data-y='270'  data-endspeed='500'  data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'><span>For Businesses</span></div>
                  <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='0'  data-y='350'  data-endspeed='500'  data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'><span>Special</span> Pricing</div>
                  <div class='tp-caption sfb  tp-resizeme ' data-x='0'  data-y='570'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'><a href="<?= $base;?>?action=shop" class="buy-btn">Shop Now</a></div>
                  <div    class='tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='470'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>Get Special Pricing on Subscription Based Orders</div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Category slider Start-->
   
    <!--Category silder End -->
    
    <div class="offer-banner-section">
  <div class="container">
    <div class="row">
      <div class="offer-inner col-lg-12"> 
        <!--newsletter-wrap-->
        <div class="left">
          <div class="col"><a href="#"><img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/offer-banner1.jpg" alt="offer banner1"></a></div>
          <div class="col"><a href="#"><img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/offer-banner2.jpg" alt="offer banner2"></a></div>
          <div class="col"><a href="#"><img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/offer-banner3.jpg" alt="offer banner2"></a></div>
        </div>
      </div>
    </div>
  </div>
</div>
    
    <!-- best Pro Slider -->
    
    <div class="mid-section" id="about">
       
      <div class="container">
        <div class="row">
          <h2>ABOUT US</h2>
        </div>
        <div class="row">
          <div class="offer-inner col-lg-12 col-xs-12"> 
            <!--newsletter-wrap-->
            <div class="left">
              <div class="col-md-5"><img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/About-Us-Banner.png" alt="offer banner1"></div>
              <div class="col-md-7">
               <div class="col-md-12 col-xs-12">
                  <p class="about-p">Kwik Basket is focused in servicing commercial Kitchens within Nairobi. 
                      We supply farm fresh produce directly from the farmers. We are specialized 
                      in supplying fresh produce through our well-equipped distribution and packing 
                      facilities.</p><br/>
                      <p class="about-p">
                          
                          We provide helpful information to farmers on the demand requirements to sell 
                          their fresh produce through Kwik Basket – and help Businesses/ Retailers/ 
                          Commercial Kitchens to buy farm fresh produce at competitive pricing.
                      </p>

               </div>
               <div class="col-md-12 mt20 about-p col-xs-12">
                  Kwik Basket has a number of exciting benefits for farmers and customers. 
                  We source all our products from local farmers and are available in their 
                  freshest possible condition. We avoid processing and packaging in order to 
                  maximize quality and freshness. Secondly, we understand the challenges farmers 
                  face to gain access to markets. Kwik Basket platform makes selling products easier 
                  for farmers and also benefits customers by allowing them to enjoy the highest quality 
                  produce and enjoy fresh fruit and veg delivery.
               </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="hot-section" id="whom">
      <div class="container">
        <div class="row">
          <div class="ad-info">
            <h2>Who We Serve</h2>
          </div>
        </div>
        <div class="row">
          <div class="hot-deal">
           
            <ul class="products-grid">
              <li class="item col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="item-inner">
                  <div class="item-img">
                    <div class="item-img-info">
                      <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/commercial-kitchen.jpg" alt="Commercial Kitchen & Hostels">
                    </div>
                  </div>
                  <div class="item-info">
                    <div class="info-inner">
                      <div class="item-title">
                          Commercial Kitchen & Hostels </div>
                      <div class="item-content">  
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="item col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="item-inner">
                      <div class="item-img">
                        <div class="item-img-info">
                          <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/Hospital.png" alt="Hospitals ">
                        </div>
                      </div>
                      <div class="item-info">
                        <div class="info-inner">
                          <div class="item-title">
                              Hospitals</div>
                          <div class="item-content">
                          </div>
                        </div>
                      </div>
                    </div>
              </li>
              <li class="item col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="item-inner">
                      <div class="item-img">
                        <div class="item-img-info">
                          <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/University-College.png" alt="Universities & Colleges">
                        </div>
                      </div>
                      <div class="item-info">
                        <div class="info-inner">
                          <div class="item-title">
                              Universities & Colleges</div>
                          <div class="item-content"> 
                          </div>
                        </div>
                      </div>
                    </div>
              </li>
              <li class="item col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="item-inner">
                      <div class="item-img">
                        <div class="item-img-info">
                          <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/Restaurant-hotels.png" alt="Restaurants & Hotels ">
                        </div>
                      </div>
                      <div class="item-info">
                        <div class="info-inner">
                          <div class="item-title">
                              Restaurants & Hotels</div>
                          <div class="item-content">
                          </div>
                        </div>
                      </div>
                    </div>
              </li>
            </ul>
          </div>
        </div>
        
        <div class="row">
            <h2 class="partners">Our Partners</h2>
          <div class="logo-brand">
            <div class="col">
              <div  class="product-flexslider hidden-buttons">
                <div class="col-md-12 col-xs-12"> 
                  <!-- Item -->
                  <div class="item col-md-3 col-xs-12">
                    <div class="logo-item">
                    <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/ETG-Logo.png" alt="Image">
                    </div>
                  </div>
                  <div class="item col-md-3 col-xs-12">
                    <div class="logo-item">
                    <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/veg-pro1.png" alt="Image">
                    </div>
                  </div>
                  <!-- End Item --> 
                  <!-- Item -->
                  <div class="item col-md-3 col-xs-12">
                    <div class="logo-item">
                    <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/TBL-EL.png" alt="Image" class="tbl-img">
                    </div>
                  </div>
                  <div class="item col-md-3 col-xs-12">
                    <div class="logo-item pl40">
                    <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/Quatrix-logo.png" alt="Image">
                    </div>
                  </div>
                  <!-- End Item --> 
                 
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mid-section how-work" id="works">
        <div class="container">
          <div class="row">
            <h2>How It works</h2>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 works">
              
                <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/1.png"/>
                
                            
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12  works">
                
                <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/2.png"/>
                
              
                
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 works">
                
                  <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/3.png"/>
                  
                
                    
                  </div>
            </div>
            
               
              </div>
              
          </div>
        </div>
      </div>
    <!-- Logo Brand Block --> 
    
  </div>
 
  <div class="container">
    <div class="row our-features-box">
      <ul>
        <li>
          <div class="feature-box">
            <div class="icon-truck"></div>
            <div class="content">FREE SHIPPING </div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            <div class="icon-support"></div>
            <div class="content">Have a question?<br>
              +254 780 703 586</div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            <div class="icon-money"></div>
            <div class="content">Customized Discounts & Pricing</div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            <div class="icon-return"></div>
            <div class="content">Easy Return Policy</div>
          </div>
        </li>
        <li class="last">
          <div class="feature-box android-app">  <a href="https://play.google.com/store/apps/details?id=com.kwikbasket.customer"><i class="fa fa-android"></i> download</a> </div>
        </li>
      </ul>
    </div>
  </div>
  <!-- For version 1,2,3,4,6 -->
   
  <footer> 
    <!-- BEGIN INFORMATIVE FOOTER -->
    <div class="footer-inner">
      <div class="newsletter-row">
        <div class="container">
          <div class="row"> 
            
            <!-- Footer Newsletter -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col1" id="contact">
              <div class="newsletter-wrap">
                <h2>Contact Us</h2>
                <div id="contactus-message"></div>
				<div id="contactus-success-message"></div>
                <form action="" id="contactForm" method="post">
                    <div class="static-contain">
                      <fieldset class="group-select">
                        <ul>
                          <li id="billing-new-address-form">
                            <fieldset class="">
                              <ul>
                                <li>
                                  <div class="customer-name">
                                    <div class="input-box name-firstname">
                                      <label for="name"><em class="required">*</em>Name</label>
                                      <br>
                                      <input name="name" id="input-name" title="name" value="" placeholder="john doe" class="input-text required-entry" type="text">
                                    </div>
                                    <div class="input-box name-firstname">
                                      <label for="email"><em class="required">*</em>Email</label>
                                      <br>
                                      <input name="email" id="input-email" title="email" placeholder="john.doe@gmail.com" value="" class="input-text required-entry validate-email" type="text">
                                    </div>
                                  </div>
                                </li>
                                <li>
                                    <div class="input-box name-firstname">
                                        <label for="telephone">Telephone</label>
                                        <br>
                                        <input name="telephone" maxlength="11" id="input-telephone" title="telephone" value="" class="input-text" type="text">
                                      </div>
                                      <div class="input-box name-firstname">
                                        <label for="company-name"><em class="required">*</em>Company Name</label>
                                        <br>
                                        <input name="company-name" id="input-company-name" title="company-name" value="" class="input-text required-entry" type="text">
                                      </div>
                                 
                                 
                                  <br>
                                  
                                </li>
                                <li>
                                  <label for="comment"><em class="required">*</em>Comment</label>
                                  <br>
                                  <textarea name="enquiry" id="input-enquiry" title="Comment" class="required-entry input-text" cols="5" rows="3"></textarea>
                                </li>
                              </ul>
                            </fieldset>
                          </li>
                          <p class="require"><em class="required">* </em>Required Fields</p>
                          <input type="text" name="hideit" id="hideit" value="" style="display:none !important;">
                          <div class="buttons-set">
                            <button id="contactus" type="button" name="next"  type="submit" title="Submit" class="button submit"><span><span>Submit</span></span></button>
                          </div>
                        </ul>
                      </fieldset>
                    </div>
                  </form>
              </div>
              <!--newsletter-wrap--> 
            </div>
          </div>
        </div>
        <!--footer-column-last--> 
      </div>
     
      
      <!--container--> 
    </div>
    <!--footer-inner--> 
    
    <!--footer-middle-->
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-4">
            <div class="social">
              <ul>
                <li class="fb"><a href="#"></a></li>
                <li class="tw"><a href="#"></a></li>
                <li class="linkedin"><a href="#"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12 coppyright"> © 2020 Kwik Baskets. All Rights Reserved. </div>
          <div class="col-xs-12 col-sm-4">
            <div class="payment-accept"> <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/payment-1.png" alt=""> <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/payment-2.png" alt=""> <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/payment-3.png" alt=""> </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <!--footer-bottom--> 
    <!-- BEGIN SIMPLE FOOTER --> 
  </footer>
  <!-- End For version 1,2,3,4,6 --> 
  
</div>
<!--page--> 
<!-- Mobile Menu-->
<div id="mobile-menu">
  <ul class="mobile-menu">
  <li>
      <div class="mm-search">
       <img src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/logo.png" alt="logo"/>
      </div>
    </li>
    <li data-link ="home"><div class="home">Home </div> </li>
    <li data-link ="about"><a href="#about">About Us</a></li>
    <li data-link ="whom"><a href="#whom">Whom We Serve</a></li>
    <li data-link ="works"><a href="#works">How It Works</a></li>
    <li data-link ="contact"><a href="#contact">Contact Us</a></li>
  </ul>
  
</div>



<!-- JavaScript --> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/bootstrap.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/parallax.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/revslider.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/common.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.bxslider.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/owl.carousel.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.mobile-menu.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/countdown.js"></script> 
<script>

jQuery('input[name="telephone"]').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
});
 jQuery("ul#nav >li").click(function() {
       var minus = 190;
       var id = jQuery(this).attr('data-link');
 
      if(jQuery( "#header" ).hasClass( "sticky-header-bar") == true){
        minus = 100;
      }
	  if(id != 'home'){
		  jQuery('html, body').animate({
			scrollTop: jQuery("#"+id).offset().top-minus
		},2000);
	  }else{
	     jQuery("html, body").animate({ scrollTop: 0 }, 2000);
	  }
   
});
        jQuery(document).ready(function(){
            jQuery('#thm-rev-slider').show().revolution({
                dottedOverlay: 'none',
                delay: 5000,
                startwidth: 0,
                startheight:750,

                hideThumbs: 200,
                thumbWidth: 200,
                thumbHeight: 50,
                thumbAmount: 2,

                navigationType: 'thumb',
                navigationArrows: 'solo',
                navigationStyle: 'round',

                touchenabled: 'on',
                onHoverStop: 'on',
                
                swipe_velocity: 0.7,
                swipe_min_touches: 1,
                swipe_max_touches: 1,
                drag_block_vertical: false,
            
                spinner: 'spinner0',
                keyboardNavigation: 'off',

                navigationHAlign: 'center',
                navigationVAlign: 'bottom',
                navigationHOffset: 0,
                navigationVOffset: 20,

                soloArrowLeftHalign: 'left',
                soloArrowLeftValign: 'center',
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,

                soloArrowRightHalign: 'right',
                soloArrowRightValign: 'center',
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,

                shadow: 0,
                fullWidth: 'on',
                fullScreen: 'on',

                stopLoop: 'off',
                stopAfterLoops: -1,
                stopAtSlide: -1,

                shuffle: 'off',

                autoHeight: 'on',
                forceFullWidth: 'off',
                fullScreenAlignForce: 'off',
                minFullScreenHeight: 0,
                hideNavDelayOnMobile: 1500,
            
                hideThumbsOnMobile: 'off',
                hideBulletsOnMobile: 'off',
                hideArrowsOnMobile: 'off',
                hideThumbsUnderResolution: 0,

                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                startWithSlide: 0,
                fullScreenOffsetContainer: ''
            });
        });
        </script> 
<script>
    function HideMe()
    {
        jQuery('.popup1').hide();
        jQuery('#fade').hide();
    }
</script> 
<!-- Hot Deals Timer 1--> 
<script>
      var dthen1 = new Date("12/25/17 11:59:00 PM");
      start = "08/04/15 03:02:11 AM";
      start_date = Date.parse(start);
      var dnow1 = new Date(start_date);
      if (CountStepper > 0)
      ddiff = new Date((dnow1) - (dthen1));
      else
      ddiff = new Date((dthen1) - (dnow1));
      gsecs1 = Math.floor(ddiff.valueOf() / 1000);
      
      var iid1 = "countbox_1";
      CountBack_slider(gsecs1, "countbox_1", 1);
    </script>
    
<script>    
    jQuery(document).ready(function($) {  

// site preloader -- also uncomment the div in the header and the css style for #preloader
$(window).load(function(){
	$('#preloader').fadeOut('fast',function(){$(this).remove();});
});

});


jQuery(document).delegate('#contactus', 'click', function() {
    console.log("contactus click");

    var text = jQuery('.contact-modal-text').html();
    jQuery('.contact-modal-text').html('');
    jQuery('.contact-loader').show();


    jQuery.ajax({
        url: 'index.php?path=information/contact',
        type: 'post',
        data: jQuery('#contactForm').serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            console.log(json);
            if (json['status']) {
                jQuery('#contactus-message').html('');
                jQuery('#contactus-success-message').html("<p style='color:green'>"+json['text_message']+"</p>");

                jQuery('.contact-modal-text').html(text);
                jQuery('.contact-loader').hide();

                
                jQuery('#contactusModal').find('form').trigger('reset');
				jQuery('#contactForm').trigger("reset");
                
            } else {
                $error = '';

                if(json['error_email']){
                    $error += json['error_email']+'<br/>';
                }
				if(json['error_name']){
                    $error += json['error_name']+'<br/>';
                }
				
				if(json['error_company']){
                    $error += json['error_company']+'<br/>';
                }
				
                if(json['error_enquiry']){
                    //$error += json['error_enquiry']+'<br/>';
				    $error +='Comments must be between 10 and 3000 characters!<br/>';
                }
              
                jQuery('.contact-modal-text').html(text);
                jQuery('.contact-loader').hide();

                jQuery('#contactus-message').html("<p style='color:red'>"+$error+"</p>");
            }
        }
    });
});
   </script>
</body>
</html>
