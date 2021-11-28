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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
          <div class="pull-right">
                    <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="Download Excel"><i class="fa fa-download"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
          </div>
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-group"><?php echo $entry_group; ?></label>
                <select name="filter_group" id="input-group" class="form-control">
                  <?php foreach ($groups as $group) { ?>
                  <?php if ($group['value'] == $filter_group) { ?>
                  <option value="<?php echo $group['value']; ?>" selected="selected"><?php echo $group['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $group['value']; ?>"><?php echo $group['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_order_status_id" id="input-status" class="form-control">
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
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label"><?= $entry_city ?></label>  
                    <input name="filter_city" class="form-control" value="<?= $filter_city ?>" />
                </div>
                <div class="form-group">
                    <label class="control-label"><?= $entry_customer ?></label>  
                    <input name="filter_customer" class="form-control" value="<?= $filter_customer ?>" />
                </div>
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_date_start; ?></td>
                <td class="text-left"><?php echo $column_date_end; ?></td>
                <td class="text-right"><?php echo $column_orders; ?></td>
                <td class="text-right"><?php echo $column_products; ?></td>
                <td class="text-right"><?php echo $column_tax; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
                <td class="text-center"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['date_start']; ?></td>
                <td class="text-left"><?php echo $order['date_end']; ?></td>
                <td class="text-right"><?php echo $order['orders']; ?></td>
                <td class="text-right"><?php echo $order['products']; ?></td>
                <td class="text-right"><?php echo $order['tax']; ?></td>
                <td class="text-right"><?php echo $order['total']; ?></td>
                 <td class="text-center"><a class="download" id="download-order-products"  data-toggle="tooltip" order_startdate="<?php echo $order['date_starto']; ?>" order_enddate="<?php echo $order['date_endo']; ?>" filter_customer="<?= $filter_customer ?>" filter_city="<?= $filter_city ?>" filter_order_status_id="<?= $filter_order_status_id ?>"  title="Download Individual Order Summary" class="btn btn-info"><i  style="cursor: pointer;height:20px;width:20px" class="fa fa-file-excel-o"></i></a></td>
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
      
    $('input[name=\'filter_city\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=report/sale_order/city_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    
    $('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=sale/customer/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
    });
    
$('#button-filter').on('click', function() {
	url = 'index.php?path=report/sale_order&token=<?php echo $token; ?>';
	
	var filter_city = $('input[name=\'filter_city\']').val();
	
	if (filter_city) {
		url += '&filter_city=' + encodeURIComponent(filter_city);
	}
        
        var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
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
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,
             widgetParent: 'body'
});
//--></script></div>
<?php echo $footer; ?>


<script type="text/javascript">


 $(document).delegate('.download', 'click', function(e) {
  
            e.preventDefault();            
            $orderstartdate = $(this).attr('order_startdate');
            $orderenddate = $(this).attr('order_enddate');   
            $filter_customer = $(this).attr('filter_customer');
            $filter_order_status_id = $(this).attr('filter_order_status_id');  
            $filter_city = $(this).attr('filter_city');            
            url = 'index.php?path=sale/order/consolidatedOrdersSummary&token=<?php echo $token; ?>&orderenddate=' + $orderenddate+'&orderstartdate='+$orderstartdate+'&filter_customer='+$filter_customer+'&filter_order_status_id='+$filter_order_status_id+'&filter_city='+$filter_city;               
            var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
            var filter_customer = $('select[name=\'filter_customer\']').val();
	
                           
              if (filter_order_status_id != 0) {
                alert(1);
                url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
              }	 
                var filter_city = $('input[name=\'filter_city\']').val();
              
              if (filter_city) {
                url += '&filter_city=' + encodeURIComponent(filter_city);
              }
              
              if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
              }
                location = url;
             
        });



        
function excel() {
       url = 'index.php?path=report/sale_order/saleorderexcel&token=<?php echo $token; ?>';
      
         	var filter_city = $('input[name=\'filter_city\']').val();
	
	if (filter_city) {
		url += '&filter_city=' + encodeURIComponent(filter_city);
	}
        
        var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
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

        </script>
<style>

        .download
 {
   font-size: 1.5em;
 }
 body {
    position: relative;
}
 </style>