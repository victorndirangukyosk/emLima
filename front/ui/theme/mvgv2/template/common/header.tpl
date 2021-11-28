<!-- check mvg/templte/common/header.tpl -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="kdt:page" content="product-listing-page">

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
    <!-- <title><?= $heading_title ?></title> -->
    <title><?= $title ?></title>
    <!-- Bootstrap -->
    <?php echo $google_analytics; ?>
    
    <link href="<?= $base; ?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/owl.theme.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/style.css?v=1.1.7">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/mycart.css">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/custom.css?v=1.1.0">

    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/javascript/jquery.pan.css">
    
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/example.css?v=1.1.0">
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/pygments.css?v=1.1.0"> -->
    <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/easyzoom.css?v=1.1.0">
    
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/abhishek.css?v=2.0.6"> -->
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="../../https@oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="../../https@oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>

    

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script src="<?= $base; ?>front/ui/javascript/common.js?v=2.0.4" type="text/javascript"></script>

    <script src="<?= $base; ?>front/ui/javascript/jquery.pan.js?v=2.0.4" type="text/javascript"></script>
    <script src="<?= $base; ?>front/ui/javascript/easyzoom.js"></script>

    <script src="<?= $base; ?>front/ui/theme/mvgv2/javascript/common.js?v=2.0.8" charset="UTF-8" type="text/javascript"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
    <script type="text/javascript" src="https://js.iugu.com/v2"></script>
    <script src="https://cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js"></script>


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

                <a href="#" class="user-address" type="button" data-toggle="modal" data-target="#useraddress-popup"> Change Store/Location</a>
            </div>
        </div>
        <div class="search">
            <div class="pull-left visible-xs side-nav-btn-control">
                <button type="button" class="btn btn-default" data-toggle="offcanvas"><i class="fa fa-bars"></i></button>
            </div>
            <div class="header-search-form header-item">
                <div class="search-form">
                    <form onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;" id="product-search-form">
                        <i class="fa fa-circle-o-notch fa-spin" style="display: none;"></i>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">

                                <?php if (isset($this->request->get['search'])) { ?>

                                    <input type="text" name="product_name" placeholder="<?= $text_search_product ?>" value="<?= $this->request->get['search']?>" class="form-control"/>

                                <?php } else { ?>

                                    <input type="text" name="product_name" placeholder="<?= $text_search_product ?>" class="form-control"/>

                                <?php } ?>

                                    
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
                                <div class="user-profile"><span class="user-profile-img"><img src="<?= $base; ?>front/ui/theme/mvgv2/images/user-profile.png"></span>
                                    <a href="<?= $account ?>" > <span class="user-name"><?= $full_name ?></span> </a>
                                </div>
                            </li>
                            <li><a href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></li>
                            <li><a href="<?= $wishlist ?>" ><i class="fa fa-list-ul"></i><?= $text_my_wishlist?></a></li>
                            <li><a href="<?= $address ?>" ><i class="fa fa-address-book"></i><?= $label_my_address ?></a></li>

                            <?php if($this->config->get('config_credit_enabled')) { ?>

                                <li><a href="<?= $credit ?>" ><i class="fa fa-money"></i><?= $text_my_cash ?></a></li>
                            <?php } ?>

                            <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>
                            <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                            <li><a href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></li>

                        </ul>
                    
                        <?php } else{ ?>

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
    <div class="store-cart-panel">
        <div class="modal right fade" id="store-cart-side" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="cart-panel-content">
                    </div>
                    <div class="modal-footer">
                        <!-- <p><?= $text_verify_number ?> </p> -->
                        <a href="<?php echo $checkout; ?>" id="proceed_to_checkout">

                            <button type="button" class="btn btn-primary btn-block btn-lg" id="proceed_to_checkout_button">
                                
                                <span class="checkout-modal-text"><?= $text_proceed_to_checkout?> </span>
                                <div class="checkout-loader" style="display: none;"></div>

                            </button>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <?php $i = 1; foreach ($breadcrumbs as $breadcrumb) { 

                            if($i == 1) { $i++; ?>
                                
                                <li><a href="#" data-toggle="modal" data-target="#useraddress-popup" ><?php echo $breadcrumb['text']; ?></a></li>
                                
                            <?php } else {

                                if ( $i++ == count($breadcrumbs)) { ?>
                                        <li><?php echo $breadcrumb['text']; ?></li>
                                    <?php } else { ?>
                                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                                <?php } ?>
                        <?php } }?>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper" id="wrapper">
        <div class="container-fluid" >
            <div class="row row-offcanvas row-offcanvas-left" >
                <!-- <div class="pull-left visible-xs side-nav-btn-control">
                    <button type="button" class="btn btn-default" data-toggle="offcanvas"><i class="fa fa-bars"></i></button>
                </div>
                 -->
                <div class="col-lg-2 col-md-3 col-sm-4 nopl sidebar-offcanvas" id="sidebar"  >
                    <div class="side-nav">
                        <div class="store-company-logo">
                            <!-- <img src="<?= $store_big_logo ?>" alt="" class="img-responsive"> -->
                            <a href="<?= $go_to_store ?>" class="hidden-xs hidden-sm header_item_content">
                                <img src="<?= $store_big_logo ?>" alt="" class="img-responsive">
                            </a>
                        </div>
                        <div id="side-menu" class="side-menu">
                            <ul>
                                <?php foreach ($categories as $category) { ?>

                                    <?php if ($category['children']) { ?>

                                        <?php   if(isset($category_id) && $category_id == $category['id']) { ?>
                                            <li class="has-sub open active"><a href="<?= $category['href'] ?>"><?= $category['name'] ?></a>
                                                <ul >
                                                    <?php foreach($category['children'] as $child){ ?>

                                                    <?php if($sub_category_id == $child['id']) { ?>
                                                        <li class="selected">
                                                            <a href="<?= $child['href'] ?>">
                                                                <?= $child['name'] ?> 
                                                            </a>
                                                        </li>

                                                    <?php } else { ?>
                                                        <li>
                                                            <a href="<?= $child['href'] ?>">
                                                                <?= $child['name'] ?> 
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } else { ?>
                                            <li class="has-sub"><a href="<?= $category['href'] ?>"><?= $category['name'] ?></a>
                                                <ul>
                                                    <?php foreach($category['children'] as $child){ ?>
                                                    <li>
                                                        <a href="<?= $child['href'] ?>">
                                                            <?= $child['name'] ?> 
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        
                                    <?php }else{ ?>
                                        <li class="has-sub">
                                            <a href="<?= $category['href'] ?>">
                                                <?= $category['name'] ?>
                                            </a>      
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                </div>