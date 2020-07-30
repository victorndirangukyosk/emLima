<?php echo $header; ?>

<link type="text/css" href="admin/ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link href="admin/ui/javascript/bootstrap/shop/shop.css" type="text/css" rel="stylesheet" />
<link href="admin/ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="admin/ui/javascript/summernote/summernote.css" rel="stylesheet">
<link href="admin/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link href="admin/ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
<link href="admin/ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />

<!-- include libraries(jQuery, bootstrap) -->
<!-- <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>  -->

<script type="text/javascript" src="admin/ui/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="admin/ui/javascript/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="admin/ui/javascript/bootstrap-select/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="admin/ui/javascript/tinymce/jquery.tinymce.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.0/jquery.tinymce.min.js"></script> -->

<script src="admin/ui/javascript/common.js" type="text/javascript"></script>
<script src="admin/ui/javascript/jscolor-2.0.4/jscolor.js" type="text/javascript"></script>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h3><?php echo Dashboard ?></h3>
      
    </div>
  </div>
  <div class="container-fluid">



        <div class="row" id="sum_widgets">
 <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="profile-block">
         <img src="<?= $base;?>front/ui/theme/mvgv2/images/profile.png" alt="">
                                        <div class="profile-number"><?= $DashboardData['customer_name'] ?> </div>
                                        <div class="profile-number"><?= $DashboardData['email'] ?> </div>
                                        <div class="profile-number">+254- <?= $DashboardData['telephone'] ?> </div>
                                    </div></div>

      

      <div class="col-lg-3 col-md-4 col-sm-6">
      <div class="panel profit db mbm">
  <div class="panel-body">
    <p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
    <h4 class="value"><span><?php echo $DashboardData['total_orders']; ?></span></h4>
    <p class="description">Total Orders</p>
  </div>
</div>
 <div class="panel profit db mbm">
  <div class="panel-body">
    <p class="icon"><i class="icon fa fa-eur"></i></p>
    <h4 class="value"><span><?php echo $DashboardData['avg_value']; ?></span></h4>
    <p class="description">Avg. Value</p>
  </div>

</div>
</div>
      <div class="col-lg-3 col-md-4 col-sm-6"><div class="panel db mbm">
  <div class="panel-body">
    <p class="icon"><i class="icon fa fa-money"></i></p>
    <h4 class="value"><span><?php echo $DashboardData['total_spent']; ?></span></h4>
    <p class="description">Total Spent</p>
  </div>
</div>
 <div class="panel profit db mbm">
  <div class="panel-body">
    <p class="icon"><i class="icon fa fa-list"></i></p>
    <h4 class="value"><span><?php echo $DashboardData['frequency']; ?></span></h4>
    <p class="description">Frequency</p>
  </div>
  
</div></div>
       <div class="col-lg-3 col-md-3 col-sm-6"><div class="panel">
  <div class="panel-body">
  <p class="description"> <b>Know Your KwikBasket Champion</b></p>
    <p class="icon"><i class="icon fa fa-group"></i></p>
    <h4 class="value"><span> </span></h4>
    <p class="description">Wellington Ayugi
</br></br>+254-123456789
</br></br>
wa@kwikbasket.com</br> </br>
Badge:</br> 
</p>
  </div>
</div></div>  
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><div class="panel panel-default">

   
</div>
<link type="text/css" href="ui/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<script type="text/javascript" src="ui/javascript/jquery/daterangepicker/moment.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.tickrotor.js"></script>

<script type="text/javascript">
var start_date = '';
var end_date = '';
var block_range = 'day';

jQuery(document).ready(function() { 
  
});

$('#block-range li').on('click', function(e) {
    e.preventDefault();

    $(this).parent().find('li').removeClass('active');
    $(this).addClass('active');

    block_range = $(this).attr('id');

    
});
 

  
 
   
</script>

 
</div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div id="recenttabs" class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Most bought Products (Last 30 days)
</h3>
  </div>
  <div class="panel-body"  width="50%">
    <nav>
      
    </nav>
    <div class="tab-content panel"  width="50%">
      <div id="dash_recent_orders" class="tab-pane active"  width="50%">
        <div class="table-responsive"  width="50%">
        <table class="table table-bordered">
            <thead>
            <tr>
              
              <td>Product Name</td>
              <td>Unit of Measure</td>
              <td>Qty</td>
              
            </tr>
            </thead>
            <tbody>
                  <?php if ($DashboardData['most_purhased']) { ?>
            <?php foreach ($DashboardData['most_purhased'] as $_bestseller) { ?>
            <tr>
              
              <td><?php echo $_bestseller['name']; ?></td>
              <td><?php echo $_bestseller['unit']; ?></td>
              <td><?php echo $_bestseller['total']; ?></td>
               
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
      </div>
     
       
    </div>
  </div>
</div>
</div>


 <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div id="recenttabs" class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Recent Orders
</h3>
  </div>
  <div class="panel-body"  width="50%">
    <nav>
      
    </nav>
    <div class="tab-content panel"  width="50%">
      <div id="dash_recent_orders" class="tab-pane active"  width="50%">
        <div class="table-responsive"  width="50%">
        <table class="table table-bordered">
            <thead>
            <tr>
              
              <td>Order Id</td>
              <td>Status</td>
              <td>Order Date</td>
              <td>Delivery Date</td>
              <td class="text-center">Action</td>
            </tr>
            </thead>
            <tbody>
                  <?php if ($DashboardData['recent_orders']) { ?>
            <?php foreach ($DashboardData['recent_orders'] as $ro) { ?>
            <tr>
              
              <td><?php echo $ro['order_id']; ?></td>
              <td><?php echo $ro['name']; ?></td>
              <td><?php echo $ro['date_added']; ?></td>
              <td><?php echo $ro['delivery_date']; ?></td>
              <td class="text-center">
                <a href="" data-toggle="tooltip" title="add to cart" class="btn btn-success">
                  <i class="icon fa fa-shopping-cart"></i>
                </a>

                <a href="" data-toggle="tooltip" title="add to cart" class="btn btn-success">
                  <i class="icon fa fa-shopping-cart"></i>
                </a>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="6"><?php echo 'Functionality pending'; ?></td>
            </tr>
            <?php } ?>
                                    </tbody>
          </table>
        </div>
      </div>
     
       
    </div>
  </div>
</div>
</div>
    </div>
  </div>
   
<?php echo $footer; ?>


<style>
#sum_widgets .db:hover {
    background: #008db9;
    color: #fff;
}

#sum_widgets .panel {
    border: 1px solid #DDDDDD;
}

.panel {
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    -webkit-border-radius: 0 !important;
    -moz-border-radius: 0 !important;
    border-radius: 0 !important;
}


<link type="text/css" href="ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link href="ui/javascript/bootstrap/shop/shop.css" type="text/css" rel="stylesheet" />
<link href="ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="ui/javascript/summernote/summernote.css" rel="stylesheet">
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link href="ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
<link href="ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />

</style>


 
