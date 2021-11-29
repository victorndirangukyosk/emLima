<button type="button" id="button-confirm" data-toggle="collapse" data-loading-text="loading......." class="btn btn-default"><?= $button_confirm?></button>

<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {

        if (!saveAddress()) {
            return false;
        }

        $.ajax({
            type: 'get',
            url: 'index.php?path=payment/free_checkout/confirm',
            cache: false,
            beforeSend: function() {
                //$(".overlayed").show();
                $('#button-confirm').button('loading');
            },
            complete: function() {
                //$(".overlayed").hide();
                $('#button-confirm').button('reset');
            },
            success: function() {
                location = '<?php echo $continue; ?>';
            }
        });
    });
//--></script> 