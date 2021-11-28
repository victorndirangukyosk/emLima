<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]> ><! <![endif]-->
<html style="" class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
    <!-- <![endif] -->
    <head>

        <title><?= $title ?></title>
        
        <link rel="shortcut icon" type="image/png" href="image/data/<?php echo $this->config->get('config_icon') ?>"/>
        <link href="image/theme/192x192-f99243ddd270b032e0da78e91a6ef414.png" rel="icon" sizes="192x192" />
        <link href="image/theme/128x128-e16fef127b8a1d546004e7cb716c17d7.png" rel="icon" sizes="128x128" />

        <base href="<?php echo $base; ?>" />
                 
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>
        <?php if ($keywords) { ?>
        <meta name="keywords" content= "<?php echo $keywords; ?>" />
        <?php } ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            
            <?php if ($icon) { ?>
            <link href="<?php echo $icon; ?>" rel="icon" />
            <?php } ?>
            
            <?php foreach ($metas as $meta) { ?>
            <meta name="<?php echo $meta['name']; ?>" content="<?php echo $meta['content']; ?>" />
            <?php } ?>

            <?php foreach ($links as $link) { ?>
            <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
            <?php } ?>

            <script src="<?= $base?>front/ui/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>   
            <script src="<?= $base?>front/ui/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   
            <script src="<?= $base?>front/ui/javascript/common.js" type="text/javascript"></script>

            <?php foreach ($scripts as $script) { ?>
            <script src="<?php echo $script; ?>" type="text/javascript"></script>
            <?php } ?>

            <?php echo $google_analytics; ?>

            <!-- - if Rails.env.staging? -->
            <!-- %meta{:content => "noindex", :name => "robots"} -->
            <link rel="stylesheet" href="<?= $base?>front/ui/theme/mvg/stylesheet/iwp.css" />
            <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,400,600,300" />    
            <link href="<?= $base?>front/ui/theme/mvg/stylesheet/css.css" rel="stylesheet" type="text/css" />    
            <link href="<?= $base?>front/ui/theme/mvg/stylesheet/layout.css" media="screen" rel="stylesheet" type="text/css" />

            <script src="<?= $base?>front/ui/theme/mvg/javascript/common.js" charset="UTF-8" type="text/javascript"></script>

            <?php foreach ($styles as $style) { ?>
            <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
            <?php } ?>

            <link href="<?= $base?>front/ui/theme/mvg/stylesheet/custom.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="<?php echo $class; ?>">
        
        <!--[if lte IE 9]>
        <div class='container-fluid'>
            <div class='row'>
                <div class='not-supported' role='alert'>
                    <button class='close' data-dismiss='alert' type='button'>
                        <span aria-hidden='true'>×</span>
                        <span class='sr-only'>Close</span>
                    </button>
                    <strong>Warning:</strong>
                    We don't fully support your browser. Please use  Chrome, Firefox or IE 10 and above for the best experience.
                </div>
            </div>
        </div>
        <![endif]-->

        <div class="mob-menu-container">
            <div class="mob-menu-with-orverlay">
                <div class="respcontainer">
                    <div class="resp-close">
                        <i class="fa fa-times"></i>
                    </div>
                    <div class="resp-header">
                        <a href="<?= $home ?>" class="resp-logo">
                            <img src="<?= $logo ?>" alt="Resp-logo" />
                        </a>
                    </div>
                    <div class="resp-categories">
                        <a href="<?= $home ?>" class="all-cat"><?= $text_all_categories ?></a>
                        <div class="mob-cat-list">
                            <ul>
                                <?php foreach ($categories as $category) { ?>
                                <li id="classification-1">
                                    <a href="<?= $category['href'] ?>">
                                        <?= $category['name'] ?>                                        
                                        
                                        <?php if($category['children']){ ?>
                                        <i class="fa fa-plus"></i>                                        
                                        <?php } ?>
                                        
                                    </a>
                                    <?php if($category['children']){ ?>
                                    <ul>
                                        <?php foreach($category['children'] as $children){ ?>
                                        <li id="classification-2" class="no-arrow">
                                            <a href="<?= $children['href'] ?>"><?= $children['name'] ?></a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <?php } ?>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="resp-menu">
                        <div class="resp-menu-header">
                           <?= $text_menu ?>
                        </div>
                        <div class="resp-menu-list resp-menu-navbar">
                            <ul>                                
                                <li class="dropdown">
                                    <a role="button" data-toggle="dropdown" class="dropdown-toggle">
                                        <span>
                                            <i class="fa fa-phone"></i>
                                        </span>
                                         <?= $support ?>
                                        <span class="caretsmallsize">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu">
                                        <li>
                                            <a href="<?= $help ?>" id="faq">
                                                <span>
                                                    <i class="fa fa-question-circle"></i>
                                                </span>
                                               <?= $faq ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="tel:<?= $telephone ?>" id="support">
                                                <span>
                                                    <i class="fa fa-headphones"></i>
                                                </span>
                                                <?= $call ?><?= $telephone ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <?php if($is_login) { ?>
                                <li>
                                    <a href="<?= $this->url->link('account/account') ?>" id="account" class="app-link">
                                        <span>
                                            <i class="fa fa-user"></i>
                                        </span>
                                        <?= $text_account ?>
                                    </a>
                                </li>     
                                <?php }else{ ?>
                                <li>
                                    <a href="<?= $this->url->link('account/register') ?>" id="sign-out" class="app-link">
                                        <span>
                                            <i class="fa fa-user"></i>
                                        </span>
                                        <?= $text_register ?>
                                    </a>
                                </li>        
                                <li>
                                    <a href="<?= $this->url->link('account/login') ?>" id="sign-in" class="app-link">
                                        <span>
                                            <i class="fa fa-sign-in"></i>
                                        </span>
                                        <?= $text_sign_in ?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="overlay"></div>          
            </div>      
        </div>
        <div class="mob-nav-container"><div class="resp-wrapper"><div class="resp-wrapper-head">
                    <div class="top-head">
                        <a class="respBtn">
                            <i class="fa fa-bars"></i>
                        </a>

                        <?php if(isset($store) && $store){ ?>
                        <a id="shop-info-link" class="resp-wrapper-logo">
                            <div class="visible-xs">
                                <?= $store['name'] ?>
                                <span>
                                    <i class="fa fa-caret-down"></i>
                                </span>
                            </div>
                            <div class="visible-sm">
                                <?= $store['name'] ?>
                                <span>
                                    <i class="fa fa-caret-down"></i>
                                </span>
                            </div>
                        </a>
                        <?php } ?>

                        <a href="<?= $this->url->link('checkout/cart') ?>" class="resp-shoppingcart">
                            <span>
                                <i class="fa fa-shopping-cart"></i>
                            </span>
                            <div class="shoppingitem-fig"><?= $this->cart->countProducts(); ?></div>
                        </a>
                    </div>
                    <div class="clear"></div>
                    <div class="resp-search-box">
                        <div>
                            <form onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
                                    <i class="fa fa-circle-o-notch fa-spin" style="display: none;"></i>
                                    <span></span>
                                <!-- <input type="text" placeholder="Search for your product"> -->
                                
                                <input type="text" name="product_name"  placeholder="Search fors your product" />
                                <button type="submit"></button>
                                <div class="resp-searchresult">
                                    <div></div>
                                </div>
                            </form>
                            <div class="resp-overlay"></div>                                
                        </div>                            
                    </div>
                </div>                    
            </div>                    
        </div>

        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 col-xs-2">
                        <h1 id="logo">
                        <?php if ($logo) { ?>
                            <a class="logo" href="<?php echo $home; ?>">
                                <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" />
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo $home; ?>"><?php echo $name; ?></a>
                        <?php } ?>
                        </h1>
                    </div>

                    <div class="col-center col-md-4 col-sm-5 col-xs-6">
                        <?php if(!empty($store)){ ?>
                        <div class="store-name-wrapper">
                            <h6 style="padding-top: 3px; margin-top: 0px;"><?= $text_shopping_from ?></h6>
                            <a class="shopinfomodelBtn pull-right" id="shop-info-link">
                                <?= $store['name'] ?>
                                <span style="padding: 0;">
                                    <i class="fa fa-caret-down"></i>
                                </span>
                            </a>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="col-md-6 col-sm-5 col-right col-xs-4 topnavsguest">
                        <ul class="nav navbar-nav navbar-right">
                            <li>  
                                <a href="<?= $this->url->link('information/enquiries') ?>"><?= $list_products ?></a>
                            </li>    
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                    <span>
                                        <i class="fa fa fa-phone"></i>
                                    </span>
                                   <?= $support ?>
                                    <span>
                                        <i class="fa fa-caret-down"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu pull-left">
                                    <span class="rightcaret"></span>
                                    <li>
                                        <a href="<?= $help ?>" id="faq">
                                            <i class="fa fa-question-circle"></i>
                                           <?= $faq ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tel:<?= $telephone ?>" id="support">
                                            <i class="fa fa-headphones"></i>
                                            <?= $call ?><br />
                                            <?= $telephone ?>
                                        </a>
                                    </li>                                    
                                </ul>
                            </li>

                            <?php if($is_login) { ?>
                            <li class="dropdown">
                                <a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle">
                                    <?= $text ?>
                                    <?= $name ?>       
                                    <span class="fa fa-caret-down"></span>
                                </a>
                                <ul role="menu" class="dropdown-menu account">
                                    <span class="rightcaret"></span>
                                    <li>
                                        <a href="<?= $account ?>" id="my-account">
                                            <i class="fa fa-user"></i>
                                             <?= $text_account ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $reward ?>" id="my-rewards">
                                            <i class="fa fa-gift"></i>
                                            <?= $text_rewards ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $order ?>" id="my-orders">
                                            <i class="fa fa-clock-o"></i>
                                            <?= $text_orders ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $refer?>" id="invite-friends" class="ddmenubtopb">
                                            <i class="fa fa-users"></i>
                                            <?= $text_refer ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $logout ?>" id="sign-out" class="app-link"><i class="fa fa-sign-out"></i><?= $text_sign_out ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php } else{ ?>
                            <li>
                                <a href="<?= $this->url->link('account/login') ?>" id="sign-in" class="app-link"><span>
                                        <i class="fa fa-sign-in"></i>
                                    </span><?= $text_sign_in ?>
                                </a>
                            </li>
                            <?php } ?>


                        </ul>
                        <!-- .notification-popup -->
                        <!--   %span.rightcaret -->
                        <!--     %i.fa.fa-caret-up -->
                        <!--   Congratulations! -->
                        <!--   %strong Your account has been credited with 28 points -->
                    </div>
                </div>                    
            </div>        
        </div>
        <div class="alerter">
            <?php if($notices){ ?>
                <div class="alert alert-info normalalert">
                    <?php foreach($notices as $notice){ ?>
                        <p><?= $notice ?></p>
                    <?php } ?>
                </div>
            <?php } ?>            
        </div>
        

        <div class="buy-bar-container">
            <div class="headtabs affix-top"><div class="container-fluid">
                    <div class="row">
                        <div class="categories-col col-md-2 col-xs-2">
                            <div class="categories-list-container">
                                <div class="categories-list">
                                    <a href="<?php echo $home; ?>" class="categories-head">
                                        <span class="cate-navicon">
                                            <i class="fa fa-navicon"></i>
                                        </span>
                                        <?= $text_all_categories ?>
                                        <span class="angledown-allcag">
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                    </a>
                                    <ul class="cat-list" style="display: block;">
                                        <?php foreach ($categories as $category) { ?>

                                        <?php if ($category['children']) { ?>
                                        <li>
                                            <a href="<?= $category['href'] ?>">
                                                <?= $category['name'] ?> <i class="fa fa-angle-right"></i>
                                            </a>                                    

                                            <div class="drop-menu-2 desk-drop-down">
                                                <p><?= $category['name'] ?></p>


                                                <ul>
                                                    <?php foreach($category['children'] as $child){ ?>
                                                    <li>
                                                        <a href="<?= $child['href'] ?>">
                                                            <?= $child['name'] ?> 
                                                        </a>
                                                    </li>
                                                    <?php } ?><!-- foreach children -->
                                                </ul>

                                                <img src="<?= $category['thumb'] ?>" />

                                            </div>
                                        </li>
                                        <?php }else{ ?>
                                        <li class="nonavbordertb">
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
                        <div class="col-md-6 col-xs-6 search-box-container searchbox-div">

                            <a href="<?= $this->url->link('product/recipe') ?>" class="nav-btn btn btn-recipe">
                                <i class="fa fa-list-alt"></i>
                                <span><?= $button_recipes ?></span>
                            </a>

                            <div class="search-box">
                                <form onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
                                    <i class="fa fa-circle-o-notch fa-spin" style="display: none;"></i>
                                    <span></span>
                                    <!-- <input type="text" name="search" placeholder="Search fors your product" /> -->
                                    <input type="text" name="product_name" placeholder="Search for your product"/>
                                    <div class="show-search-results" style="">
                                        <div class="showcontent-container"></div>
                                        <div class="search-product-box">
                                            <div class="sticky-add-it-here">
                                                <h3>Can't find your product?</h3>
                                                <a class="btn btn-add-it">ADD IT HERE</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="cart-pop-up-container">
                            <div class="col-xs-4 col-md-4 item-cart-right" id="cart">
                                <?= $cart ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="new-product-toast-container"><div></div>

                </div>            
            </div>        
        </div>

