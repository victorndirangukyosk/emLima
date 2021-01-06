<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?php if (!$this->user->isVendor()): ?>
                        <button type="" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default"><i class="fa fa-truck"></i></button>
                <?php endif ?>  

                
                <button type="" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></button>

               <?php if (!$this->user->isVendor()): ?>
                        <!-- <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a> -->
                <?php endif ?>  
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
        <div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        
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
                                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                            </div>

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                    <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                                </div>
                            <?php endif ?> 
                            
                            
                            
                            <!--<div class="form-group">
                                <label class="control-label" for="input-customer"><?= $entry_city ?></label>
                                <input type="text" name="filter_city" value="<?php echo $filter_city; ?>" class="form-control" />
                            </div>-->

                             <div class="form-group">
                                <label class="control-label" for="input-name"><?= $column_delivery_method ?></label>
                                <input type="text" name="filter_delivery_method" value="<?php echo $filter_delivery_method; ?>" placeholder="<?php echo $column_delivery_method; ?>" id="input-name" class="form-control" />
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="control-label" for="input-delivery-date">Delivery Date</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" placeholder="<?php echo $column_delivery_date; ?>" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

                            
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <select name="filter_order_status" id="input-order-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_order_status == '0') { ?>
                                    <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_missing; ?></option>
                                    <?php } ?>
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                            </div>

                            <!--<div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div>-->

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-name"><?= $column_payment ?></label>
                                    <input type="text" name="filter_payment" value="<?php echo $filter_payment; ?>" placeholder="<?php echo $column_payment; ?>" id="input-name" class="form-control" />
                                </div>
                            <?php endif ?> 


                        </div>



                        <div class="col-sm-4">

                           <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>

                            <?php if(!$this->user->isVendor()){ ?>  
                            <!--<div class="form-group">
                                <label class="control-label" for="input-model"><?= $text_vendor ?></label>
                                <input type="text" name="filter_vendor" value="<?php echo $filter_vendor; ?>" placeholder="<?php echo $text_vendor; ?>" id="input-model" class="form-control" />
                            </div>-->
                            <?php } ?>


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
                            <!--<div class="form-group">
                                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>-->
                            
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

                        </div>

                        
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"  name="selected[]"/>
                                    </td>
                                    <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                                        <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                                        <?php } ?></td>

                                    <?php if (!$this->user->isVendor()): ?>

                                        <td style="width: 3px;" class="text-left">
                                            <?php if ($sort == 'customer') { ?>
                                            <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                                            <?php } else { ?>
                                            <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                                            <?php } ?>
                                        </td>

                                    <?php endif ?> 

                                    
                                    <!-- <td class="text-left">
                                        <?php if ($sort == 'city') { ?>
                                        <a href="<?php echo $sort_city; ?>" class="<?php echo strtolower($order); ?>">City</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_city; ?>"><?= $column_city ?></a>
                                        <?php } ?>
                                    </td> -->
                                    <td class="text-left">
                                        <?php if ($sort == 'status') { ?>
                                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                        <?php } ?>
                                    </td>

                                    

                                    <!-- <td class="text-right"><?php if ($sort == 'o.total') { ?>
                                        <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                                        <?php } ?></td> -->
                                    <td class="text-right"><?php if ($sort == 'o.total') { ?>
                                        <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left">
                                        <?php if ($sort == 'o.date_added') { ?>
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                        <?php } ?>
                                    </td>
                                    <!-- <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                                        <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                                        <?php } ?></td> -->

                                    <td class="text-left">Delivery Date</td>

                                    <td class="text-left">Delivery Timeslot</td>

                                   <!--  <?php if (!$this->user->isVendor()): ?>
                                        <td class="text-right"><?php echo $column_payment; ?></td>
                                     <?php endif ?>  

                                    
                                    <td class="text-right"><?php echo $column_delivery_method; ?></td>-->

                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders) { ?>
                                <?php foreach ($orders as $order) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
                                    </td>
                                    <td class="text-right"><?php echo $order['order_id']; ?></td>

                                    <?php if (!$this->user->isVendor()): ?>

                                        <td class="text-left" style="width:200px">
                                            <?php echo $order['customer']; ?>  <br/>
                                            <?php echo $order['company_name']  ; ?> <br/>
                                            <?php echo $order['shipping_address']  ; ?>
                                        </td>

                                    <?php endif ?> 
                                    <!-- <td class="text-left"><?php echo $order['city']; ?></td> -->
                                    <!-- <td class="text-left"><?php echo $order['status']; ?></td> -->
                                    <td class="text-left">
						<select name="order_status_id" id="input-order-status<?php echo $order['order_id']; ?>" class="form-control">
						  <?php foreach ($order['all_order_statuses'] as $order_statuses) { ?>
						  <?php if ($order_statuses['order_status_id'] == $order['order_status_id']) { ?>
						  <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
						  <?php } else { ?>
						  <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
						  <?php } ?>
						  <?php } ?>
						</select>
                                    <!--<h3 class="my-order-title label" style="background-color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2;" id="order-status" ><?php echo $order['status']; ?></h3>-->
                                    </td>
                                   

                                    <td class="text-right"><?php echo $order['sub_total']; /*echo $order['total'];*/ ?></td>
                                    <td class="text-left"><?php echo $order['date_added']; ?></td>
                                    <!-- <td class="text-left"><?php echo $order['date_modified']; ?></td> -->

                                     <td class="text-right"><?php echo $order['delivery_date']; ?></td>
                                    <td class="text-left"><?php echo $order['delivery_timeslot']; ?></td>
                                    
                                    <!-- <?php if (!$this->user->isVendor()): ?>
                                        <td class="text-right"  style="width:120px"><?php echo $order['payment_method']; ?></td>
                                     <?php endif ?>  

                                    
                                    <td class="text-right"><?php echo $order['shipping_method']; ?></td> -->

                                    <td class="text-right">
                                    <div style="width: 100%; display:flex; justify-content: space-between; flex-flow: row wrap; gap: 4px;">
                                    <?php if (!$this->user->isVendor()): ?>
                                        <!-- <a href="<?php echo $order['order_spreadsheet']; ?>" target="_blank" data-toggle="tooltip" title="Download Calculation Sheet" class="btn btn-info"><i class="fa fa-file-excel-o"></i></a> -->
                                        <!--<a href="<?php echo $order['shipping']; ?>" target="_blank" data-toggle="tooltip" title="Print Delivery Note" class="btn btn-info"><i class="fa fa-truck"></i></a>-->
                                    <?php endif ?>  
                
                                       

                                        <a href="<?php echo $order['invoice']; ?>" target="_blank" data-toggle="tooltip" title="Print Invoice">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        </a>

                                        

                                        <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="View Order Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                        </a> 
                                       
                                            <?php if ( !in_array( $order['order_status_id'], array_merge( $this->config->get( 'config_refund_status' ), $this->config->get( 'config_complete_status' ) ) ) ) { ?>
                                                <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="Edit Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </a> 
                                            <?php } ?>
                                        
                                        <!-- <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> -->
                                       
                                       <a href="#" onclick="getPO(<?= $order['order_id'] ?>)" data-toggle="modal" data-dismiss="modal" data-target="#poModal" title="PO Details">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                       </a>
                                       <a href="#" data-toggle="tooltip" title="Update Order Status" data-orderid="<?= $order['order_id'] ?>" id="update_order_status">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                       </a> 
                                       </div>
                                    </td>
                                        
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php if ($orders) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
    $('input[name=\'filter_store_name\']').autocomplete({
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
        $('input[name=\'filter_store_name\']').val(item['label']);
    }
    });


    $('input[name=\'filter_vendor\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?path=setting/store/vendor_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_vendor\']').val(item['label']);
    }
    });


    function excel() {
      url = 'index.php?path=report/product_purchased/excel&token=<?php echo $token; ?>';
      
      var filter_city = $('input[name=\'filter_city\']').val();
      
      if (filter_city) {
        url += '&filter_city=' + encodeURIComponent(filter_city);
      }
            
      var filter_date_start = $('input[name=\'filter_date_start\']').val();
      
      if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
      }

      var filter_date_end = $('input[name=\'filter_date_end\']').val();
      
      if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
      }
      
      var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
      
      if (filter_order_status_id != 0) {
        url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
      } 
      
      location = url;
    }


  $('#button-shipping, #button-invoice').on('click', function () {
  location = location;
        });

   $('#button-filter').on('click', function () {
            url = 'index.php?path=sale/order&token=<?php echo $token; ?>';

             var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  

            var filter_city = $('input[name=\'filter_city\']').val();

            if (filter_city) {
                url += '&filter_city=' + encodeURIComponent(filter_city);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }

            var filter_store_name = $('input[name=\'filter_store_name\']').val();

            if (filter_store_name) {
                url += '&filter_store_name=' + encodeURIComponent(filter_store_name);
            }

            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method) {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }
            
            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date) {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }

            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment) {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }



            var filter_order_status = $('select[name=\'filter_order_status\']').val();

            if (filter_order_status != '*') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total) {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end) {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }

            var filter_vendor = $('input[name=\'filter_vendor\']').val();

            if (filter_vendor) {
                url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
            }

            var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

            if (filter_date_modified) {
                url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
            }

            location = url;
        });
        //--></script>
    <script type="text/javascript"><!--
        
        $('input[name=\'filter_city\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/order/city_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['city_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_city\']').val(item['label']);
            }
        });
         $companyName="";
        $('input[name=\'filter_customer\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request)+'&filter_company=' +$companyName,
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['customer_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_customer\']').val(item['label']);
            }
        });


           $('input[name=\'filter_company\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletecompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['name']
                            }
                        }));

                        
                    }
                });
                $companyName="";
            },
            'select': function (item) {
                $('input[name=\'filter_company\']').val(item['label']);
                $('input[name=\'filter_customer\']').val('');
                $companyName=item['label'];
            }
        });
        
        //--></script> 
    <script type="text/javascript"><!--
  $('input[name^=\'selected\']').on('change', function () {

            $('#button-shipping, #button-invoice').prop('disabled', true);

            var selected = $('input[name^=\'selected\']:checked');

            if (selected.length) {
                $('#button-invoice').prop('disabled', false);
            }

            for (i = 0; i < selected.length; i++) {
                if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
                    $('#button-shipping').prop('disabled', false);

                    break;
                }
            }

        });

        $('input[name^=\'selected\']:first').trigger('change');

        $('a[id^=\'button-delete\']').on('click', function (e) {
            e.preventDefault();

            if (confirm('<?php echo $text_confirm; ?>')) {
                location = $(this).attr('href');
            }
        });
        //--></script> 




    
