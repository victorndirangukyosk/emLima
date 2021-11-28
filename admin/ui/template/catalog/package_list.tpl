<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-row').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-row">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center">
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?= $column_name ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?= $column_name ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'amount') { ?>
                    <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?= $column_amount ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_amount; ?>"><?= $column_amount ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?= $column_benefits ?></td>
                  <td class="text-right"><?= $column_status ?></td>
                  <td class="text-right"><?= $column_priority ?></td>
                  <td class="text-right"><?= $column_date ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php                 
                if ($results) { ?>
                <?php foreach ($results as $row) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($row['package_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $row['package_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $row['package_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $row['name']; ?></td>
                  <td class="text-left"><?php echo $row['amount']; ?></td>
                  <td class="text-left">
                      <?php echo $row['free_year']; ?> Year
                      <?php echo $row['free_month']; ?> Month
                  </td>                  
                  <td class="text-right">
                      <?php if($row['status']){  ?>
                        Enable
                      <?php }else{ ?>
                        Disable
                      <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $row['priority']; ?></td>
                  <td class="text-right"><?php echo date('d-m-Y', strtotime($row['date_added'])); ?></td>
                  <td class="text-right">
                      <a href="<?php echo $row['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary">
                          <i class="fa fa-pencil"></i>
                      </a>
                  </td>
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $page_results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>