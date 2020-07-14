<?php echo $header;?>
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="my-order-view-dashboard">
                        <div class="row">
                            <!--<div class="col-md-11">
                                <div class="back-link-block"><a href="<?php echo $continue; ?>"> <span class="back-arrow"><i class="fa fa-long-arrow-left"></i> </span> <?= $text_go_back ?></a></div>
                            </div>-->
							
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-order-view-content">
                                    <div class="my-order">
                                        <div class="list-group my-order-group">
                                            <li class="list-group-item my-order-list-head">

                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <h2 class="my-order-list-title"><?= $store_name?> : Accept Delivery Form</h2>
                                                    </div>
                                                </div>
                                                
                                                
                                                <span>
                                                    <?= $text_order_id_with_colon ?>
                                                    <span class="my-order-id-number">#<?php echo $order_id; ?></span>
                                                </span>
                                                <span class="my-order-id-item"><strong><?php echo $total_products; ?></strong> <?= $text_products ?></span>
                                                <!--<span class="my-order-id-item"><strong><?php echo $total_quantity; ?></strong> <?= $text_products ?></span>-->
                                            </li>
											<div class="product-list">
                                            <?php $i=0;  foreach ($products as $product) { 
											 //echo '<pre>';print_r($product);exit;
											?>
                                                <li class="list-group-item" data-product-id="<?= $product['product_id'] ?>" data-product-name="<?= $product['name'] ?>">
                                                <div class="row">
                                                    <div class="col-md-2 col-xs-4">
                                                        <div class="mycart-product-img"><img src="<?= $product['image'] ?>" alt="" class="img-responsive"></div>
                                                    </div>
                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && $can_return) { ?>
                                                        <div class="col-md-2 col-xs-8">
                                                    <?php } else { ?>
                                                        <div class="col-md-2 col-xs-8">
                                                    <?php } ?>
                                                        <div class="mycart-product-info">
                                                            <h3> <?php echo $product['name']; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $product['unit']; ?></span>
                                                            </p>
                                                            <!--<?php if($product['product_type'] == 'replacable') { ?>
                                                                <span class="badge badge-success replacable"   data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                                                                 <?= $text_replacable ?>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span  class="badge badge-danger replacable"  data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                                                    <?= $text_not_replacable ?>
                                                                </span>
                                                            <?php } ?>-->
                                                            
                                                            <?php foreach ($products_status as $product_status) { ?>
                                                                <?php if(trim($product['name']) == trim($product_status->product_name) && $product['unit'] == $product_status->unit ) { $is_true = false; ?>
                                                                    <!--<span> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>-->
                                                                    <?php if($product_status->status == 'Remaining') {  $is_true = true;?>
                                                                        <span class="badge badge-warning">
                                                                            <?= $text_remaining ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                    

                                                                    <?php if($product_status->status == 'not available') { $is_true = true; ?>
                                                                        <span class="badge badge-danger">
                                                                            <?= $text_not_avialable ?>
                                                                        </span>
                                                                    <?php } ?>

                                                                    <?php if($product_status->status == 'picked') { $is_true = true; ?>
                                                                        <span class="badge badge-success">
                                                                            <?= $text_picked ?>
                                                                        </span>
                                                                    <?php } ?>

                                                                    <?php if($product_status->status == 'replaced') { $is_true = true; ?>
                                                                        <span class="badge badge-primary">
                                                                            <?= $text_replaced ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                    <?php if(!$is_true) { ?>
                                                                        <span class="badge badge-primary">
                                                                            <?= $product_status->status ?>
                                                                        </span>
                                                                    <?php } ?>
                                                            <?php } } ?>
                                                        </div>
                                                    </div>
                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && $can_return) { ?>
                                                        <div class="col-md-2 col-xs-8">
                                                    <?php } else { ?>
                                                       <div class="col-md-2 col-xs-8">
                                                    <?php } ?>
                                                        <div class="my-order-price">
                                                            <?php echo $product['quantity']; ?> x <?php echo $product['price']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-xs-8">
                                                        <div class="my-order-price">
                                                            <?php echo $product['total']; ?>
                                                        </div>
                                                    </div>
													<div class="col-md-2 col-xs-8" style="margin-top:12px">
                                                           <select id="action_<?= $product['product_id'] ?>" required="true">
															 	    <option value="">-Select Action-</option>
																	<option value="accept">Accept</option>
																	<option value="reject">Reject</option>
																	<option value="replace">Replace</option>
															</select>
                                                       
                                                    </div>
													<div class="col-md-2 col-xs-8" style="margin-top:12px">
													<label>Action Note</label>
													<textarea id="action_note_<?= $product['product_id'] ?>"  class="form-control" rows="4"></textarea>
                                                    </div>
                              
                                                    <?php /* if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && is_null($product['return_id']) && $can_return) { ?>
                                                        <div class="col-md-2">
                                                            <?php /* ?>
															<div class="my-order-price">
                                                                <!-- <a href="<?php echo $product['return']; ?>" id="return_button" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a> -->

                                                                <?php $url = $product['return']; ?>
                                                                <a onclick="return_product('<?= $url ?>')"  data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>

                                                                <!-- <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button> -->

                                                            </div>
															 <?php  ?>
                                                        </div>
                                                    <?php } */ ?>

                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && !is_null($product['return_id'])) { ?>
                                                        <div class="col-md-2 col-xs-8">
                                                            <div class="my-order-price">
                                                                Return Status: <?= $product['return_status'] ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    
                                                    
                                                </div>
                                            </li>
											
                                            <?php } ?>
											</div>
											<a onclick="accept_reject_delivery()" class="btn btn-default btn-xl btn-accept-reject" style="margin:5px;float:right" >Submit</a>
                                        </div>
                                    </div>
