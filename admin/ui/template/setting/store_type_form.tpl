<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
        
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
            <div class="tab-pane">

                

                <ul class="nav nav-tabs" id="seo-language">
                    <?php foreach ($languages as $language) { ?>
                        <li><a href="#seo-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>"  onerror="this.onerror=null; this.src='../image/cache/placeholder-226x226.png'  width="15px""  title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    
                    <?php foreach ($languages as $language) { ?>

                        <div class="tab-pane" id="seo-language<?php echo $language['language_id']; ?>">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name"><?= $column_name ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="help[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($help[$language['language_id']]) ? $help[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                                      <?php if (isset($error_name[$language['language_id']])) { ?>
                                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                                      <?php } ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $column_sort_order ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="help[<?php echo $language['language_id']; ?>][sort_order]" value="<?php echo isset($help[$language['language_id']]) ? $help[$language['language_id']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                                </div>
                            </div>
                        </div>
                    <?php } ?> 
                </div>  
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#seo-language a:first').tab('show');
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
//--></script>
            
<?php echo $footer; ?>