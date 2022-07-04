<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="kdt:page" content="home-page">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <meta http-equiv="content-language" content="<?= $config_language?>">

  <?php if ($description) { ?>
  <meta name="description" content="<?php echo $description; ?>" />
  <?php } ?>
  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?php echo $keywords; ?>" />
  <?php } ?>
  <title>
    <?= $heading_title ?>
  </title>
  <?php if ($icon) { ?>
  <link href="<?php echo $icon; ?>" rel="icon" />
  <?php } ?>

  <link href="<?= $base;?>front/ui/theme/metaorganic/assets/images/favicon.ico" rel="icon">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.min.css">
  <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.min.css" rel="stylesheet">
  <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.min.css" rel="stylesheet">
  <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.min.css" rel="stylesheet">
  <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.min.css?v=5.2.7">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/mvgv2/css/mycart.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/mvgv2/css/custom.min.css?v=1.1.0">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/list.min.css">
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.20.4/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/css/drawer.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/drawer.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/css/owl.carousel.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/css/owl.theme.min.css">
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.min.css">
  <style>
    body {
      padding-right: 0 !important;
    }
    .view-all-buttons {
    margin-top: 5px;
    /*margin-bottom: 24px;*/
    display: flex;
    align-items: center;
    justify-content: center;
    }

    @media (min-width:768px) and (max-width:1023px) {

      .header__primary-navigation-item--more-categories {
        background: #fff;
        border: 0;
        padding: 0 0px !important;
        width: 100% !important;
        top: 0;
        right: 0px;
      }
    }
  </style>
</head>

