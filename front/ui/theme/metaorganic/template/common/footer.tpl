<footer> 
    <!-- BEGIN METAORGANIC FOOTER -->
   
   <div id="footer" class="footer">
         <div class="footer__app-section">
            <div class="container">
               <div id="download-app" class="footer__download-app-container">
                  <div id="download-app-icon" class="footer__download-app-desktop">
                     <div class="footer__app-icon-bg">
                       <img src="<?= $base;?>front/ui/theme/metaorganic/images/app-logo.png" class="footer-app-img" >
                     </div>
                     <div class="footer__download-app-info">
                        <div class="footer__download-app-heading">Download the Kwik Basket app for  Android</div>
                        <div class="footer__download-app-heading-short">Download the Kwik Basket</div>
                        <div class="footer__download-app-subheading">Buy and sell faster on the go</div>
                        <div class="footer__download-app-rating">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                           
                        </div>
                     </div>
                  </div>
                  <div id="app-icons" class="footer__download-app-badges">
                     <!--<a class="footer__download-app-badge" href="<?= $app_store ?>" target="_blank"><img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/apple-app-store.svg" alt="Download the Mydhukha IOS app for free" width="135" height="40"></a>-->
                     <a class="footer__download-app-badge" href="<?= $play_store ?>" target="_blank"><img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/google-play.svg" alt="Download the Mydhukha Android app for free" width="135" height="40"></a>
                  </div>
               </div>
            </div>
         </div>
         <div style="display:none;" class="container">
            <div id="footer-links" class="footer__footer-links">
               <div class="footer__footer-link-col contactmobhide">
                 
                  <h4 class="footer__footer-link-heading"><?= $text_useful_links ?></h4>
                    <ul class="footer__footer-link-heading">
                       
                        <li class="footer__footer-item"><a class="footer__footer-link h-url" href="<?= $this->url->link('information/enquiries'); ?>"><?= $text_store_listed?></a></li>                    
                        <li class="footer__footer-item"><a class="footer__footer-link h-url" href="<?= $this->config->get('config_shopper_link') ?>" target="_blank"><?= $become_shopper ?></a></li>

                        <li class="footer__footer-item"><a class="footer__footer-link h-url" href="<?= $base.'admin'; ?>" target="_blank"><?= $login_seller ?></a></li>

                        <!-- <li class="footer__footer-item"><a class="footer__footer-link h-url"  href="<?php echo $this->url->link('blog/article'); ?>"><?= $blog ?></a></li> -->
                        <li class="footer__footer-item"><a class="footer__footer-link h-url" href="<?= $help ?>"><?= $text_faq ?></a></li>
                        <!-- <li class="footer__footer-item"><a class="footer__footer-link h-url" href="#"><?= $text_careers?></a></li> -->
                         <li class="footer__footer-item"><a class="footer__footer-link h-url" href="#" data-toggle="modal" data-target="#contactusModal"><?= $contactus ?></a></li>
                    </ul>
               </div>
               <div class="footer__footer-link-col contactmobhide">
                  <h4 class="footer__footer-link-heading">Legal</h4>
                   <ul class="footer__footer-link-heading">
                        <?php foreach ($informations as $information) { ?>
                            <li class="footer__footer-item"><a class="footer__footer-link h-url" href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                        <?php } ?>
                       
                    </ul>
               </div>
               <div class="footer__footer-link-col contactmob">
                 <h4>Contact Us</h4>
                <div class="contacts-info">
                  <address>
                  </i>PO Box 57666-00200, Heritan House Woodlands Road, Nairobi<br>
                  </address>
                  <div class="phone-footer">+254 738770186</div>
                  <div class="email-footer"><a class="footer__footer-link h-url" href="mailto:hello@kwikbasket.com">hello@kwikbasket.com</a></div>
                </div>
               </div>
               <div class="footer__footer-link-col contactmobhide">
                  <h4><?= $text_about_title ?>  <?= $text_trademark?></h4>
                  <address>
                        <?php if($footer_text) { ?>

                            <?= $aboutus ?>
                            <br/>
                            
                                <a href="<?= $aboutus_link ?>" target="_blank" class="btn-link"><?= $text_read_more?></a>
                        <?php }  ?>
                        </address>
               </div>
               <!--<div class="footer__footer-link-col">
                  <h4 class="footer__footer-link-heading">Mydhukha</h4>
                  <ul>
                     
                     <li class="footer__footer-item"><a class="footer__footer-link " href="#">Sitemap</a></li>
                  </ul>
               </div>
               <div class="footer__footer-link-col">
                  <h4 class="footer__footer-link-heading">Top Categories</h4>
                  <ul>
                     
                     <li class="footer__footer-item"><a class="footer__footer-link " href="#">All Categories</a></li>
                  </ul>
               </div>-->
            </div>
         </div>
         <div class="footer__social-section">
            <div class="c-clearfix container">
               
               <div id="copyright" class="footer__copyright">
                  <span class="footer__copyright-text">
                  Copyright ©  <?php echo date("Y"); ?> Kwik Basket. All Rights Reserved. </span>
               </div>
            </div>
         </div>
      </div>
      <!-- END METAORGANIC FOOTER -->
   
  </footer>
