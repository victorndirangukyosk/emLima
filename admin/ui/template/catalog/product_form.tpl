<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
		<button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
		<button type="submit" onclick="save('new')" form="form-product" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
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
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-seo" data-toggle="tab"><?php echo $tab_seo; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-variation" data-toggle="tab"><?= $tab_variations ?></a></li>
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
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
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
                <label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                  <?php if ($error_model) { ?>
                  <div class="text-danger"><?php echo $error_model; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="<?php echo $help_sku; ?>"><?php echo $entry_sku; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-upc"><span data-toggle="tooltip" title="<?php echo $help_upc; ?>"><?php echo $entry_upc; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="upc" value="<?php echo $upc; ?>" placeholder="<?php echo $entry_upc; ?>" id="input-upc" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-location"><?php echo $entry_location; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="location" value="<?php echo $location; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                <div class="col-sm-10">
                  <select name="tax_class_id" id="input-tax-class" class="form-control">
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
                <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-subtract"><?php echo $entry_subtract; ?></label>
                <div class="col-sm-10">
                  <select name="subtract" id="input-subtract" class="form-control">
                    <?php if ($subtract) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="stock_status_id" id="input-stock-status" class="form-control">
                    <?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_shipping; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($shipping) { ?>
                    <input type="radio" name="shipping" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$shipping) { ?>
                    <input type="radio" name="shipping" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              <!-- 
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-length"><?php echo $entry_dimension; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="text" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>                
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
                <div class="col-sm-10">
                  <select name="length_class_id" id="input-length-class" class="form-control">
                    <?php foreach ($length_classes as $length_class) { ?>
                    <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight"><?php echo $entry_weight; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
                <div class="col-sm-10">
                  <select name="weight_class_id" id="input-weight-class" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                    <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
                -->
                
              <?php if(!$is_vendor){ ?>  
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
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
                                <div class="text-danger"><?php echo $error_seo_url[$language['language_id']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]['meta_title']) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                                <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                                <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
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
                <!--
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
                  <input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
                </div>
              </div>
                -->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_categories as $product_category) { ?>
                    <div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
                      <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
             
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">                  
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $product_store)) { ?>
                        <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
                  <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_relateds as $product_related) { ?>
                    <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
                      <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
           
            <!--  
            <div class="tab-pane" id="tab-special">
              <div class="table-responsive">
                <table id="special" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_priority; ?></td>
                      <td class="text-right"><?php echo $entry_price; ?></td>
                      <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $special_row = 0; ?>
                    <?php foreach ($product_specials as $product_special) { ?>
                    <tr id="special-row<?php echo $special_row; ?>">
                      <td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group date">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group date">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left"><button type="button" onclick="$('#special-row<?php echo $special_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $special_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="<?php echo $button_special_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            -->
              
            <div class="tab-pane" id="tab-variation">

              <div class="table-responsive">
                <table id="variations" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_image; ?></td>
                      <td class="text-left"><?= $entry_name ?></td>
                      <td class="text-left"><?php echo $entry_price; ?></td>
                      <td class="text-left"><?= $entry_special_price ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left">
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
                      <td class="text-left">                          
                        <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                      </td>
                      <td class="text-left">                          
                        <input type="text" name="special_price" value="<?php echo $special_price; ?>" placeholder="Special price" id="input-special-price" class="form-control" />
                      </td>
                      <td class="text-right"></td>
                      <td class="text-left"></td>
                    </tr>
                    
                    <?php $variation_row = 0; ?>
                    <?php foreach ($product_variations as $product_variation) { ?>
                    <tr id="variation-row<?php echo $variation_row; ?>">
                      <td class="text-left">
                          <a href="" id="variation-image<?php echo $variation_row; ?>" data-toggle="image" class="img-thumbnail">
                              <img src="<?php echo $product_variation['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                          </a>
                          <input type="hidden" name="product_variation[<?php echo $variation_row; ?>][image]" value="<?php echo $product_variation['image']; ?>" id="input-image<?php echo $variation_row; ?>" />
                      </td>
                      <td class="text-right">
                          <input type="text" name="product_variation[<?php echo $variation_row; ?>][name]" value="<?php echo $product_variation['name']; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                      </td>
                      <td class="text-left">
                          <input type="text" name="product_variation[<?php echo $variation_row; ?>][price]" value="<?php echo $product_variation['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" />
                      </td>
                      <td class="text-left">
                          <input type="text" name="product_variation[<?php echo $variation_row; ?>][special_price]" value="<?php echo $product_variation['special_price']; ?>" placeholder="Special price" class="form-control" />
                      </td>
                      <td class="text-right">
                          <input type="text" name="product_variation[<?php echo $variation_row; ?>][sort_order]" value="<?php echo $product_variation['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="pull-right form-control" />
                      </td>
                      <td class="text-left">
                          <button type="button" onclick="$('#variation-row<?php echo $variation_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $variation_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
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
              
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
	<?php if( $text_editor == 'summernote' ) { ?>
		$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
	<?php } else if ( $text_editor == 'tinymce' ) { ?>
		$('#input-description<?php echo $language['language_id']; ?>').tinymce({
			script_url : 'ui/javascript/tinymce/tinymce.min.js',
			plugins: "visualblocks,textpattern,table,media,pagebreak,link,image",
		      target_list: [
		       {title: 'None', value: ''},
		       {title: 'Same page', value: '_self'},
		       {title: 'New page', value: '_blank'},
		       {title: 'LIghtbox', value: '_lightbox'}
		      ]
		});
	<?php } ?>
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
			url: 'index.php?path=catalog/product/category_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
		$('input[name=\'related\']').val('');
		
		$('#product-related' + item['value']).remove();
		
		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script> 
  
  <script type="text/javascript"><!--
var variation_row = <?php echo $variation_row; ?>;

function addVariation() {
	html  = '<tr id="variation-row' + variation_row + '">';
	html += '  <td class="text-left"><a href="" id="variation-image' + variation_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_variation[' + variation_row + '][image]" value="" id="input-variation' + variation_row + '" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][special_price]" value="" placeholder="Special price" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="pull-right form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#variation-row' + variation_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#variations tbody').append(html);
	
	variation_row++;
}
//--></script> 
  
<script type="text/javascript"><!--
    
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

//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#seo-language a:first').tab('show');
//--></script></div>

<script type="text/javascript"><!--
function save(type){
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}
//--></script>
            
<?php echo $footer; ?> 