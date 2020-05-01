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
	     <a class="header__logo-link " href="<?= BASE_URL?>">
                        <img src="<?=$logo?>" />
                       
          </a></div>
			<div id="main-container" class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
			<div class="container">
  <div class="formBox level-login">
    <div class="box boxShaddow"></div>
    <div class="box loginBox">
      <h2><?= $text_number_verification ?></h2>
	  <h4><?= $text_enter_number_to_login ?></h4>
	  <div id="login-message">
      </div>
      <form class="form" id="login-form" autocomplete="off" method="post" enctype="multipart/form-data" novalidate>
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
    
    <div class="box registerBox ">
      <span class="reg_bg"></span>
      <h2><?= $text_number_verification ?></h2>
	  
      <form class="form">
        <div class="f_row">
          <label>Username</label>
          <input type="text" class="input-field" required>
          <u></u>
        </div>
		<div class="f_row">
          <label>Lastname</label>
          <input type="text" class="input-field" required>
          <u></u>
        </div>
		<div class="f_row">
          <label>E-Mail Address</label>
          <input type="text" class="input-field" required>
          <u></u>
        </div>
        <div class="f_row">
          <label>Password</label>
          <input type="password" class="input-field" required>
          <u></u>
        </div>
        <div class="f_row">
          <label>Password Confirm</label>
          <input type="password" class="input-field" required>
          <u></u>
        </div>
          <div class="mdl-selectfield f_row">
        <label>Standard Select</label>
        <select class="browser-default">
          <option value="" disabled selected>Choose your option</option>
          <option value="1">Option 1</option>
          <option value="2">Option 2</option>
          <option value="3">Option 3</option>
        </select>
      </div>
		<div class="f_row">
          <label>Companyname</label>
          <input type="text" class="input-field" required>
          <u></u>
        </div>
		<div class="f_row ">
          <label>Address</label>
          <input type="text" class="input-field" required>
          <u></u>
        </div>
		
          
            <div class="md-checkbox last">
    <input id="i2" type="checkbox" checked>
    <label for="i2">You agree to our <b>Terms of Service</b> & <b>Privacy Policy</b></label>
  </div>
        
        <button class="btn-large">Sign Up</button>
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
			<div class="col-lg-10 mb30">
			<div class="col-lg-4 pull-left"><i class="fa fa-share-square-o" aria-hidden="true"></i> <span>info@kwikbasket.com</span></div>
			<div class="col-lg-3 pull-left"><i class="fa fa-phone" aria-hidden="true"></i> <span>+254 738770186</span></div>
			<div class="col-lg-5 pull-left"><i class="fa fa-address-card" aria-hidden="true"></i> <span class="setfont">PO Box 57666-00200, Heritan House Woodlands Road, Nairobi</span></div>
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
    $('.formBox').removeClass('level-reg-revers');
	$('#main-container').removeClass('col-lg-7').addClass('col-lg-12');
    $('.formBox').toggleClass('level-login').toggleClass('level-reg');
    if(!$('.formBox').hasClass('level-reg')) {
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
