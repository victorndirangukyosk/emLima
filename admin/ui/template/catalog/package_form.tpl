<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-filter" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
        <button type="submit" form="form-filter" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
        <button type="submit" onclick="save('new')" form="form-filter" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?= $entry_name ?></label>
            <div class="col-sm-10">
                <input class='form-control' name="name" value="<?= $name ?>" placeholder="Package name" />  
              <?php if ($error_name) { ?>
              <div class="text-danger">
                  <?php echo $error_name; ?>
              </div>
              <?php } ?>
            </div>
          </div>
            
          <div class="form-group">
            <label class="col-sm-2 control-label"><?= $entry_amount ?></label>
            <div class="col-sm-10">
                <input class='form-control' name="amount" value="<?= $amount ?>" placeholder="Amount" />  
            </div>
          </div>
            
          <div class="form-group">
            <label class="col-sm-2 control-label"><?= $entry_free_month ?></label>
            <div class="col-sm-10">
                <input style="width: 110px;" class='pull-left input-small form-control' name="free_month" placeholder="Months" value="<?= $free_month ?>" />  
                <input style="width: 110px;" class='pull-left input-small form-control' name="free_year" placeholder="Years" value="<?= $free_year ?>" />                  
            </div>
          </div>
            
          <div class="form-group">
            <label class="col-sm-2 control-label"><?= $entry_status ?></label>
            <div class="col-sm-10">
                <select name="status" class="form-control">
                    <?php if($status){ ?>
                    <option value="1" selected=""><?= $text_enabled ?></option>
                    <option value="0"><?= $text_disabled ?></option>
                    <?php }else{ ?>
                    <option value="1"><?= $text_enabled ?></option>
                    <option value="0" selected=""><?= $text_disabled ?></option>
                    <?php } ?>
                </select>
            </div>
          </div>
            
          <div class="form-group">
            <label class="col-sm-2 control-label"><?= $entry_priority ?></label>
            <div class="col-sm-10">
              <input class='form-control' name="priority" value="<?= $priority ?>" />  
            </div>
          </div>                
        </form>
      </div>
    </div>
  </div>
</div>

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