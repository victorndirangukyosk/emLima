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
    <meta name="description" content="<?php echo $description; ?>"/>
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>"/>

    <?php } ?>
    <title><?= $title ?></title>
    <!-- BEGIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.css" rel="stylesheet">
    <!-- END CSS -->

    <!-- Bootstrap -->

    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.css?v=5.1">
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/abhishek.css"> -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/mycart.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.css">
    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon"/>
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
    <script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8"
            type="text/javascript"></script>
    <script type="text/javascript" src="https://js.iugu.com/v2"></script>
    <!--<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/metaorganic/stylesheet/style.css" media="all">-->
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/metaorganic/stylesheet/responsive.css"
          media="all">
    <script src="<?= $base;?>front/ui/javascript/easyzoom.js"></script>
</head>

<body>
<?php if ($error_warning) { ?>
<div class="alert alert-danger">
    <center><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></center>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>

<?php if ($success) { ?>

<div class="alert alert-success normalalert">
    <p class="notice-text"> <?php echo $success; ?> </p>
</div>

<?php } ?>


<div class="overlayed"></div>
<div class="alerter">
    <?php if($notices){ ?>
    <div class="alert alert-danger normalalert">
        <?php foreach($notices as $notice){ ?>
        <p class="notice-text"><?= $notice ?></p>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<header>

    <div class="col-md-12"
         style="position: relative; z-index: 1040;  padding-bottom: 16px; border-bottom: 1px solid #ea6f28; margin-bottom: 14px; background-color:#fff">

        <div class="row" style="margin-top:25px;">
            <div class="col-md-2">
                <div class="header__logo-container">
                    <a class="header__logo-link " href="<?= BASE_URL?>">
                        <img src="<?=$logo?>"/>

                    </a>

                </div>
            </div>
            <div class="col-md-7">
                <div class="header__search-bar-wrapper">
                    <div id="search-form-wrapper" class="header__search-bar search-form-wrapper">
                        <div class="header__search-title">
                            Search
                            <div class="header__mobile-search-close j-mobile-close-search-trigger"></div>
                        </div>

                        <form id="search-form-form" class="search-form c-position-relative search-form--switch-category-position" action="#" method="get">
                            <ul class="header__search-bar-list header__search-bar-item--before-keyword-field">

                                <li class="header__search-bar-item header__search-bar-item--category search-category-container">
                                    <div >
                                        <select class="form-control" id="selectedCategory">
                                            <option value="">- Select categories-</option>
                                            <?php foreach($categories as $categoty){
                                            //print_r($categoty);exit;?>
                                            <option value="<?=$categoty['category_id']?>"><?=$categoty['name']?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </li>
                                <li class="header__search-bar-item header__search-bar-item--location search-location-all">
                                    <div class="header__search-location search-location">
                                        <i class="fa fa-map-marker header__search-location-icon" aria-hidden="true"></i>

                                        <!-- SuggestionWidget  start -->
                                        <div id="search-area-wrp" class="c-sggstnbx header__search-input-wrapper">
                                            <form  id="product-search-form"  class="navbar-form active" role="search" onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
                                                <div class="input-group">
                                                    <input type="text" name="edit_product_name" id="edit_product_name"  class="header__search-input zipcode-enter" placeholder="Search for your product" />
                                                    <span class="input-group-btn">
                                                        <!--<button type="submit" class="search-btn"> <span class="glyphicon glyphicon-search"> <span class="sr-only">Search</span> </span> </button>-->
                                                        <div class="resp-searchresult">
                                                            <div></div>
                                                        </div>
                                                    </span> </div>
                                            </form>

                                            <?php /* if($this->config->get('config_store_location') == 'autosuggestion') { ?>
                                            <input name="zipcode" id="searchTextField"  class="header__search-input zipcode-enter" type="text"  required="" alt=""  maxlength="" size="" tabindex="3" placeholder="Find Stores in your Location" highlight="y" strict="y" autocomplete="off">
                                            <?php } else { ?>
                                            <input name="zipcode" id="searchTextField"  class="header__search-input zipcode-enter" type="text"  required="" alt=""  maxlength="" size="" tabindex="3" placeholder="<?= $zipcode_mask ?>" highlight="y" strict="y" autocomplete="off">

                                            <?php } */ ?>



                                            <!--<input type="hidden" name="store_list_url" value="<?=BASE_URL ?>">

                                            <input type="hidden" id="store_location" value="<?= $this->config->get('config_store_location'); ?>">-->




                                        </div>
                                    </div>
                                </li>
                                <!--<li class="header__search-bar-item header__search-bar-item--submit search-submit">
                                   <button type="submit" tabindex="5" data-spinner-btn="" class="header__search-button">
                                         <i class="fa fa-search header__search-button-icon header__search-button-icon--search" aria-hidden="true"></i>
     
                                      <span class="header__search-button-text">Search</span>
                                   </button>
                                </li>-->

                            </ul>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="header__navigation-container" role="navigation">

                    <div class="header__primary-navigation-outer-wrapper">

                        <div class="header__primary-navigation-item header__primary-navigation-item--more-categories">

                            <div class="header__secondary-navigation-tablet-container"></div>
                            <ul class="header__upper-deck-list">
                                <div>
                                    <div class="menuset">
                                        <div class="newset"><a class="btn" href="<?= $account ?>">
                                                <span>MY ACCOUNT</span> </a>
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

