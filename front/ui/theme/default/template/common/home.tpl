<!DOCTYPE>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]> ><! <![endif]-->
<html style="" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"><!-- <![endif] -->
<head>
    <title>
        <?= $heading_title ?>
    </title>
        
    <link rel="shortcut icon" type="image/png" href="image/<?php echo $this->config->get('config_icon') ?>"/>
        
    <link href="<?= $base ?>front/ui/theme/default/stylesheet/layout_home.css?V=1" media="screen" rel="stylesheet" type="text/css" />
</head>
    <body class="home-page">
        
        <div class="bounce"></i><img style="width:100%;" src="image/pn.png"></img></div> 
        <div class="fixed"><a href="https://www.dropbox.com/s/rki4afedojmsjzy/Login%20Credentials%20-%20MV%20Grocery.xlsx?dl=1" target="_blank">Demo Access Excel</a></div>

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

        <section style="height: 439.982px;" class="welcome">

            <header role="banner" class="instacart-nav navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" data-toggle="collapse" data-target="#instacart-navbar-collapse" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        
                        <a href="<?= $home ?>" class="resp-logo logo">
                            <img src="<?= $logo ?>" alt="Resp-logo" />
                        </a>
                        
                    </div>
                    <div id="instacart-navbar-collapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                    <i class="fa fa-phone"></i>
                                    <?= $support ?>
                                    <span class="caret"></span>
                                </a>
                                <ul role="menu" class="dropdown-menu support">
                                    <li>
                                        <a class="hidden-xs hidden-sm text-center" href="<?= $this->url->link('information/help') ?>"><?= $faq ?></a>
                                        <a class="visible-xs visible-sm" href="<?= $this->url->link('information/help') ?>"><?= $faq ?></a>
                                    </li>
                                    <li>
                                        <a href="tel:08088983828">
                                            <div class="hidden-xs hidden-sm text-center">
                                                <?= $call ?>
                                                <br>
                                                    <?= $telephone ?>
                                            </div>
                                            <div class="visible-xs visible-sm">
                                                <i class="fa fa-headphones"></i>
                                                <?= $telephone ?>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                                <?php if($is_login) { ?>
                                <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                    <?= $text ?>
                                    <?= $name ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu account" role="menu">
                                        <span class="rightcaret"></span>
                                        <li>
                                            <a href="<?= $account ?>" id="my-account">
                                                <i class="fa fa-user"></i>
                                                <?= $text_account ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= $reward ?>" id="my-rewards">
                                                <i class="fa fa-gift"></i>
                                                <?= $text_rewards ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= $order ?>" id="my-orders">
                                                <i class="fa fa-clock-o"></i>
                                                <?= $text_orders ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= $refer?>" id="invite-friends" class="ddmenubtopb">
                                                <i class="fa fa-users"></i>
                                                <?= $text_refer ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= $logout ?>" id="sign-out" class="app-link"><i class="fa fa-sign-out"></i><?= $text_sign_out ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } else{ ?>
                                <li>
                                    <a href="<?= $this->url->link('account/login') ?>" id="sign-in" class="app-link"><span>
                                            <i class="fa fa-sign-in"></i>
                                        </span><?= $text_sign_in ?>
                                    </a>
                                </li>
                                <?php } ?>
                        </ul>
                    </div>
                </div>
            </header>

            <?php if($warning){ ?>
            <div class="alert alert-warning">
                <?= $warning ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php } ?>
                
            <div class="container-fluid">
                
                <h1>
                    <?= $text_heading ?>
                </h1>
                <h3>
                    <div class="h-f-t">
                        <?= $text_heading2 ?>
                    </div>
                    <div class="v-f-t">
                        <?= $text_heading3 ?>
                    </div>
                </h3>
                <div class="store-content">
                    <div class="store-selector">
                        <div class="col-md-3 col-md-offset-1" id="combobox-location">
                            <div class="selectize-control combobox-location single">
                                <div class="selectize-input">
                                    <!--
                                    <input type="text" autocomplete="off" placeholder="Enter Location" name="address" />
                                    -->
                                     <input name="address" id="searchTextField" type="text" class="field-input" />
                                    <button onclick="detect_location();" type="button" id="btn_location">
                                        <i class="fa-crosshairs fa"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 store-options" id="combobox-shop">
                            <div class="selectize-control combobox-shop single">
                                <div class="selectize-input items has-options not-full">
                                    <input name="store_name" type="text" autocomplete="off" tabindex="" style="width: 126px; opacity: 1; position: relative; left: 0px;" placeholder="Choose Store" />
                                    <input name="store_id" type="hidden" />
                                </div>
                                <div class="selectize-dropdown combobox-shop single hide" style="width: 100%; top: 42px; left: 0px;">
                                    <div class="selectize-dropdown-content ps-container ps-active-y" id="store_list_container">

                                        <!--
                                        <div class="shop active" data-selectable="" onclick="start(888)">
                                            <div class="name">Pearl City Super Bazaar</div>
                                            <div class="address">80ft Road, Arekere</div>                                                
                                        </div>                
                                        --> 

                                    </div>                                        
                                </div>
                            </div>    
                        </div>
                        <div class="col-md-3 store-options">
                            <button class="btn btn-lg btn-primary ladda-button start-shopping" data-style="zoom-out" data-size="l">
                                <span class="ladda-label"><?= $label_start ?></span>
                                <span class="ladda-spinner"></span>
                                <div style="width: 0px;" class="ladda-progress"></div>
                            </button>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </section>

        <section class="steps">
            <div class="container">
                <div class="row">
                    <div class="step">
                        <div class="step-icon">
                            <div class="sprite-step-1"></div>
                        </div>
                        <?= $step1 ?>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <div class="sprite-step-2"></div>
                        </div>
                        <?= $step2 ?>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <div class="sprite-step-3"></div>
                        </div>
                        <?= $step3 ?>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <div class="sprite-step-4"></div>
                        </div>
                        <?= $step4 ?>
                    </div>
                </div>
            </div>
        </section>

        <?php if($banners){ ?>
        <section class="offers">
            <div class="container">
                <h3><?= $text_heading4 ?></h3>
                <div  class="offer-content">
                    <div class="offer-container">
                        <ul class="offer-slider" style="width: 615%; position: relative; transition-duration: 0s; transform: translate3d(-40px, 0px, 0px);">
                           <?php foreach($banners as $banner){ ?>
                            <li>
                                <div class="offer">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <img src="<?= $banner['image'] ?>" class="image" />
                                        </div>
                                        <div class="col-md-7">
                                            <div class="details">
                                                <div class="title"><?= $banner['title'] ?></div>
                                                <div class="short-description"><?= $banner['description'] ?></div>
                                                <?php if($banner['link']){ ?>                                                 
                                                <a href="<?= $banner['link'] ?>" class="know-more">Know more</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                           <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <?php } ?>
        
        <?php if($testimonials){ ?>
        <section class="loves">
            <div class="container">
                <h3><?= $text_heading4 ?></h3>
                <div class="row">
                    <?php foreach($testimonials as $love){ ?>
                    <div class="love">
                        <div class="sprite-user-1">
                            <img src="<?= $love['thumb'] ?>" />
                        </div>
                        <div class="message">
                            “<?= $love['message'] ?>”
                        </div>
                        <div class="name">-<?= $love['name'] ?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <?php } ?>

     <!--   <section class="app">
            <div class="container">
                <h3>Order on the go? Get the App</h3>
                <div class="app-row">
                    <div class="app-col h-f-t">
                        <img src="image/<?= $this->config->get('config_promo_app_image') ?>" class="center-img" alt="App Screenshots" />
                    </div>
                    <div class="app-col make-center">
                        <h3>
                            All your favorite products right at your finger tip, shop from
                            110+
                            locations across Bangalore
                        </h3>
                        <div class="row">
                            <div class="col-xs-12 center-block">
                                <a target="_blank" href="<?= $this->config->get('config_android_app_link') ?>" class="res-padding-left">
                                    <img src="image/theme/playstore-745d80c3769548f43631f5af755fd97d.png" alt="App Screenshots">
                                </a>
                                <a target="_blank" href="<?= $this->config->get('config_apple_app_link') ?>">
                                    <img src="image/theme/appstore-de022106fb7a694c61fbf44b9407551e.png" alt="App Screenshots">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="app-col v-f-t">
                        <img src="image/<?= $this->config->get('config_promo_app_image') ?>" class="center-img" alt="App Screenshots" />
                    </div>
                </div>
            </div>
        </section>-->

        <section class="contact_us">
            <div class="container">
                <h3><?= $text_heading6 ?></h3>
                <div class="row">
                    <div id="contact" class="col-md-6">

                        <div class="form-group" id="message_wrapper">                   
                        </div>

                        <form method="POST" class="contact-us bv-form" action="contactus" novalidate="novalidate">
                            <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                            <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                            <div class="form-group has-feedback">
                                <label for="contact-name" class="sr-only"><?= $label_name ?> </label>
                                <input type="text" placeholder="Name" name="name" id="contact-name" data-bv-notempty="true" data-bv-notempty-message="Name required" class="form-control contact" data-bv-field="name" />
                                    <i style="display: none;" class="form-control-feedback glyphicon glyphicon-remove" data-bv-icon-for="name"></i>
                                    <small style="display: none;" data-bv-for="name" class="help-block">Name required</small>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="contact-email" class="sr-only"><?= $label_email/phone ?></label>
                                <input type="text" placeholder="Email/Phone" name="email" id="contact-email" data-bv-notempty="true" data-bv-notempty-message="Email/Phone required" class="form-control contact" data-bv-field="email">
                                    <i style="display: none;" class="form-control-feedback glyphicon glyphicon-remove" data-bv-icon-for="email"></i>
                                    <small style="display: none;" class="help-block" data-bv-for="email">Email/Phone required</small>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="contact-message" class="sr-only"><?= $label_msg ?></label>
                                <textarea rows="6" placeholder="Message" name="enquiry" id="contact-message" data-bv-notempty="true" data-bv-notempty-message="Message required" class="form-control contact" data-bv-field="message"></textarea>
                                <i style="display: none;" class="form-control-feedback glyphicon glyphicon-remove" data-bv-icon-for="message"></i>
                                <small style="display: none;" data-bv-for="enquiry" class="help-block">Message required</small>
                            </div>
                            <button type="button" onclick="send();" class="btn btn-success btn-lg">
                                <?= $button_send ?>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 h-f-t">
                        <!--map-->
                        <img src="image/<?= $this->config->get('config_map_image') ?>" class="center-img" alt="Map Screenshots" />
                    </div>
                </div>
            </div>
        </section>
                    
        <script src="front/ui/javascript/jquery/jquery-2.1.1.min.js"></script>
        <script src="front/ui/javascript/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
        <script src="front/ui/javascript/home.js"></script>    
        <script src="front/ui/javascript/bxslider/jquery.bxslider.min.js"></script>
    
        <?= $footer ?>
        
        <script>

            $('.offer-slider').bxSlider({
              maxSlides: 2,
              responsive: true,
              slideWidth: 535,
              slideMargin: 35,
              preloadImages: 'visible'
            });

            function send() {

                $('.help-block').css('display', 'none');
                $('.contact-us i').css('display', 'none');

                $.post('index.php?path=information/contact/send', $('.contact-us').serialize(), function(data) {

                    var data = JSON.parse(data);

                    if (data.status) {
                        $html = '<div class="alert alert-info alert-dismissable text-center">';
                        $html += '<button type="button" data-dismiss="alert" class="close" aria-hidden="true">×</button>';
                        $html += '<ul>Thank you for contacting us. We\'ll get back to you soon!</ul>';
                        $('#message_wrapper').html($html);

                        $('.contact-us input, .contact-us textarea').val('');

                    } else {
                        if (data.error_name) {
                            $('.help-block[data-bv-for="name"]').html(data.error_name).show();
                            $('.help-block[data-bv-for="name"]').parent().find('i').show();
                        }

                        if (data.error_email) {
                            $('.help-block[data-bv-for="email"]').html(data.error_email).show();
                            $('.help-block[data-bv-for="email"]').parent().find('i').show();
                        }

                        if (data.error_enquiry) {
                            $('.help-block[data-bv-for="enquiry"]').html(data.error_enquiry).show();
                            $('.help-block[data-bv-for="enquiry"]').parent().find('i').show();
                        }
                    }
                });
            }
        </script>

   
        <style>
            .pac-container::after{
                background: none !important;
                margin-top: 10px;
            }
        </style>
