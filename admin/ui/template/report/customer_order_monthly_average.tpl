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
            
           <div class="col-sm-4">

           <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>

              <div class="form-group">
                <label class="control-label" for="input-payment-terms">Payment Terms</label>
                <select name="filter_payment_terms" id="input-payment-terms" class="form-control">
                  
                  <?php if ('Credit' == $filter_payment_terms) { ?>
                  <option value="Credit" selected="selected">Credit</option>
                  <?php } else { ?>
                  <option value="Payment On Delivery">Payment On Delivery</option>
                  <?php } ?>
                  
                  <?php if ('Payment On Delivery' == $filter_payment_terms) { ?>
                  <option value="Payment On Delivery" selected="selected">Payment On Delivery</option>
                  <?php } else { ?>
                  <option value="Credit">Credit</option>
                  <?php } ?>

                   
                </select>
              </div>
            </div> 
 
           
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
           
          </div>
        </div>
        <div class="table-responsive">

 <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Customer ID</td>
                <td class="text-left"><?php echo $column_customer; ?></td>
                <td class="text-left">Company Name</td>
                <td class="text-left">Payment Terms</td>
                <td class="text-left">Monthly Average</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($customers) { ?>
              <?php foreach ($customers as $customer) { ?>
              <tr>
                <td class="text-left"><?php echo $customer['customer_id']; ?></td>
                <td class="text-left"><?php echo $customer['customer']; ?></td>
                <td class="text-left"><?php echo $customer['company']; ?></td>
                
                <td class="text-left"><?php echo $customer['payment_terms']; ?></td>
                <td class="text-left"><?php echo $customer['monthly_average']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?path=report/customer_order_monthly_average&token=<?php echo $token; ?>';

  
            //var filter_customer = $('input[name=\'filter_customer\']').val();

            //if (filter_customer) {
            //    url += '&filter_customer=' + encodeURIComponent(filter_customer);
            //}


    var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
  

	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
	

  if(filter_date_end=="" || filter_date_start=="")
  {
    alert("Please select Start and End Dates");

      return;
  }
  else{
        dt1 = new Date(filter_date_start);
    dt2 = new Date(filter_date_end);
    if(diff_months(dt1, dt2)>12)
    {
    alert("Please select Start and End months less than 12 or with in the year");
    return;
    }
 
  }
	if (filter_payment_terms != 0) {
		url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms);
	}	

  


	location = url;
});
//--></script> 
  <script type="text/javascript"><!--


function diff_months(dt2, dt1) 
 {

  var diff =(dt2.getTime() - dt1.getTime()) / 1000;
   diff /= (60 * 60 * 24 * 7 * 4);
  return Math.abs(Math.round(diff));
  
 }

   $companyName="";
       /* $('input[name=\'filter_customer\']').autocomplete({         

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
        });*/


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
                //$('input[name=\'filter_customer\']').val('');
                $companyName=item['label'];
            }
        });

 
function excel() {
       url = 'index.php?path=report/customer_order_monthly_average/order_monthly_average_excel&token=<?php echo $token; ?>';
      
        
     //var filter_customer = $('input[name=\'filter_customer\']').val();

           // if (filter_customer) {
             //   url += '&filter_customer=' + encodeURIComponent(filter_customer);
           // }


    var filter_company = $('input[name=\'filter_company\']').val();

            if (filter_company) {
                url += '&filter_company=' + encodeURIComponent(filter_company);
            }
  
   

	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
	
	if (filter_payment_terms != 0) {
		url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms);
	}	
  if(filter_date_end=="" || filter_date_start=="")
  {
    alert("Please select Start and End Dates");
    return;
  }



    location = url;
}

   
 </script>




    <script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false,
             widgetParent: 'body',
              format: "YYYY-MM-DD",
    viewMode: "months", 
    minViewMode: "months"
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

.ui-datepicker-calendar {
 
    display: none;
 
}​

 </style>