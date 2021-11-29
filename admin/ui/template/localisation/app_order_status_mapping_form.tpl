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
                <!-- <td class="text-left"><?php echo $entry_code; ?></td> -->
                
                <td class="text-left"><?php echo $entry_order_statuses; ?></td>                
                <td class="text-left">App Order Status</td>
                <td class="text-left">App Order Status Code</td>
              </tr>
            </thead>
            <tbody>
              <?php $app_order_status_mapping_row = 0; ?>
              <?php foreach ($order_statuses as $order_status) { ?>
                <tr id="tax-rule-row<?php echo $app_order_status_mapping_row; ?>">
                  
                  
                  
                  <td class="text-left">                    
                      <input type="text" name="app_delivery_status[<?php echo $order_status['order_status_id']; ?>][order_status_id]" value="<?php echo $order_status['name']; ?>" placeholder="<?php echo $order_status['name']; ?>" class="form-control" style="background-color:#DEDEDE" readonly/></td>



                  <?php $find = false;
                  foreach ($db_delviery_statuses as $db_status) { ?>

                    <?php  if ($db_status['order_status_id'] == $order_status['order_status_id']) { ?>

                        <td class="text-left">
                          <select name="app_delivery_status[<?php echo $order_status['order_status_id']; ?>][app_order_status_id]" class="form-control">
                            <?php foreach ($app_order_statuses as $app_order_status) { ?>
                            <?php  if ($app_order_status['app_order_status_id'] == $db_status['app_order_status_id']) { ?>

                           

                            <option value="<?php echo $app_order_status['app_order_status_id']; ?>" selected="selected"><?php echo $app_order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $app_order_status['app_order_status_id']; ?>"><?php echo $app_order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </td>

                        <td class="text-left">                   

                            
                          <input type="text" name="app_delivery_status[<?php echo $order_status['order_status_id']; ?>][code]" value="<?php echo $db_status['code']; ?>" placeholder="<?php echo $entry_trigger; ?>" class="form-control" />
                             
                        </td>
                        
                        


                      <?php $find = true; } ?>

                    <?php } ?>

                  
                    <?php if(!$find) {?>

                        <td class="text-left">
                          <select name="app_delivery_status[<?php echo $order_status['order_status_id']; ?>][app_order_status_id]" class="form-control">
                            <?php foreach ($app_order_statuses as $app_order_status) { ?>
                            <?php  if ($app_order_status['app_order_status_id'] == $app_delivery_status['app_order_status_id']) { ?>

                           

                            <option value="<?php echo $app_order_status['app_order_status_id']; ?>" selected="selected"><?php echo $app_order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $app_order_status['app_order_status_id']; ?>"><?php echo $app_order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </td>

                        <td class="text-left">                   

                            
                          <input type="text" name="app_delivery_status[<?php echo $order_status['order_status_id']; ?>][code]" value="" placeholder="<?php echo $entry_trigger; ?>" class="form-control" />
                             
                        </td>

                    <?php } ?>

                </tr>
              <?php $app_order_status_mapping_row++; ?>
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