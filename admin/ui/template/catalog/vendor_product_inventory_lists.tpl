<?php echo $header; ?><?php echo $column_left;?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                
            <?php if($is_vendor){ ?>
                <!-- <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a> -->
                <!--<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>-->
            <?php }else{ ?>
                <button type="button" id="new_update_inventory" data-toggle="tooltip" title="Update Inventory" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                <button type="button" data-toggle="tooltip" title="Update Inventory" class="btn btn-default" onclick="updateinventory();"><i class="fa fa-floppy-o text-success"></i></button>
                <!--<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>-->
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i></button>
                <!--<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>-->
            <?php } ?>
				<span style="margin-left: 10px;" onclick="ChangeInventory()" form="form-product" data-toggle="tooltip" title="Change Inventory" class="btn btn-success"><i class="fa fa-check"></i></span>
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
                                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_from; ?></label>
                                <input type="text" name="filter_product_id_from" value="<?php echo $filter_product_id_from; ?>" placeholder="<?php echo $entry_product_id_from; ?>" id="input-model" class="form-control" />
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

                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_price; ?></label>
                                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-model" class="form-control" />
                            </div>
                            
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

                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_to; ?></label>
                                <input type="text" name="filter_product_id_to" value="<?php echo $filter_product_id_to; ?>" placeholder="<?php echo $entry_product_id_to; ?>" id="input-model" class="form-control" />
                            </div>


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

                                    <!-- <td><?= $entry_model ?></td> -->
                                    <!--<td class="text-left"><?php if ($sort == 'p.model') { ?>
                                        <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_model; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_model; ?>"><?php echo $entry_model; ?></a>
                                        <?php } ?></td>-->


                                    <td><?= $column_unit ?> (Unit Of measure)</td>

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
                                     <td class="text-left">Buying Price</td>
                                     <td class="text-left">Source</td>
                                     <td class="text-left"><?php echo 'Current '.$column_quantity; ?></td>
                                     <td class="text-left"><?php echo 'Total Procured Qty'; ?></td>
                                     <td class="text-left"><?php echo 'Rejected Qty'; ?></td>
									 <td class="text-right"><?php echo 'Total Qty'; ?></td>
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
                                    <td class="text-right"><?php echo $product['product_id']; ?></td>
                                    <td class="text-right"><?php echo $product['product_store_id']; ?></td>
                                    <td class="text-left"><?php echo $product['name']; ?></td>

                                    <!--<td class="text-left"><?php echo $product['model']; ?></td>-->

                                    <td class="text-left"><?php echo $product['unit']; ?></td>
                                    <!--<td class="text-left"><?php echo $product['store_name']; ?></td>->

                                    
                                    
                                    <td class="text-left"><?php foreach ($categories as $category) { ?>
                                        <?php if (in_array($category['category_id'], $product['category'])) { ?>
                                        <?php echo $category['name'];?><br>
                                        <?php } ?> <?php } ?></td>
                                    
                                    <td><?php echo $product['quantity'] ?></td>
                                    <td>

                                        <?php if (!(int)$product['special_price']){ ?>
                                            <?php echo $product['price']; ?>
                                        <?php } else { ?>
                                            <del>
                                            <?php echo $product['price'];?>
                                            </del>
                                            <?php echo "  " ,$product['special_price'] ?>
                                                <?php } ?>
                                    </td>
                                    <td class="text-left">
                                        <?php echo $product['status']; ?>
                                    </td>-->
                                    <td class="text-left">
                                         <input style="max-width: 75px !important; text-align: right;" name="buying_price" type="text" onkeypress="return validateFloatKeyPress(this, event);" class="buying_price" data-general_product_id="<?php echo $product['product_id']; ?>" data-name="<?php echo $product['name']; ?>" data-current-buying-price="<?php echo $product['buying_price']; ?>" id="buying_price_<?php echo $product['product_store_id'];?>" value="<?php echo $product['buying_price']; ?>">
                                    </td>
				    <td class="text-left">
                                        <input style="max-width: 75px !important;" name="source" type="text" class="source" id="source_<?php echo $product['product_store_id'];?>" data-current-source="<?php echo $product['source']; ?>" value="<?php echo $product['source']; ?>">
                                    </td>
                                    <td class="text-left">
                                        <?php //echo $product['quantity'] ?>
                                    <input style="max-width: 75px !important; text-align: right;" name="current_qty_in_warehouse" type="text" onkeypress="return validateFloatKeyPress(this, event);"  class="current_qty_in_warehouse" data-general_product_id="<?php echo $product['product_id']; ?>" data-product_store_id="<?php echo $product['product_store_id']; ?>"  data-name="<?php echo $product['name']; ?>" data-current-qty="<?php echo $product['quantity']; ?>"  id="current_qty_in_warehouse_<?php echo $product['product_store_id'];?>" value="<?php echo $product['quantity'] ?>">
                                    </td>
                                    <td class="text-left">
                                        <input style="max-width: 75px !important; text-align: right; " name="total_procured_qty" type="text" onkeypress="return validateFloatKeyPress(this, event);"  class="procured_qty" data-general_product_id="<?php echo $product['product_id']; ?>" data-product_store_id="<?php echo $product['product_store_id']; ?>" data-name="<?php echo $product['name']; ?>" data-current-qty="<?php echo $product['quantity']; ?>"  id="total_procured_qty_<?php echo $product['product_store_id'];?>" value="">
                                    </td>
                                    <td class="text-left">
                                        <input style="max-width: 75px !important; text-align: right; " name="rejected_qty" type="text" class="rejected_qty" onkeypress="return validateFloatKeyPress(this, event);" id="rejected_qty_<?php echo $product['product_store_id'];?>" data-product_store_id="<?php echo $product['product_store_id']; ?>" data-current-qty="<?php echo $product['quantity']; ?>" value="">
                                    </td>
				    <td class="text-left">
                                        <input style="max-width: 75px !important; text-align: right; " name="total_qty" disabled type="number"  id="total_qty_<?php echo $product['product_store_id'];?>" value="">
                                    </td>
                                    <td class="text-right"><!--<button type="button" onclick="ChangeProductInventory('<?php echo $product['product_store_id']; ?>');" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Save"><i class="fa fa-check-circle text-success"></i></button>-->
									<button type="button" onclick="getProductInventoryHistory('<?php echo $product['product_store_id']; ?>');" 
									data-toggle="modal" data-target="#<?php echo $product['product_store_id']; ?>historyModal"
								    title="" class="btn btn-default" data-original-title="History"><i class="fa fa-history text-success"></i></button>
									</td>
                                    
                                </tr>
									<div id="<?php echo $product['product_store_id']; ?>historyModal" class="modal fade" role="dialog">
									  <div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content" style="min-width: 680px !important;">
										  <div style="color: white;background-color: #008db9;" class="modal-header">
                                                                                      	<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title"><strong>Inventory History : <?php echo $product['name']; ?></strong></h4>
										  </div>
										  <div class="modal-body">
											
										  </div>
										  <div class="modal-footer">
                                                                                        <a class="btn btn-primary" href="<?php echo $inventory_history.'&filter_name='.$product['name']; ?>" role="button">VIEW ALL</a>
											<!--<button type="button" class="btn btn-primary" data-dismiss="modal">VIEW ALL</button>-->
										  </div>
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
    
     <!-- Modal -->
    <div id="inventoryupdateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div style="color: white;background-color: #008db9;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><strong>Inventory Update</strong></h4>
            </div>
            <div class="modal-body">
                <form id="inventory_update" name="inventory_update">
                    <div class="form-group required">
                        <label for="recipient-name" class="col-form-label">Product Name</label>
                        <input type="text" placeholder="Serach Product" class="form-control" data-vendor-product-id="" data-vendor-product-name="" id="new_vendor_product_name" name="new_vendor_product_name" style="max-width: 568px !important;">
                    </div>
                    <div class="form-group required">
                        <label for="recipient-name" class="col-form-label">Product UOM</label>
                        <select class="form-select" id="new_vendor_product_uom" name="new_vendor_product_uom" style="max-width: 568px !important;">
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="buying-price" class="col-form-label">Buying Price</label>
                                <input type="number" class="form-control" id="new_buying_price" name="new_buying_price" min="1" style="max-width: 568px !important;">
                            </div>   
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="source" class="col-form-label">Source</label>
                                <input placeholder="Search Supplier/Farmer" type="text" class="form-control" id="new_buying_source" data-new-buying-source-id="" name="new_buying_source" style="max-width: 568px !important;">
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="procured-quantity" class="col-form-label">Procured Quantity</label>
                                <input type="number" class="form-control" id="new_procured_quantity" name="new_procured_quantity" min="0.01" style="max-width: 568px !important;">
                            </div>   
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="source" class="col-form-label">Rejected Quantity</label>
                                <input type="number" class="form-control" id="new_rejected_quantity" name="new_rejected_quantity" min="0" value="0" style="max-width: 568px !important;">
                            </div>   
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-danger" style="display:none;">
                </div>
                <div class="alert alert-success" style="display:none;">
                </div>
                <div class="alert alert-success download" style="display:none;">
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_inventory_form" name="update_inventory_form">Update Inventory</button>
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

            var url = 'index.php?path=catalog/vendor_product/inventory&token=<?php echo $token; ?>';

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
                    url: 'index.php?path=catalog/product/getProductInventoryHistory&token=<?= $token ?>',
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

