<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer-ban-ip">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
                  <td class="text-left"><?php echo $column_Company; ?></td>
                  <td class="text-left"><?php echo $column_Customer; ?></td>
                 
                  <td class="text-left"><?php echo $column_rating; ?></td>
                  
                  <td class="text-left"><?php echo $column_feedback_type; ?></td>
                  <td class="text-left"><?php echo $column_comments; ?></td>
                  <!--<td class="text-left"><?php echo $column_order_id; ?></td>-->
                </tr>
              </thead>
              <tbody>
                <?php if ($customer_feedbacks) { ?>
                <?php foreach ($customer_feedbacks as $customer_feedback) { ?>
                <tr>
            <!--<td class="text-center"><?php if (in_array($customer_feedback['feedback_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer_feedback['feedback_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer_feedback['feedback_id']; ?>" />
                    <?php } ?></td>-->
                  <td class="text-left"><?php echo $customer_feedback['company_name']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['customer_name']; ?></td>

                  <td class="text-left"><?php echo $customer_feedback['rating']; ?></td>

                  <td class="text-left"><?php  echo $customer_feedback['feedback_type']; ?>  </td>
                  <td class="text-left"><?php  echo $customer_feedback['comments']; ?> </td>
                  <!--<td class="text-left"><?php echo $customer_feedback['order_id']; ?></td>-->

                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 