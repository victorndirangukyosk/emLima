<?php echo $header; ?>

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

<div class="page-header">
    <h2>
        <?= $text_heading ?>
        <div class='buttons-wrap pull-right'>
            <button form="form-request" class="btn btn-primary" data-toggle='tooltip' data-title='Invoice' id="btn-invoice" formtarget="_blank">
                <i class="fa fa-print"></i>
            </button>
        </div>
    </h2>    
</div>

<form id="form-request" action="<?= $invoice ?>" method="post">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 1px;" class="text-center">
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                </th>
                <th class="visible-xs">
                   <?= $text_details ?> 
                </th>
                <th class="hidden-xs">
                    <?php if ($sort == 'o.vendor_order_id') { ?>
                    <a href="<?php echo $sort_vendor_order_id; ?>" class="<?php echo strtolower($order); ?>"><?= $column_vendor_order_id ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_vendor_order_id; ?>"><?= $column_vendor_order_id ?></a>
                    <?php } ?>
                </th>
                <th class="hidden-xs">
                    <?php if ($sort == 'o.store_name') { ?>
                    <a href="<?php echo $sort_store_name; ?>" class="<?php echo strtolower($order); ?>"><?= $column_store ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_store_name; ?>"><?= $column_store ?></a>
                    <?php } ?>
                </th>
                <th class="hidden-xs">
                    <?php if ($sort == 'o.delivery_date') { ?>
                    <a href="<?php echo $sort_delivery_date; ?>" class="<?php echo strtolower($order); ?>"><?= $column_delivery_date ?>
                    <?php } else { ?>
                    <a href="<?php echo $sort_delivery_date; ?>"><?= $column_delivery_date ?></a>
                    <?php } ?>
                </th>
                <th class="hidden-xs"><?= $column_delivery_timeslot ?>
                <th class="hidden-xs">
                    <?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?>
                </th>
                <th class="hidden-xs">
                    <?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?= $column_total ?>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?= $column_total ?></a>
                    <?php } ?>
                </th>
                <th class="text-center"><?= $column_action ?>
            </tr>
        </thead>
        <tbody>
            <?php if($orders){ ?>
            <?php foreach($orders as $order){ ?>
            <tr>
                <td class="text-center"><?php if (in_array($order['vendor_order_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['vendor_order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['vendor_order_id']; ?>" />
                    <?php } ?>
                </td>
                
                <td class="visible-xs">                   
                    #ID : <?= $order['vendor_order_id'] ?> <br />
                    <?= $order['store_name'] ?>, <br />
                    <?= $order['delivery_date'] ?>, <br />
                    <?= $order['delivery_timeslot'] ?>, <br />
                    <?= $order['total'] ?>                    
                </td>
                <td class="hidden-xs"><?= $order['vendor_order_id'] ?></td>
                <td class="hidden-xs"><?= $order['store_name'] ?></td>
                <td class="hidden-xs"><?= $order['delivery_date'] ?></td>
                <td class="hidden-xs"><?= $order['delivery_timeslot'] ?></td>
                <td class="hidden-xs"><?= $order['status'] ?></td>
                <td class="hidden-xs"><?= $order['total'] ?></td>
                <td class="text-center">
                    
                    <?php if(in_array($order['order_status_id'], $config_processing_status)) { ?>
                        <a class="btn btn-primary" href="<?= $order['track'] ?>" data-toggle="tooltip" data-title="Start tracking">
                            <i class="fa fa-play"></i>
                        </a>
                    <?php }elseif($order['shopper_commision'] > 0) { ?>
                        <a class="btn btn-info" href="<?= $order['track_info'] ?>" data-toggle="tooltip" data-title="Track info">
                            <i class="fa fa-info"></i>
                        </a>
                    <?php }else{ ?>                    
                        -
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
    
<?php if ($orders) { ?>
<div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
<?php } ?>

<?php echo $footer; ?>