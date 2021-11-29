<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" onclick="save('save')" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
				<button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
				<button type="submit" onclick="save('new')" form="form-product" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li>
					<a href="<?php echo $breadcrumb['href']; ?>">
						<?php echo $breadcrumb['text']; ?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>

		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
			<?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab-general" data-toggle="tab">
								<?php echo $tab_general; ?>
							</a>
						</li>
						<li>
							<a href="#tab-data" data-toggle="tab">
								<?php echo $tab_data; ?>
							</a>
						</li>
						<li>
							<a href="#tab-seo" data-toggle="tab">
								<?php echo $tab_seo; ?>
							</a>
						</li>
						<li>
							<a href="#tab-links" data-toggle="tab">
								<?php echo $tab_links; ?>
							</a>
						</li>
						<li><a href="#tab-variation" data-toggle="tab">
								<?= $tab_variations ?></a></li>
								<li><a href="#tab-product-type" data-toggle="tab">
								<?= $tab_product_types ?></a></li>
						<li><a href="#tab-images" data-toggle="tab">
								<?= $tab_images ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<ul class="nav nav-tabs" id="language">
								<?php foreach ($languages as $language) { ?>
								<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
									<div class="form-group required">
										<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
										<div class="col-sm-10">
											<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
											<?php if (isset($error_name[$language['language_id']])) { ?>
											<div class="text-danger">
												<?php echo $error_name[$language['language_id']]; ?>
											</div>
											<?php } ?>
										</div>
									</div>
									<!-- <div class="form-group required">
										<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $product_unit; ?></label>
										<div class="col-sm-10">
											<input type="text" name="product_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['unit'] : ''; ?>" placeholder="<?php echo $product_unit; ?>" id="input-unit<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
											<?php if ($error_unit) { ?>
											<div class="text-danger">
												<?php echo $error_unit; ?>
											</div>
											<?php } ?>
										</div>
									</div> -->

									

									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
										<div class="col-sm-10">
											<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
										<div class="col-sm-10">
											<input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-data">
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-model">
									<?php echo $entry_model; ?></label>
								<div class="col-sm-10">
									<input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" minlength="13" maxlength="13" />
									<?php if ($error_model) { ?>
									<div class="text-danger">
										<?php echo $error_model; ?>
									</div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $product_weight; ?></label>
								<div class="col-sm-10">

									<input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
									<?php if ($error_weight) { ?>
									<div class="text-danger">
										<?php echo $error_weight; ?>
									</div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-model">
									<?php echo $entry_product_price; ?></label>
								<div class="col-sm-10">
									<input type="text" name="product_price" value="<?php echo $product_price; ?>" placeholder="<?php echo $entry_product_price; ?>" id="input-product_price" class="form-control" />
									<?php if ($error_product_price) { ?>
									<div class="text-danger">
										<?php echo $error_product_price; ?>
									</div>
									<?php } ?>
								</div>
							</div>

							<?php if(!$is_vendor){ ?>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status">
									<?php echo $entry_status; ?>
								</label>
								<div class="col-sm-10">
									<select name="status" id="input-status" class="form-control">
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
							<?php } ?>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="tab-seo">
							<ul class="nav nav-tabs" id="seo-language">
								<?php foreach ($languages as $language) { ?>
								<li><a href="#seo-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="seo-language<?php echo $language['language_id']; ?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-seo-url"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
										<div class="col-sm-10">
											<input type="text" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url" class="form-control" />
											<?php if (isset($error_seo_url[$language['language_id']])) { ?>
											<div class="text-danger">
												<?php echo $error_seo_url[$language['language_id']]; ?>
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]['meta_title']) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
											<?php if (isset($error_meta_title[$language['language_id']])) { ?>
											<div class="text-danger">
												<?php echo $error_meta_title[$language['language_id']]; ?>
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
										<div class="col-sm-10">
											<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
										<div class="col-sm-10">
											<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-links">

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
									<div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_categories as $product_category) { ?>
										<div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i>
											<?php echo $product_category['name']; ?>
											<input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
										</div>
										<?php } ?>
									</div>
								</div>
							</div>

						</div>



						<div class="tab-pane" id="tab-variation">

								<!-- <div class="form-group required">
									<label class="col-sm-2 control-label"><?php echo $product_unit; ?></label>
									<div class="col-sm-10">
										<input type="text" name="unit" value="<?php echo $unit; ?>" placeholder="<?php echo $product_unit; ?>" id="input-unit" class="form-control input-full-width" />
										<?php if ($error_unit) { ?>
										<div class="text-danger">
											<?php echo $error_unit; ?>
										</div>
										<?php } ?>
									</div>
								</div> -->

								<div class="table-responsive">
								<table id="variations" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<!-- <td class="text-left">
												<?php echo $entry_image; ?>
											</td>
											<td class="text-left"><?= $entry_name ?></td>
											 -->
											<td class="text-left">
												<?= $entry_weight ?>
											</td>
											<td class="text-left"><?= $entry_product_price ?></td>
											<td class="text-left"><?php echo $product_unit; ?></td>
											<td> Actions</td>

										</tr>
									</thead>
									<tbody>
										<tr>
										  <!-- <td class="text-left">
											  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
												  <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
											  </a>
											  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
										  </td>
										  	<td class="text-right">
											  <input type="text" name="default_variation_name" value="<?php echo $default_variation_name; ?>" placeholder="" class="form-control" id="input-default-variation-name" />
											  <?php if (isset($error_name[$default_variation_name])) { ?>
											  <div class="text-danger"><?php echo $error_name[$default_variation_name]; ?></div>
											  <?php } ?>
										  	</td>
											<td class="text-right"></td> -->
											<td class="text-left"> <input type="text" placeholder="<?php echo $entry_weight; ?>" id="dupli-input-weight" class="form-control input-full-width text-left"  /> </td>
											<td class="text-left">
												
												<input type="text" placeholder="<?php echo $entry_product_price; ?>" id="dupli-input-product_price" class="form-control input-full-width text-left"  /> 

											</td>

											<td class="text-right">
												<input type="text" name="unit" value="<?php echo $unit; ?>" placeholder="<?php echo $product_unit; ?>" id="input-unit" class="form-control input-full-width text-left"  />
												<?php if ($error_unit) { ?>
												<div class="text-danger">
													<?php echo $error_unit; ?>
												</div>
												<?php } ?>
										  	</td>

											
										  	<td class="text-left"></td>
										</tr>
										<?php $variation_row=0; ?>
										<?php foreach ($product_variations as $product_variation) { ?>
										<tr id="variation-row<?php echo $variation_row; ?>">
											<!-- < td class="text-left">
											
											  <img src="<?php echo $product_variation['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
											</td>
											<td class="text-left">
												<?php echo $product_variation['name']; ?>
											</td>
											<td class="text-left">
												<?php echo $product_variation['model']; ?>
											</td>
 -->

											<td class="text-left">
												<?php echo $product_variation['weight']; ?>
											</td>

											<td class="text-left">
												<?php echo $product_variation['default_price']; ?>
											</td>

											<td class="text-left">

												<?php echo $product_variation['unit']; ?>
											</td>

											<td class="text-left">

												<a href="<?php echo $this->url->link( 'catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_variation['product_id'], 'SSL' ) ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>


												<button type="button" data-id="<?php echo $product_variation['product_id'] ?>" class="btn btn-danger deleteVariation">
													<i class="fa fa-minus-circle"></i>
												</button>
										   </td>

										</tr>
										<?php $variation_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3"></td>
											<td class="text-left">
												<button type="button" onclick="addVariation();" data-toggle="tooltip" title="Add Variation" class="btn btn-primary">
													 <i class="fa fa-plus-circle"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						
						
						<div class="tab-pane" id="tab-product-type">

								<div class="table-responsive">
								<table id="productTypes" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
				
											<td class="text-left">
												Type
											</td>
											<!--<td class="text-left"><?= $entry_product_price ?></td>
											<td class="text-left"><?php echo $product_unit; ?></td>-->
											
											<!--<td> Actions</td>-->

										</tr>
									</thead>
									<tbody>
										<tr>
										 
											<td class="text-left"> <input name="product_types[]" type="text" placeholder="product type" id="dupli-input-weight" class="form-control input-full-width text-left"  /> </td>
										
										  	<!--<td class="text-left"></td>-->
										</tr>
										<?php $variation_row=0; 
										//echo '<pre>';print_r($product_types);exit;
										?>
										<?php foreach ($product_types as $product_type) { ?>
										<tr id="variation-row<?php echo $variation_row; ?>">
											

											<td class="text-left">
											 <input readonly name="product_types[]" type="text" placeholder="produce type" id="dupli-input-weight" value="<?php echo $product_type; ?>" class="form-control input-full-width text-left"  />
												
											</td>


											<td class="text-left">

												<!--<a href="<?php echo $this->url->link( 'catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_variation['product_id'], 'SSL' ) ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>-->


												<button type="button" data-id="<?php echo $product_variation['product_id'] ?>" class="btn btn-danger deleteProduceType">
													<i class="fa fa-minus-circle"></i>
												</button>
										   </td>

										</tr>
										<?php $variation_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3"></td>
											<td class="text-left">
												<button type="button" onclick="addProductType();" data-toggle="tooltip" title="Add Product type" class="btn btn-primary">
													 <i class="fa fa-plus-circle"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>

						<!-- <div class="tab-pane" id="tab-variation">
							<div class="form-group">
				                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
				                <div class="col-sm-10">
				                  <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
				                  <div id="variation_product" class="well well-sm" style="height: 130px; overflow: auto;">
				                    <?php foreach ($variation_product as $variation_product) { ?>
				                    <div id="variation_product<?php echo $variation_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $variation_product['name']; ?>
				                      <input type="hidden" name="variation_product[]" value="<?php echo $variation_product['product_id']; ?>" />
				                    </div>
				                    <?php } ?>
				                  </div>
				                </div>
				            </div>
						</div> -->

						<div class="tab-pane" id="tab-images">

								<div class="table-responsive">
								<table id="images" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left">
												<?php echo $entry_image; ?>
											</td>
											<td><?php echo $entry_sort_order; ?></td>
											<td class="text-left"> Actions </td>
										</tr>
									</thead>
									<tbody>
										<tr>
										    <td class="text-left">
											  <a href="" id="thumb-image-default" data-toggle="image" class="img-thumbnail">
												  <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
											  </a>
											  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
										    </td>
											<td class="text-right"></td>
											<td class="text-left"></td>
										</tr>
										<?php $image_row=0; ?>
										<?php foreach ($product_images as $product_image) { ?>
										<tr id="image-row<?php echo $image_row; ?>">
											<td class="text-left">
											
											  <img src="<?php echo $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
											</td>
											<td class="text-right">
												<?php echo $product_image['sort_order']; ?>
											</td>

											<td class="text-left">
												<button type="button" data-id="<?php echo $product_image['product_image_id'] ?>" class="btn btn-danger deleteImage">
													<i class="fa fa-minus-circle"></i>
												</button>
										   </td>

										</tr>
										<?php $image_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2"></td>
											<td class="text-left">
												<button type="button" onclick="addImages();" data-toggle="tooltip" title="Add Image" class="btn btn-primary">
													 <i class="fa fa-plus-circle"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>

					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val('');
		
		$('#variation_product' + item['value']).remove();
		
		$('#variation_product').append('<div id="variation_product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="variation_product[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#variation_product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
</script>

<script type="text/javascript">
	<!--
	var variation_row = 1;

	function addVariation() {

		html = '<tr id="variation-row' + variation_row + '">';
		/*html += '  <td class="text-left"><a href="" id="variation-image' + variation_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_variation[' + variation_row + '][image]" value="" id="input-variation' + variation_row + '" /></td>';
		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][model]" value="" placeholder="<?php echo $entry_model; ?>" class="form-control" /></td>';*/

		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][weight]" value="" placeholder="<?php echo $product_weight; ?>" class="pull-left form-control input-full-width text-left" /></td>';


		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][product_price]" value="" placeholder="<?php echo $entry_product_price; ?>" class="pull-left form-control input-full-width text-left" /></td>';


		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][unit]" value="" placeholder="<?php echo $product_unit; ?>" class="pull-left form-control input-full-width text-left" /></td>';


		html += '  <td class="text-left"><button type="button" onclick="$(\'#variation-row' + variation_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#variations tbody').append(html);

		variation_row++;
	}
	
	var producttype_row = 1;

	function addProductType() {

		html = '<tr id="variation-row' + producttype_row + '">';
		/*html += '  <td class="text-left"><a href="" id="variation-image' + variation_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_variation[' + variation_row + '][image]" value="" id="input-variation' + variation_row + '" /></td>';
		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><input type="text" name="product_variation[' + variation_row + '][model]" value="" placeholder="<?php echo $entry_model; ?>" class="form-control" /></td>';*/

		html += '  <td class="text-left"><input type="text" name="product_types[]" value="" placeholder="produce type" class="pull-left form-control input-full-width text-left" /></td>';


		//html += '  <td class="text-left"><input type="text" name="product_types[' + producttype_row + '][product_price]" value="" placeholder="<?php echo $entry_product_price; ?>" class="pull-left form-control input-full-width text-left" /></td>';


		//html += '  <td class="text-left"><input type="text" name="product_types[' + producttype_row + '][unit]" value="" placeholder="<?php echo $product_unit; ?>" class="pull-left form-control input-full-width text-left" /></td>';


		html += '  <td class="text-left"><button type="button" onclick="$(\'#variation-row' + producttype_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#productTypes tbody').append(html);

		producttype_row++;
	}
	
	
	//-->
</script>

<script type="text/javascript">
	<!--
	var image_row = 1;

	//console.log($image_row);

	function addImages() {
		console.log("addImages");

		html = '<tr id="image-row' + image_row + '">';
		html += '  <td class="text-left"><a href="" id="image-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="pull-right form-control" /></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#images tbody').append(html);

		image_row++;
	}
	//-->
</script>

<script type="text/javascript">
	<!--
	<?php foreach ($languages as $language) { ?>
	<?php if( $text_editor == 'summernote' ) { ?>
	$('#input-description<?php echo $language['language_id']; ?>').summernote({
		height: 300
	});
	<?php } else if ( $text_editor == 'tinymce' ) { ?>
	$('#input-description<?php echo $language['language_id']; ?>').tinymce({
		script_url: 'ui/javascript/tinymce/tinymce.min.js',
		plugins: "visualblocks,textpattern,table,media,pagebreak,link,image",
		target_list: [{
			title: 'None',
			value: ''
		}, {
			title: 'Same page',
			value: '_self'
		}, {
			title: 'New page',
			value: '_blank'
		}, {
			title: 'LIghtbox',
			value: '_lightbox'
		}]
	});
	<?php } ?>
	<?php } ?>
	//-->
</script>
<script type="text/javascript">
	<!--
	// Manufacturer
	$('input[name=\'manufacturer\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?path=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						manufacturer_id: 0,
						name: '<?php echo $text_none; ?>'
					});

					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'manufacturer\']').val(item['label']);
			$('input[name=\'manufacturer_id\']').val(item['value']);
		}
	});

	// Category
	$('input[name=\'category\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?path=catalog/product/category_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['index'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'category\']').val('');
			$('#product-category' + item['value']).remove();
			$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#product-category').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Related
	$('input[name=\'related\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'related\']').val('');

			$('#product-related' + item['value']).remove();

			$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#product-related').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
	//-->
