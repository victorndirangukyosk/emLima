<h2><?php echo $text_instruction; ?></h2>
<p><b><?php echo $text_description; ?></b></p>
<div class="well well-sm">
  <p><?php echo $bank; ?></p>
  <p><?php echo $text_payment; ?></p>
</div>
<div class="buttons">
  <div class="pull-right">
     <button type="button" id="button-confirm" class="btn-account-checkout btn-large btn-orange">CONFIRM ORDER</button>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
    
    if (!saveAddress()) { return false; }

        $.ajax({
		type: 'get',
		url: 'index.php?path=payment/bank_transfer/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},		
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