<div class="phoneModal-popup">
        <div class="modal fade" id="poModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:385px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>  Save PO & SAP  data     </h2>
                                          </br> 
                                    </div>
                                    <div id="poModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="poModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="poModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label > P.O. Number </label>
                                                        <input id="order_id"   name="order_id" type="hidden"  class="form-control input-md" required>

                                                    <div class="col-md-12">
                                                        <input id="po_number" maxlength="30" required style="max-width:100% ;" name="po_number" type="text" placeholder="P.O. Number" class="form-control" required>
                                                    <br/> </div>


                                                </div>
                                               


                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <label    > SAP Customer Number </label>

                                                    <div class="col-md-12">
                                                        <input id="SAP_customer_no" maxlength="30" required style="max-width:100% ;" name="SAP_customer_no" type="text" placeholder="SAP Customer Number" class="form-control input-md" required>
                                                    <br/> </div>

                                                   
                                                </div>
                                                  

                                                <div class="form-row">

                                                 <div class="form-group">
                                                    <label    > SAP Doc Number </label>

                                                    <div class="col-md-12">
                                                        <input id="SAP_doc_no" maxlength="30" required style="max-width:100% ;" name="SAP_doc_no" type="text" placeholder="SAP Doc Number" class="form-control input-md" required>
                                                    </div>

                                                    
                                                </div>
                                                </div>


                                                 <div class="form-group">
                                                    <div class="col-md-12">
                                                       </br>
                                                     
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Close</button>


                                                        <button id="po-button" name="po-button" onclick="savePO()" type="button" class="btn btn-lg btn-success"  style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>  
                                </div>
                            </div>
                           
                            <!-- next div code -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<script  type="text/javascript">


