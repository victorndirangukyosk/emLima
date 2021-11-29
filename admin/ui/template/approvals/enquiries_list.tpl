<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are You Sure?') ? $('#form-row').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                <h3 class="panel-title"><i class="fa fa-list"></i><?= $heading_text ?></h3>
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
                                    <td class="text-left"><?= $column_name ?></td>
                                    <td class="text-left"><?= $column_email ?></td>
                                    <td class="text-right"><?= $column_date ?></td>
                                    <td class="text-right"><?= $column_action ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($results) { ?>
                                <?php foreach ($results as $row) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($row['enquiry_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $row['enquiry_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $row['enquiry_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                                    <td class="text-left"><?php echo $row['email']; ?></td>
                                    <td class="text-right"><?php echo date('d-m-Y', strtotime($row['date_added'])); ?></td>
                                    <td class="text-right">
                                        <a data-href="<?php echo $row['approve']; ?>" data-toggle="modal" data-target='#approve_model' title="Approve" class="btn-approve-form btn btn-primary">
                                            <i class="fa fa-check"></i>
                                        </a>
                                        <a target="_blank" href="<?php echo $row['view']; ?>" data-toggle="tooltip" title="View" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
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
                    <div class="col-sm-6 text-right"><?php echo $pagination_results; ?></div>
                </div>

                <div class="modal fade" id="approve_model">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>

<script>
$('.btn-approve-form').click(function(){
    $.get($(this).attr('data-href'), function(data){
        $('#approve_model .modal-content').html(data);
    });    
});
</script>