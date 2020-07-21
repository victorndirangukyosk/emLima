<?php //echo '<pre>';print_r($_SESSION);exit;?>
<?php echo $header; ?>
                      <div class="container">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row">
  <div class="col-md-9">
	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#pending">Pending Payments</a></li>
	  <li><a data-toggle="tab" href="#successfull">Successfull Payments</a></li>
	  <li><a data-toggle="tab" href="#cancelled">Cancelled Payments</a></li>
	</ul>

		<div class="tab-content">
		  <div id="pending" class="tab-pane fade in active">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>Order Id </th>
					<th>Order Date</th>
					<th>Amount Payable</th>
					<th>Payment Method</th>
					<th>Action</th>
				  </tr>
				</thead>
				<tbody>
				 <?php if(count($pending_transactions)){?>
                  <?php foreach($pending_transactions as $transaction){?>
				  <tr>
					<td><?php echo $transaction['order_id'];?></td>
					<td><?php echo $transaction['date_added'];?></td>
					<td><?php echo $transaction['currency_code'].' '.round($transaction['total'],2);?></td>
					<td><?php echo $transaction['payment_method'];?></td>
					<td><a  class="btn btn-default" onclick="changeOrderIdForPay('<?php echo $transaction['order_id'];?>','<?php echo round($transaction['total'],2)?>')">Pay Now</a></td>
				  </tr>
				  <?php } ?>
				  <?php }else{ ?>
				      <tr style="text-align:center">
					  <td colspan="5">No Transaction found</td>
					  </tr>
				 <?php } ?>
				</tbody>
			  </table>
		  </div>
		  <div id="successfull" class="tab-pane fade">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>Order Id </th>
					<th>Amount Paid</th>
					<th>Order Date</th>
					<th>Payment Method</th>
					<th>Transaction Id</th>
					<!--<th>Action</th>-->
				  </tr>
				</thead>
				<tbody>
				 <?php if(count($success_transactions)){?>
                  <?php foreach($success_transactions as $transaction){?>
				  <tr>
					<td><?php echo $transaction['order_id'];?></td>
					<td><?php echo $transaction['currency_code'].' '.round($transaction['total'],2);?></td>
					<td><?php echo $transaction['date_added'];?></td>
					<td><?php echo $transaction['payment_method'];?></td>
					<td><?php echo $transaction['transcation_id'];?></td>
					<!--<td>Pay Now</td>-->
				  </tr>
				  <?php } ?>
				  <?php }else{?>
				      <tr style="text-align:center">
					  <td colspan="5">No Transaction found</td>
					  </tr>
				 <?php } ?>
				</tbody>
			  </table>
		  </div>
		  <div id="cancelled" class="tab-pane fade">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>Order Id </th>
					<th>Amount Payable</th>
					<th>Order Date</th>
					<th>Payment Method</th>
					<!--<th>Action</th>-->
				  </tr>
				</thead>
				<tbody>
				 <?php if(count($cancelled_transactions)){?>
                  <?php foreach($cancelled_transactions as $transaction){?>
				  <tr>
					<td><?php echo $transaction['order_id'];?></td>
					<td><?php echo $transaction['currency_code'].' '.round($transaction['total'],2);?></td>
					<td><?php echo $transaction['date_added'];?></td>
					<td><?php echo $transaction['payment_method'];?></td>
					<!--<td>Pay Now</td>-->
				  </tr>
				  <?php } ?>
				  <?php }else{?>
				      <tr style="text-align:center">
					  <td colspan="4">No Transaction found</td>
					  </tr>
				 <?php } ?>
				</tbody>
			  </table>
		  </div>
		</div>
   </div>
   <div class="col-md-9" id="payment_options">
   Payment Options
	<div class="radio">
	  <label><input class="option_pay" onchange="payOptionSelected()"  value="pay_full" type="radio" name="pay_option">Pay Full</label>
	</div>
	<div class="radio">
	  <label><input type="radio" class="option_pay" onchange="payOptionSelected()" value="pay_other" name="pay_option">Pay Other Amount</label>
	</div>
   </div>

<div id="pay-confirm-order" class="col-md-9 confirm_order_class" style="padding:35px;">

<h2>Payment mPesa Online</h2>
<input type="hidden" name="order_id" value="<?php echo $this->request->get['order_id'];?>">
<input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id'];?>">
<input type="hidden" name="total_pending_amount" value="<?php echo $total_pending_amount;?>">
<input type="hidden" name="pending_order_id" value="<?php echo $pending_order_id;?>">


<h3 style="color: #f97900;">Please enter mpesa registered mobile number</h3>
<div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px; display: none;">
</div>
<div class="alert alert-success" style="font-size: 14px; display: none;" id="success_msg">
</div>

  <span class="input-group-btn" style="padding-bottom: 10px;">

    <p id="button-reward" class="" style="padding: 13px 14px;    margin-top: -8px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

        <font style="vertical-align: inherit;">
          <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
            +254                                                
          </font></font></font>
        </font>
    </p>

