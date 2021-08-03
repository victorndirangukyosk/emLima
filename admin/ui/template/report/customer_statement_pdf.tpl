 
<link type="text/css" href="ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link href="ui/javascript/bootstrap/shop/shop.css" type="text/css" rel="stylesheet" />
<link href="ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="ui/javascript/summernote/summernote.css" rel="stylesheet">
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link href="ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
<link href="ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />
 
<script type="text/javascript" src="ui/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="ui/javascript/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="ui/javascript/bootstrap-select/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="ui/javascript/tinymce/jquery.tinymce.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.0/jquery.tinymce.min.js"></script> -->

<script src="ui/javascript/common.js" type="text/javascript"></script>
<script src="ui/javascript/jscolor-2.0.4/jscolor.js" type="text/javascript"></script>

  


<div id="content">
  <div class="page-header">
    <div class="container-fluid" style="align:center">
      <h1 >Customer Orders Statement</h1>
      
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        
		 	
      </div>
      <div class="panel-body">
        
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
              </tr>
            </thead>
            <tbody>
              <?php 
              
               
               if ($customers) { ?>
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
      
  

 