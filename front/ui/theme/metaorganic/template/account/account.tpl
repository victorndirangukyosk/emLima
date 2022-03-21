<?php echo $header; ?>
                      <div class="container">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row">
    <div id="content" class="account-section">
      <?php echo $content_top; ?>
      <form  autocomplete="off" action="<?php echo $action; ?>" id="account-edit-form" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="secion-row">
      <br />
      
        <fieldset>
            <div class="form-group required has-feedback">
                <label for="name" class="col-sm-3 control-label"><?= $entry_firstname ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $firstname; ?>" size="30" placeholder="First Name" name="firstname" maxlength="100" id="name" class="form-control input-lg" readonly/>
                     <?php if($error_firstname) { ?>
                      <div class="text-danger"><?php echo $error_firstname; ?></div>
                      <?php } ?>
                </div>
            </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-6 col-xs-12">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control input-lg" readonly/>
              <?php if($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-6 col-xs-12">
              <input type="email" name="email" id="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control input-lg" readonly/>
              <?php if($error_email) { ?>
               <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>


<div class="form-group required has-feedback">
                <label for="name" class="col-sm-3 control-label"><?= $entry_companyname ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $companyname; ?>" size="30" placeholder="Company Name" name="companyname" maxlength="100" id="name" class="form-control input-lg" readonly/>
                     <?php if($error_companyname) { ?>
                      <div class="text-danger"><?php echo $error_companyname; ?></div>
                      <?php } ?>
                </div>
            </div>


            <div class="form-group required has-feedback">
                <label for="name" class="col-sm-3 control-label"><?= $entry_companyaddress ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $companyaddress; ?>" size="30" placeholder="Company Address" name="companyaddress" maxlength="100" id="name" class="form-control input-lg" />
                     <?php if($error_companyaddress) { ?>
                      <div class="text-danger"><?php echo $error_companyaddress; ?></div>
                      <?php } ?>
                </div>
            </div>


          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_phone; ?></label>

            <div class="col-sm-6 col-xs-12 input-group" style="padding-right: 15px;padding-left: 15px;">

                <span class="input-group-btn">

                   <!-- <p id="button-reward" class="phonesetbut" >

                        <font style="vertical-align: inherit;">
                          <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                            +<?= $this->config->get('config_telephone_code') ?>                                                
                          </font></font></font>
                        </font>
                    </p>-->
                    <p  class="phonesetbut" >

                        <font style="vertical-align: inherit;">
                          <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                            +<?= $this->config->get('config_telephone_code') ?>                                                
                          </font></font></font>
                        </font>
                    </p>

                </span>

                <input type="tel" name="telephone" id="tel" value="<?php echo $telephone; ?>"  id="input-telephone" class="form-control input-lg" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" readonly/>

              <?php if ($error_telephone) { ?>
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-date-added"><?php echo $entry_dob; ?></label>
            <div class="col-sm-6 col-xs-12">
                <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="<?php echo $entry_dob; ?>" data-date-format="dd/mm/YYYY" id="input-date-added" class="form-control date" />
                <?php if ($error_dob) { ?>
              <div class="text-danger"><?php echo $error_dob; ?></div>
              <?php } ?>
            </div>
          </div>
          
  

 <div class="form-group  has-feedback">
                <label for="name" class="col-sm-3 control-label"><?= $entry_fax ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $fax; ?>" size="30" placeholder="Tax No" name="fax" maxlength="100" id="name" class="form-control input-lg" />
                     <?php if(isset($error_fax) && $error_fax) { ?>
                      <div class="text-danger"><?php echo $error_fax; ?></div>
                      <?php } ?>
                </div>
            </div>
          

          <!-- <div class="form-group">
            <label class="col-sm-3 control-label" for="input-date-added"><?php echo $entry_fax; ?></label>
            <div class="col-sm-6 col-xs-12">
                <input type="text" name="fax" id="tax_number" value="<?php echo $fax; ?>" placeholder="<?php echo $taxnumber_mask; ?>" class="form-control" />

              <?php if ($error_tax) { ?>
                <div class="text-danger"><?php echo $error_tax; ?></div>
              <?php } ?>

            </div>
          </div> -->
          <input type="hidden" name="tax" id="tax_number" value="" placeholder="<?php echo $taxnumber_mask; ?>" class="form-control" />

          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_gender; ?></label>
            <div class="col-sm-6 col-xs-12">
                <label class="control control--radio" style="display: initial !important;"> 
                    <?php if($gender == 'male') {?> 
                        <input type="radio" name="gender" data-id="8" value="male" checked="checked"> <?= $text_male ?> 
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="8" value="male"> <?= $text_male ?> 
                    <?php } ?>

                    <div class="control__indicator"></div>
                </label>

                 <label class="control control--radio" style="display: initial !important;">
                    <?php if($gender == 'female') {?> 
                        <input type="radio" name="gender" data-id="9" value="female" checked="checked"> <?= $text_female ?>
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="9" value="female"> <?= $text_female ?> 
                    <?php } ?>

                   
                   <div class="control__indicator"></div>
                   </label>

                 <label class="control control--radio" style="display: initial !important;">
                    <?php if($gender == 'other') {?> 
                        <input type="radio" name="gender" data-id="8" value="other" checked="checked"> <?= $text_other ?>
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="8" value="other"> <?= $text_other ?> 
                    <?php } ?>

                   
                   <div class="control__indicator"></div>
                 </label>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-nationalid"><?php echo $entry_national_id; ?></label>
            <div class="col-sm-6 col-xs-12">
                <input type="text" name="national_id" id="national_id" placeholder="<?php echo $entry_national_id; ?>" value="<?php echo $national_id; ?>" class="form-control input-lg" />
              <?php if ($error_national_id) { ?>
              <div class="text-danger"><?php echo $error_national_id; ?></div>
              <?php } ?>
            </div>
          </div>          
                    
          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_password; ?></label>
            <div class="col-sm-6 col-xs-12">
                <input type="password" name="password" id="password" autocomplete="new-password" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control input-lg" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-password"><?php echo $entry_confirmpassword; ?></label>
            <div class="col-sm-6 col-xs-12">
                <input type="password" name="confirmpassword" id="confirmpassword" placeholder="<?php echo $entry_confirmpassword; ?>" id="input-confirmpassword" class="form-control input-lg" />
              <?php if ($error_confirmpassword) { ?>
              <div class="text-danger"><?php echo $error_confirmpassword; ?></div>
              <?php } ?>
            </div>
          </div>

          <?php if ($site_key) { ?>
          <div class="form-group  ">
            <label class="col-sm-3 control-label" for="input-date-added"></label>
            <div class="col-sm-6 col-xs-12 pl0 pr0">
              
                  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" style="padding-left:16px"></div>
                  <?php if ($error_captcha) { ?>
                  <div class="text-danger"><?php echo $error_captcha; ?></div>
                  <?php } ?>
            </div>
             
            </div>
             <?php } ?>
           
        </fieldset>
      
      </div>
      <div class="col-sm-1 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 23px">
              <button type="submit" data-style="zoom-out" id="save-button" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
            </div>
      </div>
      </form>
    </div>
</div>
       

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
<?php echo $footer; ?>    
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);


    var page_category = 'my-account-page';
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                    clear = true;
               }
             if (clear) {
            clearInterval(intervalID);
         }
        },
        period);
        })(page_category);


