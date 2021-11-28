<a href="javascript:" id="goToTop"><i class="fa fa-chevron-up"></i></a>

<div class="space-small bg-default bdr-btm">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <!-- feature block start -->
                <div class="feature-block feature-left">
                    <div class="feature-icon">
                        <div class="feature-icon-bg"> <i class="fa fa-tags"></i> </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title"><?= $text_best_prices ?> &amp; <?= $text_offers ?></h3>
                        <p><?= $text_logo_title?></p>
                    </div>
                </div>
            </div>
            <!-- /.feature block start -->
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <!-- feature block start -->
                <div class="feature-block feature-left">
                    <div class="feature-icon">
                        <div class="feature-icon-bg"> <i class="fa fa-barcode"></i> </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title"><?= $text_wide_assortment ?></h3>
                        <p><?= $text_variety_title?></p>
                    </div>
                </div>
            </div>
            <!-- /.feature block start -->
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <!-- feature block start -->
                <div class="feature-block feature-left">
                    <div class="feature-icon">
                        <div class="feature-icon-bg"> <i class="fa fa-undo"></i> </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title"><?= $text_easy_returns?></h3>
                        <p><?= $text_return_title?></p>
                    </div>
                </div>
            </div>
            <!-- /.feature block start -->
        </div>
    </div>
</div>

<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="footer-block">
                    <div class="about-info">
                        <h3 class="footer-title"><?= $text_about_title ?>  <?= $text_trademark?></h3>
                        <?php if($footer_text) { ?>

                            <?= $aboutus ?>
                            <br/>
                            
                                <a href="<?= $aboutus_link ?>" target="_blank" class="btn-link"><?= $text_read_more?></a>
                        <?php } else { ?>
                               <!--  <a href="https://www.youtube.com/embed/k0ThI81EkX8?autoplay=1"
                        class="html5lightbox btn btn-primary" id="youtubeplay" type="button" style="display:block;
                              background-image: url(<?= $base;?>front/ui/theme/mvgv2/images/youtube-button-new.png);
                              width:250px;
                              height: 140px;
                              background-color: white;
                              border-color: grey;
                            "> </a> -->
                             <a href="<?= $footer_video_link ?>"
                        class="html5lightbox btn btn-primary" id="youtubeplay" type="button" style="display:block;
                              background-image: url(<?= $base;?><?= $footer_thumb ?>);
                              width:250px;
                              height: 140px;
                              background-color: white;
                              border-color: grey;
                            "> </a>
                        <?php } ?>
                        

                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-block">
                    <h3 class="footer-title"><?= $text_useful_links ?></h3>
                    <ul class="listnone">
                        <?php foreach ($informations as $information) { ?>
                            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                        <?php } ?>
                        <li><a href="<?= $this->url->link('information/enquiries'); ?>"><?= $text_store_listed?></a></li>                    
                        <li><a href="<?= $this->config->get('config_shopper_link') ?>" target="_blank"><?= $become_shopper ?></a></li>

                        <li><a href="<?= $base.'admin'; ?>" target="_blank"><?= $login_seller ?></a></li>

                        <!-- <li><a href="<?php echo $this->url->link('blog/article'); ?>"><?= $blog ?></a></li> -->
                        <li><a href="<?= $help ?>"><?= $text_faq ?></a></li>
                        <!-- <li><a href="#"><?= $text_careers?></a></li> -->
                         <li><a href="#" data-toggle="modal" data-target="#contactusModal"><?= $contactus ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="footer-block">

                    <?php if(!empty($play_store) || !empty($app_store)) { ?>
                        <h3 class="footer-title"><?= $text_download_app ?></h3>
                    <?php } ?>
                    
                    <div class="app-action">

                        <?php if(!empty($play_store)){ ?>
                            <a href="<?= $play_store ?>" target="_blank"><img src="<?= $playStorelogo;?>" class="app-btn"></a>
                        <?php } ?>

                        <?php if(!empty($app_store)) { ?>
                            <a href="<?= $app_store ?>" target="_blank"><img src="<?= $appStorelogo;?>" class="app-btn"></a>
                        <?php } ?>

                        
                        
                    </div>
                    <?php if(empty($play_store) && empty($app_store)) { ?>
                        <div class="footer-social" style="border-top: 0px!important">
                    <?php } else { ?>
                        <div class="footer-social">
                    <?php } ?>

                       <?php if(!empty($facebook)) { ?>
                        <a href="<?= $facebook ?>" target="_blank" class="fa fa-facebook-square color-facebook"></a>
                       <?php } ?>                        
                       <?php if(!empty($twitter)) { ?>
                        <a href="<?= $twitter ?>" target="_blank" class="fa fa-twitter-square color-twitter"></a>
                       <?php } ?>
                       <?php if(!empty($instagram)) { ?>
                        <a href="<?= $instagram ?>" target="_blank"  class="fa fa-instagram color-instagram"></a>
                       <?php } ?>
                       <?php if(!empty($google)) { ?>
                        <a href="<?= $google ?>" target="_blank" class="fa fa-google-plus color-gplus"></a>
                       <?php } ?>
                       <?php if(!empty($youtube)) { ?>
                        <a href="<?= $youtube ?>" target="_blank" class="fa fa-youtube-square color-youtube"></a>
                       <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tinyfooter">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                
                <p class="footer-text">&copy; <?= $text_trademark?>, <?php echo date('Y'); ?></p>
            </div>

            <div class="col-md-2">
                <?php echo $language; ?>
            </div>

            
        </div>
    </div>
</div>
<?= $contactus_modal ?>
<?php echo $google_analytics; ?>