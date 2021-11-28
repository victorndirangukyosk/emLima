<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-store" data-toggle="tooltip" title="Save" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i><?= $text_testimonials ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
             
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-name"><?= $entry_name ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Enter name" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-logo"><?= $entry_image ?></label>
                        <div class="col-sm-10">
                            <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                                <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                            </a>
                            <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-message"><?= $entry_message ?></label>
                        <div class="col-sm-10">
                            <textarea name="message" rows="5" placeholder="Enter message" id="input-message" class="form-control"><?php echo $message; ?></textarea>
                            <?php if ($error_message) { ?>
                            <div class="text-danger"><?php echo $error_message; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort_order"><?= $entry_sort_order ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="Enter sort order" id="input-sort_order" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-currency"><?= $entry_status ?></label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control">
                                <?php if ($status) { ?>
                                <option value="1" selected="selected"><?= $text_enable ?></option>
                                <option value="0"><?= $text_disable ?></option>
                                <?php } else { ?>
                                <option value="1"><?= $text_enable ?></option>
                                <option value="0" selected="selected"><?= $text_disable ?></option>
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