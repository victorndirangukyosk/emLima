<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
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

                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                               <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
                  <!--<td class="text-left"><?php echo $column_Company; ?></td>-->
                  <td class="text-left"><?php echo $column_Customer; ?></td>
                 
                  <td class="text-left"><?php echo $column_rating; ?></td>
                  
                  <td class="text-left"><?php echo $column_feedback_type; ?></td>
                  <td class="text-left"><?php echo $column_comments; ?></td>
                  <td class="text-left">Order_Id</td>
                  <td class="text-left">Raised On</td>

                  <td class="text-left">Status</td>
                  <td class="text-left">Accepted By</td>
                  <td class="text-left">Closed Date</td>
                  <td class="text-left">Closed Comments</td>
                   <?php if ($this->user->isCustomerExperience()){ ?>
                   <td class="text-left">Action</td>  <?php } ?>
                    

                 
                </tr>
                            </thead>
                            <tbody>
                                <?php if ($customer_feedbacks) { ?>
                                <?php foreach ($customer_feedbacks as $customer_feedback) { ?>
                                <tr>
                                    <td class="text-left">
                     
                  <?php echo $customer_feedback['customer_name']; ?> 
                       </br>
                  <?php echo $customer_feedback['company_name']; ?>

                                            </td>
                                     <td class="text-left"><?php echo $customer_feedback['rating']; ?></td>

                  <td class="text-left" style="width:100px"><?php  echo $customer_feedback['feedback_type']; ?>  </td>
                  <td class="text-left" style="width:200px"><?php  echo $customer_feedback['comments']; ?> </td>
                  <td class="text-left"><?php echo $customer_feedback['order_id']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['created_date']; ?></td>
                  
                  <td class="text-left"><?php echo $customer_feedback['status']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['Accepted_user']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['closed_date']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['closed_comments']; ?></td>
                  <?php if (!$this->user->isCustomerExperience() ){ ?>
                  <?php if ($customer_feedback['rating']<=3  && $customer_feedback['status']=='Open') { ?>
                                    <td class="text-right">
                                    <div style="width: 100%; display:flex; justify-content: space-between; flex-flow: row wrap; gap: 4px;">
                                               <a href="#" id="new_print_invoice" data-feedback-id="<?= $customer_feedback['feedback_id'] ?>" target="_blank" data-toggle="tooltip" title="Accept">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                                </a>  
                                              
                                       </div>
                                    </td>
                    <?php } else if($customer_feedback['rating']<=3  && $customer_feedback['status']=='Attending') { ?>
                  <td class="text-left"><button class="" style="background:green;width:65px"  data-toggle="modal" data-dismiss="modal" data-target="#feedbackcloseModal" title="PO Details">Close</button></td>


                 <?php } else { ?>
                 <td></td>
                  <?php }?>
                 
                 
                   <?php }  ?>
                                        
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
                <?php if ($customer_feedbacks) { ?>
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
            $('#button-invoice-pdf').prop('disabled', true);

            var selected = $('input[name^=\'selected\']:checked');

            if (selected.length) {
                $('#button-invoice').prop('disabled', false);
                $('#button-invoice-pdf').prop('disabled', false);
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
                    <div class="modal-body"  style="height:480px;">
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
    
    <div class="modal fade" id="store_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> 
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Order Products List</h4>
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
                                    
                                      <button type="button" onclick="addtomissingproduct();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Add To Missing Products">Save To Missing Products</button>
                                   </div>
                            </div>
                        </div>
                    </div> <!-- /.modal -->
<script  type="text/javascript">

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
                    $('.alert').html('Order assigned to delivery partner!');
                    $(".alert").attr('class', 'alert alert-success');
                    $(".alert").show();
                    if(json.status == 200) {
                    setTimeout(function(){ window.location.reload(false); }, 1500);
                    }
		},			
		error: function(xhr, ajaxOptions, thrownError) {		
			 
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

function savedriverdetails() { 
 
    $('#driverModal-message').html('');
    $('#driverModal-success-message').html('');
   var order_id = $('input[name="order_id"]').val();
   var invoice = $('input[name="invoice_custom"]').val();
   var driver_id = $('select[name="order_drivers"]').val();
   //var driver_id = $('input[name="order_driver"]').attr("data_driver_id");
   var vehicle_number =  $('input[name="order_vehicle_number"]').val();
   var delivery_charge =  $('input[name="order_delivery_charge"]').val();
   var delivery_executive_id =  $('select[name="order_delivery_executives"]').val();
   //var delivery_executive_id =  $('input[name="order_delivery_executive"]').attr("data_delivery_executive_id");
    console.log(vehicle_number);
    console.log(delivery_charge);
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
                    data:{ order_id : order_id, vehicle_number : vehicle_number, driver_id : driver_id, delivery_executive_id:delivery_executive_id, delivery_charge : delivery_charge },
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
   var delivery_charge =  $('input[name="order_delivery_charge"]').val();
   var delivery_executive_id =  $('select[name="order_delivery_executives"]').val();
   //var delivery_executive_id =  $('input[name="order_delivery_executive"]').attr("data_delivery_executive_id");
    console.log(vehicle_number);
    console.log(delivery_charge);
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
                    data:{ order_id : order_id, vehicle_number : vehicle_number, driver_id : driver_id, delivery_executive_id:delivery_executive_id, delivery_charge : delivery_charge },
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




    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false
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

$('a[id^=\'new_print_invoice\']').on('click', function (e) {
e.preventDefault();
console.log($(this).attr("data-feedback-id"));
$.ajax({
                    url: 'index.php?path=sale/customer_feedback/acceptIssue&token=<?php echo $token; ?>&feedback_id='+$(this).attr("data-feedback-id"),
                    type: 'POST',
                    dataType: 'json',
                    data:{ feedback_id: $(this).attr("data-feedback-id") },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           //$('input[name="po_number"]').val(json['po_number']) ;
                          alert('Issue Accepted');
                           
                        }
                        else {
                            // $('input[name="po_number"]').val('') ;
                           
                            
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) { 

                       //  $('input[name="po_number"]').val('') ;
                                 
                                    return false;
                                }
                });
});


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



    </script></div>
<?php echo $footer; ?>

<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}
</style>
