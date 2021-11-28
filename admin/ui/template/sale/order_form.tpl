<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right"><a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i> <?php echo $button_cancel; ?></a></div>
	  <h1><?php echo $heading_title; ?></h1>
	  <ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	  </ul>
	</div>
  </div>
  <div class="container-fluid">
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
	  </div>
	  <div class="panel-body">
		<form class="form-horizontal">
		  <ul id="order" class="nav nav-tabs nav-justified">
			<li class="disabled active"><a href="#tab-customer" data-toggle="tab">1. <?php echo $tab_customer; ?></a></li>
			<li class="disabled"><a href="#tab-cart" data-toggle="tab">2. <?php echo $tab_product; ?></a></li>
			<li class="disabled"><a href="#tab-shipping" data-toggle="tab">3. <?php echo $tab_shipping; ?></a></li>
			<li class="disabled"><a href="#tab-total" data-toggle="tab">4. <?php echo $tab_total; ?></a></li>
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab-customer">
				
			  <!-- <input type="hidden" name="store_id" value="0" /> -->
				
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
				  <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
				<div class="col-sm-10">
				  <select name="customer_group_id" id="input-customer-group" class="form-control">
					<?php foreach ($customer_groups as $customer_group) { ?>
					<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="telephone" value="<?php echo $telephone; ?>" id="input-telephone" class="form-control" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="fax" value="<?php echo $fax; ?>" id="input-fax" class="form-control" />
				</div>
			  </div>             
			  <div class="text-right">
				<button type="button" id="button-customer" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
			  </div>
			</div>
			<div class="tab-pane" id="tab-cart">
			  <div class="table-responsive">
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <td class="text-left"><?php echo $column_product; ?></td>
					  <td class="text-left"><?php echo $column_model; ?></td>
					  <td class="text-right"><?php echo $column_quantity; ?></td>
					  <td class="text-right"><?php echo $column_price; ?></td>
					  <td class="text-right"><?php echo $column_total; ?></td>
					  <td></td>
					</tr>
				  </thead>
				  <tbody id="cart">
					<?php if ($order_products ) { ?>
					<?php $product_row = 0; ?>
					<?php foreach ($order_products as $order_product) { ?>

					<tr>
					  <td class="text-left"><?php echo $order_product['name']; ?><br />
						<input type="hidden" name="product[<?php echo $product_row; ?>][product_store_id]" value="<?php echo $order_product['product_store_id']; ?>" />
						<input type="hidden" name="product[<?php echo $product_row; ?>][store_product_variation_id]" value="<?php echo $order_product['store_product_variation_id']; ?>" />
						<input type="hidden" name="product[<?php echo $product_row; ?>][store_id]" value="<?php echo $order_product['store_id']; ?>" />
						</td>
					  <td class="text-left"><?php echo $order_product['model']; ?></td>
					  <td class="text-right"><?php echo $order_product['quantity']; ?>
						<input type="hidden" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" /></td>
					  <td class="text-right"></td>
					  <td class="text-right"></td>
					  <td class="text-center"></td>
					</tr>
					<?php $product_row++; ?>
					<?php } ?>
					
					<?php } else { ?>
					<tr>
					  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
					</tr>
				  </tbody>
				  <?php } ?>
				</table>
			  </div>
			  
			  <div class="tab-content">
				<div class="tab-pane active" id="tab-product">
				  <fieldset>
					<legend><?php echo $text_product; ?></legend>
					<div class="form-group">
					  <label class="col-sm-2 control-label" for="input-product"><?= $entry_store ?></label>
					  <div class="col-sm-10">
						<input type="text" name="store" value="" id="input-store" class="form-control" />
						<input type="hidden" name="store_id" value="<?php echo $store_id ?>" />
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
					  <div class="col-sm-10">
						<input type="text" name="product" value="" id="input-product" class="form-control" />
						<input type="hidden" name="product_store_id" value="" />
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
					  <div class="col-sm-10">
						<input type="text" name="quantity" value="1" id="input-quantity" class="form-control" />
					  </div>
					</div>
					<div id="option"></div>
				  </fieldset>
				  <div class="text-right">
					<button type="button" id="button-product-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_product_add; ?></button>
				  </div>
				</div>
				
			  </div>
			  <br />
			  <div class="row">
				<div class="col-sm-6 text-left">
				  <button type="button" onclick="$('a[href=\'#tab-customer\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
				</div>
				<div class="col-sm-6 text-right">
				  <button type="button" id="button-cart" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
				</div>
			  </div>
			</div>           
			<div class="tab-pane" id="tab-shipping">
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-firstname"><?= $entry_name ?></label>
				<div class="col-sm-10">
				  <input type="text" name="shipping_name" value="<?php echo $shipping_name; ?>" id="input-shipping-firstname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-firstname"><?= $text_flat_house_office ?></label>
				<div class="col-sm-10">
				  <input type="text" name="shipping_flat_number" value="<?php echo $shipping_flat_number; ?>" id="input-shipping-firstname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-firstname"><?= $text_stree_society_office ?></label>
				<div class="col-sm-10">
				  <input type="text" name="shipping_building_name" value="<?php echo $shipping_building_name; ?>" id="input-shipping-firstname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-firstname"><?= $text_locality ?></label>
				<div class="col-sm-10">
				  <input type="text" name="shipping_landmark" value="<?php echo $shipping_landmark; ?>" id="input-shipping-firstname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-firstname"><?= $label_zipcode ?></label>
				<div class="col-sm-10">
				  <input type="text" name="shipping_zipcode" value="<?php echo $shipping_zipcode; ?>" id="input-shipping-firstname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-lastname"><?= $entry_contact_no ?></label>
				<div class="col-sm-10">
				  <input type="text" name="shipping_contact_no" value="<?php echo $shipping_contact_no; ?>" id="input-shipping-lastname" class="form-control" />
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-lastname"><?= $entry_city ?></label>
				<div class="col-sm-10">
				  <select name="shipping_city_id" class="form-control">
					  <?php foreach($cities as $city){ ?>
					  <?php if($city['city_id'] == $shipping_city_id){ ?>
					  <option selected value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
					  <?php }else{ ?>
					  <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
					  <?php } ?>
					  <?php } ?>
				  </select>
				</div>
			  </div>  
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-shipping-lastname"><?= $entry_addess ?></label>
				<div class="col-sm-10">
					<textarea name="shipping_address" id="input-shipping-address-1" class="form-control"><?php echo $shipping_address; ?></textarea>
				</div>
			  </div>
			  <div class="row">
				<div class="col-sm-6 text-left">
				  <button type="button" onclick="$('a[href=\'#tab-cart\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
				</div>
				<div class="col-sm-6 text-right">
				  <button type="button" id="button-shipping-address" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-total">
			  <div class="table-responsive">
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <td class="text-left"><?php echo $column_product; ?></td>
					  <td class="text-left"><?php echo $column_model; ?></td>
					  <td class="text-right"><?php echo $column_quantity; ?></td>
					  <td class="text-right"><?php echo $column_price; ?></td>
					  <td class="text-right"><?php echo $column_total; ?></td>
					</tr>
				  </thead>
				  <tbody id="total">
					<tr>
					  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
					</tr>
				  </tbody>
				</table>
			  </div>
			  <fieldset>
				<legend><?php echo $text_order; ?></legend>
					<div class="form-group required">
					  <label class="col-sm-2 control-label" for="input-shipping-method"><?php echo $entry_shipping_method; ?></label>
					  <div class="col-sm-10">
						<div class="input-group">
						  <select name="shipping_method" id="input-shipping-method" class="form-control">
							<option value=""><?php echo $text_select; ?></option>
							<?php if ($shipping_code) { ?>
							<option value="<?php echo $shipping_code; ?>" selected="selected"><?php echo $entry_shipping_method; ?></option>
							<?php } ?>
						  </select>
						  <span class="input-group-btn">
						  <button type="button" id="button-shipping-method" data-toggle="tooltip" title="<?php echo $button_shipping; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
						  </span>
						</div>
					  </div>
					</div>
					<div class="form-group required">
					  <label class="col-sm-2 control-label"><?= $entry_date_timeslot ?></label>
					   <div class="col-sm-10">
					  	<div id="delivery-time-wrapper">
					  		<div class="">
						       <div class="col-sm-5">
						        <div class="form-group formgroup2">
						            <div class="deliverytime-ddown css3-metro-dropdown">
						                <select data-store-id='<?php echo $store_id ?>' id="dates_<?= $store_id ?>" name="dates[<?= $store_id ?>]" class="form-control" >
						                    <option value=""><?= $text_select_date ?></option>
						                   	<option value="<?php echo $delivery_date ?>" selected><?php echo $delivery_date ?></option>
						                </select>                
						            </div>
						            <small class="help-block" style="display: none;">Select Date</small>
						        </div>
						    </div>    
						    <div class="col-sm-1"></div>
						    <div class="col-sm-6">
						        <div class="form-group formgroup2">
						            <div class="deliverytime-ddown css3-metro-dropdown">
						                <select name="timeslot[<?= $store_id ?>]" class="form-control" id="timeslot_<?= $store_id ?>">
						                    <option><?= $text_select_timeslot ?></option>
						                    <option value="<?php echo $delivery_timeslot ?>" selected><?php echo $delivery_timeslot ?></option>
						                </select>                
						            </div>
						            <small class="help-block" style="display: none;">Select a delivery time</small>
						        </div>
						    </div>
						</div>
					  	</div>
					  </div>
				</div>

				<div class="form-group required">
				  <label class="col-sm-2 control-label" for="input-payment-method"><?php echo $entry_payment_method; ?></label>
				  <div class="col-sm-10">
					<div class="input-group">
					  <select name="payment_method" id="input-payment-method" class="form-control">
						<option value=""><?php echo $text_select; ?></option>
						<?php if ($payment_code) { ?>
						<option value="<?php echo $payment_code; ?>" selected="selected"><?php echo $payment_method; ?></option>
						<?php } ?>
					  </select>
					  <span class="input-group-btn">
					  <button type="button" id="button-payment-method" data-toggle="tooltip" title="<?php echo $button_payment; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
					  </span></div>
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="input-coupon"><?php echo $entry_coupon; ?></label>
				  <div class="col-sm-10">
					<div class="input-group">
					  <input type="text" name="coupon" value="<?php echo $coupon; ?>" id="input-coupon" class="form-control" />
					  <span class="input-group-btn">
					  <button type="button" id="button-coupon" data-toggle="tooltip" title="<?php echo $button_coupon; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
					  </span></div>
				  </div>
				</div>
				
				<div class="form-group hidden">
				  <label class="col-sm-2 control-label" for="input-voucher"><?php echo $entry_voucher; ?></label>
				  <div class="col-sm-10">
					<div class="input-group">
					  <input type="text" name="voucher" value="<?php echo $voucher; ?>" id="input-voucher" data-loading-text="<?php echo $text_loading; ?>" class="form-control" />
					  <span class="input-group-btn">
					  <button type="button" id="button-voucher" data-toggle="tooltip" title="<?php echo $button_voucher; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
					  </span></div>
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="input-reward"><?php echo $entry_reward; ?></label>
				  <div class="col-sm-10">
					<div class="input-group">
					  <input type="text" name="reward" value="<?php echo $reward; ?>" id="input-reward" data-loading-text="<?php echo $text_loading; ?>" class="form-control" />
					  <span class="input-group-btn">
					  <button type="button" id="button-reward" data-toggle="tooltip" title="<?php echo $button_reward; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
					  </span></div>
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
				  <div class="col-sm-10">
					<select name="order_status_id" id="input-order-status" class="form-control">
					  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
					  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select>
					<input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
				  <div class="col-sm-10">
					<textarea name="comment" rows="5" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
				  </div>
				</div>
				
				<div class="form-group hidden">
				  <label class="col-sm-2 control-label" for="input-affiliate"><?php echo $entry_affiliate; ?></label>
				  <div class="col-sm-10">
					<input type="text" name="affiliate" value="<?php echo $affiliate; ?>" id="input-affiliate" class="form-control" />
					<input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>" />
				  </div>
				</div>
				
			  </fieldset>
			  <div class="row">
				<div class="col-sm-6 text-left">
				  <button type="button" onclick="$('select[name=\'shipping_method\']').prop('disabled') ? $('a[href=\'#tab-payment\']').tab('show') : $('a[href=\'#tab-shipping\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
				</div>
				<div class="col-sm-6 text-right">
				  <button type="button" id="button-refresh" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning hidden"><i class="fa fa-refresh"></i></button>
				  <button type="button" id="button-save" class="btn btn-primary"><i class="fa fa-check-circle"></i> <?php echo $button_save; ?></button>
				</div>
			  </div>
			</div>
		  </div>
		</form>
	  </div>
	</div>
  </div>
  <script type="text/javascript"><!--
// Disable the tabs
$('#order a[data-toggle=\'tab\']').on('click', function(e) {
	return false;
});

// Add all products to the cart using the api
$('#button-refresh').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/cart/products&store_id=' + $('input[name=\'store_id\']').val(),
		dataType: 'json',
		method:'POST',
		data:{store_id : $('input[name=\'store_id\']').val()},
		success: function(json) {
			$('.alert-danger, .text-danger').remove();
			
			// Check for errors
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
									
				if (json['error']['stock']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['stock'] + '</div>');
				}
								
				if (json['error']['minimum']) {
					for (i in json['error']['minimum']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['minimum'][i] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			}				
			
			var shipping = false;
			
			html = '';
			
			if (json['products']) {
				for (i = 0; i < json['products'].length; i++) {
					product = json['products'][i];
					
					html += '<tr>';
					html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
					html += '  <input type="hidden" name="product[' + i + '][product_store_id]" value="' + product['product_store_id'] + '" />';
					html += '  <input type="hidden" name="product[' + i + '][store_product_variation_id]" value="' + product['store_product_variation_id'] + '" />';
										html += '  <input type="hidden" name="product[' + i + '][store_id]" value="' + product['store_id'] + '" />';
										
					if (product['option']) {
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];
							
							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							
							if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['product_option_value_id'] + '" />';
							}
							
							if (option['type'] == 'checkbox') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + '][]" value="' + option['product_option_value_id'] + '" />';
							}
							
							if (option['type'] == 'text' || option['type'] == 'textarea' || option['type'] == 'file' || option['type'] == 'date' || option['type'] == 'datetime' || option['type'] == 'time') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['value'] + '" />';
							}
						}
					}
					
					html += '</td>';
					html += '  <td class="text-left">' + product['model'] + '</td>';
					html += '  <td class="text-right">' + product['quantity'] + '<input type="hidden" name="product[' + i + '][quantity]" value="' + product['quantity'] + '" /></td>';
					html += '  <td class="text-right">' + product['price'] + '</td>';
					html += '  <td class="text-right">' + product['total'] + '</td>';
					html += '  <td class="text-center" style="width: 3px;"><button type="button" value="' + product['key'] + '" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
					html += '</tr>';
					
					if (product['shipping'] != 0) {
						shipping = true;
					}
				}
			} 
			
			if (!shipping) {
			//	$('select[name=\'shipping_method\'] option').removeAttr('selected');
				//$('select[name=\'shipping_method\']').prop('disabled', true);
				//$('#button-shipping-method').prop('disabled', true);
			} else {
				//$('select[name=\'shipping_method\']').prop('disabled', false);
				//$('#button-shipping-method').prop('disabled', false);				
			}
					
			
			
			if (json['products'] == '' && json['vouchers'] == '') {				
				html += '<tr>';
				html += '  <td colspan="6" class="text-center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	
			}

			$('#cart').html(html);

			// Totals
			html = '';
			
			if (json['products']) {
				for (i = 0; i < json['products'].length; i++) {
					product = json['products'][i];
					
					html += '<tr>';
					html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
					
					if (product['option']) {
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];
							
							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
						}
					}
					
					html += '  </td>';
					html += '  <td class="text-left">' + product['model'] + '</td>';
					html += '  <td class="text-right">' + product['quantity'] + '</td>';
					html += '  <td class="text-right">' + product['price'] + '</td>';
					html += '  <td class="text-right">' + product['total'] + '</td>';
					html += '</tr>';
				}				
			}
			
			if (json['vouchers']) {
				for (i in json['vouchers']) {
					voucher = json['vouchers'][i];
					 
					html += '<tr>';
					html += '  <td class="text-left">' + voucher['description'] + '</td>';
					html += '  <td class="text-left"></td>';
					html += '  <td class="text-right">1</td>';
					html += '  <td class="text-right">' + voucher['amount'] + '</td>';
					html += '  <td class="text-right">' + voucher['amount'] + '</td>';
					html += '</tr>';	
				}	
			}
			
			if (json['totals']) {
				for (i in json['totals']) {
					total = json['totals'][i];
					
					html += '<tr>';
					html += '  <td class="text-right" colspan="4">' + total['title'] + ':</td>';
					html += '  <td class="text-right">' + total['text'] + '</td>';
					html += '</tr>';
				}
			}
			
			if (!json['totals'] && !json['products'] && !json['vouchers']) {				
				html += '<tr>';
				html += '  <td colspan="5" class="text-center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	
			}
						
			$('#total').html(html);
		},	
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Customer
$('input[name=\'customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					customer_id: '0',
					customer_group_id: '<?php echo $customer_group_id; ?>',						
					name: '<?php echo $text_none; ?>',
					customer_group: '',
					firstname: '',
					lastname: '',
					email: '',
					telephone: '',
					fax: '',
					custom_field: [],
					address: []			
				});				
				
				response($.map(json, function(item) {
					return {
						category: item['customer_group'],
						label: item['name'],
						value: item['customer_id'],
						customer_group_id: item['customer_group_id'],						
						firstname: item['firstname'],
						lastname: item['lastname'],
						email: item['email'],
						telephone: item['telephone'],
						fax: item['fax'],
						custom_field: item['custom_field'],
						address: item['address']
					}
				}));
			}
		});
	},
	'select': function(item) {
		// Reset all custom fields
		$('#tab-customer input[type=\'text\'], #tab-customer input[type=\'text\'], #tab-customer textarea').not('#tab-customer input[name=\'customer\'], #tab-customer input[name=\'customer_id\']').val('');
		$('#tab-customer select option').removeAttr('selected');
		$('#tab-customer input[type=\'checkbox\'], #tab-customer input[type=\'radio\']').removeAttr('checked');
		
		$('#tab-customer input[name=\'customer\']').val(item['label']);
		$('#tab-customer input[name=\'customer_id\']').val(item['value']);
		$('#tab-customer select[name=\'customer_group_id\']').val(item['customer_group_id']);
		$('#tab-customer input[name=\'firstname\']').val(item['firstname']);
		$('#tab-customer input[name=\'lastname\']').val(item['lastname']);
		$('#tab-customer input[name=\'email\']').val(item['email']);
		$('#tab-customer input[name=\'telephone\']').val(item['telephone']);
		$('#tab-customer input[name=\'fax\']').val(item['fax']);		
				
		for (i in item.custom_field) {
			$('#tab-customer select[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
			$('#tab-customer textarea[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
			$('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(item.custom_field[i]);
			$('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(item.custom_field[i]);
			$('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + item.custom_field[i] + '\']').prop('checked', true);	
			
			if (item.custom_field[i] instanceof Array) {
				for (j = 0; j < item.custom_field[i].length; j++) {
					$('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + item.custom_field[i][j] + '\']').prop('checked', true);
				}
			}
		}
	
		$('select[name=\'customer_group_id\']').trigger('change');
		
		html = '<option value="0"><?php echo $text_none; ?></option>'; 
			
		for (i in  item['address']) {
			html += '<option value="' + item['address'][i]['address_id'] + '">' + item['address'][i]['firstname'] + ' ' + item['address'][i]['lastname'] + ', ' + item['address'][i]['address_1'] + ', ' + item['address'][i]['city'] + ', ' + item['address'][i]['country'] + '</option>';
		}
		
				$('select[name=\'shipping_address\']').html(html);
		
		$('select[name=\'shipping_address\']').trigger('change');
	}
});
		
// Custom Fields
$('select[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?path=sale/customer/customfield&token=<?php echo $token; ?>&customer_group_id=' + this.value,
		dataType: 'json',	
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');
			
			for (i = 0; i < json.length; i++) {
				custom_field = json[i];
				
				$('.custom-field' + custom_field['custom_field_id']).show();
				
				if (custom_field['required']) {
					$('.custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


$('select[name=\'shipping_city_id\']').on('change',function(){
	$.ajax({
		url: 'index.php?path=sale/order/checkStoreDelivery&token=<?php echo $token; ?>&shipping_city_id=' + this.value,
		dataType: 'json',   
		success: function(json) {
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});








$('select[name=\'customer_group_id\']').trigger('change');

$('#button-customer').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/customer&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-customer').button('loading');
		},
		complete: function() {
			 $('#button-customer').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}				
				
				for (i in json['error']) {
					var element = $('#input-' + i.replace('_', '-'));
					
					if (element.parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}				
				
				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
			} else {
				$.ajax({
					url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/cart/add&store_id=' + $('input[name=\'store_id\']').val(),
					type: 'post',
					data: $('#cart input[name^=\'product\'][type=\'text\'], #cart input[name^=\'product\'][type=\'hidden\'], #cart input[name^=\'product\'][type=\'radio\']:checked, #cart input[name^=\'product\'][type=\'checkbox\']:checked, #cart select[name^=\'product\'], #cart textarea[name^=\'product\'],input[name=\'store_id\']'),
					dataType: 'json',
					beforeSend: function() {
						$('#button-product-add').button('loading');
					},
					complete: function() {
						$('#button-product-add').button('reset');
					},
					success: function(json) {
						$('.alert, .text-danger').remove();
						$('.form-group').removeClass('has-error');
					
						if (json['error'] && json['error']['warning']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});		
					
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');

				$('a[href=\'#tab-cart\']').tab('show');

				$('select[name=\'payment_address\']').selectpicker('refresh');
				$('select[name=\'shipping_address\']').selectpicker('refresh');
				$('select[name=\'zone_id\']').selectpicker('refresh');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
				
$('#tab-product input[name=\'store\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=setting/store/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {

				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['store_id'],

					}

				}));
			}
		});
	},
	'select': function(item) {
		 
	$('#tab-product input[name=\'store\']').val(item['label']);
	$('#tab-product input[name=\'store_id\']').val(item['value']);
	}
});

$('#tab-product input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=catalog/product/autocompleteStoreProduct&token=<?php echo $token; ?>&filter_status=1&filter_store='+encodeURIComponent($('input[name="store_id"]').val())+'&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_store_id'],
						model: item['model'],
						option: item['option'],
						variations: item['variations'],
						default_variation_name: item['default_variation_name'],
						price: item['price']						
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('#tab-product input[name=\'product\']').val(item['label']);
		$('#tab-product input[name=\'product_store_id\']').val(item['value']);

						
		if (item['variations'] != '') {
			//console.log(item['variations']);
			html  = '  <div class="form-group">';
						html += '     <label class="col-sm-2 control-label" for="input-variation">Variations</label>';
						html += '     <div class="col-sm-10">';
						html += '       <select style="max-width:220px;" name="store_product_variation_id" id="input-variation" class="form-control">';
						
						html += '     <option value="0">';
						html +=          item['default_variation_name'];
						html += '     </option>';

						for (i = 0; i < item['variations'].length; i++) {
							html += '     <option value="'+item['variations'][i]['product_variation_store_id']+'">';
							html +=          item['variations'][i]['name'];
							html += '     </option>';
						}
						
						html += '       </select>';
						html += '    </div>';
						html += '   </div>';			
			
			$('#option').html(html);			
		} else {                    
			$('#option').html('<input type="hidden" name="store_product_variation_id" value="0" />');
		}		
	}	
});

