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
      <div class="header-container container">
        <div class="row">
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
  
  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
     <div class="page-title">
     <h2>Login or Create an Account</h2>
  </div>
        </div>
    </div>
        </div>
        </div>
    
    
    
    <div class="main-container col1-layout wow bounceInUp animated animated" style="visibility: visible;">     
              
      <div class="main">                     
                         <div class="account-login container">
<!--page-title-->

     <form action="" method="post" id="login-form">
     <input name="form_key" type="hidden" value="EPYwQxF6xoWcjLUr">
     <fieldset class="col2-set">
         <div class="col-1 new-users"> 
               <strong>New Customers</strong>    
             <div class="content">
                
                 <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                  <div class="buttons-set">
                 <button type="button" title="Create an Account" class="button create-account" onClick=""><span><span>Create an Account</span></span></button>
             </div>
             </div>
         </div>
         <div class="col-2 registered-users">
          <strong>Registered Customers</strong>             
             <div class="content">
                 
                 <p>If you have an account with us, please log in.</p>
                 <ul class="form-list">
                     <li>
                          <label for="email">Email Address<em class="required">*</em></label>
                         <div class="input-box">
                             <input type="text" name="login[username]" value="" id="email" class="input-text required-entry validate-email" title="Email Address">
                         </div>
                     </li>
                     <li>
                         <label for="pass">Password<em class="required">*</em></label>
                         <div class="input-box">
                             <input type="password" name="login[password]" class="input-text required-entry validate-password" id="pass" title="Password">
                         </div>
                     </li>
                                                                 </ul>
                 <div class="remember-me-popup">
 <div class="remember-me-popup-head" style="display:none">
     <h3 id="text2">What's this?</h3>
     <a href="#" class="remember-me-popup-close" onClick="showDiv()" title="Close">Close</a>
 </div>
 <div class="remember-me-popup-body" style="display:none">
     <p id="text1">Checking "Remember Me" will let you access your shopping cart on this computer when you are logged out</p>
     <div class="remember-me-popup-close-button a-right">
         <a href="#" class="remember-me-popup-close button" title="Close" onClick="
         showDiv()"><span>Close</span></a>
     </div>
 </div>
</div>

<p class="required">* Required Fields</p>



                  <div class="buttons-set">
               
                 <button type="submit" class="button login" title="Login" name="send" id="send2"><span>Login</span></button>

                   <a href="#" class="forgot-word">Forgot Your Password?</a>
              </div> <!--buttons-set-->
               </div> <!--content-->                               
         </div> <!--col-2 registered-users-->
                </fieldset> <!--col2-set-->
 </form>

</div> <!--account-login-->
      
      </div><!--main-container-->
       
       </div> 
    
    <!-- best Pro Slider -->
    
    
 
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
