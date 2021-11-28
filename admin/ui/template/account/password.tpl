<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <button onclick="$('#form').submit();" type="submit" form="form-paytm" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
      </div>
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
      
      <div class="panel-body">
          
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          
           <div class="form-group required">
            <label class="control-label col-sm-3"><?= $column_password ?></label>
            <div class="col-sm-9">
              <input type="password" name="password" value="<?php echo $password; ?>" class='form-control' />
              <?php if ($error_password) { ?>
              <span class="text-danger"><?php echo $error_password; ?></span>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="control-label col-sm-3"><?= $column_confirm_pswd ?></label>
            <div class="col-sm-9">
                <input type="password" name="confirm" value="<?php echo $confirm; ?>" class="form-control" />
              <?php if ($error_confirm) { ?>
              <span class="text-danger"><?php echo $error_confirm; ?></span>
              <?php  } ?>
            </div>
          </div>
          
      </form>
    </div>
  </div>
</div>
</div>

<?php echo $footer; ?> 