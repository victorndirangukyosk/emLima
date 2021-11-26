<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
               
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
      <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="Download Excel"><i class="fa fa-download"></i></button>
                   
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
      <button type="button" onclick="excelSuccessfulTransactions();" data-toggle="tooltip" title="" class="btn btn-warning btn-sm" data-original-title="Download Success Transaction"><i class="fa fa-download"></i></button>
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
                          
                            

                              <div class="form-group">
                                <label class="control-label" for="input-date-added">From date added</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="From date added" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

                           
                            
                        </div>


                          <div class="col-sm-4">
                             
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                            </div>
                            <div class="form-group">    
                                <label class="control-label" for="input-date-added-end">To date added</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added_end" value="<?php echo $filter_date_added_end; ?>" placeholder="To date added" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                           
                            
                        </div>


                         <div class="col-sm-4">
                             
                            <div class="form-group">
                                <label class="control-label" for="input-company"><?php echo $entry_company; ?></label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company" class="form-control" />
                            </div>
                            
                           
                            
                        </div>

                        <div class="col-sm-4">
                            
                            <!--<div class="form-group">
                                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>-->
                            <br>
                            
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                       
                    </div>
                </div>

                <ul class="nav nav-tabs">
                <li class="active" style="width:25%;"><a data-toggle="tab" href="#pending">Pending Payments</a></li>
                <li style="width:25%;"><a data-toggle="tab" href="#successfull">Successfull Payments</a></li>
                 </ul>

                <div class="tab-content">




                    <div id="pending" class="tab-pane fade in active">
                        
                         <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">

 
                      <div class="btn-group" >                            
                         <div class="row">
                             <div class="col-sm-6">
                                        <input disabled type="text" name="grand_total" value="" placeholder="No Order Selected" id="input-grand-total" class="form-control" />
                             </div>  
                             <div class="col-sm-4">
                                    <button type="button" id="button-bulkpayment" class="btn btn-primary" onclick="showConfirmPopup(-1,0)"  data-toggle="modal" data-dismiss="modal" data-target="#paidModal" title="Payment Confirmation">  Receive Bulk Payment</button>
                             </div>    
                         </div>                             
                            
                      </div>
                      <br>
                      <br>


                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>

                                  <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"  name="selected[]"/>
                                    </td>
                                    
                                    <td class="text-right">
                                        <?php echo $column_order_id; ?></td>
                                    <td class="text-left">
                                        <?php if ($sort == 'customer') { ?>
                                        <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                                        <?php } ?>
                                    </td>
                                    
                                    <!--<td class="text-left">
                                        <?php echo 'No of Product'; ?>
                                    </td>-->
                                    <td class="text-right"> 
                                       <?php echo $column_total; ?> 
                                        </td>

                                         <td class="text-right"> 
                                       Paid Amount 
                                        </td>

                                         <td class="text-right"> 
                                       Pending Amount 
                                        </td>
                                    <!--<td class="text-left">
                                        <?php if ($sort == 'o.date_added') { ?>
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                        <?php } ?>
                                    </td>-->

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
                                        <input type="hidden" name="order_value[]" value="<?php echo $order['total_value']; ?>" />
                                        <input type="hidden" name="partially_paid_value[]" value="<?php echo $order['amount_partialy_paid_value']; ?>" />
                                    </td>

                                    
                                    <td class="text-right">
                                        <?php $or = explode(',',$order['order_id']) ?>
                                        <?php foreach ($or as $o): ?>
                                            <a href="<?php echo $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $o, 'SSL'); ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><?php echo $o; ?></a> 
                                        <?php endforeach ?>
                                    </td>
                                    <td class="text-left"><?php echo $order['customer']; ?> <br/>
                                            <?php echo $order['company']  ; ?></td>
                                    <!--<td class="text-left"><?php echo $order['no_of_products']; ?></td>-->
                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                    <td class="text-right"><?php echo $order['amount_partialy_paid']; ?></td>
                                    <td class="text-right"><?php echo $order['pending_amount']; ?></td>
                                   <!-- <td class="text-left"><?php echo $order['date_added']; ?></td>-->
                                    <td><a class="btn btn-default" onclick="showConfirmPopup(<?= $order['order_id'] ?>,<?= $order['total_value'] ?>)"  data-toggle="modal"   data-target="#paidModal" title="Payment Confirmation" >Receive Payment</a></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                 <td  colspan="3" class="text-right">
                                     <b>Grand Total</b>
                                    </td>
                                    
                                    <td class="text-right"><?php echo $order['grand_total']; ?></td>
                                    
                                    
                                </tr>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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


                    <div id="successfull" class="tab-pane fade">
                      



                          <form method="post" enctype="multipart/form-data" target="_blank" id="form-order-success">
                    <div class="table-responsive">

 
                      <div class="btn-group" >                            
                         <div class="row">
                             <div class="col-sm-6">
                                        <input  disabled type="hidden" name="grand_total_reverse" value="" placeholder="No Order Selected" id="input-grand-total-reverse" class="form-control" />
                             </div>  
                            <!-- <div class="col-sm-4">
                                    <button type="button" id="button-reversepayment" class="btn btn-primary" onclick="showConfirmPopup(-1,0)"  data-toggle="modal" data-dismiss="modal" data-target="#paidModal" title="Payment Confirmation">  Receive Bulk Payment</button>
                             </div>   --> 
                         </div>                             
                            
                      </div>
                      <br>
                      <br>


                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>

                                  <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'select_success\']').prop('checked', this.checked);"  name="select_success[]"/>
                                    </td>
                                    
                                    <td class="text-right">
                                        <?php echo $column_order_id; ?></td>
                                    <td class="text-left">
                                        <?php if ($sort == 'customer') { ?>
                                        <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                                        <?php } ?>
                                    </td>
                                    
                                    
                                    <td class="text-right"> 
                                       <?php echo $column_total; ?> 
                                        </td>
                                         <td class="text-left"> 
                                       Paid  
                                        </td>

                                        <!-- <td class="text-right"> 
                                       Paid Amount 
                                        </td>-->

                                         <td class="text-right"> 
                                       Pending Amount 
                                        </td>

                                        <td class="text-left"> 
                                       Transaction ID
                                        </td>
                                   <!-- <td class="text-left">
                                        <?php if ($sort == 'o.date_added') { ?>
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                        <?php } ?>
                                    </td>-->

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders_success) { ?>
                                <?php foreach ($orders_success as $order) { ?>
                                <tr>

                                 <td class="text-center"><?php if (in_array($order['order_id'], $select_success)) { ?>
                                        <input type="checkbox" name="select_success[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="select_success[]" value="<?php echo $order['order_id']; ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="order_value_success[]" value="<?php echo $order['total_value']; ?>" />
                                        <input type="hidden" name="partially_paid_value_success[]" value="<?php echo $order['amount_partialy_paid_value']; ?>" />
                                    </td>

                                    
                                    <td class="text-right">
                                        <?php $or = explode(',',$order['order_id']) ?>
                                        <?php foreach ($or as $o): ?>
                                            <a href="<?php echo $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $o, 'SSL'); ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><?php echo $o; ?></a> 
                                        <?php endforeach ?>
                                    </td>
                                    <td class="text-left"><?php echo $order['customer']; ?> <br/>
                                            <?php echo $order['company']  ; ?></td>
                                   
                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                    <td class="text-left"><?php echo $order['paid']; ?></td>
                                   <!-- <td class="text-right"><?php echo $order['amount_partialy_paid']; ?></td>-->
                                    <td class="text-right"><?php echo $order['pending_amount']; ?></td>
                                    <td class="text-left"><?php echo $order['transaction_id']; ?></td>
                                      <!--<td class="text-left"><?php echo $order['date_added']; ?></td> -->
                                    <td>
 
                                    <button class="btn btn-default" type="button" onclick="reverse_payment(<?= $order['order_id'].",'".$order['transaction_id'] ."','".$order['amount_partialy_paid'] ."'"?>);" >Reverse Payment</button>  
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                 <td  colspan="3" class="text-right">
                                     <b>Grand Total</b>
                                    </td>
                                    
                                    <td class="text-right"><?php echo $order['grand_total']; ?></td>
                                    
                                    
                                </tr>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>  
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php if ($orders_success) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination_success; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results_success; ?></div>
                </div>
                <?php } ?>


                    </div>

                </div>

               
            </div>
        </div>
    </div>
    <script type="text/javascript"> 

        $('input[name^=\'selected\']').on('change', function () 
        {

            $('#button-bulkpayment').prop('disabled', true);
            var selected = $('input[name^=\'selected\']:checked');            
                if (selected.length) {
                    $('#button-bulkpayment').prop('disabled', false);                
                }
                $grand_total_array=0;
                for (i = 0; i < selected.length; i++) {                
                $total_array= ($(selected[i]).parent().find('input[name^=\'order_value\']').val()) ;
                console.log($total_array);
                
                $partial_array= ($(selected[i]).parent().find('input[name^=\'partially_paid_value\']').val()) ;
                        
                        if($partial_array!='' && $partial_array!=null && $total_array!=null)
                        {                      
                            $total_array=$total_array-$partial_array;
                        }
                        if($total_array!=null)
                        {
                        $grand_total_array += parseFloat($total_array);
                        }
                    
                }
                
                if($grand_total_array>0)
                $('input[name=\'grand_total\']').val(parseFloat($grand_total_array).toFixed(2));
                else
                $('input[name=\'grand_total\']').val('');           

        });

        $('input[name^=\'selected\']:first').trigger('change'); 


         $('input[name^=\'select\']').on('change', function () 
        {

            $('#button-bulkreverse').prop('disabled', true);
            var select = $('input[name^=\'select\']:checked');            
                if (select.length) {
                    $('#button-bulkreverse').prop('disabled', false);                
                }
                $grand_total_array_reverse=0;
                for (i = 0; i < select.length; i++) {                
                $total_array_reverse= ($(select[i]).parent().find('input[name^=\'order_value_success\']').val()) ;
                console.log($total_array_reverse);
                
                $partial_array_reverse= ($(select[i]).parent().find('input[name^=\'partially_paid_value_success\']').val()) ;
                        
                        if($partial_array_reverse!='' && $partial_array_reverse!=null && $total_array_reverse!=null)
                        {                      
                            $total_array_reverse=$total_array_reverse-$partial_array_reverse;
                        }
                        if($total_array_reverse!=null)
                        {
                        $grand_total_array_reverse += parseFloat($total_array_reverse);
                        }
                    
                }
                
                if($grand_total_array_reverse>0)
                $('input[name=\'grand_total_reverse\']').val(parseFloat($grand_total_array_reverse).toFixed(2));
                else
                $('input[name=\'grand_total_reverse\']').val('');           

        });

        $('input[name^=\'select\']:first').trigger('change'); 


     $('#button-filter').on('click', function () {
            url = 'index.php?path=sale/order_receivables&token=<?php echo $token; ?>';

            
            var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }


        var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }


            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added != '*' && filter_date_added != '') {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end != '*' && filter_date_added_end != '') {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }

        //filter commented, becoz, if multiple customers, then unable to add wallet to  particular customer
             //add wallet to parent 
            //&& filter_company==0 && filter_date_added== '' && filter_date_added_end == ''
             if(filter_customer==0 && filter_order_id==0  && filter_company==0)
            {
                //or company or date filters
                alert("Please select either customer or order_id ");
                return;
            }
            

            

            location = url;
        });
        //--></script> 
   
   
    <script type="text/javascript"><!--
        
        
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
                //$('input[name=\'filter_customer_id\']').val(item['value']);
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
   
    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false,
            widgetParent: 'body'
        });
         
        
        
      

  function excelSuccessfulTransactions() {
   
      	url = 'index.php?path=sale/order_receivables/orderreceivedexcel&token=<?php echo $token; ?>';
        
     	  var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }

        if(filter_customer==0 && filter_order_id==0 && filter_company==0)
            {
                alert("Please select either customer or order_id or company");
                return;
            }

            /*var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total) {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }*/

    
    location = url;
    
}  
        
