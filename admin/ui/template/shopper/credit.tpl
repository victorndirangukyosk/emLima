<?php echo $header; ?>


<div class="page-header">
    <h2><?= $text_wallet ?></h2>    
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?=  $column_order_id ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-right"><?php echo $column_amount; ?></td>
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

<?php echo $footer; ?>