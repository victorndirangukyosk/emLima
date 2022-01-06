<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">

        
            <div class="pull-right">
                <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default" style="display:none;"><i class="fa fa-truck"></i></button>
                <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default" style="display:none;"><i class="fa fa-print"></i></button>
               <button type="button" onclick="downloadFastOrdersonsolidated();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Consolidated Excel"><i class="fa fa-download"></i></button>

               <!-- <?php if (!$this->user->isVendor()): ?>
                        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <?php endif ?>   -->
            </div>
            <h1><?php echo $heading_title_fastorders; ?></h1>
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
                        <!-- <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?= $entry_city ?></label>
                                <input type="text" name="filter_city" value="<?php echo $filter_city; ?>" class="form-control" />
                            </div>
                            
                        </div> -->
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <br><select name="filter_order_status[]" id="input-order-status-uni"  multiple="multiple">
                                    <!--<?php if ($filter_order_status == '0') { ?>
                                    <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_missing; ?></option>
                                    <?php } ?>-->
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if (in_array($order_status['order_status_id'],explode(",", $filter_order_status))) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>

                            </div>

                           

                           
                           <!-- <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div>-->

                            


                        </div>

                         <div class="col-sm-4">


                          <div class="form-group">
                                <label class="control-label" for="input-order-fromto">Order From & To ID</label>
                                <div class="input-group">
                                <input  style ="width:48%" type="text" name="filter_order_from_id" value="<?php echo $filter_order_from_id; ?>" placeholder="Order ID From" id="input-order-from-id" class="form-control" />
                                <input  style ="width:48%;margin-left:3px;" type="text" name="filter_order_to_id" value="<?php echo $filter_order_to_id; ?>" placeholder="Order ID To" id="input-order-to-id" class="form-control" />
                                    
                                </div>
                            </div>

                            </div>


                        <div class="col-sm-4">


                         <div class="form-group">
                                <label class="control-label" for="input-order-status">Delivery Day</label>
                                <select style="width:45%;" name="filter_order_day" id="input-order-status" class="form-control">
                                    <option value="today" <?php echo ($filter_order_day == 'today') ? 'selected="selected"':  "" ?> >Today</option>
                                    <option value="tomorrow" <?php echo ($filter_order_day == 'tomorrow') ? 'selected="selected"':  "" ?> >Tomorrow</option>
                                    
                                </select>
                            </div>


                            <!--<div class="form-group">
                                <label class="control-label" for="input-name"><?= $column_delivery_method ?></label>
                                <input type="text" name="filter_delivery_method" value="<?php echo $filter_delivery_method; ?>" placeholder="<?php echo $column_delivery_method; ?>" id="input-name" class="form-control" />
                            </div>
                            

                          

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-name"><?= $column_payment ?></label>
                                    <input type="text" name="filter_payment" value="<?php echo $filter_payment; ?>" placeholder="<?php echo $column_payment; ?>" id="input-name" class="form-control" />
                                </div>
                            <?php endif ?> -->

                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button> 
                            <!-- <div class="form-group">
                                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div> -->

                        </div>
                        <!-- <div class="col-sm-4">

                            <?php if(!$this->user->isVendor()){ ?>  
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?= $text_vendor ?></label>
                                <input type="text" name="filter_vendor" value="<?php echo $filter_vendor; ?>" placeholder="<?php echo $text_vendor; ?>" id="input-model" class="form-control" />
                            </div>
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
                                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div> -->
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
                                    </td>
                                     
                                     <td class="text-left">
                                        
                                        Store
                                    </td>-->
                                    <td class="text-left">
                                        <?php echo $column_status; ?>
                                    </td>
                                    <!--<td <?php echo $column_total; ?></td>-->

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


                                    <td class="text-left">Delivery Date</td>
                                    <td class="text-left"><?php if ($sort == 'delivery_timeslot') { ?>
                                        <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>">Delivery Timeslot</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_modified; ?>">Delivery Timeslot</a>
                                        <?php } ?></td>

                                        <!--<?php if (!$this->user->isVendor()): ?>
                                        <td class="text-right"><?php echo $column_payment; ?></td>
                                     <?php endif ?>  -->

                                    
                                    <td class="text-right"><?php echo $column_delivery_method; ?></td>

                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($all_orders) { ?>

                                <?php foreach ($all_orders as $key => $orderLoop ) { ?>

                                    <tr>  <td colspan="11">  </td> </tr>
                                    <tr>  <td colspan="11"><center><h3 class="my-order-title label" style="background-color: #<?= $orderLoop['order_status_color']; ?>;display: block;line-height: 2;" id="order-status" ><?= $key?> </h3>   </center></td> </tr>
                                    <tr>  <td colspan="11">  </td> </tr>
                                    <?php foreach ($orderLoop['orders'] as $order) { ?>
                                    <tr>
                                        <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                                            <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                                            <?php } else { ?>
                                            <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                                            <?php } ?>
                                            <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
                                        </td>
                                        <td class="text-right"><?php echo $order['order_id']; ?></td>
                                        <td class="text-left"><?php echo $order['customer']; ?></td>
                                       <!-- <td class="text-left"><?php echo $order['store']; ?></td>-->
                                        <!-- <td class="text-left"><?php echo $order['status']; ?></td> -->
                                        <!--<td class="text-left">

                                        <h3 class="my-order-title label" style="background-color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2;" id="order-status" ><?php echo $order['status']; ?></h3>
                                        </td>-->

                                         <td class="text-left">
                                                <?php 
                                                $disabled = NULL;
                                                if($order['order_status_id'] == 5 || $order['order_status_id'] == 6) {
                                                $disabled = 'disabled';
                                                } ?>
                                                <select name="order_status_id" id="input-order-status<?php echo $order['order_id']; ?>" class="form-control" <?php echo $disabled; ?> >
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

                                        <td class="text-right"><?php echo $order['sub_total']; ?></td>
                                        <td class="text-left"><?php echo $order['date_added']; ?></td>
                                        <td class="text-left"><?php echo $order['delivery_date']; ?></td>
                                        <td class="text-left"><?php echo $order['delivery_timeslot']; ?></td>

                                        <!--<?php if (!$this->user->isVendor()): ?>
                                            <td class="text-right"><?php echo $order['payment_method']; ?></td>
                                         <?php endif ?>  -->

                                        
                                        <td class="text-right"><?php echo $order['shipping_method']; ?></td>

                                        <!--<td class="text-right">
                                            <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> 
                                            
                                            <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> 
                                             <?php if (!$this->user->isVendor()): ?>
                                            
                                            </td>
                                            <?php endif; ?>-->


                                               <td class="text-right">
                                    <div style="width: 100%; display:flex; justify-content: space-between; flex-flow: row wrap; gap: 4px;">
                                    <?php if (!$this->user->isVendor()): ?>
                                        <!-- <a href="<?php echo $order['order_spreadsheet']; ?>" target="_blank" data-toggle="tooltip" title="Download Calculation Sheet" class="btn btn-info"><i class="fa fa-file-excel-o"></i></a> -->
                                        <!--<a href="<?php echo $order['shipping']; ?>" target="_blank" data-toggle="tooltip" title="Print Delivery Note" class="btn btn-info"><i class="fa fa-truck"></i></a>-->
                                    <?php endif ?>  
                
                                       
                                     <?php 
                                             
                                            if ( $order['order_status_id']!=15 && $order['order_status_id']!=16 && $order['order_status_id']!=6 && $order['order_status_id']!=8 && $order['order_status_id']!=9 && $order['order_status_id']!=10) { ?>
                                               <!--<a href="<?php echo $order['invoice']; ?>" target="_blank" data-toggle="tooltip" title="Print Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                </a> -->
                                               <a href="#" id="new_print_invoice" data-order-invoice="<?php echo $order['invoice']; ?>" data-order-id="<?= $order['order_id'] ?>" data-toggle="tooltip" title="Print Invoice"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                                            <?php } ?>
                                        

                                        

                                        <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="View Order Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                        </a> 
                                       
                                            <?php 
                                            $approvalpending=array("15");
                                            if ( !in_array( $order['order_status_id'], array_merge( $this->config->get( 'config_refund_status' ), $this->config->get( 'config_complete_status' ) ,$approvalpending) ) ) { ?>
                                                <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="Edit Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </a> 
                                            <?php } ?>
                                        
                                        <!-- <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> -->
                                       
                                       <a href="#" onclick="getPO(<?= $order['order_id'] ?>)" data-toggle="modal" data-dismiss="modal" data-target="#poModal" title="PO Details">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                       </a>
                                       <?php if ($order['order_status_id'] != 5) { ?>
                                       <a href="#" data-toggle="tooltip" title="Update Order Status" data-orderid="<?= $order['order_id'] ?>" id="update_order_status">
                                       <svg xmlns="http://www.w3.org/2000/svg" id="svg<?= $order['order_id'] ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                       </a> 
                                       <?php } ?>
                                       </div>
                                    </td>


                                            

                                    </tr>
                                    <?php } ?>

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
                <!-- <?php if ($all_orders) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?> -->
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

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


        function downloadFastOrdersonsolidated() {
          
            //const deliveryDate = $("#consolidated-order-sheet-datepicker").val();
                url = 'index.php?path=report/vendor_orders/consolidatedOrderSheet&token=<?php echo $token; ?>';
              var filter_order_status = $('select[name=\'filter_order_status[]\']').val();

            console.log(filter_order_status);

            if (filter_order_status != '*') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_order_day = $('select[name=\'filter_order_day\']').val();

            if (filter_order_day != '*') {
                url += '&filter_order_day=' + encodeURIComponent(filter_order_day);
            } 

             var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i){
            return n.value;
            }).join(',');
            console.log(selected_order_id);
            
            if (selected_order_id != '') {
                url += '&selected_order_id=' + encodeURIComponent(selected_order_id);
            }
            
            
                    location = url;
            
        }




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
            url = 'index.php?path=sale/fast_order&token=<?php echo $token; ?>';

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



         var filter_order_from_id = $('input[name=\'filter_order_from_id\']').val();

            if (filter_order_from_id) {
                url += '&filter_order_from_id=' + encodeURIComponent(filter_order_from_id);
            }


             var filter_order_to_id = $('input[name=\'filter_order_to_id\']').val();

            if (filter_order_to_id) {
                url += '&filter_order_to_id=' + encodeURIComponent(filter_order_to_id);
            }
            var filter_store_name = $('input[name=\'filter_store_name\']').val();

            if (filter_store_name) {
                url += '&filter_store_name=' + encodeURIComponent(filter_store_name);
            }

            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method) {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }

            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment) {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }



            var filter_order_status = $('select[name=\'filter_order_status[]\']').val();

            console.log(filter_order_status);

            if (filter_order_status != '*') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_order_day = $('select[name=\'filter_order_day\']').val();

            if (filter_order_day != '*') {
                url += '&filter_order_day=' + encodeURIComponent(filter_order_day);
            }


            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total) {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
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
        
        $('input[name=\'filter_customer\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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






<!--//aaaaaaaaaaaaaaaaaaaaaaaaaa-->


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
                                                    <label hidden   > SAP Doc Number </label>

                                                    <div hidden class="col-md-12">
                                                        <input   id="SAP_doc_no" maxlength="30" required style="max-width:100% ;" name="SAP_doc_no" type="text" placeholder="SAP Doc Number" class="form-control input-md" required>
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
        
    <div class="modal fade" id="driverModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:400px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>Save Driver Details</h2>
                                          </br> 
                                    </div>
                                    <div id="driverModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="driverModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="driverModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="input-order-status" class="control-label"> Delivery Executive </label>
                                                    <div class="col-md-12">
                                                        <!--<input id="order_delivery_executive" maxlength="30" required style="max-width:100% ;" name="order_delivery_executive" type="text" placeholder="Delivery Executive" class="form-control" data_delivery_executive_id="" required>-->
                                                        <select name="order_delivery_executives" id="order_delivery_executives" class="form-control" required="">
                                                        <option value="0">Select Delivery Executive</option>
                                                        <?php foreach ($delivery_executives as $delivery_executive) { ?>
                                                        <option value="<?php echo $delivery_executive['delivery_executive_id']; ?>"><?php echo $delivery_executive['name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    <br/></div>
                                                </div><br/><br/>
                                                
                                                <div class="form-group">
                                                    <label > Driver </label>
                                                        <input id="order_id"   name="order_id" type="hidden"  class="form-control input-md" required>
                                                    
                                                    <div class="col-md-12">
                                                        <!--<input id="order_driver" maxlength="30" required style="max-width:100% ;" name="order_driver" type="text" placeholder="Driver" class="form-control" data_driver_id="" required>-->
                                                        <select name="order_drivers" id="order_drivers" class="form-control" required="">
                                                        <option value="0">Select Driver</option>
                                                        <?php foreach ($drivers as $driver) { ?>
                                                        <option value="<?php echo $driver['driver_id']; ?>"><?php echo $driver['name']; ?></option>
                                                        <?php } ?>    
                                                        </select>
                                                    <br/></div>
                                                </div><br/><br/>

                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <label> Vehicle Number </label>

                                                    <div class="col-md-12">
                                                        <input id="order_vehicle_number" maxlength="10" required style="max-width:100% ;" name="order_vehicle_number" type="text" placeholder="Vehicle Number" class="form-control input-md" required>
                                                    <br/> </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-6"> 
                                                        <button type="button" id="driver-buttons" name="driver-buttons" onclick="savedriverdetail()" class="btn btn-lg btn-success" data-dismiss="modal" style="width:50%; float: left;  margin-top: 10px; height: 45px;border-radius:20px">Save & Close</button>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <button id="driver-button" name="driver-button" onclick="savedriverdetails()" type="button" class="btn btn-lg btn-success"  style="width:65%; float:right;  margin-top: 10px; height: 45px;border-radius:20px">Save & Print Invoice</button>
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
    
    <div class="modal fade" id="orderprocessingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:330px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>Save Order Processing Details</h2>
                                          </br> 
                                    </div>
                                    <div id="orderprocessingModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="orderprocessingModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="orderprocessingModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="input-order-status" class="control-label"> Order Processing Group </label>
                                                    <div class="col-md-12">
                                                        <!--<input id="order_delivery_executive" maxlength="30" required style="max-width:100% ;" name="order_delivery_executive" type="text" placeholder="Delivery Executive" class="form-control" data_delivery_executive_id="" required>-->
                                                        <select name="order_processing_group_id" id="order_processing_group_id" class="form-control" required="">
                                                        <option> Select Order Processing Group </option>
                                                        <?php foreach ($order_processing_groups as $order_processing_group) { ?>
                                                        <option value="<?php echo $order_processing_group['order_processing_group_id']; ?>"><?php echo $order_processing_group['order_processing_group_name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    <br/></div>
                                                </div><br/><br/>
                                                
                                                <div class="form-group">
                                                    <label > Order Processor </label>
                                                        <input id="order_id"   name="order_id" type="hidden"  class="form-control input-md" required>
                                                        <input id="invoice_custom"   name="invoice_custom" type="hidden"  class="form-control input-md">
                                                    <div class="col-md-12">
                                                        <!--<input id="order_driver" maxlength="30" required style="max-width:100% ;" name="order_driver" type="text" placeholder="Driver" class="form-control" data_driver_id="" required>-->
                                                        <select name="order_processor_id" id="order_processor_id" class="form-control" required="">
                                                        <option> Select Order Processor </option>
                                                        </select>
                                                    <br/></div>
                                                </div><br/><br/>

                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: left; margin-top: 10px; height: 45px;border-radius:20px">Close</button>
                                                        <button id="driver-button" name="orderprocessing-button" onclick="saveorderprocessingdetails()" type="button" class="btn btn-lg btn-success"  style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Save</button>
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
    
    <!-- Modal -->
    <div class="modal fade" id="ordernoticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content"  >
                <div class="modal-body"  style="height:130px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="store-find-block">
                        <div class="mydivsss">
                            <div class="store-find">
                                <div class="store-head">
                                    <h2>Order Details</h2>
                                    </br> 
                                </div>
                                <div id="ordernoticeModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                </div>
                                <div id="ordernoticeModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
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

function savedriverdetails() { 
 
    $('#driverModal-message').html('');
    $('#driverModal-success-message').html('');
   var order_id = $('input[name="order_id"]').val();
   var invoice = $('input[name="invoice_custom"]').val();
   var driver_id = $('select[name="order_drivers"]').val();
   //var driver_id = $('input[name="order_driver"]').attr("data_driver_id");
   var vehicle_number =  $('input[name="order_vehicle_number"]').val();
   var delivery_executive_id =  $('select[name="order_delivery_executives"]').val();
   //var delivery_executive_id =  $('input[name="order_delivery_executive"]').attr("data_delivery_executive_id");
    console.log(vehicle_number);
    console.log(driver_id);
    console.log(delivery_executive_id);

              console.log($('#driverModal-form').serialize());
 
                if (isNaN(delivery_executive_id) || isNaN(order_id) || isNaN(driver_id) || driver_id  < 0 || driver_id == '' || vehicle_number == '' || vehicle_number.length == 0 || order_id < 0 || order_id == '' || delivery_executive_id < 0 || delivery_executive_id == '') {
                   
                      $('#driverModal-message').html("Please enter data");
                       return false;
                } 
                else{
                    var clicked_orderid = order_id;
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
                    //setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
                });                    
                    
                    $.ajax({
                    url: 'index.php?path=sale/order/SaveOrUpdateOrderDriverVehicleDetails&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data:{ order_id : order_id, vehicle_number : vehicle_number, driver_id : driver_id, delivery_executive_id:delivery_executive_id },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            $('#driverModal-success-message').html('Saved Successfully');
                            window.open(invoice, '_blank');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                            $('#driverModal-success-message').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                $('#driverModal-message').html("Please try again");
                                    return false;
                                }
                });
                }
               
            }
            
function savedriverdetail() { 
 
    $('#driverModal-message').html('');
    $('#driverModal-success-message').html('');
   var order_id = $('input[name="order_id"]').val();
   var invoice = $('input[name="invoice_custom"]').val();
   var driver_id = $('select[name="order_drivers"]').val();
   //var driver_id = $('input[name="order_driver"]').attr("data_driver_id");
   var vehicle_number =  $('input[name="order_vehicle_number"]').val();
   var delivery_executive_id =  $('select[name="order_delivery_executives"]').val();
   //var delivery_executive_id =  $('input[name="order_delivery_executive"]').attr("data_delivery_executive_id");
    console.log(vehicle_number);
    console.log(driver_id);
    console.log(delivery_executive_id);

              console.log($('#driverModal-form').serialize());
 
                if (isNaN(delivery_executive_id) || isNaN(order_id) || isNaN(driver_id) || driver_id  < 0 || driver_id == '' || vehicle_number == '' || vehicle_number.length == 0 || order_id < 0 || order_id == '' || delivery_executive_id < 0 || delivery_executive_id == '') {
                   
                      $('#driverModal-message').html("Please enter data");
                       return false;
                } 
                else{
                var clicked_orderid = order_id;
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                $.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[id=\'input-order-status'+clicked_orderid+'\']').val()) + '&notify=1',
		beforeSend: function() {
                // setting a timeout
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                },
                success: function(json) {	 
                    console.log(json);
                    $('.alert').html('Order status updated successfully!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    //setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
                }); 
                    $.ajax({
                    url: 'index.php?path=sale/order/SaveOrUpdateOrderDriverVehicleDetails&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data:{ order_id : order_id, vehicle_number : vehicle_number, driver_id : driver_id, delivery_executive_id:delivery_executive_id },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            $('#driverModal-success-message').html('Saved Successfully');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                            $('#driverModal-success-message').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                $('#driverModal-message').html("Please try again");
                                    return false;
                                }
                });
                }
                
$('#driverModal-form')[0].reset();               
}            

function saveorderprocessingdetails() { 
 
    $('#orderprocessingModal-message').html('');
    $('#orderprocessingModal-success-message').html('');
   var order_id = $('input[name="order_id"]').val();
   var order_processing_group_id =  $('select[name="order_processing_group_id"]').val();
   var order_processor_id =  $('select[name="order_processor_id"]').val();
   var order_processing_group_name = 'Order Processing Group : '+ $('select[name=\'order_processing_group_id\'] option:selected').text();
    console.log(order_processing_group_id);
    console.log(order_processor_id);

              console.log($('#orderprocessingModal-form').serialize());
 
                if (isNaN(order_processor_id) || isNaN(order_processing_group_id) || order_processing_group_id  <= 0 || order_processing_group_id == '' || order_processor_id == '' || order_processor_id <= 0 || order_id <= 0 || order_id == '') {
                   
                      $('#orderprocessingModal-message').html("Please enter data");
                       return false;
                } 
                else{
            var clicked_orderid = order_id;
            $('.alert').html('Please wait your request is processing!');
            $(".alert").attr('class', 'alert alert-success');
            $(".alert").show();
            $.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[id=\'input-order-status'+clicked_orderid+'\']').val()) + '&notify=1&comment='+order_processing_group_name,
		success: function(json) {	 
                    console.log(json);
                    $('.alert').html('Order status updated successfully!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    //setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
            });
                  
                    $.ajax({
                    url: 'index.php?path=sale/order/SaveOrUpdateOrderProcessorDetails&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data:{ order_id : order_id, order_processing_group_id : order_processing_group_id, order_processor_id : order_processor_id },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            $('#orderprocessingModal-success-message').html('Saved Successfully');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                            $('#orderprocessingModal-success-message').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                $('#orderprocessingModal-message').html("Please try again");
                                    return false;
                                }
                });
                }
