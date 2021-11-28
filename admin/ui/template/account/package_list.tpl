<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    
    <div class="page-header">
    <div class="container-fluid">
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>	
      </div>
      <div class="panel-body">          
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="left"><?= $column_name ?></td>              
              <td class="left"><?= $column_price ?></td>
              <td class="left"><?= $column_benefits ?></td>
              <td class="left"><?= $column_priority ?></td>
              <td class="left"><?= $column_date ?></td>
              <td class="right"><?= $column_action ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($results) { ?>
            <?php foreach ($results as $row) { ?>
            <tr>
              <td class="left"><?php echo $row['name']; ?></td>              
              <td class="left"><?php echo $row['amount']; ?></td>
              <td class="left"><?php echo $row['free_year'].' Year '.$row['free_month'].' Month' ; ?></td>
              <td class="left"><?= $row['priority'] ?></td>
              <td class="left">
                  <?= date('d-m-Y', strtotime($row['date_added'])) ?>
              </td>
              <td class="right">
                  [ <a href="/index.php?path=account/packages/pay&package_id=<?= $row['package_id'] ?>&token=<?php echo $this->session->data['token']; ?>">
                        <?= $text_pay ?>
                    </a> ]                
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $page_results; ?></div>
      </div>
    </div><!-- END .panel-body -->
  </div><!-- END panel -->
</div><!-- END container -->
</div><!-- END #content -->
<?php echo $footer; ?> 