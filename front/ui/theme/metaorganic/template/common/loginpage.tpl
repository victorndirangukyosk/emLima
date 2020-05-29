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
          <div class="logo"> <a href="<?php echo BASE_URL;?>" title="index">
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
                  <!--<li  data-link ="home"> <a class="level-top" ><span>Home</span></a></li> -->
                  <li  data-link ="about"> <a href="<?= BASE_URL;?>#about"class="level-top"  ><span>About Us</span></a> </li>
                  <li data-link ="whom"> <a href="<?= BASE_URL;?>#whom" class="level-top" ><span>Who We Serve</span></a> </li>
                  <li data-link ="works"> <a href="<?= BASE_URL;?>#works" class="level-top"><span>How It Works</span></a> </li>
                  
                  <li data-link ="contact"> <a href="<?= BASE_URL;?>#contact" class="level-top"><span>Contact Us</span></a> </li>
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
     <h2>Login / Signup</h2>
  </div>
        </div>
    </div>
        </div>
        </div>
    
      <div id="login-message" style="text-align:center;font-size:20px;margin-top:20px;">
      </div>
      <div id="signup-message" style="text-align:center;font-size:20px;margin-top:20px;">
      </div>
    <div id="login-form-div" class="main-container col1-layout wow bounceInUp animated animated" style="visibility: visible;">     
              
      <div class="main">                     
                         <div class="account-login container">
<!--page-title-->

     <form action="" method="post" id="login-form">
     <form class="form" id="login-form" autocomplete="off" method="post" enctype="multipart/form-data" novalidate >
     <fieldset class="col2-set">
         <div class="col-1 new-users"> 
               <strong>New Customers</strong>    
             <div class="content">
                
                 <p style="margin-bottom: 46px;">By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                  <div class="buttons-set" style="text-align: center;">
                 <button type="button" title="Registration" class="button create-account" onClick="showHide('register-form-div')"><span><span>Register Now</span></span></button>
             </div>
             </div>
         </div>
         <div class="col-2 registered-users">
          <strong>Registered Customers</strong>             
             <div class="content">
                 
                 <p>If you have an account with us, please log in.</p>
                 <ul class="form-list">
                     <li>
                          <label for="email"><?= $text_enter_email_address?><em class="required">*</em></label>
                         <div class="input-box">
							 <input id="email" autocomplete="off" name="email" type="email" class="input-text input-md required-entry" required onclick="emailClicked()">
                         </div>
                     </li>
                     <li>
                         <label for="pass">Password<em class="required">*</em></label>
                         <div class="input-box">
							 <input id="password" autocomplete="off" name="password"  required type="password" class="input-text required-entry input-md">
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
               
                 <button id="login_send_otp" type="button" class="button login" title="Login" name="send" id="send2"><span><?= $text_login ?></span></button>

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
    
    <div id="register-form-div" style="display:none;" class="main-container col2-right-layout">
    <div class="main container">
      <div class="row">
        <div class="col-main col-sm-12 wow bounceInUp animated animated" style="visibility: visible;">
          <div id="messages_product_view"></div>
          <form class="form" action="<?php echo $action; ?>" method="post"  autocomplete="off"  enctype="multipart/form-data" id="sign-up-form">
            <div class="static-contain">
              <fieldset class="group-select">
                <ul>
                  <li>
                    <fieldset class="">
                      <ul>
                        <li>
                          <div class="customer-name"  id="other_signup_div">
                            <div class="input-box name-firstname">
                              <label for="name"><em class="required">*</em><?= $entry_firstname ?></label>
                              <br>
							   <input id="First Name" name="firstname" type="text" autocomplete="off"  class="input-text required-entry input-md" required="">
                            </div>
							<div class="input-box name-lastname">
                              <label for="name"><?= $entry_lastname ?></label>
                              <br>
							  <input id="Last Name" name="lastname"  autocomplete="off"  type="text" class="input-text required-entry input-md" required="">
                            </div>
                            <div class="input-box name-email">
                              <label for="email"><em class="required">*</em><?= $entry_email_address ?></label>
                              <br>
							   <input id="email" name="email"  autocomplete="off"  type="text" class="input-text required-entry validate-email input-md" required="">
                            </div>
							 <div class="input-box name-phone">
                              <label for="email"><em class="required">*</em><?= $entry_phone ?></label>
                             <br>
								<span class="input-group-btn" style="
								display: table;
								position: relative;
								margin-bottom: -46px;">

								<p id="button-reward" class="" style="padding: 12px 13px;border-radius: 20px 1px 1px 20px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

								<font style="vertical-align: inherit;">
								<font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
								+<?= $this->config->get('config_telephone_code') ?>                                               
								</font></font></font>
								</font>
								</p>

								</span>
							 
							 <input id="register_phone_number" autocomplete="off"  name="telephone" type="text" class="input-text input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9">
                            </div>
							<div class="input-box name-password">
                              <label for="email"><em class="required">*</em><?= $entry_password ?></label>
                              <br>
							 <input id="password" name="password"  autocomplete="off"  type="password" class="input-text input-md" required="">
                            </div>
							<div class="input-box name-password">
                              <label for="email"><em class="required">*</em><?= $entry_confirm ?></label>
                              <br>
							 <input id="confirm" name="confirm"  autocomplete="off"  type="password" class="input-text input-md" required="">
                            </div>
							<div class="input-box name-company">
                              <label for="company"><em class="required">*</em><?= $entry_company ?></label>
                              <br>
							  <input id="company_name" name="company_name" type="text"  class="input-text input-md" required="">
                            </div>
							<div class="input-box name-entry_address_1">
                              <label for="email"><em class="required">*</em>Company <?= $entry_address_1 ?></label>
                              <br>
							 <input id="company_address" name="company_address" type="text" class="input-text input-md" required="">
                            </div>
							<div class="input-box name-entry_address_1">
							 <label>Business Type</label>
                              <br>
							<select class="browser-default" name="customer_group_id">
								<option value="">-Select Customer Type-</option>
								<?php foreach($customer_groups as $customer_group){?>
								<option value="<?=$customer_group['customer_group_id']?>"> <?=$customer_group['name']?></option>
							   
								<?php } ?>
                           </select>
                            </div>
							<div class="input-box name-housing-building">
                              <label for="email"><em class="required">*</em>House Number/ Building Name</label>
                              <br>
							 <input id="house_building" name="house_building" type="text" class="input-text input-md" required="">
                            </div>
							
							<div class="input-box name-address_2">
                              <label for="email"><em class="required">*</em><?= $entry_address_1 ?></label>
                              <br>
							 <input id="address" name="address" type="text" class="input-text input-md" required="">
                            </div>
							
							<div class="input-box name-location">
                              <label for="email"><em class="required">*</em>Location</label>
                              <br>
							   <!--<input name="modal_address_locality" type="text" class="input-text LocalityId pac-target-input" required="" placeholder="Enter a location" autocomplete="off">-->
								<input name="location" id="searchTextField" class="zipcode-enter pac-target-input input-text" type="text" required="" alt="" maxlength="" size="" tabindex="3" placeholder="Find Stores in your Location" highlight="y" strict="y" autocomplete="off">
								<input type="hidden" id="store_location" value="autosuggestion">
							</div>
							<input type="hidden" name="latitude" value="<?= $latitude ?>" />
                            <input type="hidden" name="longitude" value="<?= $longitude ?>" />
							
							 <?php if ($site_key) { ?>
						    <div class="input-box">
							<label for="input-date-added"></label>
							
							  
								  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" style="padding-left:16px"></div>
								  <div style="display:none;"class="text-danger"id="error_captha" >Please Validate Captha</div>
							</div>
							 <?php } ?>
                          </div>
						  <div class="customer-name">
						  <input type="hidden" name="fax" value="" id="fax-number" />
							<input type="hidden" name="register_verify_otp" value="" id="register_verify_otp" value="no"/>
							<div class="input-box signup_otp_div" style="display: none">
							   <label><?= $entry_signup_otp ?></label>
							   <input id="signup_otp" name="signup_otp" type="text"  class="input-text input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="4" maxlength="4">
							  <u></u>
							  <p class="forget-password signup_otp_div" style="display: none">
								<a href="#" id="signup-resend-otp" ><?= $text_resend_otp ?></a>
							  </p>
							</div>
							
						  </div>
                        </li>
                        
                      </ul>
                    </fieldset>
                  </li>
				  <li>
				  
				  </li>
                  <p class="require"><em class="required">* </em>Required Fields</p>
                  <input type="text" name="hideit" id="hideit" value="" style="display:none !important;">
                  <div class="buttons-set">
                    <!--<button type="submit" title="Submit" class="button submit"><span><span>Submit</span></span></button>-->
					<button id="signup" type="button" class="button submit">
                    <span class="signup-modal-text"><?= $heading_text ?></span>
                    <div class="signup-loader" style="display: none;"></div>
                    </button>
                    If Already have account ? Please <a onclick="showHide('login-form-div')"><strong>Login</strong></a>
                  </div>
                </ul>
              </fieldset>
            </div>
			
          </form>
          
        </div>
      
        <!--col-right sidebar--> 
      </div>
      <!--row--> 
    </div>
    <!--main-container-inner--> 
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
    <!--<li data-link ="home"><div class="home">Home </div> </li>-->
    <li data-link ="about"><a href="#about">About Us</a></li>
    <li data-link ="whom"><a href="#whom">Whom We Serve</a></li>
    <li data-link ="works"><a href="#works">How It Works</a></li>
    <li data-link ="contact"><a href="#contact">Contact Us</a></li>
  </ul>
  