</script>



<script type="text/javascript">
	<!--

	$('.date').datetimepicker({
		pickTime: false
	});

	$('.time').datetimepicker({
		pickDate: false
	});

	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true
	});

	//-->
</script>
<script type="text/javascript">
	<!--
	$('#language a:first').tab('show');
	$('#seo-language a:first').tab('show');
	//-->
</script>

<div class="modal fade" id="gridSystemModal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="gridSystemModalLabel"><strong><?= $button_edit_variation ?></strong></h4>
	  </div>
		
			
	  <div class="modal-body">
			<div class="col-md-4">
			<div class="form-group">	
				<a href="" id="variation-image" data-toggle="image" class="img-thumbnail">
					<img src="<?php echo $placeholder; ?>" id="thumb" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
					<input type="hidden" name="variation_image" value="" id="variation_image" />
				</a>
			</div>
			</div>
			<div class="col-md-8">
			<div class="form-group">
				<input type="text" name="variation_name" id="variation_name" value="" placeholder="Variation Name" class="form-control" />
			</div>	
			<input type="hidden" name="variation_id" id="variation_id" value="" class="form-control" />
			<div class="form-group">
				<input type="text" name="model" id="model" value="" placeholder="Model" class="form-control" />
			</div>
			<div class="form-group">
				<input type="text" name="sort_order" id="sort_order" value="" placeholder="Sort Order" class="form-control" />
			</div>
			</div>
	  </div>
	  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= $button_close ?></button>
			<button type="button" id="updateVariation" class="btn btn-success"><?= $button_save_changes ?></button>
	  </div>
	  
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	<!--
	function save(type) {
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'button';
		input.value = type;
		form = $("form[id^='form-']").append(input);
		form.submit();
	}
	//-->
	function editmodel(variation_id,product_id){
		$('#gridSystemModal').modal('show');
		var data = {
			variation_id : variation_id,
			product_id : product_id,
			token:'<?php echo $token; ?>'
		};
		$.ajax({
			url:'index.php?path=catalog/general/getVariationData',
			data:data,
			success:function(data){
				$('#variation_id').val(data.variation_id);
				$('#thumb').attr('src', data.thumb);
				$('#variation_image').attr('src', data.thumb);
				$('#variation_image').val(data.img);
				$('#variation_name').val(data.name);
				$('#model').val(data.model);
				$('#sort_order').val(data.sort_order);
			}
		});

	}

