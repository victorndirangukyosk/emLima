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

    
</head>

<body>
    <div class="alerter">
        <?php if($notices){ ?>
            <div class="alert alert-info normalalert">
                <?php foreach($notices as $notice){ ?>
                    <p class="notice-text"><?= $notice ?></p>
                <?php } ?>
            </div>
        <?php } ?>            
    </div>
    <div class="header">
        <div class="header_left">
            <div class="header-item">
                <a href="<?= $go_to_store ?>" class="hidden-xs hidden-sm header_item_content"><img src="<?= $logo ?>" alt=""></a>
                <a href="<?= $go_to_store ?>" class="visible-xs visible-sm header_item_content"><img src="<?= $small_icon ?>"></a>
            </div>
            <div class="header_item header-item-address">

                <a href="#" class="user-address" type="button" data-toggle="modal" data-target="#useraddress-popup"> Change Delivery Location

                </a>

            </div>
        </div>
        <div class="search">
            <!-- <div class="pull-left visible-xs side-nav-btn-control">
                <button type="button" class="btn btn-default" data-toggle="offcanvas"><i class="fa fa-bars"></i></button>
            </div> -->
            
            <div class="header-search-form header-item">
                <div class="search-form">
                    <form onsubmit="location='<?= $link ?>&filter=' + $('input[name=\'store_search\']').val(); return false;">
                        <i class="fa fa-circle-o-notch fa-spin" style="display: none;"></i>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="store_search" placeholder="<?= $text_search_store ?>" value="<?= $filter?>" >
                                    <span class="input-group-btn">
                                     <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="overlay-body"></div>
            </div>
        </div>
        <div class="header_right">
            <div class="header_item">
                <!-- <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2" style="margin-top: 11px;"> -->
                <div style="margin-top: 11px;">
                    <?php echo $language; ?>
                </div>
            </div>
            <div class="header_item">
                <div class="header-item header-dropdown">
                    <?php if($is_login) { ?>
                        
                        <a href="#" class="dropdown-toggle header-item-account hidden-sm hidden-xs" data-toggle="dropdown"><?= $text_account ?> <i class="fa fa-angle-down"></i></a>
                        <a href="#" class="dropdown-toggle header-item-account visible-sm visible-xs" data-toggle="dropdown"> <i class="fa fa-user"></i></a>

                        <ul class="dropdown-menu" role="menu">
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
                            <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>
                            <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                            <li><a href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></li>

                        </ul>
                    
                        <?php }else{ ?>

                        <a href="#" class="dropdown-toggle header-item-account hidden-sm hidden-xs" data-toggle="dropdown"><?= $text_account ?> <i class="fa fa-angle-down"></i></a>
                        <a href="#" class="dropdown-toggle header-item-account visible-sm visible-xs" data-toggle="dropdown"> <i class="fa fa-user"></i></a>

                        <ul class="dropdown-menu" role="menu">                                    
                            <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#phoneModal"><i class="fa fa-sign-in"></i><?= $text_sign_in ?></a></li>
                            <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#signupModal-popup"><i class="fa fa-user-plus"></i><?= $text_register ?></a></li>
                            <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>
                            <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
            <div class="header_item">
                <div class="store-cart-action header-item">
                    <button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
                        <span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
                        <i class="fa fa-shopping-cart"></i> 
                        <span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
                    </button>
                </div>
            </div>

            

        </div>
    </div>