function ChangeProductInventory(product_store_id){
    return false;
    var data_array = [];
    $(".procured_qty").each(function() {
        var tempObj ={};
        var procured_qty = $(this).val();
        if(procured_qty != undefined){
            
            var vendor_product_id = $(this).attr('data-product_store_id');
            var general_product_id = $(this).attr('data-general_product_id');
            var product_name = $(this).attr('data-name');
	    var current_qty = $(this).attr('data-current-qty');
            var rejected_qty = $('#rejected_qty_'+vendor_product_id).val();
            
            var current_buying_price = $('#buying_price_'+vendor_product_id).val();
            var source = $('#source_'+vendor_product_id).val();
            
            tempObj.product_store_id = vendor_product_id;
            tempObj.product_id = general_product_id;
            tempObj.product_name = product_name;
            tempObj.procured_qty = procured_qty;
            tempObj.rejected_qty = rejected_qty;
	    tempObj.current_qty = current_qty;
            tempObj.current_buying_price = current_buying_price;
            tempObj.source = source;
            if(product_store_id==vendor_product_id)
            data_array.push(tempObj);
        }
    });
    console.log('data_array',data_array);
    console.log('data_array_length',data_array.length);
    if(data_array.length  > 0){
           $.ajax({
                    url: 'index.php?path=catalog/product/updateInventory&token=<?= $token ?>',
                    dataType: 'json',
                    data: {updated_products :data_array},
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

}


function ChangeInventory(){
    var data_array = [];
    $(".procured_qty").each(function() {
        var tempObj ={};
        var procured_qty = $(this).val();
        if(procured_qty != undefined){
            
            var vendor_product_id = $(this).attr('data-product_store_id');
            var general_product_id = $(this).attr('data-general_product_id');
            var product_name = $(this).attr('data-name');
	    var current_qty = $(this).attr('data-current-qty');
            var rejected_qty = $('#rejected_qty_'+vendor_product_id).val();
            tempObj.product_store_id = vendor_product_id;
            tempObj.product_id = general_product_id;
            tempObj.product_name = product_name;
            tempObj.procured_qty = procured_qty;
            tempObj.rejected_qty = rejected_qty;
	    tempObj.current_qty = current_qty;
            data_array.push(tempObj);
        }
    });
    console.log('data_array',data_array);
    console.log('data_array_length',data_array.length);
    if(data_array.length  > 0){
           $.ajax({
                    url: 'index.php?path=catalog/product/updateInventory&token=<?= $token ?>',
                    dataType: 'json',
                    data: {updated_products :data_array},
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

}

$('input.current_qty_in_warehouse').keyup(function(){
  	var current_qty_in_warehouse = $(this).val();
        var product_store_id = $(this).attr('data-product_store_id');
        $(this).attr('data-current-qty', current_qty_in_warehouse);
        $('#current_qty_in_warehouse_'+product_store_id).attr('data-current-qty', current_qty_in_warehouse);
        $('#total_procured_qty_'+product_store_id).attr('data-current-qty', current_qty_in_warehouse);
        $('#rejected_qty_'+product_store_id).attr('data-current-qty', current_qty_in_warehouse);
        
        var current_qty = $(this).attr('data-current-qty');  

	var procured_qty = $('#total_procured_qty_'+product_store_id).val();
	var vendor_product_id = $(this).attr('data-product_store_id');
        var rejected_qty = 0;
        if ($('#rejected_qty_'+vendor_product_id).val().length != 0){
        rejected_qty =$('#rejected_qty_'+vendor_product_id).val();
        }
     
	var total = parseFloat(current_qty) + parseFloat(procured_qty)+parseFloat(rejected_qty);
	$('#total_qty_'+vendor_product_id).val(total);
});


$('input.procured_qty').keyup(function(){

    var current_qty = $(this).attr('data-current-qty');  

    var procured_qty = $(this).val();
    var vendor_product_id = $(this).attr('data-product_store_id');
    var rejected_qty = 0;
    if ($('#rejected_qty_'+vendor_product_id).val().length != 0){
      rejected_qty =$('#rejected_qty_'+vendor_product_id).val();
    }
     
	var total = parseFloat(current_qty) + parseFloat(procured_qty)+parseFloat(rejected_qty);
	$('#total_qty_'+vendor_product_id).val(total);
});



$('input.rejected_qty').keyup(function(){
    var current_qty = $(this).attr('data-current-qty');
    var rejected_qty = $(this).val();
    
    var vendor_product_id = $(this).attr('data-product_store_id');
     
     var procured_qty = 0;
     if ($('#total_procured_qty_'+vendor_product_id).val().length != 0){
      procured_qty = $('#total_procured_qty_'+vendor_product_id).val();
    }

     if(parseFloat(procured_qty) < parseFloat(rejected_qty)){
	alert("Rejected quantity should be less than procured quantity!");
	$('#rejected_qty_'+vendor_product_id).val(0);
	rejected_qty = 0;
		
    }
	var total = parseFloat(current_qty) + ( parseFloat(procured_qty) - parseFloat(rejected_qty) );
	$('#total_qty_'+vendor_product_id).val(total);
});

//-->

function isNumberKey(txt,evt)
      {
          
          
            var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode == 46) {
        //Check if the text already contains the . character
        
        if (txt.value.indexOf('.') === -1  ) {
          return true;
        } else {
          return false;
        }
      } else {
         

        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
      }
      return true; 
      }


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
       
       function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}

function updateinventory() {
console.log($('input[name="selected[]"]:checked').length);
if($('input[name="selected[]"]:checked').length == 0) {
alert('Please select atleaset one product!');
return false;
}
var data_array = [];
$('input[name="selected[]"]:checked').each(function() {
var data_inventory = {};
var vendor_product_id = $(this).val();
var buying_price = $('#buying_price_'+vendor_product_id).val(); 
var source = $('#source_'+vendor_product_id).val();  
var current_qty = $('#current_qty_in_warehouse_'+vendor_product_id).val(); 
var total_procured_qty = $('#total_procured_qty_'+vendor_product_id).val(); 
var rejected_qty = $('#rejected_qty_'+vendor_product_id).val();  
var total_qty = $('#total_qty_'+vendor_product_id).val();   

var general_product_id = $('#buying_price_'+vendor_product_id).attr('data-general_product_id');
var product_name = $('#buying_price_'+vendor_product_id).attr('data-name');

data_inventory[vendor_product_id] = { 'vendor_product_id' : vendor_product_id, 'buying_price' : buying_price, 'source' : source, 'current_qty' : current_qty, 'total_procured_qty' : total_procured_qty, 'rejected_qty' : rejected_qty, 'total_qty' : total_qty, 'product_id' : general_product_id, 'product_name' : product_name};
console.log(data_inventory);
data_array.push(data_inventory);
console.log(data_array);
});

    if(data_array.length  > 0){
           $.ajax({
                    url: 'index.php?path=catalog/product/updateMultiInventory&token=<?= $token ?>',
                    dataType: 'json',
                    data: {updated_products :data_array},
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

}


$('input[name=\'new_vendor_product_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=dropdowns/dropdowns/product_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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
                $('#new_vendor_product_name').attr('data-vendor-product-id', selected_product_store_id);
                $('#new_vendor_product_name').attr('data-vendor-product-name', selected_product_store_id);
                $('input[name=\'new_vendor_product_name\']').val(item['label']);
                $.ajax({
                url: 'index.php?path=dropdowns/dropdowns/getVendorProductVariantsInfo&product_store_id='+selected_product_store_id+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    if(json != null) {
                    var option = '';
                    for (var i=0;i<json.length;i++){
                           option += '<option data-model="'+ json[i].model +'" data-product_id="'+ json[i].product_store_id +'" data-price="'+ json[i].price +'" data-special="'+ json[i].special_price +'" value="'+ json[i].unit + '">' + json[i].unit + '</option>';
                    }
                    console.log(option);
                    var $select = $('#new_vendor_product_uom');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    var $price_input = $('#new_vendor_product_price');
                    var special_price = json[0].price == null || json[0].price == 0 ? json[0].special_price : json[0].price;
                    $price_input.val(special_price.replace(/,/g, ""));
                    $('.selectpicker').selectpicker('refresh');
                }
            }
            });
            }
});                    

                    
$('button[id^=\'new_update_inventory\']').on('click', function (e) {
$("form[id^='inventory_update']")[0].reset();
$('#inventory_update')[0].reset();               
$('#inventoryupdateModal').modal('toggle');
$('#new_vendor_product_name').attr('data-vendor-product-id', "");
$('#new_vendor_product_name').attr('data-vendor-product-name', "");
$('input[name=\'new_buying_source\']').attr('data-new-buying-source-id', "");
$('.alert.alert-success').html('');
$('.alert.alert-success.download').html('');
$('.alert.alert-danger').html('');
$('.alert.alert-success.download').hide();
$('.alert.alert-success').hide();
$('.alert.alert-danger').hide();
});

$('button[id^=\'update_inventory_form\']').on('click', function (e) {
var vendor_product_uom = $('#new_vendor_product_uom').val();
var buying_price = $('#new_buying_price').val();
var buying_source = $('#new_buying_source').val();
var procured_quantity = $('#new_procured_quantity').val();
var rejected_quantity = $('#new_rejected_quantity').val();
var vendor_product_id = $('#new_vendor_product_name').attr('data-vendor-product-id');
var buying_source_id = $('input[name=\'new_buying_source\']').attr('data-new-buying-source-id');
$('.alert.alert-success').html('');
$('.alert.alert-success.download').html('');
$('.alert.alert-danger').html(''); 
$('.alert.alert-success.download').hide();
$('.alert.alert-success').hide();
$('.alert.alert-danger').hide();    
$.ajax({
        url: 'index.php?path=catalog/product/updateInventorysingle&token=<?= $token ?>',
        dataType: 'json',
        data: { 'vendor_product_uom' : vendor_product_uom, 'buying_price' : buying_price, 'buying_source' : buying_source, 'buying_source_id' : buying_source_id, 'procured_quantity' : procured_quantity, 'rejected_quantity' : rejected_quantity, 'vendor_product_id' : vendor_product_id  },
        async: true,
        beforeSend: function() {
        $('#update_inventory_form').prop('disabled', true);
        $('.alert.alert-success').html('<i class="fa fa-check-circle text-success">Please Wait Your Request Processing!</i>');
        },
        complete: function() {
        $('#update_inventory_form').prop('disabled', false);
        },
        success: function(json) {
        if (json) {
        if(json['status'] == '200') {
        $('.alert.alert-success').html('');
        $('.alert.alert-success.download').html('');
        $('.alert.alert-success').html('<i class="fa fa-check-circle text-success">'+json['message']+'</i>');
        $('.alert.alert-success.download').html('<button id="download_inventory_voucher" type="button" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Download Voucher" data-inventory-voucher="'+json['data']+'"><i class="fa fa-download text-success"></i></button>');
        $('.alert.alert-success').show();
        $('.alert.alert-success.download').show();
        console.log(json);
        }
        if(json['status'] == '400') {
        $('.alert.alert-danger').html('');
        $('.alert.alert-danger').html('<i class="fa fa-times-circle text-danger">'+json['message']+'</i>');
        $('.alert.alert-danger').show();    
        }
        }
        else {
        $('.alert.alert-danger').html('<i class="fa fa-times-circle text-danger">Please try again later!</i>');
        $('.alert.alert-danger').show();     
        }
        $('#update_inventory_form').prop('disabled', false);
        }
        });
});

$('input[name=\'new_buying_source\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=sale/supplier/autocompletesupplierfarmer&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['supplier_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'new_buying_source\']').val(item['label']);
                $('input[name=\'new_buying_source\']').attr('data-new-buying-source-id', item['value']);
            }
});

$(document).on('click', '#download_inventory_voucher', function(e){ 
e.preventDefault();
var inventory_voucher = $(this).attr("data-inventory-voucher");
console.log(inventory_voucher);
window.open(inventory_voucher, '_blank');
});
</script>
<style>
.bootstrap-select {
width : 100% !important;    
}
</style>

<?php echo $footer; ?>