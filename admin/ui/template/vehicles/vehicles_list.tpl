<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="dispatchplanning" data-toggle="tooltip" title="Dispatch Planning" class="btn btn-primary"><i class="fa fa-random"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
                  <td class="text-right"><a href="<?php echo $vehicle['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
      <!-- Modal -->
    <div id="dispatchModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div style="color: white;background-color: #008db9;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><strong>Assign Vehicle </strong></h4>
            </div>
            <div class="modal-body">
                <form id="vehicle_dispatch_planning" name="vehicle_dispatch_planning">
                    <div class="row">
                    <div class="col-sm-6">    
                    <div class="form-group required">
                       <label for="input-delivery-date" class="col-form-label">Delivery Date</label>
                       <div class="input-group date">
                           <input type="text" name="delivery_date" value="" placeholder="Delivery Date" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                           <span class="input-group-btn">
                               <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                           </span>
                       </div>
                    </div>
                    </div>
                    <div class="col-sm-6">        
                    <label for="recipient-name" class="col-form-label">Delivery Timeslot</label>
                    <select class="form-select" id="delivery_timeslot" name="delivery_timeslot">
                     <option value="">Select Delivery Timeslot</option>
                     </select>
                    </div>
                    </div>
                    <div class="form-group required">
                        <label for="recipient-name" class="col-form-label">Delivery Executive</label>
                        <input type="hidden" id="clicked_vehicle_id" name="clicked_vehicle_id" value="" />
                        <select class="form-select" id="delivery_executive" name="delivery_executive">
                            <option value="">Select Delivery Executive</option>
                        </select>
                    </div>
                    <div class="form-group required">
                        <label for="recipient-name" class="col-form-label">Driver</label>
                        <select class="form-select" id="driver" name="driver">
                            <option value="">Select Driver</option>
                        </select>
                    </div>
                    <div class="form-group required">
                        <label for="recipient-name" class="col-form-label">Vehicle</label>
                        <select class="form-select" id="vehicle" name="vehicle">
                            <option value="">Select Vehicle</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-danger" style="display:none;">
                </div>
                <div class="alert alert-success" style="display:none;">
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_vehicle_to_dispatch_plan" name="add_vehicle_to_dispatch_plan">Assign Vehicle</button>
            </div>
        </div>

    </div>
</div>  
  <script type="text/javascript"><!--
$('button[id^=\'dispatchplanning\']').on('click', function (e) {
e.preventDefault();
console.log($(this).data('vehicleid'));
$('#clicked_vehicle_id').val($(this).data('vehicleid'));
$('#dispatchModal').modal('toggle');
$.ajax({
                url: 'index.php?path=dropdowns/dropdowns/getdeliverytimeslots&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json.suggestions.delivery_timeslots);
                    if(json != null) {
                    var option = '<option value="">Select Delivery Timeslot</option>';
                    for (var i=0;i<json.suggestions.delivery_timeslots.length;i++){
                           option += '<option value="'+ json.suggestions.delivery_timeslots[i].timeslot + '">' + json.suggestions.delivery_timeslots[i].timeslot + '</option>';
                    }
                    console.log(option);
                    var $select = $('#delivery_timeslot');
                    $select.html('');
                    if(json.suggestions.delivery_timeslots != null && json.suggestions.delivery_timeslots.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    }
            }
});
});
$('#delivery_timeslot, #delivery_timeslot').on('change', function() {
$.ajax({
                url: 'index.php?path=vehicles/dispatchplanning/getunassignedvehicles&delivery_date='+$('input[name=\'delivery_date\']').val()+'&delivery_timeslot='+$('select[name=\'delivery_timeslot\']').val()+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    if(json != null) {
                    var option = '<option value="">Select Vehicle</option>';
                    for (var i=0;i<json.length;i++){
                           option += '<option value="'+ json[i].vehicle_id + '">' + json[i].registration_number + '</option>';
                    }
                    console.log(option);
                    var $select = $('#vehicle');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    }
            }
});
$.ajax({
                url: 'index.php?path=vehicles/dispatchplanning/getunassigneddeliveryexecutives&delivery_date='+$('input[name=\'delivery_date\']').val()+'&delivery_timeslot='+$('select[name=\'delivery_timeslot\']').val()+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    if(json != null) {
                    var option = '<option value="">Select Delivery Executive</option>';
                    for (var i=0;i<json.length;i++){
                           option += '<option value="'+ json[i].delivery_executive_id + '">' + json[i].firstname +' '+ json[i].lastname + '</option>';
                    }
                    console.log(option);
                    var $select = $('#delivery_executive');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    }
            }
});
$.ajax({
                url: 'index.php?path=vehicles/dispatchplanning/getunassigneddrivers&delivery_date='+$('input[name=\'delivery_date\']').val()+'&delivery_timeslot='+$('select[name=\'delivery_timeslot\']').val()+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    if(json != null) {
                    var option = '<option value="">Select Driver</option>';
                    for (var i=0;i<json.length;i++){
                           option += '<option value="'+ json[i].driver_id + '">' + json[i].firstname +' '+json[i].lastname+ '</option>';
                    }
                    console.log(option);
                    var $select = $('#driver');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    }
            }
});
});
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
$('#add_vehicle_to_dispatch_plan').on('click', function() {
if($('select[name=\'delivery_executive\']').val() == '' || $('select[name=\'driver\']').val() == '' || $('select[name=\'delivery_timeslot\']').val() == '' || $('input[name=\'delivery_date\']').val() == '') {
$('.alert.alert-danger').html('<i class="fa fa-times-circle text-danger"></i>All Fileds Are Mandatory!');
$('.alert.alert-danger').show();
console.log('Validation Failed!');
return false;
}
$.ajax({
            url: 'index.php?path=vehicles/dispatchplanning&vehicle_id='+$('#clicked_vehicle_id').val()+'&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $("form[id^='vehicle_dispatch_planning']").serialize(),
            beforeSend: function () {
            $('#add_vehicle_to_dispatch_plan').prop("disabled",true);
            },
            complete: function () {
            $('#add_vehicle_to_dispatch_plan').prop("disabled",false);
            },
            success: function(json) {
                if (json) {
                $('.alert.alert-success').html('<i class="fa fa-check-circle text-success"></i>Vehicle Assigned Successfully!');
                $('.alert.alert-success').show();
                setTimeout(function(){ location.reload(); }, 2000);
                }
                else {
                $('.alert.alert-danger').html('<i class="fa fa-times-circle text-danger"></i> Please Try Again Later!');
                $('.alert.alert-danger').show();
                setTimeout(function(){ location.reload(); }, 2000);
                }
            }
});
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
.bootstrap-select {
width : 100% !important;    
}
</style>

