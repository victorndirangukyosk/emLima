<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="">
                    <img style="max-height: 50px !important;" alt="Logo" src="<?= $logo ?>" />
                </div>
                <div class="brand-message" style="clear: both;">
                    <?= $label_text ?>
                </div>
            </div>
            <div class="col-md-4">
                <ul class="social">
                    <li>
                        <a href="<?= $facebook ?>" target="_blank">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $twitter ?>" target="_blank">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $google ?>" target="_blank">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="list">
                    <?php foreach ($informations as $information) { ?>
                    <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                    <?php } ?>
                </ul>
                
                <ul class="small-list">
                    <li><a href="<?php echo $this->url->link('information/locations'); ?>"><?= $locations ?></a></li>
                    <li><a href="<?php echo $this->url->link('blog/article'); ?>"><?= $blog ?></a></li>
                    <li><a href="<?php echo $this->url->link('information/contact'); ?>"><?= $contactus ?></a></li>
                    <li><a href="<?php echo $this->url->link('information/enquiries'); ?>"><?= $list_your_products ?></a></li>
                    <li><a href="<?php echo $this->url->link('information/shopper'); ?>"><?= $become_shopper ?></a></li>                    
                </ul>                
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small>
                   <?= $powered ?>
                </small>
            </div>
        </div>
    </div>
</div>

<div class="shop-info-container">
    <!-- Shop info will goes here -->
</div>

<script>
//manage height 
$(document).ready(function(){
    $height = $(window).height() - $('.header').height() - $('.footer').height();
    $('#main').css('min-height', $height);
});
</script>

</body>

</html>