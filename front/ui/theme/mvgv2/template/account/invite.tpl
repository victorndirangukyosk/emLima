<?php echo $header; ?>

                
<div class="dashboard-wrapper">

   <div class="container">
        
        <div class="row">
          
            <div class="col-md-12">

                <center> <?= $invitation_text ?> </center>

            </div>
        </div>

      
        <div class="row">
          
            <div class="col-md-12">

                <?php if($referral_data['referred']) { ?>
                    <center>*<?=$referral_data['referred'] ?></center>
                <?php } ?> 

                <center>

                    <a href="#" type="button" class="btn btn-primary" type="button" data-toggle="modal" data-target="#signupModal-popup"><i class="fa fa-user-plus"></i><?= $text_register ?></a>
                </center>

            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $footer; ?>

<?= $login_modal ?>
<?= $signup_modal ?>
<?= $forget_modal ?>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>


    <script src="<?= $base;?>front/ui/javascript/home.js?v=1.0.4"></script> 
    <script src="<?= $base;?>front/ui/javascript/bxslider/jquery.bxslider.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.20.4/sweetalert2.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
   <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/js/drawer.min.js" type="text/javascript"></script>

    <!-- <script src="<?= $base; ?>front/ui/theme/mvgv2/javascript/common.js?v=2.0.7" charset="UTF-8" type="text/javascript"></script> -->

    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style type="text/css">
        
        @keyframes highlight {
          0% {
            background: #FFD700
          }
          100% {
            background: none;
          }
        }

        #welcome-login {
          animation: highlight 2s;
        }

    </style>

    <!-- <script src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap-datepicker.pt-BR.js"></script> -->
    <script src="https://cdn.jsdelivr.net/bootstrap.datepicker-fork/1.3.0/js/locales/bootstrap-datepicker.pt-BR.js"></script>

<script type="text/javascript">


    $('.zipcode-enter').focus();

    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        language: '<?php echo $config_language ?>'
    });
   

    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });


    $(document).delegate('#test-drawer', 'click', function(e) {
        $('.drawer-nav').addClass('how-it-works-open');
        $('.header-transparent').addClass('how-it-works-open');
        $('.hero-image').addClass('how-it-works-open');
        
    });
    
    $("body").on("click",function(e) {
        if ($('.how-it-works-open').length >= 1) {
            $('.drawer-nav').removeClass('how-it-works-open'); 
            $('.drawer.drawer--top').removeClass('drawer-open');
            $('.header-transparent').removeClass('how-it-works-open');
            $('.hero-image').removeClass('how-it-works-open');
        }
    });
    $(document).delegate('.how-it-works-close', 'click', function(e) {
        $('.drawer-nav').removeClass('how-it-works-open'); 
        $('.drawer.drawer--top').removeClass('drawer-open');
        $('.header-transparent').removeClass('how-it-works-open');
        $('.hero-image').removeClass('how-it-works-open');
    });
    
    function validateInp(elem) {
        var validChars = /[0-9]/;
        var strIn = elem.value;
        var strOut = '';
        for(var i=0; i < strIn.length; i++) {
          strOut += (validChars.test(strIn.charAt(i)))? strIn.charAt(i) : '';
        }
        elem.value = strOut;
    }

</script>
</body>
</html>
