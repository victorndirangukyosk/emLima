<?php echo $header; ?>
                      <div class="container">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row">
    <div id="content" class="account-section">
      <?php echo $content_top; ?>
      <form action="<?php echo $action; ?>" id="profileinfo-edit-form" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="secion-row">
      <br />
      
        <fieldset>
            <div class="form-group required has-feedback">
                <label for="name" class="col-sm-4 control-label"><?= $entry_location ?></label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $location; ?>" size="30" placeholder="<?= $entry_location ?>" name="location" id="input-location" class="form-control input-lg" />
                     <?php if($error_location) { ?>
                      <div class="text-danger"><?php echo $error_location; ?></div>
                      <?php } ?>
                </div>
            </div>
          <div class="form-group required has-feedback">
            <label class="col-sm-4 control-label" for="input-lastname"><?php echo $entry_requirement; ?></label>
            <div class="col-sm-6">
              <input type="text" name="requirement" value="<?php echo $requirement; ?>" placeholder="<?php echo $entry_requirement; ?>" id="input-requirement" class="form-control input-lg" />
              <?php if($error_requirement) { ?>
              <div class="text-danger"><?php echo $error_requirement; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-email"><?php echo $entry_mandatory_products; ?></label>
            <div class="col-sm-6">
              <input type="text" name="mandatory_products" id="input-mandatory_products" value="<?php echo $mandatory_products; ?>" placeholder="<?php echo $entry_mandatory_products; ?>" class="form-control input-lg" />
              <?php if($error_mandatory_products) { ?>
               <div class="text-danger"><?php echo $error_mandatory_products; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
      
      </div>
      <div class="secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 14.7%;">
              <button hidden type="submit" data-style="zoom-out" id="save-button" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
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
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    
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


<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

 

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