$('#button-product-add').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/cart/add&store_id=' + $('input[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-product input[type="hidden"], #tab-product input[name="quantity"], #tab-product select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-product-add').button('loading');
		},
		complete: function() {
			$('#button-product-add').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
		
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
				
				if (json['error']['option']) {	
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));
						
						if (element.parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}
				
				if (json['error']['store']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['store'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');				
			} else {
							// Refresh products, vouchers and totals
							$('#button-refresh').trigger('click');
							$('#tab-product input[name="quantity"]').val('');
							$('#tab-product input[name="product_store_id"]').val('');
							$('#tab-product input[name="product"]').val('');
							$('#option').html('');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});				
});



$('#tab-cart').delegate('.btn-danger', 'click', function() {
	var node = this;
	
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/cart/remove&store_id=' + $('input[name=\'store_id\']').val(),
		type: 'post',
		data: 'key=' + encodeURIComponent(this.value),
		dataType: 'json',
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
		
			// Check for errors
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} else {
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});				
});

$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/hasProduct&store_id=' + $('input[name=\'store_id\']').val(),
		type: 'post',
		data: $('#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea,input[type=\'store_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-address').button('loading');
		},
		complete: function() {
			$('#button-shipping-address').button('reset');
		},
		success:function(json){

			if (json['error']['warning']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}else{
				$('a[href=\'#tab-shipping\']').tab('show');
			}
		},error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});				
});

