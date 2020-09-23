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

                <!-- <input type="text" name="commission_per" value="<?php echo $commission_per; ?>" placeholder="<?php echo $commission_per; ?>" class="form-control" />

                <input type="text" name="vat_commission_per" value="<?php echo $vat_commission_per; ?>" placeholder="<?php echo $vat_commission_per; ?>" class="form-control" /> -->

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
              
              <!-- <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_return_status_id" id="input-status" class="form-control">
                  <option value="0"><?php echo $text_all_status; ?></option>
                  <?php foreach ($return_statuses as $return_status) { ?>
                  <?php if ($return_status['return_status_id'] == $filter_return_status_id) { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div> -->

              <div class="form-group">
                  <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                  <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
              </div>

              <div class="form-group">
                  <label class="control-label" for="input-name">Commision and vat percentage</label>
                  <input type="text" name="commission_per" value="<?php echo $commission_per; ?>" placeholder="Commision Percentage" class="form-control" />

                 <input type="text" name="vat_commission_per" value="<?php echo $vat_commission_per; ?>" placeholder="VAT on Commision" class="form-control" />
              </div>


              

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

            </div>
              
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>

                <td class="text-left">Reference </td>
                <td class="text-left">Ref. No.</td>
                <td class="text-left">Date of Delivery</td>
                <td class="text-right">No. Of Items</td>
                <td class="text-right">Order Amount</td>
              </tr>
            </thead>
            <tbody>

              <?php if ($vendor_orders) { ?>
              <?php foreach ($vendor_orders as $order) { ?>
              <tr>
                <td class="text-left">Order</td>
                <td class="text-left"><?php echo $order['order_id']; ?></td>
                <td class="text-left"><?php echo $order['delivery_date']; ?></td>
                <td class="text-right"><?php echo $order['products']; ?></td>
                <td class="text-right"><?php echo $order['subtotal']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <!-- <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr> -->
              <?php } ?>

              <?php if ($returns) { ?>
              <?php foreach ($returns as $return) { ?>
              <tr>
                <td class="text-left">Return</td>
                <td class="text-left"><?php echo $return['return_id']; ?></td>
                <td class="text-left"><?php echo $return['return_date']; ?></td>
                <td class="text-right"><?php echo $return['items']; ?></td>
                <td class="text-right"><?php echo $return['return_amount']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <!-- <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr> -->
              <?php } ?>


            </tbody>
          </table>
        </div>
        <div class="row">
          <!-- <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div> -->
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?path=report/combined_report&token=<?php echo $token; ?>';
	
	var filter_city = $('input[name=\'filter_city\']').val();
	
	if (filter_city) {
		url += '&filter_city=' + encodeURIComponent(filter_city);
	}
  
  var commission_per = $('input[name=\'commission_per\']').val();

  if (commission_per) {
        url += '&commission_per=' + encodeURIComponent(commission_per);
    }
    var vat_commission_per = $('input[name=\'vat_commission_per\']').val();
    if (vat_commission_per) {
        url += '&vat_commission_per=' + encodeURIComponent(vat_commission_per);
    }

	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_store_name = $('input[name=\'filter_store_name\']').val();

  if (filter_store_name) {
      url += '&filter_store_name=' + encodeURIComponent(filter_store_name);
  }

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_return_status_id = $('select[name=\'filter_return_status_id\']').val();
	
	if (filter_return_status_id != 0) {
		url += '&filter_return_status_id=' + encodeURIComponent(filter_return_status_id);
	}	

	location = url;
});
  
  function excel() {
            
      url = 'index.php?path=report/combined_report/excel&token=<?php echo $token; ?>';

      var filter_city = $('input[name=\'filter_city\']').val();
    
    if (filter_city) {
        url += '&filter_city=' + encodeURIComponent(filter_city);
    }

    var commission_per = $('input[name=\'commission_per\']').val();
  
  if (commission_per) {
        url += '&commission_per=' + encodeURIComponent(commission_per);
    }
    var vat_commission_per = $('input[name=\'vat_commission_per\']').val();
    if (vat_commission_per) {
        url += '&vat_commission_per=' + encodeURIComponent(vat_commission_per);
    }

        
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
    
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

  var filter_store_name = $('input[name=\'filter_store_name\']').val();

  if (filter_store_name) {
      url += '&filter_store_name=' + encodeURIComponent(filter_store_name);
  }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }
        
    var filter_group = $('select[name=\'filter_group\']').val();
    
    if (filter_group) {
        url += '&filter_group=' + encodeURIComponent(filter_group);
    }
    
    var filter_return_status_id = $('select[name=\'filter_return_status_id\']').val();
    
    if (filter_return_status_id != 0) {
        url += '&filter_return_status_id=' + encodeURIComponent(filter_return_status_id);
    }   
      
    location = url;
  }

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

    $('input[name=\'filter_city\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=report/sale_return/city_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,  widgetParent: 'body'
});
//--></script></div>
<?php echo $footer; ?>


<style>
body {
    position: inline;
}
</style>