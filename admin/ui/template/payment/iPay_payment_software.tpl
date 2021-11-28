<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  	<div class="page-header">
    	<div class="container-fluid">
      		<div class="pull-right">
        		<button type="submit" form="ipay-form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
    		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      		<h1><img src="view/image/iPay.png" alt="" /> <?php echo $heading_title; ?></h1>
      		<ul class="breadcrumb">
        		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
        		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        		<?php } ?>
      		</ul>
    	</div>
  	</div>

  	<div class="container-fluid">
  		<?php if ($error_warning) { ?>
  			<div class="warning"><?php echo $error_warning; ?></div>
  		<?php } ?>
  		<div class="panel panel-default">
	      <div class="panel-heading">
	        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo "Edit payment"; ?></h3>
	      </div>
		      <div class="panel-body">
		      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="ipay-form" class ="form-horizontal">

		      	<div class="form-group">
		      		 <label class="col-sm-2 control-label" for="input_iPay_payment_software_merchant_name"><?php echo $entry_login; ?></label>
						<div class="col-sm-10">
							<input type="text" name="iPay_payment_software_merchant_name" value="<?php echo $iPay_payment_software_merchant_name; ?>" placeholder="<?php echo $entry_login; ?>" id="input_iPay_payment_software_merchant_name" class="form-control" />
						</div>
	      		</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_merchant_key"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_key; ?></label>
					<div class="col-sm-10">
						<input type="text" name="iPay_payment_software_merchant_key" value="<?php echo $iPay_payment_software_merchant_key; ?>" placeholder="<?php echo $entry_key; ?>" id="input_iPay_payment_software_merchant_key" class="form-control" />
					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_callback_url"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_callback_url; ?></label>
					<div class="col-sm-10">
						<input type="text" name="iPay_payment_software_callback_url" value="<?php echo $iPay_payment_software_callback_url; ?>" placeholder="<?php echo $entry_callback_url; ?>" id="input_iPay_payment_software_callback_url" class="form-control" />
					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_ipay_url"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_ipay_url; ?></label>
					<div class="col-sm-10">
						<input type="text" name="iPay_payment_software_ipay_url" value="<?php echo $iPay_payment_software_ipay_url; ?>" placeholder="<?php echo $entry_ipay_url; ?>" id="input_iPay_payment_software_ipay_url" class="form-control" />
					</div>
	      		</div>


	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_elipa_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_elipa_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_elipa_enabled" id="input_iPay_payment_software_elipa_enabled" class="form-control">
						<?php if ($iPay_payment_software_elipa_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>
					</div>


	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_mvisa_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_mvisa_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_mvisa_enabled" id="input_iPay_payment_software_mvisa_enabled" class="form-control">
						<?php if ($iPay_payment_software_mvisa_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>
					</div>


	      		</div>
	      		
	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_mpesa_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_mpesa_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_mpesa_enabled" id="input_iPay_payment_software_mpesa_enabled" class="form-control">
						<?php if ($iPay_payment_software_mpesa_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>
					</div>


	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_airtel_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_airtel_enabled; ?></label>
					<div class="col-sm-10">
						
						<select name="iPay_payment_software_airtel_enabled" id="input_iPay_payment_software_airtel_enabled" class="form-control">
						<?php if ($iPay_payment_software_airtel_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>
					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_equity_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_equity_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_equity_enabled" id="input_iPay_payment_software_equity_enabled" class="form-control">
						<?php if ($iPay_payment_software_equity_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>

					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_mobilebanking_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_mobilebanking_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_mobilebanking_enabled" id="input_iPay_payment_software_mobilebanking_enabled" class="form-control">
						<?php if ($iPay_payment_software_mobilebanking_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>

					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_debitcard_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_debitcard_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_debitcard_enabled" id="input_iPay_payment_software_debitcard_enabled" class="form-control">
						<?php if ($iPay_payment_software_debitcard_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>

					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_creditcard_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_creditcard_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_creditcard_enabled" id="input_iPay_payment_software_creditcard_enabled" class="form-control">
						<?php if ($iPay_payment_software_creditcard_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>

					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_mkoporahisi_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_mkoporahisi_enabled; ?></label>
					<div class="col-sm-10">
						

						<select name="iPay_payment_software_mkoporahisi_enabled" id="input_iPay_payment_software_mkoporahisi_enabled" class="form-control">
						<?php if ($iPay_payment_software_mkoporahisi_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>


					</div>
	      		</div>

	      		<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_saida_enabled"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_saida_enabled; ?></label>
					<div class="col-sm-10">
						
						<select name="iPay_payment_software_saida_enabled" id="input_iPay_payment_software_saida_enabled" class="form-control">
						<?php if ($iPay_payment_software_saida_enabled) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>


					</div>
	      		</div>



	      		<!--  -->



		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_mode"><?php echo $entry_mode; ?></label>
            			<div class="col-sm-10">
              			<select name="iPay_payment_software_mode" id="input_iPay_payment_software_mode" class="form-control">
						<?php if ($iPay_payment_software_mode == 'live') { ?>
						<option value="live" selected="selected"><?php echo $text_live; ?></option>
						<?php } else { ?>
						<option value="live"><?php echo $text_live; ?></option>
						<?php } ?>
						<?php if ($iPay_payment_software_mode == 'test') { ?>
						<option value="test" selected="selected"><?php echo $text_test; ?></option>
						<?php } else { ?>
						<option value="test"><?php echo $text_test; ?></option>
						<?php } ?>
						</select>
            		</div>
		      	</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_method"><?php echo $entry_method; ?></label>
            		<div class="col-sm-10">
            			<select name="iPay_payment_software_method" id="input_iPay_payment_software_method" class="form-control">
						<?php if ($iPay_payment_software_method == 'authorization') { ?>
						<option value="authorization" selected="selected"><?php echo $text_authorization; ?></option>
						<?php } else { ?>
						<option value="authorization"><?php echo $text_authorization; ?></option>
						<?php } ?>
						<?php if ($iPay_payment_software_method == 'capture') { ?>
						<option value="capture" selected="selected"><?php echo $text_capture; ?></option>
						<?php } else { ?>
						<option value="capture"><?php echo $text_capture; ?></option>
						<?php } ?>
           				</select>
            		</div>
		      	</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_order_status_id"><?php echo $entry_order_status; ?></label>
            		<div class="col-sm-10">
            			<select name="iPay_payment_software_order_status_id" id="input_iPay_payment_software_order_status_id" class="form-control">
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $iPay_payment_software_order_status_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
						</select>
            		</div>
		      	</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="iPay_payment_software_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
            		<div class="col-sm-10">
            			<select name="iPay_payment_software_geo_zone_id" id="iPay_payment_software_geo_zone_id" class="form-control">
						<option value="0"><?php echo $text_all_zones; ?></option>
						<?php foreach ($geo_zones as $geo_zone) { ?>
						<?php if ($geo_zone['geo_zone_id'] == $iPay_payment_software_geo_zone_id) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
						<?php } ?>
						</select>
            		</div>
		      	</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_status"><?php echo $entry_status; ?></label>
            		<div class="col-sm-10">
            			<select name="iPay_payment_software_status" id="input_iPay_payment_software_status" class="form-control">
						<?php if ($iPay_payment_software_status) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
						</select>
            		</div>
		      	</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
					<div class="col-sm-10">
						<input type="text" name="iPay_payment_software_total" value="<?php echo $iPay_payment_software_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input_iPay_payment_software_total" class="form-control" />
					</div>
		      	</div>

		      	<div class="form-group">
		      		<label class="col-sm-2 control-label" for="input_iPay_payment_software_sort_order"><?php echo $entry_sort_order; ?></label>
					 <div class="col-sm-10">
					 	<input type="text" name="iPay_payment_software_sort_order" value="<?php echo $iPay_payment_software_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input_iPay_payment_software_merchant_key" class="form-control" />
					</div>
		      	</div>

		      </form>
		      </div>


      </div>


  	
  	</div>



</div>
<?php echo $footer; ?> 