$('#updateVariation').click(function(){
	
	var data = {
		variation_id : $('#variation_id').val(),
		variation_name : $('#variation_name').val(),
		sort_order : $('#sort_order').val(),
		variation_image : $('#variation_image').val(),
		model : $('#model').val(),
		token:'<?php echo $token; ?>'
	};

	$.ajax({
		url:'index.php?path=catalog/general/updateVariation&token=<?php echo $token; ?>',
		data:data,
		method:'post',
		success:function(data){
			window.location.reload();
		}
	});

});

$('#images').delegate('.deleteImage', 'click', function() {
	console.log('remove');
	$(this).closest('tr').remove();
	var data = {
		product_image_id :$(this).data('id'),
		token:'<?php echo $token; ?>'
	};

	$.ajax({
		url:'index.php?path=catalog/general/deleteImage',
		data:data,
		success:function(data){
			
		}
	});
});

$('#variations').delegate('.deleteVariation', 'click', function() {
	console.log('deleteVariation remove');
	$(this).closest('tr').remove();
	var data = {
		variation_id :$(this).data('id'),
		token:'<?php echo $token; ?>'
	};

	$.ajax({
		url:'index.php?path=catalog/general/deleteVariation',
		data:data,
		success:function(data){
			
		}
	});
});

$('#productTypes').delegate('.deleteProduceType', 'click', function() {
	console.log('deleteVariation remove');
	$(this).closest('tr').remove();
	/*var data = {
		variation_id :$(this).data('id'),
		token:'<?php echo $token; ?>'
	};

	$.ajax({
		url:'index.php?path=catalog/general/deleteVariation',
		data:data,
		success:function(data){
			
		}
	});
	*/
});


$(document).ready(function () {

	$('#dupli-input-weight').val($('#input-weight').val());
	$('#dupli-input-product_price').val($('#input-product_price').val());

    $('#input-weight').keyup(function () { 

   		$('#dupli-input-weight').val($('#input-weight').val());
    });

    $('#input-product_price').keyup(function () { 

   		$('#dupli-input-product_price').val($('#input-product_price').val());

    });

    $('#dupli-input-weight').keyup(function () { 

   		$('#input-weight').val($('#dupli-input-weight').val());
    });

    $('#dupli-input-product_price').keyup(function () { 

   		$('#input-product_price').val($('#dupli-input-product_price').val());

    });

});


</script>
<?php echo $footer;