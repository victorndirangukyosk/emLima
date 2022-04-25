<?php  echo $header; ?><?php echo $column_left;?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                
            
            
                <!--<button type="button" id="new_update_inventory" data-toggle="tooltip" title="Update Inventory" class="btn btn-primary"><i class="fa fa-plus"></i></button>-->

                <button type="button" style="background:#63d17d;" data-toggle="tooltip" title="Save Requisition" class="btn btn-default" onclick="updateinventory();">Save Requisition</button>
            
            </div>
            <h1>Products Requisition By Store</h1>
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
                <div class="pull-right"  style="display:none;">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>
            </div>
            <div class="panel-body">

                <div class="well" style="display:block;">
                    <div class="row" style="display:none;">
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

                                        <!--<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">-->


                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product-requisition">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="myTable">
                            <thead>
                                <tr>                                     

                                    <th class="text-left"><?php if ($sort == 'pd.name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?>

                                    </th>                                 


                                    <th><?= $column_unit ?> (Unit Of measure)</th>
                                    <th class="text-left"><?php echo 'Total Qty Requested'; ?></th>
                                     <th></th>
                                </tr>
                            </thead>
                            <tbody>

                             <tr class="productsAdd">
          <td colspan="3">
          </td>
          <td>
              <button type="button" onclick="add();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add Product"><i class="fa fa-plus-circle"></i></button>
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
                            <!--<div class="form-group required">
                                <label for="source" class="col-form-label">Source</label>
                                <input placeholder="Search Supplier/Farmer" type="text" class="form-control" id="new_buying_source" data-new-buying-source-id="" name="new_buying_source" style="max-width: 568px !important;">
                            </div>-->   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="procured-quantity" class="col-form-label">Total Received Quantity</label>
                                <input type="number" class="form-control" id="new_procured_quantity" name="new_procured_quantity" min="0.01" style="max-width: 568px !important;">
                            </div>   
                        </div>
                       <!-- <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="source" class="col-form-label">Rejected Quantity</label>
                                <input type="number" class="form-control" id="new_rejected_quantity" name="new_rejected_quantity" min="0" value="0" style="max-width: 568px !important;">
                            </div>   
                        </div>-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-danger" style="display:none;">
                </div>
                <div class="alert alert-success" style="display:none;">
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



function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}
function add() {
   
  noProduct = makeid();

  $html  = '<tr>';        
  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control" name="products['+noProduct+'][name]" value=""/>';
  
  $html += '</td>';
    
 
  
  $html += '<td class="text-right">';
  $html += '<input type="text"  disabled name="products['+noProduct+'][unit]"  class="form-control" value=""></input>';
  $html += '</td>';
 
  
  $html += '<td class="text-right">';
  $html += '<input type="number" min="1" step="1"  class="form-control changeTotal changeQuantity text-right" name="products['+noProduct+'][quantity]" value="1"/>';
  $html += '</td>';
 
 

  $html += '<td>';
  $html += '<button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>  <input type="hidden" name="products['+noProduct+'][product_id]" value=""/> <input type="hidden" name="products['+noProduct+'][product_store_id]" value=""/>';
  $html += '</td>';

  $html += '</tr>';
  
  $('.productsAdd').before($html);
    
  run(noProduct);
}



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

    function myFunction() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
        }       
    }
    }
 

  $('#button-filter').on('click', function() {

            var url = 'index.php?path=inventory/vendor_product_requisition/inventory&token=<?php echo $token; ?>';

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


        $('input[name=\'header_name\']').autocomplete({
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
                $('input[name=\'header_name\']').val(item['label']);
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
       
       function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}

    function updateinventory() {
       
              
         if($('#form-product-requisition').serialize().length<10) {
             alert('please enter data')
    return false;
  }


         if(!confirm('Are you sure ?')) {
    return false;
  }


   $.ajax({
     
                        url: 'index.php?path=inventory/vendor_product_requisition/updateMultiInventory&token=<?= $token ?>',
    type: 'post',
    dataType: 'json',
    data: $('#form-product-requisition').serialize(),
    beforeSend: function() {
          
    },
    complete: function() {
   
    },
    success: function(json) {
      console.log(json);
      if (json['status']) {
        alert('Requisition saved successfully'); 

        location = location;
      }     
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        alert('Requisition Failed'); 

      //location = location;
    }
  });

       
    }


$('input[name=\'new_vendor_product_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({ 
                    url: 'index.php?path=catalog/product/product_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
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
    $('.alert.alert-danger').html('');
    $('.alert.alert-success').hide();
    $('.alert.alert-danger').hide();
    });

    $('button[id^=\'update_inventory_form\']').on('click', function (e) {
    var vendor_product_uom = $('#new_vendor_product_uom').val();
    //alert($( "#new_vendor_product_uom option:selected" ).attr('data-product_id'));

    var buying_price = $('#new_buying_price').val();
    //var buying_source = $('#new_buying_source').val();
    var procured_quantity = $('#new_procured_quantity').val();
    //var rejected_quantity = $('#new_rejected_quantity').val();
    //var vendor_product_id = $('#new_vendor_product_name').attr('data-vendor-product-id');
    var vendor_product_id = $('#new_vendor_product_uom  option:selected').attr('data-product_id');
    //var buying_source_id = $('input[name=\'new_buying_source\']').attr('data-new-buying-source-id');
    $('.alert.alert-success').html('');
    $('.alert.alert-danger').html(''); 
    $('.alert.alert-success').hide();
    $('.alert.alert-danger').hide();    
    $.ajax({
            url: 'index.php?path=inventory/vendor_product_dispatch/updateInventorysingle&token=<?= $token ?>',
            dataType: 'json',
            data: { 'vendor_product_uom' : vendor_product_uom, 'buying_price' : buying_price,  'received_quantity' : total_procured_qty,  'vendor_product_id' : vendor_product_id  },
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
            $('.alert.alert-success').html('<i class="fa fa-check-circle text-success">'+json['message']+'</i>');
            $('.alert.alert-success').show();
                        location.reload();

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

 



  function run(noProduct) {
        
      
      $('input[name=\'products['+noProduct+'][name]\']').autocomplete({
        'source': function(request, response) {                

            console.log("sd source");
            console.log(request);
            console.log(response);

            $.ajax({
                url: 'index.php?path=inventory/vendor_product_requisition/product_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',     
                success: function(json) {
                    response($.map(json, function(item) {
                        console.log(item);
                        
                        return {
                            label: item['name']+' - '+item['unit'],
                            value: item['name'],
                            unit: item['unit'],
                            model: item['model'],
                            product_store_id: item['product_store_id'],
                            product_id: item['product_id'],
                        }
                   
                   
                    }));
 
                    console.log(json);
                    console.log(name);
                    console.log('sdsd');
                }
            });

            //$('.product_name').val(request.term);
        },
         'select': function(item) {
               
             $('input[name=\'products['+noProduct+'][unit]').val(item.unit);          
            $('input[name=\'products['+noProduct+'][name]').val(item.label);
            $('input[name=\'products['+noProduct+'][product_id]').val(item.product_id);
            $('input[name=\'products['+noProduct+'][product_store_id]').val(item.product_store_id);
            }
        
      });
    }
    

</script>
<style>
.bootstrap-select {
width : 100% !important;    
}
</style>

<?php echo $footer; ?>