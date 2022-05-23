<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div class="page-header">
      <div class="container-fluid">
            <div class="pull-right">
                <button type="button" onclick="downloadmissingproducts();" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Missing Products Excel"><i class="fa fa-download"></i></button>
            </div>
            <h1>Missing Products Lost Revenue</h1>
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
                <h3 class="panel-title"><i class="fa fa-list"></i> Missing Products Summary</h3>
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
                                
                                  <label class="control-label" for="input-name">Product Name</label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Product Name" id="input-name" class="form-control" />

                            </div>
                            
                         

                            
                        </div>
                        <div class="col-sm-4">
   
                              
                            <div class="form-group">
                                <label class="control-label" for="input-date-added">Date Added From</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="Date Added From" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>

                        </div>



                        <div class="col-sm-4">

                          
                            <div class="form-group">
                                <label class="control-label" for="input-date-added-to">Date Added To</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added_to" value="<?php echo $filter_date_added_to; ?>" placeholder="Date Added To" data-date-format="YYYY-MM-DD" id="input-date-added-to" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

                        </div>

                        
                    </div>

                </div>   
                                      
                           

                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    
                                    <td class="text-right"><?php if ($sort == 'mp.product_store_id') { ?>
                                        <a href="<?php echo $sort_product_store_id; ?>" class="<?php echo strtolower($order); ?>">Product Store ID</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_product_store_id; ?>">Product Store ID</a>
                                        <?php } ?></td>  

                                         <td class="text-left"><?php if ($sort == 'mp.name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Product Name</a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>">Product Name</a>
                                        <?php } ?></td> 

                            
                                    <td class="text-left">Unit</td>
                                    <td class="text-right">Missing Quantity</td>
                                  <!--<td class="text-right">Price</td>-->
                                    <td class="text-right">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($all_orders) { ?>
                              

                                 
                                 
                                    
                        <?php foreach ($all_orders as $order) { ?>
                                <tr>
                                    
                                    <td class="text-right"><?php echo $order['product_store_id']; ?></td>

                                                                            
                                    <td class="text-left"><?php echo $order['name']; ?></td>
                                    <td class="text-left"><?php echo $order['unit']; ?></td>
                                    <td class="text-right"><?php echo $order['quantity_required']; ?></td>
                                    <td class="text-right"><?php echo $order['total']; ?></td>
                                        
                                </tr>
                                <?php } ?>
                                
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                      <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
        
                </form>
              
               

               </div>
               

               
              </div>
            </div>
        </div>
    </div>
<script type="text/javascript">  
$('#button-filter').on('click', function () {
            url = 'index.php?path=report/missing_products_revenue&token=<?php echo $token; ?>';

              
    
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
             var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }
 
            var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();

            if (filter_date_added_to) {
                url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
            }
            

            location = url;
        });

function downloadmissingproducts() {

            url = 'index.php?path=report/missing_products_revenue/downloadmissingproducts&token=<?php echo $token; ?>';

             
  
 var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            
            
 
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            
           
 
            
            var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();

            if (filter_date_added_to) {
                url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
            }
            

            location = url;
}    
</script>
    <script type="text/javascript">       
             

             $('input[name=\'filter_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_name\']').val(item['label']);
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
        
        </script> 
    <script type="text/javascript"><!--
  $('input[name^=\'selected\']').on('change', function () {

          var selected = $('input[name^=\'selected\']:checked'); 

        });

        $('input[name^=\'selected\']:first').trigger('change');
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
     
 
</script> 
<?php echo $footer; ?>

<style>
 

tr.header
{
    cursor:pointer;
}
</style>