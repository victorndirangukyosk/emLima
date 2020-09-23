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
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i>
									</button>
									</span>
								</div>
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
								<label class="control-label" for="input-group">
									<?= $entry_name ?>
								</label>
								<input name="filter_vendor_name" value="<?= $filter_vendor_name ?>" class="form-control" />
							</div>	
							</div>

							<div class="col-sm-4">
								<div class="form-group">
	                                <label class="control-label"><?= $entry_store ?></label>
	                                <input name="store_name" value="<?= $filter_store ?>" class="form-control" />
	                                <input type="hidden" name="filter_store_id" />
	                            </div>
	                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
	                         </div> 

					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td class="left"><?= $column_store ?></td>  

								<td class="text-left"><?php echo $column_date_start; ?></td>
								<td class="text-left"><?php echo $column_date_end; ?></td>
								<td class="text-right"><?= $column_name ?></td>
								<td class="text-right"><?php echo $column_orders; ?></td>
								<td class="text-right"><?= $column_commission ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($orders) { ?>
							<?php foreach ($orders as $order) { ?>
							<tr>
								<td><?= $order['store'] ?></td>  
								<td class="text-left"><?php echo $order['date_start']; ?></td>
								<td class="text-left"><?php echo $order['date_end']; ?></td>
								<td class="text-right"><?php echo $order['user']; ?></td>
								<td class="text-right"><?php echo $order['orders']; ?></td>
								
								<td class="text-right"><?php echo $order['commission']; ?></td>
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

		$('input[name=\'filter_vendor_name\']').autocomplete({

	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=report/commission/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
		
$('#button-filter').on('click', function() {
	url = 'index.php?path=report/commission&token=<?php echo $token; ?>';
	
	var filter_vendor_name = $('input[name=\'filter_vendor_name\']').val();
	
	if (filter_vendor_name) {
		url += '&filter_vendor_name=' + encodeURIComponent(filter_vendor_name);
	}

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
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
		

	location = url;
});

function excel() {
	url = 'index.php?path=report/commission/excel&token=<?php echo $token; ?>';
	
	var filter_vendor_name = $('input[name=\'filter_vendor_name\']').val();
	
	if (filter_vendor_name) {
		url += '&filter_vendor_name=' + encodeURIComponent(filter_vendor_name);
	}
        
    var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	var filter_store = $('input[name=\'store_name\']').val();
    var filter_store_id = $('input[name=\'filter_store_id\']').val();

    if (filter_store) {
            url += '&filter_store=' + encodeURIComponent(filter_store);
            url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
    }

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
    	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	

	location = url;
}

//--></script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,  widgetParent: 'body'
});
//-->


</script>
</div>
<?php echo $footer; ?>


<style>
body {
    position: inline;
}
</style>