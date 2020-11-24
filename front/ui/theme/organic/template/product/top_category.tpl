<?php echo $header; ?>
<div class="store-cart-panel">
        <div class="modal right fade" id="store-cart-side" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="cart-panel-content">
                    </div>
                    <div class="modal-footer">
                        <!-- <p><?= $text_verify_number ?></p> -->
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

<!-- Organic Theme Code Start --->

<!--- Page Heading + breadcrumb Start --->
<div class="page-heading">
<?php /* ?>
  <div class="breadcrumbs">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <ul>
            <li class="home"> <a href="<?php echo $base; ?>" title="Go to Home Page">Home</a> <span>&rsaquo; </span> </li>
            <li class="category1601"> <strong>Vegetables</strong> </li>
          </ul>
        </div>
        <!--col-xs-12--> 
      </div>
      <!--row--> 
    </div>
    <!--container--> 
  </div>
  <?php */ ?>
  <div class="page-title">
    <!--<h2>Emlima Store</h2>-->
  </div>
</div>



<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated"> 
  <!-- For version 1, 2, 3, 8 --> 
  <!-- For version 1, 2, 3 -->
  
  <div class="container">
    <div class="row">
    <div class="breadcrumbs">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <ul>
            <li class="home"> <a href="<?php echo $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] 
     . explode('?', $_SERVER['REQUEST_URI'], 2)[0]; ?>" title="Go to Home Page">Home</a> <span>› </span> </li>
            <li class="category1601"> <strong>Emlima Store</strong> </li>
          </ul>
        </div>
        <!--col-xs-12--> 
      </div>
      <!--row--> 
    </div>
    <!--container--> 
  </div>
      <div class="col-main col-sm-9 col-sm-push-3 product-grid">
      <div class="pro-coloumn">
     
      
        <article>
          <div class="toolbar">
            <div class="sorter">
              <div class="view-mode"> <span title="Grid" class="button button-active button-grid">&nbsp;</span><!--<a href="list.html" title="List" class="button-list">&nbsp;</a>--></div>
            </div>
            <!--<div class="sort-by">
              <label class="left">Sort By: </label>
              <ul>
                <li><a href="#">Position<span class="right-arrow"></span></a>
                  <ul>
                    <li><a href="#">Name</a></li>
                    <li><a href="#">Price</a></li>
                    <li><a href="#">Position</a></li>
                  </ul>
                </li>
              </ul>
              <a class="button-asc left" href="#" title="Set Descending Direction"><span class="top_arrow"></span></a> </div>-->
            <!--<div class="pager">
              <div class="limiter">
                <label>View: </label>
                <ul>
                  <li><a href="#">15<span class="right-arrow"></span></a>
                    <ul>
                      <li><a href="#">20</a></li>
                      <li><a href="#">30</a></li>
                      <li><a href="#">35</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              
            </div>-->
          </div>
            <?php
                $products = array();
                 foreach($categories as $category){
                  $link_array = explode('/',$category['href']);
                   $page_link = end($link_array);
                   if(isset($_REQUEST['cat'])){
                     if(isset($category['products']) && ($_REQUEST['cat'] == $page_link)){
                        $products  = array_merge( $products,$category['products']);
                     }
                   }else{
                      $products  = array_merge( $products,$category['products']);
                   }
                 
                 ?>
               <?php } ?>
          <div class="category-products">
            <?php if(count($products)>0){?>
            <ul class="products-grid">
            <?php 
                foreach($products as $product) {
                //echo '<pre>';print_r($product);
              ?>
              <li class="item col-lg-4 col-md-3 col-sm-4 col-xs-6">
                              <div class="item-inner">
                              <div class="item-img">
                                <div class="item-img-info"><a href="<?=$product['href']?>" title="<?=$product['name']?>" class="product-image"><img src="<?=$product['thumb']?>" alt="<?=$product['name']?>"></a>
                                
                                  <div class="item-box-hover">
                                    <div class="box-inner product-block" data-id="<?= $product['product_store_id'] ?>">
                                      <div class="product-detail-bnt product-img product-description open-popup" data-id="<?= $product['product_store_id'] ?>" ><a  class="button detail-bnt"><span>Quick View</span></a></div>
                                      <!--<div class="actions"><span class="add-to-links"><a href="#" class="link-wishlist" title="Add to Wishlist"><span>Add to Wishlist</span></a> <a href="#" class="link-compare add_to_compare" title="Add to Compare"><span>Add to Compare</span></a></span> </div>-->
                                      
                                    </div>
                                  </div>
                                </div>
                                <div class="pro-qty-addbtn" data-variation-id="<?= $product['product_variation_store_id'] ?>" id="action_<?= $product['product_variation_store_id'] ?>">

                                   <?php require 'action.tpl'; ?>
                
                                 </div>
                                
                              <div class="item-info">
                                <div class="info-inner">
                                  <div class="item-title"><?=$product['name']?></div>
                                  <div class="item-content">
                                    <!--<div class="rating">
                                      <div class="ratings">
                                        <div class="rating-box">
                                          <div class="rating" style="width:80%"></div>
                                        </div>
                                        <p class="rating-links"><a href="#">1 Review(s)</a> <span class="separator">|</span> <a href="#">Add Review</a> </p>
                                      </div>
                                    </div>-->
                                    <div class="item-price">
                                      <div class="price-box"><span class="regular-price">Price : <span class="price"><?=$product['price']?></span> </span> </div>
                                      <?php if($product['special']){ ?>
                                      <div class="price-box"><span class="regular-price">Special Price:<span class="price"><?=$product['special']?></span> </span> </div>
                                      <?php } ?>
                                    </div>
									
                                  </div>
                                </div>
                              </div>
                            </div>
                      
              </li>
                <!--- Product Details Modal Start --->
                <div id="product_<?=$product['product_id']?>" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body class="col-lg-2 col-md-4 col-sm-6 col-xs-6 nopadding product-details" style="border-right: 1px solid rgb(215, 220, 214);">
                      <div>
                
            <?php /*echo "<pre>";print_r($product);die;*/ if(isset($product['percent_off']) && $product['percent_off'] != '0.00') { ?>

                <span class="spacial-offer"> <?php echo $product['percent_off'].'% OFF';?></span>
            <?php } ?>
                

            <?php if($this->customer->isLogged()) { ?>
            

            <a href="#" class="add-to-list list_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="list-btn" data-id="<?= $product['product_id'] ?>"
             type="button" data-toggle="modal" data-target="#listModal"  ><img class="add-list-png"   src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png">
             </a>

            <?php } else { ?>
                <a href="#"  class="add-to-list" type="button" data-toggle="modal" data-target="#phoneModal"><img class="add-list-png" src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png"></a>

            <?php } ?>
        </div>
                        <div class="product-block"  data-id="<?= $product['product_store_id'] ?>">

            <div class="product-img product-description open-popup" data-id="<?= $product['product_store_id'] ?>" data-id="<?= $product['product_store_id'] ?>">
                <img class="lazy" data-src="<?= $product['thumb'] ?>" alt="">
            </div>
            <div class="product-description" data-id="<?= $product['product_store_id'] ?>">
                

                <h3 class="open-popup" data-id="<?= $product['product_store_id'] ?>">

                    <a class="product-title"><?= $product['name']?></a>
                </h3>

                <?php if(trim($product['unit'])){ ?>
                    <p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span class="small-info"><?= $product['unit'] ?></span></p>
                <?php } else { ?>
                    <p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span class="small-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                <?php } ?>

                <div class="product-price">
                    <?php if ( $product['special'] == '0.00' || empty(trim($product['special']))) { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>" style="display: none";>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                            <?php echo $product['price']; ?>
                        </span>
                    <?php } else { ?>
                        <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>">
                            <?php echo $product['price']; ?>
                        </span>
                        <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                            <?php echo $product['special']; ?>
                        </span>
                    <?php } ?>
                    <div class="pro-qty-addbtn" data-variation-id="<?= $product['store_product_variation_id'] ?>" id="action_<?= $product['product_store_id'] ?>">
                        
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

                  </div>
                </div>
                <!--- Product Details Modal End --->
              <?php }?>
             
            </ul>
            <?php }else{ ?>
             <center> <h2> There are no products to list in this category. </h2></center>
            <?php }?>
          </div>
          <!--<div class="toolbar bottom">
              <div class="display-product-option">
                <div class="pages">
                  <label>Page:</label>
                  <ul class="pagination">
                    <li><a href="#">«</a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">»</a></li>
                  </ul>
                </div>
                <div class="product-option-right">
                  <div class="sort-by">
                    <label class="left">Sort By: </label>
                    <ul>
                      <li><a href="#">Position<span class="right-arrow"></span></a>
                        <ul>
                          <li><a href="#">Name</a></li>
                          <li><a href="#">Price</a></li>
                          <li><a href="#">Position</a></li>
                        </ul>
                      </li>
                    </ul>
                    <a class="button-asc left" href="#" title="Set Descending Direction"><span class="top_arrow"></span></a> </div>
                  <div class="pager">
                    <div class="limiter">
                      <label>View: </label>
                      <ul>
                        <li><a href="#">15<span class="right-arrow"></span></a>
                          <ul>
                            <li><a href="#">20</a></li>
                            <li><a href="#">30</a></li>
                            <li><a href="#">35</a></li>
                          </ul>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>-->
        </article>
        </div>
        <!--	///*///======    End article  ========= //*/// --> 
      </div>
      <aside class="col-left sidebar col-sm-3 col-xs-12 col-sm-pull-9 wow bounceInUp animated"> 
        <!-- BEGIN SIDE-NAV-CATEGORY -->
        <div class="side-nav-categories">
          <div class="block-title"> Categories </div>
          <!--block-title--> 
          <!-- BEGIN BOX-CATEGORY -->
          
          <div class="box-content box-category">
            <ul>
               
               <?php
                $products = array();
                 foreach($categories as $category){
                   $link_array = explode('/',$category['href']);
                   $page_link = end($link_array);
                   
                 
                 ?>
                  <li> <a href="<?='?cat='.$page_link?>"><?=$category['name']?></a> </li>
               <?php } ?>
              <?php  //echo '<pre>';print_r($products);exit;?>
              <!--level 0-->     
              
            </ul>
          </div>
          <!--box-content box-category--> 
        </div>
        <!--side-nav-categories-->
        <!--<div class="block block-layered-nav">
          <div class="block-title"> Shop By </div>
          <div class="block-content">
              <p class="block-subtitle">Shopping Options</p>
              <dl id="narrow-by-list">
                <dt class="odd">Price</dt>
                <dd class="odd">
                  <ol>
                    <li> <a href="#"><span class="price">$0.00</span> - <span class="price">$99.99</span></a> (6) </li>
                    <li> <a href="#"><span class="price">$100.00</span> and above</a> (6) </li>
                  </ol>
                </dd>
                <dt class="even">Manufacturer</dt>
                <dd class="even">
                  <ol>
                    <li> <a href="#">TheBrand</a> (9) </li>
                    <li> <a href="#">Company</a> (4) </li>
                    <li> <a href="#">LogoFashion</a> (1) </li>
                  </ol>
                </dd>
                <dt class="odd">Color</dt>
                <dd class="odd">
                  <ol>
                    <li> <a href="#">Green</a> (1) </li>
                    <li> <a href="#">White</a> (5) </li>
                    <li> <a href="#">Black</a> (5) </li>
                    <li> <a href="#">Gray</a> (4) </li>
                    <li> <a href="#">Dark Gray</a> (3) </li>
                </ol>
                </dd>
                <dt class="last even">Size</dt>
                <dd class="last even">
                  <ol>
                    <li> <a href="#">Small</a> (6) </li>
                    <li> <a href="#">Medium</a> (6) </li>
                    <li> <a href="#">Large</a> (4) </li>
                  </ol>
                </dd>
              </dl>
            </div>
        </div>-->
        <div class="custom-slider">
          <div>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li class="active" data-target="#carousel-example-generic" data-slide-to="0"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
              </ol>
              <div class="carousel-inner">
                <div class="item active"><img src="../front/ui/theme/organic/images/slide2.jpg" alt="slide3">
                  <div class="carousel-caption">
                  <h4>Fruit Shop</h4>
                    <h3><a title=" Sample Product" href="product-detail.html">Up to 70% Off</a></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <a class="link" href="#">Buy Now</a></div>
                </div>
                <div class="item"><img src="../front/ui/theme/organic/images/slide3.jpg" alt="slide1">
                  <div class="carousel-caption">
                   <h4>Black Grapes</h4>
                    <h3><a title=" Sample Product" href="product-detail.html">Mega Sale</a></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                     <a class="link" href="#">Buy Now</a>
                  </div>
                </div>
                <div class="item"><img src="../front/ui/theme/organic/images/slide1.jpg" alt="slide2">
                  <div class="carousel-caption">
                  <h4>Food Farm</h4>
                    <h3><a title=" Sample Product" href="product-detail.html">Up to 50% Off</a></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                     <a class="link" href="#">Buy Now</a>
                  </div>
                </div>
              </div>
              <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"> <span class="sr-only">Next</span> </a></div>
          </div>
        </div>
       
       <?php /* ?>
       <div class="block block-list block-cart">
          <div class="block-title"> My Cart </div>
          <div class="block-content">
            <div class="summary">
              <p class="amount">There is <a href="#">1 item</a> in your cart.</p>
              <p class="subtotal"> <span class="label">Cart Subtotal:</span> <span class="price">$299.00</span> </p>
            </div>
            <div class="ajax-checkout">
              <button type="button" title="Checkout" class="button button-checkout" onClick="#"> <span>Checkout</span> </button>
            </div>
            <p class="block-subtitle">Recently added item(s)</p>
            <ul id="cart-sidebar1" class="mini-products-list">
              <li class="item">
                <div class="item-inner"> <a href="#" class="product-image"><img src="../front/ui/theme/organic/products-images/p1.jpg" width="80" alt="product"></a>
                  <div class="product-details">
                    <div class="access"> <a href="#" class="btn-remove1">Remove</a> 
                    <a href="#" title="Edit item" class="btn-edit">
                    <i class="icon-pencil"></i><span class="hidden">Edit item</span></a> </div>
                    <!--access--> 
                    
                    <strong>1</strong> x <span class="price">$299.00</span>
                    <p class="product-name"><a href="#">Fresh Organic Mustard Leaves </a></p>
                  </div>
                  <!--product-details-bottoms--> 
                </div>
              </li>
              <li class="item  last1">
                <div class="item-inner"> <a href="#" class="product-image"><img src="../front/ui/theme/organic/products-images/p2.jpg" width="80" alt="product"></a>
                  <div class="product-details">
                    <div class="access"> <a href="#" class="btn-remove1">Remove</a> 
                    <a href="#" title="Edit item" class="btn-edit">
                    <i class="icon-pencil"></i><span class="hidden">Edit item</span></a> </div>
                    <!--access--> 
                    
                    <strong>1</strong> x <span class="price">$299.00</span>
                    <p class="product-name"><a href="#">Fresh Organic Mustard Leaves </a></p>
                  </div>
                  <!--product-details-bottoms--> 
                </div>
              </li>
            </ul>
          </div>
        </div>
    <?php */ ?>    
       
       <!--<div class="block block-compare">
          <div class="block-title"> Compare Products </div>
         <div class="block-content">
            <ol id="compare-items">
                    <li class="item odd">
                   <a href="#" class="btn-remove1" onClick="#"></a>
                <a class="product-name" href="#">Fresh Organic Mustard Leaves </a>            </li>
             <li class="item odd">
                   <a href="#" class="btn-remove1" onClick="#"></a>
                <a class="product-name" href="#">Fresh Organic Mustard Leaves </a>            </li>
             <li class="item odd">
                   <a href="#" class="btn-remove1" onClick="#"></a>
                <a class="product-name" href="#">Fresh Organic Mustard Leaves </a>            </li>
             <li class="item odd">
                   <a href="#" class="btn-remove1" onClick="#"></a>
                <a class="product-name" href="#">Fresh Organic Mustard Leaves </a>            </li>
              </ol>
       
        <div class="ajax-checkout">
            <button type="button" title="Compare" class="button button-compare" onClick="#"><span>Compare</span></button>
          
        </div><!--ajax-checkout-->
        </div>
          <!--block-content--> 
        </div>
        <!--block block-list block-compare--> 
     
      </aside>
      <!--col-right sidebar--> 
    </div>
    <!--row--> 
  </div>
  <!--container--> 
</section>
<!--main-container col2-left-layout--> 

<!--- Page Heading + breadcrumb End --->





<!-- Organic Theme Code End --->

<!--<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">

    <?php if(isset($offer_show) && $offer_show && count($offer_products['products']) > 0 ) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="product-category-block">
                    <h2><?= $text_offer ?><a href=""> <a style="color: #d60343;margin-left: 7px;font-size: 14px;letter-spacing: 0;text-decoration: underline;" href="<?= $offer_href ?>" id="view-all"><?= $text_view ?></a></h2>
                </div>
            </div>
        </div>
         <div class="row">
             <div class="store-list-wrapper" style="display:block;">
                <?php $products = $offer_products['products'] ?>
                <?php /* require(DIR_BASE.'../../front/ui/theme/mvgv2/template/product/product_collection.php'); */ ?>
            </div>
        </div>
    <?php } ?>

    <?php foreach($categories as $category){ ?>

            <?php if($category['products']){ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="product-category-block">
                            <h2><?= $category['name'] ?><a href=""> <a style="color: #d60343;margin-left: 7px;font-size: 14px;letter-spacing: 0;text-decoration: underline;" href="<?= $category['href'] ?>" id="view-all"><?= $text_view ?></a></h2>
                        </div>
                    </div>
                </div>
                 <div class="row">
                     <div class="store-list-wrapper" style="display:block;">
                        <?php $products = $category['products'] ?>
                        <?php // require(DIR_BASE.'../../front/ui/theme/mvgv2/template/product/product_collection.php'); ?>
                    </div>
                </div>
        <?php } ?>
    <?php } ?>
