<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-tax-class" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
		<button type="submit" form="form-tax-class" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
		<button type="submit" onclick="save('new')" form="form-tax-class" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>		
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
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
              <?php if ($error_title) { ?>
              <div class="text-danger"><?php echo $error_title; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              <?php if ($error_description) { ?>
              <div class="text-danger"><?php echo $error_description; ?></div>
              <?php } ?>
            </div>
          </div>
          <table id="tax-rule" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_rate; ?></td>
                <td class="text-left"><?php echo $entry_priority; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $tax_rule_row = 0; ?>
              <?php foreach ($tax_rules as $tax_rule) { ?>
              <tr id="tax-rule-row<?php echo $tax_rule_row; ?>">
                <td class="text-left"><select name="tax_rule[<?php echo $tax_rule_row; ?>][tax_rate_id]" class="form-control">
                    <?php foreach ($tax_rates as $tax_rate) { ?>
                    <?php  if ($tax_rate['tax_rate_id'] == $tax_rule['tax_rate_id']) { ?>
                    <option value="<?php echo $tax_rate['tax_rate_id']; ?>" selected="selected"><?php echo $tax_rate['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo $tax_rate['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left">                    
                    <input name="tax_rule[<?php echo $tax_rule_row; ?>][based]" value="shipping" type="hidden" />                    
                    <input type="text" name="tax_rule[<?php echo $tax_rule_row; ?>][priority]" value="<?php echo $tax_rule['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#tax-rule-row<?php echo $tax_rule_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $tax_rule_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-left"><button type="button" onclick="addRule();" data-toggle="tooltip" title="<?php echo $button_rule_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var tax_rule_row = <?php echo $tax_rule_row; ?>;

function addRule() {
	html  = '<tr id="tax-rule-row' + tax_rule_row + '">';
	html += '  <td class="text-left">';
        html += '  <select name="tax_rule[' + tax_rule_row + '][tax_rate_id]" class="selectpicker">';
        <?php foreach ($tax_rates as $tax_rate) { ?>
        html += '    <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo addslashes($tax_rate['name']); ?></option>';
        <?php } ?>
        html += '  </select>';
        html += '  </td>';
	html += '  <td class="text-left">';
        html += '    <input name="tax_rule[' + tax_rule_row + '][based]" value="shipping" type="hidden" />';
        html += '    <input type="text" name="tax_rule[' + tax_rule_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" />';
        html += '  </td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#tax-rule-row' + tax_rule_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#tax-rule tbody').append(html);
	
	tax_rule_row++;
        
        $('.selectpicker').selectpicker();
}
//--></script>
<script type="text/javascript"><!--
function save(type){
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}
//--></script></div>
<?php echo $footer; ?>