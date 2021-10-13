<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		  <!-- <a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></a> 
		  <a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default"><i class="fa fa-truck"></i></a>  -->

		  <?php if (!$this->user->isVendor()): ?>
	        <?php if ( !in_array( $order_status_id, $this->config->get( 'config_complete_status' ) ) ) { ?>
	            <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary">
				  <i class="fa fa-pencil"></i>
			  </a>
	        <?php } ?>
		  
		  <?php endif; ?>
		  <!-- <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> -->
	</div>
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
		<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
	  </div>
	  <div class="panel-body">
		<ul class="nav nav-tabs">

		<?php if(!$this->user->isVendor()){ ?>
			<li class="active" ><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
		<?php } else { ?>
			<li class="active" ><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
		<?php } ?>
		  

		  <?php if(!$this->user->isVendor()){ ?>
		  	<li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
		  <?php } ?>
		  
		  <?php if ($shipping_method) { ?>
		  <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
		  <?php } ?>
		  <?php if ($delivery_details) { ?>
		  	<li><a href="#tab-delivery" data-toggle="tab"><?php echo $tab_delivery; ?></a></li>
		  <?php } ?>
		  
		  <li><a href="#tab-product" data-toggle="tab">Updated Products<?php //echo $tab_product; ?></a></li>
		  <li><a href="#tab-original-product" data-toggle="tab">Ordered Products</a></li>
                  <li><a href="#tab-order-log" data-toggle="tab">Order Log</a></li>

		 <!-- <?php if($is_edited) {?>-->

		  <!--<?php } ?>-->
