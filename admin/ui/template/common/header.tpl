<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

<link type="text/css" href="ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link href="ui/javascript/bootstrap/shop/shop.css" type="text/css" rel="stylesheet" />
<link href="ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="ui/javascript/summernote/summernote.css" rel="stylesheet">
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link href="ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
<link href="ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />

<!-- include libraries(jQuery, bootstrap) -->
<!-- <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>  -->

<script type="text/javascript" src="ui/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="ui/javascript/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="ui/javascript/bootstrap-select/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="ui/javascript/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="ui/javascript/tinymce/jquery.tinymce.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.0/jquery.tinymce.min.js"></script> -->

<script src="ui/javascript/common.js" type="text/javascript"></script>
<script src="ui/javascript/jscolor-2.0.4/jscolor.js" type="text/javascript"></script>

<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>



<!-- include summernote css/js-->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script> -->

<script type="text/javascript" src="ui/javascript/summernote/summernote.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/datetimepicker/moment.js" ></script>
<script type="text/javascript" src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

</head>
<body>
<div id="container">
<?php if ($logged) { ?>
<header id="header" class="navbar navbar-static-top">
   <div class="menu-logo">
	<div class="logo-image">
            <img height="20" src="ui/image/gplogo.png" alt="Shop" title="Shop" />
	</div>
	<div class="menu-sitename"><a href="<?php echo $site_url; ?>"><?php echo $sitename; ?></a></div>
  </div>
  <?php if(!$this->user->isVendor()){ ?>  
  <div id="shop-search-div" class="col-sm-3 col-md-3 pull-left">
    <?php echo $search; ?>
  </div>
  <ul class="nav pull-left">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"  title="<?php echo $text_new; ?>"><i class="fa fa-plus fa-lg"></i> <span class="header-item"><?php echo $text_new; ?></span></a>
      <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_new; ?></li>
        <li><a href="<?php echo $new_product; ?>" style="display: block; overflow: auto;"><?php echo $text_new_product; ?></a></li>
        <li><a href="<?php echo $new_category; ?>" style="display: block; overflow: auto;"><?php echo $text_new_category; ?></a></li>
        <li><a href="<?php echo $new_customer; ?>" style="display: block; overflow: auto;"><?php echo $text_new_customer; ?></a></li>
      </ul>
    </li> 
  </ul>
  <?php } ?>
  <ul class="nav pull-right">
    <?php if(!$this->user->isFarmer()) { ?>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if(!empty($alert_order)) { ?><span class="label label-danger pull-left"><?php echo $alert_order; ?></span><?php } ?><i class="fa fa-shopping-cart fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_order; ?></li>
        <li><a href="<?php echo $order_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $order_status_total; ?></span>Processing</a></li>
        <li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
        
        <li><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
        
      </ul>
    </li>
    <?php } ?>
    <?php if(!$this->user->isVendor() && !$this->user->isFarmer()){ ?>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown"><?php if(!empty($alert_customer)) { ?><span class="label label-danger pull-left"><?php echo $alert_customer; ?></span><?php } ?><i class="fa fa-user fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_customer; ?></li>
        
        <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
      </ul>
    </li>
    <?php } ?>
    <?php if(!$this->user->isFarmer()) { ?>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if(!empty($alert_product)) { ?><span class="label label-danger pull-left"><?php echo $alert_product; ?></span><?php } ?><i class="fa fa-bell fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_product; ?></li>
        <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
        <li><a href="<?php echo $low_stock; ?>"><span class="label label-danger pull-right"><?php echo $product_low_total; ?></span><?php echo $text_low_stock; ?></a></li>
      </ul>
    </li>
    <?php } ?>
    <!-- <li class="dropdown">
        <a target="_blank" href="#"><i class="fa fa-life-ring fa-lg"></i></a>
    </li>  -->
    
    <li id="header-profile" class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown">
        <img width="25" height="25" src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $username; ?>" class="img-circle" />
        <span class="online-user"><?php echo $firstname; ?> <?php echo $lastname; ?></span>
      </a>
        <ul class="dropdown-menu dropdown-menu-right">
        <li>
          <div class="header-profile">
            <h4>
                <?php if($this->user->hasPermission('access','user/user')){ ?>
                <a href="<?php echo $url_user ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a>
                <?php }else{ ?>
                <a><?php echo $firstname; ?> <?php echo $lastname; ?></a>
                <?php } ?>
            </h4>
            <small><?php echo $user_group; ?></small>
          </div>
        </li>
        
        <?php if (!$this->user->isVendor()): ?>
          
        <?php if($this->user->hasPermission('access','setting/setting')){ ?>
            <li class="divider"></li>
            <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
            <?php } ?>
        <?php else: ?>    
              <li class="divider"></li>
              <li><a href="<?php echo $accountsetting; ?>"><?php echo $text_setting; ?></a></li>

         <?php endif ?>
        <?php if(!$this->user->isFarmer()) { ?>
        <li class="divider"></li>
        <?php foreach ($stores as $store) { ?>
        <li><a href="<?php echo $store['href']; ?>" target="_blank">Visit site</a></li>
        <?php }  ?>
        <li class="divider"></li>
        <?php }  ?>
        <?php if(!$this->user->isFarmer()) { ?>
        <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
        <?php } ?>
        <?php if($this->user->isFarmer()) { ?>
        <li><a href="<?php echo $farmer_logout; ?>"><?php echo $text_logout; ?></a></li>
        <?php } ?>
      </ul>
    </li>
  </ul>
</header>
<?php } ?>