$('#orderprocessingModal-form')[0].reset();               
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
$('#svg'+clicked_orderid).attr('stroke', '#FF8C00');
//return false;

if(typeof verifyStatusChange == 'function'){
if(verifyStatusChange() == false){
return false;
}
}

$.ajax({
url: 'index.php?path=sale/order/getDriverDetails&token=<?php echo $token; ?>',
type: 'post',
dataType: 'json',
data: 'order_id=' + clicked_orderid,
success: function(json) {
console.log(json.order_info);

if(json.order_info.order_status_id == 15 && selected_order_status_id != 6) {
console.log('You Cant Update Order Status!');  
$('#ordernoticeModal').modal('toggle');
$('#ordernoticeModal-message').html('You Cant Update Order Status! Until Parent Customer Approve The Order.');
return false;
}

if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text()=='Delivered')
{ 
$.ajax({
		url: 'index.php?path=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id='+clicked_orderid,
		dataType: 'json',
		success: function(json) {
	        console.log(json);
                if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text()!='Order Processing')
                {
                setTimeout(function(){ window.location.reload(false); }, 1500);    
                }        
		},			
		error: function(xhr, ajaxOptions, thrownError) {
	        //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

/*if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text()=='Ready for delivery')
{
$('input[name="order_id"]').val(clicked_orderid);
$('#driverModal').modal('toggle');
savedriverdetails();
}*/

if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text()=='Order Processing')
{
$('input[name="order_id"]').val(clicked_orderid);
$('#orderprocessingModal').modal('toggle');
//saveorderprocessingdetails();
}

if(/*selected_order_status_id != 3 &&*/ selected_order_status_id != 1) {
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
                    if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text()!='Order Processing')
                    {
                    setTimeout(function(){ window.location.reload(false); }, 1500);    
                    }
                    //setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
});   
}

},			
error: function(xhr, ajaxOptions, thrownError) {		
}
});

}

