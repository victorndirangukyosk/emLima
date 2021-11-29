
<input type="hidden" name="key" value="<?php echo $key; ?>" />
<input type="hidden" name="txnid" value="<?php echo $txnid; ?>" />
<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
<input type="hidden" name="productinfo" value="<?php echo $productinfo; ?>" />
<input type="hidden" name="firstname" value="<?php echo $firstname; ?>" />
<input type="hidden" name="Lastname" value="<?php echo $Lastname; ?>" />
<input type="hidden" name="Zipcode" value="<?php echo $Zipcode; ?>" />
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="phone" value="<?php echo $phone; ?>" />
<input type="hidden" name="surl" value="<?php echo $surl; ?>" />
<input type="hidden" name="Furl" value="<?php echo $Furl; ?>" />
<input type="hidden" name="curl" value="<?php echo $curl; ?>" />
<input type="hidden" name="Hash" value="<?php echo $Hash;?>" />
<input type="hidden" name="Pg" value="<?php echo $Pg; ?>" />

<button type="button" id="button-confirm" class="btn-account-checkout btn-large btn-orange">CONFIRM ORDER</button>

<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {

		console.log("payu button clif");
        if (!saveAddress()) {
            return false;
        }

        $('#place-order-form').attr('action','<?php echo $action ?>').submit();
    });
//--></script> 