</div>-->

        
        </div>
    </div>
</div>

<div class="modal-wrapper"></div> 

    
    <div style="padding-bottom:30px;"></div>
    
    <?php echo $footer; ?> 
    <?= $login_modal ?>
    <?= $signup_modal ?>
    <?= $forget_modal ?>

<div class="listModal-popup">
    <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="uncheckAll()"><span aria-hidden="true">&times;</span></button>
                    <div class="store-find-block">
                        <div class="mydivsssx">
                            <div class="store-find">
                                <div class="store-head">
                                    <h1><?= $text_add_to_list ?></h1>
                                </div>

                                <div id="list-message-success" style="color: green;">
                                </div>

                                <div id="list-message-error" style="color: red;">
                                </div>

                               
                                <form id="add-in-list" action="" method="post" enctype="multipart/form-data">

                                    <table class="table table-striped">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center;"><?= $text_list_name ?></th>
                                            <th style="text-align: center;"><?= $text_add_to ?> </th>
                                          </tr>
                                        </thead>
                                        <tbody id="users-list">
                                            <?php foreach ($lists as $list) { ?>
                                              <tr>
                                                <td><?= $list['name'] ?></td>
                                                <td class=""> <input type="checkbox" class="" name="add_to_list[]" value="<?= $list['wishlist_id'] ?>"></td>
                                              </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <input type="hidden" name="listproductId" class="listproductId" value=""/>

                                    <button id="add-in-list-button" type="button" name="next" class="btn btn-default btn-lg">
                                        <span class="add-in-list-modal-text"><?= $text_confirm ?> </span>
                                        <div class="add-in-list-loader" style="display: none;"></div>
                                    </button>
                                </form>

                               
                                <p class="seperator"><?= $text_or ?> </p>
                               
                                  
                                <div class="social-login-section">
                                    <form id="list-create-form" action="" method="post" enctype="multipart/form-data" class="form">
                                        
                                        <input type="hidden" name="listproductId" class="listproductId" value=""/>
                                        <div class="row">
                                            <div class="col-sm-9 form-group required">
                                                <input id="list-name" name="name" type="text" placeholder="<?= $text_enter_list_name ?>" class="form-control input-bg" required>
                                            </div>
                                            
                                            
                                            <div class="col-sm-3 form-group">
                                                <button id="list-create-button" type="button" name="next" class="btn btn-default btn-lg">
                                                        <span class="list-create-modal-text"><?= $text_create_list ?> </span>
                                                        <div class="list-create-loader" style="display: none;"></div>
                                                </button>
                                            </div> 
                                            
                                        </div>
                                        
                                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="changelocationModal">
    <div class="modal fade" id="useraddress-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <div class="exclamation-icon"><i class="fa fa-exclamation-circle fa-4x"></i></div>
                 <div class="changelocationModal-content">
                    <h2><?= $text_change_locality ?></h2>
                    <?php if($this->config->get('config_multi_store')) { ?>
                        <p><?= $text_only_on_change_locality_warning ?></p>
                    <?php } else { ?>
                        <p><?= $text_change_locality_warning ?></p>
                    <?php } ?> 

                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                        <b> <?= $text_change_location_name ?> : <?= $zipcode ?></b>
                    <?php } else { ?>
                        <b><?= $text_change_location_name ?> : <?= $location_name_full ?></b>
                        
                    <?php } ?>
                    
                </div>
                <a href="<?php echo $toHome ?>" class="btn btn-primary"><?= $button_change_locality ?></a>
                <a href="<?php echo $toStore ?>" class="btn btn-default"><?= 
                $button_change_store ?></a>
                
            </div>
        </div>
    </div>