setInterval(function() {
$('#svg'+clicked_orderid).attr('stroke', '#51AB66');
}, 4000); // 60 * 1000 milsec

});

$('select[id^=\'order_processing_group_id\']').on('change', function (e) {
    var order_processing_group_id = $('select[id=\'order_processing_group_id\'] option:selected').val();
    $.ajax({
      url: 'index.php?path=orderprocessinggroup/orderprocessor/getAllOrderProcessors&token=<?php echo $token; ?>&order_processing_group_id='+order_processing_group_id,
      dataType: 'json',     
      success: function(json) {
//console.log(json.length);
var $select = $('#order_processor_id');
    $select.html('');
    $select.append('<option value=""> Select Order Processor </option>');
    if(json != null && json.length > 0) {
    $.each(json, function(index, value) {
      $select.append('<option value="' + value.order_processor_id + '">' + value.name + '</option>');
    });
    }
    $('.selectpicker').selectpicker('refresh');
}
});    
});          
</script>


<!--//aaaaaaaaaaaaaaaaaaaaaaaaa-->







    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

    
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <script type="text/javascript">

    $( "#input-order-status-uni" ).select2({
        theme: "classic",
         width: 'resolve'
    });

    <!--
  $('.date').datetimepicker({
            pickTime: false
        });

  setInterval(function() {
     location = location;
    }, 300 * 1000);
        //-->
           $driverName="";
        
        
