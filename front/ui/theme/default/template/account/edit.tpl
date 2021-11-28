<div class="container">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="row">
    <div id="content" class="account-section">
      <?php echo $content_top; ?>
      <form action="<?php echo $action; ?>" id="account-edit-form" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="secion-row">
      <br />
      
        <fieldset>
            <div class="form-group required has-feedback">
                <label for="name" class="col-sm-4 control-label"><?= $entry_firstname ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $firstname; ?>" size="30" placeholder="First Name" name="firstname" maxlength="100" id="name" class="form-control input-lg" />
                     <?php if($error_firstname) { ?>
                      <div class="text-danger"><?php echo $error_firstname; ?></div>
                      <?php } ?>
                </div>
            </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-6">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control input-lg" />
              <?php if($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-6">
              <input type="email" name="email" id="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control input-lg" />
              <?php if($error_email) { ?>
               <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-6">
                <input type="tel" name="telephone" id="tel" value="<?php echo $telephone; ?>" placeholder="<?= $telephone_mask ?>" id="input-telephone" class="form-control input-lg" />
              <?php if ($error_telephone) { ?>
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-date-added"><?php echo $entry_dob; ?></label>
            <div class="col-sm-6">
                <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="<?php echo $entry_dob; ?>" data-date-format="dd/mm/YYYY" id="input-date-added" class="form-control date" />
                <?php if ($error_dob) { ?>
              <div class="text-danger"><?php echo $error_dob; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group">
          <label class="col-sm-4 control-label" for="input-telephone"><?php echo $entry_gender; ?></label>
            <div class="col-sm-6 ">
              <label class="control control--radio"> 
                <?php if($gender == 'male') {?> 
                    <input type="radio" name="gender" data-id="8" value="male" checked="checked"> <?= $text_male ?> 
                <?php } else {?>
                <input type="radio" name="gender" data-id="8" value="male"> <?= $text_male ?> 
                <?php } ?>

                <div class="control__indicator"></div>
              </label>

               <label class="control control--radio">
                <?php if($gender == 'female') {?> 
                    <input type="radio" name="gender" data-id="9" value="female" checked="checked"> <?= $text_female ?>
                <?php } else {?>
                <input type="radio" name="gender" data-id="9" value="female"> <?= $text_female ?> 
                <?php } ?>

                 
                 <div class="control__indicator"></div>
                 </label>

               <label class="control control--radio">
                <?php if($gender == 'other') {?> 
                    <input type="radio" name="gender" data-id="8" value="other" checked="checked"> <?= $text_other ?>
                <?php } else {?>
                <input type="radio" name="gender" data-id="8" value="other"> <?= $text_other ?> 
                <?php } ?>

                 
                 <div class="control__indicator"></div>
               </label>
            </div>
          </div>

          <input type="hidden" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
           
        </fieldset>
      
      </div>
      <div class="secion-row text-center" style="margin-bottom: 20px;">
              <button type="submit" data-style="zoom-out" id="save-button" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
            </div>
      </div>
      </form>
    </div>
</div>

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

<!-- Isolated Version of Bootstrap, not needed if your site already uses Bootstrap -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

<!-- Bootstrap Date-Picker Plugin -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
    
<script type="text/javascript">

  /*jQuery(function($){
        console.log("signup mask");
       $("#tel").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    });*/
    jQuery(function($){
        console.log("mask");
       $("#tel").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });

    $('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });    
</script>