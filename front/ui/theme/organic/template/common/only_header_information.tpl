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
    <!-- Bootstrap -->
    
    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.css?v=5.1">
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/abhishek.css"> -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/mycart.css">
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
    <script src="<?= $base;?>front/ui/theme/mvgv2/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
    <script type="text/javascript" src="https://js.iugu.com/v2"></script>
    <link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?= $base ?>front/ui/theme/organic/stylesheet/responsive.css" media="all">
    <script src="<?= $base;?>front/ui/javascript/easyzoom.js"></script>
</head>

<body>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><center><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></center>
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
        

    <div id="header">
      <div class="container">
        <div class="header-container row">
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

  <script>
  $("span.glyphicon.glyphicon-search").click(function(){
    var currentpath  = window.location.href;
    var search_text =  $("input[name=store_search]").val();
    if(search_text == ''){
      alert('Please enter text to search');
    }else{
      window.location.href = currentpath+'&filter='+search_text;
    }
    
   });

    $("#mini-cart-button").click(function(){
      $("#toTop").show();
     $("#toTop").css('opacity','1.0');
    });
  </script>
