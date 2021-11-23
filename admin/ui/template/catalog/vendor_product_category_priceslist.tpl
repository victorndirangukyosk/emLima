<?php echo $header; ?><?php echo $column_left;?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                
            <?php if($is_vendor){ ?>
                <!-- <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a> -->
                <!--<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>-->
            <?php }else{ ?>
                <!--<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>-->
                <!--<button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i></button>
                <!--<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>-->
            <button type="button" onclick="addnewproduct();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Add New"><i class="fa fa-plus"></i></button>
            <?php } ?>
	    <!--<span style="margin-left: 10px;" onclick="ChangeCategoryPrices()" form="form-product" data-toggle="tooltip" title="" class="btn btn-success"><i class="fa fa-check"></i></span>-->
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
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>
            </div>
            <div class="panel-body">

                <div class="well" style="display:none;">
                    <div class="row">
                         <div class="<?php echo $is_vendor ? 'col-sm-4' : 'col-sm-4' ?>">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>
							
                            <div class="form-group">
                                <label class="control-label" for="input-category">Price Category</label>
                                <select name="filter_category_price" id="input-category-price" class="form-control">
                                    <option value="*"></option>
                                    <?php foreach ($price_categories_list as $price_category) { ?>
                                    <?php if ($price_category['price_category'] == $filter_category_price) { ?>
                                    <option value="<?php echo $price_category['price_category']; ?>" selected="selected"><?php echo $price_category['price_category']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $price_category['price_category']; ?>"><?php echo $price_category['price_category']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <!--<div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                            </div>-->

                            <!--<div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_from; ?></label>
                                <input type="text" name="filter_product_id_from" value="<?php echo $filter_product_id_from; ?>" placeholder="<?php echo $entry_product_id_from; ?>" id="input-model" class="form-control" />
                            </div>-->
                            <div class="form-group">
                                <label class="control-label" for="input-model">Price Category Status</label>
                                <select name="filter_price_category_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_price_category_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_price_category_status && !is_null($filter_price_category_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                        <?php if (!$is_vendor): ?>
                            
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_id" value="<?php echo $filter_store_id; ?>" placeholder="Store Name" id="input-store-name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?= $entry_vendor_name ?></label>
                                <input type="text" name="filter_vendor_name" value="<?php echo $filter_vendor_name; ?>" placeholder="Vendor Name" id="input-model" class="form-control" />
                            </div>

                            <!--<div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_price; ?></label>
                                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-model" class="form-control" />
                            </div>-->
                            
                        </div>
                        <?php endif ?>

                        <?php if ($is_vendor): ?>
                            
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_id" value="<?php echo $filter_store_id; ?>" placeholder="Store Name" id="input-store-name" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_price; ?></label>
                                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-model" class="form-control" />
                            </div>

                        </div>
                        <?php endif ?>


                        <div class="<?php echo $is_vendor ? 'col-sm-4' : 'col-sm-4' ?>">
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="filter_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_status && !is_null($filter_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-category"><?php echo $column_category; ?></label>
                                <select name="filter_category" id="input-category" class="form-control">
                                    <option value="*"></option>
                                    <?php foreach ($categories as $category) { ?>
                                    <?php if ($category['category_id'] == $filter_category) { ?>
                                    <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <!--<div class="form-group">
                                <label class="control-label" for="input-model">Price Category Status</label>
                                <select name="filter_price_category_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_price_category_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_price_category_status && !is_null($filter_price_category_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>-->
                            </div> 
                            <!--<div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_to; ?></label>
                                <input type="text" name="filter_product_id_to" value="<?php echo $filter_product_id_to; ?>" placeholder="<?php echo $entry_product_id_to; ?>" id="input-model" class="form-control" />
                            </div>-->


                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-center"><?php echo $column_image; ?></td>
                                    <!-- <td class="text-left"><?= $column_product_id ?></td> -->
                                    

                                    <td class="text-left"><?php if ($sort == 'p.product_id') { ?>
                                        <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_product_id; ?>"><?php echo $column_product_id; ?></a>
                                        <?php } ?></td>

                                    <!--<td class="text-left"><?= $column_vproduct_id ?></td> -->

                                    <td class="text-left"><?php if ($sort == 'ps.product_store_id') { ?>
                                        <a href="<?php echo $sort_vproduct_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_vproduct_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_vproduct_id; ?>"><?php echo $column_vproduct_id; ?></a>
                                        <?php } ?></td>



                                    <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td>Unit</td>
                                    <?php if(count($price_categories)>0){
										foreach($price_categories as $price_cat){
										?>
										<td>Category : <?= $price_cat['price_category'] ?></td>
									<?php }?>
									<?php }?>
                                    <!-- <td><?= $entry_model ?></td> -->
                                    <!--<td class="text-left"><?php if ($sort == 'p.model') { ?>
                                        <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_model; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_model; ?>"><?php echo $entry_model; ?></a>
                                        <?php } ?></td>-->


                                    <!--<td><?= $column_unit ?> (Unit Of measure)</td>-->

                                    <!--<td class="text-left"><?php if ($sort == 'st.name') { ?>
                                        <a href="<?php echo $sort_store; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_store_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_store; ?>"><?php echo $column_store_name; ?></a>
                                        <?php } ?></td>-->

                                    <!-- <td>Vendor Name</td> -->
                                    <!--<td class="text-left"><?php if ($sort == 'p2c.category_id') { ?>
                                        <a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
                                        <?php } ?></td>

                                    <td class="text-left"><?php if ($sort == 'ps.quantity') { ?>
                                        <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                                        <?php } ?></td>

                                    <td><?= $column_price  ?></td>
                                    <td class="text-left"><?php echo $column_status; ?></a>
                                       </td>-->
									   
                                     <!--<td class="text-right"><?php echo 'Current '.$column_quantity; ?></td>
                                     <td class="text-right"><?php echo 'Total Procured Qty'; ?></td>
                                     <td class="text-right"><?php echo 'Rejected Qty'; ?></td>
									 <td class="text-right"><?php echo 'Total Qty'; ?></td>-->
                                     <td class="text-right"><?php echo $column_action; ?></td>
                                     
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products) { ?>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($product['product_store_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_store_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_store_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-center"><?php if ($product['image']) { ?>
                                        <a href="<?php echo $product['bigimage']; ?>" target="_blank"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                                        <?php } else { ?>
                                        <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $product['product_id']; ?></td>
                                    <td class="text-left"><?php echo $product['product_store_id']; ?></td>
                                    <td class="text-left"><?php echo $product['name']; ?></td>
                                    <td class="text-left"><?php echo $product['unit']; ?></td>
									<?php //echo '<pre>';print_r($category_prices);exit;?>
                                    <?php if(count($price_categories)>0){
										foreach($price_categories as $price_cat){
										?>
										<td>
                                         <input data-price-category="<?php echo $price_cat['price_category'];?>" type="number" class="category_price_<?php echo $product['product_store_id'];?>"  id="category_price_<?php echo $product['product_store_id'];?>_<?php echo $price_cat['price_category'];?>"  value="<?= ($category_prices[$product['product_store_id'].'_'.$price_cat['price_category'].'_75']) ? $category_prices[$product['product_store_id'].'_'.$price_cat['price_category'].'_75'] : ''  ?>">
										<? //= ($category_prices[$product['product_store_id'].'_'.$price_cat['price_category'].'_75']) ? $category_prices[$product['product_store_id'].'_'.$price_cat['price_category'].'_75'] : '-'  ?>
										</td>
									<?php }?>
									<?php }?>
                                 
                                <td class="text-right">
                                    <?php if(isset($product['category_price_status']) && $product['category_price_status'] != NULL && $product['category_price_status'] == 1) { ?>
                                    <button type="button" onclick="ChangeCategoryPricesStatus('<?php echo $product['product_store_id'];?>','<?php echo $product['product_id'];?>','<?php echo $product['name']; ?>', 0, '<?php echo $price_cat['price_category']; ?>')" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Disable Product Category Price Status"><i class="fa fa-check-circle text-success"></i></button>
                                    <?php } ?>
                                    <?php if(isset($product['category_price_status']) && $product['category_price_status'] != NULL && $product['category_price_status'] == 0) { ?>
                                    <button type="button" onclick="ChangeCategoryPricesStatus('<?php echo $product['product_store_id'];?>','<?php echo $product['product_id'];?>','<?php echo $product['name']; ?>', 1, '<?php echo $price_cat['price_category']; ?>')" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Enable Product Category Price Status"><i class="fa fa-times-circle text-danger"></i></button>
                                    <?php } ?>
                                    <button type="button" onclick="ChangeCategoryPrices('<?php echo $product['product_store_id'];?>','<?php echo $product['product_id'];?>','<?php echo $product['name']; ?>')" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Save"><i class="fa fa-save text-success"></i></button>
									<button type="button" onclick="getProductInventoryHistory('<?php echo $product['product_store_id']; ?>');" 
									data-toggle="modal" data-target="#<?php echo $product['product_store_id']; ?>historyModal"
								    title="" class="btn btn-default" data-original-title="History"><i class="fa fa-history text-success"></i></button>
							    </td>
                                    
                                </tr>
									<div id="<?php echo $product['product_store_id']; ?>historyModal" class="modal fade" role="dialog">
									  <div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content">
										  <div style="color: white;background-color: #008db9;" class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title"><strong>Category Prices History : <?php echo $product['name']; ?></strong></h4>
										  </div>
										  <div class="modal-body">
											
										  </div>
										  <!--<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										  </div>-->
										</div>

									  </div>
									</div>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
				
                    
                    <div class="modal fade" id="store_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> 
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><?= $text_select_store ?></h4>
                                </div>
                                <div class="modal-body">

                                    <div class="message_wrapper"></div>

                                    <div class="form-group">
                                        <input type="text" name="store" value="" placeholder="Store name" id="input-product" class="form-control" />
                                        <div id="store-list" class="well well-sm" style="max-width: 100%; height: 150px; overflow: auto;">
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $button_close ?></button>
                                    <button onclick="submit_copy();" type="button" class="btn btn-primary"><?= $button_submit ?></button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.modal -->
                </form>
               
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
<div id="addnewModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div style="color: white;background-color: #008db9;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><strong>Add Product To Price Category </strong></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Price Category:</label>
                        <select class="form-select" id="select_price_category" name="select_price_category">
                            <option value="">Select Price Category</option>
                            <?php foreach ($price_categories_list as $price_category) { ?>
                            <option value="<?php echo $price_category['price_category']; ?>"><?php echo $price_category['price_category']; ?></option>
                            <?php } ?>    
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Product</label>
                        <input type="text" class="form-control" id="vendor_product_name" name="vendor_product_name">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Product UOM</label>
                        <select class="form-select" id="vendor_product_uom" name="vendor_product_uom">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Price</label>
                        <input type="number" class="form-control" id="vendor_product_price" name="vendor_product_price">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Add New</button>
            </div>
        </div>

    </div>
</div>
    <script type="text/javascript"><!--
        
$(document).delegate('#store-list .fa-minus-circle','click', function(){
    $(this).parent().remove();
});

$('input[name=\'filter_store_id\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?path=setting/store/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['store_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {

        
        $('input[name=\'filter_store_id\']').val(item['label']);
    }
});

$('input[name=\'filter_vendor_name\']').autocomplete({
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

        
        $('input[name=\'filter_vendor_name\']').val(item['label']);
    }
});                                   


function submit_copy() {
    
    $('.message_wrapper').html('');
    
    $error = '';
    
    if($('input[name="product_store[]"').length == 0){
        $error += '<li>Select store(s).</li>';
    }
    
    if($('input[name="selected[]"]:checked').length == 0){
        $error += '<li>Select products.</li>';
    }
    
    if(!$error){        
        $('form').attr('action','index.php?path=catalog/product/copy&token=<?= $token ?>').submit();
    } else{        
        $('.message_wrapper').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close">&times</button><ul class="list list-unstyled">'+$error+'</ul></div>');
    }
}

  $('#button-filter').on('click', function() {

            var url = 'index.php?path=catalog/vendor_product/category_priceslist&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_vendor_name = $('input[name=\'filter_vendor_name\']').val();

            if (filter_vendor_name) {
                url += '&filter_vendor_name=' + encodeURIComponent(filter_vendor_name);
            }

            var filter_category = $('select[name=\'filter_category\']').val();

            if (filter_category != '*') {
                url += '&filter_category=' + encodeURIComponent(filter_category);
            }
			
	    var filter_category_price = $('select[name=\'filter_category_price\']').val();

            if (filter_category_price != '*') {
                url += '&filter_category_price=' + encodeURIComponent(filter_category_price);
            }

            var filter_price = $('input[name=\'filter_price\']').val();

            if (filter_price) {
                url += '&filter_price=' + encodeURIComponent(filter_price);
            }

            var filter_product_id_to = $('input[name=\'filter_product_id_to\']').val();

            if (filter_product_id_to) {
                url += '&filter_product_id_to=' + encodeURIComponent(filter_product_id_to);
            }

            var filter_product_id_from = $('input[name=\'filter_product_id_from\']').val();

            if (filter_product_id_from) {
                url += '&filter_product_id_from=' + encodeURIComponent(filter_product_id_from);
            }
            
            var filter_model = $('input[name=\'filter_model\']').val();

            if (filter_model) {
                url += '&filter_model=' + encodeURIComponent(filter_model);
            }
            

            var filter_store_id = $('input[name=\'filter_store_id\']').val();

            if (filter_store_id) {
                url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
            }

            var filter_status = $('select[name=\'filter_status\']').val();

            if (filter_status != '*') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }
            
            var filter_price_category_status = $('select[name=\'filter_price_category_status\']').val();

            if (filter_price_category_status != '*') {
                url += '&filter_price_category_status=' + encodeURIComponent(filter_price_category_status);
            }

            location = url;
        });
  //--></script> 
    <script type="text/javascript"><!--
  $('input[name=\'filter_name\']').autocomplete({
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
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });

        $('input[name=\'filter_model\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['model'],
                                value: item['product_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_model\']').val(item['label']);
            }
        });
  //--></script></div>

<script type="text/javascript"><!--
function changeStatus(status) {
        $.ajax({
            url: 'index.php?path=common/edit/changeStatus&type=product&status=' + status + '&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $("form[id^='form-']").serialize(),
            success: function(json) {
                if (json) {
                    $('.panel.panel-default').before('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json.warning + '<button type="button" class="close" data-dismiss="alert">×</button></div>');
                }
                else {
                    location.reload();
                }
            }
        });
    }
//--></script>
<script type="text/javascript"><!--
function changeStatus(status) {
        $.ajax({
            url: 'index.php?path=common/edit/changeStatus&type=vendor_product&status=' + status + '&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $("form[id^='form-']").serialize(),
            success: function(json) {
                if (json) {
                    $('.panel.panel-default').before('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json.warning + '<button type="button" class="close" data-dismiss="alert">×</button></div>');
                }
                else {
                    location.reload();
                }
            }
        });
    }

function getProductInventoryHistory(product_store_id){
	  $('.modal-body').html('');
	   $.ajax({
                    url: 'index.php?path=catalog/product/getProductCategoryPricesHistory&token=<?= $token ?>',
                    dataType: 'html',
                    data: {product_store_id :product_store_id},
                    success: function(json) {
					   $('.modal-body').html(json);
                    },
					error: function(json) {
					 console.log('html',json);
					  $('.modal-body').html(json);
                    }
         });
}

function addnewproduct(){
    $('#addnewModal').modal('toggle');
}

function ChangeCategoryPrices(product_store_id,product_id,product_name){

        var tempObj = {};
		tempObj.product_store_id = product_store_id;
		tempObj.product_id = product_id;
		tempObj.product_name = product_name;
		var category_prices = [];
        $("select#input-category-price option").each(function()
		{
			var category = $(this).val();
			if(category !="*")
			tempObj[category] = $("#category_price_"+product_store_id+'_'+category).val();
			
		});
		
        $.ajax({
                    url: 'index.php?path=catalog/product/updateCategoryPrices&token=<?= $token ?>',
                    dataType: 'json',
                    data: {updatedata :tempObj},
                    success: function(json) {
                        if (json) {
                            $('.panel.panel-default').before('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json.warning + '<button type="button" class="close" data-dismiss="alert">×</button></div>');
                        }
                        else {
                            location.reload();
                        }
                    }
        });


}

function ChangeCategoryPricesStatus(product_store_id,product_id,product_name,status, price_category){
	
        $.ajax({
                    url: 'index.php?path=catalog/vendor_product/updateCategoryPricesStatuss&token=<?= $token ?>',
                    dataType: 'json',
                    data: { product_store_id :product_store_id, product_id : product_id, product_name : product_name, status : status, price_category : price_category },
                    success: function(json) {
                        if (json) {
                            $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check"></i> ' + json.warning + '<button type="button" class="close" data-dismiss="alert">×</button></div>');
                         setTimeout(function(){ location.reload(); }, 1500);
                                                        
                    }
                    }
        });


}

$('input.procured_qty').keyup(function(){

    var current_qty = $(this).attr('data-current-qty');  

	var procured_qty = $(this).val();
	var vendor_product_id = $(this).attr('id');
     var rejected_qty=0;
    if ($('#rejected_qty_'+vendor_product_id).val().length != 0){
      rejected_qty =$('#rejected_qty_'+vendor_product_id).val();
    }
     
	var total = parseFloat(current_qty) + parseFloat(procured_qty)+parseFloat(rejected_qty);
	$('#total_qty_'+vendor_product_id).val(total);
});



$('input.rejected_qty').keyup(function(){
    var current_qty = $(this).attr('data-current-qty');
	var rejected_qty = $(this).val();
    
	var vendor_product_id = $(this).attr('id');
   vendor_product_id=vendor_product_id.replace('rejected_qty_','');

	 var procured_qty =0;
     if ($('#'+vendor_product_id).val().length != 0){
      procured_qty =$('#'+vendor_product_id).val();
    }

     if(parseFloat(procured_qty) < parseFloat(rejected_qty)){
		alert("Rejected quantity should be less than procured quantity!");
		$('#rejected_qty_'+vendor_product_id).val(0);
		rejected_qty = 0;
		
	 }
	var total = parseFloat(current_qty) + ( parseFloat(procured_qty) - parseFloat(rejected_qty) );
	$('#total_qty_'+vendor_product_id).val(total);
});

$('input[name=\'vendor_product_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=sale/order/product_autocomplete_category&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) +'&filter_price_category_name=' + encodeURIComponent($('select[name=\'select_price_category\']').val()),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name']+' '+item['unit'],
                                value: item['product_store_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                console.log(item['value']);
                var selected_product_store_id = item['value'];
                var category_pricing_name = $('select[name=\'select_price_category\']').val();
                $('input[name=\'vendor_product_name\']').val(item['label']);
                $.ajax({
                url: 'index.php?path=sale/order/getVendorProductVariantsInfo&product_store_id='+selected_product_store_id+'&token=<?php echo $token; ?>&category_pricing_name='+category_pricing_name,
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    var option = '';
                    for (var i=0;i<json.length;i++){
                           option += '<option data-model="'+ json[i].model +'" data-product_id="'+ json[i].product_store_id +'" data-price="'+ json[i].price +'" data-special="'+ json[i].special_price +'" value="'+ json[i].unit + '">' + json[i].unit + '</option>';
                    }
                    console.log(option);
                    var $select = $('#vendor_product_uom');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    var $price_input = $('#vendor_product_price');
                    var special_price = json[0].price == null || json[0].price == 0 ? json[0].special_price : json[0].price;
                    $price_input.val(special_price.replace(/,/g, ""));
                }
            });
            }
});
//--></script>

<?php echo $footer; ?>
<style>
.bootstrap-select {
width : 100% !important;    
}
</style>