<?php if($mpesaOnline == true){?>			
<div id="pay-confirm-order" >
<div class="col-md-10">
<h2>mPesa Online</h2>
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

</span>
</div>
<div class="col-md-2" style="margin-right: 2px;">
<button type="button" id="button-confirm" data-toggle="collapse" style="width:200px;" data-loading-text="checking phone..." class="btn btn-default">PAY &amp; CONFIRM</button>

<button type="button" id="button-retry" class="btn btn-default" style="display: none;width:200px;"> Retry</button>

<button type="button" id="button-complete" data-toggle="collapse" data-loading-text="checking payment..." class="btn btn-default" style="display: none;">Confirm Payment</button>
</div>

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
	    if($('#mpesa_phone_number').val().length >= 9) {
	    	$.ajax({
		        type: 'post',
		        url: 'index.php?path=payment/mpesa/confirm',
		        data: 'mobile=' + encodeURIComponent($('#mpesa_phone_number').val())+'&payment_method=mpesa&order_id='+order_id,
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
	        		
	        		$('#success_msg').html('Payment Successfull.');
	        		$('#success_msg').show();
                    setTimeout(function(){ location = '<?php echo $continue; ?>'; }, 1500);
	        		//setTimeout(function(){ location = 'http://localhost:90/kwikbasket/checkout-success'; }, 1500);
					

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
    
</div>
<?php } ?>
                                </div>
                            </div>
                            
                                 <?php if(htmlspecialchars($_GET["order_status"])=='Delivered') { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="my-order-view-sidebar">
                                            <li class="list-group-item my-order-list-head">
                                                <h2 class="my-order-list-title">
                                                    <?= $text_delivery_detail ?>
                                                    <?php if(count($delivery_data) > 0) { ?>
                                                         # <?= $delivery_data->delivery_id ?>
                                                    <?php } ?>
                                                </h2>
                                            </li>
                                            <div class="checkout-sidebar">
                                                <div class="">
                                                 
                                                    <?php if(!isset($delivery_data->assigned_to)) { ?>
                                                            <center> <?= $text_no_delivery_alloted ?></center>

                                                    <?php } elseif(isset($delivery_data->assigned_to)) { ?>
                                                        <div class="checkout-invoice">
                                                            <div class="checout-invoice-title"><a href="<?= $shopper_link.$delivery_data->driver->profile->drivers_photo ?>" target="_blank" > <img style="    height: 80px;width: 80px;" src="<?= $shopper_link.$delivery_data->driver->profile->drivers_photo ?>"> </a></div>
                                                            <div class="checout-invoice-price"><?= $delivery_data->driver->first_name ?> <?= $delivery_data->driver->last_name ?>
                                                                <br>
                                                                <?= $delivery_data->driver->phone_number ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- new start -->


                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="my-order-view-sidebar">
                                                            
                                                            <form method="post" action="" id="review-form">
                                                              <div class="form-group">
                                                                

                                                                <input type="hidden" value="<?= $delivery_data->reviews->ratings?>" class="form-control" id="driver_rating" name="rating">
                                                                
                                                                <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->ratings)) { ?>
                                                                    <div class="col-sm-12">
                                                                        <span >
                                                                            <fieldset class="show_driver_rating">
                                                                                <input type="radio" id="driver_star5" name="show_driver_rating" value="5" disabled="disabled" />

                                                                                <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                                <input type="radio" id="driver_star4half" name="show_driver_rating" value="4.5" disabled="disabled"  />

                                                                                <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                                <input type="radio" id="driver_star4" name="show_driver_rating" value="4" disabled="disabled" />

                                                                                <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                                <input type="radio" id="driver_star3half" name="show_driver_rating" value="3.5" disabled="disabled" />

                                                                                <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                                <input type="radio" id="driver_star3" name="show_driver_rating" value="3" disabled="disabled" />

                                                                                <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                                <input type="radio" id="driver_star2half" name="show_driver_rating" value="2.5" disabled="disabled" />

                                                                                <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                                <input type="radio" id="driver_star2" name="show_driver_rating" value="2" disabled="disabled" />

                                                                                <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                                <input type="radio" id="driver_star1half" name="show_driver_rating" value="1.5" disabled="disabled"  />

                                                                                <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                                <input type="radio" id="driver_star1" name="show_driver_rating" value="1" disabled="disabled" />

                                                                                <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                                <input type="radio" id="driver_starhalf" name="show_driver_rating" value=".5" disabled="disabled" />

                                                                                <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                            </fieldset>
                                                                        </span>
                                                                    </div>
                                                                    
                                                                <?php } else { ?>
                                                                    

                                                                    <div class="col-sm-12">
                                                                        <span >
                                                                            <fieldset class="driver_rating">
                                                                                <input type="radio" id="driver_star5" name="driver_rating" value="5" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                                <input type="radio" id="driver_star4half" name="driver_rating" value="4.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                                <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                                <input type="radio" id="driver_star4" name="driver_rating" value="4" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                                <input type="radio" id="driver_star3half" name="driver_rating" value="3.5" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                                <input type="radio" id="driver_star3" name="driver_rating" value="3" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                                <input type="radio" id="driver_star2half" name="driver_rating" value="2.5" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                                <input type="radio" id="driver_star2" name="driver_rating" value="2" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                                <input type="radio" id="driver_star1half" name="driver_rating" value="1.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                                <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                                <input type="radio" id="driver_star1" name="driver_rating" value="1" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                                <input type="radio" id="driver_starhalf" name="driver_rating" value=".5" onclick="saveOrderdriverRating(this.value);" />

                                                                                <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                            </fieldset>
                                                                        </span>
                                                                    </div>

                                                                <?php } ?>
                                                              </div>
                                                             
                                                                <div class="rating-success-message" style="color: green;">
                                                                </div>

                                                                <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>

                                                                    <button type="button" class="btn btn-default disabled"><?= $text_send ?></button>

                                                                <?php } else { ?>

                                                                    <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button>
                                                                    

                                                                <?php } ?>
                                                            </form>
                                                        
                                                        </div>
                                                    </div>
                                                </div>


                                    <!-- end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
 <?php }?>
                                <?php if(isset($delivery_data->status) && $delivery_data->status == 441 && false) { ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="my-order-view-sidebar">
                                                <li class="list-group-item my-order-list-head">
                                                    <h2 class="my-order-list-title">
                                                        <?= $text_send_rating ?>
                                                    </h2>
                                                </li>
                                                <div class="checkout-sidebar">
                                                    <div class="">
                                                        <form method="post" action="" id="review-form">
                                                          <div class="form-group">
                                                            <label ><?= $text_rating ?>: </label>

                                                            <input type="hidden" value="<?= $delivery_data->reviews->ratings?>" class="form-control" id="driver_rating" name="rating">
                                                            
                                                            <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->ratings)) { ?>
                                                                <div class="col-sm-12">
                                                                    <span >
                                                                        <fieldset class="show_driver_rating">
                                                                            <input type="radio" id="driver_star5" name="show_driver_rating" value="5" disabled="disabled" />

                                                                            <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star4half" name="show_driver_rating" value="4.5" disabled="disabled"  />

                                                                            <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star4" name="show_driver_rating" value="4" disabled="disabled" />

                                                                            <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3half" name="show_driver_rating" value="3.5" disabled="disabled" />

                                                                            <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3" name="show_driver_rating" value="3" disabled="disabled" />

                                                                            <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2half" name="show_driver_rating" value="2.5" disabled="disabled" />

                                                                            <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2" name="show_driver_rating" value="2" disabled="disabled" />

                                                                            <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1half" name="show_driver_rating" value="1.5" disabled="disabled"  />

                                                                            <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1" name="show_driver_rating" value="1" disabled="disabled" />

                                                                            <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                            <input type="radio" id="driver_starhalf" name="show_driver_rating" value=".5" disabled="disabled" />

                                                                            <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                        </fieldset>
                                                                    </span>
                                                                </div>
                                                                
                                                            <?php } else { ?>
                                                                

                                                                <div class="col-sm-12">
                                                                    <span >
                                                                        <fieldset class="driver_rating">
                                                                            <input type="radio" id="driver_star5" name="driver_rating" value="5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star4half" name="driver_rating" value="4.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                            <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star4" name="driver_rating" value="4" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3half" name="driver_rating" value="3.5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3" name="driver_rating" value="3" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2half" name="driver_rating" value="2.5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2" name="driver_rating" value="2" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1half" name="driver_rating" value="1.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                            <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1" name="driver_rating" value="1" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                            <input type="radio" id="driver_starhalf" name="driver_rating" value=".5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                        </fieldset>
                                                                    </span>
                                                                </div>

                                                            <?php } ?>
                                                          </div>
                                                          <div class="form-group">
                                                            <label ><?= $text_review ?>:</label>

                                                            <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>
                                                                <textarea class="form-control" name="review" type="text" id="review" disabled><?= $delivery_data->reviews->review ?> </textarea>
                                                            <?php } else {?>
                                                                <textarea class="form-control" name="review" type="text" id="review"></textarea>
                                                            <?php } ?>
                                                            
                                                          </div>
                                                            <div class="rating-success-message" style="color: green;">
                                                            </div>

                                                            <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>

                                                                <button type="button" class="btn btn-default disabled"><?= $text_send ?></button>

                                                            <?php } else { ?>

                                                                <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button>
                                                                

                                                            <?php } ?>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<div class="refundOrderModal-popup">
        <div class="modal fade" id="refundOrderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <div class="store-head">
                        <h1>Return Initiation</h1>
                        <h4>Please Fill details to initate return:</h4>
						<br>
						<form id="refund-form" onsubmit="return checkProductSelected()" action="<?= $action ?>" autocomplete="off" method="post" enctype="multipart/form-data">
						<div class="row">
                          <div class="form-group">
						  <div class="col-md-12" style="padding-right: 15px;padding-left: 15px;">
						   <input type="hidden" name="order_id" value="<?php echo $this->request->get['order_id'];?>">
							  <table class="table table-bordered">
								<thead>
								  <tr>
									<th>Product Name</th>
									<th>Return Qunatity</th>
									<th>Select All <input type="checkbox" class="select-all checkbox" name="select-all" /></th>
								  </tr>
								</thead>
								<tbody>
								<?php foreach ($products as $product) {
									if(is_null($product['return_id'])){
									?>
								  <tr>
									<td><?php echo $product['name'];?></td>
									<td><input type="text" name="return_qty[]" value="<?php echo $product['quantity']?>"></td>
									<td><input type="checkbox" class="select-item checkbox" name="select-products[]" value="<?php echo $product['product_id']?>" /></td>
								  </tr>
								<?php } ?>
								<?php } ?>
								</tbody>
							  </table>
                          </div>
						  </div>
						  <div class="form-group required">
                  <label class="col-sm-4 control-label"><?php echo $entry_reason; ?></label>
                  <div class="col-sm-6">
                    <?php foreach ($return_reasons as $return_reason) { ?>
                    <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="return_reason_id" required value="<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
                        <?php echo $return_reason['name']; ?></label>
                    </div>
                    <?php } else { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="return_reason_id"  required value="<?php echo $return_reason['return_reason_id']; ?>" />
                        <?php echo $return_reason['name']; ?></label>
                    </div>
                    <?php  } ?>
                    <?php  } ?>
                    <?php if ($error_reason) { ?>
                    <div class="text-danger"><?php echo $error_reason; ?></div>
                    <?php } ?>
                  </div>
                </div>
				<div class="form-group required">
                  <label class="col-sm-4 control-label"><?php echo $entry_return_action; ?></label>
                  <div class="col-sm-6">
                    <?php /*foreach ($return_actions as $return_action) { ?>
                    <?php if ($return_action['return_action_id'] == $return_action_id) { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="return_action_id" required value="<?php echo $return_action['return_action_id']; ?>" checked="checked" />
                        <?php echo $return_action['name']; ?></label>
                    </div>
                    <?php } else { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="return_action_id" required  value="<?php echo $return_action['return_action_id']; ?>" />
                        <?php echo $return_action['name']; ?></label>
                    </div>
                    <?php  } ?>
                    <?php  } ?>
                    <?php if ($error_return_action) { ?>
                    <div class="text-danger"><?php echo $error_return_action; ?></div>
                    <?php } */ ?>
					<div class="radio">
                      <label>
                        <input type="radio" name="customer_desired_action" required value="refund" />
                        Refund Money</label>
                    </div>
					<div class="radio">
                      <label>
                        <input type="radio" name="customer_desired_action" required value="replace" />
                        Replace Product</label>
                    </div>
                  </div>
                </div>
				
				<div class="form-group required">
                  <label class="col-sm-4 control-label"><?php echo $entry_opened; ?></label>
                  <div class="col-sm-6">
                    <label class="radio-inline">
                      <?php if ($opened) { ?>
                      <input type="radio" name="opened" value="1" checked="checked" />
                      <?php } else { ?>
                      <input type="radio" name="opened" value="1" />
                      <?php } ?>
                      <?php echo $text_yes; ?></label>
                    <label class="radio-inline">
                      <?php if (!$opened) { ?>
                      <input type="radio" name="opened" value="0" checked="checked" />
                      <?php } else { ?>
                      <input type="radio" name="opened" value="0" />
                      <?php } ?>
                      <?php echo $text_no; ?></label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-12 control-label" for="input-comment"><?php echo $entry_fault_detail; ?></label>
				   <div class="col-sm-4">
				   </div>
                  <div class="col-sm-8">
                    <textarea name="comment" rows="10" placeholder="<?php echo $entry_fault_detail; ?>" id="input-comment" class="form-control input-lg"><?php echo $comment; ?></textarea>
                  </div>
                </div>
				<?php if ($text_agree) { ?>
              <div class="buttons clearfix" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <?php echo $text_agree; ?>
                  <input type="checkbox" required name="agree" value="1"/>
                  <input type="submit"  value="<?php echo $button_submit; ?>" class="btn-orange btn btn-primary" />
                </div>
              </div>
              <?php } else { ?>
              <div class="buttons clearfix" style="margin-bottom: 20px;">
                  <div class="col-md-6">
                  <input type="submit"  value="<?php echo $button_submit; ?>" class="btn-orange btn btn-primary" />
                </div>
              </div>
              <?php } ?>
						 </div>
						 </form>
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


    var page_category = 'order-detail-page';
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

        $(document).ready(function() {

            rating = '';
            console.log(<?= $rating ?>+"rating received");

            if(<?= $rating ?> % 1 === 0) {
                rating = <?= $rating ?>;
            } else {
                rating = (<?= $rating ?> - .5)+"half";
            }
            console.log(rating);

            $('#star'+rating).removeAttr('disabled');
            $('#star'+rating).click();

            //driver rating start
                                                            
            <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->ratings)) { ?>
                
                driver_rating = '';
                console.log(<?= $delivery_data->reviews->ratings ?>+"driver_rating received");

                if(<?= $delivery_data->reviews->ratings ?> % 1 === 0) {
                    driver_rating = <?= $delivery_data->reviews->ratings ?>;
                } else {
                    driver_rating = (<?= $delivery_data->reviews->ratings ?> - .5)+"half";
                }
                console.log(driver_rating);

                $('#driver_star'+driver_rating).removeAttr('disabled');
                $('#driver_star'+driver_rating).click();


            <?php } ?>
        });

        function sendReview() {
            console.log("sendReview");
            console.log("timeslot");
            
           
            data = {
                rating :$('#driver_rating').val(),
                review :  '',//$('textarea[name="review"]').val(),
                delivery_id: '<?= $delivery_id ?>'//"del_XPeEGFX3Hc4ZeWg5"
            }

            console.log(data);

            $('.rating-success-message').html('');

            $.ajax({
                url: 'index.php?path=checkout/success/sendOrderRating',
                type: 'post',
                data:data,
                dataType: 'json',
                success: function(response) {
                    console.log("sendReview");
                    console.log(response);
                    console.log("sendRevssiew");

                    $('.rating-success-message').html(response.message);

                    //$('#confirm-wrapper').html(html);
                }
            });

        }

        function saveOrderdriverRating(rating) {

            console.log(rating);
            console.log("saveOrderdriverRating");
            

            $('#driver_rating').val(rating);
        }

        function saveOrderRating(rating) {

            console.log(rating);
            console.log("saveOrderRating");
            

            data = {
                rating : rating,
                //rating : 3.5,
                order_id : <?= $order_id ?>
            }
            //$('.rating-success-message').html('');

            console.log(data);

            $.ajax({
                url: 'index.php?path=checkout/success/saveOrderRating',
                type: 'post',
                data:data,
                dataType: 'json',
                success: function(response) {
                    console.log("saveOrderRating");
                    console.log(response);
                }
            });

        }

        function return_product(return_product) {
        //function return_product() {

            //console.log(return_product);
            console.log("return_product");
            

            data = {
                order_id : <?= $order_id ?>
            }
            
            console.log(data);

            $.ajax({
                url: 'index.php?path=account/order/can_return',
                type: 'post',
                data:data,
                dataType: 'json',
                success: function(response) {
                    console.log("saveOrderRating");
                    console.log(response);

                    if(response['can_return']) {
                        location = return_product;
                    } else {
                        alert("Sorry, Return Window has passed");
                        location = location;
                    }
                }
            });

        }

        setInterval(function() {
         location = location;
        }, 60 * 100000); // 60 * 1000 milsec
        
        

    </script>
	<script>
	
	function checkProductSelected(){
			 var len = $("input.select-item:checked:checked").length;
			 if(len > 0){
				 return true;
			 }else{
				 alert('Please select at least one product');
				 return false;
			 }
	}
	
    $(function(){

        //button select all or cancel
        /*$("#select-all").click(function () {
            var all = $("input.select-all")[0];
            all.checked = !all.checked
            var checked = all.checked;
            $("input.select-item").each(function (index,item) {
                item.checked = checked;
            });
        });

        //button select invert
        $("#select-invert").click(function () {
            $("input.select-item").each(function (index,item) {
                item.checked = !item.checked;
            });
            checkSelected();
        });

        //button get selected info
        $("#selected").click(function () {
            var items=[];
            $("input.select-item:checked:checked").each(function (index,item) {
                items[index] = item.value;
            });
            if (items.length < 1) {
                alert("no selected items!!!");
            }else {
                var values = items.join(',');
                console.log(values);
                var html = $("<div></div>");
                html.html("selected:"+values);
                html.appendTo("body");
            }
        });
		*/

        //column checkbox select all or cancel
        $("input.select-all").click(function () {
            var checked = this.checked;
            $("input.select-item").each(function (index,item) {
                item.checked = checked;
            });
        });

        //check selected items
        $("input.select-item").click(function () {
            var checked = this.checked;
            console.log(checked);
            checkSelected();
        });

        //check is all selected
        function checkSelected() {
            var all = $("input.select-all")[0];
            var total = $("input.select-item").length;
            var len = $("input.select-item:checked:checked").length;
            console.log("total:"+total);
            console.log("len:"+len);
            all.checked = len===total;
        }
		
		
		
    });
	function accept_reject_delivery(){
		   var error ='';
		   var productsActionError = [];
		   var productsActionNoteError = [];
		   var productSubmitArray = [];
		   $('.error').remove();
		   var return_replace =0;
		   //var orderId = $('.my-order-id-number').text().replace('#','');
		   //alert(orderId);
		   $('.product-list li').each(function( index ) {
			  var productId = $( this ).attr('data-product-id');
			  var productName = $( this ).attr('data-product-name');
			  var action = $( "#action_"+productId+" option:selected" ).val();
			  var action_note = $( "#action_note_"+productId).val();
			  var tempArray = [];
			      tempArray.push(productId)
				  tempArray.push(productName)
				  tempArray.push(action);
				  tempArray.push(action_note);
				  productSubmitArray.push(tempArray);
			  if(action =="" || action == undefined){
				  productsActionError.push(productName);
			  }else{
				  if(action !='accept'){
					  return_replace++;
					  if(action_note =="" || action == undefined){
						  productsActionNoteError.push(productName)
					  }
				  }
			  }
			  
			  var action_note = $( "#action_note_"+productId).val();
              console.log( productId + ": " + action + ": " + action_note);
			
           });
		  
		  if(productsActionError.length > 0){
						  error += '<span>Please select action for : ( '+productsActionError.toString()+' )</span></br>';
		  }
		  if(productsActionNoteError.length > 0){
			  error += '<span>Please enter action note for : ( '+productsActionNoteError.toString()+' )</span>';
		  }
		  if(error!=''){
			   $( "<p class='error'>"+error+"</p>" ).insertAfter( ".product-list" );
			   console.log('productSubmitArrayErr',productSubmitArray);
		  }else{
			  data = {
                order_id : <?= $order_id ?>,
				products : productSubmitArray,
				return_replace:return_replace
              }
			  $.ajax({
                url: 'index.php?path=account/order/accept_delivery_submit',
                type: 'post',
                data:data,
                dataType: 'json',
                success: function(response) {
					var redirect_url = window.location.href.match(/^.*\//)[0];
                    console.log("saveOrderRating");
                    console.log(response);

                    if(response['status'] == true) {
						alert('Delivery Process initiated!');
                        location = redirect_url;
                    } else {
                        alert('Something went wrong!');
                    }
                }
              });
			  
		  }
		  
		}
</script>
<style>
.error {
    color: red;
}
</style>
</html>
