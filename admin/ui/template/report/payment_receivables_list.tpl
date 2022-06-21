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
                            
                           <div class="form-group">
                <label class="control-label" for="input-payment-terms">Payment Terms</label>
                <select name="filter_payment_terms" id="input-payment-terms" class="form-control">
                            <option value=""></option>
                            <option value="Payment On Delivery" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == 'Payment On Delivery') { ?> selected="selected" <?php } ?> >Payment On Delivery</option>
                            <option value="Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == 'Credit') { ?> selected="selected" <?php } ?> >Credit</option>
                          
                </select>
              </div>   
                            <br>
                            
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                       
                    </div>
                </div>

                

                <div class="tab-content">




                    
                        
                         <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">

 
                     
                      <br>
                      <br>


                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>

                                 
                                    
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

                                  
                                    
                                    <td class="text-right">
                                       <?php echo $order['order_id']; ?>
                                    </td>
                                    <td class="text-left"><?php echo $order['customer']; ?> <br/>
                                            <?php echo $order['company']  ; ?></td>
                                    <!--<td class="text-left"><?php echo $order['no_of_products']; ?></td>-->
                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                    <td class="text-right"><?php echo $order['amount_partialy_paid']; ?></td>
                                    <td class="text-right"><?php echo $order['pending_amount']; ?></td>
                                   <!-- <td class="text-left"><?php echo $order['date_added']; ?></td>-->
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
                <?php if ($orders) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?>

                    </div>

 

                </div>

               
            </div>
        
    </div>
    <script type="text/javascript"> 
 

     $('#button-filter').on('click', function () {
            url = 'index.php?path=report/payment_receivables&token=<?php echo $token; ?>';

            
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


                var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
                
                if (filter_payment_terms != '*' && filter_payment_terms != '') {
                    url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms); 
                } 

            if(filter_date_added=='' || filter_date_added_end=='')
            {
                alert("Please select filter dates ");
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
         
        
        
      
 
        
function excel() {
   
      	url = 'index.php?path=report/payment_receivables/paymentreceivablesexcel&token=<?php echo $token; ?>';
        
     	  var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }
            var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
            var filter_customer = $('input[name=\'filter_customer\']').val();


   var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added != '*' && filter_date_added != '') {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
            var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

            if (filter_date_added_end != '*' && filter_date_added_end != '') {
                url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
            }

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }

        var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
                
                if (filter_payment_terms != '*' && filter_payment_terms != '') {
                    url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms); 
                } 

            if(filter_date_added=='' || filter_date_added_end=='')
            {
                alert("Please select filter dates ");
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
                    <div class="modal-body"  style="height:485px;">
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

                                                    <label > Paid To </label>
                                                        <div class="col-md-12">
                                                        <select required name="paid_to" id="paid_to" style="max-width:100% ;" class="form-control">
                                                        
                                                        <option value="Bank Account Number" selected="selected">Bank Account Number</option>
                                                        <option value="Mpesa">Mpesa</option>
                                                        <option value="Pay bill No">Pay bill No</option>
                                                        
                                                    </select>      <br/>  <br/> </div> 

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

    
 

</script>


<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}
</style>