<body data-wrapper-optimized="" id="homenew" class="new-homepage-image-format drawer drawer--top">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger">
    <center><i class="fa fa-exclamation-circle"></i>
      <?php echo $error_warning; ?>
    </center>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <div
    data-sticky-banner="{enable: false, enableOnDesktop:false, enableOnTablet:false, enableOnMobile:false, staticStartDistance:130}"
    id="leaderboard-header-banner" class="header__banner-container header__banner-container--hidden">
    <div class="header__banner-container-close"></div>
  </div>
  <div class="col-md-12"
    style="position: relative; z-index: 1040; padding-bottom: 16px; border-bottom: 1px solid #ea6f28; margin-bottom: 14px;">

    <div class="row" style="margin-top: 25px;">
      <div class="col-md-2">
        <div class="header__logo-container">
          <a class="header__logo-link " href="<?= BASE_URL?>">
            <img src="<?=$logo?>" />
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

            <form id="search-form-form" class="search-form c-position-relative search-form--switch-category-position"
              action="#" method="get">
              <ul class="header__search-bar-list header__search-bar-item--before-keyword-field">

                <li class="header__search-bar-item header__search-bar-item--category search-category-container">
                  <div>
                      <select class="form-control" id="selectedCategory" data-url="<?= $category_url; ?>">
                      <option value="">- Select categories-</option>
                      <?php foreach($categories_new as $category){ ?>
                      <option value="<?=$category['category_id']?>" <?php if(isset($this->request->get['filter_category']) && $this->request->get['filter_category'] > 0 && $this->request->get['filter_category'] == $category['category_id']) { echo "selected"; } ?> >
                        <?=$category['name']?>
                      </option>
                      <?php } ?>

                    </select>
                  </div>
                </li>
                <li class="header__search-bar-item header__search-bar-item--location search-location-all">
                  <div class="header__search-location search-location">
                    <i class="fas fa-search header__search-location-icon" aria-hidden="true"></i>

                    <!-- SuggestionWidget  start -->
                    <div id="search-area-wrp" class="c-sggstnbx header__search-input-wrapper">
                      <form id="product-search-form" class="navbar-form active" role="search"
                        onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
                        <div class="input-group">
                          <input type="text" name="product_name" id="product_name"
                            class="header__search-input zipcode-enter" placeholder="Search for your product" />
                          <span class="input-group-btn">
                            <div class="resp-searchresult">
                              <div></div>
                            </div>
                          </span>
                        </div>
                      </form>
                    </div>
                  </div>
                </li>
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
                <?php if(!$is_login){?>
                <li class="header__upper-deck-item header__upper-deck-item--register">
                  <a data-toggle="modal" data-dismiss="modal" data-target="#signupModal-popup"
                    class="header__upper-deck-item-link register" data-spinner-btn="{showOnSubmit: false}">
                    Register</a>
                </li>
                <li class="header__upper-deck-item header__upper-deck-item--signin">
                  <a data-toggle="modal" data-target="#phoneModal" class="header__upper-deck-item-link sign-in"
                    data-spinner-btn="{showOnSubmit: false}">
                    Sign In</a>
                </li>
                <li class="header__upper-deck-item header__upper-deck-item setcartbtn">
                  <div class="butn setui"> <button class="btn btn-default mini-cart-button" role="button"
                      data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
                      <span class="badge cart-count">
                        <?= $this->cart->countProducts(); ?>
                      </span>
                      <i class="fa fa-shopping-cart"></i>
                      <span class="hidden-xs hidden-sm cart-total-amount">
                        <?= $this->currency->format($this->cart->getTotal()); ?>
                      </span>
                    </button></div>
                </li>
                <?php }else{?>
                <div>
                  <div class="menuset">
                    <div class="newset" style="margin-top: 20px;"><a class="btn" href="#">
                        <span>
                          <?= $full_name ?>
                        </span> </a>

                      <div class="dropdownset" style="display:none;">
                        <div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link"
                            href="<?= $dashboard ?>"><i class="fa fa-user"></i>Dashboard</a></div>
                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $po_ocr ?>"><i
                              class="fa fa-file-text"></i>Purchase Order &nbsp;<span
                              class="badge badge-pill badge-success">BETA</span></a></div>
                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $wishlist ?>"><i
                              class="fa fa-shopping-basket"></i>My Basket</a></div>
                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $account ?>"><i
                              class="fa fa-user"></i>My Account</a></div>
                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $help ?>"><i
                              class="fa fa-question-circle"></i>Help</a></div>
                        <div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="<?= $logout ?>"><i
                              class="fa fa-power-off"></i>
                            <?= $text_logout ?>
                          </a></div>
                      </div>
                    </div>
                    <div class="butn setui" style="position:relative; z-index:-1000;">
                    <a  onclick="checkMinimumOrderTotal();">
                      <div class="btn btn-default mini-cart-button" role="button" data-toggle="modal"
                        data-target="#store-cart-sides" id="mini-cart-button"
                        style="margin-right:10px; margin-top:0px; display:flex; flex-flow: column nowrap;">
                        <div style="display:flex; align-items: center;">
                          <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;
                          <span class="hidden-xs hidden-sm cart-total-amount">
                            <?= $this->
                          currency->format($this->cart->getTotal()); ?>
                          </span>
                        </div>
                        <span class="badge cart-count" style="margin: 4px 0px;">
                          <?= $this->cart->countProducts(); ?>
                          items in cart
                        </span>
                      </div>
                      </a>
                    </div>

                 <?php if($this->config->get('wallet' . '_status')){?>
                     <div class="butn setui" style="position:relative;z-index:-1000; padding-right:0px;">
                    <a href=<?= $wallet_url ?>>
                      <div class="btn mini-wallet-button" role="button"  
                          id="mini-wallet-button"
                        style="margin-right: -10px;margin-top:0px;display:flex;flex-flow: column nowrap;background-color:transparent!important">
                        <div style="display:flex;align-items: center;align-content: center;justify-content: center;">
                          <i class="fa fa-money"></i>&nbsp;&nbsp;                           
                        </div>
                        <span class="badge mini-wallet" style="margin: 8px 0px;">
                           <?= $wallet_amount ?>
                        </span>
                      </div>
                      </a>
                    </div>

                  <?php }?>
                  </div>
                </div>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>

  <div style="clear:both !important"> </div>
  
  <!-- HIDE FREQUENTLY BOUGHT PRODUCTS -->
  <?php if(count($mostboughtproducts) > 0 && $this->customer->getId() == 0) { ?>
  <div class="container--full-width featured-categories">
      <div class="container" style="width:100%;">
          <div class="_47ahp" data-test-selector="search-results">
              <div style="margin-top: 16px" class="clearfix featured-categories__header">
                  <h2 class="featured-categories__header-title"><span>Frequently Bought</span></h2>
              </div>
              <ul id="items-ul" class="row" data-test-selector="item-cards-layout-grid">
                  <?php if(count($mostboughtproducts) > 0) { foreach($mostboughtproducts as $mostboughtproduct ) { ?>
                  <li class="col-md-2" style="min-height: 265px">
                      <span class="view-all-buttons"><?php echo $mostboughtproduct['vendor_display_name']; ?></span>
                      <a class="product-detail-bnt open-popup" role="button" data-store="<?= $mostboughtproduct['store_id'] ?>" data-id="<?= $mostboughtproduct['product_store_id'] ?>" target="_blank" aria-label="<?= $mostboughtproduct['name'] ?>">
                          <img class="_1xvs1" src="<?= $mostboughtproduct['thumb'] ?>" title="<?= $mostboughtproduct['name'] ?>" alt="<?= $mostboughtproduct['name'] ?>">
                          <div class="_25ygu"><?= $mostboughtproduct['name'] ?><br>
                              <div style="color:#6dbd46">
                                  <?= $mostboughtproduct['variations'][0]['special'];?>
                                  <?php  echo '/ Per ' . $mostboughtproduct['variations'][0]['unit']; ?>
                              </div>
                              <span id="flag-qty-id-<?= $mostboughtproduct['product_store_id']; ?>-<?= $mostboughtproduct['store_product_variation_id']; ?>" style="padding:5px;display: <?= $mostboughtproduct['qty_in_cart'] > 0 ? 'block' : 'none'; ?>">
                                  <?php echo $mostboughtproduct['qty_in_cart']; ?> items in cart <i class="fas fa-flag"></i>
                              </span>
                          </div>
                      </a>
                  </li>

                  <!--- Product Details Modal Start --->
                  <div id="product_<?=$mostboughtproduct['product_id']?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body class=" col-lg-2 col-md-4 col-sm-6 col-xs-6 nopadding product-details"
                                   style="border-right: 1px solid rgb(215, 220, 214);">
                                   <div>

                                      <?php /*echo "<pre>";print_r($mostboughtproduct);die;*/ if(isset($mostboughtproduct['percent_off']) && $mostboughtproduct['percent_off'] != '0.00') { ?>

                                      <span class="spacial-offer">
                                          <?php echo $mostboughtproduct['percent_off'].'% OFF';?>
                                      </span>
                                      <?php } ?>


                                      <?php if($this->customer->isLogged()) { ?>


                                      <a href="#"
                                         class="add-to-list list_button<?= $mostboughtproduct['product_store_id'] ?>-<?= $mostboughtproduct['store_product_variation_id'] ?>"
                                         id="list-btn" data-id="<?= $mostboughtproduct['product_id'] ?>" type="button" data-toggle="modal"
                                         data-target="#listModal"><img class="add-list-png"
                                                                    src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png">
                                      </a>

                                      <?php } else { ?>
                                      <a href="#" class="add-to-list" type="button" data-toggle="modal" data-target="#phoneModal"><img
                                              class="add-list-png" src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png"></a>

                                      <?php } ?>
                                  </div>
                                  <div class="product-block" data-id="<?= $mostboughtproduct['product_store_id'] ?>">

                                      <div class="product-img product-description open-popup"
                                           data-id="<?= $mostboughtproduct['product_store_id'] ?>" data-id="<?= $mostboughtproduct['product_store_id'] ?>">
                                          <img class="lazy" data-src="<?= $mostboughtproduct['thumb'] ?>" alt="">
                                      </div>
                                      <div class="product-description" data-id="<?= $mostboughtproduct['product_store_id'] ?>">


                                          <h3 class="open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>">

                                              <a class="product-title">
                                                  <?= $mostboughtproduct['name']?>
                                              </a>
                                          </h3>

                                          <?php if(trim(isset($mostboughtproduct['unit']))){ ?>
                                          <p class="product-info open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>"><span
                                                  class="small-info">
                                                  <?= $mostboughtproduct['unit'] ?>
                                              </span></p>
                                          <?php } else { ?>
                                          <p class="product-info open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>"><span
                                                  class="small-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                                          <?php } ?>

                                          <div class="product-price">
                                              <?php if ( $mostboughtproduct['variations'][0]['special'] == '0.00' || empty(trim($mostboughtproduct['variations'][0]['special']))) { ?>
                                              <span class="price-cancelled open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>"
                                                    style="display: none" ;>
                                              </span>
                                              <span class="price open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>">
                                                  <?php echo $mostboughtproduct['variations'][0]['price']; ?>
                                              </span>
                                              <?php } else { ?>
                                              <span class="price-cancelled open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>">
                                                  <?php echo $mostboughtproduct['variations'][0]['price']; ?>
                                              </span>
                                              <span class="price open-popup" data-id="<?= $mostboughtproduct['product_store_id'] ?>">
                                                  <?php echo $mostboughtproduct['variations'][0]['special']; ?>
                                              </span>
                                              <?php } ?>
                                              <div class="pro-qty-addbtn" data-store-id="<?= isset($current_store) ? $current_store : '' ?>"
                                                   data-variation-id="<?= $mostboughtproduct['store_product_variation_id'] ?>"
                                                   id="action_<?= $mostboughtproduct['product_store_id'] ?>">

                                                  <?php require 'action.tpl'; ?>
                                              </div>
                                          </div>

                                      </div>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  Footer
                              </div>
                          </div>
                          <!----- Product Detail Modal End --->

                      </div>
                  </div>
                  <?php } } ?>
              </ul>
          </div>
          <span class="view-all-button"><a href=<?= $mostboughtproducts_url; ?>>View All Frequently Bought</a></span>
      </div>
  </div>
  <?php } ?>
  <div class="container--full-width featured-categories">
      <div class="container" style="width:100%;">
          <div class="_47ahp" data-test-selector="search-results">
              <div class="row">
                  <div class="col-md-4">
                  </div>
                  <div class="col-md-5">
                  </div>
                  <div class="col-md-3">
                      <select class="form-control" id="sorting" name="sorting" style="height:34px !important;" data-url="<?= $category_url; ?>">
                          <option value="">Sort Products</option>
                          <option value="">Default</option>
                          <option value="nasc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'nasc') { echo "selected"; } ?> >Name (A-Z)</option>
                          <option value="ndesc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'ndesc') { echo "selected"; } ?> >Name (Z-A)</option>
                          <option value="pasc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'pasc') { echo "selected"; } ?> >Price (Low > High)</option>
                          <option value="pdesc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'pdesc') { echo "selected"; } ?> >Price (High > Low)</option>
                      </select>
                  </div>
              </div>
          </div>  
      </div>
  </div>
  <?php
					$i=0;
					foreach($categories as $category){
					$i++;
					       $link_array = explode('/',$category['href']);
                           $page_link = end($link_array);

					  ?>

  <?php if(count($category['products'])>0) { ?>
  <div class="container--full-width featured-categories <?php if($i == 1) { echo "first-feature-cat"; } ?> ">
    <div class="container" style="width:100%;">
      <div class="_47ahp" data-test-selector="search-results">
        <div style="margin-top: 16px" class="clearfix featured-categories__header">
          <h2 class="featured-categories__header-title"><span>
              <?=$category['name']?>
            </span></h2>

        </div>
        <ul id="items-ul" class="row" data-test-selector="item-cards-layout-grid">

          <?php foreach($category['products'] as $product) { ?>
          <li class="col-md-2" style="min-height: 265px">
            <span class="view-all-buttons"><?php echo $product['vendor_display_name']; ?></span>
            <a class="product-detail-bnt open-popup" role="button" data-store="<?= $product['store_id'] ?>"
              data-id="<?= $product['product_store_id'] ?>" target="_blank" aria-label="<?=$product['name']?>">
              <img class="_1xvs1" src="<?=$product['thumb']?>" title="<?=$product['name']?>"
                alt="<?=$product['name']?>">
              <div class="_25ygu">
                <?=$product['name']?>
                <br/>
                
                <?php if($product['variations'][0]['category_price_discount_percentage'] > 0) { ?>
                <div>
                  <del><?= $product['variations'][0]['category_price_discount_amount'];?></del>(<?= $product['variations'][0]['category_price_discount_percentage'];?>% OFF)
                </div>
                <?php } ?>
                
                <div style="color:#6dbd46">
                  <?= $product['variations'][0]['special'];?>
                  <?php  echo '/ Per ' . $product['variations'][0]['unit']; ?>
                </div>
                <span id="flag-qty-id-<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"
                  style="padding:5px;display: <?= $product['qty_in_cart'] ? 'block' : 'none'; ?>">
                  <?php echo $product['qty_in_cart']?>
                  items in cart <i class="fas fa-flag"></i>
                </span>
              </div>
            </a>
          </li>
          <!--- Product Details Modal Start --->
          <div id="product_<?=$product['product_id']?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body class="col-lg-2 col-md-4 col-sm-6 col-xs-6 nopadding product-details"
                  style="border-right: 1px solid rgb(215, 220, 214);">
                  <div>

                    <?php /*echo "<pre>";print_r($product);die;*/ if(isset($product['percent_off']) && $product['percent_off'] != '0.00') { ?>

                    <span class="spacial-offer">
                      <?php echo $product['percent_off'].'% OFF';?>
                    </span>
                    <?php } ?>


                    <?php if($this->customer->isLogged()) { ?>


                    <a href="#"
                      class="add-to-list list_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"
                      id="list-btn" data-id="<?= $product['product_id'] ?>" type="button" data-toggle="modal"
                      data-target="#listModal"><img class="add-list-png"
                        src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png">
                    </a>

                    <?php } else { ?>
                    <a href="#" class="add-to-list" type="button" data-toggle="modal" data-target="#phoneModal"><img
                        class="add-list-png" src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png"></a>

                    <?php } ?>
                  </div>
                  <div class="product-block" data-id="<?= $product['product_store_id'] ?>">

                    <div class="product-img product-description open-popup"
                      data-id="<?= $product['product_store_id'] ?>" data-id="<?= $product['product_store_id'] ?>">
                      <img class="lazy" data-src="<?= $product['thumb'] ?>" alt="">
                    </div>
                    <div class="product-description" data-id="<?= $product['product_store_id'] ?>">


                      <h3 class="open-popup" data-id="<?= $product['product_store_id'] ?>">

                        <a class="product-title">
                          <?= $product['name']?>
                        </a>
                      </h3>

                      <?php if(trim(isset($product['unit']))) { ?>
                      <p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span
                          class="small-info">
                          <?= $product['unit'] ?>
                        </span></p>
                      <?php } else { ?>
                      <p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span
                          class="small-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                      <?php } ?>

                      <div class="product-price">
                        <?php if ( $product['variations'][0]['special'] == '0.00' || empty(trim($product['variations'][0]['special']))) { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>"
                          style="display: none" ;>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                          <?php echo $product['variations'][0]['price']; ?>
                        </span>
                        <?php } else { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>">
                          <?php echo $product['variations'][0]['price']; ?>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                          <?php echo $product['variations'][0]['special']; ?>
                        </span>
                        <?php } ?>
                        <div class="pro-qty-addbtn" data-store-id="<?= isset($current_store) ? $current_store : '' ?>"
                          data-variation-id="<?= $product['store_product_variation_id'] ?>"
                          id="action_<?= $product['product_store_id'] ?>">

                          <?php require 'action.tpl'; ?>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  Footer
                </div>
              </div>
              <!----- Product Detail Modal End --->

            </div>
          </div>
          <?php }?>

        </ul>

        <?php }?>

      </div>
      <style>
        .view-all-button {
          margin-bottom: 24px;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .view-all-button a {
          text-align: center;
          color: #fff;
          padding: 7px 10px;
          font-size: 10px !important;
          font-weight: 600;
          text-transform: uppercase;
          user-select: none;
          cursor: pointer;
          background-color: rgb(132, 194, 37);
          border-radius: 4px;
        }
      </style>
      <?php if(count($category['products'])>11) { ?>
      <span class="view-all-button"><a
          href="<?=$this->url->link('common/home/allproducts&filter_category='.$category['id'])?>">View All
          <?=$category['name']?>
        </a></span>
      <?php } ?>
    </div>


  </div>


  <?php } ?>
  
  <!-- /.page-container -->
  <div class="below-the-fold">
    <!-- /.page-container -->
    <div class="container--full-width section section--alternate" id="homepage-leaderboard-bottom">
      <div id="div-gpt-ad-632115744089839813-leaderboard-footer" class="clearfix placement google-banner"
        data-track-action="homepage" data-track-label="bottom1" style="display: none;"></div>
    </div>

  </div>
  <!--Cart HTML Start-->
  <div class="store-cart-panel">
    <div class="modal right fade" id="store-cart-side" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="cart-panel-content">
          </div>
          <div class="modal-footer">
            <!-- <p><?= isset($text_verify_number) ? $text_verify_number : '' ?></p> -->
            <a href="<?php echo $checkout; ?>" id="proceed_to_checkout">

              <button type="button" class="btn btn-primary btn-block btn-lg" id="proceed_to_checkout_button">
                <span class="checkout-modal-text">
                  <?= isset($text_proceed_to_checkout) ? $text_proceed_to_checkout : '' ?>
                </span>
                <div class="checkout-loader" style="display: none;"></div>

              </button>
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!--Cart HTML End-->
  <div class="modal-wrapper"></div>


    <!-- Modal -->
    <div class="addressModal">
        <div class="modal fade" id="exampleModal_deliverycharge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <div class="row">
                            <div class="col-md-12">
                              <h2>Accept Delivery Charge</h2>
                            </div>
                            <div class="modal-body">

                             

                            <p style="font-weight: bold; font-size: 12px;"><span id="min_required_free_delivery" style="font-weight: bold; font-size: 12px;"></span>  -  away from minimum order value.  Delivery charge - <span id="min_required_free_delivery_charge" style="font-weight: bold; font-size: 12px;"></span>  will be added<span style="color:#ea7128;"></span></p>
                            </div>
                            <div class="addnews-address-form">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button style="width:40%" id="agree_delivery_charge" name="agree_delivery_charge" type="button" class="btn btn-primary">I AGREE</button>
                                        <button style="width:40%" id="cancel_delivery_charge" name="cancel_delivery_charge" type="button" class="btn btn-grey  cancelbut" data-dismiss="modal">DECLINE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

  <?php echo $footer ?>
  <!-- Phone Modal -->
  <?= $login_modal ?>
  <?= $signup_modal ?>
  <?= $forget_modal ?>
  <?= $contactus_modal ?>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
  <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
  <script src="<?= $base;?>front/ui/javascript/easyzoom.js"></script>

  <script type="text/javascript"
    src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

  <script src="<?= $base;?>front/ui/javascript/home.js?v=1.0.4"></script>
  <script src="<?= $base;?>front/ui/javascript/bxslider/jquery.bxslider.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.20.4/sweetalert2.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
  <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>
  <!-- <script src="<?= $base;?>front/ui/theme/mvgv2/js/iscroll.min.js"></script>
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/drawer.min.js" type="text/javascript"></script> -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/js/drawer.min.js" type="text/javascript"></script>

  <script src="<?= $base;?>front/ui/javascript/common.js?v=2.0.5" type="text/javascript"></script>
  <script src="<?= $base; ?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8"
    type="text/javascript"></script>

<script type="text/javascript">
$(document).delegate('#selectedCategory', 'change', function () {
console.log($( this ).val());
console.log($( this ).attr('data-url'));
var url = $( this ).attr('data-url');
var sorting = $("#sorting").val();
window.location.replace(url+"index.php?path=common/home&filter_category="+$( this ).val()+"&filter_sort="+sorting);
});

$(document).delegate('#sorting', 'change', function () {
console.log($( this ).val());
console.log($( this ).attr('data-url'));
var url = $( this ).attr('data-url');
var selectedCategory = $("#selectedCategory").val();
window.location.replace(url+"index.php?path=common/home&filter_sort="+$( this ).val()+"&filter_category="+selectedCategory);
});




 $('#agree_delivery_charge').on('click', function(){
        $.ajax({
            url: 'index.php?path=checkout/confirm/AcceptDeliveryCharge',
            type: 'post',
            data: 'accept_terms=true',
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(json) {
                console.log(json);
                if (json['delivery_charge_terms']) {
                   $('#exampleModal_deliverycharge').modal('hide');
                   window.location.href = "<?= $continue.'/index.php?path=checkout/checkoutitems'; ?>";
                }else{
                  $('#exampleModal_deliverycharge').modal('show');
                }
            }
        });
        });




function checkMinimumOrderTotal() {

    $.ajax({
        url: 'index.php?path=checkout/confirm/CheckMinimumOrderTotal',
         type: 'post',
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {
            },
        success: function(json) {
            if (json['min_order_total_reached']=="FALSE") {
                      //$("#proceed_to_checkout").addClass("disabled"); 
                 $('#exampleModal_deliverycharge').modal('show');
                  $('#min_required_free_delivery').text(json['amount_required']);
                  $('#min_required_free_delivery_charge').text(json['delivery_charge']);

                             return false;
                }else{
            //$("#proceed_to_checkout").removeClass("disabled"); 

                 $('#exampleModal_deliverycharge').modal('hide');  
                 $('#min_required_free_delivery').text('');   
                  $('#min_required_free_delivery_charge').text('');

                  window.location.href = "<?= $continue.'/index.php?path=checkout/checkoutitems'; ?>";


                   
                }
        },
        error: function(xhr, ajaxOptions, thrownError) {
        }
    });
}

</script>

  <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
  <style type="text/css">

   #agree_delivery_charge {
    width: 49%;
    float: left;
    margin-top: 10px;
    margin-right: 5px;
    }

    @keyframes highlight {
      0% {
        background: #FFD700
      }

      100% {
        background: none;
      }
    }

    #welcome-login {
      animation: highlight 2s;
    }
  </style>

  <!-- <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap-datepicker.pt-BR.js"></script> -->
  <script
    src="https://cdn.jsdelivr.net/bootstrap.datepicker-fork/1.3.0/js/locales/bootstrap-datepicker.pt-BR.js"></script>

  <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      $(".newset").mouseleave(function () {
        $(".dropdownset").css("display", "none");
      });
      $(".newset").mouseover(function () {
        $(".dropdownset").css("display", "block");
      });

      $(".dropdownset").mouseleave(function () {
        $(".dropdownset").css("display", "none");
      });
      $(".dropdownset").mouseover(function () {
        $(".dropdownset").css("display", "block");
      });
    });


    //  aniback();


    // function aniback() {

    //   $.when(
    //        $('.hero-image').animate({
    //          'background-position-y': ($('.hero-image').css('background-position-y').replace(/[^0-9-]/g, '') - 15 ) + 'px'
    //      }, 1000, 'linear')
    //      ).then(aniback);

    //  }


    /*
       setInterval(function(){
   
       $('.hero-image').animate({
            //'background-position-y': '-15px'
            'background-position-y': ($('.hero-image').css('background-position-y').replace(/[^0-9-]/g, '') - 20 ) + 'px'
         }, 1000, 'linear');
   
       },1000);
   
   
    */

    // $('.zipcode-enter').focus();

    $('.date-dob').datepicker({
      pickTime: false,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      autoclose: true,
      language: '<?php echo $config_language ?>'
    });

    /*$('#signupModal-popup').on('shown.bs.modal', function() {

        console.log("load modal");
        $("#register_phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});

    });

    jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    jQuery(function ($) {
      console.log(" fax-number mask");
      $("#fax-number").mask("<?= $taxnumber_mask_number ?>", { autoclear: false, placeholder: "<?= $taxnumber_mask ?>" });
    });

    /*$('.zipcode-enter').keydown(function (e) {
      if (e.which == 13 && $('.pac-container:visible').length) return false;
    });*/
    /*$('.zipcode-enter').keydown(function(event) {

        console.log("zipcode enter");
        if (event.keyCode == 13) {
          return false;
        }
    }); */

    $(document).delegate('#test-drawer', 'click', function (e) {
      $('.drawer-nav').addClass('how-it-works-open');
      $('.header-transparent').addClass('how-it-works-open');
      $('.hero-image').addClass('how-it-works-open');

    });

    $("body").on("click", function (e) {
      if ($('.how-it-works-open').length >= 1) {
        $('.drawer-nav').removeClass('how-it-works-open');
        $('.drawer.drawer--top').removeClass('drawer-open');
        $('.header-transparent').removeClass('how-it-works-open');
        $('.hero-image').removeClass('how-it-works-open');
      }
    });
    $(document).delegate('.how-it-works-close', 'click', function (e) {
      $('.drawer-nav').removeClass('how-it-works-open');
      $('.drawer.drawer--top').removeClass('drawer-open');
      $('.header-transparent').removeClass('how-it-works-open');
      $('.hero-image').removeClass('how-it-works-open');
    });

    function validateInp(elem) {
      var validChars = /[0-9]/;
      var strIn = elem.value;
      var strOut = '';
      for (var i = 0; i < strIn.length; i++) {
        strOut += (validChars.test(strIn.charAt(i))) ? strIn.charAt(i) : '';
      }
      elem.value = strOut;
    }

  </script>
  <script type="text/javascript">

    <?php if ($this->config->get('config_store_location') == 'zipcode') { ?>

      jQuery(function ($) {
        console.log("mask");
        $("#searchTextField").mask("<?= $zipcode_mask_number ?>", { autoclear: false, placeholder: "<?= $zipcode_mask ?>" });
      });

    <?php } ?>
  </script>