</script>

<?php } ?>
<script type="text/javascript">

$('button[id^=\'button-custom-field\']').on('click', function() {
    var node = this;
    
    $('#form-upload').remove();
    
    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

    $('#form-upload input[name=\'file\']').trigger('click');
    
    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }
    
    timer = setInterval(function() {
        if ($('#form-upload input[name=\'file\']').val() != '') {
            clearInterval(timer);
            
            $.ajax({
                url: 'index.php?path=tool/upload',
                type: 'post',       
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,     
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');            
                },      
                success: function(json) {
                    $(node).parent().find('.text-danger').remove();
                    
                    if (json['error']) {
                        $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                    }
                                
                    if (json['success']) {
                        alert(json['success']);
                        
                        $(node).parent().find('input').attr('value', json['code']);
                    }
                },          
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});
//--></script> 
<!--  jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<!-- <link rel="stylesheet" href="<?= $base ?>front/ui/theme/mvgv2/css/bootstrap-iso.css" /> -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

<!-- <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $base ?>front/ui/theme/mvgv2/css/bootstrap-datepicker3.min.css"/> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    
<script type="text/javascript">

    /*jQuery(function($){
        console.log("signup mask");
       $("#tel").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    });*/
    /*jQuery(function($){
        console.log("mask");
       $("#tel").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    jQuery(function($) {
        console.log("tax mask");
       $("#tax_number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    

    $('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });   
</script> 
                 

    <?php if($redirect_coming) { ?>
      <script type="text/javascript">
        $('#save-button').click();
      </script>
      
    <?php } ?>
</body>
</html>