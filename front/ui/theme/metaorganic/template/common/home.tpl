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
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <title><?= $heading_title ?></title>
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    
     <link href="<?= $base;?>front/ui/theme/metaorganic/assets/images/favicon.ico" rel="icon">
    <!-- BEGIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets/css/style.css">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/all.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/fontawesome.css" rel="stylesheet">
    <link href="<?= $base;?>front/ui/theme/metaorganic/assets/css/brands.css" rel="stylesheet">
      <!-- END CSS -->   
    
    <!-- Bootstrap -->
    <link href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.css?v=5.2.7">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/abhishek.css?v=2.2.3">

    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/drawer.min.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.20.4/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/css/drawer.min.css">


    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/drawer.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/css/owl.theme.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.min.css">
    <style>
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
<?php //echo $page;//exit;?>
<body  data-wrapper-optimized="" id="homenew"  class="new-homepage-image-format drawer drawer--top">
      <?php //echo '<pre>';print_r($data);exit; ?>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><center><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></center>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>

       <div data-sticky-banner="{enable: false, enableOnDesktop:false, enableOnTablet:false, enableOnMobile:false, staticStartDistance:130}" id="leaderboard-header-banner" class="header__banner-container header__banner-container--hidden">
         <div class="header__banner-container-close"></div>
      </div>
      <div id="header-new" class="header">
       
         <div class="header__wrapper">
         <div class="header__search-bar-wrapper">
                  <div id="search-form-wrapper" class="header__search-bar search-form-wrapper">
                     <div class="header__search-title">
                        Search
                        <div class="header__mobile-search-close j-mobile-close-search-trigger"></div>
                     </div>
                     
                     <form id="search-form-form" class="search-form c-position-relative search-form--switch-category-position" action="#" method="get">
                        <ul class="header__search-bar-list header__search-bar-item--before-keyword-field">
                           
                           <li class="header__search-bar-item header__search-bar-item--category search-category-container">
                           <div class="form-group">
                              <select class="form-control" id="selectedCategory">
                                 <option>- Select categories-</option>
                                  <?php foreach($categories as $categoty){
                                     //print_r($categoty);exit;?>
                                 <option value="<?=$categoty['id']?>"><?=$categoty['name']?></option>
                                  <?php } ?>
                                
                              </select>
                           </div>
                           </li>
                           <li class="header__search-bar-item header__search-bar-item--location search-location-all">
                              <div class="header__search-location search-location">
                                    <i class="fa fa-map-marker header__search-location-icon" aria-hidden="true"></i>
                                 
                                 <!-- SuggestionWidget  start -->
                                 <div id="search-area-wrp" class="c-sggstnbx header__search-input-wrapper">
                                  
                                   
                                      <?php if($this->config->get('config_store_location') == 'autosuggestion') { ?>
                                              <input name="zipcode" id="searchTextField"  class="header__search-input zipcode-enter" type="text"  required="" alt=""  maxlength="" size="" tabindex="3" placeholder="Find Stores in your Location" highlight="y" strict="y" autocomplete="off">
                                            <?php } else { ?>
                                                <input name="zipcode" id="searchTextField"  class="header__search-input zipcode-enter" type="text"  required="" alt=""  maxlength="" size="" tabindex="3" placeholder="<?= $zipcode_mask ?>" highlight="y" strict="y" autocomplete="off">

                                            <?php } ?>

                                            

                                            <input type="hidden" name="store_list_url" value="<?=BASE_URL ?>">

                                            <input type="hidden" id="store_location" value="<?= $this->config->get('config_store_location'); ?>">
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



            <div class="header__top-layer">
               <div class="header__navigation-container" role="navigation">
               
                  <div class="header__primary-navigation-outer-wrapper">
                     
                     <div class="header__primary-navigation-item header__primary-navigation-item--more-categories" style="margin-left: 0px; margin-right:-13px">
                        
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
                        <li class="header__upper-deck-item header__upper-deck-item setcartbtn"><div class="butn setui"> <a href="<?= BASE_URL?>/checkout"><button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></a></div></li>
                        <?php }else{?>
                         <div>
                         <div class="menuset">
                             <!-- <a class="header__upper-deck-item-link" href="<?= $account ?>" > <span class="user-profile-img">Profile</span></a>-->
                            
                             <div class="newset"><a class="btn" href="<?= $account ?>" > <span ><?= $full_name ?></span> </a>     
                           
                           <div class="dropdownset" style="display:none; margin-top:3px;">
                                  <div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link" href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></div>
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
                                     <div class="butn setui"> <a href="<?= BASE_URL?>/checkout"><button class="btn btn-default mini-cart-button" role="button" data-toggle="modal" data-target="#store-cart-side" id="mini-cart-button" style="margin-right:10px; margin-top:0px">
										<span class="badge cart-count"><?= $this->cart->countProducts(); ?></span>
										<i class="fa fa-shopping-cart"></i> 
										<span class="hidden-xs hidden-sm cart-total-amount"><?= $this->currency->format($this->cart->getTotal()); ?></span>
						</button></a></div>
                                    </div>
                                    </div>
                       <?php } ?>
                     </ul>
                  </div>
                  
               </div>
              
               
               
            </div>
            <div id="homepage-billboard-new" class="header__home-hero header__home-hero--homenew">
               <div class="header__home-hero-image-wrapper">
                  <picture id="random-hero-image" class="header__home-hero-image" alt="">
                     <!--[if IE 9]>
                    
                     <![endif]-->
                     <!--<img id="random-hero-img" class="header__home-hero-image" alt="" src="assets/images/xxlarge.jpg">-->
                  </picture>
               </div>
               
               <div class="header__home-hero-placement-close"></div>
                <div class="header__logo-container test">
                     <a class="header__logo-link " href="<?= BASE_URL?>">
                        <img src="<?=$logo?>" />
                       
                     </a>
                     <div itemscope="" class="seo-visible">
                        <a itemprop="url" href="#">Home</a>
                        <img itemprop="logo" src="<?=$logo?>" width="100" height="100">
                     </div>
                  </div>
           
            </div>
         </div>
      </div>
       </div>
       <div style="clear:both !important"> </div>
         <?php if(!$page || ($page !='stores')){ ?>
         <div class="container--full-width featured-categories">
                <div class="container">
                   <div class="clearfix featured-categories__header">
                      <h2 class="featured-categories__header-title"><span>Shop By Categories</span></h2>
                      
                   </div>
                   <div class="featured-categories__scroller">
                      <div class="clearfix featured-categories__items owl-carousel owl-theme">
                      <?php foreach($categories as $categoty){
                           $link_array = explode('/',$categoty['href']);
                           $page_link = end($link_array);
                          ?>
                         <div class="featured-categories__item">
                          <!--<a href="<?=$this->url->link('product/store', 'store_id=2').'?cat='.$page_link?>"  class="featured-categories__item-link">-->
                          <a href="<?=$base?>?page=stores"  class="featured-categories__item-link">
                               <div class="featured-categories__item-description featured-categories__item-description--cars">
                                  <h4 class="featured-categories__item-description-title"><?=$categoty['name']?></h4>
                                  <!--<p class="featured-categories__item-description-total-ads">122,700&nbsp;ads</p>-->
                               </div>
                              <img src="<?=$categoty['thumb']?>" alt="<?=$categoty['name']?>">
                            </a>
                         </div>
                         <?php } ?>
                         
                      </div>
                   </div>
                </div>
             </div>
        <?php } ?>
         <div id="homepage-gallery" class="homepage-gallery c-clearfix items-per-page-11 can-fit-4-blocks">
            <div class="tabbed js-tabbed-module c-clearfix">
               <div class="tabbed__tabs-container" data-tabbed-role="controls">
                  <h1 class="h1set">Stores</h1>
                  <?php if(!$stores){?>
                     <h2>No Stores Found, Try with other stores</h2>'
                  <?php } ?>
               </div>
               
               <div class="tabbed__content-container">
                  <div class="tabbed__tab-content homepage-gallery__tab-gallery tabbed__active-content" data-tabbed-role="tab" data-tab-content-name="Diamond Plaza" style="">
                     
                     <div class="tabbed__tab-items c-clearfix">
                     
                        <?php foreach($stores as $store){
                         
                        ?>
                        <a href="<?= $base?>store/<?= $store['href'];?>">
                        <div class="homepage-gallery__block gallery-listing js-click-block" data-analytics="{&quot;category&quot;:&quot;USER_ENGAGEMENT&quot;,&quot;action&quot;:&quot;goto_Mydhukha_gallery&quot;,&quot;label&quot;:&quot;NEW_HP&quot;}">
                           <div class="gallery-listing__thumb-container">
                              <a href="<?= $base?>store/<?= $store['href'];?>" class="gallery-listing__thumb-link">
                              <img src="<?= $store['thumb'];?>" class="gallery-listing__thumb">
                              </a>
                           </div>
                           <div class="gallery-listing__details">
                              <h3 class="gallery-listing__title pr0 text-center">
                                 <a href="<?= $base?>store/<?= $store['href'];?>"><span><?= $store['name'];?></span></a>
                              </h3>
                              
                              <a href="<?= $base?>store/<?= $store['href'];?>" class="gallery-listing__extras-link">
                                 <div class="col-md-12 col-sm-12 addressdetailset pr0 pl0 text-center mb10"><i><?= $store['address'];?></i></div>
                                 <div class="gallery-listing__extras ">
                                    <div class="gallery-listing__location col-md-12 pl0 pr0">
                                      <div class="col-md-6 col-sm-6 fontset pl0">
                                      <i class="fa fa-star" aria-hidden="true"></i> 
                                      <i class="fa fa-star" aria-hidden="true"></i> 
                                      <i class="fa fa-star" aria-hidden="true"></i> 
                                      <i class="fa fa-star" aria-hidden="true"></i> 
                                      
                                      </div>
                                      <div class="col-md-6 col-sm-6 addressdetailset pr0 storelistdetail"><?= $store['storeTypes'] ?></div>
                                      
                                    </div>
                                 </div>
                              </a>
                              <a href="<?= $base?>store/<?= $store['href'];?>" class="gallery-listing__watchlist watchlist j-watchlist" title="Add to watchlist" data-action="add" data-adid="1243508174">
                                
                                 <span class="watchlist__text">Add to watchlist</span>
                              </a>
                           </div>
                        </div>
                        </a>
                        <?php } ?>
                       
                        
                     </div>
                     <!--div id="see-all" class="tabbed__see-all">
                        <a class="button button--primary-outline tabbed__button-see-all" href="#">See all</a>
                     </div>-->
                  </div>
                  <div class="tabbed__tab-content homepage-gallery__tab-activitylist" data-tabbed-role="tab" data-tab-content-name="activitylist" style="display:none;"></div>
                  <div class="tabbed__tab-content homepage-gallery__tab-watchlist" data-tabbed-role="tab" data-tab-content-name="Lavington" style="display:none;"></div>
                  <div class="tabbed__tab-content homepage-gallery__tab-alerts" data-tabbed-role="tab" data-tab-content-name="Westlands" style="display:none;"></div>
                  <div class="tabbed__tab-content homepage-gallery__tab-alerts" data-tabbed-role="tab" data-tab-content-name="store" style="display:none;"></div>
                 
                  
               </div>
            </div>
         </div>
      </div>
      <!-- /.page-container -->
      <div class="below-the-fold">
         <!-- /.page-container -->
         
         <!--<div class="container--full-width popular-searches">
            <div class="container">
               <div class="popular-searches__content">
                  <div id="footer-dynamic" class="c-clear c-text-center">
                     <div class="c-dark-green">
                        <span class="c-bold">Popular Items: </span>
                        <a href="#" title="" class="popular-searches__link">Item 1</a> 
						<a href="#" title="" class="popular-searches__link">Item 2</a> 
						<a href="#" title="" class="popular-searches__link">Item 3</a> 
						<a href="#" title="" class="popular-searches__link">Item 4</a> 
						<a href="#" title="" class="popular-searches__link">Item 5</a> 
						<a href="#" title="" class="popular-searches__link">Item 6</a> 
						<a href="#" title="" class="popular-searches__link">Item 7</a> 
						<a href="#" title="" class="popular-searches__link">Item 8</a> 
						<a href="#" title="" class="popular-searches__link">Item 9</a> 
						<a href="#" title="" class="popular-searches__link">Item 10</a> 
						<a href="#" title="" class="popular-searches__link">Item 11</a> 
						<a href="#" title="" class="popular-searches__link">Item 12</a> 
						<a href="#" title="" class="popular-searches__link">Item 13</a> 
						<a href="#" title="" class="popular-searches__link">Item 14</a> 
						<a href="#" title="" class="popular-searches__link">Item 15</a> 
                     </div>
                  </div>
               </div>
            </div>
         </div>-->
         <div class="container--full-width section section--alternate" id="homepage-leaderboard-bottom">
            <div id="div-gpt-ad-632115744089839813-leaderboard-footer" class="clearfix placement google-banner" data-track-action="homepage" data-track-label="bottom1" style="display: none;"></div>
         </div>
        
 </div>
  
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
    
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

    <script src="<?= $base;?>front/ui/javascript/home.js?v=1.0.4"></script> 
    <script src="<?= $base;?>front/ui/javascript/bxslider/jquery.bxslider.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.20.4/sweetalert2.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
   <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
    <!-- <script src="<?= $base;?>front/ui/theme/mvgv2/js/iscroll.min.js"></script>
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/drawer.min.js" type="text/javascript"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/js/drawer.min.js" type="text/javascript"></script>

    <script src="<?= $base; ?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>

    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style type="text/css">
        
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
    <script src="https://cdn.jsdelivr.net/bootstrap.datepicker-fork/1.3.0/js/locales/bootstrap-datepicker.pt-BR.js"></script>

    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
   
