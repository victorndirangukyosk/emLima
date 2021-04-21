<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" onclick="save('new')" form="form-user" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-user" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>			
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">

        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                                    <?php if ($error_product) { ?>
                                    <div class="text-danger"><?php echo $error_product; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-unit"><?php echo $entry_unit; ?></label>
                                <div class="col-sm-10">
                                    <select name="product_unit" id="product_unit" class="form-control">
                                    </select>
                                    <?php if ($error_unit) { ?>
                                    <div class="text-danger"><?php echo $error_unit; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" readonly="" />
                                    <?php if ($error_price) { ?>
                                    <div class="text-danger"><?php echo $error_price; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_quantity; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                                    <?php if ($error_quantity) { ?>
                                    <div class="text-danger"><?php echo $error_quantity; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="total" value="<?php echo $total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                                    <?php if ($error_total) { ?>
                                    <div class="text-danger"><?php echo $error_total; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('input[name=\'product\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/farmer_transactions/product_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['product_store_id'],
                                price : item['special_price']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
            
            console.log(item.value);
            $('input[name=\'product\']').val(item['label']);
            $.ajax({
                url: 'index.php?path=sale/farmer_transactions/getProductVariantsInfo&product_store_id='+item.value+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    var $select = $('#product_unit');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $.each(json, function(index, value) {
                    $select.append('<option value="' + value.product_store_id + '" data-special-price="' + value.special_price + '">' + value.unit + '</option>');
                    });
                    }
                    $('.selectpicker').selectpicker('refresh');
                }
            });
            $('input[name=\'price\']').val(item.price);
            }
        });

$('select[name=\'product_unit\']').on('change', function (e) {
 var special_price = $('select[name=\'product_unit\']').find('option:selected').attr('data-special-price');
 $('input[name=\'price\']').val(special_price);
 var total = special_price.replace(',', '')*$('input[name=\'quantity\']').val();
 $('input[name=\'total\']').val(total);
});
</script>
<script type="text/javascript"><!--
function save(type) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'button';
        input.value = type;
        assign_customers();
        form = $("form[id^='form-']").append(input);
        form.submit();
    }
//--></script>
<?php echo $footer; ?> 