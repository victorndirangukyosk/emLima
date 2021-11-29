<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-paytm" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      <div class="panel-body">          
          
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paytm" class="form-horizontal">
              
          <div class="form-group required">
            <label class="control-label col-sm-3" for="input-merchant-id">
                <span data-toggle="tooltip" title="Payu Merchant ID">Merchant ID</span></label>
            <div class="col-sm-9">
              <input type="text" name="payu_merchant" value="<?php echo $payu_merchant; ?>" class="form-control"/>
              <?php if ($error_merchant) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="control-label col-sm-3" for="input-salt">
                <span data-toggle="tooltip" title="Payu Salt">Salt</span></label>
            <div class="col-sm-9">
              <input type="text" name="payu_salt" value="<?php echo $payu_salt; ?>" class="form-control"/>
              <?php if ($error_salt) { ?>
              <span class="error"><?php echo $error_salt; ?></span>
              <?php } ?>
            </div>
          </div>
          
              
         <div class="form-group required">
            <label class="control-label col-sm-3" for="input-salt">
                <span data-toggle="tooltip" title="To avoid duplicate order id">Payu order prefix</span></label>
            <div class="col-sm-9">
              <input type="text" name="payu_order_prefix" value="<?php echo $payu_order_prefix; ?>" class="form-control"/>
            </div>
          </div>
              
           <div class="form-group required">
            <label class="control-label col-sm-3" for="input-salt">Mode</label>
            <div class="col-sm-9">
              	<select name="payu_test">                
                    <option value="live" <?php echo ($payu_test == 'live' ? ' selected="selected"' : '')?>> live</option>                
                    <option value="demo" <?php echo ($payu_test == 'demo' ? ' selected="selected"' : '')?>> demo</option>                
		</select>
            </div>
          </div>

          <div class="form-group">
              <label class="control-label col-sm-3" for="input-total">
                  <span data-toggle="tooltip" title="The checkout total the order must reach before this payment method becomes active.">Total</span>
              </label>
              <div class="col-sm-9">
                  <input type="text" name="payu_total" value="<?php echo $payu_total; ?>" class='form-control' />
              </div>
          </div>
          
          <div class="form-group">
              <label class="control-label col-sm-3" for="input-status">
                  <?php echo $entry_order_status; ?>
              </label>
              <div class="col-sm-9">
                <select name="payu_order_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $payu_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
              </div>
          </div>
          
          <div class="form-group">
              <label class="control-label col-sm-3" for="input-status">
                <?php echo $entry_status; ?>
              </label>
              <div class="col-sm-9">
                  <select name="payu_status" class="form-control">
                <?php if ($payu_status) { ?>
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
              <label class="control-label col-sm-3" for="input-status">
                <?php echo $entry_sort_order; ?>
              </label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" name="payu_sort_order" value="<?php echo $payu_sort_order; ?>" size="1" />
              </div>
          </div>          
      </form>
          
    </div>
  </div>
  </div>
</div>
<?php echo $footer; ?> 