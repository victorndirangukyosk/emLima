<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div class="page-header">
      <div class="container-fluid">
               <div class="pull-right">                  
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
        <div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>		
            </div>
            <div class="panel-body">

            	 
                <div class="well" style="display:none;max-height:310px !important;" >
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                            </div>

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                    <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                                </div>
                            <?php endif ?> 
                             
                         

                            
                        </div>
                        <div class="col-sm-4">
   
                               <div class="form-group">
                                <label class="control-label" for="input-order-fromto">Order From & To ID</label>
                                <div class="input-group">
                                <input  style ="width:48%" type="text" name="filter_order_from_id" value="<?php echo $filter_order_from_id; ?>" placeholder="Order ID From" id="input-order-from-id" class="form-control" />
                                <input  style ="width:48%;margin-left:3px;" type="text" name="filter_order_to_id" value="<?php echo $filter_order_to_id; ?>" placeholder="Order ID To" id="input-order-to-id" class="form-control" />
                                    
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-delivery-date">Delivery Date</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" placeholder="<?php echo $column_delivery_date; ?>" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

                        </div>



                        <div class="col-sm-4">

                           <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-delivery-date">Delivery Time Slot</label>
                                    <select name="filter_delivery_time_slot" id="input-delivery-time-slot" class="form-control">
                                    <option value="">Select <?php echo $column_delivery_time_slot; ?></option>
                                    <?php foreach ($time_slots as $time_slot) { ?>
                                    <?php if ($time_slot['timeslot'] == $filter_delivery_time_slot) { ?>
                                    <option value="<?php echo $time_slot['timeslot']; ?>" selected="selected"><?php echo $time_slot['timeslot']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $time_slot['timeslot']; ?>"><?php echo $time_slot['timeslot']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

                        </div>

                        
                    </div>

                </div>   
                                      
                           

                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"  name="selected[]"/>
                                    </td>
                                    <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                                        <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                                        <?php } ?></td>                                  
                           
                                   <!-- <td class="text-right">Product Vendor ID</td>-->
                                    <td class="text-left">Product Name</td>
                                    <td class="text-left">Unit</td>
                                    <td class="text-right">Missing Quantity</td>
                                   <!-- <td class="text-right">Price</td>
                                    <td class="text-right">Total</td>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($all_orders) { ?>
                                <?php foreach ($all_orders as $key => $orderLoop ) { ?>

                                 
                                <!--<tr class="header">  <td colspan="11"><center><h3 class="my-order-title label" style="background-color: #ff2a00a8;display: block;line-height: 2;" id="order-status-id" data-download-invoice="<?= $this->url->link('sale/order/missing_products_order_invoice', 'token=' . $this->session->data['token'] . '&order_id=' . $key, 'SSL')?>" ><?= $key?> </h3>   </center></td> </tr>-->
                                    
                        <?php foreach ($orderLoop['orders'] as $order) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($order['id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" />
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?php echo $order['order_id']; ?></td>

                                                                            
                                    <td class="text-left"><?php echo $order['name']; ?></td>
                                    <td class="text-left"><?php echo $order['unit']; ?></td>
                                    <td class="text-right"><?php echo $order['quantity_required']; ?></td>
                                        
                                </tr>
                                <?php } ?>
                                  <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
               <!-- <?php if ($all_orders) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?>-->
               

               </div>
               

               <div class="tab-pane" id="tab-missing-product">
				<table class="table table-bordered">
				   

				</table>
			  </div>
              </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">  

   $('#button-filter').on('click', function () {
            url = 'index.php?path=sale/order_product_missing_products&token=<?php echo $token; ?>';

             var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
 
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

              var filter_order_from_id = $('input[name=\'filter_order_from_id\']').val();

            if (filter_order_from_id) {
                url += '&filter_order_from_id=' + encodeURIComponent(filter_order_from_id);
            }


             var filter_order_to_id = $('input[name=\'filter_order_to_id\']').val();

            if (filter_order_to_id) {
                url += '&filter_order_to_id=' + encodeURIComponent(filter_order_to_id);
            }

            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }
            
            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date) {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }
            
            var filter_delivery_time_slot = $('select[name=\'filter_delivery_time_slot\']').val();

            if (filter_delivery_time_slot) {
                url += '&filter_delivery_time_slot=' + encodeURIComponent(filter_delivery_time_slot);
            }

            location = url;
        });
        </script>
    <script type="text/javascript">       
             
         $companyName="";
        $('input[name=\'filter_customer\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request)+'&filter_company=' +$companyName,
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['customer_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_customer\']').val(item['label']);
            }
        });


           $('input[name=\'filter_company\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletecompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['name']
                            }
                        }));

                        
                    }
                });
                $companyName="";
            },
            'select': function (item) {
                $('input[name=\'filter_company\']').val(item['label']);
                $('input[name=\'filter_customer\']').val('');
                $companyName=item['label'];
            }
        });
        
        </script> 
    <script type="text/javascript"><!--
  $('input[name^=\'selected\']').on('change', function () {

          var selected = $('input[name^=\'selected\']:checked'); 

        });

        $('input[name^=\'selected\']:first').trigger('change');
 </script>          
                            
     

<script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false
        });

    setInterval(function() {
     location = location;
    }, 300 * 1000); // 60 * 1000 milsec
     


         $('.header').click(function(){

$(this).nextUntil('tr.header').slideToggle(1000);
});

$(document).on('click', '#order-status-id', function(e){ 
e.preventDefault();
var download_invoice = $(this).attr("data-download-invoice");
console.log(download_invoice);
window.open(download_invoice, '_blank');
});
</script> 
<?php echo $footer; ?>

<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}

tr.header
{
    cursor:pointer;
}
</style>