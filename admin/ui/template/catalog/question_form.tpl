<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
    <button type="submit" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
    <button type="submit" onclick="save('new')" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>   
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">



          <div class="tab-content">

              <?php foreach ($languages as $language) { ?>
          

                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-name">Question</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <span class="input-group-addon"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/ >
                      </span>

                      <input type="text" name="checkout_question_description[<?php echo $language['language_id']; ?>][question]" value="<?php echo isset($checkout_question_description[$language['language_id']]) ? $checkout_question_description[$language['language_id']]['question'] : '';?>"  placeholder="Question" id="input-question" class="form-control" /> 

                    </div>
                    <?php if (isset($error_question[$language['language_id']])) { ?>
                    <div class="text-danger"><?php echo $error_question[$language['language_id']]; ?></div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>

             
              
             
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

          </div>
        </form>
      </div>
    </div>
  </div>
 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});
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