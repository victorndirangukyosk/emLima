<?php echo $header; ?><?php echo $column_left; ?>
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
                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>		
            </div>
            <div class="panel-body">
                <div class="well" style="display:none;">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_package ?></label>
                                <input type="text" name="filter_package" value="<?php echo $entry_package; ?>" placeholder="Package name"  class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_vendor ?></label>
                                <input type="text" name="filter_vendor" value="<?php echo $entry_vendor; ?>" placeholder="Package vendor"  class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_transaction_no ?></label>
                                <input type="text" name="filter_transaction_no" value="<?php echo $entry_transaction_no; ?>" placeholder="Transaction no"  class="form-control" autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="row">    
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_amount ?></label>
                                <input type="text" name="filter_amount" value="<?php echo $entry_amount; ?>" placeholder="Amount"  class="form-control" autocomplete="off" />
                            </div>
                        </div>           
                        <div class="col-sm-6"></div>
                        <div class="col-sm-2">
                            <br />
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><?= $column_package ?></td>
                                    <td><?= $column_vendor ?></td>
                                    <td><?= $column_transaction_no ?></td>
                                    <td><?= $column_amount ?></td>
                                    <td><?= $column_date_added ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) { ?>
                                <?php foreach ($rows as $row) { ?>
                                <tr>
                                    <td class="left"><?php echo $row['package']; ?></td>
                                    <td class="left"><?php echo $row['vendor']; ?></td>
                                    <td class="left"><?php echo $row['transaction_no']; ?></td>
                                    <td class="left"><?php echo $row['amount']; ?></td>
                                    <td class="left"><?php echo date('d-m-y', strtotime($row['date_added'])); ?></td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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

<script type="text/javascript">
    
$('#button-filter').on('click', function() {
    
    url = 'index.php?path=transactions/package&token=<?php echo $token; ?>';

    var filter_package = $('input[name=\'filter_package\']').val();

    if (filter_package) {
        url += '&filter_package=' + encodeURIComponent(filter_package);
    }
    
    var filter_vendor = $('input[name=\'filter_vendor\']').val();

    if (filter_vendor) {
        url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
    }
    
    var filter_amount = $('input[name=\'filter_amount\']').val();

    if (filter_amount) {
        url += '&filter_amount=' + encodeURIComponent(filter_amount);
    }

    var filter_transaction_no = $('input[name=\'filter_transaction_no\']').val();

    if (filter_transaction_no) {
        url += '&filter_transaction_no=' + encodeURIComponent(filter_transaction_no);
    }
    
    location = url;
});

</script>
    
<?php echo $footer; ?>