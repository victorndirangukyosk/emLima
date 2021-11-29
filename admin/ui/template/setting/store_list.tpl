<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">

            <div class="pull-right">
                <?php if(!$this->user->isVendor()){ ?>  

            
                

                    <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i>
                    </a>
                        
                    
                <?php } ?>

                    <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-store').submit() : false;"><i class="fa fa-trash-o"></i>
                        </button>
                        <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
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
            </div>
            <div class="panel-body">
                <div class="well" style="display:none;">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_name ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_city ?></label>
                                <input type="text" name="filter_city" value="<?php echo $filter_city; ?>" placeholder="City" class="form-control" />
                            </div>

                        </div>
                        <div class="col-sm-4">

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
                            
                            
                            <?php if(!$this->user->isVendor()){ ?>  
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?= $text_vendor ?></label>
                                <input type="text" name="filter_vendor" value="<?php echo $filter_vendor; ?>" placeholder="<?php echo $entry_vendor; ?>" id="input-model" class="form-control" />
                            </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-4">

                            <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date"  style="max-width: 100%;">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>

                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="word-break: break-all;">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-left">
                                        <?php if ($sort == 's.name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left"><?= $column_address ?></td>
                                    <td class="text-left"><?= $column_city ?></td>
                                    <td class="text-left"><?= $column_zipcode ?></td>
                                    <td class="text-left"><?= $column_vendor_name ?></td>
                                    <td class="text-left">
                                        <?php if ($sort == 's.status') { ?>
                                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($stores) { ?>
                                <?php foreach ($stores as $store) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($store['store_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $store['name']; ?></td>
                                    <td class="text-left"><?php echo $store['address']; ?></td>
                                    <td class="text-left"><?php echo $store['city']; ?></td>
                                    <td class="text-left">

                                        <?php if ($this->config->get('config_store_location') == 'zipcode') { ?>
                                            <?php echo $store['zipcode']; ?>
                                        <?php } else { ?>
                                            <?php echo $store['serviceable_radius']; ?>
                                        <?php } ?>

                                        
                                            
                                    </td>
                                    <td>
                                        
                                        <a href="<?php echo $store['vendor_link']; ?>" > <?php echo $store['vendor_name'] ?> </a>

                                    </td>

                                    <td class="text-left">
                                        <?php if($store['status']){ ?>
                                            <?php echo $text_enabled; ?>
                                        <?php }else{ ?>
                                            <?php echo $text_disabled; ?>  
                                        <?php } ?>
                                    </td>
                                    <td class="text-right">
                                        

                                        <a href="<?php echo $store['duplicate']; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-info"><i class="fa fa-copy"></i></a>
                                        
                                        <a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
<?php echo $footer; ?> 

<script>
    
    $('input[name=\'filter_city\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=setting/store/city_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    
    $('input[name=\'filter_name\']').autocomplete({
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
		$('input[name=\'filter_name\']').val(item['label']);
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

    $('#button-filter').on('click', function() {
	var url = 'index.php?path=setting/store&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
        
    var filter_city = $('input[name=\'filter_city\']').val();

	if (filter_city) {
		url += '&filter_city=' + encodeURIComponent(filter_city);
	}

	var filter_vendor = $('input[name=\'filter_vendor\']').val();

	if (filter_vendor) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});

 function excel() {
            
    url = 'index.php?path=setting/store/export_excel&token=<?php echo $token; ?>';
    
    location = url;
}
</script>

<script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript">
    $('.date').datetimepicker({
        pickTime: false
    });    
</script>