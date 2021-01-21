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

  <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://use.fontawesome.com/releases/v5.8.1/css/all.css' rel='stylesheet'>
  <link rel="stylesheet" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style-new.css">
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
  <script type='text/javascript'
    src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js'></script>
  <script src="<?= $base;?>front/ui/theme/metaorganic/assets/js/scripts.js"></script>
  <script src="<?= $base;?>front/ui/theme/metaorganic/assets/js/cart.js"></script>
</head>


<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
  <div class="super_container">
    <!-- Header -->
    <header class="header">
      <!-- Top Bar -->
      <div class="top_bar">
        <div class="container">
          <div class="row">
            <div class="col d-flex flex-row">
              <div class="top_bar_contact_item">
                <div class="top_bar_icon"><img
                    src="<?= $base;?>front/ui/theme/metaorganic/assets/images/icons/phone.svg" alt="Phone Icon"></div>
                +254 738 770 186
              </div>
              <div class="top_bar_contact_item">
                <div class="top_bar_icon"><img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/icons/mail.svg"
                    alt="Mail Icon"></div><a href="mailto:hello@kwikbasket.com">hello@kwikbasket.com</a>
              </div>
              <div class="top_bar_content ml-auto">
                <div class="top_bar_user">
                  <div class="user_icon"><img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/icons/user.svg"
                      alt="User icon"></div>
                  <div>Hi,
                    <?= $full_name ?>
                  </div>
                  <div><a href="<?= $logout ?>">Sign Out</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- Header Main -->
      <div class="header_main">
        <div class="container">
          <div class="row">

            <!-- Logo -->
            <div class="col-lg-2 col-sm-3 col-3 order-1">
              <div class="logo_container">
                <div class="logo">
                  <a href="#">
                    <img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/logo.svg" alt="KwikBasket Logo">
                  </a>
                </div>
              </div>
            </div>

            <!-- Search -->
            <div class="col-lg-6 col-12 order-lg-2 order-3 text-lg-left text-right">
              <div class="header_search">
                <div class="header_search_content">
                  <div class="header_search_form_container">
                    <form action="#" class="header_search_form clearfix"> <input type="search" required="required"
                        class="header_search_input" placeholder="Search for products...">
                      <div class="custom_dropdown" style="display: none;">
                        <div class="custom_dropdown_list"> <span class="custom_dropdown_placeholder clc">All
                            Categories</span> <i class="fas fa-chevron-down"></i>
                          <ul class="custom_list clc">
                            <li><a class="clc" href="#">All Categories</a></li>
                            <li><a class="clc" href="#">Computers</a></li>
                            <li><a class="clc" href="#">Laptops</a></li>
                            <li><a class="clc" href="#">Cameras</a></li>
                            <li><a class="clc" href="#">Hardware</a></li>
                            <li><a class="clc" href="#">Smartphones</a></li>
                          </ul>
                        </div>
                      </div> <button type="submit" class="header_search_button trans_300" value="Submit"><img
                          src="https://res.cloudinary.com/dxfq3iotg/image/upload/v1560918770/search.png"
                          alt=""></button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Wishlist -->
            <div class="col-lg-4 col-9 order-lg-3 order-2 text-lg-left text-right">
              <div class="wishlist_cart d-flex flex-row align-items-center justify-content-end">
                <div class="wishlist d-flex flex-row align-items-center justify-content-end">
                  <div class="wishlist_icon"><img
                      src="<?= $base;?>front/ui/theme/metaorganic/assets/images/icons/heart.png" alt=""></div>
                  <div class="wishlist_content">
                    <div class="wishlist_text"><a href="<?= $wishlist ?>">Wishlists</a></div>
                    <div class="wishlist_count">10</div>
                  </div>
                </div>

                <!-- Cart -->
                <div class="cart mini-cart-button" data-toggle="modal" data-target="#mini-cart-panel">
                  <div class="cart_container d-flex flex-row align-items-center justify-content-end">
                    <div class="cart_icon"> <img
                        src="<?= $base;?>front/ui/theme/metaorganic/assets/images/icons/cart.png" alt="">
                      <div class="cart_count"><span class="cart_items_count">
                          <?= $this->cart->countProducts(); ?>
                        </span></div>
                    </div>
                    <div class="cart_content">
                      <div class="cart_text"><a>Basket</a></div>
                      <div class="cart_price">
                        <?= $this->currency->format($this->cart->getTotal()); ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Navigation -->
      <nav class="main_nav">
        <div class="container">
          <div class="row">
            <div class="col">
              <div class="main_nav_content d-flex flex-row">
                <!-- Categories Menu -->
                <!-- Main Nav Menu -->
                <div class="main_nav_menu">
                  <ul class="standard_dropdown main_nav_dropdown">
                    <li><a href="<?= $dashboard ?>">Dashboard</a></li>
                    <li>
                      <a href="<?= $po_ocr ?>">
                        Purchase Order <span class="badge badge-pill badge-success">BETA</span>
                      </a>
                    </li>
                    <li class="hassubs"> <a href="#">My Account<i class="fas fa-chevron-down"></i></a>
                      <ul>
                        <li><a href="<?= $account ?>">Profile<i class="fas fa-chevron-down"></i></a></li>
                        <li><a href="<?= $account ?>">Order History<i class="fas fa-chevron-down"></i></a>
                        </li>
                        <li><a href="<?= $account ?>">Transactions<i class="fas fa-chevron-down"></i></a></li>
                        <li><a href="<?= $account ?>">Settings<i class="fas fa-chevron-down"></i></a></li>
                        <li><a href="<?= $logout ?>">Sign Out<i class="fas fa-chevron-down"></i></a></li>
                      </ul>
                    </li>
                  </ul>
                </div>

                <!-- Menu Trigger -->
                <div class="menu_trigger_container ml-auto">
                  <div class="menu_trigger d-flex flex-row align-items-center justify-content-end">
                    <div class="menu_burger">
                      <div class="menu_trigger_text">menu</div>
                      <div class="cat_burger menu_burger_inner">
                        <span></span><span></span><span></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Menu -->
      <div class="page_menu">
        <div class="container">
          <div class="row">
            <div class="col">
              <div class="page_menu_content">
                <div class="page_menu_search">
                  <form action="#"> <input type="search" required="required" class="page_menu_search_input"
                      placeholder="Search for products..."> </form>
                </div>
                <ul class="page_menu_nav">
                  <li class="page_menu_item"> <a href="#">Dashboard<i class="fa fa-angle-down"></i></a>
                  </li>
                  <li class="page_menu_item"> <a href="#">Purchase Order <span
                        class="badge badge-pill badge-success">BETA</span></i></a>
                  </li>
                  <li class="page_menu_item has-children"> <a href="<?= $account ?>">My Account<i
                        class="fa fa-angle-down"></i></a>
                    <ul class="page_menu_selection">
                      <li><a href="<?= $account ?>">Profile<i class="fa fa-angle-down"></i></a></li>
                      <li><a href="<?= $account ?>">Transactions<i class="fa fa-angle-down"></i></a></li>
                      <li><a href="<?= $account ?>">Settings<i class="fa fa-angle-down"></i></a></li>
                    </ul>
                  </li>
                  <li class="page_menu_item"><a href="<?= $logout ?>">Sign Out<i class="fa fa-angle-down"></i></a>
                  </li>
                </ul>
                <div class="menu_contact">
                  <div class="menu_contact_item">
                    +254 738 770 186
                  </div>
                  <div class="menu_contact_item">
                    <a href="mailto:hello@kwikbasket.com">hello@kwikbasket.com</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="container products-grid">
      <?php foreach($categories as $category) { ?>
      <?php if(count($category['products']) > 0) { ?>
      <h3 class="category-title">
        <span>
          <?=$category['name']?>
        </span>
      </h3>
      <div class="row">
        <?php foreach($category['products'] as $product) { ?>
        <div class="col-md-2 products-grid-item" data-store="<?= $product['store_id'] ?>"
          data-id="<?= $product['product_store_id'] ?>">

          <img class="product-image" src="<?=$product['thumb']?>" alt="<?=$product['name']?>">
          <p class="product-name">
            <?= $product['name'] ?>
          </p>
          <p class="product-price">
            <?= $product['variations'][0]['special_price'] ?>
            <?php  echo '/ Per ' . $product['variations'][0]['unit']; ?>
          </p>

          <div id="<?= $product['product_id'] ?>-product-quantity" class="product-quantity-in-basket"
            style="display: <?= $product['qty_in_cart'] ? 'block' : 'none'; ?>">
            <?= $product['qty_in_cart'] ?>
          </div>
        </div>

        <?php } ?>
      </div>
      <?php } ?>
      <?php } ?>
    </div>

    <div class="product-popup-wrapper"></div>

    <div id="mini-cart-panel" class="modal fixed-left fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Basket</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mini-cart-content"></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-cta">Checkout</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function () {

      $(document).delegate('.products-grid-item', 'click', function () {
        $('.products-grid-item').prop('disabled', true);
        $.get('index.php?path=product/product/view&product_store_id=' + $(this).attr('data-id') + '&store_id=' + $(this).attr('data-store'), function (data) {
          $('.products-grid-item').prop('disabled', false);
          $('.product-popup-wrapper').html(data);
          $('#product-details-popup').modal('show');
        });
        $('#product_name').val('');
      });

      $(document).delegate('.mini-cart-button', 'click', function () {
        $('.mini-cart-content').load('index.php?path=common/cart/newInfo');
      });
    });
  </script>

</body>

</html>