</body>

<?php if ($kondutoStatus) { ?>



<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>
<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
  __kdt.push({ "public_key": public_key }); // The public key identifies your store
  __kdt.push({ "post_on_load": false });
  (function () {
    var kdt = document.createElement('script');
    kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
    kdt.async = true; kdt.src = 'https://i.k-analytix.com/k.js';
    var s = document.getElementsByTagName('body')[0];

    console.log(s);
    s.parentNode.insertBefore(kdt, s);
  })();

  var visitorID;
  (function () {
    var period = 300;
    var limit = 20 * 1e3;
    var nTry = 0;
    var intervalID = setInterval(function () {
      var clear = limit / period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof (Konduto.getVisitorID) !== "undefined") {
        visitorID = window.Konduto.getVisitorID();

        console.log("visitorIDif");
        console.log("visitorIDif" + visitorID);
        console.log(visitorID);
        $.ajax({
          url: 'index.php?path=common/home/saveVisitorId&visitor_id=' + visitorID,
          type: 'post',
          dataType: 'json',
          success: function (json) {
            console.log(json);
          }
        });
        clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
        clearInterval(intervalID);
      }
    }, period);
  })(visitorID);


  var page_category = 'home';
  (function () {
    var period = 300;
    var limit = 20 * 1e3;
    var nTry = 0;
    var intervalID = setInterval(function () {
      var clear = limit / period <= ++nTry;
      if (typeof (Konduto.sendEvent) !== "undefined") {

        Konduto.sendEvent(' page ', page_category); //Programmatic trigger event
        clear = true;
      }
      if (clear) {
        clearInterval(intervalID);
      }
    },
      period);
  })(page_category);