</div>



<!-- JavaScript --> 
<script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/bootstrap.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/parallax.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/revslider.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/common.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.bxslider.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/owl.carousel.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.mobile-menu.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/countdown.js"></script> 
	 <script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" type="text/javascript"></script>
	 <script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>
	  <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
	  <script src="<?= $base;?>front/ui/javascript/home.js?v=1.0.4"></script> 
	 <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>
     <script type="text/javascript" src="<?= $base;?>admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2"></script>
<script>
jQuery('.zipcode-enter').focus();

      /* jQuery('#us1').locationpicker({
                location: {
                    latitude: 0,
                    longitude: 0                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: jQuery('input[name="latitude"]'),
                    longitudeInput: jQuery('input[name="longitude"]'),
                    locationNameInput: jQuery('.LocalityId')
                },
                enableAutocomplete: true,
                zoom:13

            }); 


            function saveLatLng() {
                $('#GMapPopup').modal('hide');

                $('.LocalityId').val($('.LocalityId').val());
            }*/
			
    jQuery(function($) {
        console.log(" fax-number mask");
       jQuery("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });
	
	  jQuery(function($){
            console.log("mask");
           jQuery("#searchTextField").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
        });
			
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

function showHide(formId){
    //jQuery('#preloader').show();
	jQuery('#'+formId).show();
    jQuery("html, body").animate({ scrollTop: 200 }, 2000);
    if(formId == 'register-form-div'){
        jQuery('#login-form-div').hide(1000);
    }else{
	  jQuery('#register-form-div').hide(1000);
	}
	//jQuery('#preloader').fadeOut('fast',function(){$(this).remove();});
	

}
   </script>
</body>
</html>
