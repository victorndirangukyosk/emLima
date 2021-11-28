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
              <input type="text" name="express_total" value="<?php echo $express_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cost"><?= $entry_cost ?></label>
            <div class="col-sm-10">
              <input type="text" name="express_cost" value="<?php echo $express_cost; ?>" placeholder="Cost" id="input-cost" class="form-control" />
            </div>
          </div>
            
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?= $entry_free_delivery_amount ?></label>
            <div class="col-sm-10">
              <input type="text" name="express_free_delivery_amount" value="<?php echo $express_free_delivery_amount; ?>" placeholder="Free Delivery Amount" class="form-control" />
            </div>
          </div>  
            
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="express_status" id="input-status" class="form-control">
                <?php if ($express_status) { ?>
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
              <input data-date-format="HH:mm" class="form-control time_diff" value="<?php echo $express_delivery_time_diff; ?>" placeholder="Time Difference for home delivery" type="text" name="express_delivery_time_diff" />  
              <?php if ($error_delivery_time_diff) { ?>
              <div class="text-danger"><?php echo $error_delivery_time_diff; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?= $entry_how_much_time ?></label>
            <div class="col-sm-10">
              <input data-date-format="HH:mm" class="form-control time_period" value="<?php echo $express_how_much_time; ?>" placeholder="How much time for delivery time" type="text" name="express_how_much_time" />  
              <?php if ($error_how_much_time) { ?>
              <div class="text-danger"><?php echo $error_how_much_time; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"> Use Delivery System Charges</label>
            <div class="col-sm-10">
              <label class="radio-inline">
                  <?php if ($express_use_deliverysystem) { ?>
                  <input type="radio" name="express_use_deliverysystem" value="1" checked="checked" />
                  Yes
                  <?php } else { ?>
                  <input type="radio" name="express_use_deliverysystem" value="1" />
                  Yes
                  <?php } ?>
              </label>
              <label class="radio-inline">
                  <?php if (!$express_use_deliverysystem) { ?>
                  <input type="radio" name="express_use_deliverysystem" value="0" checked="checked" />
                  No
                  <?php } else { ?>
                  <input type="radio" name="express_use_deliverysystem" value="0" />
                  No
                  <?php } ?>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="express_sort_order" value="<?php echo $express_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
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

        $('.time_period').datetimepicker({
            pickDate: false,
            format: 'HH:mm',
            default: false
        });
        
        $(document).delegate('.remove','click', function(){
          $(this).parent().parent().remove();
        });


    });
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