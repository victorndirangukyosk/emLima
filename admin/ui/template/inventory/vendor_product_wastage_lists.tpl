<?php echo $header; ?><?php echo $column_left;?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">               
            
                <button type="button" id="add_wastage" data-toggle="tooltip" title="Add Wastage" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                <!--<?php if($this->user->getGroupName() == 'Administrator') { ?>-->
                          
            <!--<?php } ?>-->
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
        <!--<?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>-->
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
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_from; ?></label>
                                <input type="text" name="filter_product_id_from" value="<?php echo $filter_product_id_from; ?>" placeholder="<?php echo $entry_product_id_from; ?>" id="input-model" class="form-control" />
                            </div>

                           


                             <div class="form-group">

                             <label class="control-label" for="input-date-added">Date Added / From</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="Date Added" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                                

                               
                            </div>

                        </div>
                        <?php if (!$is_vendor): ?>
                            
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_id" value="<?php echo $filter_store_id; ?>" placeholder="Store Name" id="input-store-name" class="form-control" />
                            </div>
                           

                            <div class="form-group">

                              <label class="control-label" for="input-model"><?php echo $entry_product_id_to; ?></label>
                                <input type="text" name="filter_product_id_to" value="<?php echo $filter_product_id_to; ?>" placeholder="<?php echo $entry_product_id_to; ?>" id="input-model" class="form-control" />
                            

                              </div>

                               <div class="form-group">

                             <label class="control-label" for="input-date-added-to">Date Added To</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added_to" value="<?php echo $filter_date_added_to; ?>" placeholder="Date Added To" data-date-format="YYYY-MM-DD" id="input-date-added-to" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                                

                               
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
                             <div class="form-group">
                                <label class="control-label" for="input-model"><?= $entry_vendor_name ?></label>
                                <input type="text" name="filter_vendor_name" value="<?php echo $filter_vendor_name; ?>" placeholder="Vendor Name" id="input-model" class="form-control" />
                            </div>
                            </div>


 <div class="form-group" style="margin-top:30px;">
                  <label hidden><input type="checkbox" name="filter_group_by_date[]" value="<?php echo $filter_group_by_date; ?>" <?php if($filter_group_by_date == 1) { ?> checked="" <?php } ?>> Group By Date </label>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
             
              </div>
                            <div class="form-group">


                               </div>


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
                                    

                                    <td class="text-left"><?php if ($sort == 'p.product_id') { ?>
                                        <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_product_id; ?>"><?php echo $column_product_id; ?></a>
                                        <?php } ?></td>


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

                                     
                                     <td class="text-right">Wastage</td>
                                     <td class="text-left">Date Added</td>
                                     <td class="text-left">Added By</td>
                                     <td class="text-right">Cumulative Wastage</td>

                                    
                                     
                                    
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


                                    <td class="text-left"><?php echo $product['unit']; ?></td>
                                     
                                    <td class="text-right"><?php echo $product['wastage_qty']; ?>
                                    </td>
                                     <td class="text-left"><?php echo $product['date_added']; ?>
                                    </td>
                                     <td class="text-left"><?php echo $product['added_by_user']; ?>
                                    </td>

                                     <td class="text-right"><?php echo $product['cumulative_wastage']; ?>
                                    </td>
				    
				 
                                    
                                </tr>
									 
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
    <div id="inventorywastageupdateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div style="color: white;background-color: #008db9;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><strong>Add Wastage</strong></h4>
            </div>
            <div class="modal-body">
                <form id="inventory_wastage_update" name="inventory_wastage_update">
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
                                
                            </div>   
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group required">
                                   </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="wastage-quantity" class="col-form-label">Wastage Quantity</label>
                                <input type="number" class="form-control" id="wastage_quantity" name="wastage_quantity" min="0.01" style="max-width: 568px !important;width:568px;">
                            </div>   
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group required">
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
                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_inventory_wastage_form" name="update_inventory_wastage_form">Update Inventory</button>
            </div>
        </div>

    </div>
