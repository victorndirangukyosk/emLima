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
    
</head>

<body>
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
