<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
      <div class="pull-right">
            <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
            
      </div>    
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">
           <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-make"><?php echo $entry_make; ?></label>
                <input type="text" name="filter_make" value="<?php echo $filter_make; ?>" placeholder="<?php echo $entry_make; ?>" id="input-make" class="form-control" />
              </div>
            </div>
              
            <div class="col-sm-3">
            <div class="form-group">
                <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                </div>
              </div>
            </div>
            
              <div class="col-sm-3">
                  <div class="form-group">
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
              </div>
              </div>
    
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date" style="max-width: 321px;">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
            </div>
            </div>
           <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-registration-number"><?php echo $entry_registration_number; ?></label>
                <input type="text" name="filter_registration_number" value="<?php echo $filter_registration_number; ?>" placeholder="<?php echo $entry_registration_number; ?>" id="input-registration-number" class="form-control" />
            </div>
           </div>
              
           <div class="col-sm-3">
            <div class="form-group">
            <div class="input-group date" style="max-width: 321px; margin-top:23px;">
                  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>    
            </div>
            </div>
           </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'c.make') { ?>
                    <a href="<?php echo $sort_make; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_make; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_make; ?>"><?php echo $column_make; ?></a>
                    <?php } ?></td>
                  <td style="width: 3px;" class="text-left"><?php if ($sort == 'c.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_registration_number; ?></td>

                  <td class="text-left"><?php if ($sort == 'c.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($vehicles) { ?>
                <?php foreach ($vehicles as $vehicle) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($vehicle['vehicle_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $vehicle['vehicle_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $vehicle['vehicle_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $vehicle['make']; ?></td>
                  <td class="text-left"><?php echo $vehicle['model']; ?></td>
                  <td class="text-left"><?php echo $vehicle['registration_number']; ?></td>
                  <td class="text-left"><?php echo $vehicle['status']; ?></td>
                  <td class="text-left"><?php echo $vehicle['date_added']; ?></td>
                  <td class="text-right"><button type="button" data-toggle="tooltip" title="Dispatch Planning" class="btn btn-primary" onclick=""><i class="fa fa-random"></i></button>
<a href="<?php echo $vehicle['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
</td>
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
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  url = 'index.php?path=vehicles/vehicles_list&token=<?php echo $token; ?>';
  
  var filter_make = $('input[name=\'filter_make\']').val();
  
  if (filter_make) {
    url += '&filter_make=' + encodeURIComponent(filter_make);
  }
  
  var filter_model = $('input[name=\'filter_model\']').val();
  
  if (filter_model) {
    url += '&filter_model=' + encodeURIComponent(filter_model);
  } 
  
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status); 
  } 
  
  var filter_registration_number = $('input[name=\'filter_registration_number\']').val();
  
  if (filter_registration_number) {
    url += '&filter_registration_number=' + encodeURIComponent(filter_registration_number);
  } 
    
  var filter_date_added = $('input[name=\'filter_date_added\']').val();
  
  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }
  
  location = url;
});
//--></script> 
  <script type="text/javascript"><!--

$('input[name=\'filter_make\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=vehicles/vehicles_list/autocompletebyVehicleMake&token=<?php echo $token; ?>&filter_make=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['make'],
            value: item['make']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_make\']').val(item['label']);
  } 
});

$('input[name=\'filter_model\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=vehicles/vehicles_list/autocompletebyVehicleModel&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['model'],
            value: item['model']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_model\']').val(item['label']);
  } 
});

$('input[name=\'filter_registration_number\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=vehicles/vehicles_list/autocompletebyVehicleRegistrationNumber&token=<?php echo $token; ?>&filter_registration_number=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['registration_number'],
            value: item['vehicle_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_registration_number\']').val(item['label']);
  } 
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false,
     widgetParent: 'body'
});

function excel() {
            
    url = 'index.php?path=vehicles/vehicles_list/export_excel&token=<?php echo $token; ?>';
    
    location = url;
}

//--></script></div>
<?php echo $footer; ?> 

<style>
body {
    position: relative;
}
</style>

