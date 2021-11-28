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

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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

        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,400,600,300" />    
        <link href="<?= $base ?>front/ui/theme/default/stylesheet/layout.css" media="screen" rel="stylesheet" type="text/css" />

        <?php foreach ($styles as $style) { ?>
        <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>

    </head>

    <body class="<?php echo $class; ?>">

        <header class="ic-nav-new">
            <div class="ic-nav-primary">
                <div class="ic-nav-inner clearfix">

                    <a class="header-logo pull-left" href="<?php echo $home; ?>">
                        <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" />
                    </a>

                    <span><?= $text_help_center ?></span>
                    <div id="primaryNavView"></div>
                </div>
            </div>
        </header>

        <div role="main" id="main" class="container" style="min-height: 350px;">

            <div class="container help-search">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div id='help_search_form' action="#">
                            <div class="form-group">
                                <input type="search" placeholder="How can we help?" name="q" class="search" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <script>
                $(function(){
                    $('.search').on('keydown', function(e){
                        if(e.keyCode === 13){
                            $q = $('.search').val();
                            location = '<?= $this->url->link('information/help/search') ?>&q='+$q;
                        }                        
                    });
                });
            </script>