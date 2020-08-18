<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-flutterwave" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-flutterwave" class="form-horizontal">

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-payable">Public key</label>
            <div class="col-sm-10">
              <input type="text" name="flutterwave_public_key" value="<?php echo $flutterwave_public_key; ?>" placeholder="Public key" id="input-payable" class="form-control" />
              <?php if ($error_public_key) { ?>
              <div class="text-danger"><?php echo $error_public_key; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-payable">Secret key</label>
            <div class="col-sm-10">
              <input type="text" name="flutterwave_secret_key" value="<?php echo $flutterwave_secret_key; ?>" placeholder="Secret key" id="input-payable" class="form-control" />
              <?php if ($error_secret_key) { ?>
              <div class="text-danger"><?php echo $error_secret_key; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-payable">Encryption key</label>
            <div class="col-sm-10">
              <input type="text" name="flutterwave_encryption_key" value="<?php echo $flutterwave_encryption_key; ?>" placeholder="Encryption key" id="input-payable" class="form-control" />
              <?php if ($error_encryption_key) { ?>
              <div class="text-danger"><?php echo $error_encryption_key; ?></div>
              <?php } ?>
            </div>
          </div>          

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="flutterwave_total" value="<?php echo $flutterwave_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="flutterwave_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $flutterwave_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status">Order Status Failed</label>
            <div class="col-sm-10">
              <select name="flutterwave_failed_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $flutterwave_failed_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
              
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="flutterwave_status" id="input-status" class="form-control">
                <?php if ($flutterwave_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-flutterwave-environment">
              <span data-toggle="tooltip" data-original-title="<?php echo $help_test; ?>">
                Environment
              </span>
            </label>
            <div class="col-sm-10">
              <select name="flutterwave_environment" id="input-flutterwave-environment" class="form-control">
                <?php if ($flutterwave_environment == 'live') { ?>
                <option value="live" selected="selected">Live</option>
                <?php } else { ?>
                <option value="live">Live</option>
                <?php } ?>
                <?php if ($flutterwave_environment == 'sandbox') { ?>
                <option value="sandbox" selected="selected">Sandbox</option>
                <?php } else { ?>
                <option value="sandbox">Sandbox</option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="flutterwave_sort_order" value="<?php echo $flutterwave_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>