<script type="text/javascript">
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

    $('.zipcode-enter').focus();

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

    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
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

    $(document).delegate('#test-drawer', 'click', function(e) {
        $('.drawer-nav').addClass('how-it-works-open');
        $('.header-transparent').addClass('how-it-works-open');
        $('.hero-image').addClass('how-it-works-open');
        
    });
    
    $("body").on("click",function(e) {
        if ($('.how-it-works-open').length >= 1) {
            $('.drawer-nav').removeClass('how-it-works-open'); 
            $('.drawer.drawer--top').removeClass('drawer-open');
            $('.header-transparent').removeClass('how-it-works-open');
            $('.hero-image').removeClass('how-it-works-open');
        }
    });
    $(document).delegate('.how-it-works-close', 'click', function(e) {
        $('.drawer-nav').removeClass('how-it-works-open'); 
        $('.drawer.drawer--top').removeClass('drawer-open');
        $('.header-transparent').removeClass('how-it-works-open');
        $('.hero-image').removeClass('how-it-works-open');
    });
    
    function validateInp(elem) {
        var validChars = /[0-9]/;
        var strIn = elem.value;
        var strOut = '';
        for(var i=0; i < strIn.length; i++) {
          strOut += (validChars.test(strIn.charAt(i)))? strIn.charAt(i) : '';
        }
        elem.value = strOut;
    }

</script>
<script type="text/javascript">  

    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

        jQuery(function($){
            console.log("mask");
           $("#searchTextField").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
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
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();

               console.log("visitorIDif");
               console.log("visitorIDif"+visitorID);
               console.log(visitorID);
               $.ajax({
                    url: 'index.php?path=common/home/saveVisitorId&visitor_id='+visitorID,
                    type: 'post',
                    dataType: 'json',
                    success: function(json) {
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
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
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
 $(document).ready(function() {
              var owl = $('.owl-carousel');
              owl.owlCarousel({
				items:5,
            itemsTablet:4,
            itemsMobile:3,
				loop:true,
				margin:10,
				navigation:true,
				navigationText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
				autoPlay:true,
				autoPlayTimeout:1000,
				autoPlayHoverPause:true
              });
              $('.play').on('click', function() {
                owl.trigger('play.owl.autoplay', [1000])
              })
              $('.stop').on('click', function() {
                owl.trigger('stop.owl.autoplay')
              })
            })
 </script>
</html>
