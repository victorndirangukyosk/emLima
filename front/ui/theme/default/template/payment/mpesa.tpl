<h2><?php echo $text_instruction; ?></h2>
<h3 style="color: #f97900;">Please enter mpesa registered mobile number</h3>
<div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px;">
</div>
<div class="alert alert-success" style="font-size: 14px;" id="success_msg" style="margin-bottom: 7px;">
</div>

  <span class="input-group-btn" style="padding-bottom: 10px;">

    <p id="button-reward" class="" style="padding: 13px 14px;    margin-top: -8px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

        <font style="vertical-align: inherit;">
          <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
            +<?= $this->config->get('config_telephone_code') ?>                                                
          </font></font></font>
        </font>
    </p>

<input id="mpesa_phone_number" name="telephone" type="text" value="<?= $customer_number?>" class="form-control input-md" required="" placeholder="Mobile number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" style="display: inline-block;    width: 22%;" >

</span>

<button type="button" id="button-confirm" data-toggle="collapse" data-loading-text="checking phone..." class="btn btn-default" disabled="">PAY &amp; CONFIRM</button>

<button type="button" id="button-retry" class="btn btn-default"> Retry</button>

<button type="button" id="button-complete" data-toggle="collapse" data-loading-text="checking payment..." class="btn btn-default">Confirm Payment</button>


<script type="text/javascript">

	$('#error_msg').hide();
	$('#success_msg').hide();
	$('#button-complete').hide();
	$('#button-retry').hide();
	
	$( document ).ready(function() {

		console.log("referfxx def");
		if($('#mpesa_phone_number').val().length >= 9) {
	    	$( "#button-confirm" ).prop( "disabled", false );
	    } else {
	    	$( "#button-confirm" ).prop( "disabled", true );
	    }
	});

	$('#mpesa_phone_number').on('input', function() { 
	    
		console.log("referfxx");
	    if($(this).val().length >= 9) {
	    	$( "#button-confirm" ).prop( "disabled", false );
	    } else {
	    	$( "#button-confirm" ).prop( "disabled", true );
	    }
	});

	$('#button-confirm,#button-retry').on('click', function() {
	    
	    $('#loading').show();

	    $('#error_msg').hide();
            
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
	    if($('#mpesa_phone_number').val().length >= 9) {
	    	$.ajax({
		        type: 'post',
		        url: 'index.php?path=payment/mpesa/confirm',
		        data: 'mobile=' + encodeURIComponent($('#mpesa_phone_number').val()),
	            dataType: 'json',
		        cache: false,
		        beforeSend: function() {
		            $(".overlayed").show();
		            $('#button-confirm').button('loading');
		        },
		        complete: function() {
		            $(".overlayed").hide();
		        },      
		        success: function(json) {

		        	console.log(json);
		        	console.log('json mpesa');

		        	$('#button-confirm').button('reset');
		            $('#loading').hide();

		        	if(json['processed']) {
		        		//location = '<?php echo $continue; ?>';
		        		
		        		//$('#success_msg').html('A payment request has been sent to the mpesa number '+$('#mpesa_phone_number').val()+'. Please wait for a few seconds then check for your phone for an MPESA PIN entry prompt.');

		        		$('#success_msg').html('A payment request has been sent on your above number. Please make the payment by entering mpesa PIN and click on Confirm Payment button after receiving sms from mpesa');


		        		
		        		$('#success_msg').show();

		        		
		        		$('#button-complete').show();

		        		console.log('json mpesa1');
		        		$('#button-confirm').hide();
		        		$('#button-retry').hide();
		        		console.log('json mpesa2');

		        	} else {
		        		console.log('json mpesa err');
		        		console.log(json['error']);
		        		$('#error_msg').html(json['error']);
		        		$('#error_msg').show();
		        	}
		            
		        },
		        error: function(json) {

		        	console.log('josn mpesa');
		        	console.log(json);

		        	$('#error_msg').html(json['responseText']);
		        	$('#error_msg').show();
		        }
		    });
	    }
	});

	$('#button-complete').on('click', function() {
	    
	    $('#error_msg').hide();
	    $('#success_msg').hide();

    	$.ajax({
	        type: 'post',
	        url: 'index.php?path=payment/mpesa/complete',
            dataType: 'json',
	        cache: false,
	        beforeSend: function() {
	            $(".overlayed").show();
	            $('#button-complete').button('loading');
	        },
	        complete: function() {
	            $(".overlayed").hide();
	            $('#button-complete').button('reset');
	        },      
	        success: function(json) {

	        	console.log(json);
	        	console.log('json mpesa');
	        	if(json['status']) {
	        		//success
	        		
	        		$('#success_msg').html('Payment Successfull.');
	        		$('#success_msg').show();

	        		setTimeout(function(){ location = '<?php echo $continue; ?>'; }, 1500);

	        	} else {

	        		//failed
	        		//$('#button-confirm').show();
	        		//$('#button-retry').hide();
	        		//$('#button-complete').hide();

	        		$('#error_msg').html(json['error']);
	        		$('#error_msg').show();

	        		$('#button-complete').hide();
	        		$('#button-retry').show();

	        	}
	            
	        },
	        error: function(json) {
	        	$('#error_msg').html(json['responseText']);
	        	$('#error_msg').show();
	        }
	    });
	});

</script> 
