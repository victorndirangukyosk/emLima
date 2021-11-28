<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1>Shopper's timeslots</h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i>
                    <?= $text_heading ?>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-timeslots" class="form-horizontal">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-sunday" data-toggle="tab"><?= $tab_sunday ?></a></li>
                        <li><a href="#tab-monday" data-toggle="tab"><?= $tab_monday ?></a></li>
                        <li><a href="#tab-tuesday" data-toggle="tab"><?= $tab_tuesday ?></a></li>
                        <li><a href="#tab-wednesday" data-toggle="tab"><?= $tab_wesnesday ?></a></li>
                        <li><a href="#tab-thursday" data-toggle="tab"><?= $tab_thursday ?></a></li>
                        <li><a href="#tab-friday" data-toggle="tab"><?= $tab_friday ?></a></li>
                        <li><a href="#tab-saturday" data-toggle="tab"><?= $tab_saturday ?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-sunday">
                            <div class="table-responsive">
                                <table id="timeslots_0" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sunday_row = 0; ?>
                                        <?php foreach ($timeslots[0] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[0][<?php echo $sunday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[0][<?php echo $sunday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $sunday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(0);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-monday">
                            <div class="table-responsive">
                                <table id="timeslots_1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $monday_row = 0; ?>
                                        <?php foreach ($timeslots[1] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[1][<?php echo $monday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[1][<?php echo $monday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $monday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(1);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-tuesday">
                            <div class="table-responsive">
                                <table id="timeslots_2" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tuesday_row = 0; ?>
                                        <?php foreach ($timeslots[2] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[2][<?php echo $tuesday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[2][<?php echo $tuesday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $tuesday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(2);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-wednesday">
                            <div class="table-responsive">
                                <table id="timeslots_3" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $wednesday_row = 0; ?>
                                        <?php foreach ($timeslots[3] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[3][<?php echo $wednesday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[3][<?php echo $wednesday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $wednesday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(3);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-thursday">
                            <div class="table-responsive">
                                <table id="timeslots_4" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $thursday_row = 0; ?>
                                        <?php foreach ($timeslots[4] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[4][<?php echo $thursday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[4][<?php echo $thursday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $thursday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(4);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-friday">
                            <div class="table-responsive">
                                <table id="timeslots_5" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $friday_row = 0; ?>
                                        <?php foreach ($timeslots[5] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[5][<?php echo $friday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[5][<?php echo $friday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $friday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(5);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-saturday">
                            <div class="table-responsive">
                                <table id="timeslots_6" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><?= $column_from ?></td>
                                            <td class="text-left"><?= $column_to ?></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $saturday_row = 0; ?>
                                        <?php foreach ($timeslots[6] as $timeslot) { ?>
                                        <tr>
                                            <td class="text-left"><input type="text" name="timeslots[6][<?php echo $saturday_row; ?>][from_time]" value="<?php echo $timeslot['from_time']; ?>" placeholder="From time" class="form-control" /></td>
                                            <td class="text-left"><input type="text" name="timeslots[6][<?php echo $saturday_row; ?>][to_time]" value="<?php echo $timeslot['to_time']; ?>" placeholder="To time" class="form-control" /></td>
                                            <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $saturday_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addTimeslot(6);" data-toggle="tooltip" title="Add Timeslots" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- END .tab-content -->                

                </form>    
            </div><!-- END .panel-body -->      
        </div><!-- END .panel -->
    </div><!-- END .container-fluid -->
</div><!-- END #content -->

<script type="text/javascript"><!--
    var row_counts = [];
    row_counts[0] = <?= $sunday_row ?>;
    row_counts[1] = <?= $monday_row ?>;
    row_counts[2] = <?= $tuesday_row ?>;
    row_counts[3] = <?= $wednesday_row ?>;
    row_counts[4] = <?= $thursday_row ?>;
    row_counts[5] = <?= $friday_row ?>;
    row_counts[6] = <?= $saturday_row ?>;
    
    function addTimeslot($day){
        $row_count = row_counts[$day];
        
        $html  = '<tr>';
        $html += '<td class="text-left"><input type="text" name="timeslots['+$day+']['+$row_count+'][from_time]" value="" placeholder="From time" class="form-control" /></td>';
        $html += '<td class="text-left"><input type="text" name="timeslots['+$day+']['+$row_count+'][to_time]" value="" placeholder="To time" class="form-control" /></td>';
        $html += '<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        $html += '</tr>';

        $('#timeslots_'+$day+' tbody').append($html);
        
        row_counts[$day]++;
        
        $('#timeslots_'+$day+' tr:last-child input').datetimepicker({
            pickDate: false,
            format: 'HH:mm'
        });
    }
    
    $(document).delegate('.btn-remove','click', function(){
        $(this).parents('tr').remove();
    });
    
    $('#form-timeslots input').datetimepicker({
        pickDate: false,
        format: 'HH:mm'
    });
    
</script>

<script type="text/javascript"><!--
function save(type) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'button';
        input.value = type;
        form = $("form[id^='form-']").append(input);
        form.submit();
    }
//--></script>
<?php echo $footer; ?> 