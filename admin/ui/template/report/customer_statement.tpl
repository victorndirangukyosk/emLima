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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
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
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <select name="filter_customer" id="input-customer" class="form-control">
                  <option value="0">Please Select</option>
                  <?php foreach ($customer_names as $cust) { ?>
                  <?php if ($cust['customer_id'] == $filter_customer) { ?>
                  <option value="<?php echo $cust['customer_id']; ?>" selected="selected"><?php echo $cust['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $cust['customer_id']; ?>"><?php echo $cust['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_order_status_id" id="input-status" class="form-control">
                  <option value="0"><?php echo $text_all_status; ?></option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>

             <div class="col-sm-4">
                  <input type="hidden" name="filter_date "      class="form-control" />
                              
 </div>
 
             <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
           
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left">Company Name</td>
                <!--<td class="text-left"><?php echo $column_email; ?></td>
                <td class="text-left"><?php echo $column_customer_group; ?></td>
                <td class="text-left"><?php echo $column_status; ?></td>-->
                <td class="text-right">Order Id</td> 
                <td class="text-right">Order Date</td> 
                <!--<td class="text-right"><?php echo $column_products; ?></td> 
                <td class="text-right"><?php echo $column_products; ?></td>-->
                <!--<td class="text-right"><?php echo $column_total; ?></td>-->
                <td class="text-right">P.O. Number</td>
                <td class="text-right"><?php echo $column_total; ?></td>
                <td class="text-center"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($customers) { ?>
              <?php foreach ($customers as $customer) { ?>
              <tr>
                <td class="text-left"><?php echo $customer['customer']; ?></td>
               <!-- <td class="text-left"><?php echo $customer['email']; ?></td>
                <td class="text-left"><?php echo $customer['customer_group']; ?></td>
                <td class="text-left"><?php echo $customer['status']; ?></td>-->
                <td class="text-right"><?php echo $customer['order_id']; ?></td> 
                <td class="text-right"><?php echo $customer['date_added']; ?></td> 
                <!--<td class="text-right"><?php echo $customer['products']; ?></td> 
                <td class="text-right"><?php echo $customer['editedproducts']; ?></td>-->

                <!--<td class="text-right"><?php echo $customer['total']; ?></td>-->
                <td class="text-right"><?php echo $customer['po_number']; ?></td>
                <td class="text-right"><?php echo $customer['subtotal']; ?></td>
                <td class="text-center"><a class="download" id="download-order-products"  data-toggle="tooltip" order_date="<?php echo $customer['date_added']; ?>" data="<?php echo $customer['customer']; ?>" value=<?php echo $customer['order_id']; ?>  title="Download Products" class="btn btn-info"><i class="fa fa-file-excel-o"></i></a></td>
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

  var filter_customer = $('select[name=\'filter_customer\']').val();
	
	if (filter_customer != 0) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}	
  else{
    alert("Please select customer");
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
 
function excel() {
       url = 'index.php?path=report/customer_order/statementexcel&token=<?php echo $token; ?>';
      
        
  var filter_customer = $('select[name=\'filter_customer\']').val();
	
	if (filter_customer != 0) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}	
  else{
    alert("Please select customer");
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
            $orderdate = $(this).attr('order_date');
           
 
            if ($orderid > 0) {                
                const url = 'index.php?path=sale/order/consolidatedOrderProducts&token=<?php echo $token; ?>&order_id=' + encodeURIComponent($orderid)+'&customer='+$customer+'&date='+$orderdate;
                location = url;
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
 