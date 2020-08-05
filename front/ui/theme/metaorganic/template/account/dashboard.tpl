<?php echo $header; ?>

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
                </div>
            </div>
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

                </div>
              </div> 

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
            </div>
        </div>  
        
        
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><div class="panel panel-default">
            </div> 
        </div>
         
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div id="recenttabs" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Most bought Products (Last 30 days)
                        </h3>
                    </div>
                    <div class="panel-body"  width="50%">
                        
                        <div class="tab-content panel"  width="50%">
                            
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


            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div id="recenttabs" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Recent Orders
                        </h3>
                    </div>
                    <div class="panel-body"  width="50%">
                        <nav>

                        </nav>
                        <div class="tab-content panel"  width="50%">
                          
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
                                                <td >
                                                    

                                                    <a  data-confirm="Products in this order will be added to cart !!" class="btn btn-success download"  data-store-id="<?= ACTIVE_STORE_ID ?>"  data-toggle="tooltip"    value="<?php echo $ro['order_id']; ?>" title="Add To Cart/Reorder"><i class="fa fa-dollar"></i></a>

                                                    <a href="<?php echo $ro['href'];?>" target="_blank" data-toggle="tooltip" title="order info" class="btn btn-success">
                                                        <i class="fa fa-info"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="6"><?php echo 'No Orders'; ?></td>
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

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div id="recenttabs" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Recent Activities
                        </h3>
                    </div>
                    <div class="panel-body"  width="50%">
                        <nav>

                        </nav>
                        <div class="tab-content panel"  width="50%">
                            <div id="dash_recent_orders" class="tab-pane active"  width="50%">
                                <?php if ($DashboardData['recent_activity']) { ?>
                                <?php foreach ($DashboardData['recent_activity'] as $resat) { ?>
                                <div class="table-responsive"  width="50%">
                                    <?php echo $resat['firstname']." ".$resat['lastname']. " Placed Order "?> <a href="<?php echo $resat['href'];?>" target="_blank" data-toggle="tooltip" title="order info" class="btn-link text_green">
                                        <?php echo  "#". $resat['order_id']; ?></a><?php echo " for KES ".$resat['total']."  ". $resat['date_added']; ?>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div id="recenttabs" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i>Recent Buying Pattern
                        </h3>
                    </div>
                    <div id="charts" class="panel-body">
          <div id="chart-recentbuyingpattern"  class="chart chart_active"></div>
                 </div>
                </div>
            </div>
        </div>
        
    </div>
 <div class="row">
            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><div class="panel panel-default">
                    <div class="panel-heading">
                       
                    </div>
                    <div  width="50%">
                        <nav>

                        </nav>
                        <div class="tab-content panel"  width="50%">
                            <div   class="tab-pane active"  width="50%">
                                
                            </div>


                        </div>
                    </div>
                </div>
            </div>


           
        </div>
 </div> 


<?php echo $footer; ?>




<link type="text/css" href="admin/ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link href="admin/ui/javascript/bootstrap/shop/shop.css" type="text/css" rel="stylesheet" />
<link href="admin/ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="admin/ui/javascript/summernote/summernote.css" rel="stylesheet">
<link href="admin/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link href="admin/ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
<link href="admin/ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />
 
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
.new {
}

        <link type="text/css" href="ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
        <link href="ui/javascript/bootstrap/shop/shop.css" type="text/css" rel="stylesheet" />
        <link href="ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
        <link href="ui/javascript/summernote/summernote.css" rel="stylesheet">
        <link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
        <link href="ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
        <link href="ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />

    </style>

    <link type="text/css" href="admin/ui/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.flot/0.8.3/jquery.flot.min.js"></script>

<script type="text/javascript" src="admin/ui/javascript/jquery/daterangepicker/moment.js"></script>
<script type="text/javascript" src="admin/ui/javascript/jquery/daterangepicker/daterangepicker.js"></script>

<script type="text/javascript" src="admin/ui/javascript/jquery/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="admin/ui/javascript/jquery/flot/jquery.flot.tickrotor.js"></script>
 



<script type="text/javascript">
   $( document ).ready(function() { 
   recentPattern();
});
 

function recentPattern() {  
 //  $('#chart-recentbuyingpattern').html('<div class="loading"><img src="ui/image/loader.gif"></div>');
 
    $.ajax({
        type: 'get',       
        url: 'index.php?path=account/dashboard/recentbuyingpattern',      
        dataType: 'json',
        success: function(json) {
          
            var option = {
                shadowSize: 0,
               // lines: {
                 //   show: false
                //},
                series: {
    bars: {
        show: true
    }
},
bars: {
    align: "center",
    barWidth: 0.5
},
                grid: {
                    backgroundColor: '#FFFFFF',
                    hoverable: true
                },
                //bars: {
                   // show: true,
                   // fillColor: '#008db9',
                   // width:'2px',
                   // align: "center",
                //},
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis: {
                    mode: "money",
                    min: 0,
                    tickDecimals: 2,
                    tickFormatter: function (v, axis) { return "KES " + v.toFixed(axis.tickDecimals) }
                }
            };

            json['order']['color'] = "#008db9";
            $.plot('#chart-recentbuyingpattern', [json['order']], option);

            $('#chart-recentbuyingpattern').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-recentbuyingpattern').css('cursor', 'pointer');
                } else {
                    $('#chart-recentbuyingpattern').css('cursor', 'auto');
                }
            });

           
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}


</script>

<script>

 $(document).delegate('.download', 'click', function(e) {
     var baseurl = window.location.origin+window.location.pathname;
    // alert(baseurl);
  var choice = confirm($(this).attr('data-confirm'));
 var added = "false";
   
   if(choice) {
            e.preventDefault();
            $orderid = $(this).attr('value');  
            $store_id = $(this).attr('data-store-id');   
       
              $.ajax({
                    url: 'index.php?path=account/dashboard/getOrderProducts',
                    dataType: 'json',
                    type:'POST',
                    data: {'order_id':$orderid},
                    success: function(json) {
                                  $(json).each(function (index, item) {

               // each iteration
               var product_id = item.product_id;
               var quantity = item.quantity;
               if (quantity > 0) { 
                    added = "true";
                cart.add(product_id, quantity, 0,$store_id,'','');                
               
                console.log("added to cart"); 
            }
           }); 
                    },
                    complete: function() {
				 
                  baseurl=baseurl+"?path=checkout/checkoutitems";
           var win = window.open(baseurl, '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                } else {
                    //Browser has blocked it
                    alert('Please allow popups for this website');
                }
			},	
                }); 
                //alert(added);         
          
           
               /* if(added){
                   
                 
                    var win = window.open(baseurl, '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                } else {
                    //Browser has blocked it
                    alert('Please allow popups for this website');
                }

              }*/
   }

  
        });

 
</script>
 
 



