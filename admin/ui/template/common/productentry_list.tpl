<?php echo $header; ?><?php echo $column_left; ?>
<br>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
             

                <!--<button type="button" data-toggle="tooltip" title="Download" class="btn btn-success"><i class="fa fa-download"></i></button>-->
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirmnew('Are you sure to delete ?') ? $('#form-product-entry').submit() : false;"><i class="fa fa-trash-o"></i></button>


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
                                <label class="control-label" for="input-source"><?php echo $entry_source; ?></label>
                                <input type="text" name="filter_source" value="<?php echo $filter_source; ?>" placeholder="<?php echo $entry_source; ?>" id="input-source" class="form-control" />
                            </div>

                            
                        </div>
                        
                        <div class="col-sm-4">
                           <!-- <div class="form-group">
                                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                            </div>-->

                            <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                          </div>


                            <div class="form-group">    
                                <label class="control-label" for="input-date-added-end"><?php echo $entry_date_added_end; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added_end" value="<?php echo $filter_date_added_end; ?>" placeholder="<?php echo $entry_date_added_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>



                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

                        </div>

                        
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product-entry">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>

                                    <td class="text-left"><?php if ($sort == 'p.product_entry_id') { ?>
                                        <a href="<?php echo $sort_product_entry_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_product_entry_id; ?>"><?php echo $column_id; ?></a>
                                        <?php } ?></td>


                                    <td class="text-left"><?php if ($sort == 'p.product_name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?></td>

                                    <td class="text-left">
                                    <?php echo $column_unit; ?>
                                    </td>


                                    <td class="text-left"><?php if ($sort == 'p.source') { ?>
                                        <a href="<?php echo $sort_source; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_source; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_source; ?>"><?php echo $column_source; ?></a>
                                        <?php } ?></td>

                                        
                                     
                                    
                                    <td class="text-left"><?php if ($sort == 'p.quantity') { ?>
                                        <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                                        <?php } ?></td>

                                          <td class="text-left"><?php if ($sort == 'p.price') { ?>
                                        <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                                        <?php } ?></td>
                                    
                                    <td class="text-right"><?php echo $column_action; ?></td>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products) { ?>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($product['product_entry_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_entry_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['product_entry_id']; ?>" />
                                        <?php } ?></td>
                                    
                                    <td class="text-left"><?php echo $product['product_entry_id']; ?></td>
                                    <td class="text-left"><?php echo $product['name']; ?></td>
                                    <td class="text-left"><?php echo $product['unit']; ?></td>
                                    <td class="text-left"><?php echo $product['source']; ?></td>
                                    <td class="text-left"><?php echo $product['quantity']; ?></td>
                                    <td class="text-left"><?php echo $product['price']; ?></td>
                                    <td class="text-right"><a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
        
    

    $('.date').datetimepicker({
            pickTime: false,
     widgetParent: 'body'

        });


  $('#button-filter').on('click', function() {

            var url = 'index.php?path=common/productentry';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_source = $('input[name=\'filter_source\']').val();

            if (filter_source) {
                url += '&filter_source=' + encodeURIComponent(filter_source);
            }

           

            //var filter_price = $('input[name=\'filter_price\']').val();

            //if (filter_price) {
              //  url += '&filter_price=' + encodeURIComponent(filter_price);
            //}

            //var filter_quantity = $('input[name=\'filter_quantity\']').val();

           // if (filter_quantity) {
             //   url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
            //}

             var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end) {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
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



<?php echo $footer; ?>