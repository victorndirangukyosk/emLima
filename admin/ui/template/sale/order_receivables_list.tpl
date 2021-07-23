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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                            </div>
                            
                           
                            
                        </div>
                        <div class="col-sm-6">
                            
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
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                       
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">

 
                      <div class="btn-group" >                            
                         <div class="row">
                             <div class="col-sm-6">
                                        <input disabled type="text" name="grand_total" value="" placeholder="No Order Selected" id="input-grand-total" class="form-control" />
                             </div>  
                             <div class="col-sm-4">
                                    <button type="button" id="button-bulkpayment" class="btn btn-primary" onclick="showConfirmPopup(-1)"  data-toggle="modal" data-dismiss="modal" data-target="#paidModal" title="Payment Confirmation">  Receive Bulk Payment</button>
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
                                    </td>

                                    
                                    <td class="text-right">
                                        <?php $or = explode(',',$order['order_id']) ?>
                                        <?php foreach ($or as $o): ?>
                                            <a href="<?php echo $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $o, 'SSL'); ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><?php echo $o; ?></a> 
                                        <?php endforeach ?>
                                    </td>
                                    <td class="text-left"><?php echo $order['customer']; ?></td>
                                    <!--<td class="text-left"><?php echo $order['no_of_products']; ?></td>-->
                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                   <!-- <td class="text-left"><?php echo $order['date_added']; ?></td>-->
                                    <td><a class="btn btn-default" onclick="showConfirmPopup(<?= $order['order_id'] ?>)"  data-toggle="modal"   data-target="#paidModal" title="Payment Confirmation" >Receive Payment</a></td>
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
        </div>
    </div>
    <script type="text/javascript"> 

     $('input[name^=\'selected\']').on('change', function () {
            var selected = $('input[name^=\'selected\']:checked');
            $grand_total_array=0;
            for (i = 0; i < selected.length; i++) {
               $total_array= ($(selected[i]).parent().find('input[name^=\'order_value\']').val()) ;
                    
                    $grand_total_array += parseInt($total_array);
                
            }
            //alert($grand_total_array);
            if($grand_total_array>0)
             $('input[name=\'grand_total\']').val($grand_total_array);

           

        });

        $('input[name^=\'selected\']:first').trigger('change');

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

            if(filter_customer==0 && filter_order_id==0)
            {
                alert("Please select either customer or order_id ");
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
        });
        //--></script> 
    <script type="text/javascript"><!--
        
        
        
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
   
    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false
        });
         
        
        
        
        
function excel() {
   
      	url = 'index.php?path=sale/order_receivables/orderreceivablesexcel&token=<?php echo $token; ?>';
        
     	  var filter_order_id = $('input[name=\'filter_order_id\']').val();

            if (filter_order_id) {
                url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
            }

            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }

 if(filter_customer==0 && filter_order_id==0)
            {
                alert("Please select either customer or order_id ");
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






function showConfirmPopup($order_id) {
               
            $('input[name="paid_order_id"]').val($order_id) ;    
            if($order_id>0)
            {
             var text ="<span class='col-sm-12 control-label orderlabel super' style='background: #FFE4CB;text-align: center;padding-top: 0px'>Order Id:"+$order_id+" </span><br><br>";
            $("#modal_bodyvalue").html(text);   
            }
            else{
              
                 $("#modal_bodyvalue").html(''); 
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
                                                        <input id="paid_amount" maxlength="30" required style="max-width:100% ;" name="paid_amount" type="text" placeholder="Amount Received" class="form-control" required>
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

