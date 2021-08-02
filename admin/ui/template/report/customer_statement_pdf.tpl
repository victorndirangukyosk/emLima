 
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Customer Statement</h1>
      
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>List</h3>
		  <div class="pull-right">
      
                 </div>		
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row"> 
              
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                
                <td class="text-left">Customer Name</td>
                <td class="text-left">Company Name</td>
                <!--<td class="text-left"><?php echo $column_email; ?></td>
                <td class="text-left"><?php echo $column_customer_group; ?></td>
                <td class="text-left"><?php echo $column_status; ?></td>-->
                <td class="text-right">Order Id</td> 
                <td class="text-right">Order Date</td> 
                <td class="text-right">Delivery Date</td> 
                <!--<td class="text-right"><?php echo $column_products; ?></td> 
                <td class="text-right"><?php echo $column_products; ?></td>-->
                <!--<td class="text-right"><?php echo $column_total; ?></td>-->
                <td class="text-right">P.O. Number</td>
                <!--<td class="text-right"><?php echo $column_total; ?></td>-->
                <td class="text-right">Order value</td>
                <td class="text-right">Amount Paid</td>
                <td class="text-right">Pending Amount</td>
                <td class="text-right">Payment Status</td>
                <td class="text-center"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($customers) { ?>
              <?php foreach ($customers as $customer) { ?>
              <tr>
                <td class="text-left"><?php echo $customer['customer']; ?></td>
                <td class="text-left"><?php echo $customer['company']; ?></td>
               <!-- <td class="text-left"><?php echo $customer['email']; ?></td>
                <td class="text-left"><?php echo $customer['customer_group']; ?></td>
                <td class="text-left"><?php echo $customer['status']; ?></td>-->
                <td class="text-right"><?php echo $customer['order_id']; ?></td> 
                <td class="text-right"><?php echo $customer['date_added']; ?></td> 
                <td class="text-right"><?php echo $customer['delivery_date']; ?></td> 
                <!--<td class="text-right"><?php echo $customer['products']; ?></td> 
                <td class="text-right"><?php echo $customer['editedproducts']; ?></td>-->

                <!--<td class="text-right"><?php echo $customer['total']; ?></td>-->
                <td class="text-right"><?php echo $customer['po_number']; ?></td>
                <td class="text-right"><?php echo $customer['subtotal']; ?></td>
                <td class="text-right"><?php echo $customer['amountpaid']; ?></td>
                <td class="text-right"><?php echo $customer['pendingamount']; ?></td>
                <td class="text-right"><?php echo $customer['paid']; ?></td>
                <td class="text-center"><a class="download" id="download-order-products"  data-toggle="tooltip" order_date="<?php echo $customer['date_added']; ?>" company="<?php echo $customer['company']; ?>" data="<?php echo $customer['customer']; ?>" value=<?php echo $customer['order_id']; ?>  title="Download Statements" class="btn btn-info"><i  style="cursor: pointer;height:20px;width:20px" class="fa fa-file-excel-o"></i></a></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?path=report/customer_order/statement&token=<?php echo $token; ?>';

  
            var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }


    var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
  if(filter_customer==0 && filter_company==0)
  {
    alert("Please select either customer or company ");
    return;
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

 
function excel() {
       url = 'index.php?path=report/customer_order/statementexcel&token=<?php echo $token; ?>';
      
        
     var filter_customer = $('input[name=\'filter_customer\']').val();

            if (filter_customer) {
                url += '&filter_customer=' + encodeURIComponent(filter_customer);
            }


    var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
  if(filter_customer==0 && filter_company==0)
  {
    alert("Please select either customer or company ");
    return;
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


     $(document).delegate('.download', 'click', function(e) {
  
            e.preventDefault();
            $orderid = $(this).attr('value');
            $customer = $(this).attr('data');
            $company = $(this).attr('company');
            $orderdate = $(this).attr('order_date');
           
 
            if ($orderid > 0) {                
                const url = 'index.php?path=sale/order/consolidatedOrderProducts&token=<?php echo $token; ?>&order_id=' + encodeURIComponent($orderid)+'&customer='+$customer+'&date='+$orderdate+'&company='+$company;
                location = url;
            }
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
    
        //--></script></div>



        
<?php echo $footer; ?>
 <style>

 .download
 {
   font-size: 1.5em;
 }
 body {
    position: relative;
}
 </style>