<input id="mpesa_phone_number" name="telephone" type="text" value="" class="form-control input-md" required="" placeholder="Mobile number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" style="display: inline-block;    width: 22%;">
<input id="mpesa_amount" name="amount_to_pay" type="text" value="" readonly class="form-control input-md" required="" placeholder="Amount" onkeypress="return (event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" style="display:inline-block; width: 22%;margin-left: 10px;">

</span>


<button type="button" id="button-confirm" data-toggle="collapse" style="width:200px;" data-loading-text="checking phone..." class="btn btn-default">PAY &amp; CONFIRM</button>

<button type="button" id="button-retry" class="btn btn-default" style="display: none;width:200px;"> Retry</button>

<button type="button" id="button-complete" data-toggle="collapse" data-loading-text="checking payment..." class="btn btn-default" style="display: none;">Confirm Payment</button>
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
        var order_id = $('input[name="order_id"]').val();
		var mpesa_amount = $('input[name="amount_to_pay"]').val();
		var customer = $('input[name="customer_id"]').val();
		var pending_order_id = $('input[name="pending_order_id"]').val();
		if(mpesa_amount =='' || mpesa_amount < 1){
			alert('Please select valid amount to pay');
		}else{
	    if($('#mpesa_phone_number').val().length >= 9) {
	    	$.ajax({
		        type: 'post',
		        url: 'index.php?path=payment/mpesa/confirm',
		        data: 'mobile=' + encodeURIComponent($('#mpesa_phone_number').val())+'&payment_method=mpesa&order_id='+order_id+'&amount='+mpesa_amount+'&pending_order_ids='+pending_order_id,
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
		        		//location = 'http://localhost:90/kwikbasket/checkout-success';
		        		
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
		}
	});

	$('#button-complete').on('click', function() {
	    
	    $('#error_msg').hide();
	    $('#success_msg').hide();
        var order_id = $('input[name="order_id"]').val();
    	$.ajax({
	        type: 'post',
	        url: 'index.php?path=payment/mpesa/complete',
			data: 'payment_method=mpesa&order_id='+order_id,
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
	        		alert('Payment Successfull');
	        		//$('#success_msg').html('Payment Successfull.');
	        		//$('#success_msg').show();
                    //setTimeout(function(){ location = '<?php echo $continue; ?>'; }, 1500);
	        		//setTimeout(function(){ location = 'http://localhost:90/kwikbasket/checkout-success'; }, 1500);
					

	        	} else {

	        		//failed
	        		//$('#button-confirm').show();
	        		//$('#button-retry').hide();
	        		//$('#button-complete').hide();
                    alert(json['error']);
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
    
 </div>

   </div>
   </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo $footer; ?>
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
</body>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);


    var page_category = 'my-account-page';
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                    clear = true;
               }
             if (clear) {
            clearInterval(intervalID);
         }
        },
        period);
        })(page_category);


</script>

<?php } ?>
<script type="text/javascript">

$('button[id^=\'button-custom-field\']').on('click', function() {
    var node = this;
    
    $('#form-upload').remove();
    
    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

    $('#form-upload input[name=\'file\']').trigger('click');
    
    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }
    
    timer = setInterval(function() {
        if ($('#form-upload input[name=\'file\']').val() != '') {
            clearInterval(timer);
            
            $.ajax({
                url: 'index.php?path=tool/upload',
                type: 'post',       
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,     
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');            
                },      
                success: function(json) {
                    $(node).parent().find('.text-danger').remove();
                    
                    if (json['error']) {
                        $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                    }
                                
                    if (json['success']) {
                        alert(json['success']);
                        
                        $(node).parent().find('input').attr('value', json['code']);
                    }
                },          
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});
//--></script> 
<!--  jQuery -->

<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    
<script type="text/javascript">

    /*jQuery(function($){
        console.log("signup mask");
       $("#tel").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    });*/
    /*jQuery(function($){
        console.log("mask");
       $("#tel").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    jQuery(function($) {
        console.log("tax mask");
       $("#tax_number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    

    $('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });    
	
	function changeOrderIdForPay(orderId,amount_to_pay){
		$('input[name="order_id"]').val(orderId);
		$('#mpesa_amount').val(amount_to_pay);
		$('div#payment_options').hide();
		$('div#payment_options').focus();
		/* $('html, body').animate({
          scrollTop: $("#payment_options").offset().top
          }, 2000);
		*/
	}
	
	function payOptionSelected(){
		//total_pending_amount
	    var radioValue = $("input[name='pay_option']:checked").val();
	    var total_pending_amount = $("input[name='total_pending_amount']").val();
	   if(radioValue == 'pay_full'){
		   $('#mpesa_amount').attr('readonly',true);
		   $('#mpesa_amount').val(total_pending_amount);
	   }else{
		   $('#mpesa_amount').attr('readonly',false);
		   $('#mpesa_amount').val('');
	   }
	}
</script> 
                 

    <?php if($redirect_coming) { ?>
      <script type="text/javascript">
        $('#save-button').click();
      </script>
      
    <?php } ?>
	<style>
	.nav-tabs>li {
		width: 33.3%;
    }
	
	.option_pay {
		margin-top:-3px !important;
	}
	</style>
</body>
</html>