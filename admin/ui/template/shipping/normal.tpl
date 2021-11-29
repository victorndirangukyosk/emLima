<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-shopper" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
    <button type="submit" form="form-shopper" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
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
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shopper" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="normal_total" value="<?php echo $normal_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cost"><?= $entry_cost ?></label>
            <div class="col-sm-10">
              <input type="text" name="normal_cost" value="<?php echo $normal_cost; ?>" placeholder="Cost" id="input-cost" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-normal_number_of_days"><?= $entry_number_days_visible ?></label>
            <div class="col-sm-10">
              <input type="text" name="normal_number_of_days" value="<?php echo $normal_number_of_days; ?>" placeholder="No. of days visible in front default = 7" id="input-normal_number_of_days" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?= $entry_free_delivery_amount ?></label>
            <div class="col-sm-10">
              <input type="text" name="normal_free_delivery_amount" value="<?php echo $normal_free_delivery_amount; ?>" placeholder="Free Delivery Amount" class="form-control" />
            </div>
          </div>  
            
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="normal_status" id="input-status" class="form-control">
                <?php if ($normal_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?= $entry_home_delivery_time_difference ?></label>
            <div class="col-sm-10">
              <input data-date-format="HH:mm" class="form-control time_diff" value="<?php echo $normal_delivery_time_diff; ?>" placeholder="Time Difference for home delivery" type="text" name="normal_delivery_time_diff" />  
              <?php if ($error_delivery_time_diff) { ?>
              <div class="text-danger"><?php echo $error_delivery_time_diff; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"> Use Delivery System Charges</label>
            <div class="col-sm-10">
              <label class="radio-inline">
                  <?php if ($normal_use_deliverysystem) { ?>
                  <input type="radio" name="normal_use_deliverysystem" value="1" checked="checked" />
                  Yes
                  <?php } else { ?>
                  <input type="radio" name="normal_use_deliverysystem" value="1" />
                  Yes
                  <?php } ?>
              </label>
              <label class="radio-inline">
                  <?php if (!$normal_use_deliverysystem) { ?>
                  <input type="radio" name="normal_use_deliverysystem" value="0" checked="checked" />
                  No
                  <?php } else { ?>
                  <input type="radio" name="normal_use_deliverysystem" value="0" />
                  No
                  <?php } ?>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="normal_sort_order" value="<?php echo $normal_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
            <div class="tab-pickup-timeslot">
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-bordered col-md-12" id="delivery-timeslots">
                    <thead>
                      <tr>
                        <td ><?= $column_timeslot ?></td>
                        <td ><?= $column_sunday ?></td>
                        <td ><?= $column_monday ?></td>
                        <td ><?= $column_tuesday ?></td>
                        <td ><?= $column_wesnesday ?></td>
                        <td ><?= $column_thursday ?></td>
                        <td ><?= $column_friday ?></td>
                        <td ><?= $column_saturday ?></td>
                        <td></td>
                      </tr>  
                    </thead>
                    <tbody>
                      <?php $i=0; ?>
                      <?php foreach($delivery_timeslots as $timeslot){ ?>   
                      <tr>
                        <td>
                          <?= $timeslot['timeslot'] ?>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[0][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[0]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[1][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[1]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[2][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[2]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[3][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[3]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[4][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[4]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[5][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[5]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-controls" name="delivery_timeslots[6][<?= $timeslot['timeslot'] ?>]">                        
                            <?php if($timeslot[6]){ ?>
                            <option value="1" selected=""><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php }else{ ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected=""><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </td>    
                        <td>
                          <a class="remove btn btn-danger">
                            <i class="fa fa-trash"></i>
                          </a>
                        </td>    
                      </tr>    
                      <?php $i++; } ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row">
                <div class="timeslot_form" style="padding-left: 100px; position: relative;">
                  <div class="form-group">
                    <div class="col-lg-2 text-right">
                       <label style="line-height: 30px;"><?= $entry_add_timeslot ?></label>
                    </div>
                    <div class="col-lg-10 time_slot">
                      <input style="float:left; width: 100px;margin-right: 5px;" class="form-control time" placeholder="From" type="text" name="from" />              
                      <input style="float:left; width: 100px;" class="form-control time" placeholder="To" type="text" name="to" />                                            
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-2">
                      <label>&nbsp;</label>
                    </div>
                    <div class="col-lg-10">
                      <button type="button" class="btn btn-primary" onclick="add('delivery');">
                        <i class="fa fa-plus"></i><?= $button_add_timeslot ?>
                      </button>
                    </div>
                  </div>
                </div> 
              </div>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>
<style>
  .time_slot_wrapper .row, .ptime_slot_wrapper .row{
    width: 222px !important;
  }
  .time_slot {
    float: left;
    font-size: 16px;
    line-height: 30px;
    text-indent: 18px;
    width: 170px;
  }
  .time_slot_wrapper .remove, .ptime_slot_wrapper .remove {
    float: right !important;
  }
  .time_slot_wrapper, .ptime_slot_wrapper {
    width: 220px;
  }
  .time_slot > input {
    display: inline-block;
    float: left;
    margin-right: 5px;
    width: 80px;
  }
  .row {
    padding: 5px 0;
  }

</style>
<script type="text/javascript">

$(function(){
        $('.time').datetimepicker({
            pickDate: false,
            format: 'hh:mma'
        });

        $('.time_diff').datetimepicker({
            pickDate: false,
            format: 'HH:mm',
            default: false
        });

        $(document).delegate('.remove','click', function(){
          $(this).parent().parent().remove();
        });


    });
    var i = <?= $i ?>;

function add(tblname){

  $from = $('input[name="from"]').val();
  $to   = $('input[name="to"]').val();

  if(!$from.length > 0){
    $('input[name="from"]').css('border','1px solid red');  
    return;
  }else{
    $('input[name="from"]').val('');  
    $('input[name="from"]').css('border','1px solid #ccc');  
  }

  if(!$to.length > 0){
    $('input[name="to"]').css('border','1px solid red'); 
    return;
  }else{
    $('input[name="to"]').css('border','1px solid #ccc');  
    $('input[name="to"]').val('');  
  }

  $timeslot = $from+' - '+$to;
    
  $html  = '<tr>';        
  $html += '<td>';
  $html += $timeslot;
  $html += '</td>';

  for($j=0; $j<7; $j++){        
    $html += '<td>';
    $html += '<select class="form-controls" name="delivery_timeslots['+$j+']['+$timeslot+']">';                        
    $html += '<option value="1" selected="">Enable</option>';
    $html += '<option value="0" >Disable</option>';
    $html += '</select>';
    $html += '</td>';        
  }
  
  $html += '<td>';
  $html += '<a class="remove btn btn-danger">';
  $html += '<i class="fa fa-trash"></i>';
  $html += '</a>';
  $html += '</td>';
  $html += '</tr>';
  
  $('#'+tblname+'-timeslots tbody').append($html);
    
}
</script>

<script type="text/javascript"><!--
function save(type){
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'button';
  input.value = type;
  form = $("form[id^='form-']").append(input);
  form.submit();
}
//--></script>
<?php echo $footer; ?> 