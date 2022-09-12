<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <!-- <div class="pull-right">
                <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default"><i class="fa fa-truck"></i></button>
                <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></button>

               
            </div> -->
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

                    <button type="button" onclick="excel();" data-toggle="tooltip" title="Download Excel" class="btn btn-success btn-sm"><i class="fa fa-download"></i></button>

                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>		
            </div>
            <div class="panel-body">
                <div class="well" style="display:none;">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-id">Order ID</label>
                                <input type="number" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="Order ID" id="input-order-id" class="form-control" />
                            </div>
                            <div class="form-group" hidden>
                                <label class="control-label" for="input-customer">Customer</label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="Customer" id="input-customer" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-transaction-id">Transaction ID</label>
                                <input type="text" name="filter_transaction_id" value="<?php echo $filter_transaction_id; ?>" placeholder="Transaction ID" id="input-order-id" class="form-control" />
                            </div>
                        
                                                        
                        </div>
                        <div class="col-sm-4">
                             
                        <div class="form-group" hidden>
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>    

                            <div class="form-group">
                                  <label class="control-label" for="input-user">User</label>
                                <input type="text" name="filter_user" value="<?php echo $filter_user; ?>" placeholder="Added by /User" id="input-user" class="form-control" />
                                <input type="hidden" name="filter_user_id" />
                          
                            </div> 

                        </div>



                        <div class="col-sm-4">

                            


                            <div class="form-group">
                                <label class="control-label" for="input-date-added">Transaction Date From</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="Date From" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-modified">Transaction Date To</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="Date To" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
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
                                    

                                    <td class="text-right">ID</td>
                                    <td class="text-right">Order ID</td>
                                    <td class="text-left">Transaction ID</td>
                                    <td class="text-right">Amount Received</td>                                    
                                    <td class="text-right">Partial Amount Paid</td>
                                    <td class="text-right">Amount Applied</td>
                                    <td class="text-right">Order Total</td>                             
                                    <td class="text-left">Transaction Date</td>                             
                                    <td class="text-left">IP</td>                             
                                    <td class="text-left">Added By</td>                             
                                    <td class="text-right">Credit ID</td>     
                                     
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders) { ?>
                                <?php foreach ($orders as $order) { ?>
                                <tr>
                                    
                                    <td class="text-right"><?php echo $order['id']; ?></td>
                                    <td class="text-right"><?php echo $order['order_id']; ?></td>
                                    <td class="text-left"><?php echo $order['transaction_id']; ?></td>
                                    <td class="text-right"><?php echo $order['amount_received']; ?></td>
                                    <td class="text-right"><?php echo $order['partial_amount']; ?></td>
                                    <td class="text-right"><?php echo $order['patial_amount_applied']; ?></td>
                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                    <td class="text-left"><?php echo $order['date_added']; ?></td>
                                    <td class="text-left"><?php echo $order['ip']; ?></td>
                                    <td class="text-left"><?php echo $order['user']; ?></td>
                                    <td class="text-right"><?php echo $order['credit_id']; ?></td>                             
                                     
                                    
                                    
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


    $('input[name=\'filter_user\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?path=report/payment_transaction_history/user_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
        $('input[name=\'filter_user\']').val(item['label']);
                    $('input[name=\'filter_user_id\']').val(item['value']);

    }
    });


  $('#button-filter').on('click', function () {
            url = 'index.php?path=report/payment_transaction_history&token=<?php echo $token; ?>';

            

             var filter_user = $('input[name=\'filter_user\']').val();
            var filter_user_id = $('input[name=\'filter_user_id\']').val();

            if (filter_user) {
                    url += '&filter_user=' + encodeURIComponent(filter_user);
                    url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
            }

            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

            var filter_transaction_id = $('input[name=\'filter_transaction_id\']').val();

            if (filter_transaction_id) {
                url += '&filter_transaction_id=' + encodeURIComponent(filter_transaction_id);
            }


            

 
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
  
           
            var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

            if (filter_date_modified) {
                url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
            }

    if(filter_user=='' && filter_order_id=='' && filter_transaction_id=='' )
    {
             if((filter_date_modified=='' || filter_date_added==''))
            {
                alert("Please select start & end date filters");
                return;
            }
           
          // alert(filter_date_modified);

             if(filter_date_modified!='' && filter_date_added!='')
            {
                
                const date1 = new Date(filter_date_added);
                const date2 = new Date(filter_date_modified);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                console.log(diffTime + " milliseconds");
                console.log(diffDays + " days");
                if(diffDays<0)
                {
                alert("Please select proper start & end date filters");
                                return;
                }
                if(diffDays>30)
                {
                    alert("Duration between start & end date filters should be less than 30 days");
                                return;
                }
            }
    }

            location = url;
        });
        //--></script> 
    <script type="text/javascript"><!--
        
        
        $('input[name=\'filter_customer\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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
            },
            'select': function (item) {
                $('input[name=\'filter_company\']').val(item['label']);
            }
        });


        //--></script> 
    
    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({

            pickTime: false,
            widgetParent: 'body'
        });

function excel() {
       url = 'index.php?path=report/payment_transaction_history/excel&token=<?php echo $token; ?>';
      
       var filter_user = $('input[name=\'filter_user\']').val();
            var filter_user_id = $('input[name=\'filter_user_id\']').val();

            if (filter_user) {
                    url += '&filter_user=' + encodeURIComponent(filter_user);
                    url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
            }

       var filter_order_id = $('input[name=\'filter_order_id\']').val();

        if (filter_order_id) {
            url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
        }

        var filter_transaction_id = $('input[name=\'filter_transaction_id\']').val();

        if (filter_transaction_id) {
            url += '&filter_transaction_id=' + encodeURIComponent(filter_transaction_id);
        }


       /* var filter_customer = $('input[name=\'filter_customer\']').val();

        if (filter_customer) {
            url += '&filter_customer=' + encodeURIComponent(filter_customer);
        }
var filter_company = $('input[name=\'filter_company\']').val();

        if (filter_company) {
            url += '&filter_company=' + encodeURIComponent(filter_company);
        }*/

        
        
       

        var filter_date_added = $('input[name=\'filter_date_added\']').val();

        if (filter_date_added) {
            url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
        }

           
            var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

            
        if (filter_date_modified) {
            url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
        }
   
       if(filter_user=='' && filter_order_id=='' && filter_transaction_id=='' )

    {
             if((filter_date_modified=='' || filter_date_added==''))
            {
                alert("Please select start & end date filters");
                return;
            }
           
          // alert(filter_date_modified);

             if(filter_date_modified!='' && filter_date_added!='')
            {
                
                const date1 = new Date(filter_date_added);
                const date2 = new Date(filter_date_modified);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                console.log(diffTime + " milliseconds");
                console.log(diffDays + " days");
                if(diffDays<0)
                {
                alert("Please select proper start & end date filters");
                                return;
                }
                if(diffDays>30)
                {
                    alert("Duration between start & end date filters should be less than 30 days");
                                return;
                }
            }
    }


    location = url;
}


    
        //--></script></div>
<?php echo $footer; ?>