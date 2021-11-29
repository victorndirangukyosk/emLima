<?php echo $header; ?>

<div role="main" id="main" class="account-section container" style="min-height: 350px;">
    <div class="row mobile-title">
        <div class="col-md-12">
            <div class="content-wrapper with-padding static-page-heading">
                <h2><?= $text_heading1 ?></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="content-wrapper with-padding">
            <div class="col-md-12">
                <div class="u-textCenter text-center">
                    <h2><?= $text_heading2 ?></h2>
                    <p class="lead"><?= $text_find ?> <?= $this->config->get('config_name') ?> <?= $text_delivers ?></p>
                </div>
                <div class="text-center u-textCenter">
                    <form class="form-inline" id="location_form" onsubmit="return false;">
                        <div class="form-group">
                            <input type="text" autocomplete="off" placeholder="<?= $entry_zipcode ?>" class="span2 centered" value="" id="zip_code" name="zip_code">
                        </div>
                        <div class="form-group">
                            <button type="button" class="ic-btn ic-btn-primary btn-check-zip centered">
                                <?= $button_check ?> 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?> 

<script>
    function locations(){
        if($('#zip_code').val().length > 0){
            location = '<?= $action ?>&zipcode='+$('#zip_code').val();
        }else{
            $('#zip_code').css('border','1px solid red');
        }
    }
    
    $('.btn-check-zip').click(function(){
        locations();
    });
    
    $('#zip_code').keypress(function (e) {
        var key = e.which;
        if(key == 13){
            locations();
        }
    });
</script>