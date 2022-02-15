    
<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($payment_methods) { ?>
<div class="select-locations" >
    <?php if(isset($payment_wallet_methods)) { ?>
    <label for="payment_<?= $payment_wallet_methods['code'] ?>" class="control control--checkbox"><?php echo $payment_wallet_methods['title']; ?> <span style="font-size: 10px;vertical-align: middle;"><?php echo $payment_wallet_methods['terms1']; ?></span>

        <input type="checkbox" class="control control--checkbox" id="payment_<?= $payment_wallet_methods['code'] ?>" name="payment_wallet_method" value="<?php echo $payment_wallet_methods['code']; ?>" checked="checked" />
        
        <div class="control__indicator paymentwallet"></div>      
    </label>
    <?php } ?>
<?php $i = 0; foreach ($payment_methods as $payment_method) { ?>
    
    <?php if($i++ == 0) { ?>
        <label for="payment_<?= $payment_method['code'] ?>" class="control control--radio"><?php echo $payment_method['title']; ?> <span style="font-size: 10px;vertical-align: middle;"><?php   echo $payment_method['terms1']; ?></span>

        <input type="radio" class="control control--radio" id="payment_<?= $payment_method['code'] ?>" name="payment_method" value="<?php echo $payment_method['code']; ?>"/>
        
        <div class="control__indicator"></div>      
        </label>
    <?php } else { ?>

        <label for="payment_<?= $payment_method['code'] ?>" class="control control--radio"><?php echo $payment_method['title']; ?> <span style="font-size: 10px;vertical-align: middle;"><?php   echo $payment_method['terms1']; ?></span>
        
        <input type="radio" class="control control--radio" id="payment_<?= $payment_method['code'] ?>" name="payment_method" value="<?php echo $payment_method['code']; ?>" />
        
        <div class="control__indicator"></div>      
        </label>

    <?php } ?>
    

<?php } ?>
</div>
<?php } ?>

<script type="text/javascript"><!--
$(document).on('change', 'input[name=\'payment_method\']:checked, input[name=\'payment_wallet_method\']', function () {
var clickedName = $(this).attr('name');
$.ajax({
                url: 'index.php?path=checkout/payment_method/CheckWalletBalanceCartAmountAreSame',
                type: 'post',
                dataType: 'json',
                cache: false,
                async: false,
                complete: function() {
                
                },
                beforeSend: function() {

                },
                success: function(json) {
                if(json.wallet_cart_amount_same == true) {
                
                if(clickedName == 'payment_wallet_method' && $('input[name="'+clickedName+'"]:checked').length > 0) {
                $('input[name=payment_method]:checked').prop('checked', false);
                }
                
                if(clickedName == 'payment_method' && $('input[name="'+clickedName+'"]:checked').length > 0) {
                $('input[name=payment_wallet_method]:checked').prop('checked', false);
                }
                
                }
                
                savePaymentMethod();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
});
//--></script>
