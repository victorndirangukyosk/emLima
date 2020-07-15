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

     <link href="<?= $base;?>front/ui/theme/metaorganic/assets/images/favicon.ico" rel="icon">
    <!-- BEGIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.min.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.min.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.min.css" rel="stylesheet">
    <!-- END CSS -->   
    
    <!-- Bootstrap -->
    
    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/organic/css/style.css?v=5.1">

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/mycart.min.css">
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
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/style.min.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/responsive.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.min.css">
    <script src="<?= $base;?>front/ui/javascript/easyzoom.js"></script>
</head>

<body>
    
    <div class="alerter">
        <?php if($success){ ?>
            <div class="alert alert-info normalalert">
                <p class="notice-text"><?= $success ?></p>
            </div>
        <?php } ?>            
    </div>

     <header style="position: relative; z-index: 1040;  padding-bottom: 20px; border-bottom: 1px solid #ea6f28; ">
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
                        <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->
                            
                             <div class="newset"><a class="btn" href="<?= $account ?>" > <span ><?= $full_name ?></span> </a>     
                           
                           <div class="dropdownset" style="display:none; margin-top:-1px;">
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
                                      <div class="butn setui"> <a href="<?= BASE_URL?>/index.php?path=checkout/checkoutitems"><button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
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
                       
                     </div>
                     <div class="header__primary-navigation-wrapper">
                      
                        <div class="header__primary-navigation-list"> 
                        <!--<span class ="organic_logo"><img src="<?=$logo?>"></span>-->
                        </div>
                     </div>
                     
                  
               </div>
  </header>
 
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="dashboard-content">
                        <div class="row">
                            <div class="col-md-3 nopr">
                                <div class="dashboard-profile-left">
                                    <div class="profile-block">
                                        <img src="<?= $base;?>front/ui/theme/mvgv2/images/profile.png" alt="">
                                        <div class="profile-number"><?= $text_hello ?>, <?= $f_name ?></div>
                                    </div>
                                    <div class="profile-navigation">
                                        <ul class="nav nav-stacked">

                                            <li role="presentation">
                                                <?php if(strpos($account,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                    <a href="<?= $account ?>" class="active">
                                                <?php } else { ?>
                                                    <a href="<?= $account ?>">
                                                <?php } ?>
                                                
                                                <i class="fa fa-edit"></i><?= $text_profile ?></a>
                                            </li>

                                            <li role="presentation">
                                                <?php if(strpos($profile_info,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                    <a href="<?= $profile_info ?>" class="active">
                                                <?php } else { ?>
                                                    <a href="<?= $profile_info ?>">
                                                <?php } ?>
                                                
                                                <i class="fa fa-edit"></i><?= $text_profile_info ?></a>
                                            </li>

                                            <li role="presentation">
                                                <?php if(strpos($account_transactions,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                    <a href="<?= $account_transactions ?>" class="active">
                                                <?php } else { ?>
                                                    <a href="<?= $account_transactions ?>">
                                                <?php } ?>
                                                
                                                <i class="fa fa-credit-card"></i><?= $text_transactions ?></a>
                                            </li>

                                            <li role="presentation" >
                                                <?php if(strpos($wishlist,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                    <a href="<?= $wishlist ?>" class="active">
                                                <?php } else { ?>
                                                    <a href="<?= $wishlist ?>">
                                                <?php } ?>
                                                
                                                <i class="fa fa-list-alt"></i><?= $text_wishlist ?></a>
                                            </li>

                                            <li role="presentation" >

                                            <?php if(strpos($refer,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                <a href="<?= $refer ?>" class="active">
                                            <?php } else { ?>
                                                <a href="<?= $refer ?>">
                                            <?php } ?>

                                            <i class="fa fa-share-alt"></i><?= $text_refer ?></a>
                                            </li>

                                            <li role="presentation" >

                                            <?php if(strpos($order,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                <a href="<?= $order ?>" class="active">
                                            <?php } else { ?>
                                                <a href="<?= $order ?>">
                                            <?php } ?>

                                            <i class="fa fa-reorder"></i><?= $text_order ?></a>
                                            </li>

                                            <?php if($this->config->get('config_account_return_status') == 'yes') { ?>
                                                <li role="presentation" >
                                                    <?php if(strpos( $return,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                        <a href="<?= $return ?>" class="active">
                                                    <?php } else { ?>
                                                        <a href="<?= $return ?>">
                                                    <?php } ?>
                                                    
                                                    <i class="fa fa-undo"></i><?= $text_return ?></a>
                                                </li>
                                            <?php } ?>
                                            

                                            <?php if($this->config->get('config_credit_enabled')) { ?>

                                                <li role="presentation">

                                                    <?php if(strpos( $credit,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                        <a href="<?php echo $credit; ?>" class="active">
                                                    <?php } else { ?>
                                                        <a href="<?php echo $credit; ?>">
                                                    <?php } ?>
                                                    <i class="fa fa-money"></i><?= $text_cash ?> </a>
                                                </li>
                                            <?php } ?>

                                            <?php if($this->config->get('config_reward_enabled')) { ?>
                                            
                                                <li role="presentation">

                                                    <?php if(strpos( $reward,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                        <a href="<?php echo $reward; ?>" class="active">
                                                    <?php } else { ?>
                                                        <a href="<?php echo $reward; ?>">
                                                    <?php } ?>
                                                    <i class="fa fa-money"></i><?= $text_rewards ?> </a>
                                                </li>

                                            <?php } ?>

                                            <li role="presentation">
                                            <?php if(strpos($address,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                <a href="<?= $address ?>" class="active">
                                            <?php } else { ?>
                                                <a href="<?= $address ?>">
                                            <?php } ?>

                                            <i class="fa fa-address-book"></i><?= $label_address ?> </a></li>
                                            <li role="presentation"><a href="<?= $logout ?>"><i class="fa fa-power-off"></i> <?= $text_signout ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
</script>
