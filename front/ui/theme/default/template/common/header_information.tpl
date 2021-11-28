<!DOCTYPE>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]> ><! <![endif]-->

<html style="" class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
    <!-- <![endif] -->
    <head>

        <title><?= $title ?></title>

        <link href="image/theme/192x192-f99243ddd270b032e0da78e91a6ef414.png" rel="icon" sizes="192x192" />
        <link href="image/theme/128x128-e16fef127b8a1d546004e7cb716c17d7.png" rel="icon" sizes="128x128" />

        <base href="<?php echo $base; ?>" />
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>
        <?php if ($keywords) { ?>
        <meta name="keywords" content= "<?php echo $keywords; ?>" />
        <?php } ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <?php if ($icon) { ?>
            <link href="<?php echo $icon; ?>" rel="icon" />
            <?php } ?>
            <?php foreach ($metas as $meta) { ?>
            <meta name="<?php echo $meta['name']; ?>" content="<?php echo $meta['content']; ?>" />
            <?php } ?>

            <?php foreach ($links as $link) { ?>
            <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
            <?php } ?>

            <script src="front/ui/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>   
            <script src="front/ui/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   
            <script src="front/ui/javascript/common.js" type="text/javascript"></script>

            <?php foreach ($scripts as $script) { ?>
            <script src="<?php echo $script; ?>" type="text/javascript"></script>
            <?php } ?>

            <?php echo $google_analytics; ?>

            <!-- - if Rails.env.staging? -->
            <!-- %meta{:content => "noindex", :name => "robots"} -->
            <link rel="stylesheet" href="<?= $base ?>front/ui/theme/default/stylesheet/iwp.css" />
            <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,400,600,300" />    
            <link href="<?= $base ?>front/ui/theme/default/stylesheet/css.css" rel="stylesheet" type="text/css" />    
            <link href="<?= $base ?>front/ui/theme/default/stylesheet/layout.css" media="screen" rel="stylesheet" type="text/css" />

            <script src="<?= $base ?>front/ui/theme/default/javascript/common.js" charset="UTF-8" type="text/javascript"></script>

            <?php foreach ($styles as $style) { ?>
            <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
            <?php } ?>

            <link href="<?= $base ?>front/ui/theme/default/stylesheet/custom.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="<?php echo $class; ?>">

        <!--[if lte IE 9]>
        <div class='container-fluid'>
            <div class='row'>
                <div class='not-supported' role='alert'>
                    <button class='close' data-dismiss='alert' type='button'>
                        <span aria-hidden='true'>×</span>
                        <span class='sr-only'>Close</span>
                    </button>
                    <strong>Warning:</strong>
                    We don't fully support your browser. Please use  Chrome, Firefox or IE 10 and above for the best experience.
                </div>
            </div>
        </div>
        <![endif]-->

        <header id="header">
            <div class="container">
                <div class="navbar-container">
                    <div role="navigation" class="navbar navbar-default">
                        <div class="navbar-header">
                            <button type="button" data-toggle="collapse" data-target=".navbar-collapse" class="navbar-toggle">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                            <h1 id="logo">
                                <?php if ($logo) { ?>
                                <a class="logo" href="<?php echo $home; ?>">
                                    <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" />
                                </a>
                                <?php } else { ?>
                                <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
                                <?php } ?>
                            </h1>
                        </div>
                        
                        <div class="collapse navbar-collapse" id="instacart-navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li>  
                                    <a href="<?= $this->url->link('information/enquiries') ?>">List Your Products</a>
                                </li> 
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                                        <i class="fa fa-phone"></i>
                                        Support
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu support" role="menu">
                                        <li>
                                            <a href="<?= $help ?>" id="faq">
                                                <i class="fa fa-question-circle"></i>
                                                FAQ
                                            </a>
                                        </li>
                                        <li>
                                            <a href="tel:<?= $telephone ?>" id="support">
                                                <i class="fa fa-headphones"></i>
                                                Call us @ <br />
                                                <?= $telephone ?>
                                            </a>
                                        </li>        
                                    </ul>
                                </li>

                                <?php if($is_login) { ?>
                                <li class="dropdown">
                                    <a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle">
                                        Hi,
                                        <?= $name ?>       
                                        <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu account">
                                        <span class="rightcaret"></span>
                                        <li>
                                        <a href="<?= $account ?>" id="my-account">
                                          <i class="fa fa-user"></i>
                                          My Account
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $reward ?>" id="my-rewards">
                                          <i class="fa fa-gift"></i>
                                          My Rewards
                                          </span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $order ?>" id="my-orders">
                                          <i class="fa fa-clock-o"></i>
                                          My Orders
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $refer ?>" id="invite-friends" class="ddmenubtopb">
                                          <i class="fa fa-users"></i>
                                          Refer Friends
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $logout ?>" id="sign-out" class="app-link"><i class="fa fa-sign-out"></i>Sign out
                                        </a>
                                      </li>
                                    </ul>
                                </li>
                                <?php } else{ ?>
                                <li>
                                    <a href="<?= $this->url->link('account/login') ?>" id="sign-in" class="app-link"><span>
                                            <i class="fa fa-sign-in"></i>
                                        </span>Sign In
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>


