<?php echo $header; ?>
<div class="page-container">
	<div class="checkout">
		<div id="step-container" class="container-fluid">
			<div class="wizard">
				<div class="row">
					<div class="col-sm-4 col-xs-4">
						<div id="go-back" class="wizardno checkicon click">
							<a href="<?= $this->url->link('checkout/cart') ?>">
								<i class="fa fa-check"></i>
								<dummy>1</dummy>
							</a>
							<p><?= $text_cart ?></p>
						</div>
					</div>
					
					<div class="col-sm-4 col-xs-4">
						<div class="wizardno checkicon">
							<a>
								<dummy>2</dummy>
								<i class="fa fa-check"></i>
							</a>
							<p>
								<?= $text_signin ?>
							</p>
						</div>
					</div>
					<div class="col-sm-4 col-xs-4">
						<div class="wizardno activewizard">
							<a>
								<dummy>3</dummy>
								<i class="fa fa-check"></i>
							</a>
							<p><?= $text_place_order ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="checkout-page-container">
			<div class="containerliquid whitebg">
				<div class="container-fluid">
					<div class="row">
						<div class="aligntocenter col-md-12">
							<h3 class="deskviewonly signupheading"><?= $heading_text ?></h3>
						</div>
					</div>
				</div>
				<h4 class="deskviewonly orderheadings"><?= $text_delivery_details ?></h4>
				<h4 class="orderheadings tabviewonly">Delivery Info</h4>
				<form role="form" id="place-order-form" novalidate="novalidate" class="bv-form" method="post">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><?= $label_name ?></label>
									<input type="text" name="shipping_name" value="<?php echo $name; ?>" placeholder="Name" class="form-control check-change" />
									<small style="display: none;" class="help-block">
									<?= $error_name ?>
									</small>
								</div>
								<div class="form-group">
									<label class="control-label"><?= $label_phone ?></label>
									<input type="text" value="<?php echo $contact_no; ?>" name="shipping_contact_no" placeholder="Phone Number" id="phone" class="form-control check-change" />
									<small style="display: none;" class="help-block"><?= $error_phone ?></small>
								</div>

								<div class="form-group required">
						            <label class="control-label" for="input-address"><?=  $entry_flat_number; ?></label>
						            <div >
							              <input name="flat_number" type="text" placeholder="Flat number" id="input-flat-number" class="form-control check-change" value="<?php echo $flat_number; ?>" />
							              <?php if ($error_flat_number) { ?>
							              <div class="text-danger"><?php echo $error_flat_number; ?></div>
							              <?php } ?>
						            </div>
						        </div>
						        <div class="form-group required">
						            <label class="control-label" for="input-building-name"><?=  $entry_building_name; ?></label>
						            <div >
						              <input type="text" name="building_name" placeholder="Building Name" id="input-building-name" class="form-control check-change" value="<?php echo $building_name; ?>"/>
						              <?php if ($error_building_name) { ?>
						              <div class="text-danger"><?php echo $error_building_name; ?></div>
						              <?php } ?>
						            </div>
						        </div>
					          <div class="form-group required">
					            <label class="control-label" for="input-landmark"><?=  $entry_landmark; ?></label>
					            <div >
					              <input name="landmark" type="text" placeholder="Landmark" id="input-landmark" class="form-control check-change" value="<?php echo $landmark; ?>"/>
					              <?php if ($error_landmark) { ?>
					              <div class="text-danger"><?php echo $error_landmark; ?></div>
					              <?php } ?>
					            </div>
					          </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<!-- <label class="control-label"><?= $label_address ?></label> -->
									<div class="addresses-container">
										<?php if($addresses){ ?>
										<div class="place_order_addresses">
											<div class="addressdropdown dropdown">
												<a data-toggle="dropdown" id="open-address"><?= $text_select_address ?></a>
												<ul class="addressmenu dropdown-menu">
													<?php foreach($addresses as $address){ ?>
													<li style="padding-left: 5px;"
														data-name="<?= $address['name'] ?>"
														data-contact_no="<?= $address['contact_no'] ?>"
														data-city_id="<?= $address['city_id'] ?>"
														data-address="<?= $address['address'] ?>"

														data-flat_number="<?= $address['flat_number'] ?>"
														data-building_name="<?= $address['building_name'] ?>"
														data-landmark="<?= $address['landmark'] ?>"

														>
														<?php echo $address['name'].', '.$address['address'].', '.$address['city']; ?>
													</li>
													<?php } ?>
												</ul>
											</div>
										</div>
										<?php } ?>
									</div>
									<div class="form-group">
										<label class="control-label"><?= $label_city ?></label><br />
										<div class="css3-metro-dropdown" style="width: 100%;">
											<select name="" disabled="true" id="shipping_city_id">
												<?php foreach($cities as $city){ ?>
												<?php if($city['city_id'] == $city_id){ ?>
												<option selected value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
												<?php }else{ ?>
												<option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
												<?php } ?>
												<?php } ?>
											</select>
											<input type="hidden" value="<?php echo $city_id; ?>" name="shipping_city_id" id="shipping_city_id">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label"><?= $label_zipcode ?></label><br />
										<div class="css3-metro-dropdown" style="width: 100%;">
											<select name="" disabled="true" id="shipping_city_id">
												<option selected value="<?= $zipcode ?>"><?= $zipcode ?></option>
											</select>
											<input type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode" id="shipping_zipcode">
										</div>
									</div>
								
									<div class="form-group">
										<label class="control-label"><?php echo $entry_type; ?></label>
										<div >
											<?php if ($address_type == 1) { ?>
											<label class="radio-inline">
											<input type="radio" name="address_type" value="1" />
											<?php echo $text_home_address; ?></label>
											<label class="radio-inline">
											<input type="radio" name="address_type" value="0" checked="checked" />
											<?php echo $text_office; ?></label>
											<?php } else { ?>
											<label class="radio-inline">
											<input type="radio" name="address_type" value="1" checked="checked" />
											<?php echo $text_home_address; ?></label>
											<label class="radio-inline">
											<input type="radio" name="address_type" value="0"  />
											<?php echo $text_office; ?></label>
											<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12 text-center">
								            <button type="button" disabled="true" id="save-address" class="btn-account-checkout btn-large btn-orange" onclick="saveInAddressBook()"><?= $save_address ?></button>
								        </div>
								    </div>
								</div>
							</div>
					<?php foreach ($store_data as $os): ?>
						<h4 class="orderheadings"><?= $text_delivery_options ?> <?php echo $os['name'] ?></h4>
						<div class="container-fluid">
							<div class="row" id="shipping-method-wrapper-<?php echo $os['store_id'] ?>">
								<!-- shipping method will goes here -->
							</div>
						</div>
					
						<h4 class="orderheadings"><?= $text_delivery_time ?><?php echo $os['name'] ?></h4>
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-6" id="delivery-time-wrapper-<?php echo $os['store_id'] ?>">
									<div class="row">
										
									</div>
								</div>
							</div>
						</div>
					<?php endforeach ?>  		
						<h4 class="orderheadings"><?= $text_payment_method ?></h4>
						<div class="container-fluid">
							<div class="not-inline payment-methods row">
								<div id="payment-method-wrapper">
									<!-- payment method will goes here -->
								</div>
							</div>
						</div>

					    <div class="cart-total table">
					        <div class="divideline light"></div>
					        <div class="container-fluid">
					            <div class="row-check-out">
					                <div class="col-md-6 h-f-t">
					                    <div class="info-box">
					                        <div class="info">
					                            <div class="title"><?= $title_availability ?></div>
					                            <div class="message"><?= $title_text1 ?></div>
					                        </div>
					                        <div class="info">
					                            <div class="title"><?= $title_price ?></div>
					                            <div class="message">
					                                <?= $title_text2 ?>
					                            </div>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-5 col-md-offset-1" id="checkout-total-wrapper">
					                    <!-- Total data will goes here -->
					                </div>
					            </div>
					        </div>
					    </div>

					    <div class="row">
					     <div class="col-md-12 text-center" id="confirm-wrapper">
					            <button type="submit" disabled="" class="btn-account-checkout btn-large btn-orange"><?= $button_confirm ?></button>
					        </div>
					    </div>    
				</form>
			</div>
		</div>
	</div>
</div>
<ul>

<script type="text/javascript">
	$('#button-reward').on('click', function() {
		$.ajax({
			url: 'index.php?path=checkout/reward/reward',
			type: 'post',
			data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
			dataType: 'json',
			beforeSend: function() {
				$('#button-reward').button('loading');
				$('#reward-error').hide();
				$('#reward-success').hide();
			},
			complete: function() {
				$('#button-reward').button('reset');
			},
			success: function(json) {
				if (json['error']) {
					$('#reward-error').html('<p id="error">'+json['error']+'</p>').show();
				}else{
					$('#reward-success').html('<p id="success">'+json['success']+'</p>').show();
					loadTotals($('input#shipping_city_id').val());
				}
			}
		});
	});
	$(document).delegate('.addressmenu li', 'click', function() {
		$('input[name="shipping_contact_no"]').val($(this).attr('data-contact_no'));
		$('input[name="shipping_name"]').val($(this).attr('data-name'));
		$('textarea[name="shipping_address"]').val($(this).attr('data-address'));
		$('input[name="shipping_city_id"]').val($(this).attr('data-city_id'));

		$('input[name="flat_number"]').val($(this).attr('data-flat_number'));
		$('input[name="building_name"]').val($(this).attr('data-building_name'));
		$('input[name="landmark"]').val($(this).attr('data-landmark'));

		loadTotals($(this).attr('data-city_id'));
	});
</script>

<script type="text/javascript">

// Load totals
function loadTotals($city_id) {
	$.ajax({
		url: 'index.php?path=checkout/totals&city_id=' + $city_id,
		type: 'post',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#checkout-total-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(html) {
			$('#checkout-total-wrapper').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
//Load Delivery Time
function loadDeliveryTime(store_id) {
	
	var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value')
	
	$.ajax({
		url: 'index.php?path=checkout/delivery_time&shipping_method='+shipping_method+'&store_id='+store_id+'',
		type: 'get',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#delivery-time-wrapper-'+store_id+'').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(html) {
			$('#delivery-time-wrapper-'+store_id+'').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}


function getTimeSlot(store_id,date){

	var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value')
	
	$.ajax({
		url: 'index.php?path=checkout/delivery_time/get_time_slot&shipping_method='+shipping_method+'&store_id='+store_id+'&date='+date+'',
		type: 'get',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
		},
		success: function(html) {

			console.log("html");
			console.log(html);
			$('#timeslot_'+store_id+'').html(html);
			loadPaymentMethods();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

// Methods related with shipping
$(document).ready(function() {
	//loadTotals(<?= $city_id ?>);
	<?php
		if ($shipping_required) { 

		foreach ($store_data as $os): 
		?>
			loadShippingMethods('<?php echo $os["store_id"] ?>'); 
			
		<?php
		endforeach;
		} ?>
	
	loadPaymentMethods();
});

<?php
if ($shipping_required) { 

?>
	// Load shipping methods
	function loadShippingMethods(store_id) {
		data = {
			store_id : store_id
		}
		
		$.ajax({
			url: 'index.php?path=checkout/shipping_method',
			type: 'post',
			dataType: 'html',
			data:data,
			cache: false,
			beforeSend: function() {
				$('#shipping-method-wrapper-'+store_id+'').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');

			},
			success: function(html) {
				$('#shipping-method-wrapper-'+store_id+'').html(html);
				saveShippingMethod(store_id);	
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// Save the selected shipping method
	function saveShippingMethod(store_id) {
		
		var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value');
		 
		if (shipping_method == undefined) {
			shipping_method = 0;
		}
		$.ajax({
			url: 'index.php?path=checkout/shipping_method/save',
			type: 'post',
			data: {
				store_id:store_id,
				shipping_method: shipping_method
			},
			dataType: 'html',
			cache: false,
			success: function(json) {
				if (json['redirect']) {
					//location = json['redirect'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						$('#shipping-method-wrapper').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				} else {
					loadTotals($('input[name="shipping_city_id"]').val());
					
					loadDeliveryTime(store_id);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	 <?php

} ?>




// Load payment methods
function loadPaymentMethods() {
	$.ajax({
		url: 'index.php?path=checkout/payment_method',
		type: 'post',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#payment-method-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(html) {
			$('#payment-method-wrapper').html(html);
			savePaymentMethod();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
// Save the selected payment method
function savePaymentMethod() {
	console.log("savepayment");

	var payment_method = $('input[name=\'payment_method\']:checked').attr('value');
        var payment_wallet_method = $('input[name=\'payment_wallet_method\']:checked').attr('value');
	if (payment_method == undefined) {
		payment_method = 0;
	}
        if (payment_wallet_method == undefined) {
               payment_wallet_method = 0;
        }
        console.log(payment_method);
        console.log(payment_wallet_method);
	$.ajax({
		url: 'index.php?path=checkout/payment_method/save',
		type: 'post',
		data: {
			payment_method: payment_method,
                        payment_wallet_method: payment_wallet_method
		},
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			// $('#payment-method-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(json) {
			if (json['redirect']) {
				//  location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-method-wrapper').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			} else {
				loadConfirm();
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
// Load confirm page
function loadConfirm() {
	$.ajax({
		url: 'index.php?path=checkout/confirm',
		type: 'post',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#confirm-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(html) {
			$('#confirm-wrapper').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function saveTimeSlot(store_id,timeslot){
	console.log("timeslot");
	var date = $('#dates_'+store_id+'').val();
	data = {
		store_id :store_id,
		date : date,
		timeslot :timeslot,
	}
	$.ajax({
		url: 'index.php?path=checkout/delivery_time/save',
		type: 'post',
		data:data,
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			//$('#confirm-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(html) {
			//$('#confirm-wrapper').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function saveAddress() {

	console.log("saveAddress");
	$('.alert').remove();
	$('#button-confirm').button('loading');

	$('.help-block').hide();
	$('.has-error').removeClass('has-error');

	$error = false;

	var shipping_name = $('input[name="shipping_name"]').val();
	var shipping_contact_no = $('input[name="shipping_contact_no"]').val();
	var shipping_address = $('textarea[name="shipping_address"]').val();
	var shipping_city_id = $('input[name="shipping_city_id"]').val();

	var landmark = $('input[name="landmark"]').val();
	var building_name = $('input[name="building_name"]').val();
	var flat_number = $('input[name="flat_number"]').val();
	var address_type = $('input[name="address_type"]').val();

	//validate all fields

	if (landmark.length <= 0) {
		$error = true;
		$('input[name="landmark"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	if (building_name.length <= 0) {
		$error = true;
		$('input[name="building_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	if (flat_number.length <= 0) {
		$error = true;
		$('input[name="flat_number"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	if (address_type.length <= 0) {
		$error = true;
		$('input[name="address_type"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}

	if (shipping_name.length <= 0) {
		$error = true;
		$('input[name="shipping_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}

	if (shipping_contact_no.length <= 0) {
		$error = true;
		$('input[name="shipping_contact_no"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}

	// if (shipping_address.length <= 0) {
	// 	$error = true;
	// 	$('textarea[name="shipping_address"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	// }
	<?php foreach ($store_data as $os):  ?>
	$('#delivery-time-wrapper-<?php echo $os["store_id"] ?> select').each(function() {

		if (this.value.length <= 0) {

			$error = true;
			$(this).parents('.form-group').addClass('has-error').find('.help-block').show();
		}
		
	})
	<?php endforeach; ?>

	if (!$error) {

		$valid_address = 0;
		$.ajax({
			url: 'index.php?path=checkout/address/save',
			type: 'post',
			async: false,
			data: $('#place-order-form').serialize(),
			dataType: 'json',
			cache: false,
			success: function(json) {

				if (json.status == 0) {
					$('h3.signupheading').before('<div class="alert alert-danger">' + json.msg + '</div>');
					$('html, body').animate({
						scrollTop: 0
					}, 'slow');
					$('#button-confirm').button('reset');
				} else {
					$valid_address = 1;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$('#button-confirm').button('reset');
				return false;
			}
		});

		if ($valid_address) {
			return true;
		}

	} else {
		$('html, body').animate({
			scrollTop: 0
		}, 'slow');
		$('#button-confirm').button('reset');
		return false;
	}
}

function saveInAddressBook() {

	console.log("saveInAddressBook");
	$('.alert').remove();
	$('#save-address').button('saving');

	$('.help-block').hide();
	$('.has-error').removeClass('has-error');

	$error = false;

	var shipping_name = $('input[name="shipping_name"]').val();
	var shipping_contact_no = $('input[name="shipping_contact_no"]').val();
	var shipping_address = $('textarea[name="shipping_address"]').val();
	var shipping_city_id = $('input[name="shipping_city_id"]').val();

	var landmark = $('input[name="landmark"]').val();
	var building_name = $('input[name="building_name"]').val();
	var flat_number = $('input[name="flat_number"]').val();
	var address_type = $('input[name="address_type"]').val();

	//validate all fields

	if (landmark.length <= 0) {
		$error = true;
		$('input[name="landmark"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	if (building_name.length <= 0) {
		$error = true;
		$('input[name="building_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	if (flat_number.length <= 0) {
		$error = true;
		$('input[name="flat_number"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	if (address_type.length <= 0) {
		$error = true;
		$('input[name="address_type"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	
	if (shipping_name.length <= 0) {
		$error = true;
		$('input[name="shipping_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}

	if (shipping_contact_no.length <= 0) {
		$error = true;
		$('input[name="shipping_contact_no"]').parents('.form-group').addClass('has-error').find('.help-block').show();
	}
	
	if (!$error) {

		$valid_address = 0;
		$.ajax({
			url: 'index.php?path=checkout/address/addInAddressBook',
			type: 'post',
			async: false,
			data: $('#place-order-form').serialize(),
			dataType: 'json',
			cache: false,
			success: function(json) {

				console.log(json+"json add address");
				if (json.status == 0) {
					$('h3.signupheading').before('<div class="alert alert-danger">' + json.msg + '</div>');
					$('html, body').animate({
						scrollTop: 0
					}, 'slow');
					$('#button-confirm').button('reset');
				} else {
					$valid_address = 1;					
					$('#save-address').prop('disabled', true);
					alert('Added in your address book');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$('#button-confirm').button('reset');
				return false;
			}
		});

	} else {
		$('html, body').animate({
			scrollTop: 0
		}, 'slow');
		$('#button-confirm').button('reset');
		return false;
	}
}
$('.check-change').keyup(function(){
  //your stuff
  console.log("in change");
    $('#save-address').prop('disabled', false);
});


</script>
<?php echo $footer; ?>