function getPO($order_id) {
               
                $('#poModal-message').html('');
               $('#poModal-success-message').html('');
                 

                 $.ajax({
                    url: 'index.php?path=sale/order/getPO&token=<?php echo $token; ?>&order_id='+$order_id,
                    type: 'POST',
                    dataType: 'json',
                    data:{order_id:$order_id},
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           $('input[name="po_number"]').val(json['po_number']) ;
                           $('input[name="SAP_customer_no"]').val(json['SAP_customer_no']) ;
                           $('input[name="SAP_doc_no"]').val(json['SAP_doc_no']) ;
                        }
                        else {
                             $('input[name="po_number"]').val('') ;
                           $('input[name="SAP_customer_no"]').val('') ;
                           $('input[name="SAP_doc_no"]').val('') ;
                            
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) { 

                         $('input[name="po_number"]').val('') ;
                           $('input[name="SAP_customer_no"]').val('') ;
                           $('input[name="SAP_doc_no"]').val('') ;
                                
                                    return false;
                                }
                });


               
               $('input[name="order_id"]').val($order_id) ;
                  
            }


function savePO() { 
 
    $('#poModal-message').html('');
               $('#poModal-success-message').html('');
   var po = $('input[name="po_number"]').val();
    var scno =  $('input[name="SAP_customer_no"]').val() ;
     var sdno =   $('input[name="SAP_doc_no"]').val() ;

              console.log($('#poModal-form').serialize());
 
                if (po.length  <= 1 && scno.length<=1 && sdno.length<=1) {
                   
                      $('#poModal-message').html("Please enter data");
                       return false;
                } 
                else{  
                  
                    $.ajax({
                    url: 'index.php?path=sale/order/updatePO&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data:$('#poModal-form').serialize(),
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            $('#poModal-success-message').html(' Saved Successfully');
                        }
                        else {
                            $('#poModal-success-message').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                $('#poModal-message').html("Please try again");
                                    return false;
                                }
                });
                }
               
            }