</div>
</div>

<!-- Start store banner -->

<!--<div class="bannerModal_popup">
    <div class="modal fade" id="bannermodal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close close-model" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <center><img src="<?php echo $banner_logo; ?>" alt="" class="img-responsive"></center>
                    
                </div>
            </div>
        </div>
    </div>
</div>-->

<script type="text/javascript">
    
    $(document).delegate('.close-model', 'click', function(){
        console.log("close product block");
            $('#bannermodal').modal('hide');
            $('.modal-backdrop').remove();
    });
</script>

<!-- End store banner -->


    <script type="text/javascript" src="<?= $base ?>front/ui/theme/organic/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/slider-carousel.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.plugins.min.js"></script>

    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>


    <script type="text/javascript">

   

    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    // jQuery(function($){
    //     console.log("signup mask");
    //    $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    // });
    /*jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/
    
    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    $(function() {
        console.log("lazy f");
        $('img.lazy').Lazy({
                beforeLoad: function(element) {
                    // called before an elements gets handled
                    console.log("lazy");
                },
                effect: 'fadeIn',
                effectTime : 500,
                visibleOnly: true,
            }
        );
    });

    $(document).ready(function() {

        
        
        if(<?php  echo isset( $show_banner) ? 'true' : 'false' ?>) {

            $('#bannermodal').modal('show');    
        }

        $('[data-toggle="offcanvas"]').click(function() {
            $('.row-offcanvas').toggleClass('active')
        });

        $('[data-toggle="tooltip"]').tooltip(); 
    });

    $('.header-search-form').on('click', function() {
        $('body').toggleClass('overflow-y-hidden');
    });
    $('.header-search-form').on('click', function() {
        $('.overlay-body').toggleClass('backdrop');
    });
    
    $(".ddd").on("click", function() {
        var $button = $(this);
        var $input = $button.closest('.sp-quantity').find("input.quntity-input");

        $input.val(function(i, value) {
            return +value + (1 * +$button.data('multi'));
        });
    });

    $(document).delegate('#clearcart', 'click', function(){
        var choice = confirm($(this).attr('data-confirm'));

        if(choice) {

            $.ajax({
                url: 'index.php?path=checkout/cart/clear_cart',
                type: 'post',
                data:'',
                dataType: 'json',
                success: function(json) {
                if (json['location']) {
                    location = json.redirect;
                    location = location;
                }}
            });
        }
    });
    </script>

    <?php if ($not_delivery): ?>

    <div id="notallowed-others" class="modal fade " aria-hidden="true">
        <div class="modal-dialog  modal-sm">
            <div class="modal-content" style="height:150px">
                <div class="modal-header">
                    <h4 class="modal-title"><?= $error_no_delivery ?></h4>
                </div>
                <div class="modal-body text-center">
                    <a href="#" id="clearcart" class="btn btn-danger btn-lg"><?= $button_clear_cart ?></a>
                    <a href="index.php?path=checkout/checkout" class="btn btn-success btn-lg"><?= $button_checkout ?></a>
                </div><!-- END .modal-body -->
            </div><!-- END .modal-content -->
        </div><!-- END .modal-dialog -->
    </div>
    <script type="text/javascript">
     $('#notallowed-others').modal({backdrop: 'static', keyboard: false}); 
     
    </script>
    <?php endif ?>


    

    <script type="text/javascript">

  $(document).ready(function () {
    $(document).delegate('.open-popup', 'click', function () {
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


    if(window.screen.availWidth < 450 || window.screen.availHeight < 732) {
        $("#sidebarss").trigger("sticky_kit:detach");
    } else {
        $("#sidebarss").stick_in_parent();
    }

    $('.add-to-list').on('click', function (e) {
        
        console.log("erg");
        data = {
            product_id : $(this).data("id")
        }

        $.ajax({
            url: 'index.php?path=account/wishlist/getProductWislists',
            type: 'post',
            data:data,
            dataType: 'json',
            success: function(json) {
                if (json['status']) {

                    console.log(json);
                    $('#users-list').html(json['html']);
                }
            }
        });
    });

        
</script>
    <style>
        .cat-list > li:hover  .drop-menu-2  {
          display: block;
        }
        .drop-menu-2 li:hover .drop-menu-3{
            display: block;
        }
    </style>
</body>

</html>
