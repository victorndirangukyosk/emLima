<?php //echo '<pre>';print_r($data);exit;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="kdt:page" content="login-page"> 
 
    <meta http-equiv="content-language" content="<?= $config_language?>">

    <meta name="author" content="">

    <title>Login</title>

    <!-- Bootstrap CSS File -->
     <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font-Awesome CSS File -->
   <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/font-awesome-new.css" rel="stylesheet">

    <!-- Slider Revolution CSS File -->
	<link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/login.css" rel="stylesheet">
	
	</head>
	<body>
	  <div class="container-fluid">
	  
		<div class="row fullwidth">
		<div class="col-lg-9 fullwidth-inner" >
		<div class="col-lg-12">
        <div class="col-lg-6 col-xs-6">
	     <a class="header__logo-link " href="<?= BASE_URL?>">
                        <img src="<?=$logo?>" />
                       
          </a></div>
          <div class="col-lg-6 col-xs-6">
          <img src="<?= $base;?>front/ui/theme/metaorganic/images/logo-kwikbasket-2.png" style="width:160px !important; float: right; margin: 30px 10px;">
          </div>
          </div>
			<div id="main-container" class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
			<div class="container">
  <div class="formBox level-login">
    <div class="box boxShaddow"></div>
    <div class="box loginBox">
      <h2><?= $text_number_verification ?></h2>
	  <h4><?= $text_enter_number_to_login ?></h4>
	  <div id="login-message">
      </div>
      <form class="form" id="login-form" autocomplete="off" method="post" enctype="multipart/form-data" novalidate >
        <div class="f_row">
          <label><?= $text_enter_email_address?></label>
		  <input id="email" autocomplete="off" name="email" type="email" class="input-field input-md" required onclick="emailClicked()">
          <u></u>
        </div>
        <div class="f_row last">
          <label>Password</label>
		  <input id="password" autocomplete="off" name="password"  required type="password" class="input-field input-md">
          <u></u>
        </div>
		<div class="form-group">
        <label class="col-md-4 control-label sr-only" for="next"><?= $text_move_next?></label>
         <div class="col-md-12">
          <button id="login_send_otp" type="button" name="next" class="btn btn-default btn-block btn-lg">
            <span class="login-modal-text"><?= $text_login ?></span>
            <div class="login-loader" style="display: none;"></div>
          </button>
         </div>
       </div>
      
       
      </form>
    </div>
    
    <div class="box registerBox "  >
      <span class="reg_bg"></span>
      <h2><?= $text_number_verification ?></h2>
	  <div id="signup-message">
      </div>
	  <form class="form" action="<?php echo $action; ?>" method="post"  autocomplete="off"  enctype="multipart/form-data" id="sign-up-form">
        <div id="other_signup_div">
		<div class="f_row formui">
          <label><?= $entry_firstname ?></label>
		  <input id="First Name" name="firstname" type="text" autocomplete="off"  class="input-field input-md" required="">
          <u></u>
        </div>
		<div class="f_row formui">
          <label><?= $entry_lastname ?></label>
		  <input id="Last Name" name="lastname"  autocomplete="off"  type="text" class="input-field input-md" required="">
         <u></u>
        </div>
         <div class="clearfix"></div>
		<div class="f_row formui">
          <label><?= $entry_email_address ?></label>
		  <input id="email" name="email"  autocomplete="off"  type="text" class="input-field input-md" required="">
          <u></u>
        </div>
		<div class="f_row formui">
          <label><?= $entry_phone ?>  ( +<?= $this->config->get('config_telephone_code') ?> ) </label>
          <input id="register_phone_number" autocomplete="off"  name="telephone" type="text" class="input-field input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9">
          <u></u>
        </div>
        <div class="clearfix"></div>
        <div class="f_row formui">
          <label><?= $entry_password ?></label>
		  <input id="password" name="password"  autocomplete="off"  type="password" class="input-field input-md" required="">
          <u></u>
        </div>
        <div class="f_row formui">
          <label><?= $entry_confirm ?></label>
		  <input id="confirm" name="confirm"  autocomplete="off"  type="password" class="input-field input-md" required="">
          <u></u>
        </div>
        <div class="clearfix"></div>
        <div class="mdl-selectfield f_row">
        <label>Type</label>
		<select class="browser-default"" name="customer_group_id">
                    <option value="">-Select Customer Type-</option>
				    <?php foreach($customer_groups as $customer_group){?>
                    <option value="<?=$customer_group['customer_group_id']?>"> <?=$customer_group['name']?></option>
                   
					<?php } ?>
        </select>
        </div>
       <div class="clearfix"></div>
		<div class="f_row formui">
          <label><?= $entry_company ?></label>
		   <input id="company_name" name="company_name" type="text"  class="input-field input-md" required="">
          <u></u>
        </div>
		<div class="f_row formui">
          <label><?= $entry_address_1 ?></label>
		  <input id="company_address" name="company_address" type="text" class="input-field input-md" required="">
          <u></u>
        </div>
		
         <div class="clearfix"></div>
            <div class="md-checkbox last ">
			 <input id="i2" type="checkbox" name="agree_checkbox" />   
			<label for="i2"><?= $text_enter_you_agree ?>                                      
             <a target="_blank" alt="<?= $text_terms_of_service ?>" href="<?= $account_terms_link ?>">
            <strong>  <?= $text_terms_of_service ?></strong> 
			</a>

                                        &amp; 
             
			 <a target="_blank" alt="<?= $text_privacy_policy?>" href="<?= $privacy_link ?>">
              <strong><?= $text_privacy_policy?></strong>
             </a>
			</label> 
           <div class="text-danger" id="error_agree" style="display: none"><i class="fa fa-star" aria-hidden="true"></i>Please agree to terms and conditions</div>			
		</div>
       </div>
		
        <!--<button class="btn-large">Sign Up</button>-->
		<!-- Button -->
		<input type="hidden" name="fax" value="" id="fax-number" />
        <input type="hidden" name="register_verify_otp" value="" id="register_verify_otp" value="no"/>
		<div class="f_row formui signup_otp_div" style="display: none">
           <!--<label><?= $entry_signup_otp ?></label>-->
		   <input id="signup_otp" name="signup_otp" type="text"  placeholder="<?= $entry_signup_otp ?>" class="form-control input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="4" maxlength="4">
          <u></u>
        </div>
		<p class="forget-password signup_otp_div" style="display: none">
        <a href="#" id="signup-resend-otp" ><?= $text_resend_otp ?></a>
        </p>
		
        <div class="f_row formui">
            <div class="col-md-12">
                <button id="signup" type="button" class="btn-large">
                    <span class="signup-modal-text"><?= $heading_text ?></span>
                    <div class="signup-loader" style="display: none;"></div>
                </button>
            </div>
        </div>

      </form>
    </div>
    <a href="#" class="regTag icon-add">
      Sign Up
