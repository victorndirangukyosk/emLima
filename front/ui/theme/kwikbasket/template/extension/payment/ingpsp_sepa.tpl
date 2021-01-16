<h2><?php echo $ing_bank_details; ?></h2>
<p><b><?php echo $text_description; ?></b></p>

<!-- <form action=<?php echo $action; ?> method="get"> -->
    <div class="well well-sm">
        <p><?php echo $ing_payment_reference; ?></p>
        <p><?php echo $ing_iban; ?></p>
        <p><?php echo $ing_bic; ?></p>
        <p><?php echo $ing_account_holder; ?></p>
        <p><?php echo $ing_residence; ?></p>
    </div>

    <!-- <div class="buttons pull-right">
        <div class="right">
            <input type="submit" value="<?php echo $button_confirm; ?>" class="button btn btn-primary"/>
        </div>
    </div> -->
<!-- </form> -->

<button type="button" id="button-confirm" data-toggle="collapse" data-loading-text="loading......." class="btn btn-default"><?= $button_confirm?></button>

<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
    
    if (!saveAddress()) { 
        return false; 
    }
    
    location = '<?php echo $action; ?>';
    
    /*$.ajax({
        type: 'get',
        url: 'index.php?path=payment/ingpsp_cc/confirm',
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
        },      
        success: function() {
               location = '<?php echo $continue; ?>'; 
        }       
    });*/
});
//--></script> 
