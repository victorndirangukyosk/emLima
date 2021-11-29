<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<div class="select-locations">
<?php foreach ($shipping_methods as $shipping_method) { ?>

<?php if (!$shipping_method['error']) { ?>
    <?php foreach ($shipping_method['quote'] as $quote) { ?>    
        <label class="control control--radio">
            <?php if ($quote['code'] == $code || !$code) { ?>
            <?php $code = $quote['code']; ?>

            <input type="radio" name="shipping_method-<?php echo $store_id ?>"   data-id="<?php echo $store_id ?>"  value="<?php echo $quote['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="shipping_method-<?php echo $store_id ?>"   data-id="<?php echo $store_id ?>"    value="<?php echo $quote['code']; ?>" />
            <?php } ?>        
            <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?>
            <?php if($quote['code']=='express.express'){?>
             <span style="font-size:15px;font-weight:bold">( Delivery possible with 5 hours turn around time and subject to products availability )</span>
            <?php }?>
            <div class="control__indicator"></div>
        </label>
    <?php } ?><!-- END foreach -->

<?php }else{ ?><!-- END if not error -->
<div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
<?php } ?><!-- END if error -->
<?php } ?><!-- END foreach shipping-methods -->
</div>
<?php } ?><!-- if shipping-methods -->
<script type="text/javascript"><!--
$(document).on('change', 'input[name=\'shipping_method-<?php echo $store_id ?>\']:checked', function(e) {

    
    $(this).prop("checked", true);
    //alert($(this).attr('value'));
        
    console.log("shipping_method js");
    $('#checkout-total-wrapper').html('<center><div class="login-loader" style=""></div></center>');
    
    saveShippingMethod(<?php echo $store_id ?>);
});
//--></script>