</div>  
    
    
    <script type="text/javascript"><!--
        
 
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

 

  $('#button-filter').on('click', function() {

            var url = 'index.php?path=inventory/inventory_wastage&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

             var filter_group_by_date = 0;
            
            if ($('input[name=\'filter_group_by_date[]\']').is(':checked')) {
                filter_group_by_date = 1;
                url += '&filter_group_by_date=' + encodeURIComponent(filter_group_by_date);
            } else {
            url += '&filter_group_by_date=' + encodeURIComponent(filter_group_by_date);    
            }

            var filter_vendor_name = $('input[name=\'filter_vendor_name\']').val();

            if (filter_vendor_name) {
                url += '&filter_vendor_name=' + encodeURIComponent(filter_vendor_name);
            }
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }


              var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();

            if (filter_date_added_to) {
                url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
            }
            

           

            var filter_product_id_to = $('input[name=\'filter_product_id_to\']').val();

            if (filter_product_id_to) {
                url += '&filter_product_id_to=' + encodeURIComponent(filter_product_id_to);
            }

            var filter_product_id_from = $('input[name=\'filter_product_id_from\']').val();

            if (filter_product_id_from) {
                url += '&filter_product_id_from=' + encodeURIComponent(filter_product_id_from);
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

        
  //--></script></div>

<script type="text/javascript"><!--

 

        $('.date').datetimepicker({
	pickTime: false,  widgetParent: 'body'
});

    setInterval(function() {
     location = location;
    }, 300 * 1000);
 
//--></script>
<script type="text/javascript"><!--
 
  
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
       
    

$('input[name=\'new_vendor_product_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/product_autocomplete_all&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                //label: item['name']+' '+item['unit'],
                                value: item['product_store_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                console.log(item['value']);
                var selected_product_store_id = item['value'];                
                var selected_product_store_name = item['label'];
                $('#new_vendor_product_name').attr('data-vendor-product-id', selected_product_store_id);
                $('#new_vendor_product_name').attr('data-vendor-product-name', selected_product_store_name);
                $('input[name=\'new_vendor_product_name\']').val(item['label']);
                $.ajax({
                url: 'index.php?path=catalog/product/getVendorProductVariantsInfo&product_store_id='+selected_product_store_id+'&token=<?php echo $token; ?>',
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
                    
                    $('.selectpicker').selectpicker('refresh');
                }
            }
            });
            }
});                    

                    
$('button[id^=\'add_wastage\']').on('click', function (e) {
$("form[id^='inventory_wastage_update']")[0].reset();
$('#inventory_wastage_update')[0].reset();               
$('#inventorywastageupdateModal').modal('toggle');
$('#new_vendor_product_name').attr('data-vendor-product-id', "");
$('#new_vendor_product_name').attr('data-vendor-product-name', "");
$('.alert.alert-success').html('');
$('.alert.alert-danger').html('');
$('.alert.alert-success').hide();
$('.alert.alert-danger').hide();
});

$('button[id^=\'update_inventory_wastage_form\']').on('click', function (e) {
var vendor_product_uom = $('#new_vendor_product_uom').val();
var wastage_quantity = $('#wastage_quantity').val();
//var vendor_product_id = $('#new_vendor_product_name').attr('data-vendor-product-id');
//above ID is not correct to consider, as changing uom dropdown will not update product ID 
var vendor_product_name = $('#new_vendor_product_name').attr('data-vendor-product-name');

$('.alert.alert-success').html('');
$('.alert.alert-danger').html(''); 
$('.alert.alert-success').hide();
$('.alert.alert-danger').hide();    
$.ajax({
        url: 'index.php?path=inventory/inventory_wastage/updateInventoryWastage&token=<?= $token ?>',
        dataType: 'json',
        data: { 'vendor_product_uom' : vendor_product_uom,  'wastage_quantity' : wastage_quantity, 'vendor_product_name' : vendor_product_name  },
        async: true,
        beforeSend: function() {
        $('#update_inventory_wastage_form').prop('disabled', true);
        $('.alert.alert-success').html('<i class="fa fa-check-circle text-success">Please Wait Your Request Processing!</i>');
        },
        complete: function() {
        $('#update_inventory_wastage_form').prop('disabled', false);
        },
        success: function(json) {
        if (json) {
        if(json['status'] == '200') {
        $('.alert.alert-success').html('');
        $('.alert.alert-success').html('<i class="fa fa-check-circle text-success">'+json['message']+'</i>');
        $('.alert.alert-success').show();
        console.log(json);
                    location.reload();

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
        $('#update_inventory_wastage_form').prop('disabled', false);
        }
        });
});
 

 
</script>
<style>
.bootstrap-select {
width : 100% !important;    
}
</style>

<?php echo $footer; ?>