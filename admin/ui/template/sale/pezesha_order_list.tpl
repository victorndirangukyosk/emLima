<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" onclick="downloadOrders();" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Pezesha Orders Excel"><i class="fa fa-download"></i></button>
            </div>
            <h1>Pezesha Received</h1>
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

                            
                             <div class="form-group" >
                                    <label class="control-label" for="input-company">Company</label>
                                    <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company" id="input-customer" class="form-control" />
                                </div>
                             
                         
                          <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

           

                            
                        </div>
                        <div class="col-sm-4">
                            
                            
                               
                                <div class="form-group">
                                <label class="control-label" for="input-company-parent">Parent Company</label>
                                <input type="text" name="filter_company_parent" value="<?php if($filter_company_parent != NULL && $filter_company_parent_id != NULL) { echo $filter_company_parent; } ?>" placeholder="Parent Company" id="input-company-parent" class="form-control" data-parent-company-id="<?php if($filter_company_parent != NULL && $filter_company_parent_id != NULL) { echo $filter_company_parent_id; } ?>" />
                          
                            </div>

 <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group" >
                                    <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                    <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                                </div>
                            <?php endif ?> 

                            
                            <div class="form-group">    
                                <label class="control-label" for="input-date-added-end"><?php echo $entry_date_added_end; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added_end" value="<?php echo $filter_date_added_end; ?>" placeholder="<?php echo $entry_date_added_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

                        </div>



                        <div class="col-sm-4">

                          

                            
                             <div class="form-group">
                                <label class="control-label" for="input-delivery-date">Delivery Date</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" placeholder="<?php echo $column_delivery_date; ?>" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            
                           
                            
                            <div class="form-group">
                            <button type="button" id="button-filter" class="btn btn-primary pull-left" style="margin-top:20px;"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                            
                        </div>

                        
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">
                      <br>

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"  name="selected[]"/>
                                    </td>
                                    <td class="text-left">Customer ID</td>  
                                     <td class="text-left">
                                            <?php if ($sort == 'customer') { ?>
                                            <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>">Customer Name</a>
                                            <?php } else { ?>
                                            <a href="<?php echo $sort_customer; ?>">Customer Name</a>
                                            <?php } ?>
                                        </td>                                
                                    <td class="text-left">Company Name</td>

                                                                          
                                       

                                        <td class="text-left"><?php if ($sort == 'o.order_id') { ?>
                                        <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                                        <?php } ?></td>

                                    <td class="text-left">Order Date</td>

                                    <td class="text-left">Delivery Date</td>

                                    <td class="text-left"><?php if ($sort == 'o.total') { ?>
                                        <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>">Order Value</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_total; ?>">Order Value</a>
                                        <?php } ?></td>
                                    <td class="text-left">Amount Paid</td>

                                    <td class="text-left">
                                        <?php if ($sort == 'o.created_at') { ?>
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Payment Received</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>">Date of Payment</a>
                                        <?php } ?>
                                    </td>

                                    <td class="text-left">Payment Method</td>
                                    <td class="text-left">Paid To</td>
                                    <td class="text-left">Payment Transaction ID</td>
                                        

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders) { ?>
                                <?php foreach ($orders as $order) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
                                        <input type="hidden" name="order_status1[]" value="<?php echo $order['order_status_id']; ?>" />
                                        <input type="hidden" name="order_delivery_ids[]" value="<?php echo $order['delivery_id']; ?>" />
                                    </td>
                                    <td class="text-left"><?php echo $order['customer_id']; ?></td>

                                        <td class="text-left" style="width:100px">
                                            <?php echo $order['customer']; ?>  <br/>
                                        </td>
                                        <td class="text-left" style="width:100px">
                                            <?php echo $order['company_name']; ?>  <br/>
                                        </td>

                                <td class="text-left"><?php echo $order['order_prefix'].''.$order['order_id']; ?></td>

                                    <td class="text-left"><?php echo $order['date_added']; ?></td>
                                    <td class="text-left"><?php echo $order['delivery_date']; ?></td>
                                    
                                    <td class="text-left"><?php echo $order['total']; ?></td>
                                    <td class="text-left"><?php echo $order['amount_paid']; ?></td>
                                    <td class="text-left"><?php echo $order['created_at']; ?></td>
                                    <td class="text-left"><?php echo $order['payment_method']; ?></td>
                                    <td class="text-left"><?php echo $order['paid_to']; ?></td>
                                    <td class="text-left"><?php echo $order['mpesa_reference']; ?></td>
                                </tr>
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
                <?php if ($orders) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
    var selected_order_ids=[];        
    $('input[name=\'filter_store_name\']').autocomplete({
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
        $('input[name=\'filter_store_name\']').val(item['label']);
    }
    });


    

  

   $('#button-filter').on('click', function () {
            url = 'index.php?path=sale/pezesha_receivables&token=<?php echo $token; ?>';

             var filter_company_parent = $('input[name=\'filter_company_parent\']').val();

            if (filter_company_parent) {
                url += '&filter_company_parent=' + encodeURIComponent(filter_company_parent);
            }
  

            var filter_company_parent_id = $('input[name=\'filter_company_parent\']').attr("data-parent-company-id");
            //alert(filter_company_parent_id);
            
            if (filter_company_parent_id) {
                url += '&filter_company_parent_id=' + encodeURIComponent(filter_company_parent_id);
            }


            var filter_city = $('input[name=\'filter_city\']').val();

            if (filter_city) {
                url += '&filter_city=' + encodeURIComponent(filter_city);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

             
 

            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company != '*' && filter_company != '') {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
            
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer != '*' && filter_customer != '') {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }

            

            var filter_store_name = $('input[name=\'filter_store_name\']').val();

            if (filter_store_name) {
                url += '&filter_store_name=' + encodeURIComponent(filter_store_name);
            }

            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method) {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }
            
            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date) {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }
            
            var filter_delivery_time_slot = $('select[name=\'filter_delivery_time_slot\']').val();

            if (filter_delivery_time_slot) {
                url += '&filter_delivery_time_slot=' + encodeURIComponent(filter_delivery_time_slot);
            }

            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment) {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }



                         


            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total) {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end) {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }

             

            var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

            if (filter_date_modified) {
                url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
            }

            location = url;
        });
        //--></script>
    <script type="text/javascript"><!--
        
        
         $companyName="";
        $('input[name=\'filter_customer\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletebyCompany_pezesha&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request)+'&filter_company_parent=' +$companyName,
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
                    url: 'index.php?path=sale/customer/autocompletecompany_pezesha_all&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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


           $('input[name=\'filter_company_parent\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletecompany_pezesha&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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

                console.log(item);
                $('input[name=\'filter_company_parent\']').val(item['label']);
                $('input[name=\'filter_company_parent\']').attr("data-parent-company-id", item['value']);

                $('input[name=\'filter_customer\']').val('');
                
            }
        });
        
        //--></script> 
    <script type="text/javascript"><!--
     $('input[name^=\'selected\']').on('change', function () {           
            
            var selected = $('input[name^=\'selected\']:checked');
 
            
            
        selected_order_ids = [];       
        $('input[name="selected[]"]:checked').each(function() {            
        selected_order_ids.push($(this).val());
       
        });
        });

        $('input[name^=\'selected\']:first').trigger('change');

        $('a[id^=\'button-delete\']').on('click', function (e) {
            e.preventDefault();

            if (confirm('<?php echo $text_confirm; ?>')) {
                location = $(this).attr('href');
            }
        });
        //--></script> 



 
