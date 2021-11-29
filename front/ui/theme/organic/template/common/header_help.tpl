<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="<?= $config_language?>">
    
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <title><?= $title ?></title>
    <!-- Bootstrap -->
    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/abhishek.css?v=2.0.6">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.css?v=5.1">
    
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <!-- <link href="<?= $base;?>front/ui/theme/mvg/stylesheet/layout.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,400,600,300" /> -->

    <!-- <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?> -->

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/stylesheet/layout_help.css">
    
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base;?>front/ui/javascript/common.js" type="text/javascript"></script>
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script src="<?= $base;?>front/ui/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <script src="<?= $base;?>front/ui/theme/mvgv2/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
     <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/responsive.css" media="all">
</head>

<body>
   <header>
        

    <div id="header">
      <div class="container">
        <div class="header-container row">
         <?php // echo (isset($_SERVER['HTTPS']) ? 'http' : 'https' ). "://" . $_SERVER['SERVER_NAME'];?>
          <div class="logo">   <a href="<?=(isset($_SERVER['HTTPS']) ? 'https' : 'http' ). "://" . $_SERVER['SERVER_NAME']; ?>" class="hidden-xs hidden-sm header_item_content">
          <img src="<?= $logo ?>" alt=""><!--<span class="logo-text">Emlima</span>-->
          </a>
                 </div>
         
          
          <!--row-->
          
          <div class="fl-header-right">
            <div class="fl-links">
              <div class="no-js"> 
              <?php if($is_login) { ?>
               <a title="Company" class="clicker"></a>
               <?php }else{ ?>
                <div class=top-left-login-signup>Login / Signup</div>
               <?php } ?>
                <div class="fl-nav-links">
                  
                  <ul class="links">
					          <li>My Account</li>
                     <?php if($is_login) { ?>
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
                            <!--<li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>-->
                            <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                            <li><a href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></li>

                     <?php }else {?>
                      <li><a href="#"  type="button" data-toggle="modal" data-target="#phoneModal"><i class="fa fa-sign-in"></i><?= $text_sign_in ?></a></li>
                      <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#signupModal-popup"><i class="fa fa-user-plus"></i><?= $text_register ?></a></li>
                      <!--<li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>-->
                      <li class="last"><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
          
            <div class="fl-cart-contain">
             <div class="header_item">
                <!--<div class="store-cart-action header-item">
                    <button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
                        <span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
                        <i class="fa fa-shopping-cart"></i> 
                        <span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
                    </button>
                </div>-->
            </div>
            <?php /* ?>
              <div class="mini-cart">
                <div class="basket"> <a href="shopping-cart.html"><span> 2 </span></a> </div>
                <div class="fl-mini-cart-content" style="display: none;">
                  <div class="block-subtitle">
                    <div class="top-subtotal">2 items, <span class="price">$259.99</span> </div>
                    <!--top-subtotal--> 
                    <!--pull-right--> 
                  </div>
                  <!--block-subtitle-->
                  <ul class="mini-products-list" id="cart-sidebar">
                    <li class="item first">
                      <div class="item-inner"><a class="product-image" title="timi &amp; leslie Sophia Diaper Bag, Lemon Yellow/Shadow White" href="#l"><img alt="timi &amp; leslie Sophia Diaper Bag, Lemon Yellow/Shadow White" src="products-images/p4.jpg"></a>
                        <div class="product-details">
                          <div class="access"><a class="btn-remove1" title="Remove This Item" href="#">Remove</a> <a class="btn-edit" title="Edit item" href="#"><i class="icon-pencil"></i><span class="hidden">Edit item</span></a> </div>
                          <!--access--> 
                          <strong>1</strong> x <span class="price">$179.99</span>
                          <p class="product-name"><a href="product-detail.html">Fresh Organic Mustard Leaves</a></p>
                        </div>
                      </div>
                    </li>
                    <li class="item last">
                      <div class="item-inner"><a class="product-image" title="JP Lizzy Satchel Designer Diaper Bag - Slate Citron" href="#"><img alt="JP Lizzy Satchel Designer Diaper Bag - Slate Citron" src="products-images/p3.jpg"></a>
                        <div class="product-details">
                          <div class="access"><a class="btn-remove1" title="Remove This Item" href="#">Remove</a> <a class="btn-edit" title="Edit item" href="#"><i class="icon-pencil"></i><span class="hidden">Edit item</span></a> </div>
                          <!--access--> 
                          <strong>1</strong> x <span class="price">$80.00</span>
                          <p class="product-name"><a href="product-detail.html">Fresh Organic Mustard Leaves</a></p>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <div class="actions">
                    <button class="btn-checkout" title="Checkout" type="button" onClick="window.location=checkout.html"><span>Checkout</span></button>
                  </div>
                  <!--actions--> 
                </div>
                <!--fl-mini-cart-content--> 
              </div>
               <?php */ ?>
            </div>
            <!--mini-cart-->
           
            <div class="collapse navbar-collapse">
              <form class="navbar-form active" role="search" onsubmit="location='<?= $this->url->link('product/search') ?>&search=' + $('input[name=\'product_name\']').val(); return false;">
                <div class="input-group">
                  <input type="text" name="product_name"  placeholder="Search for your product" />
                  <span class="input-group-btn">
                  <button type="submit" class="search-btn"> <span class="glyphicon glyphicon-search"> <span class="sr-only">Search</span> </span> </button>
                    <div class="resp-searchresult">
                                    <div></div>
                                </div>
                  </span> </div>
              </form>
            </div>
            <!--links--> 
          </div>
        </div>
      </div>
    </div>
  </header>
    <div class="checkout-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="container help-search">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div id='help_search_form' action="#">
                                    <div class="form-group">
                                        <input type="search" placeholder="<?= $text_how_can_help ?>" name="q" class="help_search" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            $(function(){
                $('.help_search').on('keydown', function(e){
                    if(e.keyCode === 13){
                        $q = $('.help_search').val();
                        location = '<?= $this->url->link('information/help/search') ?>&q='+$q;
                    }                        
                });
            });
        </script>
    </div>
