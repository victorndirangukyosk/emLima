<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" onclick="save('save')" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
				<button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>

				<?php if (!$this->user->isVendor()) { ?>

					<button type="submit" onclick="save('new')" form="form-store" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>

				<?php } ?>    

						
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-contact" data-toggle="tab"><?= $tab_contact ?></a></li>
						<li><a href="#tab-local" data-toggle="tab"><?= $tab_delivery ?></a></li>
						<li><a href="#tab-design" data-toggle="tab"><?= $tab_image ?></a></li>  

						<?php if (!$this->user->isVendor()) { ?>

							<li><a href="#tab-commission" data-toggle="tab"><?= $tab_commission ?></a></li>

						<?php } ?>                     

						<li class="active"><a href="#tab-location" data-toggle="tab"><?= $tab_location ?></a></li>  
						<li class="<?php echo $pickupEnabled  ? '' :'hide' ?>" id="pickup_delivery_time" ><a href="#tab-pickup-timeslot" data-toggle="tab"><?= $tab_pickup_timeslot ?></a></li>  

						<li class="<?php echo $storeDeliveryEnabled  ? '' :'hide' ?>" id="delivery_time">
							<a href="#tab-delivery-timeslot" data-toggle="tab"><?= $tab_delivery_timeslot ?></a>
						</li>  
						<li>
							<a href="#tab-open-hours" data-toggle="tab"><?= $tab_open_hours ?></a>
						</li>       


					</ul>

					<div class="tab-content">
						
						<div class="tab-pane active" id="tab-location">
	  
							<div class="alert alert-info">
								<button class="close" data-dismiss="alert">&times;</button>
								<?= $text_description ?> 
							</div>
							
							<div style="margin-bottom: 15px;">
								<label><?= $text_search ?></label>
								<input class="form-control" type="text" id="us2-address" style="max-width: 100%" />                                
							</div>

							<div style="margin-bottom: 15px;">
								<label>Zipcode</label>
								<input class="form-control" type="text" name="store_zipcode" value="<?= $store_zipcode?>" style="max-width: 100%" />

								                                
							</div>
							<!--<input type="hidden" name="store_zipcode" value="00100"/>-->
							
							<input type="button" class="btn btn-primary" onclick="locationpickerLoad()" value="View Map" /> 

							<div id="us1" style="width: 100%; height: 400px;"></div>
							
							<input type="hidden" name="latitude" value="<?= $latitude ?>" />
							<input type="hidden" name="longitude" value="<?= $longitude ?>" />
							
							
						</div>
							
						<div class="tab-pane" id="tab-contact">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-email"><?= $entry_email ?></label>
								<div class="col-sm-10">
                                                                        <textarea name="email" rows="5" placeholder="Email" id="input-email" class="form-control"><?php echo $email; ?></textarea>
									<!--<input type="text" name="email" value="<?php echo $email; ?>" placeholder="Email" id="input-email" class="form-control" />-->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-email"><?= $entry_telephone ?></label>
								<div class="col-sm-10 input-group" style="left: 14px;">
									<span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
									<input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?= $entry_telephone ?>" id="input-telephone" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" />

									

								</div>
								<?php if ($error_telephone) { ?>
									<label class="col-sm-2 control-label"></label>
									<div class="text-danger"><?php echo $error_telephone; ?></div>
								<?php } ?>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-fax"><?= $entry_fax ?></label>
								<div class="col-sm-10 input-group" style="left: 14px;">
									<span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
									<input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?= $entry_fax ?>" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9"/>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tab-general">
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
								<div class="col-sm-10">
									<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
									<?php if ($error_name) { ?>
									<div class="text-danger"><?php echo $error_name; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-about_us"><?php echo $entry_about_us; ?></label>
								<div class="col-sm-10">
									<textarea name="about_us" rows="5" placeholder="<?php echo $entry_about_us; ?>" id="input-about_us" class="form-control"><?php echo $about_us; ?></textarea>
									<?php if ($error_about_us) { ?>
									<div class="text-danger"><?php echo $error_address; ?></div>
									<?php } ?>
								</div>
							</div>


							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_category; ?></label>
								<div class="col-sm-10">
									<select class="selectpicker storeCategories" name="storeCategories[]" multiple data-selected-text-format="countSelectedText">
									<?php foreach($top_categories as $category){ ?>
									 		<option value="<?php echo $category['category_id']; ?>"> 
									 			<?php echo $category['name']; ?>
									 		</option>
									<?php	} ?>
									</select>
								</div>
							</div>

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-seo_url"><?php echo $entry_seo_url ?></label>
								<div class="col-sm-10">
									<input type="text" name="seo_url" value="<?php echo $seo_url; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo_url" class="form-control" />
									<?php if ($error_seo_url) { ?>
									<div class="text-danger"><?php echo $error_seo_url; ?></div>
									<?php } ?>
								</div>
							</div>

							<!-- <div class="form-group required">
								<label class="col-sm-2 control-label" for="input-about_us"><?php echo $entry_about_us; ?></label>
								<div class="col-sm-10">
									<textarea name="about_us" rows="5" placeholder="<?php echo $entry_about_us; ?>" id="input-about_us" class="form-control"><?php echo $about_us; ?></textarea>
									<?php if ($error_about_us) { ?>
									<div class="text-danger"><?php echo $error_address; ?></div>
									<?php } ?>
								</div>
							</div> -->

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-pickup_notes"><?php echo $entry_pickup_notes; ?></label>
								<div class="col-sm-10">
									<textarea name="pickup_notes" rows="5" placeholder="<?php echo $entry_pickup_notes; ?>" id="input-pickup_notes" class="form-control"><?php echo $pickup_notes; ?></textarea>
								</div>
							</div>

							<?php if(!$this->user->isVendor()){ ?>
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-name"><?= $text_vendor ?></label>
								<div class="col-sm-10">
									<input type="text" name="vendor_name" value="<?php echo $vendor_name; ?>" placeholder="Vendor name" class="form-control" />
									<input type="hidden" name="vendor_id" value="<?= $vendor_id ?>" />
									<?php if ($error_vendor_id) { ?>
									<div class="text-danger"><?php echo $error_vendor_id; ?></div>
									<?php } ?>
								</div>
							</div>

							

							<?php if (!$this->user->isVendor()): ?>
						
							<!-- <div class="form-group required">
									<label class="col-sm-2 control-label" for="input-commision"><?php echo $entry_commision; ?> %</label>
									<div class="col-sm-2" style="padding-right: 0px;"> 
										<input type="number" step=".01" name="commision" value="<?php echo $commision; ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
										<?php if ($error_commision) { ?>
										<div class="text-danger"><?php echo $error_commision; ?></div>
										<?php } ?>
									</div>
									
									<center  class="col-sm-1" style="color: green;padding-right: 0px;padding-left: 0px;width: 28px!important;padding-top: 7px;">
										+ 
									</center>

									<label class="col-sm-1 control-label" for="input-commision">
										<?php echo $entry_fixed_commision; ?>
									</label>

									<div class="col-sm-2" style="padding-left: 0px;">
										<input type="number" step=".01" name="fixed_commision" value="<?php echo $fixed_commision; ?>" placeholder="<?php echo $entry_fixed_commision; ?>" id="input-commision" class="form-control" />
									</div>

									<p class="col-sm-1" for="input-commision" style="padding-left: 0px;padding-top: 7px;">
										<?php echo $this->currency->getSymbolLeft(); ?>
									</p>
									
							</div> -->

							<?php endif ?>

							<?php } ?>

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-geocode"><?= $entry_store_type ?></label>
								<div class="col-sm-10">
									<select id="selectStoreType" multiple=""  size="10" class="form-control" name="store_type_ids[]" >
									<?php foreach($categories as $category){ ?>
									<?php if(in_array($category['store_type_id'],$store_type_ids)) {  ?> 
									<option selected value="<?= $category['store_type_id'] ?>"><?= $category['name'] ?></option>
									<?php }else{ ?>
									<option value="<?= $category['store_type_id'] ?>"><?= $category['name'] ?></option>
									<?php } ?>
									<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-tax"><?php echo $entry_tax_number; ?></label>
								<div class="col-sm-10">
									<input type="text" name="tax" value="<?php echo $tax; ?>" placeholder="<?php echo $entry_tax_number; ?>" id="input-tax" class="form-control" />
									<?php if ($error_tax) { ?>
									<div class="text-danger"><?php echo $error_tax; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
								<div class="col-sm-10">
									<textarea name="address" rows="5" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control"><?php echo $address; ?></textarea>
									<?php if ($error_address) { ?>
									<div class="text-danger"><?php echo $error_address; ?></div>
									<?php } ?>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-geocode"><?= $entry_city ?></label>
								<div class="col-sm-4">
									<select id="selectCity" size="10" class="form-control" name="city_id" >
									<?php foreach($cities as $city){ ?>
									<?php if($city['city_id'] == $city_id){  ?>
									<option selected value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
									<?php }else{ ?>
									<option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
									<?php } ?>
									<?php } ?>
									</select>

									<!-- <a href="#" onclick="return export_city_zipcodes();"><?= $text_export_zipcode ?> </a> -->
								</div>
							</div>
							

							<?php if($this->config->get('config_store_location') == 'autosuggestion') { ?>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-currency"> Serviceable Radius </label>
									<div class="col-sm-10">
										<input type="number" step=".01" name="serviceable_radius" value="<?php echo $serviceable_radius; ?>" placeholder="Serviceable Radius" id="input-serviceable_radius" class="form-control" />
									</div>
								</div>

							<?php } else { ?>
								<div class="form-group">
					                <label class="col-sm-2 control-label" for="input-product">

					                <span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_cityzipcodes; ?></span>

					                

					                </label>
					                <div class="col-sm-10 apply-css">


					                  <input type="text" name="city_zipcodes" value="" placeholder="<?php echo $entry_cityzipcodes; ?>" id="input-product" class="form-control" />

					                  <input type="file" name="upload" id="upload" style="margin-top: 10px;margin-bottom: 10px" />

					                  <div id="city_zipcodes" class="well well-sm">
					                    <?php foreach ($city_zipcodes as $city_zipcodes) { ?>
					                    <div id="city_zipcodes<?php echo $city_zipcodes; ?>"><i class="fa fa-minus-circle"></i> <?php echo $city_zipcodes; ?>
					                      <input type="hidden" name="city_zipcodes[]" value="<?php echo $city_zipcodes; ?>" />
					                    </div>
					                    <?php } ?>
					                  </div>
					                </div>
					            </div>

							<?php } ?>
							

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-currency"><?= $entry_status ?></label>
								<div class="col-sm-10">
									<select name="status" class="form-control">
										<?php if ($status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div> 
						</div>

						<div class="tab-pane" id="tab-local">

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_delivery_by_store_owner ?></label>
								<div class="col-sm-10">
									<select name="delivery_by_owner" id="delivery_by_owner" class="form-control">
											<?php if ($delivery_by_owner) { ?>
											<option value="1" selected="selected"><?= $text_yes ?></option>
											<option value="0"><?= $text_no ?></option>
											<?php } else { ?>
											<option value="1"><?= $text_yes ?></option>
											<option value="0" selected="selected"><?= $text_no ?></option>
											<?php } ?>
									</select>
								</div>
							</div> 

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_pickup_delivery ?></label>
								<div class="col-sm-10">
									<select name="pickup_delivery" id="pickup_delivery" class="form-control">
											<?php if ($pickup_delivery) { ?>
											<option value="1" selected="selected"><?= $text_yes ?></option>
											<option value="0"><?= $text_no ?></option>
											<?php } else { ?>
											<option value="1"><?= $text_yes ?></option>
											<option value="0" selected="selected"><?= $text_no ?></option>
											<?php } ?>
									</select>
								</div>
							</div> 


							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-currency"><?= $entry_min_order_amount ?></label>
								<div class="col-sm-10">
									<input type="number" step=".01" name="min_order_amount" value="<?php echo $min_order_amount; ?>" placeholder="<?php echo $entry_min_order_amount; ?>" id="input-min_order_amount" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-currency"><?= $entry_free_delivery_amount ?></label>
								<div class="col-sm-10">
									<input type="number" step=".01" name="min_order_cod" value="<?php echo $min_order_cod; ?>" placeholder="<?php echo $entry_min_order_cod; ?>" id="input-min_order_cod" class="form-control" />
								</div>
							</div>	

							<div id="showifstoreowner" class="<?php echo $delivery_by_owner ? '' : 'hide' ?>">
							
								

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-currency"><?= $entry_cost_of_delivery ?></label>
									<div class="col-sm-10">
										<input type="number" step=".01" name="cost_of_delivery" value="<?php echo $cost_of_delivery; ?>" placeholder="<?php echo 'Cost of Delivery Flat Rate'; ?>" id="input-cost_of_delivery" class="form-control" />
									</div>
								</div>

								<div class="form-group required" class="<?php echo $delivery_by_owner ? '' : 'hide' ?>">
									<label class="col-sm-2 control-label" for="input-name"><?= $entry_home_delivery_time_difference ?></label>
									<div class="col-sm-10">
										<input data-date-format="HH:mm" class="form-control time_diff" value="<?php echo $delivery_time_diff; ?>" placeholder="Time Difference for home delivery" type="text" name="delivery_time_diff" />  
										<?php if ($error_delivery_time_diff) { ?>
										<div class="text-danger"><?php echo $error_delivery_time_diff; ?></div>
										<?php } ?>
									</div>
								</div><!-- END .form-group -->

							</div>

							<!-- <div class="form-group">
								<label class="col-sm-2 control-label" for="input-currency"><?= $entry_delivery_time_picker_status ?></label>
								<div class="col-sm-10">
									<select name="delivery_date_time_status" class="form-control">
										<?php if ($delivery_date_time_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div> -->
							

						</div>
						<div class="tab-pane" id="tab-design">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?><div class="help-block"> (Dimensions 256px x 256px) </div></label>
								<div class="col-sm-10">
									<a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail">
										<img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $thumb; ?>" />
										
									</a>
									

									<input type="hidden" name="logo" value="<?php echo $logo; ?>" id="input-logo" />
								</div>
								<label class="col-sm-2 control-label" for="input-big-logo"><?php echo $entry_big_logo; ?><div class="help-block"> (Dimensions 320px x 103px) </div></label>
								<div class="col-sm-10">
									<a href="" id="thumb-big-logo" data-toggle="image" class="img-thumbnail">
										<img src="<?php echo $big_thumb; ?>" alt="" title="" data-placeholder="<?php echo $big_thumb; ?>" />
										
									</a>
									<input type="hidden" name="big_logo" value="<?php echo $big_logo; ?>" id="input-big-logo" />
								</div>

								<label class="col-sm-2 control-label" for="input-banner-logo"><?php echo $entry_banner_logo; ?><div class="help-block"> (Dimensions 800px x 450px) </div></label>
								<div class="col-sm-10">
									<a href="" id="thumb-banner-logo" data-toggle="image" class="img-thumbnail">
										<img src="<?php echo $banner_thumb; ?>" alt="" title="" data-placeholder="<?php echo $banner_thumb; ?>" />
										
									</a>
									<input type="hidden" name="banner_logo" value="<?php echo $banner_logo; ?>" id="input-banner-logo" />
								</div>

								<label class="col-sm-2 control-label" for="input-banner-logo"><?php echo $entry_banner_logo_status; ?></label>
								<div class="col-sm-10" style="    margin-top: 10px;">
									<label class="radio-inlines">
                                        <?php if ($banner_logo_status) { ?>
                                        <input type="radio" name="banner_logo_status" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="banner_logo_status" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inlines">
                                        <?php if (!$banner_logo_status) { ?>
                                        <input type="radio" name="banner_logo_status" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="banner_logo_status" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
								</div>

							</div>
						</div>

						<?php if (!$this->user->isVendor()) { ?>
						
							

							<div class="tab-pane" id="tab-commission">
								
								<div class="row">
									<div class="col-md-12">

										<select class="form-controls" name="commission_type" id="commission_type">                        
											<?php if($commission_type == 'category'){ ?>
											<option value="category" selected="">Category</option>
											<option value="store">Store</option>
											<?php }else{ ?>
											<option value="category" >Category</option>
											<option value="store" selected="">Store</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="row" style="margin-top: 20px">
									<div class="col-md-12">
										<div class="form-group required">
												<label class="col-sm-2" for="input-commision">Store <?php echo $entry_commision; ?> %</label>
												<div class="col-sm-2" style="padding-right: 0px;"> 
													<input type="number" step=".01" name="commision" value="<?php echo $commision; ?>" placeholder="<?php echo $entry_commision; ?>" <?php echo ($commission_type == 'store') ? '' : 'readonly="readonly"' ?>  id="input-commision" class="form-control" />
													<?php if ($error_commision) { ?>
													<div class="text-danger"><?php echo $error_commision; ?></div>
													<?php } ?>
												</div>
												
												<center  class="col-sm-1" style="color: green;padding-right: 0px;padding-left: 0px;width: 28px!important;padding-top: 7px;">
													+ 
												</center>

												<label class="col-sm-1 control-label" for="input-commision">
													<?php echo $entry_fixed_commision; ?>
												</label>

												<div class="col-sm-2" style="padding-left: 0px;">
													<input type="number" step=".01" name="fixed_commision" value="<?php echo $fixed_commision; ?>" placeholder="<?php echo $entry_fixed_commision; ?>" <?php echo ($commission_type == 'store') ? '' : 'readonly="readonly"' ?>  id="input-commision" class="form-control" />
												</div>

												<p class="col-sm-1" for="input-commision" style="padding-left: 0px;padding-top: 7px;">
													<?php echo $this->currency->getSymbolLeft(); ?>
												</p>
										</div>
									</div>
								</div>


								<div class="row">
									<div class="col-md-12">
										<table class="table table-bordered col-md-12" id="ssdelivery-timeslots">
											<thead>
												<tr>
													<td >Category</td>
													<td >Commission</td>
												</tr>  
											</thead>
											<tbody>
												<?php $i=0; ?>
												<?php foreach($store_top_categories as $category){ ?>
												<tr>
													<td>
														<?= $category['name'] ?>
													</td>
													
													<td>

														<?php	if(array_key_exists($category['category_id'],$store_categories_commission)) {?>

															<div class="col-sm-2" style="padding-right: 0px;"> 
																<input type="number" step=".01" name="category_commission[<?php echo $category['category_id']; ?>][commission]" value="<?= $store_categories_commission[$category['category_id']]['commission'] ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
																<?php if ($error_category_commission) { ?>
																	<div class="text-danger"><?php echo $error_category_commission; ?></div>
																<?php } ?>

															</div>
															
															<center  class="col-sm-1" style="color: green;padding-right: 0px;padding-left: 0px;width: 28px!important;padding-top: 7px;">
																+ 
															</center>

															<label class="col-sm-1 control-label" for="input-commision">
																<?php echo $entry_fixed_commision; ?>
															</label>

															<div class="col-sm-2" style="padding-left: 0px;">
																<input type="number" step=".01" name="category_commission[<?php echo $category['category_id']; ?>][fixed_commission]" value="<?= $store_categories_commission[$category['category_id']]['fixed_commission'] ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
																<?php if ($error_category_commission) { ?>
																	<div class="text-danger"><?php echo $error_category_commission; ?></div>
																<?php } ?>
															</div>

															<p class="col-sm-1" for="input-commision" style="padding-left: 0px;padding-top: 7px;">
																<?php echo $this->currency->getSymbolLeft(); ?>
															</p>


														<?php	} else { ?>
															<!-- <input type="number" step=".01" name="category_id_<?php echo $category['category_id']; ?>" value="" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" /> -->

															<div class="col-sm-2" style="padding-right: 0px;"> 
																<input type="number" step=".01" name="category_commission[<?php echo $category['category_id']; ?>][commission]" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
																<?php if ($error_category_commission) { ?>
																	<div class="text-danger"><?php echo $error_category_commission; ?></div>
																<?php } ?>
															</div>
															
															<center  class="col-sm-1" style="color: green;padding-right: 0px;padding-left: 0px;width: 28px!important;padding-top: 7px;">
																+ 
															</center>

															<label class="col-sm-1 control-label" for="input-commision">
																<?php echo $entry_fixed_commision; ?>
															</label>

															<div class="col-sm-2" style="padding-left: 0px;">
																<input type="number" step=".01" name="category_commission[<?php echo $category['category_id']; ?>][fixed_commission]" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
																<?php if ($error_category_commission) { ?>
																	<div class="text-danger"><?php echo $error_category_commission; ?></div>
																<?php } ?>
															</div>

															<p class="col-sm-1" for="input-commision" style="padding-left: 0px;padding-top: 7px;">
																<?php echo $this->currency->getSymbolLeft(); ?>
															</p>

														<?php	} ?>
															
														<input type="hidden" name="category_commission[<?php echo $category['category_id']; ?>][category_id]" value="<?= $category['category_id'] ?>" />

														
													</td>    
												</tr>    
												<?php $i++; } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						<?php  } ?>

						

						<div class="tab-pane " id="tab-delivery-timeslot">
							<div class="row">
								<div class="col-md-12">
									<table class="table table-bordered col-md-12" id="delivery-timeslots">
										<thead>
											<tr>
												<td ><?= $column_timeslot ?></td>
												<td ><?= $column_sunday ?></td>
												<td ><?= $column_monday ?></td>
												<td ><?= $column_tuesday ?></td>
												<td ><?= $column_wesnesday ?></td>
												<td ><?= $column_thirsday ?></td>
												<td ><?= $column_friday ?></td>
												<td ><?= $column_saturday ?></td>
												<td></td>
											</tr>  
										</thead>
										<tbody>
											<?php $i=0; ?>
											<?php foreach($delivery_timeslots as $timeslot){ ?>   
											<tr>
												<td>
													<?= $timeslot['timeslot'] ?>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[0][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[0]){ ?>
														<option value="1" selected="" class="green-text"><?php echo $text_enabled; ?></option>
														<option value="0" class="red-text" ><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1" class="green-text"><?php echo $text_enabled; ?></option>
														<option value="0" selected="" class="red-text"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[1][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[1]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[2][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[2]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[3][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[3]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[4][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[4]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[5][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[5]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="delivery_timeslots[6][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[6]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>    
												<td>
													<a class="remove btn btn-danger">
														<i class="fa fa-trash"></i>
													</a>
												</td>    
											</tr>    
											<?php $i++; } ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="timeslot_form" style="padding-left: 100px; position: relative;">
									<div class="form-group">
										<div class="col-lg-2 text-right">
											 <label style="line-height: 30px;"><?= $entry_add_timeslot ?></label>
										</div>
										<div class="col-lg-10 time_slot">
											<input style="float:left; width: 100px;margin-right: 5px;" class="form-control time" placeholder="From" type="text" name="from" />              
											<input style="float:left; width: 100px;" class="form-control time" placeholder="To" type="text" name="to" />                                            
										</div>
									</div>
									<div class="form-group">
										<div class="col-lg-2">
											<label>&nbsp;</label>
										</div>
										<div class="col-lg-10">
											<button type="button" class="btn btn-primary" onclick="add('delivery');">
												<i class="fa fa-plus"></i><?= $button_add_timeslot ?>
											</button>
										</div>
									</div>
								</div> 
							</div>
						</div>

						<div class="tab-pane " id="tab-pickup-timeslot">
							<div class="row" style="">
								<table class="table table-bordered col-md-12" id="pickup-timeslots">
									<thead>
										<tr>
											<td ><?= $column_timeslot ?></td>
												<td ><?= $column_sunday ?></td>
												<td ><?= $column_monday ?></td>
												<td ><?= $column_tuesday ?></td>
												<td ><?= $column_wesnesday ?></td>
												<td ><?= $column_thirsday ?></td>
												<td ><?= $column_friday ?></td>
												<td ><?= $column_saturday ?></td>
											<td></td>
										</tr>  
									</thead>
									<tbody>
										<?php $i=0; ?>
										<?php foreach($pickup_timeslots as $timeslot){ ?>   
										<tr>
											<td>
												<?= $timeslot['timeslot'] ?>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[0][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[0]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[1][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[1]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[2][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[2]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[3][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[3]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[4][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[4]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[5][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[5]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="form-controls" name="pickup_timeslots[6][<?= $timeslot['timeslot'] ?>]">                        
													<?php if($timeslot[6]){ ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php }else{ ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</td>    
											<td>
												<a class="remove btn btn-danger">
													<i class="fa fa-trash"></i>
												</a>
											</td>    
										</tr>    
										<?php $i++; } ?>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="timeslot_form" style="padding-left: 100px; position: relative;">
									<div class="form-group">
										<div class="col-lg-2 text-right">
											 <label style="line-height: 30px;"><?= $entry_add_timeslot ?></label>
										</div>
										<div class="col-lg-10 time_slot">
											<input style="float:left; width: 100px;margin-right: 5px;" class="form-control time" placeholder="From" id="pickup-from" type="text" name="from" />              
											<input style="float:left; width: 100px;" class="form-control time" id="pickup-to" placeholder="To" type="text" name="to" />                                            
										</div>
									</div>
									<div class="form-group">
										<div class="col-lg-2">
											<label>&nbsp;</label>
										</div>
										<div class="col-lg-10">
											<button type="button" class="btn btn-primary" onclick="addpic('pickup');">
												<i class="fa fa-plus"></i><?= $button_add_timeslot ?>
											</button>
										</div>
									</div>
								</div>   
							</div>
						</div>

						<div class="tab-pane " id="tab-open-hours">
							<div class="row">
								<div class="col-md-12">
									<table class="table table-bordered col-md-12" id="openhours-timeslots">
										<thead>
											<tr>
												<td ><?= $column_timeslot ?></td>
												<td ><?= $column_sunday ?></td>
												<td ><?= $column_monday ?></td>
												<td ><?= $column_tuesday ?></td>
												<td ><?= $column_wesnesday ?></td>
												<td ><?= $column_thirsday ?></td>
												<td ><?= $column_friday ?></td>
												<td ><?= $column_saturday ?></td>
												<td></td>
											</tr>  
										</thead>
										<tbody>
											<?php $i=0; ?>
											<?php foreach($open_hours as $timeslot){ ?>   
											<tr>
												<td>
													<?= $timeslot['timeslot'] ?>
												</td>
												<td>
													<select class="form-controls" name="open_hours[0][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[0]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="open_hours[1][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[1]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="open_hours[2][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[2]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="open_hours[3][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[3]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="open_hours[4][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[4]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="open_hours[5][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[5]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-controls" name="open_hours[6][<?= $timeslot['timeslot'] ?>]">                        
														<?php if($timeslot[6]){ ?>
														<option value="1" selected=""><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php }else{ ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected=""><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>    
												<td>
													<a class="remove btn btn-danger">
														<i class="fa fa-trash"></i>
													</a>
												</td>    
											</tr>    
											<?php $i++; } ?>
										</tbody>
									</table>
								</div>
							</div>

							<div class="row">
								<div class="timeslot_form" style="padding-left: 100px; position: relative;">
									<div class="form-group">
										<div class="col-lg-2 text-right">
											 <label style="line-height: 30px;">Add open hours</label>
										</div>
										<div class="col-lg-10 time_slot">
											<input style="float:left; width: 100px;margin-right: 5px;" class="form-control time" placeholder="From" type="text" name="openhour-from" />              
											<input style="float:left; width: 100px;" class="form-control time" placeholder="To" type="text" name="openhour-to" />                                            
										</div>
									</div>
									<div class="form-group">
										<div class="col-lg-2">
											<label>&nbsp;</label>
										</div>
										<div class="col-lg-10">
											<button type="button" class="btn btn-primary" onclick="addOpenHours('openhours');">
												<i class="fa fa-plus"></i>Add open hour
											</button>
										</div>
									</div>
								</div> 
							</div>
							
							
						</div>


						<!-- open hours end -->

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
	function save(type) {
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'button';
			input.value = type;
			form = $("form[id^='form-']").append(input);
			form.submit();
		}
//--></script>

<script>

$(document).ready(function(){

	//alert("rtg");
	<?php if(isset($category_for_store)){ ?>

		$('.storeCategories').selectpicker('val',<?php echo $category_for_store; ?>);
	<?php } else { ?>
		//alert(<?php echo $category_for_store; ?>);
		$('.storeCategories').selectpicker('val',['']);
	<?php } ?>
});

	$(function(){
		$('input[name=\'vendor_name\']').autocomplete({
			'source': function(request, response) {                
					$.ajax({
							url: 'index.php?path=setting/store/vendor_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
							dataType: 'json',			
							success: function(json) {
									response($.map(json, function(item) {
											return {
													label: item['name'],
													value: item['user_id']
											}
									}));
							}
					});
			},
			'select': function(item) {
					$('input[name=\'vendor_name\']').val(item['label']);
					$('input[name=\'vendor_id\']').val(item['value']);
			}	
		});

		$('.time').datetimepicker({
			pickDate: false,
			format: 'hh:mma'
		});

		$('.time_diff').datetimepicker({
			pickDate: false,
			format: 'HH:mm',
		});


	});
	
	
	window.onload = function(){
		$('a[href="#tab-general"]').trigger('click');
	}


$(document).delegate('.remove','click', function(){
	$(this).parent().parent().remove();
});


$('#commission_type').change(function() {
	
	if($(this).val()=='category') {
		
		$("input[name=\'commision\']").css('background-color' , '#DEDEDE');
        $("input[name=\'fixed_commision\']").css('background-color' , '#DEDEDE');
        $("input[name=\'commision\']").attr('readonly', true);
        $("input[name=\'fixed_commision\']").attr('readonly', true);

	} else{

		$("input[name=\'commision\']").css('background-color' , '');
        $("input[name=\'fixed_commision\']").css('background-color' , '');
        $("input[name=\'commision\']").attr('readonly', false);
        $("input[name=\'fixed_commision\']").attr('readonly', false);
	}
});

$('#delivery_by_owner').change(function() {
	if ($(this).val()==1 && '<?php echo $storeDeliveryExtensionEnabled; ?>') {
		$('#showifstoreowner').removeClass('hide');
		$('#delivery_time').removeClass('hide');
	}else{
		$('#delivery_time').addClass('hide');
		$('#showifstoreowner').addClass('hide');
	}
});

$('#pickup_delivery').change(function() {
	if ($(this).val()==1 && '<?php echo $storePickupExtensionEnabled; ?>') {
		$('#pickup_delivery_time').removeClass('hide');
	}else{
		$('#pickup_delivery_time').addClass('hide');
	}
});

var i = <?= $i ?>;

function add(tblname){

	//alert('e');
	$from = $('input[name="from"]').val();
	$to   = $('input[name="to"]').val();

	if(!$from.length > 0){
		$('input[name="from"]').css('border','1px solid red');  
		return;
	}else{
		$('input[name="from"]').val('');  
		$('input[name="from"]').css('border','1px solid #ccc');  
	}

	if(!$to.length > 0){
		$('input[name="to"]').css('border','1px solid red'); 
		return;
	}else{
		$('input[name="to"]').css('border','1px solid #ccc');  
		$('input[name="to"]').val('');  
	}

	$timeslot = $from+' - '+$to;
		
	$html  = '<tr>';        
	$html += '<td>';
	$html += $timeslot;
	$html += '</td>';

	for($j=0; $j<7; $j++){        
		$html += '<td>';
		$html += '<select class="form-controls" name="delivery_timeslots['+$j+']['+$timeslot+']">';                        
		$html += '<option value="1" selected="">Enable</option>';
		$html += '<option value="0" >Disable</option>';
		$html += '</select>';
		$html += '</td>';        
	}
	
	$html += '<td>';
	$html += '<a class="remove btn btn-danger">';
	$html += '<i class="fa fa-trash"></i>';
	$html += '</a>';
	$html += '</td>';
	$html += '</tr>';
	
	//console.log('#'+tblname+'-timeslots');
	$('#'+tblname+'-timeslots tbody').append($html);
		
}

function addOpenHours(tblname){

	//alert('e');
	$from = $('input[name="openhour-from"]').val();
	$to   = $('input[name="openhour-to"]').val();

	if(!$from.length > 0){
		$('input[name="openhour-from"]').css('border','1px solid red');  
		return;
	}else{
		$('input[name="openhour-from"]').val('');  
		$('input[name="openhour-from"]').css('border','1px solid #ccc');  
	}

	if(!$to.length > 0){
		$('input[name="openhour-to"]').css('border','1px solid red'); 
		return;
	}else{
		$('input[name="openhour-to"]').css('border','1px solid #ccc');  
		$('input[name="openhour-to"]').val('');  
	}

	$timeslot = $from+' - '+$to;
		
	$html  = '<tr>';        
	$html += '<td>';
	$html += $timeslot;
	$html += '</td>';

	for($j=0; $j<7; $j++){        
		$html += '<td>';
		$html += '<select class="form-controls" name="open_hours['+$j+']['+$timeslot+']">';                        
		$html += '<option value="1" selected="">Enable</option>';
		$html += '<option value="0" >Disable</option>';
		$html += '</select>';
		$html += '</td>';        
	}
	
	$html += '<td>';
	$html += '<a class="remove btn btn-danger">';
	$html += '<i class="fa fa-trash"></i>';
	$html += '</a>';
	$html += '</td>';
	$html += '</tr>';
	
	//console.log('#'+tblname+'-timeslots');
	$('#'+tblname+'-timeslots tbody').append($html);
		
}


function addpic(tblname) {

	$from = $('#pickup-from').val();
	$to   = $('#pickup-to').val();

	if(!$from.length > 0){
		$('#pickup-from').css('border','1px solid red');  
		return;
	}else{
		$('#pickup-from').val('');  
		$('#pickup-from').css('border','1px solid #ccc');  
	}

	if(!$to.length > 0){
		$('#pickup-to').css('border','1px solid red'); 
		return;
	}else{
		$('#pickup-to').css('border','1px solid #ccc');  
		$('#pickup-to').val('');  
	}

	$timeslot = $from+' - '+$to;
		
	$html  = '<tr>';        
	$html += '<td>';
	$html += $timeslot;
	$html += '</td>';

	for($j=0; $j<7; $j++){        
		$html += '<td>';
		$html += '<select class="form-controls" name="pickup_timeslots['+$j+']['+$timeslot+']">';                        
		$html += '<option value="1" selected="">Enable</option>';
		$html += '<option value="0" >Disable</option>';
		$html += '</select>';
		$html += '</td>';        
	}
	
	$html += '<td>';
	$html += '<a class="remove btn btn-danger">';
	$html += '<i class="fa fa-trash"></i>';
	$html += '</a>';
	$html += '</td>';
	$html += '</tr>';
	
	$('#'+tblname+'-timeslots tbody').append($html);
		
}
	
</script>

<script type="text/javascript"><!--

function getZipcodes() {
	var city_id = $('#selectCity').find(":selected").val();
	console.log("ss"+$('#selectCity').find(":selected").val());

	$.ajax({
        url : 'index.php?path=setting/store/getZipcodes&token=<?php echo $token; ?>&city_id='+  encodeURIComponent(city_id),
        method: 'get',
        data: {city_id : city_id},
        success:function(data){
            console.log("data"+data);
            if(data){
               
            }else{
                console.log("ss");
            }
        }
    });
}

$('input[name=\'city_zipcodes\']').autocomplete({

	'source': function(request, response) {

		var city_id = $('#selectCity').find(":selected").val()

		$.ajax({
			url: 'index.php?path=setting/store/getZipcodesAutocomplete&city_id='+encodeURIComponent(city_id)+'&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['zipcode'],
						value: item['zipcode']
					}
				}));

				$('.apply-css ul').css({'width': '300px', 'height': '200px', 'overflow': 'auto'});
			}
		});
	},
	'select': function(item) {
		$('input[name=\'city_zipcodes\']').val('');
		
		$('#city_zipcodes' + item['value']).remove();
		
		$('#city_zipcodes').append('<div id="city_zipcodes' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="city_zipcodes[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#city_zipcodes').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

function export_city_zipcodes() {
    
    var city_id = $('#selectCity').find(":selected").val();

    url = 'index.php?path=setting/store/export_city_zipcodes&token=<?php echo $token; ?>&city_id='+city_id;
    
    location = url;

    return false;
}

function locationpickerLoad() {
    
    
	$('#us1').locationpicker({
		location: {
			latitude: <?= $latitude?$latitude:0 ?>,
			longitude: <?= $longitude?$longitude:0 ?>
		},	
		radius: <?= $serviceable_radius; ?> * 1000,    // 10 miles in metres,
		inputBinding: {
			latitudeInput: $('input[name="latitude"]'),
			longitudeInput: $('input[name="longitude"]'),
			locationNameInput: $('#us2-address')
		},
		enableAutocomplete: true
	});

	return false;
}




</script>

<style>
	.time_slot_wrapper .row, .ptime_slot_wrapper .row{
		width: 222px !important;
	}
	.time_slot {
		float: left;
		font-size: 16px;
		line-height: 30px;
		text-indent: 18px;
		width: 170px;
	}
	.time_slot_wrapper .remove, .ptime_slot_wrapper .remove {
		float: right !important;
	}
	.time_slot_wrapper, .ptime_slot_wrapper {
		width: 220px;
	}
	.time_slot > input {
		display: inline-block;
		float: left;
		margin-right: 5px;
		width: 80px;
	}
	.row {
		padding: 5px 0;
	}

</style>