



<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
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
            <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
            
      </div>    
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">

           <div class="col-sm-3">
 <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>

                        <div class="form-group">
                     
                     
                      <label class="control-label" for="input-payment-terms">Payment Terms</label>
                <select name="filter_payment_terms" id="input-payment-terms" class="form-control">
                            <option value=""></option>
                            <option value="Payment On Delivery" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == 'Payment On Delivery') { ?> selected="selected" <?php } ?> >Payment On Delivery</option>
                            <option value="7 Days Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == '7 Days Credit') { ?> selected="selected" <?php } ?> >7 Days Credit</option>
                            <option value="15 Days Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == '15 Days Credit') { ?> selected="selected" <?php } ?> >15 Days Credit</option>
                            <option value="30 Days Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == '30 Days Credit') { ?> selected="selected" <?php } ?> >30 Days Credit</option>
                </select>
                                 </div>     
           </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name">Customer Name</label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="Customer Name" id="input-name" class="form-control" />
              </div>
               
            </div>
            <div class="col-sm-3">
              
              <div class="form-group">
                <label class="control-label" for="input-status">Status</label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected">Enabled</option>
                  <?php } else { ?>
                  <option value="1">Enabled</option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected">Disabled</option>
                  <?php } else { ?>
                  <option value="0">Disabled</option>
                  <?php } ?>
                </select>
              </div>
            </div>

             <div class="col-sm-3">

               

              <div class="form-group" style="margin-top:20px;">
               <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>  
</div> </div>

            

              
            </div>
              

              

            
              
              

             
              
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Customer Name</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>">Customer Name</a>
                    <?php } ?></td>                 
                 
                  
                  <td class="text-left"><?php if ($sort == 'c.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Status</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>">Status</a>
                    <?php } ?></td>-->
                 
                 
                  <td class="text-center">Customer Name</td>
                  <td class="text-center">Status</td>
                  <td class="text-center">Payment Terms</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($customers) { ?>
                <?php foreach ($customers as $customer) { ?>
                <tr>
                   
                  <td class="text-left"><?php echo $customer['name']; ?>
                  <td class="text-left"><?php echo $customer['status']; ?></td>
                  <td class="text-left"><?php echo $customer['payment_terms']; ?></td>
                  
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
  url = 'index.php?path=report/customer&token=<?php echo $token; ?>';

   var filter_company = $('input[name=\'filter_company\']').val();

   if (filter_company) {
     url += '&filter_company=' + encodeURIComponent(filter_company);
   }
   
       
  var filter_customer = $('input[name=\'filter_customer\']').val();
  
  if (filter_customer) {
    url += '&filter_customer=' + encodeURIComponent(filter_customer);
  }
   
  var filter_status = $('select[name=\'filter_status\']').val();

  
  if (filter_status != '*') {
    url += '&filter_status=' + (filter_status); 
  }
 console.log(filter_status);
  
  var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
  
    if (filter_payment_terms != '*'  && filter_payment_terms != '') {

    url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms); 
  } 
         
  
  location = url;
});
//--></script> 
  <script type="text/javascript"><!--

  $companyName="";
$('input[name=\'filter_customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=sale/customer/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request)+'&filter_company=' +$companyName,
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_customer\']').val(item['label']);
  } 
});

$('input[name=\'filter_parent_customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=sale/customer/autocompleteparentcustomer&token=<?php echo $token; ?>&filter_parent_customer=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_parent_customer\']').val(item['label']);
    $('input[name=\'filter_parent_customer\']').attr("data-parent-customer-id", item['value']);
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
$('.date').datetimepicker({
  pickTime: false,
     widgetParent: 'body'
});

function excel() {
            
    url = 'index.php?path=report/customer/customerexcel&token=<?php echo $token; ?>';
    



    
   var filter_company = $('input[name=\'filter_company\']').val();

   if (filter_company) {
     url += '&filter_company=' + encodeURIComponent(filter_company);
   }
      
  
  var filter_customer = $('input[name=\'filter_customer\']').val();
  
  if (filter_customer) {
    url += '&filter_customer=' + encodeURIComponent(filter_customer);
  }
   
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status); 
  } 
 
  var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
  
  if (filter_payment_terms != '*'  && filter_payment_terms != '') {
    url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms); 
  }
    
      
  
    location = url;
}
$('a.customer_verified').bind("click", function (e) {
e.preventDefault();
});
$('a.unlock_customer').bind("click", function (e) {
e.preventDefault();
});
//--></script></div>
<?php echo $footer; ?> 

<style>
body {
    position: relative;
}
</style>

