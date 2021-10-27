<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <!-- <div class="pull-right">
                <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default"><i class="fa fa-truck"></i></button>
                <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></button>

               <?php if (!$this->user->isVendor()): ?>
                        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <?php endif ?>  
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
                                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-order-id"><?php echo $entry_transaction_id; ?></label>
                                <input type="text" name="filter_transaction_id" value="<?php echo $filter_transaction_id; ?>" placeholder="<?php echo $entry_transaction_id; ?>" id="input-order-id" class="form-control" />
                            </div>
<div class="form-group">
                                <label class="control-label" for="input-date-order">Order Date</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_order" value="<?php echo $filter_date_order; ?>" placeholder="Order Date" data-date-format="YYYY-MM-DD" id="input-date-order" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                                                        
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <select name="filter_order_status" id="input-order-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_order_status == '0') { ?>
                                    <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_missing; ?></option>
                                    <?php } ?>
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                           <!--  <div class="form-group">
                                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div> -->

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-name"><?= $column_payment ?></label>
                                    <input type="text" name="filter_payment" value="<?php echo $filter_payment; ?>" placeholder="<?php echo $column_payment; ?>" id="input-name" class="form-control" />
                                </div>
                            <?php endif ?> 
 <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>     

                        </div>



                        <div class="col-sm-4">

                            


                            <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-delivery">Delivery Date</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_delivery" value="<?php echo $filter_date_delivery; ?>" placeholder="Delivery Date" data-date-format="YYYY-MM-DD" id="input-date-delivery" class="form-control" />
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
                                    

                                    <td class="text-right"><?php echo $column_order_id; ?></td>
                                    <td class="text-left">Company Name</td>                                    
                                    <td class="text-left"><?php echo $column_customer; ?></td>
                                    <td class="text-right">Order Date</td>
                                    <td class="text-right">Delivery Date</td>                             

                                    <td class="text-right"><?php echo $column_transaction_id; ?></td>
                                     <td class="text-right"><?php echo 'Delivery'.'  ' . $column_status; ?></td>  

                                     

                                    <?php if (!$this->user->isVendor()): ?>
                                        <td class="text-right"><?php echo $column_payment; ?></td>
                                     <?php endif ?> 
                                     <td class="text-right">Amount</td>
                                     
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders) { ?>
                                <?php foreach ($orders as $order) { ?>
                                <tr>
                                    
                                    <td class="text-right"><?php echo $order['order_id']; ?></td>
                                    <td class="text-left"><?php echo $order['company']; ?></td>
                                    <td class="text-left"><?php echo $order['customer']; ?></td>
                                    <td class="text-right"><?php echo $order['date_added']; ?></td>
                                    <td class="text-right"><?php echo $order['delivery_date']; ?></td>
                                    
                                    
                                    <td class="text-right"><?php echo $order['transaction_id']; ?></td>
                                   <td class="text-right"><?php echo $order['status']; ?>

                                   <!-- <h3 class="my-order-title label" style="background-color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2;" id="order-status" ><?php echo $order['status']; ?></h3>-->
                                    </td> 

                                    <?php if (!$this->user->isVendor()): ?>
                                        <td class="text-right"><?php echo $order['payment_method']; ?></td>
                                     <?php endif ?>  

                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                    
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


    $('input[name=\'filter_vendor\']').autocomplete({
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
        $('input[name=\'filter_vendor\']').val(item['label']);
    }
    });


  $('#button-filter').on('click', function () {
            url = 'index.php?path=report/sale_transaction&token=<?php echo $token; ?>';

            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

            var filter_transaction_id = $('input[name=\'filter_transaction_id\']').val();

            if (filter_transaction_id) {
                url += '&filter_transaction_id=' + encodeURIComponent(filter_transaction_id);
            }


            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }


             var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }

            
            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment) {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }



            var filter_order_status = $('select[name=\'filter_order_status\']').val();

            if (filter_order_status != '*') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

           

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }


var filter_date_order = $('input[name=\'filter_date_order\']').val();

            if (filter_date_order) {
                url += '&filter_date_order=' + encodeURIComponent(filter_date_order);
            }


var filter_date_delivery = $('input[name=\'filter_date_delivery\']').val();

            if (filter_date_delivery) {
                url += '&filter_date_delivery=' + encodeURIComponent(filter_date_delivery);
            }

           
            var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

            if (filter_date_modified) {
                url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
            }

    if(filter_date_order=='' && filter_date_delivery=='' && filter_order_id=='' && filter_transaction_id=='')
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
    <script type="text/javascript"><!--
  $('input[name^=\'selected\']').on('change', function () {

            $('#button-shipping, #button-invoice').prop('disabled', true);

            var selected = $('input[name^=\'selected\']:checked');

            if (selected.length) {
                $('#button-invoice').prop('disabled', false);
            }

            for (i = 0; i < selected.length; i++) {
                if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
                    $('#button-shipping').prop('disabled', false);

                    break;
                }
            }

        });

        $('input[name^=\'selected\']:first').trigger('change');

        $('a[id^=\'button-delete\']').on('click', function (e) {
            e.preventDefault();

            if (confirm('<?php echo $text_confirm; ?>')) {
                location = $(this).attr('href');
            }
        });
        //--></script> 
    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false
        });

function excel() {
       url = 'index.php?path=report/sale_transaction/excel&token=<?php echo $token; ?>';
      
       var filter_order_id = $('input[name=\'filter_order_id\']').val();

        if (filter_order_id) {
            url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
        }

        var filter_transaction_id = $('input[name=\'filter_transaction_id\']').val();

        if (filter_transaction_id) {
            url += '&filter_transaction_id=' + encodeURIComponent(filter_transaction_id);
        }


        var filter_customer = $('input[name=\'filter_customer\']').val();

        if (filter_customer) {
            url += '&filter_customer=' + encodeURIComponent(filter_customer);
        }
var filter_company = $('input[name=\'filter_company\']').val();

        if (filter_company) {
            url += '&filter_company=' + encodeURIComponent(filter_company);
        }

        
        var filter_payment = $('input[name=\'filter_payment\']').val();

        if (filter_payment) {
            url += '&filter_payment=' + encodeURIComponent(filter_payment);
        }



        var filter_order_status = $('select[name=\'filter_order_status\']').val();

        if (filter_order_status != '*') {
            url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
        }

       

        var filter_date_added = $('input[name=\'filter_date_added\']').val();

        if (filter_date_added) {
            url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
        }

       
var filter_date_order = $('input[name=\'filter_date_order\']').val();

            if (filter_date_order) {
                url += '&filter_date_order=' + encodeURIComponent(filter_date_order);
            }


var filter_date_delivery = $('input[name=\'filter_date_delivery\']').val();

            if (filter_date_delivery) {
                url += '&filter_date_delivery=' + encodeURIComponent(filter_date_delivery);
            }

        var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

        if (filter_date_modified) {
            url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
        }
   
   if(filter_date_order=='' && filter_date_delivery=='' && filter_order_id=='' && filter_transaction_id=='')
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