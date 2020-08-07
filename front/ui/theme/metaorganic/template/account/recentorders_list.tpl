<?php echo $header;?>
 
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--<div class="pull-right"> 
          <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
      </div>-->
      <h1><?php echo $heading_title; ?></h1>
      
    </div>
  </div>
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Orders List</h3>
          
      </div>
      <div class="panel-body">
        
        <form action="" method="post" enctype="multipart/form-data" id="form-recentorders">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                   <td class="text-left"><?php if ($sort == 'order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>">Order Id</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_name; ?>Order Id</a>
                    <?php } ?></td>

                     <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Status</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>">Status</a>
                    <?php } ?></td>

                  <td class="text-left"><?php if ($sort == 'date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Order Date</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>">Order Date </a>
                    <?php } ?></td>


                     <td class="text-left"><?php if ($sort == 'delivery_date') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>">Delivery Date</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>">Delivery Date </a>
                    <?php } ?></td>
                   
                </tr>
              </thead>
              <tbody>
                <?php if ($recentorders) { ?>
                <?php foreach ($recentorders as $pro) { ?>
                <tr>
                 
                  <td class="text-left"><?php echo $pro['order_id']; ?>
                   </td>
                  <td class="text-left"><?php echo $pro['name']; ?></td>
                  <td class="text-left"><?php echo $pro['date_added']; ?></td>
                  <td class="text-left"><?php echo $pro['delivery_date']; ?></td>
                 
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

  <?php echo $footer;?>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  url = 'index.php?path=account/dashboard/getRecentOrderProductsList';
  
  var filter_product_name = $('input[name=\'filter_product_name\']').val();
  
  if (filter_product_name) {
    url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
  }
  location = url;
});
//--></script>  
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});

function excel() {
            
    url = 'index.php?path=sale/customer/export_excel&token=<?php echo $token; ?>';
    
    location = url;
}

//--></script></div>
 