$('input[name=\'order_driver\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=drivers/drivers_list/autocompletebyDriverName&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request)+'&filter_company=' +$driverName,
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['driver_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'order_driver\']').val(item['label']);
    $('input[name=\'order_driver\']').attr('data_driver_id',item['value']);
  } 
});

$('input[name=\'order_delivery_executive\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=executives/executives_list/autocompletebyExecutiveName&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['executive_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'order_delivery_executive\']').val(item['label']);
    $('input[name=\'order_delivery_executive\']').attr('data_delivery_executive_id',item['value']);
  } 
});

$('a[id^=\'new_print_invoice\']').on('click', function (e) {
e.preventDefault();
var invoice = $(this).attr("data-order-invoice");
var order_id = $(this).attr("data-order-id");
var order_status = $('select[id=\'input-order-status'+order_id+'\'] option:selected').text();

 $('select[name="order_delivery_executives"]').selectpicker('val', 0);
 $('select[name="order_drivers"]').selectpicker('val', 0);
 $('input[name="order_vehicle_number"]').val('');

$.ajax({
		url: 'index.php?path=sale/order/getDriverDetails&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + order_id,
		success: function(json) {
                    console.log(json);
                    console.log(json.order_info.order_id);
                    console.log(json.order_info.driver_id);
                    console.log(json.order_info.vehicle_number);
                    console.log(json.order_info.delivery_executive_id);
                    if(/*order_status != 'Ready for delivery'*/ json.order_info.order_status != 'Order Processing' || order_status != 'Order Processing' || json.order_info.driver_id == null || json.order_info.vehicle_number == null || json.order_info.delivery_executive_id == null)
                    {
                    $('input[name="order_id"]').val(order_id);
                    $('input[name="invoice_custom"]').val(invoice);
                    $('#driverModal').modal('toggle');
                    if(order_status != 'Order Processing' || json.order_info.order_status != 'Order Processing') {
                    //if(order_status != 'Ready for delivery') {
                    $('#driverModal-message').html("Please Update Order Status As Order Processing!");
                    //$('#driverModal-message').html("Please Select Order Status As Ready For Delivery!");
                    $('#driver-buttons').prop('disabled', true);
                    $('#driver-button').prop('disabled', true);
                    return false;
                    } else {
                    $('#driverModal-message').html("");
                    $('#driver-buttons').prop('disabled', false);
                    $('#driver-button').prop('disabled', false);    
                    }
                    } else {
                    console.log(invoice);
                    window.open(invoice, '_blank');
                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
});
console.log($(this).attr("data-order-id"));
});

        
        </script></div>
<?php echo $footer; ?>



<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}
</style>