<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="" id="button-status-update" form="form-order" data-toggle="tooltip" title="<?php echo $button_status_update; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></button>
                <button type="" id="button-status-update-transit" form="form-order" data-toggle="tooltip" title="<?php echo $button_status_update_transit; ?>" class="btn btn-default"><i class="fa fa-cubes"></i></button>
                <?php if (!$this->user->isVendor()): ?>
                        <button type="" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default"><i class="fa fa-truck"></i></button>
                <?php endif ?>  
                <button type="" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></button>
                <button type="" id="button-invoice-pdf" form="form-order" formaction="<?php echo $invoicepdf; ?>" data-toggle="tooltip" title="Download Invoice" class="btn btn-default"><i class="fa fa-print"></i></button>
                <button type="button" onclick="downloadOrderStickers();" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Orders List"><i class="fa fa-download"></i></button>
                <button type="button" onclick="downloadOrders();" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Orders Excel"><i class="fa fa-download"></i></button>
                <button type="button" onclick="downloadOrdersonsolidated();" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Consolidated Excel"><i class="fa fa-download"></i></button>
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
                <div class="well" style="display:none;max-height:310px !important;" >
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

                             <!--<div class="form-group">
                                <label class="control-label" for="input-name"><?= $column_delivery_method ?></label>
                                <input type="text" name="filter_delivery_method" value="<?php echo $filter_delivery_method; ?>" placeholder="<?php echo $column_delivery_method; ?>" id="input-name" class="form-control" />
                            </div>-->
                            
                             <div class="form-group">
                             
                                <label class="control-label" for="input-order-type">Order Type</label>
                                <select name="filter_order_type" id="input-order-type" class="form-control">
                                    <option value="*" selected></option> 
                                    <?php if ($filter_order_type=='1') { ?>
                                    <option value="1" selected="selected">Manual</option>
                                    <?php } else { ?>
                                    <option value="1">Manual</option>
                                    <?php } ?>
                                     <?php if ($filter_order_type=='0') { ?>
                                    <option value="0" selected="selected">Online</option>
                                    <?php } else { ?>
                                    <option value="0">Online</option>
                                    <?php } ?>
                                     
                                </select>
                            </div>
                         
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
                                <label class="control-label" for="input-payment-status"><?php echo $entry_payment_status; ?></label>
                                <select name="filter_paid" id="input-paid" class="form-control">
                                    <option value="*">Select Payment Status</option>
                                    <?php if (isset($filter_paid) && $filter_paid == 'P') { ?>
                                    <option value="P" selected="selected">Partially Paid</option>
                                    <?php } else { ?>
                                    <option value="P">Partially Paid</option>
                                    <?php } ?>
                                    <?php if(isset($filter_paid) && $filter_paid == 'Y') { ?>
                                    <option value="Y" selected="selected">Paid</option>
                                    <?php } else { ?>
                                    <option value="Y">Paid</option>
                                    <?php } ?>
                                    <?php if(isset($filter_paid) && $filter_paid == 'N') { ?>
                                    <option value="N" selected="selected">Unpaid</option>
                                    <?php } else { ?>
                                    <option value="N">Unpaid</option>
                                    <?php } ?>
                                </select>    
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
                            <!--<div class="form-group">
                                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                            </div>-->

                            <!--<div class="form-group">
                                <label class="control-label" for="input-name"><?= $entry_store_name ?></label>
                                <input type="text" name="filter_store_name" value="<?php echo $filter_store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-name" class="form-control" />
                            </div>-->

                            <?php if (!$this->user->isVendor()): ?>
                                <div class="form-group">
                                    <label class="control-label" for="input-payment"><?= $column_payment ?></label>
                                    <input type="text" name="filter_payment" value="<?php echo $filter_payment; ?>" placeholder="<?php echo $column_payment; ?>" id="input-name" class="form-control" />
                               
                                
                               
                                </div>
                            <?php endif ?> 

   
                               <div class="form-group">
                                <label class="control-label" for="input-order-fromto">Order From & To ID</label>
                                <div class="input-group">
                                <input  style ="width:48%" type="text" name="filter_order_from_id" value="<?php echo $filter_order_from_id; ?>" placeholder="Order ID From" id="input-order-from-id" class="form-control" />
                                <input  style ="width:48%;margin-left:3px;" type="text" name="filter_order_to_id" value="<?php echo $filter_order_to_id; ?>" placeholder="Order ID To" id="input-order-to-id" class="form-control" />
                                    
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


                             <div class="form-group">
                             
                                <label class="control-label" for="input-order-type">Order Placed From</label>
                                <select name="filter_order_placed_from" id="input-order-placed-from" class="form-control">
                                    <option value="*" selected></option> 
                                    <?php if ($filter_order_placed_from=='Web') { ?>
                                    <option value="Web" selected="selected">Web</option>
                                    <?php } else { ?>
                                    <option value="Web">Web</option>
                                    <?php } ?>
                                     <?php if ($filter_order_placed_from=='Mobile') { ?>
                                    <option value="Mobile" selected="selected">Mobile</option>
                                    <?php } else { ?>
                                    <option value="Mobile">Mobile</option>
                                    <?php } ?>
                                     
                                </select>
                            </div>


                        </div>



                        <div class="col-sm-4">

                           <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>

                            <?php if(!$this->user->isVendor()){ ?>  
                            <div class="form-group">
                                <label class="control-label" for="input-model"><?= $text_vendor ?></label>
                                <input type="text" name="filter_vendor" value="<?php echo $filter_vendor; ?>" placeholder="<?php echo $text_vendor; ?>" id="input-model" class="form-control" />
                            </div>
                            <?php } ?>


                            
                            
                            <!--<div class="form-group">
                                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>-->
                            
                             <div class="form-group">
                                <label class="control-label" for="input-delivery-date">Delivery Date</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" placeholder="<?php echo $column_delivery_date; ?>" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="input-delivery-date">Delivery Time Slot</label>
                                    <select name="filter_delivery_time_slot" id="input-delivery-time-slot" class="form-control">
                                    <option value="">Select <?php echo $column_delivery_time_slot; ?></option>
                                    <?php foreach ($time_slots as $time_slot) { ?>
                                    <?php if ($time_slot['timeslot'] == $filter_delivery_time_slot) { ?>
                                    <option value="<?php echo $time_slot['timeslot']; ?>" selected="selected"><?php echo $time_slot['timeslot']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $time_slot['timeslot']; ?>"><?php echo $time_slot['timeslot']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                            <button type="button" id="button-filter" class="btn btn-primary pull-left" style="margin-top:20px;"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                            
                        </div>

                        
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">

                       <?php if ($this->user->hasPermission('access', 'amitruck/amitruckquotes'))    { ?>
                     <div class="btn-group" >                            
                         <div class="row">
                             <div class="col-sm-8">
                                        <input disabled type="text" name="order_delivery_count" value="" placeholder="No Order Selected" id="input-grand-total" class="form-control" />
                             </div>  
                             <div class="col-sm-2">
                                    <button type="button" id="button-bulkdeliveryrequest" class="btn btn-primary" onclick="createDeliveryRequest()"  data-toggle="modal" data-dismiss="modal" data-target="" title="Request Delivery">  Request Bulk Delivery</button>
                             </div>    
                         </div>                             
                            
                      </div>
                       <?php } ?>
                      <br>
                      <br>

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
                                    <td class="text-center">Vendor</td>


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
                                    
                                    <?php if ($this->user->isVendor()) { ?>
                                    <td class="text-left">Vendor Order Status</td>
                                    <?php } ?>
                                    

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
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Order Date</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>">Order Date</a>
                                        <?php } ?>
                                    </td>
                                    <!-- <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                                        <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                                        <?php } ?></td> -->


                                     <td class="text-left">
                                        <?php if ($sort == 'o.delivery_date') { ?>
                                        <a href="<?php echo $sort_delivery_date; ?>" class="<?php echo strtolower($order); ?>">Delivery Date</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_delivery_date; ?>">Delivery Date</a>
                                        <?php } ?>
                                    </td>


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
                                        <input type="hidden" name="order_status1[]" value="<?php echo $order['order_status_id']; ?>" />
                                        <input type="hidden" name="order_delivery_ids[]" value="<?php echo $order['delivery_id']; ?>" />
                                    </td>
                                    <td class="text-left"><?php echo $order['order_prefix'].''.$order['order_id']; ?></td>
                                    <?php if (!$this->user->isVendor()): ?>
                                    <td class="text-left"><?php echo $order['vendor_name']; ?></td>

                                        <td class="text-left" style="width:200px">
                                            <?php echo $order['customer']; ?>  <br/>
                                            <?php echo $order['company_name']  ; ?> <br/>
                                            <?php echo $order['shipping_address']  ; ?>
                                        </td>

                                    <?php endif ?> 
                                    <!-- <td class="text-left"><?php echo $order['city']; ?></td> -->
                                    <!-- <td class="text-left"><?php echo $order['status']; ?></td> -->
                                    <?php if (!$this->user->isVendor()) { ?>
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
                                   <?php } ?>
                                   <?php if($this->user->isVendor()) { ?>
                                   <td class="text-left"><?php echo $order['status']; ?></td>
                                   <?php } ?>
                                   <?php if($this->user->isVendor()) { ?>
                                   <td class="text-left">
                                       <select name="vendor_order_status_id" id="input-vendor-order-status<?php echo $order['order_id']; ?>" class="form-control">
                                           <option>Vendor Order Status</option>
                                          <?php foreach ($vendor_order_statuses as $vendor_order_status) { ?>
				          <?php if ($vendor_order_status['order_status_id'] == $order['vendor_order_status_id']) { ?>
				          <option value="<?php echo $vendor_order_status['order_status_id']; ?>" selected="selected"><?php echo $vendor_order_status['name']; ?></option>
				          <?php } else { ?>
                                          <option value="<?php echo $vendor_order_status['order_status_id']; ?>"><?php echo $vendor_order_status['name']; ?></option>
				          <?php } } ?> 
				       </select>
                                   </td>
                                   <?php } ?>
                                    <?php if($this->user->isVendor()) { ?>
                                    <td class="text-right"><?php echo $order['vendor_total']; /*echo $order['total'];*/ ?></td>
                                    <?php } else { ?>
                                    <td class="text-right"><?php /*echo $order['sub_total'];*/ echo $order['total']; ?></td>
                                    <?php } ?>
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
                
                                       
                                     <?php 
                                             
                                            if ( $order['order_status_id']!=15 && $order['order_status_id']!=16 && $order['order_status_id']!=6 && $order['order_status_id']!=8 && $order['order_status_id']!=9 && $order['order_status_id']!=10) { ?>
                                              
                                               <?php if ($this->user->isVendor()) { ?>
                                               <!--<a href="<?php echo $order['invoice']; ?>" target="_blank" data-toggle="tooltip" title="Print Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                </a>-->  
                                               <?php } else { ?> 
                                               
                                               <a href="#" id="updated_new_print_invoice"  data-order-vendor="<?php echo $order['vendor_name']; ?>" data-order-invoice="<?php echo $order['invoice']; ?>" data-order-id="<?= $order['order_id'] ?>"  data-order-delivery-date="<?= $order['delivery_date'] ?>" data-toggle="tooltip" title="Print Invoice"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                                           <?php } ?> 
                                            <?php } ?>
                                        

                                        

                                        <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="View Order Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                        </a> 
                                       
                                            <?php 
                                            $approvalpending=array("15");
                                            if ( !in_array( $order['order_status_id'], array_merge( $this->config->get( 'config_refund_status' ), $this->config->get( 'config_complete_status' ) ,$approvalpending) ) && !$this->user->isVendor() && $this->user->hasPermission('modify', 'sale/editinvoice')) { ?>
                                                <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="Edit Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </a> 
                                            <?php } ?>
                                        
                                        <!-- <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> -->
                                       <?php if (!$this->user->isVendor() && $this->user->hasPermission('modify', 'sale/order')) { ?>
                                       
                                       <a href="#" onclick="getPO(<?= $order['order_id'] ?>)" data-toggle="modal" data-dismiss="modal" data-target="#poModal" title="PO Details">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                       </a>
                                        <?php } ?>
                                       <?php if ($order['order_status_id'] != 5  && (!$this->user->isVendor()) && $this->user->hasPermission('modify', 'sale/order')) { ?>
                                       <a href="#" data-toggle="tooltip" title="Update Order Status" data-orderid="<?= $order['order_id'] ?>" id="update_order_status">
                                       <svg xmlns="http://www.w3.org/2000/svg" id="svg<?= $order['order_id'] ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                       </a> 
                                       <?php } ?>
                                       <?php if ($order['order_status_id'] != 5  && ($this->user->isVendor())) { ?>
                                       <a href="#" data-toggle="tooltip" title="Update Vendor Order Status" data-orderid="<?= $order['order_id'] ?>" id="update_vendor_order_status">
                                       <svg xmlns="http://www.w3.org/2000/svg" id="svg<?= $order['order_id'] ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                       </a> 
                                       <?php } ?>
                                       <?php if ($order['paid'] == 'Y'  && (!$this->user->isVendor())) { ?>
                                       <a href="#" class="customer_verified" data-toggle="tooltip" title="Paid">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                       </a> 
                                       <?php } ?>
                                       <?php if ($order['paid'] == 'P'  && (!$this->user->isVendor())) { ?>
                                       <a href="#" class="customer_verified" data-toggle="tooltip" title="Partially Paid">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFFF00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                       </a> 
                                       <?php } ?>
                                       <?php if ($order['missing_products_count'] == 0 && $order['order_status_id'] == 1  && (!$this->user->isVendor()))    { ?>
                                       <a href="#" data-toggle="tooltip" data-target="store_modal" title="Missed Products List" data-orderid="<?= $order['order_id'] ?>" id="order_products_list">
                                       <svg xmlns="http://www.w3.org/2000/svg" id="svg<?= $order['order_id'] ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                                       </a> 
                                       <?php } ?>
                                        
                                        <?php if ($order['delivery_id'] == NULL && ($order['order_status_id'] == 1 ) && ($this->user->hasPermission('access', 'amitruck/amitruckquotes')) )   { ?>
                                       <a href="#" target="_blank" data-toggle="tooltip" title="Amitruck" data-orderid="<?= $order['order_id'] ?>" data-ordertotal="<?= $order['sub_total_custom'] ?>" id="assign_to_amitruck">
                                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        </a>
                                        <?php } ?>
                                        <a href="#" target="_blank" data-toggle="tooltip" title="Products List" data-orderid="<?= $order['order_id'] ?>" data-order-product-list="<?php echo $order['products_list']; ?>" id="download_product_list">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg>
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
    var selected_order_ids=[];        
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

              var filter_order_from_id = $('input[name=\'filter_order_from_id\']').val();

            if (filter_order_from_id) {
                url += '&filter_order_from_id=' + encodeURIComponent(filter_order_from_id);
            }


             var filter_order_to_id = $('input[name=\'filter_order_to_id\']').val();

            if (filter_order_to_id) {
                url += '&filter_order_to_id=' + encodeURIComponent(filter_order_to_id);
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
            
            var filter_delivery_time_slot = $('select[name=\'filter_delivery_time_slot\']').val();

            if (filter_delivery_time_slot) {
                url += '&filter_delivery_time_slot=' + encodeURIComponent(filter_delivery_time_slot);
            }

            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment) {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }



            var filter_order_status = $('select[name=\'filter_order_status\']').val();

            if (filter_order_status != '*') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }


            var filter_order_type = $('select[name=\'filter_order_type\']').val();

            if (filter_order_type != '*') {
                url += '&filter_order_type=' + encodeURIComponent(filter_order_type);
            }


              var filter_order_placed_from = $('select[name=\'filter_order_placed_from\']').val();

            if (filter_order_placed_from != '*') {
                url += '&filter_order_placed_from=' + encodeURIComponent(filter_order_placed_from);
            }
            
            var filter_paid = $('select[name=\'filter_paid\']').val();

            if (filter_paid != '*' && filter_paid != '') {
                url += '&filter_paid=' + encodeURIComponent(filter_paid);
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


           $('input[name=\'filter_payment\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletepayment&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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
            },
            'select': function (item) {
                $('input[name=\'filter_payment\']').val(item['label']);
            }
        });
        
        //--></script> 
    <script type="text/javascript"><!--
  $('input[name^=\'selected\']').on('change', function () {

            $('#button-shipping, #button-invoice').prop('disabled', true);
            $('#button-invoice-pdf').prop('disabled', true);
            $('#button-bulkdeliveryrequest').prop('disabled', true);
            $('#button-status-update').prop('disabled', true);
            $('#button-status-update-transit').prop('disabled', true);
            
            var selected = $('input[name^=\'selected\']:checked');
            $('input[name=\'order_delivery_count\']').val('');


            if (selected.length) {
                $('#button-invoice').prop('disabled', false);
                $('#button-invoice-pdf').prop('disabled', false);
                $('#button-bulkdeliveryrequest').prop('disabled', false);
                $('#button-status-update').prop('disabled', false);
                $('#button-status-update-transit').prop('disabled', false);
             $('input[name=\'order_delivery_count\']').val((selected.length)+' -orders selected');

            }
                    
            for (i = 0; i < selected.length; i++) {

                
                if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
                    $('#button-shipping').prop('disabled', false);

                   // break; break is commented to continue iteration for other checks
                }
                 //alert($(selected[i]).parent().find('input[name^=\'order_status1\']').val());
                $selected_order_status=$(selected[i]).parent().find('input[name^=\'order_status1\']').val();
                $selected_order_delivery_id=$(selected[i]).parent().find('input[name^=\'order_delivery_ids\']').val();
                
                //alert($selected_order_delivery_id);
               
                   if (($selected_order_status!=1 ) ) {//&& $selected_order_status!=4
                    
                    $('#button-bulkdeliveryrequest').prop('disabled', true);
                   $('input[name=\'order_delivery_count\']').val('one or few orders not eligible');

                   
                }
                else if($selected_order_delivery_id != ''  )
                {
                      $('#button-bulkdeliveryrequest').prop('disabled', true);
                   $('input[name=\'order_delivery_count\']').val('one or few orders not eligible');

                }
                
               
            }
            
            
        selected_order_ids = [];       
        $('input[name="selected[]"]:checked').each(function() {
        console.log('Hi');    
        console.log(this.value);
        console.log('Hi');    
        selected_order_ids.push($(this).val());
        console.log(selected_order_ids);
        });
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
        
    
    <div class="modal fade" id="driverModal_new_two" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:330px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>Save Driver Details</h2>
                                          </br> 
                                    </div>
                                    <div id="driverModal-new-messages" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="driverModal-new-success-messages" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="driverModal-new-form" action="" method="post" enctype="multipart/form-data">
                                            <div class="form-row">
                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <label> Vehicle Number </label>
                                                    <div class="col-md-12" >
                                                        <div class="pull-right">
                                                        <button type="button" id="dispatchplanning" data-url="<?php echo $dispatchplanning; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Dispatch Planning"><i class="fa fa-random"></i></button>
                                                        </div>
                                                    <div style="width:88%;margin-right:10px;">
                                                        <select  name="order_vehicle_numbers_two" id="order_vehicle_numbers_two" class="form-control" required="">
                                                        </select> 
                                                    </div>
                                                       
                                                    <br/> </div>
                                                </div>
                                            </div>

                                                 <div class="form-row">
                                                <div class="form-group" id="div_deliverycharge">
                                                    <label> Delivery Charge </label>

                                                    <div class="col-md-12">
                                                        <input id="order_delivery_charge_two" maxlength="10" required style="max-width:100% ;" name="order_delivery_charge_two" type="number" placeholder="Delivery Charge" class="form-control input-md" required>
                                                    <br/> </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-6"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: left; margin-top: 10px; height: 45px;border-radius:20px">Close</button>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <button id="driver-new-button" name="driver-new-button" onclick="savedriverdetails_new_two()" type="button" class="btn btn-lg btn-success"  style="width:65%; float:right;  margin-top: 10px; height: 45px;border-radius:20px">Save</button>
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
     
     
    <div class="modal fade" id="missingproductsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="height:150px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>Missing Order Products</h2>
                                          </br> 
                                    </div>
                                    <div id="driverModal-messages" style="color: red;text-align:center;font-size: 15px;">This Order Have Missing Products, Please Update Order Invoice.</div>
                                </div>
                            <!-- next div code -->
                            <div class="form-group">
                                <div class="col-md-6"> 
                                    <button type="button" id="missing-close-buttons" name="missing-close-buttons" class="btn btn-lg btn-success" data-dismiss="modal" style="width:50%; float: left;  margin-top: 10px; height: 45px;border-radius:20px">Close</button>
                                </div>
                                <div class="col-md-6"> 
                                    <button id="missing-button" data-orderid="" data-order-invoice="" name="missing-button" type="button" class="btn btn-lg btn-success"  style="width:65%; float:right;  margin-top: 10px; height: 45px;border-radius:20px">Edit Invoice</button>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
    </div>                                                   
                                                        
     <div class="modal fade" id="driverModal_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:330px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>Save Driver Details</h2>
                                          </br> 
                                    </div>
                                    <div id="driverModal-messages" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="driverModal-success-messages" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="driverModal-form" action="" method="post" enctype="multipart/form-data">
                                            <div class="form-row">
                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <label> Vehicle Number </label>
                                                    <div class="col-md-12" >
                                                        <!--<div class="pull-right">
                                                        <button id="new-vehicle-button" name="new-vehicle-button" type="button" data-toggle="modal" data-dismiss="modal" title="add new vehicle" data-target="#vehicleModal" class="btn btn-lg btn-success"><i class="fa fa-plus"></i></button>
                                                        </div>-->
                                                        <div class="pull-right">
                                                        <button type="button" id="dispatchplanning_new" data-url="<?php echo $dispatchplanning; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Dispatch Planning"><i class="fa fa-random"></i></button>
                                                        </div>
                                                        <!--<input id="order_vehicle_number" maxlength="10" required style="max-width:100% ;" name="order_vehicle_number" type="text" placeholder="Vehicle Number" class="form-control input-md" required>-->
                                                    <div style="width:88%;margin-right:10px;" >
                                                        <input id="order_id"   name="order_id" type="hidden"  class="form-control input-md" required>
                                                        <input id="order_delivery_date"   name="order_delivery_date" type="hidden"  class="form-control input-md" required>
                                                        <input id="updateDeliveryDate"   name="updateDeliveryDate" type="hidden" value=0 class="form-control input-md" required>
                                                        <select  name="order_vehicle_numbers" id="order_vehicle_numbers" class="form-control" required="">
                                                        </select> 
                                                    </div>
                                                       
                                                    <br/> </div>
                                                </div>
                                            </div>

                                                 <div class="form-row">
                                                <div class="form-group" id="div_deliverycharge">
                                                    <label> Delivery Charge </label>

                                                    <div class="col-md-12">
                                                        <input id="order_delivery_charge" maxlength="10" required style="max-width:100% ;" name="order_delivery_charge" type="number" placeholder="Delivery Charge" class="form-control input-md" required>
                                                    <br/> </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-6"> 
                                                        <button type="button" id="driver-buttons" name="driver-buttons" onclick="savedriverdetail_new()" class="btn btn-lg btn-success" data-dismiss="modal" style="width:50%; float: left;  margin-top: 10px; height: 45px;border-radius:20px">Save & Close</button>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <button id="driver-button" name="driver-button" onclick="savedriverdetails_new()" type="button" class="btn btn-lg btn-success"  style="width:65%; float:right;  margin-top: 10px; height: 45px;border-radius:20px">Save & Print Invoice</button>
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
    
    
    <div class="modal fade" id="neworderprocessingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                    <div id="orderprocessingModal-messages" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="orderprocessingModal-success-messages" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="neworderprocessingModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="input-order-status" class="control-label"> Order Processing Group </label>
                                                    <div class="col-md-12">
                                                        <!--<input id="order_delivery_executive" maxlength="30" required style="max-width:100% ;" name="order_delivery_executive" type="text" placeholder="Delivery Executive" class="form-control" data_delivery_executive_id="" required>-->
                                                        <select name="new_order_processing_group_id" id="new_order_processing_group_id" class="form-control" required="">
                                                        <option> Select Order Processing Group </option>
                                                        <?php foreach ($order_processing_groups as $order_processing_group) { ?>
                                                        <option value="<?php echo $order_processing_group['order_processing_group_id']; ?>"><?php echo $order_processing_group['order_processing_group_name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    <br/></div>
                                                </div><br/><br/>
                                                
                                                <div class="form-group">
                                                    <label > Order Processor </label>
                                                    <div class="col-md-12">
                                                        <!--<input id="order_driver" maxlength="30" required style="max-width:100% ;" name="order_driver" type="text" placeholder="Driver" class="form-control" data_driver_id="" required>-->
                                                        <select name="new_order_processor_id" id="new_order_processor_id" class="form-control" required="">
                                                        <option> Select Order Processor </option>
                                                        </select>
                                                    <br/></div>
                                                </div><br/><br/>

                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: left; margin-top: 10px; height: 45px;border-radius:20px">Close</button>
                                                        <button id="new-driver-button" name="new-orderprocessing-button" onclick="saveorderprocessingdetailsnew()" type="button" class="btn btn-lg btn-success"  style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Save</button>
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
    
    <div class="modal fade" id="store_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> 
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Order Products List</h4>
                                </div>
                                <div class="alert alert-danger missed" style="display:none;">
                                </div>
                                <div class="alert alert-success missed" style="display:none;">
                                </div>                        
                                <div class="modal-body orderproducts" style="overflow-y: auto;overflow-x: hidden;max-height:400px">

                                    <div class="message_wrapper"></div>

                                    <div class="form-group">
                                        <input type="text" name="store" value="" placeholder="Store name" id="input-product" class="form-control" />
                                        <div id="store-list" class="well well-sm" style="max-width: 100%; height: 150px; overflow: auto;">
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                      <button id="addtomissingproduct" type="button" onclick="addtomissingproduct();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Add To Missing Products">Save To Missing Products</button>
                                   </div>
                            </div>
                        </div>
                    </div> <!-- /.modal -->


 <!---modal popup--->
                    <div class="phoneModal-popup">
        <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:525px;">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>  Save Vehicle     </h2>
                                          </br> 
                                    </div>
                                    <div id="vehicleModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="vehicleModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="vehicleModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            
                                            

                                             <div class="form-group required col-md-12">
                        <label class="col-sm-4 control-label" for="input-make"><?php echo $entry_make; ?></label>
                        <div class="col-sm-8">
                          <input type="text" name="make" value="<?php echo $make; ?>" placeholder="<?php echo $entry_make; ?>" id="input-make" class="form-control" />
                          <?php if ($error_make) { ?>
                          <div class="text-danger"><?php echo $error_make; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <br>

                      <div class="form-group required col-md-12">
                        <label class="col-sm-4 control-label" for="input-model"><?php echo $entry_model; ?></label>
                        <div class="col-sm-8">
                          <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                          <?php if ($error_model) { ?>
                          <div class="text-danger"><?php echo $error_model; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <br>

                      <div class="form-group required col-md-12">
                        <label class="col-sm-4 control-label" for="input-registration-number"><?php echo $entry_registration_number; ?></label>
                        <div class="col-sm-8">
                          <input type="text" name="registration_number" value="<?php echo $registration_number; ?>" placeholder="<?php echo $entry_registration_number; ?>" id="input-registration-number" class="form-control" />
                          <?php if ($error_registration_number) { ?>
                          <div class="text-danger"><?php echo $error_registration_number; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <br>

                      <div class="form-group required col-md-12">
                        <label class="col-sm-4 control-label" for="input-registration-date"><?php echo $entry_registration_date; ?></label>
                        <div class="col-sm-8">
                          <div class="input-group date">  
                          <input type="text" name="registration_date" value="<?php echo $registration_date; ?>" placeholder="<?php echo $entry_registration_date; ?>" id="input-registration-date" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span></div>
                          <?php if ($error_registration_date) { ?>
                          <div class="text-danger"><?php echo $error_registration_date; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <br>

                      <div class="form-group required col-md-12">
                        <label class="col-sm-4 control-label" for="input-registration-validity-upto"><?php echo $entry_registration_validity_upto; ?></label>
                        <div class="col-sm-8">
                            <div class="input-group date">
                                <input type="text" name="registration_validity_upto" value="<?php echo $registration_validity_upto; ?>" placeholder="<?php echo $entry_registration_validity_upto; ?>" data-date-format="YYYY-MM-DD" id="registration_validity_upto" class="form-control" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span></div>
                        <?php if ($error_registration_validity_upto) { ?>
                          <div class="text-danger"><?php echo $error_registration_validity_upto; ?></div>
                        <?php  } ?>
                        </div>
                      </div>
                      <!-- start -->
                      

                      <!-- end -->
                      <div class="form-group col-md-12">
                        <label class="col-sm-4 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-md-8" style="max-width: 250px">
                          <select name="status" id="input-status" class="form-control">
                            <?php if (!($status)) { ?>
                            <option value="1" selected="selected">Enabled</option>
                            <option value="0">Disabled</option>
                            <?php } else { ?>
                            <option value="1">Enabled</option>
                            <option value="0" selected="selected">Disabled</option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                       <div class="form-group">
                                                    <div class="col-md-6"> 
                                                        <button type="button" id="vehicle-buttons" name="vehicle-buttons" onclick="savevehicledetails()" class="btn btn-lg btn-success"  style="width:50%; float: left;  margin-top: 10px; height: 45px;border-radius:20px">Save & Close</button>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <button id="vehicle-button" name="vehicle-button" onclick="closevehicledetails()" type="button" class="btn btn-lg btn-success"  style="width:65%; float:right;  margin-top: 10px; height: 45px;border-radius:20px">Close</button>
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

    <!--modal popup-->
<script  type="text/javascript">
$('a[id^=\'download_product_list\']').on('click', function (e) {
e.preventDefault();
var product_list = $(this).attr("data-order-product-list");
var order_id = $(this).data('orderid');
console.log(order_id);
console.log(product_list);
window.open(product_list, '_blank');
});
        
$('a[id^=\'assign_to_amitruck\']').on('click', function (e) {
e.preventDefault();
console.log($(this).data('orderid'));
console.log($(this).data('ordertotal'));

                $.ajax({
		url: 'index.php?path=amitruck/amitruck/createDelivery&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + encodeURIComponent($(this).data('orderid')) + '&order_total='+ encodeURIComponent($(this).data('ordertotal')),
		beforeSend: function() {
                // setting a timeout
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                },
                success: function(json) {	 
                    console.log(json.status);
                    
                    if(json.status == 200) {
                    $('.alert').html('Order assigned to delivery partner!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    alert('Order assigned to delivery partner!');

                    setTimeout(function(){ window.location.reload(false); }, 1500);
                    }
                    else{
                        alert('Amitruck API down!');

                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			   console.log(xhr);
		}
                }); 

});



 $('input[name^=\'selectedproducts\']').on('change', function () {            
            var selectedproducts = $('input[name^=\'selectedproducts\']:checked');  

        });
        $('input[name^=\'selectedproducts\']:first').trigger('change');



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

function savedriverdetails_new() { 
 
    $('#driverModal-messages').html('');
    $('#driverModal-success-messages').html('');
   var order_id = $('input[name="order_id"]').val();
   var order_delivery_date = $('input[name="order_delivery_date"]').val();  
   var updateDeliveryDate = $('input[name="updateDeliveryDate"]').val();  
 
   var invoice = $('input[name="invoice_custom"]').val();
   var vehicle_number =  $('select[name="order_vehicle_numbers"]').val();
   var delivery_charge =  $('input[name="order_delivery_charge"]').val();
    console.log(vehicle_number);
    console.log(delivery_charge);

              console.log($('#driverModal-form').serialize());
                 if (isNaN(order_id) || vehicle_number == '' || vehicle_number.length == 0 || order_id < 0 || order_id == '' || vehicle_number == '0' ) {
                      $('#driverModal-messages').html("Please enter data");
                       return false;
                } 
                else{
                  //  return;
                var clicked_orderid = order_id;
                $.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=4&notify=1',
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
                    data:{ order_id : order_id, vehicle_number : vehicle_number, delivery_charge : delivery_charge ,updateDeliveryDate:updateDeliveryDate},
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            $('#driverModal-success-messages').html('Saved Successfully');
                            
                            //ORDER STATUS UPDATE TO TRANSIT
                            $.ajax({
		            url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		            type: 'post',
		            dataType: 'json',
		            data: 'order_status_id=4&notify=1',
		            success: function(json) {	 
                            console.log(json);
		            },			
		            error: function(xhr, ajaxOptions, thrownError) {		
			 
		            }
                            });
                            //ORDER STATUS UPDATE TO TRANSIT
                            
                            window.open(invoice, '_blank');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                            $('#driverModal-success-messages').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                $('#driverModal-messages').html("Please try again");
                                    return false;
                                }
                });
                }
               
}

function savedriverdetail_new() { 
 
    $('#driverModal-messages').html('');
    $('#driverModal-success-messages').html('');
   var order_id = $('input[name="order_id"]').val();
   var invoice = $('input[name="invoice_custom"]').val();
   var order_delivery_date = $('input[name="order_delivery_date"]').val();  
   var updateDeliveryDate = $('input[name="updateDeliveryDate"]').val();  
 
   var vehicle_number =  $('select[name="order_vehicle_numbers"]').val();
   var delivery_charge =  $('input[name="order_delivery_charge"]').val();
    console.log(vehicle_number);
    console.log(delivery_charge);

              console.log($('#driverModal-form').serialize());
 
                if (isNaN(order_id) || vehicle_number == '' || vehicle_number.length == 0 || order_id < 0 || order_id == '' || vehicle_number == '0' ) {

                   
                      $('#driverModal-messages').html("Please enter data");
                       return false;
                } 
                else{
                //return;
                var clicked_orderid = order_id;
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                $.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=4&notify=1',
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
                    data:{ order_id : order_id, vehicle_number : vehicle_number, delivery_charge : delivery_charge,updateDeliveryDate:updateDeliveryDate },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            
                            //ORDER STATUS UPDATE TO TRANSIT
                            $.ajax({
		            url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		            type: 'post',
		            dataType: 'json',
		            data: 'order_status_id=4&notify=1',
		            success: function(json) {	 
                            console.log(json);
		            },			
		            error: function(xhr, ajaxOptions, thrownError) {		
			 
		            }
                            });
                            //ORDER STATUS UPDATE TO TRANSIT
                            
                            $('#driverModal-success-messages').html('Saved Successfully');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                            $('#driverModal-success-messages').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    

                                 // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                $('#driverModal-messages').html("Please try again");
                                    return false;
                                }
                });
                }
                
$('#driverModal-form')[0].reset();               
}

function savedriverdetails_new_two() { 
 
    $('#driverModal-new-messages').html('');
    $('#driverModal-new-success-messages').html('');
    var order_id = selected_order_ids;
    var updateDeliveryDate = 0;
 
    var vehicle_number =  $('select[name="order_vehicle_numbers_two"]').val();
    var delivery_charge =  $('input[name="order_delivery_charge_two"]').val();
    console.log(vehicle_number);
    console.log(delivery_charge);
    console.log($('#driverModal-new-form').serialize());
 
                if (vehicle_number == '' || vehicle_number.length == 0 || order_id == '' || vehicle_number == '0' ) {
                $('#driverModal-new-messages').html("Please enter data");
                return false;
                } 
                else{
                var clicked_orderid = order_id.toString().replace('on,','');
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                $.ajax({
		url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/intransit&order_id='+clicked_orderid+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=4&notify=0&vehicle_number='+vehicle_number+'&delivery_charge='+delivery_charge,
		beforeSend: function() {
                $('.alert').html('Please wait your request is processing!');
                $('#driverModal-new-success-messages').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                },
                success: function(json) {	 
                console.log(json);
                $.ajax({
                    url: 'index.php?path=sale/order/SaveOrUpdateOrderDriverVehicleDetailsBulk&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data:{ order_id : order_id, vehicle_number : vehicle_number, delivery_charge : delivery_charge,updateDeliveryDate:updateDeliveryDate },
                    beforeSend: function() {
                    $('#driverModal-new-success-messages').html('Please wait your request is processing!');
                    },
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                            $('#driverModal-new-success-messages').html('Saved Successfully');
                            setTimeout(function(){ window.location.reload(false); }, 1500);
                        }
                        else {
                            $('#driverModal-new-success-messages').html('Please try again');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {    
                    // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                    $('#driverModal-new-messages').html("Please try again");
                    return false;
                    }
                });
                $('.alert').html('Order status updated successfully!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
		}
                }); 
                }
                
$('#driverModal-new-form')[0].reset();               
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
		beforeSend: function() {
                if($('select[id=\'input-order-status'+clicked_orderid+'\'] option:selected').text() == 'Delivered')
                    {
                    $.ajax({
                    url: 'index.php?path=sale/customer_pezesha/applyloanfordeliveredorder&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data: { order_id : clicked_orderid },
                    success: function(json) {
                    if(json.status == 422) {    
                    $.each(json.errors, function (key, data) {
                    alert(key+' : '+data);
                    })
                    }
    
                    if(json.status == 200 && json.response_code == 0) {    
                    alert(json.message);
                    }
    
                    }
                    });    
                    }
                },
                complete: function() {
              
                },
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
$('select[id^=\'new_order_processing_group_id\']').on('change', function (e) {
    var order_processing_group_id = $('select[id=\'new_order_processing_group_id\'] option:selected').val();
    $.ajax({
      url: 'index.php?path=orderprocessinggroup/orderprocessor/getAllOrderProcessors&token=<?php echo $token; ?>&order_processing_group_id='+order_processing_group_id,
      dataType: 'json',     
      success: function(json) {
//console.log(json.length);
var $select = $('#new_order_processor_id');
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




    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false,
     widgetParent: 'body'

        });

    setInterval(function() {
     location = location;
    }, 300 * 1000); // 60 * 1000 milsec
    
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
$('button[id^=\'missing-button\']').on('click', function (e) {
e.preventDefault();
console.log('EDIT INVOICE');
var edit_invoice = $(this).attr("data-order-invoice");
var order_id = $(this).attr("data-orderid");
window.open('index.php?path=sale/editinvoice/EditInvoice&token=<?php echo $token; ?>&order_id='+order_id, '_blank');
});
$('a[id^=\'updated_new_print_invoice\']').on('click', function (e) {
e.preventDefault();
var invoice = $(this).attr("data-order-invoice");
var order_id = $(this).attr("data-order-id");
var order_vendor = $(this).attr("data-order-vendor");
var order_delivery_date = $(this).attr("data-order-delivery-date");
var updateDeliveryDate=0;

var order_status = $('select[id=\'input-order-status'+order_id+'\'] option:selected').text();
var order_status_id_new = $('select[id=\'input-order-status'+order_id+'\'] option:selected').val();
console.log(order_status_id_new);


 $('select[name="order_delivery_executives"]').selectpicker('val', 0);
 $('select[name="order_drivers"]').selectpicker('val', 0);
 //$('input[name="order_vehicle_number"]').val('');
  $('#div_deliverycharge').hide();
 if(order_vendor=='Kwik Basket')
 { 
 $('#div_deliverycharge').show();
 }
var currentdate=new Date();
var dd = String(currentdate.getDate()).padStart(2, '0');
var mm = String(currentdate.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = currentdate.getFullYear();

const order_delivery_dateArray = order_delivery_date.split("/");
var dd2 = order_delivery_dateArray[0];
var mm2 = order_delivery_dateArray[1]; //January is 0!
var yyyy2 = order_delivery_dateArray[2];

currentdate = dd + '/' + mm + '/' + yyyy;
console.log(order_delivery_date);
console.log('beloe current');
var order_delivery_date_js = new Date(yyyy2, mm2, dd2);
var currentdate_js = new Date(yyyy, mm, dd);

console.log(currentdate);
console.log(order_delivery_date_js);
console.log(currentdate_js);
$.ajax({
      url: 'index.php?path=sale/order/getmissingorderproducts&token=<?php echo $token; ?>',
      dataType: 'json',
      cache: false,
      type: 'post',
      async: true,
      data: { 'order_id' : order_id },
      beforeSend: function() {
              
      },
      complete: function() {
              
      },
      success: function (json) {
      console.log('MISSING_PRODUCTS');        
      console.log(json);
      console.log('MISSING_PRODUCTS');
      if(json.data.missing_products_count > 0) {
      $('#missingproductsModal').modal('toggle');
      $('#missing-button').attr('data-orderid', order_id);
      $('#missing-button').attr('data-order-invoice', json.data.edit_invoice_url)
      return false;
      } else {
      if(new Date(yyyy2, mm2, dd2) > new Date(yyyy, mm, dd)) {
 if (confirm("Do you want to modify delivery date to current date")) {
 //continue;
updateDeliveryDate=1;
$('input[name="updateDeliveryDate"]').val(updateDeliveryDate);
 } else {
 return;
 }
 }
console.log("Do you want to modify delivery date to current date"); 
$.ajax({
                url: 'index.php?path=vehicles/dispatchplanning/getassignedvehicles&updateDeliveryDate='+updateDeliveryDate+'&order_id='+order_id+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    console.log(json.length);
                    if(json != null && json.length > 0) {
                    $('#driverModal-messages').html("");
                    $('#driver-buttons').prop('disabled', false);
                    $('#driver-button').prop('disabled', false);    
                    var option = '<option value="">Select Vehicle</option>';
                    for (var i=0;i<json.length;i++){
                           option += '<option value="'+ json[i].vehicle_id + '">' + json[i].registration_number + '</option>';
                    }
                    console.log(option);
                    var $select = $('#order_vehicle_numbers');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    } else {
                    if(order_status_id_new == 1) {
                    $('#driverModal-messages').html("Please Assign Vehicle To Dispatch Plan!");
                    $('#driver-buttons').prop('disabled', true);
                    $('#driver-button').prop('disabled', true);
                    }
                    }
            }
}); 
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
                    console.log(json.order_info.delivery_charges);
                    if(json.order_info.order_status == 'Order Approval Pending' || order_status == 'Order Approval Pending' || json.order_info.order_status == 'Order Recieved' || order_status == 'Order Recieved' || json.order_info.driver_id == null || json.order_info.vehicle_number == null || json.order_info.delivery_executive_id == null)
                    {
                    $('input[name="order_id"]').val(order_id);
                    $('input[name="updateDeliveryDate"]').val(updateDeliveryDate);
                    $('input[name="invoice_custom"]').val(invoice);
                    $('input[name="order_delivery_charge"]').val(json.order_info.delivery_charges);
                    $('#driverModal_new').modal('toggle');
                    if(json.order_info.order_status == 'Order Approval Pending' || order_status == 'Order Approval Pending' || json.order_info.order_status == 'Order Recieved' || order_status == 'Order Recieved') {
                    $('#driverModal-messages').html("Please Update Order Status As Order Processing!");
                    $('#driver-buttons').prop('disabled', true);
                    $('#driver-button').prop('disabled', true);
                    return false;
                    } else {
                    $('#driverModal-messages').html("");
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
      }
              
      }
}); 
console.log($(this).attr("data-order-id"));
});

function downloadOrdersonsolidated() {


             
            //const deliveryDate = $("#consolidated-order-sheet-datepicker").val();
                url = 'index.php?path=report/vendor_orders/consolidatedOrderSheetForOrders&token=<?php echo $token; ?>';
              var filter_order_status = $('select[name=\'filter_order_status\']').val();

            console.log(filter_order_status);

            if (filter_order_status != '*' && filter_order_status != '') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date != '*' && filter_delivery_date != '') {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }
            
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company != '*' && filter_company != '') {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
            
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer != '*' && filter_customer != '') {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }
            
            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total != '*' && filter_total != '') {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }
            
            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method != '*' && filter_delivery_method != '') {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }
            
            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment != '*' && filter_payment != '') {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }
            
            var filter_order_type = $('select[name=\'filter_order_type\']').val();

            if (filter_order_type != '*' && filter_order_type != '') {
                url += '&filter_order_type=' + encodeURIComponent(filter_order_type);
            }
            

              var filter_order_placed_from = $('select[name=\'filter_order_placed_from\']').val();

            if (filter_order_placed_from != '*' && filter_order_placed_from != '') {
                url += '&filter_order_placed_from=' + encodeURIComponent(filter_order_placed_from);
            }
            

            
            var filter_paid = $('select[name=\'filter_paid\']').val();

            if (filter_paid != '*' && filter_paid != '') {
                url += '&filter_paid=' + encodeURIComponent(filter_paid);
            }
            
            var filter_order_from_id = $('input[name=\'filter_order_from_id\']').val();

            if (filter_order_from_id != '*' && filter_order_from_id != '') {
                url += '&filter_order_from_id=' + encodeURIComponent(filter_order_from_id);
            }
            
            var filter_order_to_id = $('input[name=\'filter_order_to_id\']').val();

            if (filter_order_to_id != '*' && filter_order_to_id != '') {
                url += '&filter_order_to_id=' + encodeURIComponent(filter_order_to_id);
            }
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added != '*' && filter_date_added != '') {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end != '*' && filter_date_added_end != '') {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id != '*' && filter_order_id != '') {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            
            var filter_delivery_time_slot = $('select[name=\'filter_delivery_time_slot\']').val();

            if (filter_delivery_time_slot != '') {
                url += '&filter_delivery_time_slot=' + encodeURIComponent(filter_delivery_time_slot);
            }
            
            var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i){
            if(n.value!='on')
            {
                return n.value;
                }
            }).join(',');
            console.log(selected_order_id);
            
            if (selected_order_id != '') {
                url += '&selected_order_id=' + encodeURIComponent(selected_order_id);
            }
            
             
              if((filter_order_from_id==''||filter_order_to_id=='') && filter_delivery_date=='' && filter_order_id=='' && selected_order_id=='')
            {
                if((filter_date_added=='' || filter_date_added_end=='') && filter_delivery_date=='')
                {
                    alert("Please select  date filters or other ");
                    return;
                }
           
                // alert(filter_date_added_end);

                    if(filter_date_added_end!='' && filter_date_added!='')
                    {
                        
                        const date1 = new Date(filter_date_added);
                        const date2 = new Date(filter_date_added_end);
                        const diffTime = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        console.log(diffTime + " milliseconds");
                        console.log(diffDays + " days");
                        if(diffDays<0)
                        {
                        alert("Please select proper start & end date filters");
                                        return;
                        }
                        if(diffDays>60)
                        {
                            alert("Duration between start & end date filters should be less than 60 days");
                                        return;
                        }
                    }
            }


            location = url;
            
}
function downloadOrders() {


             
            //const deliveryDate = $("#consolidated-order-sheet-datepicker").val();
                url = 'index.php?path=report/vendor_orders/downloadorders&token=<?php echo $token; ?>';
              var filter_order_status = $('select[name=\'filter_order_status\']').val();

            console.log(filter_order_status);

            if (filter_order_status != '*' && filter_order_status != '') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date != '*' && filter_delivery_date != '') {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }
            
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company != '*' && filter_company != '') {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
            
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer != '*' && filter_customer != '') {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }
            
            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total != '*' && filter_total != '') {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }
            
            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method != '*' && filter_delivery_method != '') {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }
            
            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment != '*' && filter_payment != '') {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }
            
            var filter_order_type = $('select[name=\'filter_order_type\']').val();

            if (filter_order_type != '*' && filter_order_type != '') {
                url += '&filter_order_type=' + encodeURIComponent(filter_order_type);
            }
            
              var filter_order_placed_from = $('select[name=\'filter_order_placed_from\']').val();

            if (filter_order_placed_from != '*' && filter_order_placed_from != '') {
                url += '&filter_order_placed_from=' + encodeURIComponent(filter_order_placed_from);
            }
            
            
            var filter_paid = $('select[name=\'filter_paid\']').val();

            if (filter_paid != '*' && filter_paid != '') {
                url += '&filter_paid=' + encodeURIComponent(filter_paid);
            }
            
            var filter_order_from_id = $('input[name=\'filter_order_from_id\']').val();

            if (filter_order_from_id != '*' && filter_order_from_id != '') {
                url += '&filter_order_from_id=' + encodeURIComponent(filter_order_from_id);
            }
            
            var filter_order_to_id = $('input[name=\'filter_order_to_id\']').val();

            if (filter_order_to_id != '*' && filter_order_to_id != '') {
                url += '&filter_order_to_id=' + encodeURIComponent(filter_order_to_id);
            }
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added != '*' && filter_date_added != '') {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end != '*' && filter_date_added_end != '') {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id != '*' && filter_order_id != '') {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            
            var filter_delivery_time_slot = $('select[name=\'filter_delivery_time_slot\']').val();
            
            if (filter_delivery_time_slot != '') {
                url += '&filter_delivery_time_slot=' + encodeURIComponent(filter_delivery_time_slot);
            }
            
            var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i){
            return n.value;
            }).join(',');
            console.log(selected_order_id);
            
            if (selected_order_id != '') {
                url += '&selected_order_id=' + encodeURIComponent(selected_order_id);
            }
            

              if((filter_order_from_id==''||filter_order_to_id=='') && filter_delivery_date=='' && filter_order_id=='' && selected_order_id=='')
            {
                if((filter_date_added=='' || filter_date_added_end=='') && filter_delivery_date=='')
                {
                    alert("Please select  date filters or other ");
                    return;
                }
           
                // alert(filter_date_added_end);

                    if(filter_date_added_end!='' && filter_date_added!='')
                    {
                        
                        const date1 = new Date(filter_date_added);
                        const date2 = new Date(filter_date_added_end);
                        const diffTime = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        console.log(diffTime + " milliseconds");
                        console.log(diffDays + " days");
                        if(diffDays<0)
                        {
                        alert("Please select proper start & end date filters");
                                        return;
                        }
                        if(diffDays>60)
                        {
                            alert("Duration between start & end date filters should be less than 60 days");
                                        return;
                        }
                    }
            }


            location = url;
            
}

