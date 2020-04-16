<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-store" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>        
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
                
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab-general">
                            
                            <?php if(!$this->user->isVendor()) { ?>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name"><?= $text_vendor ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="vendor_name" value="<?php echo $vendor_name; ?>" placeholder="Vendor name" class="form-control" />
                                    <input type="hidden" name="vendor_id" value="<?= $vendor_id ?>" />
                                    <?php if ($error_vendor_id) { ?>
                                    <div class="text-danger"><?php echo $error_vendor_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php if (!$this->user->isVendor()): ?>
                                
                            
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-commision"><?php echo $entry_commision; ?></label>
                                <div class="col-sm-10">
                                    <input type="number" name="commision" value="<?php echo $commision; ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
                                    <?php if ($error_commision) { ?>
                                    <div class="text-danger"><?php echo $error_commision; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php endif ?>

                            <?php } ?>
                            
                            <button type="submit"> Create Sub-Account</button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>

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

<script>

    $(function(){
        $('input[name=\'vendor_name\']').autocomplete({
            'source': function(request, response) {                
                    $.ajax({
                            url: 'index.php?path=setting/store/vendor_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',           
                            success: function(json) {
                                    response($.map(json, function(item) {
                                            return {
                                                    label: item['name'],
                                                    value: item['user_id']
                                            }
                                    }));
                            }
                    });
            },
            'select': function(item) {
                    $('input[name=\'vendor_name\']').val(item['label']);
                    $('input[name=\'vendor_id\']').val(item['value']);
            }   
        });

        $('.time').datetimepicker({
            pickDate: false,
            format: 'hh:mma'
        });

        $('.time_diff').datetimepicker({
            pickDate: false,
            format: 'HH:mm',
        });


    });
    
    
    window.onload = function(){
        $('a[href="#tab-general"]').trigger('click');
    }


$(document).delegate('.remove','click', function(){
    $(this).parent().parent().remove();
});



</script>

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