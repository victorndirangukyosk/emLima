<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-attribute" data-toggle="tooltip" title="Save"
                    class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>

                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default"><i
                        class="fa fa-times-circle text-danger"></i></a></div>
            <h1>Email Group</h1>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-email-group"
                    class="form-horizontal">
                    <div class="form-group">
                        <input type="hidden" name="group-id"
                                value="<?php echo isset($group['id']) ? $group['id'] : ''; ?>"
                                id="group-id" class="form-control"/>
                        <label class="col-sm-2 control-label" for="input-question">Group Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="group-name"
                                value="<?php echo isset($group['name']) ? $group['name'] : ''; ?>"
                                placeholder="Group Name" id="group-name" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-question">Group Description</label>
                        <div class="col-sm-10">
                            <textarea type="text" name="group-description" placeholder="Group Description" 
                            id="group-description" class="form-control"><?php echo isset($group['description']) ? $group['description'] : ''; ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function save(type) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'button';
        input.value = type;
        form = $("form[id^='form-']").append(input);
        form.submit();
    }
</script>

<?php echo $footer; ?>