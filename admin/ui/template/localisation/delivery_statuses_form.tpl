<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">

      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
   <!--  <button type="submit" form="form-location" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a> -->
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
      
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-tax-class">
          
          <table id="tax-rule" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_code; ?></td>
                <td class="text-left"><?php echo $entry_trigger; ?></td>
                <td class="text-left"><?php echo $entry_order_statuses; ?></td>                
              </tr>
            </thead>
            <tbody>
              <?php $delivery_statuses_row = 0; ?>
              <?php foreach ($delivery_statuses as $delivery_status) { ?>
                <tr id="tax-rule-row<?php echo $delivery_statuses_row; ?>">
                  
                  <td class="text-left">                   
                      <input type="text" name="delivery_status[<?php echo $delivery_statuses_row; ?>][code]" value="<?php echo $delivery_status['code']; ?>" placeholder="<?php echo $entry_trigger; ?>" class="form-control" readonly="true"/></td>
                  
                  <td class="text-left">                    
                      <input type="text" name="delivery_status[<?php echo $delivery_statuses_row; ?>][status]" value="<?php echo $delivery_status['status']; ?>" placeholder="<?php echo $entry_trigger; ?>" class="form-control" readonly="true"/></td>


                  <td class="text-left"><select name="delivery_status[<?php echo $delivery_statuses_row; ?>][order_status_id]" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php  if ($order_status['order_status_id'] == $delivery_status['order_status_id']) { ?>

                      <!-- <input name="delivery_status[<?php echo $delivery_statuses_row; ?>][order_status_id]" value="<?php $delivery_status['order_status_id'] ?>" type="hidden" />  -->    

                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                </tr>
              <?php $delivery_statuses_row++; ?>
              <?php } ?>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
function save(type) {
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}
//--></script></div>
<?php echo $footer; ?>