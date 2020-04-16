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
    <div class="header-white">
        <div class="header_left">
            <div class="header_item">
                <a href="<?= $store ?>" class="header_item_content"><img src="<?= $logo ?>" alt=""></a>
            </div>
        </div>
        <div class="header_right hidden-xs hidden-sm">

           <div class="header_item">
                <div class="header-checkout-item">
                    <div class="checkout-promise-item"><i class="fa fa-shopping-basket"></i> <?= $text_replacement_guarantee?></div>
                    <div class="checkout-promise-item"><i class="fa fa-calendar-check-o"></i><?= $text_genuine_product?></div>
                    <div class="checkout-promise-item"><i class="fa fa-shield"></i> <?= $text_secure_payments?></div>
                </div>
            </div>
        </div>
    </div>