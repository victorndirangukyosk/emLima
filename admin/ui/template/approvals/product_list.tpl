<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-primary" onclick="changeStatus(1)">
                    <i class="fa fa-check-circle"></i> &nbsp; Approve Products
                </button>
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>
                            
                        </div>
                        
                        <div class="col-sm-6">
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
                            
                            <br />
                            <button type="button" id="button-filter" class="btn btn-primary pull-right" style="margin-top: 4px;">
                                <i class="fa fa-search"></i> <?php echo $button_filter; ?>
                            </button>
                            
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
                                    <td class="text-left">ID</td>
                                    <td class="text-left"><?php echo $column_name; ?>
                                      
                                        </td>
                                    <td class="text-left">
                                        
                                        <?php echo $column_model; ?>
                                        </td>
                                    <td class="text-left">
                                    <?php echo $column_category; ?>
                                        
                                    <td class="text-left"><?php echo $column_price; ?>
                                    </td>
                                    <td class="text-right">
                                        <?php echo $column_quantity; ?></a>
                                        </td>
                                    <td class="text-left"><?php echo $column_status; ?></a>
                                        </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products) { ?>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($product['product_store_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_store_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_store_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-center"><?php if ($product['image']) { ?>
                                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                                        <?php } else { ?>
                                        <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $product['product_id']; ?></td>
                                    <td class="text-left"><?php echo $product['name']; ?></td>
                                    <td class="text-left"><?php echo $product['model']; ?></td>
                                    <td class="text-left"><?php foreach ($categories as $category) { ?>
                                        <?php if (in_array($category['category_id'], $product['category'])) { ?>
                                        <?php echo $category['name'];?><br>
                                        <?php } ?> <?php } ?></td>
                                    <td class="text-left">

                                        <?php echo $product['price']; ?>
                                        </td>
                                    <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                                        <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                                        <?php } elseif ($product['quantity'] <= 5) { ?>
                                        <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                                        <?php } else { ?>
                                        <span class="label label-success"><?php echo $product['quantity']; ?></span>
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $product['status']; ?></td>
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
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
  $('#button-filter').on('click', function() {
            var url = 'index.php?path=catalog/product&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_model = $('input[name=\'filter_model\']').val();

            if (filter_model) {
                url += '&filter_model=' + encodeURIComponent(filter_model);
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

<script type="text/javascript">
function changeStatus(status) {
        $.ajax({
            url: 'index.php?path=common/edit/changeStatus&type=vendor_product&status=' + status + '&token=<?php echo $token; ?>',
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
</script>

<?php echo $footer; ?>