<script  type="text/javascript">  


 $('input[name^=\'selectedproducts\']').on('change', function () {            
            var selectedproducts = $('input[name^=\'selectedproducts\']:checked');  

        });
        $('input[name^=\'selectedproducts\']:first').trigger('change');
 
   
</script>




    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false,
     widgetParent: 'body'

        });

    setInterval(function() {
     location = location;
    }, 300 * 1000); // 60 * 1000 milsec
    
        //-->
    
  
 
  
   
function downloadOrders() {


             
                url = 'index.php?path=report/vendor_orders/downloadpezeshaordersreceivables&token=<?php echo $token; ?>';
              var filter_order_status = $('select[name=\'filter_order_status\']').val();

            

            if (filter_order_status != '*' && filter_order_status != '' && filter_order_status != 'undefined') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date != '*' && filter_delivery_date != '') {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }
            
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company != '*' && filter_company != '') {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }


             var filter_company_parent = $('input[name=\'filter_company_parent\']').val();

            if (filter_company_parent) {
                url += '&filter_company_parent=' + encodeURIComponent(filter_company_parent);
            }
  

            var filter_company_parent_id = $('input[name=\'filter_company_parent\']').attr("data-parent-company-id");
            //alert(filter_company_parent_id);
            
            if (filter_company_parent_id) {
                url += '&filter_company_parent_id=' + encodeURIComponent(filter_company_parent_id);
            }

            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company != '*' && filter_company != '') {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
            
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer != '*' && filter_customer != '') {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }
            
            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total != '*' && filter_total != '') {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }
            
            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method != '*' && filter_delivery_method != '') {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }
            
            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment != '*' && filter_payment != '') {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }
            
            var filter_order_type = $('select[name=\'filter_order_type\']').val();

            if (filter_order_type != '*' && filter_order_type != '') {
                url += '&filter_order_type=' + encodeURIComponent(filter_order_type);
            }
            
            
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added != '*' && filter_date_added != '') {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end != '*' && filter_date_added_end != '') {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id != '*' && filter_order_id != '') {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            
            
            
            var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i){
            return n.value;
            }).join(',');
            console.log(selected_order_id);
            
            if (selected_order_id != '') {
                url += '&selected_order_id=' + encodeURIComponent(selected_order_id);
            }
            
 


            location = url;
            
}
</script></div>
<?php echo $footer; ?>

<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}
</style>