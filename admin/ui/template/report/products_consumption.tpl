<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Products Consumption</h1>
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
                <label class="control-label" for="input-product">Product</label>

                 <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Product" id="input-customer" class="form-control" />
              
              </div>

            
            </div>

            
 
 
             <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Order Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Order Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
             <!-- <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>-->
            </div>
           
          </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                
               <td class="text-left">Order Date</td>
               <td class="text-left">Order Id</td>
                <td class="text-left">Customer Name</td>                
                <td class="text-left">Customer Status</td> 
                <td class="text-left">Payment Terms</td> 

                <td class="text-left">Product Name</td> 
                <td class="text-left">Unit</td> 
                <td class="text-right">Quantity</td> 
                <td class="text-right">Order Status</td> 
                
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $prod) { ?>
              <tr>
                <td class="text-left"><?php echo $prod['order_date']; ?></td>
                <td class="text-left"><?php echo $prod['order_id']; ?></td>
                <td class="text-left"><?php echo $prod['customer']; ?></td>
                <td class="text-left"><?php echo $prod['customer_status']; ?></td>
                <td class="text-left"><?php echo $prod['payment_terms']; ?></td>

               <td class="text-left"><?php echo $prod['name']; ?></td>
                <td class="text-left"><?php echo $prod['unit']; ?></td>
                <td class="text-right"><?php echo $prod['quantity']; ?></td>
                <td class="text-right"><?php echo $prod['status']; ?></td>
                </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?path=report/products_consumption&token=<?php echo $token; ?>';

  
            var filter_customer = $('input[name=\'filter_customer\']').val();

           
            
    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
  
   

	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
  if(filter_date_start=="" && filter_name=="" )
  {
     alert("Please select order date or product");
     return;
  }



	//var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	//if (filter_date_end) {
		//url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	//}
  //else
  //{
    //alert("Please select end date");
     //return;
  //}
	
	 

  


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
   
    $('input[name=\'filter_name\']').autocomplete({

            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=report/customer_boughtproducts/product_autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request)+'&filter_company=' +$companyName,
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['product_store_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_name\']').val(item['label']);
                $('input[name=\'filter_name\']').attr('product_id',item['value']);
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
  
     var  url = 'index.php?path=report/products_consumption/productsconsumptionexcel&token=<?php echo $token; ?>';
      
        
     
    
    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
   
  

	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
   

   if (""== filter_date_start && ""== filter_name)
  {
     alert("Please select order date or product");
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