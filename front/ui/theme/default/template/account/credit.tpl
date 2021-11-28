<?php echo $header; ?>
<div class="container" id="main">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="account-section <?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="secion-row">  
      <div class="title">
          <?php echo $heading_title; ?>
      </div>
      <p><?php echo $text_total; ?> <b><?php echo $total; ?></b>.</p>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-left"><?php echo $column_description; ?></td>
              <td class="text-right"><?php echo $column_amount; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($credits) { ?>
            <?php foreach ($credits  as $credit) { ?>
            <tr>
              <td class="text-left"><?php echo $credit['date_added']; ?></td>
              <td class="text-left"><?php echo $credit['description']; ?></td>
              <td class="text-right"><?php echo $credit['amount']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <br />
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary btn-orange"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
        </div>
    <?php echo $column_right; ?></div>
  
</div>
<?php echo $footer; ?>