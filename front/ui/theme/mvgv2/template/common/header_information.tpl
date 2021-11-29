<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="kdt:page" content="account-page">
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
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/style.css?v=5.2.8">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/abhishek.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
   
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="../../https@oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="../../https@oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?= $base;?>front/ui/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>   
    <!-- <script src="<?= $base;?>front/ui/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   -->

    <script src="<?= $base;?>front/ui/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <script src="<?= $base;?>front/ui/theme/mvgv2/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script>
</head>

<body>
    
    <div class="alerter">
        <?php if($success){ ?>
            <div class="alert alert-info normalalert">
                <p class="notice-text"><?= $success ?></p>
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

    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="dashboard-content">
                        <div class="row">
                            <div class="col-md-3 nopr">
                                <div class="dashboard-profile-left">
                                    <div class="profile-block">
                                        <img src="<?= $base;?>front/ui/theme/mvgv2/images/profile.png" alt="">
                                        <div class="profile-number"><?= $text_hello ?>, <?= $f_name ?></div>
                                    </div>
                                    <div class="profile-navigation">
                                        <ul class="nav nav-stacked">

                                            <li role="presentation">
                                                <?php if(strpos($account,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                    <a href="<?= $account ?>" class="active">
                                                <?php } else { ?>
                                                    <a href="<?= $account ?>">
                                                <?php } ?>
                                                
                                                <i class="fa fa-edit"></i><?= $text_profile ?></a>
                                            </li>

                                            <li role="presentation" >
                                                <?php if(strpos($wishlist,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                    <a href="<?= $wishlist ?>" class="active">
                                                <?php } else { ?>
                                                    <a href="<?= $wishlist ?>">
                                                <?php } ?>
                                                
                                                <i class="fa fa-list-alt"></i><?= $text_wishlist ?></a>
                                            </li>

                                            <li role="presentation" >

                                            <?php if(strpos($refer,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                <a href="<?= $refer ?>" class="active">
                                            <?php } else { ?>
                                                <a href="<?= $refer ?>">
                                            <?php } ?>

                                            <i class="fa fa-share-alt"></i><?= $text_refer ?></a>
                                            </li>

                                            <li role="presentation" >

                                            <?php if(strpos($order,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                <a href="<?= $order ?>" class="active">
                                            <?php } else { ?>
                                                <a href="<?= $order ?>">
                                            <?php } ?>

                                            <i class="fa fa-reorder"></i><?= $text_order ?></a>
                                            </li>

                                            <?php if($this->config->get('config_account_return_status') == 'yes') { ?>
                                                <li role="presentation" >
                                                    <?php if(strpos( $return,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                        <a href="<?= $return ?>" class="active">
                                                    <?php } else { ?>
                                                        <a href="<?= $return ?>">
                                                    <?php } ?>
                                                    
                                                    <i class="fa fa-undo"></i><?= $text_return ?></a>
                                                </li>
                                            <?php } ?>
                                            

                                            <?php if($this->config->get('config_credit_enabled')) { ?>

                                                <li role="presentation">

                                                    <?php if(strpos( $credit,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                        <a href="<?php echo $credit; ?>" class="active">
                                                    <?php } else { ?>
                                                        <a href="<?php echo $credit; ?>">
                                                    <?php } ?>
                                                    <i class="fa fa-money"></i><?= $text_cash ?> </a>
                                                </li>
                                            <?php } ?>

                                            <?php if($this->config->get('config_reward_enabled')) { ?>
                                            
                                                <li role="presentation">

                                                    <?php if(strpos( $reward,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                        <a href="<?php echo $reward; ?>" class="active">
                                                    <?php } else { ?>
                                                        <a href="<?php echo $reward; ?>">
                                                    <?php } ?>
                                                    <i class="fa fa-money"></i><?= $text_rewards ?> </a>
                                                </li>

                                            <?php } ?>

                                            <li role="presentation">
                                            <?php if(strpos($address,$_SERVER["REQUEST_URI"]) !== false) { ?>
                                                <a href="<?= $address ?>" class="active">
                                            <?php } else { ?>
                                                <a href="<?= $address ?>">
                                            <?php } ?>

                                            <i class="fa fa-address-book"></i><?= $label_address ?> </a></li>
                                            <li role="presentation"><a href="<?= $logout ?>"><i class="fa fa-power-off"></i> <?= $text_signout ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>