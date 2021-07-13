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
                  <td class="text-left"><?php echo $column_order_id; ?></td> 
                 
                  <td class="text-left"><?php echo $column_Company; ?></td>
                  <td class="text-left"><?php echo $column_Customer; ?></td>
                  
                  <td class="text-left"><?php echo $column_issue_type; ?></td>
                  <td class="text-left"><?php echo $column_issue_details; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($customer_issues) { ?>
                <?php foreach ($customer_issues as $customer_issue) { ?>
                <tr>
            <!--<td class="text-center"><?php if (in_array($customer_issue['issue_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer_issue['issue_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer_issue['issue_id']; ?>" />
                    <?php } ?></td>-->
                    <?php if  ($customer_issue['order_id']==0) { ?>
                  <td class="text-left"></td>
                  <?php } else {?>
                  <td class="text-left"><?php echo $customer_issue['order_id']; ?></td>
                  <?php } ?>
                  <td class="text-left"><?php echo $customer_issue['company_name']; ?></td>
                  <td class="text-left"><?php echo $customer_issue['customer_name']; ?></td>


                  <td class="text-left"><?php  echo $customer_issue['issue_type']; ?>  </td>
                  <td class="text-left"><?php  echo $customer_issue['issue_details']; ?> </td>

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