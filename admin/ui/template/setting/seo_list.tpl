<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
                                <label class="control-label" for="input-name">
                                    <?= $entry_query ?>
                                </label>
                                <input type="text" name="filter_query" value="<?php echo $filter_query; ?>" placeholder="Query" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-keyword">
                                    <?= $entry_keyword ?>
                                </label>
                                <input type="text" name="filter_keyword" value="<?php echo $filter_keyword; ?>" placeholder="Keyword" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                        
                        <div class="col-sm-4">                    
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-left"><?php if ($sort == 'query') { ?>
                                        <a href="<?php echo $sort_query; ?>" class="<?php echo strtolower($order); ?>"><?= $column_query ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_query; ?>"><?= $column_query ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left"><?php if ($sort == 'keyword') { ?>
                                        <a href="<?php echo $sort_keyword; ?>" class="<?php echo strtolower($order); ?>"><?= $column_keyword ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_keyword; ?>"><?= $column_keyword ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) { ?>
                                <?php foreach ($rows as $row) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($row['url_alias_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $row['url_alias_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $row['url_alias_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $row['query']; ?></td>
                                    <td class="text-left"><?php echo $row['keyword']; ?></td>
                                    <td class="text-right"><a href="<?php echo $row['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
        url = 'index.php?path=setting/seo&token=<?php echo $token; ?>';

        var filter_query = $('input[name=\'filter_query\']').val();

        if (filter_query) {
            url += '&filter_query=' + encodeURIComponent(filter_query);
        }

        var filter_keyword = $('input[name=\'filter_keyword\']').val();

        if (filter_keyword) {
            url += '&filter_keyword=' + encodeURIComponent(filter_keyword);
        }

        location = url;
    });
//--></script>
<?php echo $footer; ?>