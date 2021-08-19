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
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-5">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>

                            <!-- <?php if(isset($this->request->get['state_id'])) { ?>
                                <a href="#" onclick="return export_state_zipcodes();"><?= $text_export_zipcode ?> </a>
                            <?php } ?> -->
                            

                        </div>
                    </div>   

                    <!-- <div class="tab-pane" id="tab-variation">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_geocode; ?>"><?php echo $entry_zipcode; ?></span></label>
                            <div class="col-sm-10">
                              <input type="text" name="zipcode" value="" placeholder="<?php echo $entry_zipcode; ?>" id="input-product" class="form-control" onKeyDown="if(event.keyCode==13) saveZipcode(this.value);"/>

                              <input type="file" name="upload" id="upload" style="margin-top: 10px;margin-bottom: 10px" />

                              <div id="state_zipcodes" class="well well-sm" style="height: 150px; overflow: auto;">
                                <?if( isset($state_zipcodes) && is_array($state_zipcodes)) {?>

                                    <?php foreach ($state_zipcodes as $state_zipcodes) { ?>
                                    <div id="state_zipcodes<?php echo $state_zipcodes['state_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $state_zipcodes['zipcode']; ?>
                                      <input type="hidden" name="state_zipcodes[]" value="<?php echo $state_zipcodes['zipcode']; ?>" />

                                    </div>
                                    <?php } ?>

                                <?} ?>
                              </div>
                            </div>
                        </div>
                    </div>       -->

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-open"><?= $entry_sort_order ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="Sort order" class="form-control" />
                        </div>
                    </div>
                    
                    <input type="hidden" name="status" value="1" />
                    
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

<script type="text/javascript">
function saveZipcode(zipcode) {
    $('#state_zipcodes').append('<div id="state_zipcodes' + zipcode+ '"><i class="fa fa-minus-circle"></i> ' +zipcode+ '<input type="hidden" name="state_zipcodes[]" value="' + zipcode + '" /></div>'); 
    $('input[name=\'zipcode\']').val('');
}
<!--
// $('input[name=\'zipcode\']').({
    
//          
// });

$('#state_zipcodes').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});

function export_state_zipcodes() {
    
    var state_id = <?php echo  isset($this->request->get['state_id'])?$this->request->get['state_id']:''; ?>

    url = 'index.php?path=localisation/state/export_state_zipcodes&token=<?php echo $token; ?>&state_id='+state_id;
    
    location = url;

    return false;
}

</script>

<?php echo $footer; ?>
