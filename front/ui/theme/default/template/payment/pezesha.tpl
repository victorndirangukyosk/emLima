<div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px;"></div>
<div class="alert alert-success" style="font-size: 14px;" id="success_msg" style="margin-bottom: 7px;"></div>
<button type="button" id="button-pezesha-confirm" data-toggle="collapse" data-loading-text="<?= $text_loading ?>" class="btn btn-default"><?= $button_confirm?></button>

<script type="text/javascript">
$('#error_msg').hide();
$('#success_msg').hide();
$('#button-pezesha-confirm').on('click', function() {
    
    //location = '<?php echo $continue; ?>';
    $('#error_msg').hide();
    $('#success_msg').hide();
    
    $('#loading').show();

    if (!saveAddress()) { 
        return false; 
    }
    


    $.ajax({
        type: 'get',
        url: 'index.php?path=payment/pezesha/applyloan',
        dataType: 'json',
        cache: false,
        beforeSend: function() {
                $(".overlayed").show();
                $('#button-pezesha-confirm').button('loading');
        },
        complete: function() {
                $(".overlayed").hide();
                $('#button-pezesha-confirm').button('reset');
                $('#loading').hide();
        },      
        success: function(json) {
           console.log(json); 
           if(json.status) {
           location = '<?php echo $continue; ?>';
           }
           
           if(!json.status) {
           $('#error_msg').html(json.message);
           $('#error_msg').show();    
           console.log(json);     
           }

        }       
    });
});
</script> 