$('a[id^=\'update_order_status\']').on('click', function (e) {
e.preventDefault();
console.log($(this).data('orderid'));
var clicked_orderid = $(this).data('orderid');
var selected_order_status_id = $('select[id=\'input-order-status'+clicked_orderid+'\']').val();
console.log($('select[id=\'input-order-status'+clicked_orderid+'\']').val());
//return false;

if($.isNumeric(clicked_orderid) && clicked_orderid > 0 && $.isNumeric(selected_order_status_id) && selected_order_status_id > 0)  {
console.log(clicked_orderid);
console.log(selected_order_status_id);
$(this).find('i').toggleClass('fa fa-refresh fa fa-spinner');
$(this).attr("disabled","disabled");   
//return false;

if(typeof verifyStatusChange == 'function'){
if(verifyStatusChange() == false){
return false;
}
}

if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text()=='Delivered')
{
	 
	$.ajax({
		url: 'index.php?path=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id='+clicked_orderid,
		dataType: 'json',
		success: function(json) {
			console.log(json);
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[id=\'input-order-status'+clicked_orderid+'\']').val()) + '&notify=1',
		success: function(json) {	 
                    console.log(json);
                    $('.alert').html('Order status updated successfully!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
	});    
}	
});
            
</script>




    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false
        });

    setInterval(function() {
     location = location;
    }, 300 * 1000); // 60 * 1000 milsec
    
        //--></script></div>
<?php echo $footer; ?>