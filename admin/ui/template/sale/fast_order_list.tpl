<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default" style="display:none;"><i class="fa fa-truck"></i></button>
                <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></button>

               <!-- <?php if (!$this->user->isVendor()): ?>
                        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <?php endif ?>   -->
            </div>
            <h1><?php echo $heading_title_fastorders; ?></h1>
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
                        <!-- <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?= $entry_city ?></label>
                                <input type="text" name="filter_city" value="<?php echo $filter_city; ?>" class="form-control" />
                            </div>
                            
                        </div> -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <select name="filter_order_status[]" id="input-order-status-uni" multiple="multiple">
                                    <?php if ($filter_order_status == '0') { ?>
                                    <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_missing; ?></option>
                                    <?php } ?>
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if (in_array($order_status['order_status_id'],explode(",", $filter_order_status))) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $column_delivery_method ?></label>
                                <input type="text" name="filter_delivery_method" value="<?php echo $filter_delivery_method; ?>" placeholder="<?php echo $column_delivery_method; ?>" id="input-name" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div>

                        </div>

                        <div class="col-sm-6">

                            <div class="form-group">
                                <label class="control-label" for="input-order-status">Day</label>
                                <select name="filter_order_day" id="input-order-status" class="form-control">
                                    <option value="today" <?php echo ($filter_order_day == 'today') ? 'selected="selected"':  "" ?> >Today</option>
                                    <option value="tomorrow" <?php echo ($filter_order_day == 'tomorrow') ? 'selected="selected"':  "" ?> >Tomorrow</option>
                                    
                                </select>
                            </div>

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-name"><?= $column_payment ?></label>
                                    <input type="text" name="filter_payment" value="<?php echo $filter_payment; ?>" placeholder="<?php echo $column_payment; ?>" id="input-name" class="form-control" />
                                </div>
                            <?php endif ?> 

                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button> 
                            <!-- <div class="form-group">
                                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div> -->

                        </div>
                        <!-- <div class="col-sm-4">

                            <?php if(!$this->user->isVendor()){ ?>  
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?= $text_vendor ?></label>
                                <input type="text" name="filter_vendor" value="<?php echo $filter_vendor; ?>" placeholder="<?php echo $text_vendor; ?>" id="input-model" class="form-control" />
                            </div>
                            <?php } ?>


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
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div> -->
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
                                    <td class="text-left">
                                        <?php if ($sort == 'customer') { ?>
                                        <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                                        <?php } ?>
                                    </td>
                                    <!-- <td class="text-left">
                                        <?php if ($sort == 'city') { ?>
                                        <a href="<?php echo $sort_city; ?>" class="<?php echo strtolower($order); ?>">City</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_city; ?>"><?= $column_city ?></a>
                                        <?php } ?>
                                    </td>
                                     -->
                                     <td class="text-left">
                                        
                                        Store
                                    </td>
                                    <td class="text-left">
                                        <?php echo $column_status; ?>
                                    </td>
                                    <td <?php echo $column_total; ?></td>
                                    <td class="text-left">Delivery Date</td>
                                    <td class="text-left"><?php if ($sort == 'delivery_timeslot') { ?>
                                        <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>">Delivery Timeslot</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_modified; ?>">Delivery Timeslot</a>
                                        <?php } ?></td>

                                        <?php if (!$this->user->isVendor()): ?>
                                        <td class="text-right"><?php echo $column_payment; ?></td>
                                     <?php endif ?>  

                                    
                                    <td class="text-right"><?php echo $column_delivery_method; ?></td>

                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($all_orders) { ?>

                                <?php foreach ($all_orders as $key => $orderLoop ) { ?>

                                    <tr>  <td colspan="11">  </td> </tr>
                                    <tr>  <td colspan="11"><center><h3 class="my-order-title label" style="background-color: #<?= $orderLoop['order_status_color']; ?>;display: block;line-height: 2;" id="order-status" ><?= $key?> </h3>   </center></td> </tr>
                                    <tr>  <td colspan="11">  </td> </tr>
                                    <?php foreach ($orderLoop['orders'] as $order) { ?>
                                    <tr>
                                        <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                                            <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                                            <?php } else { ?>
                                            <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                                            <?php } ?>
                                            <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
                                        </td>
                                        <td class="text-right"><?php echo $order['order_id']; ?></td>
                                        <td class="text-left"><?php echo $order['customer']; ?></td>
                                        <td class="text-left"><?php echo $order['store']; ?></td>
                                        <!-- <td class="text-left"><?php echo $order['status']; ?></td> -->
                                        <td class="text-left">

                                        <h3 class="my-order-title label" style="background-color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2;" id="order-status" ><?php echo $order['status']; ?></h3>
                                        </td>
                                        <td class="text-right"><?php echo $order['sub_total']; ?></td>
                                        <td class="text-left"><?php echo $order['delivery_date']; ?></td>
                                        <td class="text-left"><?php echo $order['delivery_timeslot']; ?></td>

                                        <?php if (!$this->user->isVendor()): ?>
                                            <td class="text-right"><?php echo $order['payment_method']; ?></td>
                                         <?php endif ?>  

                                        
                                        <td class="text-right"><?php echo $order['shipping_method']; ?></td>

                                        <td class="text-right">
                                            <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> 
                                            
                                            <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> 
                                             <?php if (!$this->user->isVendor()): ?>
                                            <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
                                            <?php endif; ?>
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
                <?php } ?> -->
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

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
            url = 'index.php?path=sale/fast_order&token=<?php echo $token; ?>';

            var filter_city = $('input[name=\'filter_city\']').val();

            if (filter_city) {
                url += '&filter_city=' + encodeURIComponent(filter_city);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
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

            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment) {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }



            var filter_order_status = $('select[name=\'filter_order_status[]\']').val();

            console.log(filter_order_status);

            if (filter_order_status != '*') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_order_day = $('select[name=\'filter_order_day\']').val();

            if (filter_order_day != '*') {
                url += '&filter_order_day=' + encodeURIComponent(filter_order_day);
            }


            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total) {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }

            var filter_vendor = $('input[name=\'filter_vendor\']').val();

            if (filter_vendor) {
                url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
            }

            var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

            if (filter_date_modified) {
                url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
            }

            location = url;
        });
        //--></script> 
    <script type="text/javascript"><!--
        
        $('input[name=\'filter_city\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/order/city_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['city_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_city\']').val(item['label']);
            }
        });
        
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <script type="text/javascript">

    $( "#input-order-status-uni" ).select2({
        theme: "classic",
         width: 'resolve'
    });

    <!--
  $('.date').datetimepicker({
            pickTime: false
        });

  setInterval(function() {
     location = location;
    }, 300 * 1000);
        //--></script></div>
<?php echo $footer; ?>