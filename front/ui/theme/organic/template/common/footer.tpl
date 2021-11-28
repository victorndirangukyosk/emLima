<footer> 
    <!-- BEGIN INFORMATIVE FOOTER -->
   
      <div class="footer-middle">
        <div class="container">
          <div class="row">
            <div class="col-md-4 col-sm-6">
              <div class="footer-column">
                <div class="about-info">
                        <h4><?= $text_about_title ?>  <?= $text_trademark?></h4>
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
            <div class="col-md-4 col-sm-6">
              <div class="footer-column">
                 <h4><?= $text_useful_links ?></h4>
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
            
            <div class="col-md-3 col-sm-6">
              <div class="footer-column">
                <h4>Contact Us</h4>
                <div class="contacts-info">
                  <address>
                  PO Box 57666-00200, Heritan House Woodlands Road, Off. Argwings-Kodhek Road, Kenya<br>
                  </address>
                  <div class="phone-footer">+254 780703586</div>
                  <div class="email-footer"><a href="mailto:no-reply@emlima.com">no-reply@emlima.com</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!--container--> 
    </div>
    <!--footer-inner--> 
    
    <!--footer-middle-->
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-4">
            <!--<div class="social">
              <ul>
                <li class="fb"><a href="https://www.facebook.com/kwikbasket" target="_blank"></a></li>
                <li class="tw"><a href="#"></a></li>
                <li class="googleplus"><a href="#"></a></li>
                <li class="rss"><a href="#"></a></li>
                <li class="pintrest"><a href="#"></a></li>
                <li class="linkedin"><a href="#"></a></li>
                <li class="youtube"><a href="#"></a></li>
              </ul>
            </div>-->
          </div>
          <div class="col-sm-4 col-xs-12 coppyright">© emLima, <?php echo date("Y"); ?> </div>
          <div class="col-xs-12 col-sm-4">
            <!--<div class="payment-accept"> 
             <a href="<?= $play_store ?>"><img src="<?= $base ?>front/ui/theme/organic/images/playstore.png" alt=""></a>
             <a href="<?= $app_store ?>"><img src="<?= $base ?>front/ui/theme/organic/images/appstore.png" alt=""> </a></div>
          </div>-->
        </div>
      </div>
    </div>
    <!--footer-bottom--> 
    <!-- BEGIN SIMPLE FOOTER --> 
  </footer>