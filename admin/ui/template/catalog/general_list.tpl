<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                
            <?php if($is_vendor){ ?>
                <button type="button" onclick="$('.message_wrapper').html('');" type="button" data-target="#store_modal" data-toggle="modal" class="btn btn-default">
                    <i class="fa fa-copy"></i> &nbsp; <?= $button_sell_selected ?>
                </button>

                <button type="button" onclick="$('.message_wrapper').html('');" type="button" data-target="#store_all_modal" data-toggle="modal" class="btn btn-default">
                    <i class="fa fa-copy"></i> &nbsp; <?= $button_all_sell_selected ?>
                </button>

            <?php }else{ ?>
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
            <?php } ?>
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
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_from; ?></label>
                                <input type="text" name="filter_product_id_from" value="<?php echo $filter_product_id_from; ?>" placeholder="<?php echo $entry_product_id_from; ?>" id="input-model" class="form-control" />
                            </div>

                            
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?php echo $entry_product_id_to; ?></label>
                                <input type="text" name="filter_product_id_to" value="<?php echo $filter_product_id_to; ?>" placeholder="<?php echo $entry_product_id_to; ?>" id="input-model" class="form-control" />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="filter_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_status && !is_null($filter_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-category"><?php echo $column_category; ?></label>
                                <select name="filter_category" id="input-category" class="form-control">
                                    <option value="*"></option>
                                    <?php foreach ($categories as $category) { ?>
                                    <?php if ($category['category_id'] == $filter_category) { ?>
                                    <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-center"><?php echo $column_image; ?></td>
                                    <!-- <td class="text-left"><?= $column_id ?></td> -->

                                    <td class="text-left"><?php if ($sort == 'p.product_id') { ?>
                                        <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_product_id; ?>"><?php echo $column_id; ?></a>
                                        <?php } ?></td>


                                    <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?></td>

                                    <td class="text-left"><?php if ($sort == 'p.unit') { ?>
                                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_unit; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_model; ?>"><?php echo $column_unit; ?></a>
                                    <?php } ?></td>


                                    <td class="text-left"><?php if ($sort == 'p.model') { ?>
                                        <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                                        <?php } ?></td>

                                        
                                    <td class="text-left"><?php if ($sort == 'p2c.category_id') { ?>
                                        <a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
                                        <?php } ?></td>	
                                    
                                    <td class="text-left"><?php if ($sort == 'p.status') { ?>
                                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                        <?php } ?></td>
                                    <?php if(!$is_vendor){ ?>
                                    <td class="text-right"><?php echo $column_action; ?></td>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products) { ?>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-center"><?php if ($product['image']) { ?>
                                        <a href="<?php echo $product['bigimage']; ?>" target="_blank"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                                        <?php } else { ?>
                                        <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $product['product_id']; ?></td>
                                    <td class="text-left"><?php echo $product['name']; ?></td>
                                    <td class="text-left"><?php echo $product['unit']; ?></td>
                                    <td class="text-left"><?php echo $product['model']; ?></td>
                                    <td class="text-left"><?php foreach ($categories as $category) { ?>
                                        <?php if (in_array($category['category_id'], $product['category'])) { ?>
                                        <?php echo $category['name'];?><br>
                                        <?php } ?> <?php } ?></td>
                                    
                                    
                                    <td class="text-left">
                                        <?php echo $product['status']; ?>
                                    </td>
                                    <?php if(!$is_vendor){ ?>
                                    <td class="text-right"><a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="modal fade" id="store_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> 
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><?= $text_select_store ?></h4>
                                </div>
                                <div class="modal-body">

                                    <div class="message_wrapper"></div>

                                    <div class="form-group">
                                        <input type="text" name="store" value="" placeholder="Store name" id="input-product" class="form-control" />
                                        <div id="store-list" class="well well-sm" style="max-width: 100%; height: 150px; overflow: auto;">
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $button_close ?></button>
                                    <button onclick="submit_copy();" type="button" class="btn btn-primary"><?= $button_submit ?></button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.modal -->

                    <div class="modal fade" id="store_all_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> 
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><?= $text_select_store ?></h4>
                                </div>
                                <div class="modal-body">

                                    <div class="message_wrapper"></div>

                                    <div class="form-group">
                                        <input type="text" name="store" value="" placeholder="Store name" id="input-product" class="form-control" />
                                        <div id="all_store-list" class="well well-sm" style="max-width: 100%; height: 150px; overflow: auto;">
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $button_close ?></button>
                                    <button onclick="submit_all_copy();" type="button" class="btn btn-primary"><?= $button_submit ?></button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.modal -->


                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
        
$(document).delegate('#store-list .fa-minus-circle','click', function(){
    $(this).parent().remove();
});

$(document).delegate('#all_store-list .fa-minus-circle','click', function(){
    $(this).parent().remove();
});


$('input[name=\'store\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?path=setting/store/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['store_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'store\']').val('');		
        $('#store-list-' + item['value']).remove();		
        $('#store-list').append('<div id="store-list-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_store[]" value="' + item['value'] + '" /></div>');

        $('#all_store-list-' + item['value']).remove();     
        $('#all_store-list').append('<div id="all_store-list-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_store[]" value="' + item['value'] + '" /></div>');	
    }
});

function submit_copy() {
    
    $('.message_wrapper').html('');
    
    $error = '';
    
    if($('input[name="product_store[]"').length == 0){
        $error += '<li>Select store(s).</li>';
    }
    
    if($('input[name="selected[]"]:checked').length == 0){
        $error += '<li>Select products.</li>';
    }
    
    if(!$error){        
        $('form').attr('action','index.php?path=catalog/vendor_product/general_product_copy&token=<?= $token ?>').submit();
    } else{        
        $('.message_wrapper').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close">&times</button><ul class="list list-unstyled">'+$error+'</ul></div>');
    }
}

function submit_all_copy() {
    
    $('.message_wrapper').html('');
    
    $error = '';
    
    if($('input[name="product_store[]"').length == 0){
        $error += '<li>Select store(s).</li>';
    }
    
    
    if(!$error){        
        $('form').attr('action','index.php?path=catalog/vendor_product/all_general_product_copy&token=<?= $token ?>').submit();
    } else{        
        $('.message_wrapper').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close">&times</button><ul class="list list-unstyled">'+$error+'</ul></div>');
    }
}


  $('#button-filter').on('click', function() {

            var url = 'index.php?path=catalog/general&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_model = $('input[name=\'filter_model\']').val();

            if (filter_model) {
                url += '&filter_model=' + encodeURIComponent(filter_model);
            }

            var filter_product_id_to = $('input[name=\'filter_product_id_to\']').val();

            if (filter_product_id_to) {
                url += '&filter_product_id_to=' + encodeURIComponent(filter_product_id_to);
            }

            var filter_product_id_from = $('input[name=\'filter_product_id_from\']').val();

            if (filter_product_id_from) {
                url += '&filter_product_id_from=' + encodeURIComponent(filter_product_id_from);
            }



            var filter_category = $('select[name=\'filter_category\']').val();

            if (filter_category != '*') {
                url += '&filter_category=' + encodeURIComponent(filter_category);
            }

            var filter_price = $('input[name=\'filter_price\']').val();

            if (filter_price) {
                url += '&filter_price=' + encodeURIComponent(filter_price);
            }

            var filter_quantity = $('input[name=\'filter_quantity\']').val();

            if (filter_quantity) {
                url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
            }

            var filter_status = $('select[name=\'filter_status\']').val();

            if (filter_status != '*') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            location = url;
        });
  //--></script> 
    <script type="text/javascript"><!--
  $('input[name=\'filter_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                        
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });

        $('input[name=\'filter_model\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['model'],
                                value: item['product_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_model\']').val(item['label']);
            }
        });
  //--></script></div>

<script type="text/javascript"><!--
function changeStatus(status) {
        $.ajax({
            url: 'index.php?path=common/edit/changeStatus&type=product&status=' + status + '&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $("form[id^='form-']").serialize(),
            success: function(json) {
                if (json) {
                    $('.panel.panel-default').before('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json.warning + '<button type="button" class="close" data-dismiss="alert">×</button></div>');
                }
                else {
                    location.reload();
                }
            }
        });
    }
//--></script>

<?php echo $footer; ?>