</script>
<?php } ?>
<script>
  $(document).ready(function () {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
      items: 5,
      itemsTablet: 4,
      itemsMobile: 3,
      loop: true,
      margin: 10,
      navigation: true,
      navigationText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
      autoPlay: true,
      autoPlayTimeout: 1000,
      autoPlayHoverPause: true
    });
    $('.play').on('click', function () {
      owl.trigger('play.owl.autoplay', [1000])
    })
    $('.stop').on('click', function () {
      owl.trigger('stop.owl.autoplay')
    })
  })
</script>
<script type="text/javascript">
  $(document).delegate('#clearcart', 'click', function () {
    var choice = confirm($(this).attr('data-confirm'));

    if (choice) {

      $.ajax({
        url: 'index.php?path=checkout/cart/clear_cart',
        type: 'post',
        data: '',
        dataType: 'json',
        success: function (json) {
          if (json['location']) {
            location = json.redirect;
            location = location;
          }
        }
      });
    }
  });

  $(document).delegate('.close-model', 'click', function () {
    console.log("close product block");
    $('#bannermodal').modal('hide');
    $('.modal-backdrop').remove();
  });

  $(document).ready(function () {
    $(document).delegate('.open-popup', 'click', function () {
       $('.search-dropdown').css('display', 'none');
       $('.search-dropdown').find('li').remove();
      $('.open-popup').prop('disabled', true);
      // console.log("product blocks" + $(this).attr('data-id'));
      $.get('index.php?path=product/product/view&product_store_id=' + $(this).attr('data-id') + '&store_id=' + $(this).attr('data-store'), function (data) {
        $('.open-popup').prop('disabled', false);
        $('.modal-wrapper').html(data);
        $('#popupmodal').modal('show');
      });
      $('#product_name').val('');
    });
  });