$('#button-shipping-address').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/shipping/address&store_id=' + $('input[name=\'store_id\']').val(),
		type: 'post',
		data: $('#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea,input[type=\'store_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-address').button('loading');
		},
		complete: function() {
			$('#button-shipping-address').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			// Check for errors
			if (json['error']) {
				//console.log(json['error']);
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['error']['shipping_city_id']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['shipping_city_id'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
				if (json['error']['shipping_name']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['shipping_name'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
				if (json['error']['shipping_contact_no']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['shipping_contact_no'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
				if (json['error']['shipping_address']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['shipping_address'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-shipping-' + i.replace('_', '-'));
					
					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}
				
				// Highlight any found errors
				
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');


			} else {
								// Payment Methods
				$.ajax({
					url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/payment/methods&store_id=' + $('input[name=\'store_id\']').val(),
					dataType: 'json',
					method:'post',
					data:{store_id : $('input[name=\'store_id\']').val()},
					beforeSend: function() {
						$('#button-payment-address i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
						$('#button-payment-address').prop('disabled', true);
					},
					complete: function() {
						$('#button-payment-address i').replaceWith('<i class="fa fa-arrow-right"></i>');
						$('#button-payment-address').prop('disabled', false);
					},
					success: function(json) {
						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						} else {
							html = '<option value=""><?php echo $text_select; ?></option>';
							
							if (json['payment_methods']) {
								for (i in json['payment_methods']) {
									if (json['payment_methods'][i]['code'] == $('select[name=\'payment_method\'] option:selected').val()) {
										html += '<option value="' + json['payment_methods'][i]['code'] + '" selected="selected">' + json['payment_methods'][i]['title'] + '</option>';
									} else {
										html += '<option value="' + json['payment_methods'][i]['code'] + '">' + json['payment_methods'][i]['title'] + '</option>';
									}
								}
							}	
							
							$('select[name=\'payment_method\']').html(html);
														$('select[name=\'payment_method\']').selectpicker('refresh');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});	
								
				// Shipping Methods
				$.ajax({
					url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/shipping/methods&store_id=' + $('input[name=\'store_id\']').val(),
					dataType: 'json',
					method:'post',
					data:{store_id:$('input[name=\'store_id\']').val()},
					beforeSend: function() {
						$('#button-shipping-address i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
						//$('#button-shipping-address').prop('disabled', true);
					},
					complete: function() {
						$('#button-shipping-address i').replaceWith('<i class="fa fa-arrow-right"></i>');
						//$('#button-shipping-address').prop('disabled', false);
					},
					success: function(json) {
						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						} else {
							// Shipping Methods
							html = '<option value=""><?php echo $text_select; ?></option>';
							
							if (json['shipping_methods']) {
								for (i in json['shipping_methods']) {
									html += '<optgroup label="' + json['shipping_methods'][i]['title'] + '">';
								
									if (!json['shipping_methods'][i]['error']) {
										for (j in json['shipping_methods'][i]['quote']) {
											if (json['shipping_methods'][i]['quote'][j]['code'] == $('select[name=\'shipping_method\'] option:selected').val()) {
												html += '<option value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" selected="selected">' + json['shipping_methods'][i]['quote'][j]['title'] + '</option>';
											} else {
												html += '<option value="' + json['shipping_methods'][i]['quote'][j]['code'] + '">' + json['shipping_methods'][i]['quote'][j]['title'] + '</option>';
											}
										}		
									} else {
										html += '<option value="" style="color: #F00;" >' + json['shipping_method'][i]['error'] + '</option>';
									}
									
									html += '</optgroup>';
								}
							}
							
							$('select[name=\'shipping_method\']').html(html);
							$('select[name=\'shipping_method\']').selectpicker('refresh');
														
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});	
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
								
				$('a[href=\'#tab-total\']').tab('show');							
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});				
});

// Shipping Method
$('#button-shipping-method').on('click', function() {

	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/shipping/method&store_id=' + $('input[name=\'store_id\']').val(),
		type: 'post',
		data: {store_id:$('input[name=\'store_id\']').val(),shipping_method : $('select[name=\'shipping_method\'] option:selected').val()},
		dataType: 'json',

		beforeSend: function() {
			$('#button-shipping-method').button('loading');	
		},	
		complete: function() {
			$('#button-shipping-method').button('reset');
		},		
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			
				// Highlight any found errors
				$('select[name=\'shipping_method\']').parent().parent().parent().addClass('has-error');			
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
				// date and time sloter for 
				var store_id = $('input[name=\'store_id\']').val();
				loadDeliveryTime(store_id);
			}	
		},	
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


function loadDeliveryTime(store_id) {
	
	// var shipping_method = ('select[name=\'shipping_method\']:option:selected').val();	
	var shipping_method =  $('select[name=\'shipping_method\'] option:selected').val();
	var data ={
		shipping_method:shipping_method,
		store_id:store_id
	}
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/delivery_time&shipping_method='+shipping_method+'&store_id='+store_id+'',
		type: 'post',
		dataType: 'html',
		data:data,
		cache: false,
		beforeSend: function() {
			$('#delivery-time-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function(html) {
			$('#delivery-time-wrapper').html(html);	

			

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}


function getTimeSlot(store_id,date){

	//var shipping_method = $('select[name=\'shipping_method\']:option:selected').val()
	var shipping_method =  $('select[name=\'shipping_method\'] option:selected').val();

	var data = {
		shipping_method:shipping_method,
		store_id:store_id,
		date:date
	}
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/delivery_time/get_time_slot&shipping_method='+shipping_method+'&store_id='+store_id+'&date='+date+'',
		type: 'post',
		data:data,
		dataType: 'html',
		cache: false,
		beforeSend: function() {
		},
		success: function(html) {
			$('#timeslot_'+store_id+'').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}


function saveTimeSlot(store_id,timeslot){
	
	var date = $('#dates_'+store_id+'').val();
	data = {
		store_id :store_id,
		date : date,
		timeslot :timeslot,
	}
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/delivery_time/save',
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




// Payment Method
$('#button-payment-method').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/payment/method&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'payment_method=' + $('select[name=\'payment_method\'] option:selected').val(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-method').button('loading');
		},	
		complete: function() {
			$('#button-payment-method').button('reset');
		},		
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			
				// Highlight any found errors
				$('select[name=\'payment_method\']').parent().parent().parent().addClass('has-error');				
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});		
});

// Coupon
$('#button-coupon').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/coupon&store_id=' + $('input[name=\'store_id\']').val(),
		type: 'post',
		data: $('input[name=\'coupon\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-coupon').button('loading');
		},	
		complete: function() {
			$('#button-coupon').button('reset');
		},		
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			
				// Highlight any found errors
				$('input[name=\'coupon\']').parent().parent().parent().addClass('has-error');				
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});		
});



// Reward
$('#button-reward').on('click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/reward&store_id=' + $('input[name=\'store_id\']').val(),
		type: 'post',
		data: $('input[name=\'reward\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward').button('loading');
		},	
		complete: function() {
			$('#button-reward').button('reset');
		},		
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				// Highlight any found errors
				$('input[name=\'reward\']').parent().parent().parent().addClass('has-error');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});		
});



