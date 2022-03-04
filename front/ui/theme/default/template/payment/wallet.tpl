<!-- <button type="button" id="button-confirm" class="btn-account-checkout btn-large btn-orange">CONFIRM ORDER</button>
 -->
<button type="button" id="button-confirm" data-toggle="collapse" data-loading-text="<?= $text_loading ?>" class="btn btn-default"><?= $button_confirm?></button>

<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
    
    //location = '<?php echo $continue; ?>';

    
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
        url: 'index.php?path=payment/wallet/confirm',
        cache: false,
        beforeSend: function() {
                $(".overlayed").show();
                $('#button-confirm').button('loading');
                //$('#button-confirm').attr('disabled',true);
                //$(this).css('background-color','#b2d025');
        },
        complete: function() {
                $(".overlayed").hide();
                //$('#button-confirm').removeAttr('disabled');
                $('#button-confirm').button('reset');
                $('#loading').hide();
        },      
        success: function() {
                location = '<?php echo $continue; ?>';
        }       
    });
});
//--></script> 
