<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" onclick="save('save')" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
				<button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
				<!-- <button type="submit" onclick="save('new')" form="form-product" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button> -->
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
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
		<?php if ($error_warning) { ?>

		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> 
					<?php echo $text_form; ?>
				</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<!-- <li class=""><a href="#tab-variation" data-toggle="tab"><?= $tab_variations ?></a></li> -->
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<div class="form-group required">
								<label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
								<div class="col-sm-10">
								     
								     <?php if($is_vendor) { ?>

								     	<input type="hidden" name="product_store" value="<?= $product_store ?>"/>

								     <?php } ?>
									<select class="form-control" name="product_store" <?php echo $is_vendor ? 'disabled' : '' ?>>
										<option value=""><?= $text_none ?></option>

										<?php foreach ($stores as $f): ?>
											<option value="<?php echo $f['store_id']; ?>" <?php echo $product_store == $f['store_id'] ? 'selected' : '' ?>><?php echo $f['name'] ?></option>
										<?php endforeach ?>
									</select>
									
								  <?php if ($error_product_store) { ?>
									<div class="text-danger">
										<?php echo $error_product_store; ?>
									</div>
									<?php } ?>

								</div>
							</div>
                                                        <div class="form-group required">
								<label class="col-sm-2 control-label">Vendor</label>
								<div class="col-sm-10">
									<select class="form-control" name="merchant_id">
										<option value=""><?= $text_none ?></option>

										<?php foreach ($vendors as $f): ?>
											<option value="<?php echo $f['user_id']; ?>" <?php echo $merchant_id == $f['user_id'] ? 'selected' : '' ?>><?php echo $f['username'] ?></option>
										<?php endforeach ?>
									</select>
									
								  <?php if ($error_merchant) { ?>
									<div class="text-danger">
										<?php echo $error_merchant; ?>
									</div>
									<?php } ?>

								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
								<div class="col-sm-10">

									<?php if ($edit_mode) { ?>
										<input type="text" name="product" value="<?php echo $product ?>" id="product" class="form-control input-full-width" disabled/>
									<?php } else { ?>
										<input type="text" name="product" value="<?php echo $product ?>" id="product" class="form-control input-full-width"/>
									<?php } ?>
									

								 <?php if ($error_product_id) { ?>
								<div class="text-danger">
									<?php echo $error_product_id; ?>
								</div>
								<?php } ?>
								</div>
								<input type="hidden" name="product_id" value="<?php echo $product_id ?>" id="product_id" class="form-control input-full-width" />
								
							</div>

							<div class="form-group required">
								<label class="col-sm-2 control-label"><?php echo $entry_unit; ?></label>
								<div class="col-sm-10">
									<input type="text" name="unit" value="<?php echo $unit ?>" id="unit" class="form-control input-full-width" disabled/>
								 <?php if ($error_unit) { ?>
								<div class="text-danger">
									<?php echo $error_unit; ?>
								</div>
								<?php } ?>
								</div>
								<input type="hidden" name="product_id" value="<?php echo $product_id ?>" id="product_id" class="form-control input-full-width" />
								
							</div>

							<!-- <div class="form-group required">
								<label class="col-sm-2 control-label"><?php echo $entry_weight; ?></label>
								<div class="col-sm-10">
									<input type="text" name="weight" value="<?php echo $weight ?>" id="weight" class="form-control input-full-width" disabled/>
								 <?php if ($error_weight) { ?>
								<div class="text-danger">
									<?php echo $error_weight; ?>
								</div>
								<?php } ?>
								</div>
								<input type="hidden" name="product_id" value="<?php echo $product_id ?>" id="product_id" class="form-control input-full-width" />
								
							</div> -->

							<?php if ($is_vendor): ?>
                            
                        		<input type="hidden" name="price" value="<?php echo $price; ?>"/>
                        		<input type="hidden" name="special_price" value="<?php echo $special_price; ?>"/>
                        		<input type="hidden" name="quantity" value="<?php echo $quantity; ?>"/>
	                       
	                        <?php endif ?>


							<div class="form-group required">
								<label class="col-sm-2 control-label"><?php echo $entry_price; ?></label>
								<div class="col-sm-10">

									<input type="text" name="price" onkeypress="return validateFloatKeyPress(this, event);"  value="<?php echo $price; ?>" id="price" class="form-control input-full-width" />


									<?php if ($error_price) { ?>
									<div class="text-danger">
										<?php echo $error_price; ?>
									</div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_special_price; ?></label>
								<div class="col-sm-10">
									<input type="text" name="special_price" onkeypress="return validateFloatKeyPress(this, event);"  value="<?php echo $special_price; ?>" id="special_price" class="form-control input-full-width" />
								</div>
							</div>

							<!-- <div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_tax_percentage; ?></label>
								<div class="col-sm-10">
									<input type="number" name="tax_percentage" value="<?php echo $tax_percentage; ?>" id="tax_percentage" class="form-control input-full-width" />
								</div>
							</div> -->

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_quantity; ?></label>
								<div class="col-sm-10">
									<input <?php if (!$is_vendor): ?> readonly <?php endif ?> type="number" name="quantity" value="<?php echo $quantity ?>" id="quantity" class="form-control input-full-width" />
								</div>
							</div>

							<div class="form-group " style="display:none;">
								<label class="col-sm-2 control-label"><?php echo $entry_minimum; ?></label>
								<div class="col-sm-10">
									<input type="number" name="min_quantity" value="<?php echo $min_quantity; ?>" id="min_quantity" class="form-control input-full-width" />
								</div>
							</div>

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_subtract; ?></label>
								<div class="col-sm-10">
									<select class="form-control" name="subtract_quantity" >
										<?php if ($subtract==1): ?>
										<option value="0"><?= $text_no ?></option>
										<option value="1" selected><?= $text_yes ?></option>
										<?php else: ?>	
										<option value="0" selected><?= $text_no ?></option>
										<option value="1" ><?= $text_yes ?></option>
										<?php endif ?>
									</select>
								</div>
							</div>
							<div class="form-group ">
								<label class="col-sm-2 control-label"><?= $entry_tax_class ?></label>
								<div class="col-sm-10">
									<select name="tax_class_id" id="input-tax-class" class="form-control" >
				                    <option value="0"><?php echo $text_none; ?></option>
				                    <?php foreach ($tax_classes as $tax_class) { ?>
				                    <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
				                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
				                    <?php } else { ?>
				                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
				                    <?php } ?>
				                    <?php } ?>
				                  </select>
								</div>
							</div>
							
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status">
									<?php echo $entry_status; ?>
								</label>
								<div class="col-sm-10">
									<select name="status" id="input-status" class="form-control" >
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
                                                        
                <div class="form-group">
                       <label class="col-sm-2 control-label">Delivery Days</label> 
                       <div class="col-sm-10">
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="monday" <?php if(isset($monday) && $monday == 1) { ?> checked="checked" <?php } ?> >Monday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="tuesday" <?php if(isset($tuesday) && $tuesday == 1) { ?> checked="checked" <?php } ?> >Tuesday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="wednesday" <?php if(isset($wednesday) && $wednesday == 1) { ?> checked="checked" <?php } ?> >Wednesday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="thursday" <?php if(isset($thursday) && $thursday == 1) { ?> checked="checked" <?php } ?> >Thursday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="friday" <?php if(isset($friday) && $friday == 1) { ?> checked="checked" <?php } ?> >Friday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="saturday" <?php if(isset($saturday) && $saturday == 1) { ?> checked="checked" <?php } ?> >Saturday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="product_delivery[]" value="sunday" <?php if(isset($sunday) && $sunday == 1) { ?> checked="checked" <?php } ?> >Sunday</label>
                           </div>
                       </div>
                </div>
							
						</div>
						<!-- <div class="tab-pane" id="tab-variation">
							<div id="variation">

								<?php if ($product_variations){ ?>
									<table id="variations" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											
											<td class="">
												<?= $entry_name ?>
											</td>
											<td class="">
												<?= $entry_unit ?>
											</td>
											<td>
												<?= $entry_price ?>
											</td>
											<td>
												<?= $entry_special_price ?>
											</td>
											<td>
												<?= $column_action ?>
											</td>
										</tr>
									</thead>
									<tbody>
										
											<?php $variation_row=0; ?>
								            <?php foreach ($product_variations as $product_variation) { ?>
								            <tr id="variation-row<?php echo $variation_row; ?>">
								            	<input type="hidden" name="variation_id[]" value="<?php echo $product_variation['id'] ?>">
								               
								                <td >
								                    <?php echo $product_variation['name']; ?>
								                </td>
								                <td >
								                    <?php echo $product_variation['unit']; ?>
								                </td>
								                <td >
								               	 	<?php echo $product_variation['price']; ?>
								                </td>
								                <td class="text-left">
								                	<?php echo $product_variation['special_price']; ?>
								               </td>
								               <td >
								               	 	<a href="<?php echo $product_variation['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
								                </td>

								            </tr>
								            <?php $variation_row++; ?>
								            <?php } ?>

									</tbody>
								</table>
								<?php } else { ?>
									<h1> <?= $no_variation ?></h1>

								<?php } ?>


							</div>
						</div> -->

					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
						
<?php echo $footer; ?> 

<script type="text/javascript">
	// Manufacturer
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=catalog/vendor_product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					product_id: 0,
					name: '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id'],
						unit: item['unit']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);
		$('input[name=\'unit\']').val(item['unit']);
		
		var data = {
			product_id:item['value'],
			token:'<?php echo $token; ?>'
		};
		$.ajax({
			url:'index.php?path=catalog/vendor_product/getVariation',
			data:data,
			success:function(data){
				$('#variation').html(data);
			}
		});
	}	
});


 function validateFloatKeyPress(el, evt) {

      // $optionvalue=$('.product-variation option:selected').text().trim();
       //alert($optionvalue);
       //if($optionvalue=="Per Kg")
       //{
            var charCode = (evt.which) ? evt.which : event.keyCode;
            var number = el.value.split('.');
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            //just one dot
            if(number.length>1 && charCode == 46){
                return false;
            }
            //get the carat position
            var caratPos = getSelectionStart(el);
            var dotPos = el.value.indexOf(".");
            if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
                return false;
            }
            return true;
        //}

       //else{
      //var charCode = (evt.which) ? evt.which : event.keyCode;
       // if (charCode > 31 &&
       //   (charCode < 48 || charCode > 57))
       //   return false;
         // else
      
      //return true;
      // }
}

</script>