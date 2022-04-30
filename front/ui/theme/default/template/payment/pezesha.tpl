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
    

    $('.alert-warning').remove();
    var payment_method = $('input[name=\'payment_method\']:checked').attr('value');
    var payment_wallet_method = $('input[name=\'payment_wallet_method\']:checked').attr('value');
    if(payment_method == null && payment_wallet_method == null) {
    $('#payment-method-wrapper').prepend('<div class="alert alert-warning">' + 'Please Select Atleast One Payment Method!' + '<button type="button" class="close" data-dismiss="alert" style="width:1% !important;">&times;</button></div>');
    $('#button-confirm').removeAttr('disabled');
    $('#button-confirm').button('reset');
    $('#loading').hide();
    return false;
    }
    $.ajax({
        type: 'get',
        url: 'index.php?path=payment/pezesha/applyloanone',
        dataType: 'json',
        cache: false,
        beforeSend: function() {
                $(".overlayed").show();
                $('#button-pezesha-confirm').button('loading');
        },
        complete: function() {
                console.log($("#button-pezesha-confirm").attr("data-success"));
                console.log(typeof $("#button-pezesha-confirm").attr("data-success"));
                
                $(".overlayed").show();
                $('#button-pezesha-confirm').button('loading');
                $('#loading').hide();
                
                if($("#button-pezesha-confirm").attr("data-success") === 'true') {
                $(".overlayed").show();
                $('#button-pezesha-confirm').button('loading');
                $('#loading').hide();
                } 
                
                if($("#button-pezesha-confirm").attr("data-success") === 'false') {
                console.log($("#button-pezesha-confirm").attr("data-success"));
                $('#button-pezesha-confirm').button('reset');
                }
        },      
        success: function(json) {
           if(json.status) {
           console.log(json); 
           $(".overlayed").show();
           $('#button-pezesha-confirm').button('loading');  
           $("#button-pezesha-confirm").attr("data-success", true);
           location = '<?php echo $continue; ?>';
           }
           
           if(!json.status) {
           $('#button-pezesha-confirm').button('reset');
           $("#button-pezesha-confirm").attr("data-success", false);
           $('#error_msg').html(json.message);
           $('#error_msg').show(); 
           }

        }       
    });
});
</script> 
