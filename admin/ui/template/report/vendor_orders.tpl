<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
                <div class="pull-right">
                    <button type="button" onclick="excel();" data-toggle="tooltip" title="Download Excel" class="btn btn-success btn-sm"><i class="fa fa-download"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>		
            </div>
            <div class="panel-body">
                <div class="well" style="display:none;">
                    <div class="row">



                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            
                            <div class="form-group">
                              <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                              <input type="text" name="filter_store" value="<?php echo $filter_store; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                          </div>

                            <div class="form-group">
                            <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                            <select name="filter_order_status_id" class="form-control">
                              <option value="0"><?php echo $text_all_status; ?></option>
                              <?php foreach ($order_statuses as $order_status) { ?>
                              <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>                
                            
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                
                                <td class="right">
                                    <?= $column_orders ?>
                                   
                                </td>

                                <td class="left"><?php echo $column_delivery_date; ?></td>
                                
                                
                                <td class="right">
                                    
                                    <?= $column_products ?>
                                   
                                </td>
                                <td class="right">
                                    <?= $column_subtotal ?>
                                </td>
                               
                            </tr>
                        </thead>
                        <tbody>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="consolidated-order-sheet-timepicker" id="consolidated-order-sheet-timepicker" class="form-control">
                                    <option value="">Select Delivery Time Slot</option>
                                    <?php foreach ($time_slots as $time_slot) { ?>
                                    <option value="<?php echo $time_slot['timeslot']; ?>"><?php echo $time_slot['timeslot']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" style="display: inline; cursor: pointer;" type="text"
                                               id="consolidated-order-sheet-datepicker" size="30"
                                               placeholder="Choose Delivery Date" readonly="readonly">
                                        <span class="input-group-btn">
                                        <button id="download-consolidated-order-sheet" data-toggle="tooltip"
                                                title="Download Consolidated Order Sheet" class="btn btn-info">
                                            <i class="fa fa-file-excel-o"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" style="display: inline; cursor: pointer;"
                                               type="text" id="consolidated-calculation-datepicker"
                                               size="30" placeholder="Accounts(Choose Delivery Date)"
                                               readonly="readonly">
                                        <span class="input-group-btn">
                                        <button id="download-consolidated-calculation-sheet" data-toggle="tooltip"
                                                title="Download Consolidated Calculation Sheet" class="btn btn-info">
                                            <i class="fa fa-file-excel-o"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <?php if ($vendor_orders) { ?>
                            <?php foreach ($vendor_orders as $order) { ?>
                            <tr>
                                <td class="right"><?php echo $order['order_id']; ?></td>
                                <td class="left"><?php echo $order['delivery_date']; ?></td>
                                
                                <td class="right"><?php echo $order['products']; ?></td>            
                                <!--<td class="right"><?php echo $order['subtotal']; ?></td>-->
                                <td class="right"><?php echo $order['total']; ?>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--

    $(function(){
        $('input[name=\'vendor_name\']').autocomplete({
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
                    $('input[name=\'vendor_name\']').val(item['label']);
                    $('input[name=\'vendor_id\']').val(item['value']);
            }   
        });
    });

    $('input[name=\'filter_city\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=report/vendor/city_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['city_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_city\']').val(item['label']);
	}
    });

        $('#button-filter').on('click', function() {
            
            url = 'index.php?path=report/vendor_orders&token=<?php echo $token; ?>';

            var filter_city = $('input[name=\'filter_city\']').val();

            if (filter_city) {
                    url += '&filter_city=' + encodeURIComponent(filter_city);
            }
            
            var filter_vendor = $('input[name=\'vendor_name\']').val();
            var filter_vendor_id = $('input[name=\'vendor_id\']').val();

            if (filter_vendor) {
                    url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
                    url += '&filter_vendor_id=' + encodeURIComponent(filter_vendor_id);
            }

            var filter_date_start = $('input[name=\'filter_date_start\']').val();

            if (filter_date_start) {
                    url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
            }

            var filter_store = $('input[name=\'filter_store\']').val();

              if (filter_store) {
                  url += '&filter_store=' + encodeURIComponent(filter_store);
              }

            var filter_date_end = $('input[name=\'filter_date_end\']').val();

            if (filter_date_end) {
                    url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
            }

            var filter_group = $('select[name=\'filter_group\']').val();

            if (filter_group) {
                    url += '&filter_group=' + encodeURIComponent(filter_group);
            }

            var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

            if (filter_order_status_id != 0) {
                    url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
            }	
            
            location = url;
        });

        function excel() {
            
            url = 'index.php?path=report/vendor_orders/excel&token=<?php echo $token; ?>';

            var filter_city = $('input[name=\'filter_city\']').val();

            if (filter_city) {
                    url += '&filter_city=' + encodeURIComponent(filter_city);
            }
            
            var filter_vendor = $('input[name=\'vendor_name\']').val();
            var filter_vendor_id = $('input[name=\'vendor_id\']').val();

            if (filter_vendor) {
                    url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
                    url += '&filter_vendor_id=' + encodeURIComponent(filter_vendor_id);
            }

            var filter_store = $('input[name=\'filter_store\']').val();

              if (filter_store) {
                  url += '&filter_store=' + encodeURIComponent(filter_store);
              }

            var filter_date_start = $('input[name=\'filter_date_start\']').val();

            if (filter_date_start) {
                    url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
            }

            var filter_date_end = $('input[name=\'filter_date_end\']').val();

            if (filter_date_end) {
                    url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
            }

            var filter_group = $('select[name=\'filter_group\']').val();

            if (filter_group) {
                    url += '&filter_group=' + encodeURIComponent(filter_group);
            }

            var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

            if (filter_order_status_id != 0) {
                    url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
            }	
            
            location = url;
        }


        $('input[name=\'filter_store\']').autocomplete({
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
        $('input[name=\'filter_store\']').val(item['label']);
    }
    });

        //--></script> 
    
    <script type="text/javascript">
        $('.date').datetimepicker({
            pickTime: false,
              widgetParent: 'body'
        });

        $( "#consolidated-order-sheet-datepicker" ).datetimepicker({
            pickTime: false,
            format:'YYYY-MM-DD',
        });

        $("#download-consolidated-order-sheet").click(function(e) {
            e.preventDefault();
            const deliveryDate = $("#consolidated-order-sheet-datepicker").val();
            const deliveryTime = $("#consolidated-order-sheet-timepicker").val();

            if (!deliveryDate.length > 0) {
                alert("Please select delivery date");
            } else {
                const url = 'index.php?path=report/vendor_orders/consolidatedOrderSheet&token=<?php echo $token; ?>&filter_delivery_date=' + encodeURIComponent(deliveryDate) +'&filter_delivery_time_slot=' + encodeURIComponent(deliveryTime);
                location = url;
            }
        });

        $( "#consolidated-calculation-datepicker" ).datetimepicker({
            pickTime: false,
            format:'YYYY-MM-DD',
        });


        $("#download-consolidated-calculation-sheet").click(function(e) {

            e.preventDefault();
            const deliveryDate = $("#consolidated-calculation-datepicker").val();

            if (!deliveryDate.length > 0) {
                alert("Please select delivery date");
            } else {
                const url = 'index.php?path=sale/order/consolidatedCalculationSheet&token=<?php echo $token; ?>&filter_delivery_date=' + encodeURIComponent(deliveryDate);
                location = url;
            }
        });

    </script>
</div>

<?php echo $footer; ?>

<style>
body {
    position: inline;
}
</style>