function downloadOrderStickers() {


             
            //const deliveryDate = $("#consolidated-order-sheet-datepicker").val();
                url = 'index.php?path=report/vendor_orders/downloadordersstickers&token=<?php echo $token; ?>';
              var filter_order_status = $('select[name=\'filter_order_status\']').val();

            console.log(filter_order_status);

            if (filter_order_status != '*' && filter_order_status != '') {
                url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
            }

            var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

            if (filter_delivery_date != '*' && filter_delivery_date != '') {
                url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
            }
            
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company != '*' && filter_company != '') {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
            
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer != '*' && filter_customer != '') {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }
            
            var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total != '*' && filter_total != '') {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }
            
            var filter_delivery_method = $('input[name=\'filter_delivery_method\']').val();

            if (filter_delivery_method != '*' && filter_delivery_method != '') {
                url += '&filter_delivery_method=' + encodeURIComponent(filter_delivery_method);
            }
            
            var filter_payment = $('input[name=\'filter_payment\']').val();

            if (filter_payment != '*' && filter_payment != '') {
                url += '&filter_payment=' + encodeURIComponent(filter_payment);
            }
            
            var filter_order_type = $('select[name=\'filter_order_type\']').val();

            if (filter_order_type != '*' && filter_order_type != '') {
                url += '&filter_order_type=' + encodeURIComponent(filter_order_type);
            }
            
              var filter_order_placed_from = $('select[name=\'filter_order_placed_from\']').val();

            if (filter_order_placed_from != '*' && filter_order_placed_from != '') {
                url += '&filter_order_placed_from=' + encodeURIComponent(filter_order_placed_from);
            }
            
            
            var filter_paid = $('select[name=\'filter_paid\']').val();

            if (filter_paid != '*' && filter_paid != '') {
                url += '&filter_paid=' + encodeURIComponent(filter_paid);
            }
            
            var filter_order_from_id = $('input[name=\'filter_order_from_id\']').val();

            if (filter_order_from_id != '*' && filter_order_from_id != '') {
                url += '&filter_order_from_id=' + encodeURIComponent(filter_order_from_id);
            }
            
            var filter_order_to_id = $('input[name=\'filter_order_to_id\']').val();

            if (filter_order_to_id != '*' && filter_order_to_id != '') {
                url += '&filter_order_to_id=' + encodeURIComponent(filter_order_to_id);
            }
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added != '*' && filter_date_added != '') {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end != '*' && filter_date_added_end != '') {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }
            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id != '*' && filter_order_id != '') {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            
            var filter_delivery_time_slot = $('select[name=\'filter_delivery_time_slot\']').val();
            
            if (filter_delivery_time_slot != '') {
                url += '&filter_delivery_time_slot=' + encodeURIComponent(filter_delivery_time_slot);
            }
            
            var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i){
            return n.value;
            }).join(',');
            console.log(selected_order_id);
            
            if (selected_order_id != '') {
                url += '&selected_order_id=' + encodeURIComponent(selected_order_id);
            }
            

              if((filter_order_from_id==''||filter_order_to_id=='') && filter_delivery_date=='' && filter_order_id=='' && selected_order_id=='')
            {
                if((filter_date_added=='' || filter_date_added_end=='') && filter_delivery_date=='')
                {
                    alert("Please select  date filters or other ");
                    return;
                }
           
                // alert(filter_date_added_end);

                    if(filter_date_added_end!='' && filter_date_added!='')
                    {
                        
                        const date1 = new Date(filter_date_added);
                        const date2 = new Date(filter_date_added_end);
                        const diffTime = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        console.log(diffTime + " milliseconds");
                        console.log(diffDays + " days");
                        if(diffDays<0)
                        {
                        alert("Please select proper start & end date filters");
                                        return;
                        }
                        if(diffDays>60)
                        {
                            alert("Duration between start & end date filters should be less than 60 days");
                                        return;
                        }
                    }
            }


            location = url;
            
}

