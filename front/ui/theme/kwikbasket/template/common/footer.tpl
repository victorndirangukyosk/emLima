<style>
   .copyright {
      background-color: #fff;
      font-size: 0.9rem !important;
      padding: 2rem 0px;
   }

   .copyright .content-container {
      width: 100%;
      display: flex;
      flex-flow: row wrap;
      justify-content: space-between;
   }

   .copyright .content-container ul {
      list-style: none;
      display: flex;
      flex-direction: row nowrap;
      justify-content: space-around;
      margin: 0;
      padding: 0;
   }

   .content-container ul li {
      text-decoration: none;
   }

   .content-container ul a {
      color: #6c757d;
   }

   .content-container ul a:hover {
      text-decoration: none;
   }
</style>

<footer>
   <div class="copyright">
      <div class="container">
         <div class="row" style="margin-bottom: 1rem">
            <div class="col-md-12">
               <a href="<?= $play_store ?>" target="_blank">
                  <img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/google-play.svg" alt="Download the KwikBasket Android app for free" width="135" height="40">
               </a>
            </div>
         </div>
         <div class="row">
            <div class="content-container col-md-12">
               <p class="text-muted">© 2020 KwikBasket | All Rights Reserved</p>
               <ul>
                  <li style="margin-right: 8px"><a href='<?= BASE_URL."/index.php?path=common/home/terms_and_conditions" ?>'>Terms &
                        Conditions</a></li>
                  <li><a href='<?= BASE_URL."/index.php?path=common/home/privacy_policy" ?>'>Privacy Policy</a></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</footer>