function excel() {
   
      	url = 'index.php?path=sale/order_receivables/orderreceivablesexcel&token=<?php echo $token; ?>';
        
     	  var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }

        if(filter_customer==0 && filter_order_id==0 && filter_company==0)
            {
                alert("Please select either customer or order_id or company");
                return;
            }

            /*var filter_total = $('input[name=\'filter_total\']').val();

            if (filter_total) {
                url += '&filter_total=' + encodeURIComponent(filter_total);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }*/

    
    location = url;
    
}






function showConfirmPopup($order_id,$order_value) {
               
            $('input[name="paid_order_id"]').val($order_id) ;    
            if($order_id>0)
            {
             var text ="<span class='col-sm-12 control-label orderlabel super' style='background: #FFE4CB;text-align: center;padding-top: 0px'>Order Id:"+$order_id+" </span><br><br>";
            $("#modal_bodyvalue").html(text);  
             $("#paid_amount").val($order_value); 
             $("#paid_amount").prop('disabled',true); 

            }
            else{
              
                 $("#modal_bodyvalue").html(''); 
                  $("#paid_amount").val(''); 
             $("#paid_amount").prop('disabled',false); 
            
            }
            }




</script>
</div>
<?php echo $footer; ?>





<div class="phoneModal-popup">
        <div class="modal fade" id="paidModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:385px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>  Payment Confirmation     </h2>
                                          </br> 
                                    </div>
                                    <div id="paidModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="paidModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="paidModal-form" action="" method="post" enctype="multipart/form-data">
 
                                                 <div class="form-group">
                                              <div class="form-group" id="modal_bodyvalue"></div>
                                             </div>
                                              
 
 
                                                <div class="form-group">

                                                    <label > Transaction ID </label>
                                                        <div class="col-md-12">
                                                        <input id="transaction_id" maxlength="30" required style="max-width:100% ;" name="transaction_id" type="text" placeholder="Transaction ID" class="form-control" required>
                                                        <input hidden id="paid_order_id" maxlength="30" required style="max-width:100% ;" name="paid_order_id" type="text">
                                                    
                                                                <br/> </div> 

                                                </div> 
                                                  
 
                                                 <div class="form-group">
                                                    <label    > Amount Received </label>

                                                    <div class="col-md-12">
                                                        <input id="paid_amount" value="" maxlength="30" required style="max-width:100% ;" name="paid_amount" type="number" placeholder="Amount Received" class="form-control" required>
                                                    <br/> </div> 
                                                    
                                                </div>
                                                


                                                 <div class="form-group">
                                                    <div class="col-md-12">
                                                       </br>
                                                     
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Close</button>


                                                        <button id="paid-button" name="paid-button" onclick="confirmPayment()" type="button" class="btn btn-lg btn-success"  style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Confirm</button>
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






