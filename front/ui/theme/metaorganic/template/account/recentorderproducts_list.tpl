<?php echo $header;?>
 
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> 
          <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      
    </div>
  </div>
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Products List</h3>
          
      </div>
      <div class="panel-body">
        
        <form action="" method="post" enctype="multipart/form-data" id="form-recentorderproducts">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                   <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Product Name</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?>Product Name</a>
                    <?php } ?></td>

                     <td class="text-left"><?php if ($sort == 'unit') { ?>
                    <a href="<?php echo $sort_unit; ?>" class="<?php echo strtolower($order); ?>">Unit of Measure</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_unit; ?>">Unit of Measure</a>
                    <?php } ?></td>

                  <td class="text-left"><?php if ($sort == 'total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>">Qty</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>">Qty </a>
                    <?php } ?></td>
                   
                </tr>
              </thead>
              <tbody>
                <?php if ($recentorderproducts) { ?>
                <?php foreach ($recentorderproducts as $pro) { ?>
                <tr>
                 
                  <td class="text-left"><?php echo $pro['name']; ?>
                   </td>
                  <td class="text-left"><?php echo $pro['unit']; ?></td>
                  <td class="text-left"><?php echo $pro['total']; ?></td>
                 
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
 
