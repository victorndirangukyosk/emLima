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
            <div class="col-sm-6">
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
            <div class="col-sm-6">


              <div class="form-group">
                <label class="control-label"><?= $entry_store ?></label>
                <input name="store_name" value="<?= $filter_store ?>" class="form-control" />
                <input type="hidden" name="filter_store_id" value="<?= $filter_store_id ?>" />
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
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
             
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_delivery_date; ?></td>
                <td class="text-left"><?php echo $column_order_no; ?></td>
                <td class="text-right"><?php echo $column_no_of_items; ?></td>
                <td class="text-right"><?php echo $column_subtotal; ?></td>
                <td class="text-right"><?php echo $column_wallet_used; ?></td>
                <td class="text-right"><?php echo $column_coupon; ?></td>

                <td class="text-right"><?php echo $column_reward_points_claimed; ?></td>
                <td class="text-right"><?php echo $column_delivery_charges; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>

                <td class="text-right"><?php echo $column_walletcredited; ?></td>
                <td class="text-right"><?php echo $column_paymentmethod; ?></td>
                <td class="text-right"><?php echo $column_transaction_ID; ?></td>


              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['delivery_date']; ?></td>
                <td class="text-left"><?php echo $order['order_id']; ?></td>
                <td class="text-right"><?php echo $order['no_of_items']; ?></td>
                <td class="text-right"><?php echo $order['subtotal']; ?></td>
                <td class="text-right"><?php echo $order['wallet_used']; ?></td>
                <td class="text-right"><?php echo $order['coupon_used']; ?></td>

                <td class="text-right"><?php echo $order['reward_points_used']; ?></td>
                <td class="text-right"><?php echo $order['delivery_charge']; ?></td>
                <td class="text-right"><?php echo $order['total']; ?></td>

                <td class="text-right"><?php echo $order['walletCredited']; ?></td>
                <td class="text-right"><?php echo $order['payment_method']; ?></td>
                <td class="text-right"><?php echo $order['order_transaction_id']; ?></td>


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
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
      
    $(function(){
          $('input[name=\'store_name\']').autocomplete({
              'source': function(request, response) {                
                      $.ajax({
                              url: 'index.php?path=report/store_sales/store_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
                      $('input[name=\'store_name\']').val(item['label']);
                      $('input[name=\'filter_store_id\']').val(item['value']);
              }   
          });
      });


    $('input[name=\'filter_city\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=report/sale_advanced/city_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
	url = 'index.php?path=report/sale_advanced&token=<?php echo $token; ?>';
	
	var filter_store = $('input[name=\'store_name\']').val();
    var filter_store_id = $('input[name=\'filter_store_id\']').val();

    if (filter_store) {
            url += '&filter_store=' + encodeURIComponent(filter_store);
            url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
    }
        
        var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	location = url;
});


function excel() {
  
  url = 'index.php?path=report/sale_advanced/excel&token=<?php echo $token; ?>';
  
  var filter_store = $('input[name=\'store_name\']').val();
    var filter_store_id = $('input[name=\'filter_store_id\']').val();

    if (filter_store) {
            url += '&filter_store=' + encodeURIComponent(filter_store);
            url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
    }
        
        var filter_date_start = $('input[name=\'filter_date_start\']').val();
  
  if (filter_date_start) {
    url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
  }

  var filter_date_end = $('input[name=\'filter_date_end\']').val();
  
  if (filter_date_end) {
    url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
  }

  
  var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
  
  if (filter_order_status_id != 0) {
    url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
  } 

  location = url;
}


//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>