<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-country').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-filter-country"><?php echo $column_name; ?></label>
                <input type="text" name="filter_country" value="<?php echo $filter_country; ?>" placeholder="<?php echo $column_name; ?>" id="input-filter-country" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-filter-iso-code-2"><?php echo $column_iso_code_2; ?></label>
                <input type="text" name="filter_iso_code_2" value="<?php echo $filter_iso_code_2; ?>" placeholder="<?php echo $column_iso_code_2; ?>" id="input-filter-iso-code-2" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-filter-iso-code-3"><?php echo $column_iso_code_3; ?></label>
                <input type="text" name="filter_iso_code_3" value="<?php echo $filter_iso_code_3; ?>" placeholder="<?php echo $column_iso_code_3; ?>" id="input-filter-iso-code-3" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
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
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-country">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'iso_code_2') { ?>
                    <a href="<?php echo $sort_iso_code_2; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_iso_code_2; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_iso_code_2; ?>"><?php echo $column_iso_code_2; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'iso_code_3') { ?>
                    <a href="<?php echo $sort_iso_code_3; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_iso_code_3; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_iso_code_3; ?>"><?php echo $column_iso_code_3; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($countries) { ?>
                <?php foreach ($countries as $country) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($country['country_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $country['name']; ?></td>
                  <td class="text-left"><?php echo $country['iso_code_2']; ?></td>
                  <td class="text-left"><?php echo $country['iso_code_3']; ?></td>
                  <td class="text-right"><a href="<?php echo $country['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
$('#button-filter').on('click', function() {
	var url = 'index.php?path=localisation/country&token=<?php echo $token; ?>';

	var filter_country = $('input[name=\'filter_country\']').val();

	if (filter_country) {
		url += '&filter_country=' + encodeURIComponent(filter_country);
	}

	var filter_iso_code_2 = $('input[name=\'filter_iso_code_2\']').val();

	if (filter_iso_code_2) {
		url += '&filter_iso_code_2=' + encodeURIComponent(filter_iso_code_2);
	}

	var filter_iso_code_3 = $('input[name=\'filter_iso_code_3\']').val();
	
	if (filter_iso_code_3) {
		url += '&filter_iso_code_3=' + encodeURIComponent(filter_iso_code_3);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_country\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=localisation/country/autocomplete&token=<?php echo $token; ?>&filter_country=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['country'],
						value: item['country_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_country\']').val(item['label']);
	}
});

$('input[name=\'filter_iso_code_2\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=localisation/country/autocomplete&token=<?php echo $token; ?>&filter_iso_code_2=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['iso_code_2'],
						value: item['country_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_iso_code_2\']').val(item['label']);
	}
});

$('input[name=\'filter_iso_code_3\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=localisation/country/autocomplete&token=<?php echo $token; ?>&filter_iso_code_3=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['iso_code_3'],
						value: item['country_id']
					}
				}));
				
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_iso_code_3\']').val(item['label']);
	}
});
//--></script>
<?php echo $footer; ?>