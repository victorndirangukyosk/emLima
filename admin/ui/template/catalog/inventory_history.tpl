<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
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

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>

                            
                        </div>
                        
                        <div class="col-sm-3">
                         <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date" style="max-width: 321px;">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span></div>
                            </div>   
                        </div>
                        
                        <div class="col-sm-3">
                         <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added_end; ?></label>
                                <div class="input-group date" style="max-width: 321px;">
                                    <input type="text" name="filter_date_added_end" value="<?php echo $filter_date_added_end; ?>" placeholder="<?php echo $entry_date_added_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-added-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span></div>
                            </div>   
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group" hidden>
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_id" value="<?php echo $filter_store_id; ?>" placeholder="Store Name" id="input-store-name" class="form-control" />
                            </div>

                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>    
                            </div>
                        </div>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data" id="form-customer">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
                                    <td class="text-left"><?php if ($sort == 'name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $column_product_store_id; ?></td>
                                    <td class="text-left">Unit</td>


                                    <td class="text-left">Buying Price</td>
                                    <td class="text-left">Source</td>
                                    <td class="text-left"><?php echo $column_procured_qty; ?></td>
                                    <td class="text-left"><?php echo $column_rejected_qty; ?></td>
                                    <td class="text-left"><?php echo $column_prev_quantity; ?></td>
                                    <td class="text-left"><?php echo $column_updated_quantity; ?></td>
                                    <td class="text-left"><?php echo $column_updated_by; ?></td>
                                    <!--<td class="text-left"><?php echo $column_added_user_role; ?></td>-->
                                    <td class="text-left"><?php if ($sort == 'date_added') { ?>
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left">Action</td>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($history) { ?>
                                <?php foreach ($history as $histor) { ?>
                                <tr>
                                    <!--<td class="text-center"><?php if (in_array($histor['product_history_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $histor['product_history_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $histor['product_history_id']; ?>" />
                                        <?php } ?></td>-->
                                    <td class="text-left"><?php echo $histor['product_name']; ?></td>
                                    <td class="text-right"><?php echo $histor['product_store_id']; ?></td>
                                    <td class="text-right"><?php echo $histor['unit']; ?></td>
                                    
                                      <?php if($this->user->getGroupName() == 'Administrator') { ?>

                                   
                                    <td class="text-left">
                                         <input style="max-width: 75px !important; text-align: right;" name="buying_price" type="text" onkeypress="return validateFloatKeyPress(this, event);" class="buying_price" data-general_product_id="<?php echo $histor['product_id']; ?>" data-name="<?php echo $histor['name']; ?>" data-current-buying-price="<?php echo $histor['buying_price']; ?>" id="buying_price_<?php echo $histor['product_history_id'];?>" value="<?php echo $histor['buying_price']; ?>">
                                    </td>
				    <td class="text-left">
                                        <input style="max-width: 75px !important;" name="source" type="text" class="source" id="source_<?php echo $histor['product_history_id'];?>" data-current-source="<?php echo $histor['source']; ?>" value="<?php echo $histor['source']; ?>">
                                    </td>

                                     <td class="text-left">
                                        <input style="max-width: 75px !important; text-align: right; " name="total_procured_qty" type="text" onkeypress="return validateFloatKeyPress(this, event);"  class="procured_qty" data-general_product_id="<?php echo $histor['product_id']; ?>" data-product_store_id="<?php echo $histor['product_store_id']; ?>" data-name="<?php echo $histor['name']; ?>" data-current-qty="<?php echo $histor['quantity']; ?>"  id="total_procured_qty_<?php echo $histor['product_history_id'];?>" value="<?php echo $histor['procured_qty']; ?>">
                                     
                                    
                                    </td>
                                    <td class="text-left">
                                        <input style="max-width: 75px !important; text-align: right; " name="rejected_qty" type="text" class="rejected_qty" onkeypress="return validateFloatKeyPress(this, event);" id="rejected_qty_<?php echo $histor['product_history_id'];?>" data-product_store_id="<?php echo $histor['product_store_id']; ?>" data-current-qty="<?php echo $histor['quantity']; ?>" value="<?php echo $histor['rejected_qty']; ?>">
                                   
                                    </td>

                                    <td class="text-left">
                                        <?php //echo $product['quantity'] ?>
                                    <input style="max-width: 75px !important; text-align: right;" name="prev_qty_in_warehouse" type="text" onkeypress="return validateFloatKeyPress(this, event);"  class="prev_qty_in_warehouse" data-general_product_id="<?php echo $histor['product_id']; ?>" data-product_store_id="<?php echo $histor['product_store_id']; ?>"  data-name="<?php echo $histor['name']; ?>" data-current-qty="<?php echo $histor['quantity']; ?>"  id="prev_qty_in_warehouse_<?php echo $histor['product_history_id'];?>" value="<?php echo $histor['prev_qty']; ?>">
                                    
                                   
                                    </td>
                                   
				                 <td class="text-left">
                                        <input style="max-width: 75px !important; text-align: right; " name="current_qty"  type="number"  id="current_qty_<?php echo $histor['product_history_id'];?>" value="<?php echo $histor['current_qty']; ?>">
                                   
                                    </td>
                                     <td class="text-left"><?php echo $histor['added_user']; ?></td>
                                   <!-- <td class="text-left"><?php echo $histor['added_user_role']; ?></td>-->
                                    <td class="text-left"><?php echo $histor['date_added']; ?></td>
                                    <td class="text-left"><button id="download_inventory_voucher" type="button" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Download Voucher" data-inventory-voucher="<?php echo $histor['voucher']; ?>"><i class="fa fa-download text-success"></i></button></td>
                                    <td class="text-left"><button id="update_inventory" type="button" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Update Inventory" data-inventory-update="<?php echo $histor['product_history_id']; ?>"><i class="fa fa-save text-success"></i></button></td>

                            <?php }else{ ?>
                                    <td class="text-right"><?php echo $histor['buying_price']; ?></td>
                                    <td class="text-left"><?php echo $histor['source']; ?></td>
                                    <td class="text-right"><?php echo $histor['procured_qty']; ?></td>
                                    <td class="text-right"><?php echo $histor['rejected_qty']; ?></td>
                                    <td class="text-right"><?php echo $histor['prev_qty']; ?></td>
                                    <td class="text-right"><?php echo $histor['current_qty']; ?></td>
                                    <td class="text-left"><?php echo $histor['added_user']; ?></td>
                                   <!-- <td class="text-left"><?php echo $histor['added_user_role']; ?></td>-->
                                    <td class="text-left"><?php echo $histor['date_added']; ?></td>
                                    <td class="text-left"><button id="download_inventory_voucher" type="button" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Download Voucher" data-inventory-voucher="<?php echo $histor['voucher']; ?>"><i class="fa fa-download text-success"></i></button></td>
                                 <?php } ?>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
  $('#button-filter').on('click', function () {
            url = 'index.php?path=catalog/vendor_product/InventoryHistory&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();
            
            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();
            
            if (filter_date_added_end) {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }
            
            var filter_store_id = $('input[name=\'filter_store_id\']').val();

            if (filter_store_id) {
                url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
            }

            location = url;
        });
        //--></script> 
    <script type="text/javascript"><!--
        $companyName = "";
        $('input[name=\'filter_name\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
				value: item['product_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_name\']').val(item['label']);
            }
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
        //--></script> 
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false,
            widgetParent: 'body'
        });

        function excel() {

            url = 'index.php?path=catalog/vendor_product/InventoryHistoryexcel&token=<?php echo $token; ?>';
            
            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();
            
            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();
            
            if (filter_date_added_end) {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }
            
            var filter_store_id = $('input[name=\'filter_store_id\']').val();

            if (filter_store_id) {
                url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
            }
            
            location = url;
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


        //-->
$(document).on('click', '#download_inventory_voucher', function(e){ 
e.preventDefault();
var inventory_voucher = $(this).attr("data-inventory-voucher");
console.log(inventory_voucher);
window.open(inventory_voucher, '_blank');
});


$(document).on('click', '#update_inventory', function(e){ 
e.preventDefault();
var update_inventory = $(this).attr("data-inventory-update");
var buy_price=encodeURIComponent($('input[id=\'buying_price_'+update_inventory+'\']').val());
var src=encodeURIComponent($('input[id=\'source_'+update_inventory+'\']').val());
var p_qty=encodeURIComponent($('input[id=\'total_procured_qty_'+update_inventory+'\']').val());
var r_qty=encodeURIComponent($('input[id=\'rejected_qty_'+update_inventory+'\']').val());
var prev_qty=encodeURIComponent($('input[id=\'prev_qty_in_warehouse_'+update_inventory+'\']').val());
var updated_qty=encodeURIComponent($('input[id=\'current_qty_'+update_inventory+'\']').val());

   $.ajax({
                    url: 'index.php?path=catalog/vendor_product/updateInventoryHistory&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data:{ product_history_id : update_inventory, procured_qty : p_qty, rejected_qty : r_qty,prev_qty : prev_qty,current_qty : updated_qty,buying_price : buy_price, source    : src },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                          alert('Saved Successfully');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                           alert('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                alert('Please try again');
                                    return false;
                                }
                });
});

</script>
</div>
<?php echo $footer; ?> 

<style>
    body {
        position: relative;
    }
</style>