</script>
<script type="text/javascript">

  $("#sidebarss").stick_in_parent();


  if (window.screen.availWidth < 450 || window.screen.availHeight < 732) {
    $("#sidebarss").trigger("sticky_kit:detach");
  } else {
    $("#sidebarss").stick_in_parent();
  }

  $('.add-to-list').on('click', function (e) {

    console.log("erg");
    data = {
      product_id: $(this).data("id")
    }

    $.ajax({
      url: 'index.php?path=account/wishlist/getProductWislists',
      type: 'post',
      data: data,
      dataType: 'json',
      success: function (json) {
        if (json['status']) {

          console.log(json);
          $('#users-list').html(json['html']);
        }
      }
    });
  });

  /* Cart Open jquery Code */
  $("#mini-cart-button").click(function () {
    $("#toTop").show();
    $("#toTop").css('opacity', '1.0');
  });
</script>
<style>

.mini-wallet-button
{
  color: #ea7128!important;
    background-color: transparent!important;
    box-shadow: none;
    border: none!important;
    padding: 6px 14px;
    border-radius: 2px;
    font-size: 15px;
    font-weight: 600;
}

.mini-wallet
{
  background-color:#ea7128
}

.mini-wallet-button:hover {
    color: #ccc !important;
}
</style>

</html>