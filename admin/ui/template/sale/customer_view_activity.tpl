<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left">Comment</td>
        <td class="text-right">IP</td>
        <td class="text-left">Date Added</td>
        <td class="text-left">Order ID</td>
      </tr>
    </thead>
    <tbody>
      <?php if ($activities) { ?>
      <?php foreach ($activities as $activity) { ?>
      <tr>
        <td class="text-left"><?php echo $activity['comment']; ?></td>
        <td class="text-left"><?php echo $activity['ip']; ?></td>
        <td class="text-left"><?php echo $activity['date_added']; ?></td>
        <td class="text-left"><?php echo $activity['order_id']; ?></td>
      </tr>
      <?php } ?>
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