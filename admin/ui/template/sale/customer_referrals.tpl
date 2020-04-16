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
        <td class="text-left"><?php echo $column_user_added; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($referrals) { ?>
      <?php foreach ($referrals as $referral) { ?>
      <tr>
        
        <td class="text-left"><a href="<?php echo $referral['customer_id']?>"><?php echo $referral['firstname']. " " .$referral['lastname']; ?></a></td>
        <td class="text-left"><?php echo $referral['date_added']; ?></td>
      </tr>
      <?php } ?>
      
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
