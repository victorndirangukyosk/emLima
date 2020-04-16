<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-offer').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            <div class="col-sm-4">

              <div class="form-group">
                <label class="control-label" for="input-discount"><?php echo $column_store; ?></label>
                <input type="text" name="filter_store" value="<?php echo $filter_store; ?>" placeholder="<?php echo $column_store; ?>" id="input-store" class="form-control" />
                <input type="hidden" name="filter_store_id" value="<?= $filter_store_id ?>" />

              </div>

              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />
              </div>
              
            </div>
            <div class="col-sm-4">              
			  <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $column_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $column_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			  <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $column_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $column_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $column_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-offer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>

                  <td class="text-left">
                    <?php echo $column_store_name; ?>
                  </td>


                  <td class="text-left"><?php if ($sort == 'cd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <!-- <td class="text-right"><?php if ($sort == 'c.discount') { ?>
                    <a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
                    <?php } ?></td> -->
                  <td class="text-left"><?php if ($sort == 'c.date_start') { ?>
                    <a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.date_end') { ?>
                    <a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($offers)) { ?>
                <?php foreach ($offers as $offer) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($offer['offer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $offer['offer_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $offer['offer_id']; ?>" />
                    <?php } ?></td>

                  <td class="text-left"><?php echo $offer['store_name']; ?></td>
                  <td class="text-left"><?php echo $offer['name']; ?></td>
                  <!-- <td class="text-right"><?php echo $offer['discount']; ?></td> -->
                  <td class="text-left"><?php echo $offer['date_start']; ?></td>
                  <td class="text-left"><?php echo $offer['date_end']; ?></td>
                  <td class="text-left"><?php echo $offer['status']; ?></td>
                  <td class="text-right"><a href="<?php echo $offer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--

$(function(){
        $('input[name=\'filter_store\']').autocomplete({
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
                    $('input[name=\'filter_store\']').val(item['label']);
                    $('input[name=\'filter_store_id\']').val(item['value']);
            }   
        });
    });


$('#button-filter').on('click', function() {
	var url = 'index.php?path=marketing/offer&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	var filter_store = $('input[name=\'filter_store\']').val();

	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

  var filter_store_id = $('input[name=\'filter_store_id\']').val();

  if (filter_store_id) {
    url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
  }

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=marketing/offer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['offer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});
//--></script>
<?php echo $footer; ?>