<i class="fa fa-times fontset" aria-hidden="true"></i>
    </a>
  </div>
</div></div>
			<div class="col-lg-5 login100-morenew">
				<div class="login100-more" >
				<img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/bg-01.png">
				</div>
			</div>
			<div class="col-lg-10 mb30 col-xs-12">
			<div class="col-lg-4 col-xs-12 pull-left mb20"><i class="fa fa-share-square-o" aria-hidden="true"></i> <span>hello@kwikbasket.com</span></div>
			<div class="col-lg-4 col-xs-12 pull-left mb20"><i class="fa fa-phone" aria-hidden="true"></i> <span>+254 780703586 / 704669999</span></div>
			<div class="col-lg-4 col-xs-12 pull-left mb20"><i class="fa fa-address-card" aria-hidden="true"></i> <span class="setfont">PO Box 57666-00200, Heritan House Woodlands Road, Nairobi</span></div>
			</div>
		</div>
		</div>
	  </div>
	</body>
	<script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
	<script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
	 <script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" type="text/javascript"></script>
	<script>
	
	function emailClicked() {
            $('#phone_number').val('');   
            //$('#email').removeAttr('disabled');     
        }
	var inP     =   $('.input-field');

inP.on('blur', function () {
    if (!this.value) {
        $(this).parent('.f_row').removeClass('focus');
    } else {
        $(this).parent('.f_row').addClass('focus');
    }
}).on('focus', function () {
    $(this).parent('.f_row').addClass('focus');
    $('.btn').removeClass('active');
    $('.f_row').removeClass('shake');
});


$('.resetTag').click(function(e){
    e.preventDefault();
    $('.formBox').addClass('level-forget').removeClass('level-reg');
});

$('.back').click(function(e){
    e.preventDefault();
    $('.formBox').removeClass('level-forget').addClass('level-login');
});



$('.regTag').click(function(e){
    e.preventDefault();
	 $('.login100-more').hide();
	$('span.text-danger').remove();
	$( ".formui").removeClass('error-animation');
    $('.formBox').removeClass('level-reg-revers');
	$('#main-container').removeClass('col-lg-7').addClass('col-lg-12');
    $('.formBox').toggleClass('level-login').toggleClass('level-reg');
    if(!$('.formBox').hasClass('level-reg')) {
	    $('.login100-more').show();
        $('.formBox').addClass('level-reg-revers');
		$('#main-container').removeClass('col-lg-12').addClass('col-lg-7');
    }
});
$('.btn').each(function() {
     $(this).on('click', function(e){
        e.preventDefault();
        
        var finp =  $(this).parent('form').find('input');
       
       console.log(finp.html());
       
        if (!finp.val() == 0) {
            $(this).addClass('active');
        }
        
        /*setTimeout(function () {
            
            inP.val('');
            
            $('.f_row').removeClass('shake focus');
            $('.btn').removeClass('active');
            
        }, 2000);
        */
        if(inP.val() == 0) {
            inP.parent('.f_row').addClass('shake');
        }
         
        //inP.val('');
        //$('.f_row').removeClass('focus');
        
    });
});

	</script>
	</html>
