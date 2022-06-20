<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
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
            <div class="col-sm-6">
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
                <label class="control-label" for="input-order-id">Order ID</label>
                  <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="Order ID" id="filter_order_id" class="form-control" />
                
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_order_id; ?></td>
                <td class="text-right"><?php echo $column_vendor_product_id; ?></td>
                <td class="text-right"><?php echo $column_product_name; ?></td>
                <td class="text-right"><?php echo $column_uom; ?></td>
                <td class="text-right"><?php echo $column_customer_ordred_quantity; ?></td>
                <td class="text-right"><?php echo $column_updated_quantity; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($order_and_updated_products) { ?>
              <?php foreach ($order_and_updated_products as $order_and_updated_product) { ?>
              <tr>
                <td class="text-left"><?php echo $order_and_updated_product['order_id']; ?></td>
                <td class="text-right"><?php echo $order_and_updated_product['product_id']; ?></td>
                <td class="text-right"><?php echo $order_and_updated_product['name']; ?></td>
                <td class="text-right"><?php echo $order_and_updated_product['unit']; ?></td>
                <td class="text-right"><?php echo $order_and_updated_product['quantity']; ?></td>
                <td class="text-right"><?php echo $order_and_updated_product['updated_quantity']; ?></td>
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
	url = 'index.php?path=report/order_and_updated_product&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id != '' && filter_order_id != undefined) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}	
  if((filter_order_id == ''||filter_order_id == undefined) &&( filter_date_start =='' || filter_date_end==''))
  {
    alert('please select either order id or dates');
    return;
  }
  if(filter_date_start !='' && filter_date_end !='')
  {

                        const date1 = new Date(filter_date_start);
                        const date2 = new Date(filter_date_end);
                        const diffTime = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        console.log(diffTime + " milliseconds");
                        console.log(diffDays + " days");
                        if(diffDays<0)
                        {
                        alert("Please select proper start & end date filters");
                                        return;
                        }
                        if(diffDays>60)
                        {
                            alert("Duration between start & end date filters should be less than 60 days");
                                        return;
                        }

  }

	location = url;
});



function excel() {
      	url = 'index.php?path=report/order_and_updated_product/excel&token=<?php echo $token; ?>';
        
     	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id != '') {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}	
    
     if((filter_order_id == ''||filter_order_id == undefined) &&( filter_date_start =='' || filter_date_end==''))
  {
    alert('please select either order id or dates');
    return;
  }
  if(filter_date_start !='' && filter_date_end !='')
  {

                        const date1 = new Date(filter_date_start);
                        const date2 = new Date(filter_date_end);
                        const diffTime = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        console.log(diffTime + " milliseconds");
                        console.log(diffDays + " days");
                        if(diffDays<0)
                        {
                        alert("Please select proper start & end date filters");
                                        return;
                        }
                        if(diffDays>60)
                        {
                            alert("Duration between start & end date filters should be less than 60 days");
                                        return;
                        }

  }
    location = url;
}


//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,  widgetParent: 'body'
});
//--></script></div>
<?php echo $footer; ?>


<style>
body {
    position: inline;
}
</style>
