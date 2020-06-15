<!DOCTYPE html>
<html lang="en">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Farmer Registration Page</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no">
<meta name="description" content="Default Description">
<meta name="keywords" content="fashion, store, E-commerce">
<meta name="robots" content="*">
<meta name="viewport" content="initial-scale=1.0, width=device-width">
<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
<link rel="icon" href="images/favicon.png" type="image/x-icon">-->

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

<div id="page">

  <!--<header>
    
    <div id="header">
      <div class="container">
        <div class="header-container row">
          <div class="logo"> <a class="base_url" href="<?php echo BASE_URL;?>" title="index">
            <div><img src="<?=$logo?>" alt="logo"></div>
            </a> </div>
          <div class="fl-nav-menu">
            <nav>
              <div class="mm-toggle-wrap">
                <div class="mm-toggle"><i class="icon-align-justify"></i><span class="mm-label">Menu</span> </div>
              </div>
              <div class="nav-inner"> 
                 
                <ul id="nav" class="hidden-xs">
                  
                  <li  data-link ="about"> <a href="<?= BASE_URL;?>#about"class="level-top"  ><span>About Us</span></a> </li>
                  <li data-link ="whom"> <a href="<?= BASE_URL;?>#whom" class="level-top" ><span>Who We Serve</span></a> </li>
                  <li data-link ="works"> <a href="<?= BASE_URL;?>#works" class="level-top"><span>How It Works</span></a> </li>
                  
                  <li data-link ="contact"> <a href="<?= BASE_URL;?>#contact" class="level-top"><span>Contact Us</span></a> </li>
                </ul>
                 
              </div>
            </nav>
          </div>
          
          
          
          <div class="fl-header-right">
            <div class="fl-links">
               <div class="no-js clicker"> 
               
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </header>-->


    <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
        <div class="page-title">
<h2 class="font-white mt20">Change Password</h2>
</div>
        </div>
      </div>
    </div>
  </div>
  <!-- BEGIN Main Container col2-right -->

   <div class="panel panel-default">
                    <!--<div class="panel-heading"><span class="heading-title"><?= $heading_text ?></span></div>-->
                    <div class="panel-body">
                        <form action="changepass" method="post"  enctype="multipart/form-data" class="form-horizontal">
                           <!-- <div class="col-lg-10 col-sm-12 col-xs-6">
                                 <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $label_current ?></label>
                                    <div class="col-sm-4">
                                        <input type='password' class="form-control" id="currentpassword" name='currentpassword' required>
                                        <?php if ($error_current) { ?>
                                        <div class="text-danger"><?php echo $error_current; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>-->
                                <div class="form-group required" align="center">
                                <div class="col-sm-3"></div>
                                    <label class="col-sm-2 control-label" for="input-name" style="color: black;"><?= $label_new ?></label>
                                    <div class="col-sm-4">
                                        <input type='password' class="form-control" id="newpassword" name='newpassword' required>
                                        <?php if ($error_new) { ?>
                                        <div class="text-danger"><?php echo $error_new; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                <div class="col-sm-3"></div>
                                    <label class="col-sm-2 control-label" for="input-name" style="color: black;"><?= $label_retype ?></label>
                                    <div class="col-sm-4">
                                        <input type='password' class="form-control" id="retypepassword" name="retypepassword" required>
                                        <div id="status"></div> 
                                        <?php if ($error_retype) { ?>
                                        <div class="text-danger"><?php echo $error_retype; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>                     

                                <div class="form-group">
                                 <div class="col-sm-3"></div>
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-2">
                                    <div class="form-action">
                                        <input type="submit" class="btn btn-lg btn-primary ladda-button" id="ChangePassword" name="submit" value="submit">                                    
                                    </div>
                                    </div>
                                </div>

                            </div><!-- END .col-lg-6 -->
                        </form>
                    </div><!-- END .panel-body -->
                </div><!-- END .panel -->

                
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
                <li class="fb"><a href="https://www.facebook.com/kwikbasket" target="_blank"></a></li>
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


<!-- JavaScript --> 


<script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/bootstrap.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/parallax.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/revslider.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/common.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.bxslider.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/owl.carousel.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.mobile-menu.min.js"></script> 

<script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" type="text/javascript"></script>
 <script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>

</body>
</html>

<script>
