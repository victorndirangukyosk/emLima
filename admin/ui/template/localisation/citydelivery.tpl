<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" onclick="save('save')" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="button" onclick="save('saveclose')" form="form-location" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="button" onclick="save('new')" form="form-location" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>		
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
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-location" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-5">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" readonly="" />
                        </div>
                    </div>
                    <?php print_r($city_delivery_info); ?>
                    <div class="form-group">
                       <label class="col-sm-2 control-label">Delivery Days</label> 
                       <div class="col-sm-10">
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="monday" checked="checked">Monday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="tuesday" checked="checked">Tuesday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="wednesday" checked="checked">Wednesday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="thursday" checked="checked">Thursday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="friday" checked="checked">Friday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="saturday" checked="checked">Saturday</label>
                           </div>
                           <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="city_delivery[]" value="sunday" checked="checked">Sunday</label>
                           </div>
                       </div>
                    </div>
                    
                    
                </form>
            </div>
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
//--></script>

<?php echo $footer; ?>
