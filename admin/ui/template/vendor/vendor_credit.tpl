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
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?= $column_order_id ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-right"><?php echo $column_amount; ?></td>
        <td class="text-right"><?php echo $column_invoice; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($credits) { ?>
      <?php foreach ($credits as $credit) { ?>
      <tr>
        <td class="text-left"><?php echo $credit['date_added']; ?></td>
        <td class="text-left"><?php echo $credit['order_id']; ?></td>
        <td class="text-left"><?php echo $credit['description']; ?></td>
        <td class="text-right"><?php echo $credit['amount']; ?></td>
        <td class="text-right"> <?php if(!empty($credit['invoice'])) { ?> <a href="<?php echo $credit['invoice']; ?>"> [<?php echo $column_invoice; ?>] </a> <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="2">&nbsp;</td>
        <td class="text-right"><b><?php echo $text_balance; ?></b></td>
        <td class="text-right"><?php echo $balance; ?></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