// Checkout
$('#button-save').on('click', function() {
	var order_id = $('input[name=\'order_id\']').val();
	
	if (order_id == 0) {
		var url = 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/add&store_id=' + $('input[name=\'store_id\']').val();
	} else {
		var url = 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/edit&store_id=' + $('input[name=\'store_id\']').val() + '&order_id=' + order_id;
	}
	
	$.ajax({
		url: url,
		type: 'post',
		data: $('#tab-total select[name=\'order_status_id\'], #tab-total select, #tab-total textarea[name=\'comment\'], #tab-total input[name=\'affiliate_id\'],input[name=\'store_id\']'),
		dataType: 'json',

		beforeSend: function() {
			$('#button-save').button('loading');	
		},	
		complete: function() {
			$('#button-save').button('reset');
		},		
		success: function(json) {
			$('.alert, .text-danger').remove();
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (json['order_id']) {
				$('input[name=\'order_id\']').val(json['order_id']);
			}			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});		
});

$('#content').delegate('button[id^=\'button-upload\'], button[id^=\'button-custom-field\'], button[id^=\'button-payment-custom-field\'], button[id^=\'button-shipping-custom-field\']', 'click', function() {
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
				url: 'index.php?path=tool/upload/upload&token=<?php echo $token; ?>',
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
						$(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}
								
					if (json['success']) {
						alert(json['success']);
					}
					
					if (json['code']) {
						$(node).parent().find('input[type=\'hidden\']').attr('value', json['code']);
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});
//--></script>
<script type="text/javascript">
$('#button-shipping-method').trigger('click');
<!--
$(document).on('change', '#dates_<?= $store_id ?>', function() {
	getTimeSlot('<?= $store_id?>',$(this).val());
    
});
//-->
$(document).on('change', '#timeslot_<?= $store_id ?>', function() {
    saveTimeSlot('<?= $store_id ?>',$(this).val());
});
//-->
</script>
</div>
<?php echo $footer; ?>