$('a[id^=\'order_products_list\']').on('click', function (e) {
e.preventDefault();
$('#store_modal').modal('toggle');
$('.alert.alert-danger.missed').hide();
$('.alert.alert-success.missed').hide();
$('.alert.alert-danger.missed').html('');
$('.alert.alert-success.missed').html('');
 var order_id_val=$(this).attr('data-orderid');
           console.log(order_id_val);
$('.orderproducts').html('');
	   $.ajax({
                    url: 'index.php?path=sale/order/getOrderProducts&token=<?= $token ?>',
                    dataType: 'html',
                    data: { order_id : order_id_val },
                    success: function(json) {
                        
					   $('.orderproducts').html(json);
                    },
					error: function(json) {
					 console.log('html',json);
					  $('.orderproducts').html(json);
                    }
         });

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
        $('form').attr('action','index.php?path=catalog/product/copy&token=<?= $token ?>').submit();
    } else{        
        $('.message_wrapper').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close">&times</button><ul class="list list-unstyled">'+$error+'</ul></div>');
    }
}




function addtomissingproduct() {
              $('.alert.alert-danger.missed').hide();
              $('.alert.alert-success.missed').hide();
              url = 'index.php?path=sale/order_product_missing/addtomissingproduct&token=<?php echo $token; ?>';
                var req_quantity="0";
                var order_id = $('#missed_products_order_id').val();
                var selected_order_product_id = $.map($('input[name="selectedproducts[]"]:checked'), function(n, i){
            
                var req_quantity_single =$('input[id^="updated_quantity_'+n.value+'"]').val(); 
                if(req_quantity!="0")
                {
                req_quantity=req_quantity+','+req_quantity_single;
                }
                else
                {
                req_quantity=req_quantity_single;
                }
           console.log(req_quantity);
           console.log("req_quantity");
            return n.value;
            }).join(','); 
           console.log(req_quantity);
           console.log(selected_order_product_id);
                

            if(selected_order_product_id=='' || selected_order_product_id==null)
            {
            $('.alert.alert-danger.missed').html('');
            $('.alert.alert-danger.missed').html('<i class="fa fa-exclamation-circle"></i> Warning: Please Select At Lease One Product!');
            $('.alert.alert-danger.missed').show();
            return;
            } 

             data = {
                selected :selected_order_product_id,
                quantityrequired: req_quantity,
                order_id : order_id
            }

           
            $.ajax({
                url: 'index.php?path=sale/order_product_missing/addtomissingproduct&token=<?php echo $token; ?>',
                type: 'post',
                dataType: 'json',
                data: data,
                beforeSend: function() {
                $('#addtomissingproduct').prop('disabled', true);
                $('.alert.alert-success.missed').html('');
                $('.alert.alert-success.missed').html('<i class="fa fa-exclamation-circle"></i> Success: Please wait your request is processing!');
                $('.alert.alert-success.missed').show();
                },
                complete: function() {
                $('#addtomissingproduct').prop('disabled', false);    
		},
                success: function(json) {
                console.log(json);
                if(json.status == 400) {
                $('.alert.alert-danger.missed').html('');
                $('.alert.alert-danger.missed').html('<i class="fa fa-exclamation-circle"></i> Warning: '+json.message+'!');
                $('.alert.alert-danger.missed').show();
                $('#addtomissingproduct').prop('disabled', false);
                }
                
                if(json.status == 200) {
                $('.alert.alert-success.missed').html('');
                $('.alert.alert-success.missed').html('<i class="fa fa-exclamation-circle"></i> Warning: '+json.message+'!');
                $('.alert.alert-success.missed').show();
                setTimeout(function(){
                $('#store_modal').modal('hide');
                }, 5000);
                setTimeout(function(){ window.location.reload(false); }, 6000);
                }
                //location=location;
                },			
                error: function(xhr, ajaxOptions, thrownError) {		
                    
                }       
        });
            
}


 
function validateFloatKeyPresswithVarient(el, evt, unitvarient) {

        
	 	 $optionvalue=unitvarient;
	 
	if($optionvalue=="Per Kg" || $optionvalue=="Kg")
	{
	 var charCode = (evt.which) ? evt.which : event.keyCode;
	 var number = el.value.split('.');
	 if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		 return false;
	 }
	 //just one dot
	 if(number.length>1 && charCode == 46){
		 return false;
	 }
	 //get the carat position
	 var caratPos = getSelectionStart(el);
	 var dotPos = el.value.indexOf(".");
	 if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
		 return false;
	 }
	 return true;
	}

	else{
	 var charCode = (evt.which) ? evt.which : event.keyCode;
	 if (charCode > 31 &&
	   (charCode < 48 || charCode > 57))
	   return false;
	   else
   
   return true;
	}
}
$('a.customer_verified').bind("click", function (e) {
e.preventDefault();
});

 
function savevehicledetails() { 
 
    $('#vehicleModal-message').html('');
    $('#vehicleModal-success-message').html('');
   var optionText = $('input[name="registration_number"]').val();
   var vehicle_enabled = $('select[name="status"]').val();
     //console.log(optionText);
    //console.log(vehicle_enabled); 
    console.log($('#vehicleModal-form').serialize());
 
                //if (isNaN(delivery_executive_id) || isNaN(order_id) || isNaN(driver_id) || driver_id  <= 0 || driver_id == '' || vehicle_number == '' || vehicle_number.length == 0 || order_id < 0 || order_id == '' || delivery_executive_id < 0 || delivery_executive_id == ''|| delivery_executive_id == '0' || driver_id == '0' || vehicle_number == '0' ) {

                   
                   //   $('#driverModal-message').html("Please enter data");
                   //    return false;
               // } 
                //else
                {
                //return;
                //var clicked_orderid = order_id;
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                $.ajax({
		url: 'index.php?path=vehicles/vehicles_list/addVehicle&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		 data:$('#vehicleModal-form').serialize(),
		beforeSend: function() {
                // setting a timeout
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                },
                success: function(json) {	 
                    console.log(json);
                    if(json['error']!='')
                    { $('.alert').html(json['error']);
                    //alert(json['error']);
                    $('#vehicleModal-message').html(json['error']);

                    return;
                    }
                    else{
                    $('.alert').html('Vehicle Saved successfully!') ;
                    alert('Vehicle Saved successfully!');
                    //alert(vehicle_enabled);
                    if(vehicle_enabled==1){
                    var x = document.getElementById("order_vehicle_number");
                    var c = document.createElement("option");
                    c.text = optionText;
                    c.selected = "selected";
                    x.options.add(c);
                    $('.selectpicker').selectpicker('refresh');
                    }
                    
                   
                    
                    }
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                     $('#vehicleModal').modal('hide');
    $('#driverModal').modal('show');
   $('input[name="registration_number"]').val('');
   $('input[name="make"]').val('');
   $('input[name="model"]').val('');


                    //setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 return;
		}
                }); 
                    
                }
}

 
function closevehicledetails() { 
    $('#vehicleModal').modal('hide');
    $('#driverModal').modal('show');
    $('input[name="registration_number"]').val('');
   $('input[name="make"]').val('');
   $('input[name="model"]').val('');
}



 function createDeliveryRequest() 
    {   
            

             var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i){
            return n.value;
            }).join(',');
            console.log(selected_order_id);
            
            if (selected_order_id == '') {
                alert('No order selected');
                return;
                
            }
            
            

              $.ajax({
		url: 'index.php?path=amitruck/amitruck/createMultipleOrdersDelivery&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + encodeURIComponent(selected_order_id) ,
		beforeSend: function() {
                // setting a timeout
                $('.alert').html('Please wait your request is processing!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                },
                success: function(json) {	 
                    console.log(json.status);                    
                    if(json.status == 200) {
                    $('.alert').html('Order assigned to delivery partner!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    alert('Order(s) assigned to delivery partner!');
                    setTimeout(function(){ window.location.reload(false); }, 1500);
                    }
                    else{
                    alert('Amitruck API down!');
                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			   console.log(xhr);
		}
                }); 
                
    }

$('a[id^=\'update_vendor_order_status\']').on('click', function (e) {
e.preventDefault();
console.log($(this).data('orderid'));
var clicked_orderid = $(this).data('orderid');
var selected_order_status_id = $('select[id=\'input-vendor-order-status'+clicked_orderid+'\']').val();
console.log(clicked_orderid);
console.log(selected_order_status_id);
$.ajax({
		url: 'index.php?path=sale/order/updatevendororderstatus&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'vendor_order_status_id=' + encodeURIComponent($('select[id=\'input-vendor-order-status'+clicked_orderid+'\']').val()) + '&order_id='+clicked_orderid,
		success: function(json) {	 
                    console.log(json);
                    $('.alert').html('Vendor Order status updated successfully!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    setTimeout(function(){ window.location.reload(false); }, 1500);
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
});
});

$('#button-invoice-pdfs, #button-invoices').on('click', function (e) {
e.preventDefault();
$.ajax({
		url: 'index.php?path=sale/order/checkorderstatusvalidfordownloadpdf&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + selected_order_ids + '&order_status_id=14',
		success: function(json) {	 
                    console.log(json);
                    if(json.data.invalid_order_status_count > 0) {
                    $('.alert').html('Selected Orders Status Is Invalid!');
                    $(".alert").attr('class', 'alert alert-danger');
                    $(".alert").show();
                    return false;
                    }
                    
                    if(json.data.invalid_order_status_count == 0) {
                    $('#orderprocessingModal-messages').html('');
                    $('#new-driver-button').prop('disabled', false);
                    return true;
                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
});
});

$('#button-status-update').on('click', function (e) {
e.preventDefault();
console.log(selected_order_ids);
$('#orderprocessingModal-messages').html('');
$('#orderprocessingModal-success-messages').html('');
$('#new-driver-button').prop('disabled', false);
$('#neworderprocessingModal').modal('toggle');
$.ajax({
		url: 'index.php?path=sale/order/checkorderstatus&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + selected_order_ids + '&order_status_id=14',
		success: function(json) {	 
                    console.log(json);
                    if(json.data.invalid_order_status_count > 0) {
                    $('#orderprocessingModal-messages').html('Selected Orders Status Is Invalid!');
                    $('#new-driver-button').prop('disabled', true);
                    return false;
                    }
                    
                    if(json.data.invalid_order_status_count == 0) {
                    $('#orderprocessingModal-messages').html('');
                    $('#new-driver-button').prop('disabled', false);
                    return true;
                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
});
});

$('#button-status-update-transit').on('click', function (e) {
e.preventDefault();
console.log(selected_order_ids);
$('#driverModal-new-messages').html('');
$('#driverModal-new-success-messages').html('');
$('#driver-new-buttons').prop('disabled', false);
$('#driver-new-button').prop('disabled', false);
$('#driverModal_new_two').modal('toggle');
$.ajax({
		url: 'index.php?path=sale/order/checkorderstatusprocessing&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_id=' + selected_order_ids + '&order_status_id='+$('select[name=\'filter_order_status\']').val()+'&delivery_date='+$('input[name=\'filter_delivery_date\']').val()+'&delivery_time_slot='+$('select[name=\'filter_delivery_time_slot\']').val(),
		success: function(json) {	 
                    console.log(json);
                    if($('select[name=\'filter_order_status\']').val() == null || $('select[name=\'filter_order_status\']').val() != 1 || $('select[name=\'filter_order_status\']').val() == 'undefined') {
                    $('#driverModal-new-messages').html('Please Select Order Status As Order Processing From Filters!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if($('input[name=\'filter_delivery_date\']').val() == null || $('input[name=\'filter_delivery_date\']').val() == 'undefined') {
                    $('#driverModal-new-messages').html('Please Select Delivery Date!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if($('select[name=\'filter_delivery_time_slot\']').val() == null || $('select[name=\'filter_delivery_time_slot\']').val() == 'undefined') {
                    $('#driverModal-new-messages').html('Please Select Delivery Timeslot!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if(json.data.invalid_order_status_count > 0) {
                    $('#driverModal-new-messages').html('Selected Orders Status Is Invalid!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if(json.data.invalid_order_delivery_date_count > 0) {
                    $('#driverModal-new-messages').html('Selected Orders Delivery Date Should Not Be Greater Than Current Date!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if(json.data.invalid_order_delivery_timeslot_count > 1) {
                    $('#driverModal-new-messages').html('Selected Orders Delivery Timeslots Should Be Unique!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if(json.data.invalid_order_deliverydate_count > 1) {
                    $('#driverModal-new-messages').html('Selected Orders Delivery Dates Should Be Unique!');
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    return false;
                    }
                    
                    if(json.data.invalid_order_delivery_date_count == 0 && json.data.invalid_order_status_count == 0 && json.data.invalid_order_delivery_timeslot_count == 1 && json.data.invalid_order_deliverydate_count == 1) {
                    $('#driverModal-new-messages').html('');
                    $('#driver-new-buttons').prop('disabled', false);
                    $('#driver-new-button').prop('disabled', false);
                    console.log('DISPATCH');
                    $.ajax({
                    url: 'index.php?path=vehicles/dispatchplanning/getAssignedVehiclesNew&updateDeliveryDate=0&order_id='+selected_order_ids+'&delivery_time_slot='+$('select[name=\'filter_delivery_time_slot\']').val()+'&delivery_date='+$('input[name=\'filter_delivery_date\']').val()+'&token=<?php echo $token; ?>',
                    dataType: 'json',     
                    success: function(json) {
                    console.log(json);
                    console.log(json.length);
                    if(json != null && json.length > 0) {
                    $('#driverModal-new-messages').html("");
                    $('#driver-new-buttons').prop('disabled', false);
                    $('#driver-new-button').prop('disabled', false);    
                    var option = '<option value="">Select Vehicle</option>';
                    for (var i=0;i<json.length;i++){
                           option += '<option value="'+ json[i].vehicle_id + '">' + json[i].registration_number + '</option>';
                    }
                    console.log(option);
                    var $select = $('#order_vehicle_numbers_two');
                    $select.html('');
                    if(json != null && json.length > 0) {
                    $select.append(option);
                    }
                    $('.selectpicker').selectpicker('refresh');
                    } else {
                    $('#driverModal-new-messages').html("Please Assign Vehicle To Dispatch Plan!");
                    $('#driver-new-buttons').prop('disabled', true);
                    $('#driver-new-button').prop('disabled', true);
                    }
                    }
                    }); 
                    return true;
                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
		}
});
});

function saveorderprocessingdetailsnew() { 
 
    $('#orderprocessingModal-messages').html('');
    $('#orderprocessingModal-success-messages').html('');
    var order_processing_group_id =  $('select[name="new_order_processing_group_id"]').val();
    var order_processor_id =  $('select[name="new_order_processor_id"]').val();
    var order_processing_group_name = 'Order Processing Group : '+ $('select[name=\'new_order_processing_group_id\'] option:selected').text();
    console.log(order_processing_group_id);
    console.log(order_processor_id);
    console.log($('#neworderprocessingModal-form').serialize());
 
    if (isNaN(order_processor_id) || isNaN(order_processing_group_id) || order_processing_group_id  <= 0 || order_processing_group_id == '' || order_processor_id == '' || order_processor_id <= 0) {
    $('#orderprocessingModal-messages').html("Please enter data");
    return false;
    } 
    else{
    $.ajax({
    url: 'index.php?path=sale/order/api&token=<?php echo $token; ?>&api=api/order/bulkhistory&order_id='+selected_order_ids+'&added_by=<?php echo $this->user->getId(); ?>&added_by_role=<?php echo $this->user->getGroupName(); ?>',
    type: 'post',
    dataType: 'json',
    data: 'order_status_id=1&notify=0&order_processing_group_id='+order_processing_group_id+'&order_processor_id='+order_processor_id,
    success: function(json) {	 
    console.log(json);
    $('#orderprocessingModal-success-messages').html('Order status updated successfully!');
    $('.alert').html('Order status updated successfully!');
    $(".alert").attr('class', 'alert alert-success');
    $(".alert").show();
    setTimeout(function(){ window.location.reload(false); }, 1500);
    },			
    error: function(xhr, ajaxOptions, thrownError) {		
    $('#orderprocessingModal-messages').html("Please try again");
    return false;
    }
    });
    }
$('#neworderprocessingModal-form')[0].reset();               
}

$('#dispatchplanning').on('click', function (e) {
e.preventDefault();
var url = $(this).attr("data-url");
console.log(url);
window.open(url, '_blank');
});

$('#dispatchplanning_new').on('click', function (e) {
e.preventDefault();
var url = $(this).attr("data-url");
console.log(url);
window.open(url, '_blank');
});
</script></div>
<?php echo $footer; ?>

<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}
</style>