<!--Difference  products tab is not required , so commented -->
		  <?php if(!$this->user->isVendor() && $is_edited && 1==2  ) {?>

		  	<li style="visibility:hidden" ><a href="#tab-difference-product"  data-toggle="tab">Difference Products</a></li>

		  <?php } ?>


		  <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
		  <?php if ($settlement_tab) { ?>
		  <li><a href="#tab-settlement" data-toggle="tab"><?php echo $tab_settlement; ?></a></li>
		  <?php } ?>
		  <?php if ($payment_action) { ?>
		  <li><a href="#tab-action" data-toggle="tab"><?php echo $tab_action; ?></a></li>
		  <?php } ?>
		  <?php if ($maxmind_id) { ?>
		  <li><a href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
		  <?php } ?>
		  <?php if ($questions) { ?>
		  <li><a href="#tab-question" data-toggle="tab"><?php echo $tab_question; ?></a></li>
		  <?php } ?>

		  <?php if(!$this->user->isVendor()){ ?>
		  	<li><a href="#tab-location" data-toggle="tab"><?php echo $tab_location; ?></a></li>
		  <?php } ?>
                  
                  <?php if(!$this->user->isVendor()){ ?>
		  	<li><a href="#tab-driver-location" data-toggle="tab">Driver Location</a></li>
		  <?php } ?>
		  
		  

		  
		</ul>
		<div class="tab-content">


		  	<?php if(!$this->user->isVendor()){ ?>
			  
			  
				<div class="tab-pane " id="tab-location">

					<input type="button" class="btn btn-primary" onclick="initMapLoad()" value="View Map" /> 

					<div class="" id="map" style="height: 100%; min-height: 600px;">
		    		</div>

		    		<input type="hidden" name="single_delivery_map_ui" id="single_delivery_map_ui" value="<?= $map_s ?>">

				</div>

			<?php } ?>
                        
                        <?php if(!$this->user->isVendor()){ ?>
			  
			  
				<div class="tab-pane " id="tab-driver-location">

                                    <input type="button" class="btn btn-primary" id="show_driver_location" data-order_id="<?= $order_id; ?>" data-delivery_id="<?= $delivery_id; ?>" data-delivery_latitide="<?= $delivery_latitude; ?>" data-delivery_longitude="<?= $delivery_longitude; ?>" value="View Map" /> 

					<div class="" id="drivermap" style="height: 100%; min-height: 600px;">
		    		</div>

		    		<input type="hidden" name="single_delivery_map_ui" id="single_delivery_map_ui" value="<?= $map_s ?>">

				</div>

			<?php } ?>
			

		<?php if(!$this->user->isVendor()) { ?>
			<div class="tab-pane active" id="tab-order">
		<?php } else { ?>
			<div class="tab-pane active" id="tab-order">
		<?php } ?>
		  
			<table class="table table-bordered">

			

			  <tr>
				<td><?php echo $text_order_id; ?></td>
				<td>#<?php echo $order_id; ?></td>
			  </tr>

			  <?php if(!$this->user->isVendor()) { ?>
			  		<tr>
						<td><?php echo $text_invoice_no; ?></td>
						<td><?php if ($invoice_no) { ?>
						  <?php echo $invoice_no; ?>
						  <?php } else { ?>
						  <button id="button-invoice" class="btn btn-success btn-xs"><i class="fa fa-cog"></i> <?php echo $button_generate; ?></button>
						  <?php } ?></td>
					  </tr>

					  <tr>
						<td>Rating</td>

						<?php if (is_null($rating)) { ?>

							<td> Not Rated</td>

						<?php } else { ?>
						 	<td><?php echo $rating; ?></td>

						<?php } ?>
					  </tr>
					 
					  <?php if ($customer) { ?>
					  <tr>
						<td><?php echo $text_customer; ?></td>
						<td>
							<?php if(!$this->user->isVendor()){ ?>
								<a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> </a>
							<?php }else{ ?>
								<!-- <?php echo $firstname; ?> --> <!-- <?php echo $lastname; ?>        -->             
							<?php } ?>
						</td>
					  </tr>
					  <?php } else { ?>
					  <tr>
						<td><?php echo $text_customer; ?></td>
						<td><?php echo $firstname; ?> <!-- <?php echo $lastname; ?> --></td>
					  </tr>
					  <?php } ?>
					  <?php if ($customer_group) { ?>
					  <tr>
						<td><?php echo $text_customer_group; ?></td>
						<td><?php echo $customer_group; ?></td>
					  </tr>
					  <?php } ?>
					  <tr>
						<td><?php echo $text_email; ?></td>
						<td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>  <a href="<?php echo $order_customer_link; ?>" target="_blank" data-toggle="tooltip" title="" data-original-title="View Customer Detials"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></td>
					  </tr>
                                          <?php if($parent_user_email != NULL) { ?>
                                          <tr>
						<td>Parent User Email:</td>
						<td><a href="mailto:<?php echo $email; ?>"><?php echo $parent_user_email; ?></a>  <a href="<?php echo $parent_customer_link; ?>" target="_blank" data-toggle="tooltip" title="" data-original-title="View Customer Detials"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></td>
					  </tr>
                                          <?php } ?>
                                          <?php if($head_chef != NULL) { ?>
                                          <tr>
						<td>First Level Approver Email:</td>
                                                <td><a href="mailto:<?php echo $head_chef['email']; ?>"><?php echo $head_chef['email']; ?></a>   <a href="<?php echo $head_chef_link; ?>" target="_blank" data-toggle="tooltip" title="" data-original-title="View Customer Detials"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></td>
					  </tr>
                                          <?php } ?>
                                          <?php if($procurement != NULL) { ?>
                                          <tr>
						<td>Second Level Approver Email:</td>
						<td><a href="mailto:<?php echo $procurement['email']; ?>"><?php echo $procurement['email']; ?></a>  <a href="<?php echo $procurement_link; ?>" target="_blank" data-toggle="tooltip" title="" data-original-title="View Customer Detials"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></td>
					  </tr>
                                          <?php } ?>
					  <tr>
						<td><?php echo $text_telephone; ?></td>
						<td><?php echo $country_code; ?> <?php echo $telephone; ?></td>
					  </tr>
					  <?php if ($fax) { ?>
					  <tr>
						<td><?php echo $text_fax; ?></td>
						<td><?php echo $fax; ?></td>
					  </tr>
					  <?php } ?>
					  <?php foreach ($account_custom_fields as $custom_field) { ?>
					  <tr>
						<td><?php echo $custom_field['name']; ?>:</td>
						<td><?php echo $custom_field['value']; ?></td>
					  </tr>
					  <?php } ?>
			<?php } ?>

			  
		
			  <?php if ($customer && $reward) { ?>
			  <tr>
				<td><?php echo $text_reward; ?></td>
				<td><?php echo $reward; ?>
				  <?php if (!$reward_total) { ?>
				  <button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
				  <?php } else { ?>
				  <button id="button-reward-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>
				  <?php } ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($order_status) { ?>
			  <tr>
				<td><?php echo $text_order_status; ?></td>
				<!-- <td id="order-status"><?php echo $order_status; ?></td> -->
				<td>
				<h3 class="my-order-title label" style="background-color: #<?= $order_status_color; ?>;   width: 7%;line-height: 2;" id="order-status" ><?php echo $order_status; ?></h3>
				</td>
			  </tr>
			  <?php } ?>
			  <?php if ($comment) { ?>
			  <tr>
				<td><?php echo $text_comment; ?></td>
				<td><?php echo $comment; ?></td>
			  </tr>
			  <?php } ?>

			 <?php if(!$this->user->isVendor()) { ?>

				  <?php if ($ip) { ?>
				  <tr>
					<td><?php echo $text_ip; ?></td>
					<td><?php echo $ip; ?></td>
				  </tr>
				  <?php } ?>
				  <?php if ($forwarded_ip) { ?>
				  <tr>
					<td><?php echo $text_forwarded_ip; ?></td>
					<td><?php echo $forwarded_ip; ?></td>
				  </tr>
				  <?php } ?>
				  <?php if ($user_agent) { ?>
				  <tr>
					<td><?php echo $text_user_agent; ?></td>
					<td><?php echo $user_agent; ?></td>
				  </tr>
				  <?php } ?>
				  <?php if ($accept_language) { ?>
				  <tr>
					<td><?php echo $text_accept_language; ?></td>
					<td><?php echo $accept_language; ?></td>
				  </tr>
				  <?php } ?>
			<?php } ?> 
			  <tr>
				<td><?php echo $text_date_added; ?></td>
				<td><?php echo $date_added; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $text_date_modified; ?></td>
				<td><?php echo $date_modified; ?></td>
			  </tr>

			  <tr>
					<td>Latitude:</td>
					<td><?php echo $login_latitude; ?></td>
				  </tr>

				   <tr>
					<td>Longitude:</td>
					<td><?php echo $login_longitude; ?></td>
				  </tr>
				  <?php if($login_longitude !='NA' &&  $login_latitude !='NA' && $login_longitude !='0' &&  $login_latitude !='0') { ?>
 					<tr>
					<td>Orderd Location:</td>
					<td>
					<input type="button" class="btn btn-primary" onclick="initOrderedLocationMapLoad()" value="View Map">
					</td>
					</tr>
					<tr>
					<td colspan=2>
					<div class="" id="orderdlocationmap" style="height: 100%; min-height: 600px;">
		    		</div>		    		  
					</td>
				  </tr>
				  <?php } ?>
				 
			</table>
		  </div>
		  <?php if(!$this->user->isVendor()){ ?>
		  	
		  
			  <div class="tab-pane" id="tab-payment">
				<table class="table table-bordered">
				  <?php foreach ($payment_custom_fields as $custom_field) { ?>
				  <tr>
					<td><?php echo $custom_field['name']; ?>:</td>
					<td><?php echo $custom_field['value']; ?></td>
				  </tr>
				  <?php } ?>
				  <tr>
					<td><?php echo $text_payment_method; ?></td>
					<td><?php echo $payment_method; ?></td>
				  </tr>

				  <tr>
					<td>Transaction ID</td>
                                        <td><input type="text" name="order_transaction_id" id="order_transaction_id" value="<?= $order_transaction_id ?>" > <button id="save_order_transaction_id" class="btn btn-primary" type="button" <?php if($order_status_id == 5) { ?> disabled <?php } ?> > Save </button></td>
				  </tr>

				</table>
			  </div>
		  <?php } ?>

		  <?php if ($shipping_method) { ?>
		  <div class="tab-pane" id="tab-shipping">
			<table class="table table-bordered">

			<?php if(!$this->user->isVendor()){ ?>
				<tr>
				<td><?= $text_name ?></td>
				<td><?php echo $shipping_name; ?></td>
			  </tr>
			  <!-- <tr>
				<td><?= $text_contact_no ?></td>
				<td><?php echo $shipping_contact_no; ?></td>
			  </tr> -->
			  <tr>
				<td><?= $text_city ?></td>
				<td><?php echo $shipping_city; ?></td>
			  </tr>

			  <!-- <tr>
				<td><?= $text_address ?></td>
				<td><?php echo $shipping_address; ?></td>
			  </tr>  --> 

			  <tr>
				<td><?= $text_flat_house_office ?></td>
				<td>

					<input type="text" name="shipping_flat_number" id="shipping_flat_number" value="<?= $shipping_flat_number ?>" style="width: 100%" >

					<button type="button" class="btn btn-primary" type="button" onclick="saveFlatNumber()" <?php if($order_status_id == 5) { ?> disabled <?php } ?> >
						Save
					</button>
            	</td>
			  </tr> 
			  
			  <tr>
				<td><?= $text_address ?></td>
				<td> 
				<?php echo $shipping_landmark; ?>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#s_address_edit" onclick="locationpickerLoad()" <?php if($order_status_id == 5) { ?> disabled <?php } ?> >
								Edit
							</button>
            	</td>
			  </tr> 

			  


			  <!-- <td><input type="text" name="shipping_delivery_timeslot" id="shipping_delivery_timeslot" value="<?= $delivery_timeslot ?>" > <button id="save_shipping_delivery_timeslot" class="btn btn-primary" type="button" onclick="saveOrderEditRawTimeslotOverrideFromAdmin()"> Save </button></td> -->


			<?php } ?>


			  
			  <!-- <tr>
				<td><?= $text_delivery_date ?></td>
				<td><?php echo date('d-m-Y',strtotime($delivery_date)); ?></td>
			  </tr>  
			   <tr>
				<td><?= $text_delivery_timeslot ?></td>
				<td><?php echo $delivery_timeslot; ?></td>
			  </tr>  --> 
			  <tr>
				<td><?= $text_delivery_date ?></td>
				<!-- <td><?php echo date('m/d/Y',strtotime($delivery_date)); ?></td> -->
				<td>
				<?php echo date('m/d/Y',strtotime($delivery_date)); ?>
				<!-- <div class="input-group date">
		            
		            <span class="input-group-btn">
		                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		                
		            </span>
		        </div>

		        <button style="margin-left: 3px" id="save_shipping_delivery_date" class="btn btn-primary" type="button" onclick="saveOrderEditRawTimeslotOverrideFromAdmin()"> Edit </button> -->
		        <?php if(!$shipped) { ?>

                    <a href="#" data-id='<?=$order_id ?>' style="font-size: 13px;text-decoration: underline;color: red" onclick="return timeslots(<?= $order_id ?>)" > <i class="fa fa-pencil" aria-hidden="true"></i> <strong><?= $text_edit_timeslot ?></strong></a>
                    
                <?php } ?>

                </td>

			  </tr>  
			   <tr>
				<td><?= $text_delivery_timeslot ?></td>
				<td><?php echo $delivery_timeslot; ?>
					
					<?php if(!$shipped) { ?>

	                    <a href="#" data-id='<?=$order_id ?>' style="font-size: 13px;text-decoration: underline;color: red" onclick="return timeslots(<?= $order_id ?>)" > <i class="fa fa-pencil" aria-hidden="true"></i> <strong><?= $text_edit_timeslot ?></strong></a>
	                    
	                <?php } ?>

				</td>
				<!-- <td><input type="text" name="shipping_delivery_timeslot" id="shipping_delivery_timeslot" value="<?= $delivery_timeslot ?>" > <button id="save_shipping_delivery_timeslot" class="btn btn-primary" type="button" onclick="saveOrderEditRawTimeslotOverrideFromAdmin()"> Save </button></td> -->

			  </tr>



			  <?php foreach ($shipping_custom_fields as $custom_field) { ?>
			  <tr>
				<td><?php echo $custom_field['name']; ?>:</td>
				<td><?php echo $custom_field['value']; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($shipping_method) { ?>
			  <tr>
				<td><?php echo $text_shipping_method; ?></td>
				<td><?php echo $shipping_method; ?></td>
			  </tr>
			  <?php } ?>
                          <tr>
                          <td>Delivery Executive</td>
                          <?php 
                          $order_delivery_executive = NULL;
                          $order_delivery_executive_id = NULL;
                          if(is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
                          $order_delivery_executive = $order_delivery_executive_details['firstname'].' '.$order_delivery_executive_details['lastname'];
                          $order_delivery_executive_id = $order_delivery_executive_details['delivery_executive_id'];
                          } ?>
                          <td><input type="text" name="order_delivery_executive" id="order_delivery_executive" value="<?=$order_delivery_executive; ?>" data_order_id="<?=$order_id ?>" data_delivery_executive_id="<?=$order_delivery_executive_id ?>">&nbsp;<button id="save_order_delivery_executive" class="btn btn-primary" type="button" <?php if($order_status_id == 5) { ?> disabled <?php } ?> > Save </button></td>
                          </tr>
                          <tr>
                              <td>Driver</td>
                              <?php 
                              $order_driver = NULL;
                              $order_driver_id = NULL;
                              if(is_array($order_driver_details) && $order_driver_details != NULL) {
                              $order_driver = $order_driver_details['firstname'].' '.$order_driver_details['lastname'];
                              $order_driver_id = $order_driver_details['driver_id'];
                              } ?>
                              <td><input type="text" name="order_driver" id="order_driver" value="<?=$order_driver ?>" data_order_id="<?=$order_id ?>" data_driver_id="<?=$order_driver_id ?>">&nbsp;<button id="save_order_driver" class="btn btn-primary" type="button" <?php if($order_status_id == 5) { ?> disabled <?php } ?> > Save </button></td>
                          </tr>
                          <tr>
                              <td>Vehicle Number</td>
                              <td><input type="text" name="order_vehicle_number" id="order_vehicle_number" value="<?=$order_vehicle_number ?>" data_order_id="<?=$order_id ?>">&nbsp;<button id="save_order_vehicle_number" class="btn btn-primary" type="button" <?php if($order_status_id == 5) { ?> disabled <?php } ?> > Save </button></td>
                          </tr>
                          <tr>
                              <td>Order Processing Group</td>
                              <?php 
                              $order_processing_group = NULL;
                              if(is_array($order_processing_group_details) && $order_processing_group_details != NULL) {
                              $order_processing_group = $order_processing_group_details['order_processing_group_name'];
                              } ?>
                              <td><?=$order_processing_group ?></td>
                          </tr>
                          <tr>
                              <td>Order Processor</td>
                              <?php 
                              $order_processor_name = NULL;
                              if(is_array($order_processor) && $order_processor != NULL) {
                              $order_processor_name = $order_processor['firstname'].' '.$order_processor['lastname'];
                              } ?>
                              <td><?=$order_processor_name ?></td>
                          </tr>
                          <?php if($store_id == 75) { ?>
                          <tr>
                           <td>Shipping Charges</td>
                           <td><input min="0" type="number" name="kw_shipping_charges" id="kw_shipping_charges" value="<?=round($kw_shipping_charges, 2) ?>" data_order_id="<?=$order_id ?>">&nbsp;<button id="save_kw_shipping_charges" class="btn btn-primary" type="button" <?php if($order_status_id == 5) { ?> disabled <?php } ?> > Save </button></td>
                          </tr>
                          <?php } ?>
			</table>
		  </div>
		  <?php } ?>
		  <?php if ($delivery_details) { ?>

			  <div class="tab-pane" id="tab-delivery">
				<table class="table table-bordered">
				   <tr>
					<td><?= $text_driver_image ?></td>
					<td> <img style="    height: 80px;width: 80px;" src="<?= $shopper_link.$delivery_data->driver->profile->drivers_photo ?>"> </td>
				  </tr>
				  <tr>
					<td><?= $text_driver_name ?></td>
					<td><?php echo $delivery_data->driver->first_name." ". $delivery_data->driver->last_name; ?></td>
				  </tr>
				  <tr>
					<td><?= $text_driver_contact_no ?></td>
					<td><?php echo $delivery_data->driver->phone_number; ?></td>
				  </tr>
				  <tr>
					<td><?= $text_pickup_notes?></td>
					<td><?php echo $delivery_data->pickup_notes; ?></td>
				  </tr>
				  <tr>
					<td><?= $text_dropoff_notes?></td>
					<td><?php echo $delivery_data->dropoff_notes; ?></td>
				  </tr>
				  <tr>
					<td><?= $text_driver_notes?></td>
					<td><?php echo $delivery_data->driver_notes; ?></td>
				  </tr>
				</table>
			  </div>
		 <?php } ?>

		 <?php if ($settlement_tab) { ?>
			 <div class="tab-pane" id="tab-settlement">
			 	<div id="settlement"></div>
				<table class="table table-bordered">
				  
				  <tr>
					<td><?= $text_original_amount?></td>
					<td><?= $original_total?></td>
				  </tr>
				  <tr>
					<td><?= $text_final_amount?></td>
					<td><input type="number" class="form-control" name="final_amount" id="final_amount" value="<?= $settlement_amount ?>"></td>
				  </tr>
				  <tr>
					
				  </tr>

				</table>

					<?php if(isset($settlement_done)) { ?>
						<button class="btn btn-primary" type="button" id="settle_payment" disabled="true">
						<?= $text_settle?>
						</button>

					<?php } else {?>
						<button class="btn btn-primary" type="button" id="settle_payment">
						<?= $text_settle?>
						</button>
					<?php } ?>
			  </div>
		  <?php } ?>

		  <div class="tab-pane" id="tab-product">
			
				<hr />
				 	<tr>
					<td class="left">
						<b><?= $text_store ?></b>:
						<span class="store_name"><?= $store_name ?></span>
					</td>
						<td class="left">
							
							<span style="line-height: 30px;">
								<b><?= $text_status ?></b>: 
								<span class="status">
									<?php echo $status; ?>
								</span>
							</span>

						</td>

					<!-- <td class="left" colspan="2">                    
						<b><?= $text_payment_status ?></b>:
						<span class="payment_status">
						<?= $total ?>    
						<?php if($commsion_received) { ?>
							<span class="text"><?= $text_paid ?></span>
							<?php if(!$this->user->isVendor()){ ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<button type="button" onclick="payment_status(<?= $store_id ?>,0);" class="btn btn-primary button"><?= $button_commision_unpaid ?></button>
							<?php } ?>
						<?php }else{ ?>
							<span class="text"><?= $text_unpaid ?></span>
							<?php if(!$this->user->isVendor()){ ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<button type="button" onclick="payment_status(<?= $store_id ?>,1);" class="btn btn-primary button"><?= $button_commision_pay ?></button>                        
							<?php } ?>
						<?php } ?>
						</span>
					</td> --> 

				<!--<?php if(!$this->user->isVendor()){ ?>
						<td class="left">
							<b><?= $text_commision ?></b>:
							<span class="commision">
								<?= $commission.'%' ?>
							</span>
						</td>
					<?php } ?>-->
					
				</tr>            
				<!-- <tr style="line-height: 40px;">
					<td colspan='2'>
						<b><?= $text_expected_delivery_time ?></b>:

						<span class="delivery_time_data">
							<?= date('d-m-Y',strtotime($delivery_date)).' ('.$delivery_timeslot.')' ?>
						</span>

					</td>
					
				</tr>	 -->

				<tr>
					<td><b><?= $text_expected_delivery_time ?></b></td>
					<td><?= date('m/d/Y',strtotime($delivery_date)).' ('.$delivery_timeslot.')' ?>
						

						<!-- <?php if(!$shipped) { ?>

		                    <a href="#" data-id='<?=$order_id ?>' style="font-size: 13px;text-decoration: underline;color: red" onclick="return timeslots(<?= $order_id ?>)" > <i class="fa fa-pencil" aria-hidden="true"></i> <strong><?= $text_edit_timeslot ?></strong></a>
		                    
		                <?php } ?> -->


					</td>				
				</tr>		

				<hr>
				<table class="table table-bordered table-hover">
				  <thead>
					<tr>

					  <td class="text-left"><?php echo $column_model; ?></td>
					  <td class="text-left"><?php echo $column_name; ?></td>
					  <td class="text-right"><?php echo trim($column_quantity,"( Ordered )!") ; ?></td>
					  <td class="text-right"><?php echo $column_unit; ?></td>

					  <td class="text-right"><?php echo $column_price; ?></td>
					  <td class="text-right"><?php echo $column_total; ?></td>
					  
					</tr>
				  </thead>
				  <tbody>
					<?php $i=0;  foreach ($products as $product) { ?>
					<tr>
						<td class="text-left"><?php echo $product['model']; ?></td>
					   <td class="text-left"><?php echo $product['name']; ?>
						<?php foreach ($product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?>
						<?php if($product['product_type'] == 'replacable') { ?>
                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                             <?= $text_replacable ?>
                            </span>
                        <?php } else { ?>
                            <span  class="badge badge-danger replacable" data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                <?= $text_not_replacable ?>
                            </span>
                        <?php } ?>


                        <?php foreach ($products_status as $product_status) { ?>
                            <?php if(trim($product['name']) == trim($product_status->product_name) && $product['unit'] == $product_status->unit) { $is_true = false; ?>

                            	<span> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>

                                <?php if($product_status->status == 'Remaining') {  $is_true = true;?>
                                    <span class="badge badge-warning">
                                        <?= $text_remaining ?>
                                    </span>
                                <?php } ?>

                                <?php if($product_status->status == 'In-Transit') { $is_true = true; ?>
                                    <span class="badge badge-info">
                                        <?= $text_intransit ?>
                                    </span>
                                <?php } ?>

                                <?php if($product_status->status == 'Completed') { $is_true = true; ?>
                                    <span class="badge badge-success">
                                        <?= $text_completed ?>
                                    </span>
                                <?php } ?>

                                <?php if($product_status->status == 'Canceled') { $is_true = true; ?>
                                    <span class="badge badge-danger">
                                        <?= $text_cancelled ?>
                                    </span>
                                <?php } ?>

                                <?php if(!$is_true) { ?>
                                    <span class="badge badge-primary">
                                        <?= $product_status->status ?>
                                    </span>
                                <?php } ?>
                        <?php } } ?>
						
						<br>						
							<?php echo $product['produce_type']; ?>
						</td>
					  
					  <td class="text-right"><?php echo $product['quantity']; ?></td>
						<td class="text-right"><?php echo $product['unit']; ?></td>

					  <td class="text-right"><?php echo $product['price']; ?></td>
					  <td class="text-right"><?php echo $product['total']; ?></td>
					  
					</tr>
					<?php } ?>
					<tbody>

					<?php 
						foreach ($totals as $total) { ?>

						<?php if(!$this->user->isVendor()) { ?>

							
								<tr>
									<td colspan="4"></td>
									<td class="text-right"><b><?php echo $total['title']; ?>:</b></td>
									<td class="text-right"><?php echo $total['text']; ?></td>
									
									
								</tr>
							

						<?php } else { ?>

							
								<tr>
									<td colspan="4"></td>
									<td class="text-right"><b><?php echo $total['title']; ?>:</b></td>
									<td class="text-right"><?php echo $total['text']; ?></td>
									
									
								</tr>
							

						<?php } ?>

						
						
						<?php } ?>
					</tbody>
					</table>



				</tbody>



				<hr />
			
			
			
		  </div>

		  <!-- orignal ordered products start-->
		   <?php if($is_edited  || 1==1) {?> 


		<div class="tab-pane" id="tab-original-product">     
					
				<table class="table table-bordered table-hover">
				  <thead>
					<tr>
						<td class="text-left"><?php echo $column_model; ?></td>
					  <td class="text-left"><?php echo $column_name; ?></td>
					  
					  <td class="text-right"><?php echo $column_quantity; ?></td>
					  <td class="text-right"><?php echo $column_unit.'( Ordered )'; ?></td>

					    <td class="text-right"><?php echo $column_quantity_update; ?></td>
						  <td class="text-right"><?php echo $column_unit.'( Updated )'; ?></td>

					  <td class="text-right"><?php echo $column_price; ?></td>
					  <td class="text-right"><?php echo $column_total; ?></td>
					  
					</tr>
				  </thead>
				  <tbody>
 	 
				  
					<?php $i=0;  foreach ($original_products as $original_product) { ?>
					<tr>
						<td class="text-left"><?php echo $original_product['model']; ?></td>
					   <td class="text-left"><?php echo $original_product['name']; ?>
						<?php foreach ($original_product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?>
						<?php if($original_product['product_type'] == 'replacable') { ?>
                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                             <?= $text_replacable ?>
                            </span>
                        <?php } else { ?>
                            <span  class="badge badge-danger replacable" data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                <?= $text_not_replacable ?>
                            </span>
                        <?php } ?>
<br>						
							<?php echo $original_product['produce_type']; ?>
							
						</td>
					  
					  <td class="text-right"><?php echo $original_product['quantity']; ?></td>
						<td class="text-right"><?php echo $original_product['unit']; ?></td>

						  <td class="text-right"><?php echo $original_product['quantity_updated']; ?></td>
						  <td class="text-right"><?php echo $original_product['unit_updated']; ?></td>

					  <td class="text-right"><?php echo $original_product['price']; ?></td>
					  <td class="text-right"><?php echo $original_product['total']; ?></td>
					  
					</tr>
					<?php } ?>
					<tbody style="display:none;">

						<tr>
							<td colspan="3"></td>
							<td class="text-right"><b>Total:</b></td>
							<td class="text-right"><?php echo $original_final_total; ?></td>
							
							
						</tr>
					</tbody>
					</table>
				</tbody>
		  </div>

		  <?php } ?> 

		   <?php if(!$this->user->isVendor() && $is_edited) {?>

			   <div class="tab-pane" id="tab-difference-product">     
					
					<hr />
				 <tr>
					<td class="left">
						<b><?= $text_store ?></b>:
						<span class="store_name"><?= $store_name ?></span>
					</td>
						<td class="left">
							
							<span style="line-height: 30px;">
								<b><?= $text_status ?></b>: 
								<span class="status">
									<?php echo $status; ?>
								</span>
							</span>

						</td>


					<!--<?php  if(!$this->user->isVendor()){ ?>
						<td class="left">
							<b><?= $text_commision ?></b>:
							<span class="commision">
								<?= $commission.'%' ?>
							</span>
						</td>
					<?php } ?>-->
					
				</tr>            
				<tr style="line-height: 40px;">
					<td colspan='2'>
						<b><?= $text_expected_delivery_time ?></b>:

						<span class="delivery_time_data">
							<?= date('d-m-Y',strtotime($delivery_date)).' ('.$delivery_timeslot.')' ?>
						</span>

					</td>
					
				</tr>	
				<hr>

				

				<?php if ($customer) { ?>
					  <tr style="line-height: 40px;">
						<td><?php echo $text_customer; ?></td>
						<td>
							<?php if(!$this->user->isVendor()){ ?>
								<?php echo $firstname; ?>
							<?php }else{ ?>
								<!-- <?php echo $firstname; ?> --> <!-- <?php echo $lastname; ?>        -->             
							<?php } ?>
						</td>

						<td><?php echo $text_telephone; ?></td>
						<td><?php echo $country_code; ?> <?php echo $telephone; ?></td>

					  </tr>
				<?php }  ?>
						

				<hr>

					<table class="table table-bordered table-hover">
					  <thead>
						<tr>
							<td class="text-left"><?php echo $column_model; ?></td>
						  <td class="text-left"><?php echo $column_name; ?></td>						  
						  <td class="text-right"><?php echo $column_quantity; ?></td>
						  <td class="text-right"><?php echo $column_unit.'( Ordered )'; ?></td>

						  <td class="text-right"><?php echo $column_quantity_update; ?></td>
						  <td class="text-right"><?php echo $column_unit.'( Updated )'; ?></td>

						  <td class="text-right"><?php echo $column_price; ?></td>
						  <td class="text-right"><?php echo $column_total; ?></td>
						  
						</tr>
					  </thead>
					  <tbody>
						<?php $i=0;  foreach ($difference_products as $difference_product) { ?>
						<tr>
							<td class="text-left"><?php echo $difference_product['model']; ?></td>
						   <td class="text-left"><?php echo $difference_product['name']; ?>
							<?php foreach ($difference_product['option'] as $option) { ?>
							<br />
							&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
							<?php } ?>
							<?php if($difference_product['product_type'] == 'replacable') { ?>
	                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
	                             <?= $text_replacable ?>
	                            </span>
	                        <?php } else { ?>
	                            <span  class="badge badge-danger replacable" data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
	                                <?= $text_not_replacable ?>
	                            </span>
	                        <?php } ?>

								
							</td>
						  
						  <td class="text-right"><?php echo $difference_product['quantity']; ?></td>
							<td class="text-right"><?php echo $difference_product['unit']; ?></td>

						  <td class="text-right"><?php echo $difference_product['quantity_updated']; ?></td>
						  <td class="text-right"><?php echo $difference_product['unit_updated']; ?></td>
						  <td class="text-right"><?php echo $difference_product['price']; ?></td>
						  <td class="text-right"><?php echo $difference_product['total']; ?></td>
						  
						</tr>
						<?php } ?>
						</table>
					</tbody>
			  </div>


		  <?php } ?>

		  <!-- orignal ordered products end -->
                  
                  <!-- order log -->
                  <div class="tab-pane" id="tab-order-log">     

                      <table class="table table-bordered table-hover">
                          <thead>
                              <tr>
                                  <td class="text-left">SKU</td>
                                  <td class="text-left">Name</td>

                                  <td class="text-right">Unit</td>
                                  <td class="text-right">Quantity( OLD )</td>
                                  <td class="text-right">Quantity( Updated )</td>
                                  <td class="text-right">Modified Date</td>
                              </tr>
                          </thead>
                          <tbody>


                             <?php foreach ($order_logs as $order_log) { ?>
                              <tr>
                                  <td class="text-left"><?php echo $order_log['model']; ?></td>
                                  <td class="text-left"><?php echo $order_log['name']; ?>												                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="" data-original-title="This product can be replaced by shipper with similar product, if not found.">
                                          Replacable                            </span>
                                      <br>						

                                  </td>

                                  <td class="text-right"><?php echo $order_log['unit']; ?></td>
                                  <td class="text-right"><?php echo $order_log['old_quantity']; ?></td>
                                  <td class="text-right"><?php echo $order_log['quantity']; ?></td>
                                  <td class="text-right"><?php echo $order_log['created_at']; ?></td>
                              </tr>
                             <?php } ?>
                          </tbody>
                      </table>

                  </div>
		  <!-- order log -->
		  <div class="tab-pane" id="tab-history">
			<div id="history"></div>
			<br />

			<?php if(!$this->user->isVendor()) { ?>

			<?php if ( !in_array( $order_status_id, $this->config->get( 'config_complete_status' ) ) && !in_array( $order_status_id, $this->config->get( 'config_refund_status' ) )  ) { ?>

				<fieldset>
				  <legend><?php echo $text_history; ?></legend>
				  <form class="form-horizontal">
					<div class="form-group">
					  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
					  <div class="col-sm-10">
						<select name="order_status_id" id="input-order-status" class="form-control">
						  <?php foreach ($order_statuses as $order_statuses) { ?>
						  <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
						  <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
						  <?php } else { ?>
						  <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
						  <?php } ?>
						  <?php } ?>
						</select>
					  </div>
					</div>
					<!-- <div class="form-group">
					  <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
					  <div class="col-sm-10">
						<input type="checkbox" name="notify" value="1" id="input-notify" />
					  </div>
					</div> -->
					<input type="hidden" name="notify" value="1" id="input-notify" />

					<div class="form-group">
					  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
					  <div class="col-sm-10">
						<textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
					  </div>
					</div>
				  </form>
				  <div class="text-right">
					<button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
				  </div>
				</fieldset>
				<?php } ?>

			<?php } else { ?>

				<?php if ( !in_array( $order_status_id, $this->config->get( 'config_complete_status' ) ) && !in_array( $order_status_id, $this->config->get( 'config_refund_status' ) )  ) { ?>


					<fieldset>
					  <legend><?php echo $text_history; ?></legend>
					  <form class="form-horizontal">
						<div class="form-group">
						  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
						  <div class="col-sm-10">
							<select name="order_status_id" id="input-order-status" class="form-control">
							    <?php foreach ($order_statuses as $order_statuses) { ?>

								  
							    	<?php if($allowedShippingMethods) { ?>

							    		<?php if ( in_array( $order_statuses['order_status_id'], $this->config->get( 'config_ready_for_pickup_status' ) ))  { ?>



											  	<?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
												  <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
												  <?php } else { ?>
												  <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
												  <?php } ?>
											 <?php } ?>
											 
							    	<?php } else { ?>

							    		<?php if ( in_array( $order_statuses['order_status_id'], $this->config->get( 'config_complete_status' ) )  || in_array( $order_statuses['order_status_id'], $this->config->get( 'config_ready_for_pickup_status' ) ) || in_array( $order_statuses['order_status_id'], $this->config->get( 'config_refund_status' ) )  ) { ?>


											  	<?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
												  <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
												  <?php } else { ?>
												  <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
												  <?php } ?>
											 <?php } ?>

										  

									    <?php } ?>

							    	<?php } ?>
							  		
							</select>
						  </div>
						</div>
						<!-- <div class="form-group">
						  <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
						  <div class="col-sm-10">
							<input type="checkbox" name="notify" value="1" id="input-notify" />
						  </div>
						</div> -->

						<input type="hidden" name="notify" value="1" id="input-notify" />

						<div class="form-group">
						  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
						  <div class="col-sm-10">
							<textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
						  </div>
						</div>
					  </form>
					  <div class="text-right">
						<button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
					  </div>
				</fieldset>

			 	<?php } ?>

			<?php } ?>

			<!-- <?= $order_status_name ?> -->
			<?php if(isset($order_status_name) && strpos($order_status_name, 'raud') !== false) { ?>
				<div >
					<button id="button-not-fraud" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_not_fraud; ?></button>
					<!-- <button id="button-reverse-payment" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_reverse_payment; ?></button> -->
				</div>

			<?php } ?>
			
		  </div>
		  <?php if ($payment_action) { ?>
		  <div class="tab-pane" id="tab-action"> <?php echo $payment_action; ?> </div>
		  <?php } ?>
		  <?php if ($maxmind_id) { ?>
		  <div class="tab-pane" id="tab-fraud">
			<table class="table table-bordered">
			  <?php if ($country_match) { ?>
			  <tr>
				<td><?php echo $text_country_match; ?></td>
				<td><?php echo $country_match; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($country_code) { ?>
			  <tr>
				<td><?php echo $text_country_code; ?></td>
				<td><?php echo $country_code; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($high_risk_country) { ?>
			  <tr>
				<td><?php echo $text_high_risk_country; ?></td>
				<td><?php echo $high_risk_country; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($distance) { ?>
			  <tr>
				<td><?php echo $text_distance; ?></td>
				<td><?php echo $distance; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_region) { ?>
			  <tr>
				<td><?php echo $text_ip_region; ?></td>
				<td><?php echo $ip_region; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_city) { ?>
			  <tr>
				<td><?php echo $text_ip_city; ?></td>
				<td><?php echo $ip_city; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_latitude) { ?>
			  <tr>
				<td><?php echo $text_ip_latitude; ?></td>
				<td><?php echo $ip_latitude; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_longitude) { ?>
			  <tr>
				<td><?php echo $text_ip_longitude; ?></td>
				<td><?php echo $ip_longitude; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_isp) { ?>
			  <tr>
				<td><?php echo $text_ip_isp; ?></td>
				<td><?php echo $ip_isp; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_org) { ?>
			  <tr>
				<td><?php echo $text_ip_org; ?></td>
				<td><?php echo $ip_org; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_asnum) { ?>
			  <tr>
				<td><?php echo $text_ip_asnum; ?></td>
				<td><?php echo $ip_asnum; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_user_type) { ?>
			  <tr>
				<td><?php echo $text_ip_user_type; ?></td>
				<td><?php echo $ip_user_type; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_country_confidence) { ?>
			  <tr>
				<td><?php echo $text_ip_country_confidence; ?></td>
				<td><?php echo $ip_country_confidence; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_region_confidence) { ?>
			  <tr>
				<td><?php echo $text_ip_region_confidence; ?></td>
				<td><?php echo $ip_region_confidence; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_city_confidence) { ?>
			  <tr>
				<td><?php echo $text_ip_city_confidence; ?></td>
				<td><?php echo $ip_city_confidence; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_postal_confidence) { ?>
			  <tr>
				<td><?php echo $text_ip_postal_confidence; ?></td>
				<td><?php echo $ip_postal_confidence; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_postal_code) { ?>
			  <tr>
				<td><?php echo $text_ip_postal_code; ?></td>
				<td><?php echo $ip_postal_code; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_accuracy_radius) { ?>
			  <tr>
				<td><?php echo $text_ip_accuracy_radius; ?></td>
				<td><?php echo $ip_accuracy_radius; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_net_speed_cell) { ?>
			  <tr>
				<td><?php echo $text_ip_net_speed_cell; ?></td>
				<td><?php echo $ip_net_speed_cell; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_metro_code) { ?>
			  <tr>
				<td><?php echo $text_ip_metro_code; ?></td>
				<td><?php echo $ip_metro_code; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_area_code) { ?>
			  <tr>
				<td><?php echo $text_ip_area_code; ?></td>
				<td><?php echo $ip_area_code; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_time_zone) { ?>
			  <tr>
				<td><?php echo $text_ip_time_zone; ?></td>
				<td><?php echo $ip_time_zone; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_region_name) { ?>
			  <tr>
				<td><?php echo $text_ip_region_name; ?></td>
				<td><?php echo $ip_region_name; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_domain) { ?>
			  <tr>
				<td><?php echo $text_ip_domain; ?></td>
				<td><?php echo $ip_domain; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_country_name) { ?>
			  <tr>
				<td><?php echo $text_ip_country_name; ?></td>
				<td><?php echo $ip_country_name; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_continent_code) { ?>
			  <tr>
				<td><?php echo $text_ip_continent_code; ?></td>
				<td><?php echo $ip_continent_code; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ip_corporate_proxy) { ?>
			  <tr>
				<td><?php echo $text_ip_corporate_proxy; ?></td>
				<td><?php echo $ip_corporate_proxy; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($anonymous_proxy) { ?>
			  <tr>
				<td><?php echo $text_anonymous_proxy; ?></td>
				<td><?php echo $anonymous_proxy; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($proxy_score) { ?>
			  <tr>
				<td><?php echo $text_proxy_score; ?></td>
				<td><?php echo $proxy_score; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($is_trans_proxy) { ?>
			  <tr>
				<td><?php echo $text_is_trans_proxy; ?></td>
				<td><?php echo $is_trans_proxy; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($free_mail) { ?>
			  <tr>
				<td><?php echo $text_free_mail; ?></td>
				<td><?php echo $free_mail; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($carder_email) { ?>
			  <tr>
				<td><?php echo $text_carder_email; ?></td>
				<td><?php echo $carder_email; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($high_risk_username) { ?>
			  <tr>
				<td><?php echo $text_high_risk_username; ?></td>
				<td><?php echo $high_risk_username; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($high_risk_password) { ?>
			  <tr>
				<td><?php echo $text_high_risk_password; ?></td>
				<td><?php echo $high_risk_password; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($bin_match) { ?>
			  <tr>
				<td><?php echo $text_bin_match; ?></td>
				<td><?php echo $bin_match; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($bin_country) { ?>
			  <tr>
				<td><?php echo $text_bin_country; ?></td>
				<td><?php echo $bin_country; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($bin_name_match) { ?>
			  <tr>
				<td><?php echo $text_bin_name_match; ?></td>
				<td><?php echo $bin_name_match; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($bin_name) { ?>
			  <tr>
				<td><?php echo $text_bin_name; ?></td>
				<td><?php echo $bin_name; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($bin_phone_match) { ?>
			  <tr>
				<td><?php echo $text_bin_phone_match; ?></td>
				<td><?php echo $bin_phone_match; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($bin_phone) { ?>
			  <tr>
				<td><?php echo $text_bin_phone; ?></td>
				<td><?php echo $bin_phone; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($customer_phone_in_billing_location) { ?>
			  <tr>
				<td><?php echo $text_customer_phone_in_billing_location; ?></td>
				<td><?php echo $customer_phone_in_billing_location; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ship_forward) { ?>
			  <tr>
				<td><?php echo $text_ship_forward; ?></td>
				<td><?php echo $ship_forward; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($city_postal_match) { ?>
			  <tr>
				<td><?php echo $text_city_postal_match; ?></td>
				<td><?php echo $city_postal_match; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($ship_city_postal_match) { ?>
			  <tr>
				<td><?php echo $text_ship_city_postal_match; ?></td>
				<td><?php echo $ship_city_postal_match; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($score) { ?>
			  <tr>
				<td><?php echo $text_score; ?></td>
				<td><?php echo $score; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($explanation) { ?>
			  <tr>
				<td><?php echo $text_explanation; ?></td>
				<td><?php echo $explanation; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($risk_score) { ?>
			  <tr>
				<td><?php echo $text_risk_score; ?></td>
				<td><?php echo $risk_score; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($queries_remaining) { ?>
			  <tr>
				<td><?php echo $text_queries_remaining; ?></td>
				<td><?php echo $queries_remaining; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($maxmind_id) { ?>
			  <tr>
				<td><?php echo $text_maxmind_id; ?></td>
				<td><?php echo $maxmind_id; ?></td>
			  </tr>
			  <?php } ?>
			  <?php if ($error) { ?>
			  <tr>
				<td><?php echo $text_error; ?></td>
				<td><?php echo $error; ?></td>
			  </tr>
			  <?php } ?>
			</table>
		  </div>
		  <?php } ?>
		  <?php if ($questions) { ?>
			  <div class="tab-pane" id="tab-question">
				<table class="table table-bordered">
				  <?php if ($questions) { ?>

				  	<tr>
						<th>Question</th>
						<th>Response</th>
					</tr>

				  	<?php foreach($questions as $question) { ?>
					  	<tr>
							<td><?php echo $question['question']; ?></td>
							<td><?php  echo ($question['response'] == 1)?  $text_yes:$text_no; ?></td>
						</tr>
				  	<?php } ?>
				  
				  <?php } ?>
				  
				</table>
			  </div>
		  <?php } ?>

		</div>
	  </div>
	</div>
  </div>

<div class="modal fade" id="s_address_edit" tabindex="-1" role="dialog" aria-labelledby="s_address_editLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

      	<div class="row">
			<div class="col-sm-7">
				<h2 class="modal-title" id="s_address_editLabel">Edit</h2>
			</div>
			<div class="col-sm-2">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>

			<div class="col-sm-2">
				

       			<button type="button" class="btn btn-primary" onclick="updateNewShippingAddressFromAdmin()">Save changes</button>
			</div>
		</div>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
					<div class="row" style="width: 100%">
						<div class="form-group col-sm-12 ">
							<div class="row">
								<!-- <div class="col-sm-12">
									<label for="shipping-flat-number">Unit: </label>
									<div>
										<input type="hidden" name="flat_number" value="<?=$shipping_flat_number;?>" id="shipping-flat-number" style="    width: 100%;max-width: 100%;"  />
									</div>
								</div> -->

								<input type="hidden" name="flat_number" value="<?=$shipping_flat_number;?>" id="shipping-flat-number"  />


								<div class="col-sm-12">
									<label for="shipping-flat-number"><?= $text_address ?></label>
									<div>
										<input type="text" name="flat_number" value="<?=$shipping_landmark;?>" id="shipping-address" class="qwerty" autocomplete="off" style="    width: 100%;max-width: 100%;" />

									</div>
								</div>

								<!-- <div class="col-sm-6">
									<label for="shipping-zip">Zipcode: </label>
									<div>
										<input type="text" name="flat_number" value="<?=$shipping_zipcode;?>" id="shipping-zip" class="disabled" readonly style="background:#eee;"/>
									</div>
								</div> -->

								<input type="hidden" name="flat_number" value="<?=$shipping_zipcode;?>" id="shipping-zip" class="disabled" readonly style="background:#eee;"/>

							</div>
						</div>
					</div>
					<input type="hidden" name="shipping_latitude" id="shipping_latitude" value="<?=$shipping_lat;?>"/>
					<input type="hidden" name="shipping_longitude" id="shipping_longitude" value="<?=$shipping_lon;?>"/>
					<input type="hidden" name="shipping_building_name" id="shipping_building_name" value="<?=$shipping_building_name;?>"/>
				</form>


				<div id="us1" style="width: 100%; height: 400px;"></div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateNewShippingAddressFromAdmin()">Save changes</button> -->
      </div>
    </div>
  </div>
</div>

<div class="timeslotModal-popup">
    <div class="modal fade" id="timeslotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <center> <h3 class="modal-title"> Please select the new timeslot for your order </h3> </center>

            </div>
            <div class="modal-body">
                
                <div class="store-find">
                    
                    <div class="checkout-time-table-new" id="delivery-time-wrapper"></div>  
                </div>
            </div>
        </div>
    </div>
    </div>
</div>


  <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

    <script type="text/javascript" src="ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.8"></script>

  <script type="text/javascript"><!--

  $('.date').datetimepicker({
        pickTime: false
    });

    function initMapLoad() {
    
    	initMap(<?= $pointB ?>,<?= $pointA ?>);
    	

		return false;
    }
    
    function initMapLoads(presentlocation,deliverylocation,driverDetails) {
    
    	initMaps(presentlocation,deliverylocation,driverDetails);
        return false;
    }

	function locationpickerLoad() {
    
		$('#us1').locationpicker({
			location: {
					latitude: "<?= $shipping_lat ? $shipping_lat: 0; ?>",
					longitude: "<?= $shipping_lon ? $shipping_lon: 0; ?>"
			},
			inputBinding: {
					locationNameInput: $('#shipping-address'),
			},
			enableReverseGeocode: true,
			radius: 0,
			enableAutocomplete: true,
			zoom:13,
			onchanged: function(currentLocation, radius, isMarkerDropped, ss) {
					let addressComponent = $(this).locationpicker('map').location.addressComponents;
					$('#shipping-address').val(`${addressComponent.addressLine1 ? addressComponent.addressLine1 + ", ": ""}${addressComponent.city ? addressComponent.city + ", " : ""}${addressComponent.stateOrProvince ? addressComponent.stateOrProvince + ", ":""}${addressComponent.country ? addressComponent.country: ""}`);
					$("#shipping_latitude").val(currentLocation.latitude)
					$("#shipping_longitude").val(currentLocation.longitude)
					$("#shipping_building_name").val(addressComponent.addressLine1 ? addressComponent.addressLine1: "")
					$("#shipping-zip").val(addressComponent.postalCode ? addressComponent.postalCode: "")
			},
		}); 

		return false;
	}



    

  $(document).delegate('#save_order_transaction_id', 'click', function() {

  	data = { transaction_id :  $('#order_transaction_id').val()};
  	console.log(data);
  	console.log("save_order_transaction_id");
	$.ajax({
		url: 'index.php?path=sale/order/save_order_transaction_id&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$('#save_order_transaction_id').button('loading');
		},
		complete: function() {
			$('#save_order_transaction_id').button('reset');
		},									
		success: function(json) {
			
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
                                                                               
$(document).delegate('#button-invoice', 'click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-invoice').button('loading');			
		},
		complete: function() {
			$('#button-invoice').button('reset');
		},
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('#tab-order').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['invoice_no']) {
				$('#button-invoice').replaceWith(json['invoice_no']);
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-add', 'click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-add').button('loading');
		},
		complete: function() {
			$('#button-reward-add').button('reset');
		},									
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('#button-reward-add').replaceWith('<button id="button-reward-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>');
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-remove', 'click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-remove').button('loading');
		},
		complete: function() {
			$('#button-reward-remove').button('reset');
		},				
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('#button-reward-remove').replaceWith('<button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>');
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-add', 'click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-add').button('loading');
		},
		complete: function() {
			$('#button-commission-add').button('reset');
		},			
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('#button-commission-add').replaceWith('<button id="button-commission-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>');
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-remove', 'click', function() {
	$.ajax({
		url: 'index.php?path=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-remove').button('loading');
		
		},
		complete: function() {
			$('#button-commission-remove').button('reset');
		},		
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('#button-commission-remove').replaceWith('<button id="button-commission-add" class="btn btn-success btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_add; ?></button>');
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();
	
	$('#history').load(this.href);
});			

$('#history').load('index.php?path=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');


$('#button-not-fraud').on('click', function() {
  
	$.ajax({
		url: 'index.php?path=sale/order/notFraudApi&token=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('#button-not-fraud').button('loading');			
		},
		complete: function() {
			$('#button-not-fraud').button('reset');	
		},
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} 
		
			if (json['success']) {
				$('#history').load('index.php?path=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				
				$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('textarea[name=\'comment\']').val('');
				
				$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());			
			}	

			$('#button-not-fraud').hide();
			//location.reload();		
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-reverse-payment').on('click', function() {
  
	$.ajax({
		url: 'index.php?path=sale/order/reversePaymentApi&token=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('#button-reverse-payment').button('loading');			
		},
		complete: function() {
			$('#button-reverse-payment').button('reset');	
		},
		success: function(json) {

			$('.alert').remove();
			
			if (json['error']) {
				$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} 
		
			if (json['success']) {
				$('#history').load('index.php?path=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				
				$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('textarea[name=\'comment\']').val('');
				
				$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());			
			}

		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-history').on('click', function() {
	 
	 //alert($('select[name=\'order_status_id\'] option:selected').text());
  if(typeof verifyStatusChange == 'function'){
	if(verifyStatusChange() == false){
	  return false;
	}
  }

if($('select[name=\'order_status_id\'] option:selected').text()=='Delivered')
{
	 
	$.ajax({
		url: 'index.php?path=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-invoice').button('loading');			
		},
		
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('#tab-order').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['invoice_no']) {
				$('#button-invoice').replaceWith(json['invoice_no']);
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
	$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=0' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('#button-history').button('loading');			
		},
		complete: function() {
			$('#button-history').button('reset');	
		},
		success: function(json) {
			 $('.alert').remove();
			
			if (json['error']) {
				$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} 
		
			if (json['success']) {
				$('#history').load('index.php?path=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				
				$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('textarea[name=\'comment\']').val('');
				
				$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());			

				location = location;
			}	 

		},			
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			$('#history').load('index.php?path=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				 $('#button-history').button('reset');			

			 
		}
	});
});
//--></script></div>


<script src="ui/javascript/jquery/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datepicker/css/datepicker.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
	
$(".datepicker" ).datepicker({
	format: 'yyyy-mm-dd', 
	startDate: '-0d'
}).on('changeDate', function(e){
	get_ts($(this).attr('data-id'), $(this).val());    
});    

//--></script>
  
<script>
	
	$(document).delegate('#settle_payment', 'click', function() {

		if(confirm('Are You Sure?')) {
			final_amount = $('#final_amount').val();


			$.ajax({
		url: 'index.php?path=sale/order/settle_payment&token=<?php echo $token; ?>',
		type: 'post',
		data: 'order_id=<?php echo $order_id; ?>&final_amount='+final_amount+'&customer_id=<?php echo $customer_id?>',
		beforeSend: function() {
		},
		complete: function() {
			$('.attention').remove();
		},
		success: function(data) {
			console.log("settle+payment_action");
			console.log(data);

			$('#settlement').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + data['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
			});
		}
	});

	function updateNewShippingAddressFromAdmin(){


        data = {
            order_id : '<?= $order_id ?>',
            shipping_latitude : $('#shipping_latitude').val(),
            shipping_longitude : $('#shipping_longitude').val(),
            shipping_building_name : $('#shipping_building_name').val(),
            shipping_flat_number : $('#shipping-flat-number').val(),
            shipping_landmark: $('#shipping-address').val(),
	    shipping_zipcode: $("#shipping-zip").val(),
            user_id : '<?=$this->user->getId() ?>'
        }

        $.ajax({
            url: '<?= $save_shipping_url_override ?>',//'index.php?path=checkout/edit_order/updateNewShippingAddressFromAdmin',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        return false;
	}



	function status($store_id,$status_value, $status_text){
		if(confirm('Are You Sure?')){
			$.ajax({
		url: 'index.php?path=sale/order/status&token=<?php echo $token; ?>',
		type: 'post',
		data: 'status=' + $status_value + '&order_id=<?php echo $order_id; ?>&store_id='+$store_id,
		beforeSend: function() {
		},
		complete: function() {
			$('.attention').remove();
		},
		success: function(data) {
					$('#store_'+$store_id+' .status').html($status_text);
		}
			});
		}
	}
	
	<?php if(!$this->user->isVendor()){ ?>
	function payment_status($store_id,$status){

		if(confirm('Are You Sure?')){
			$.ajax({
		url: 'index.php?path=sale/order/payment_status&token=<?php echo $token; ?>',
		type: 'post',
		data: 'status=' + $status + '&order_id=<?php echo $order_id; ?>&store_id='+$store_id,
		beforeSend: function() {
		},
		complete: function() {
			$('.attention').remove();
		},
		success: function(data) {
		if($status==1){
			$(' .payment_status .text').html('Paid');
			$('.payment_status button').html('Undo Vendor Pay').attr('onclick',"payment_status("+$store_id+",0);");
		}else{
			$('.payment_status .text').html('Unpaid');
			$('.payment_status button').html('Pay to Vendor').attr('onclick',"payment_status("+$store_id+",1);");
		}
		}
			});
		}
	}

	
	<?php } ?>

function get_ts($store_id, $date){
	$.post('<?= HTTP_SERVER ?>index.php?path=sale/order/get_home_ts&token=<?= $token ?>',{ store_id: $store_id, date: $date }, function(data){
		$('#store_'+$store_id+' .delivery_timeslot').html(data);
	});
}

//save timeslot in store_id 
$('.delivery_timeslot').change(function(){
   
  $.post('<?= HTTP_SERVER ?>index.php?path=sale/order/save_timeslots&order_id=<?= $order_id ?>&token=<?= $token ?>', $('.delivery_date, .delivery_timeslot').serialize());    
  
  $html = $(this).parent().find('.delivery_date').val()+' ('+$(this).val()+')';
   
  $(this).parent().parent().find('.delivery_time_data').html($html);
   
});

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js"></script>
<script type="text/javascript" src="ui/javascript/app-maps-google-delivery.js"></script>
<script type="text/javascript">

	function timeslots(order_id) {
        console.log("timeslots api");

        $('#timeslotModal').modal('show');
        $('#show_message').hide();
        $('#show_message').html('');

        $.ajax({
            url: '<?= $get_timeslot_url ?>',
            type: 'get',
            dataType: 'html',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                $('#delivery-time-wrapper').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        return false;
    }

    

    function saveFlatNumber() {
        console.log("saveFlatNumber api");

        //$('#timeslotModal').modal('show');

        data = {
            order_id : '<?=$order_id ?>',
            shipping_flat_number : $('#shipping_flat_number').val(),
            user_id : '<?=$this->user->getId() ?>'
        }

        console.log(data);


        $.ajax({
            url: '<?= $save_flat_addressonly ?>',//'index.php?path=checkout/delivery_time/saveOrderEditRawTimeslotOverrideFromAdmin',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
               
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        return false;
    }

	function saveOrderEditRawTimeslotOverrideFromAdmin() {
        console.log("saveOrderEditRawTimeslotOverrideFromAdmin api");

        //$('#timeslotModal').modal('show');

        data = {
            order_id : '<?=$order_id ?>',
            delivery_timeslot : $('#shipping_delivery_timeslot').val(),
            delivery_date : $('#delivery_date').val(),
            user_id : '<?=$this->user->getId() ?>'
        }

        console.log(data);


        $.ajax({
            url: '<?= $save_timeslot_url_override ?>',//'index.php?path=checkout/delivery_time/saveOrderEditRawTimeslotOverrideFromAdmin',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
               
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        return false;
    }

    function saveEditTimeSlot(order_id,delivery_date,delivery_timeslot) {
        console.log("saveEditTimeSlot api");

        //$('#timeslotModal').modal('show');
        data = {
            order_id : order_id,
            delivery_timeslot : delivery_timeslot,
            delivery_date : delivery_date,
            user_id : '<?=$this->user->getId() ?>'
        }

        $.ajax({
            url: '<?= $save_timeslot_url ?>',//'index.php?path=checkout/delivery_time/saveOrderEditRawTimeslot',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);

                $('#show_message').html(html.message);
                $('#show_message').show();
                //$('#timeslotModal').modal('close');

                if(html.message) {
                    setTimeout(function(){ window.location.reload(false); }, 1000);
                }
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        return false;
    }


	$(document).ready(function(){
      	//initMap(<?= $pointB ?>,<?= $pointA ?>);
    });
    
	function initOrderedLocationMapLoad () {
		const myLatLng = { lat: <?php echo $login_latitude ?>, lng:<?php echo $login_longitude ?> };
		const map = new google.maps.Map(document.getElementById("orderdlocationmap"), {
			zoom: 4,
			center: myLatLng,
		});
		new google.maps.Marker({
			position: myLatLng,
			map,
			title: "Ordered Location!",
		});
		}

</script>
<script>
  $driverName="";
$('input[name=\'order_driver\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=drivers/drivers_list/autocompletebyDriverName&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request)+'&filter_company=' +$driverName,
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['driver_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'order_driver\']').val(item['label']);
    $('input[name=\'order_driver\']').attr('data_driver_id',item['value']);
  } 
});

$('input[name=\'order_delivery_executive\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=executives/executives_list/autocompletebyExecutiveName&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['executive_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'order_delivery_executive\']').val(item['label']);
    $('input[name=\'order_delivery_executive\']').attr('data_delivery_executive_id',item['value']);
  } 
});

$(document).delegate('#save_order_driver', 'click', function() {
    var driver_id = $('input[name=\'order_driver\']').attr('data_driver_id');
    var order_id = $('input[name=\'order_driver\']').attr('data_order_id');
    data = {
            order_id : order_id,
            driver_id : driver_id
    }
    $.ajax({
            url: 'index.php?path=sale/order/SaveOrUpdateOrderDriverDetails&token=<?php echo $token; ?>',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
               
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
});

$(document).delegate('#save_order_delivery_executive', 'click', function() {
    var delivery_executive_id = $('input[name=\'order_delivery_executive\']').attr('data_delivery_executive_id');
    var order_id = $('input[name=\'order_delivery_executive\']').attr('data_order_id');
    data = {
            order_id : order_id,
            delivery_executive_id : delivery_executive_id
    }
    $.ajax({
            url: 'index.php?path=sale/order/SaveOrUpdateOrderDeliveryExecutiveDetails&token=<?php echo $token; ?>',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
               
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
});

$(document).delegate('#save_order_vehicle_number', 'click', function() {
    var vehicle_number = $('input[name=\'order_vehicle_number\']').val();
    var order_id = $('input[name=\'order_vehicle_number\']').attr('data_order_id');
    data = {
            order_id : order_id,
            vehicle_number : vehicle_number
    }
    $.ajax({
            url: 'index.php?path=sale/order/SaveOrUpdateOrderVehilceDetails&token=<?php echo $token; ?>',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
               
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
});

$(document).delegate('#save_kw_shipping_charges', 'click', function() {
    var kw_shipping_charges = $('input[name=\'kw_shipping_charges\']').val();
    var order_id = $('input[name=\'kw_shipping_charges\']').attr('data_order_id');
    data = {
            order_id : order_id,
            kw_shipping_charges : kw_shipping_charges
    }
    $.ajax({
            url: 'index.php?path=sale/order/SaveOrUpdateOrderShippingChargesDetails&token=<?php echo $token; ?>',
            type: 'post',
            data: data,
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            },
            success: function(html) {
                console.log(html);
                setTimeout(function(){ window.location.reload(false); }, 1000);
               
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
});

$(document).delegate('#show_driver_location', 'click', function(e) {
e.preventDefault();
console.log($(this).data('order_id'));
var delivery_latitide = $(this).data('delivery_latitide');
var delivery_longitude = $(this).data('delivery_longitude');
var delivery_location = $(this).data('delivery_latitide')+','+$(this).data('delivery_longitude');
var present_location = '-1.3068048692017753,36.65802472191967';
console.log(delivery_location);
console.log(present_location);



                $.ajax({
		url: 'index.php?path=amitruck/amitruck/getDriverLocation&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + encodeURIComponent($(this).data('order_id')),
		beforeSend: function() {
                // setting a timeout
                },
                success: function(json) {	 
                    //console.log(json.driverLocation.latitude);
                    if(json.status == 200) {
                    var present_location = json.driverLocation.latitude+','+json.driverLocation.longitude;
                    initMapLoads(present_location,delivery_location,json.driver_details);
                    } else {
                    alert(json.errors);
                    }
                    //setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
	           alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
		}
                }); 

});

</script>
<?php echo $footer; ?> 