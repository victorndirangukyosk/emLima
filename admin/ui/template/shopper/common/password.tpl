<?php echo $header; ?>

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

<div class="page-header">
    <h2>
        <?php echo $heading_title; ?>
    </h2>    
</div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

    <div class="form-group required">
        <label class="control-label col-sm-3"><?= $entry_password ?></label>
        <div class="col-sm-9">
            <input type="password" name="password" value="<?php echo $password; ?>" class='form-control' />
            <?php if ($error_password) { ?>
            <span class="text-danger"><?php echo $error_password; ?></span>
            <?php } ?>
        </div>
    </div>

    <div class="form-group required">
        <label class="control-label col-sm-3"><?= $entry_confirm_pswd ?></label>
        <div class="col-sm-9">
            <input type="password" name="confirm" value="<?php echo $confirm; ?>" class="form-control" />
            <?php if ($error_confirm) { ?>
            <span class="text-danger"><?php echo $error_confirm; ?></span>
            <?php  } ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-offset-3 col-sm-10">
            <button type="submit" class="btn btn-primary">
                <?= $button_submit ?>
            </button>
        </div>
    </div>

</form>

<?php echo $footer; ?> 