<script>

////$("#paid_amount").change(function(){
   //alert("The text has been changed.");
//});

    function confirmPayment() 
    { 
    
        $('#paidModal-message').html('');
        $('#paidModal-success-message').html('');
        var transactionid = $('input[name="transaction_id"]').val();
        var amountreceived = 0;
        amountreceived =($('input[name="paid_amount"]').val());
        var orde_id =   $('input[name="paid_order_id"]').val() ;
        var grand_total =   ($('input[name="grand_total"]').val() );

        console.log($('#paidModal-form').serialize());
        console.log(amountreceived);
        console.log(grand_total);
                
    
        if (transactionid.length  <= 1 || amountreceived.length<=1) 
        {
                    
            $('#paidModal-message').html("Please enter data");
            return false;
        } 
        if(orde_id == -1)
        {
            amountreceived=parseFloat(amountreceived);
            if(amountreceived>grand_total)
            {
                //alert("Amount received is more.please select more orders");
                //return;

                 var result = confirm("Amount received is more.Do you want the amount to be added to wallet ?");
                if (result == true) 
                {
                    //doc = "OK was pressed.";
                    //go to below Ajax call and add balance to customer wallet
                } 
                else { return; } 
            }
            if(amountreceived<grand_total)
            {
                var result = confirm("Amount received and Grand total are different.Do you want to proceed with automatic updation of orders by system based on total ?");
                if (result == true) 
                {
                    //doc = "OK was pressed.";
                    //go to below Ajax call
                } 
                else { return; }  
            }


            var selected_order_id = $.map($('input[name="selected[]"]:checked'), function(n, i)
            {
                return n.value;
            }).join(','); 

            //alert(selected_order_id);
            if(selected_order_id=='' || selected_order_id==null)
            {
                alert("Please Select the order");
                return;
            }


            $.ajax({
            url: 'index.php?path=sale/order_receivables/confirmBulkPaymentReceived&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: 'selected=' + selected_order_id + '&transaction_id='+ transactionid+ '&grand_total='+ grand_total+ '&amount_received='+ amountreceived,
            async: true,
            success: function(json) {
                console.log(json); 
                if (json['status']) 
                {
                    $('#paidModal-success-message').html(' Saved Successfully');
                    setTimeout(function() {
                            location=location;
                        }, 1000);
                            
                }
                else    {
                            $('#paidModal-success-message').html('Please try again');
                            
                        }
                },
                error: function(xhr, ajaxOptions, thrownError) {    

                                    // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                    $('#paidModal-message').html("Please try again");
                                        return false;
                                    }
                    });             

                    
        }
        else if(orde_id>0)
        {  
            $.ajax({
            url: 'index.php?path=sale/order_receivables/confirmPaymentReceived&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data:$('#paidModal-form').serialize(),
            async: true,
            success: function(json) {
                console.log(json); 
                    if (json['status']) {
                        $('#paidModal-success-message').html(' Saved Successfully');
                            setTimeout(function() {
                            location=location;
                            }, 1000);
                            
                        }
                        else {
                                $('#paidModal-success-message').html('Please try again');
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {    

                                    // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                                    $('#paidModal-message').html("Please try again");
                                        return false;
                                    }
                    });
        }     
    }


	function reverse_payment($order_id,$transaction_id,$amount_partialy_paid){

		if(confirm('Are You Sure,to reverse the payment status?')){
			$.ajax({
            url: 'index.php?path=sale/order_receivables/reversePaymentReceived&token=<?php echo $token; ?>',
            type: 'post',
            data: 'paid_order_id=' + $order_id +'&transaction_id='+$transaction_id+'&amount_partialy_paid='+$amount_partialy_paid,
            beforeSend: function() {
            },
            complete: function() {
                
            },
		    success: function(json) {
             console.log(json); 
		        if(json['status']){
                    //$(' .payment_status .text').html('Paid');
                    //$('.payment_status button').html('Undo Vendor Pay').attr('onclick',"payment_status("+$store_id+",0);");
                    alert("Payment status reversed");
           
                    setTimeout(function() {
                    location=location;
                    }, 1000);
        
                    }else{
                        //$('.payment_status .text').html('Unpaid');
                        //$('.payment_status button').html('Pay to Vendor').attr('onclick',"payment_status("+$store_id+",1);");
                    }
		    },
            error: function(xhr, ajaxOptions, thrownError) {    
                 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);                       
                 }                  
			});
        }
		
	}


</script>

