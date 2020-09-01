<?php echo $header; ?>

<div id="content">
  <div class="page-header">
    <div class="container">
      <div class="col-md-8" style="display: flex; justify-content: right;">
        <h1><strong>MY DASHBOARD</strong></h1>
      </div>
    </div>

     <div class="pull-right" id="chart-date-range"  style="margin-top: -35px;">
        
           <div class="pull-right" >
                    
                                                 <div class="company-name-container" style="width: 180px;">
                                                      <p class="company-name-title" style="margin-left: -10px; display: none;"> </p>
                                                      <select class="company-name">
                                                      <?php foreach($DashboardData['companyname'] as $companyname) { ?>
                                                      <option value="<?php echo $companyname[customer_id]; ?>" >
                                                      <?php  echo  $companyname['company_name']; ?>
                                                      </option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
            </div>
          <div id="reportrange" class="pull-right" style="height:35px;background: #fff; cursor: pointer; padding: 0px 10px; border: 1px solid #ccc; font-weight: normal;margin-right:20px;">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
          </div>
           
      </div>
  </div>

  <div class="container" style="margin-bottom: 3rem">

    <div class="row" id="sum_widgets" style="display: flex; align-items: center; margin-bottom: 48px;">
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="text-center">
          <img src="<?= $base;?>front/ui/theme/mvgv2/images/profile.png" alt="KwikBasket User" width="98">
          <div class="profile-number"><?= $DashboardData['customer_name'] ?> </div>
          <div class="profile-number"><?= $DashboardData['email'] ?> </div>
          <div class="profile-number">+254- <?= $DashboardData['telephone'] ?> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="panel profit db mbm">
          <div class="panel-body">
            <p class="icon"><i class="icon fa fa-truck"></i></p>
            <h4 class="value" style="color: unset"><span id="total_orders"><?php echo $DashboardData['total_orders']; ?></span></h4>
            <p class="description">Total Orders</p>
          </div>
        </div>
        <div class="panel profit db mbm">
          <div class="panel-body">
            <p class="icon"><i class="icon fa fa-cart-arrow-down"></i></p>
            <h4 class="value" style="color: unset"><span id="avg_value"><?php echo $DashboardData['avg_value']; ?></span></h4>
            <p class="description">Avg. Order Value</p>
          </div>

        </div>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="panel db mbm">
          <div class="panel-body">
            <p class="icon"><i class="icon fa fa-money"></i></p>
            <h4 class="value" style="color: unset"><span id="total_spent"><?php echo $DashboardData['total_spent']; ?></span></h4>
            <p class="description">Total Spent</p>
          </div>
        </div>
        <div class="panel profit db mbm" hidden>
          <div class="panel-body">
            <p class="icon"><i class="icon fa fa-line-chart"></i></p>
            <h4 class="value" style="color: unset"><span><?php echo $DashboardData['frequency']; ?></span></h4>
            <p class="description">Frequency</p>
          </div>

        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="panel">
          <div class="panel-body">
            <p class="description"><b>Know Your KwikBasket Champion</b></p>
            <p class="icon"><i class="icon fa fa-group"></i></p>
            <h4 class="value"><span> </span></h4>
            <p class="description" style="margin-top: 8px !important;">Naomi Bosibori
            <br>naomi.bosibori@kwikbasket.com
            </p>
          </div>
        </div>
      </div>
    </div>

 

<div class="row">

     <div id="valueofbasketcharts" class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Basket Value
            </h3>
      </div>
          
          <div id="chart-valueofbasket" class="chart chart_active "></div>
          </div>
     </div>
 </div>

    <div class="row"  style="width: 1207px;margin-left: 243px;">
     <!-- <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12 form-group">
        <div id="recenttabs" class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Buying Pattern
            </h3>
          </div>
          <div id="charts" class="panel-body">
            <div id="chart-recentbuyingpattern" class="chart chart_active"></div>
          </div>
        </div>
      </div>-->

      <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12 form-group">
        <div id="recenttabs" class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Most bought Products (Last 30 days)
            </h3>

             
            <span style="margin-top:-5px;background-color: #f38733;" class="butsetview"><a target="_blank" href="<?php echo BASE_URL;?>/index.php?path=account/dashboard/getRecentOrderProductsList">View All </a></span>
<div class="pull-right">
<button type="button" style="margin-top:-5px;height:31px" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
</div>


          </div>
          <div class="panel-body" width="100%">

            <div class="tab-content panel" width="100%">

              <div class="table-responsive" width="100%">
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
                    <td>
                                       <a href="#" onclick="getPurchaseHistory(<?= $_bestseller['product_id'] ?>)"   data-toggle="modal" data-dismiss="modal" title="View Purchase History" data-target="#productHistory"    class="btn btn-info" style="border-radius: 0px;"  ><i
                                class="fa fa-info"></i></a> 
                                </td>



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




    <div class="row" style="width: 1207px;margin-left: 243px;">
      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12 form-group">
        <div id="recenttabs" class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Recent Activities
            </h3>
          </div>
          <div class="panel-body" width="50%">
            <div class="tab-content panel" width="50%">
              <div id="dash_recent_orders" class="tab-pane active" width="50%">
                <ul class="timeline">
                <?php if ($DashboardData['recent_activity']) { ?>
                <?php foreach ($DashboardData['recent_activity'] as $resat) { ?>
                <li class="tl-item">
                  <div class="tl-wrap {{class}}">
                     <span class="tl-date"><?= $resat['date_added'] ?></span>  
                    <div class="tl-content panel padder b-a">
                      <span class="arrow left pull-up"></span>
                    <div><?php echo $resat['firstname']." ".$resat['lastname']. " ".$resat['comment1']?> <a
                                href="<?php echo $resat['href'];?>" target="_blank" data-toggle="tooltip"
                                title="order info" class="btn-link text_green">
                          <?php echo  "#". $resat['order_id']; ?></a><?php echo " for ".$resat['total'] ."   ".$resat['comment2'] ?></div>

                    </div>
                  </div>
                </li>
                <?php } ?>
                <?php } ?>
                </ul>
              </div>


            </div>
          </div>
        </div>
      </div>


      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12 form-group">
        <div id="recenttabs" class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> Recent Orders
            </h3>

             <span style="margin-top:-5px;background-color: #f38733;" class="butsetview"><a target="_blank" href="<?php echo BASE_URL;?>/index.php?path=account/dashboard/getRecentOrdersList">View All </a></span>
<div class="pull-right">

</div>

          </div>
          <div class="panel-body" width="50%">
            <nav>

            </nav>
            <div class="tab-content panel" width="50%">

              <div class="table-responsive" width="50%">
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
                    <td>


                      <a data-confirm="Products in this order will be added to cart !!"
                         class="btn btn-success download" data-store-id="<?= ACTIVE_STORE_ID ?>"
                         data-toggle="tooltip" value="<?php echo $ro['order_id']; ?>" title="Add To Cart/Reorder"><i
                                class="fa fa-cart-plus"></i></a>

                      <a href="<?php echo $ro['href'];?>" target="_blank" data-toggle="tooltip" title="View Order"
                         class="btn btn-success">
                        <i class="fa fa-eye"></i>
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

  </div>

  <?php echo $footer; ?>



  
    
<div class="phoneModal-popup">
        <div class="modal fade" id="productHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:450px;">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <div class="store-find-block">
                            <div class="mydivsss " style="margin-left:30px;">
                                 </br>  </br>  </br> 
                                    
                                        <h2>   Product Purchase History    </h2>
                                          </br> 
                                   
                                    
                                      </br>
                                    <!-- Text input-->
                                     
                                        <form id="productHistory-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label > No. of Times Purchased </label>
                                                        <input id="product_id"   name="product_id" type="hidden"  class="form-control input-md" required>

                                                    <div class="col-md-12">
                                                        <input id="timespurchased" maxlength="30" required style="max-width:100% ;" name="timespurchased" type="text"   class="form-control" readonly>
                                                    <br/> </div>


                                                </div>
                                               


                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <label    > Total Quantity Purchased </label>

                                                    <div class="col-md-12">
                                                        <input id="qunatitypurchased" maxlength="30" required style="max-width:100% ;" name="qunatitypurchased" type="text"   class="form-control input-md" readonly>
                                                    <br/> </div>

                                                   
                                                </div>
                                                  

                                                 <div class="form-row">
                                                <div class="form-group">
                                                    <label    > Total Value </label>

                                                    <div class="col-md-12">
                                                        <input id="totalvalue" maxlength="30" required style="max-width:100% ;" name="totalvalue" type="text"   class="form-control input-md" readonly>
                                                    <br/> </div>

                                                   
                                                </div>


                                                 <div class="form-group">
                                                    <div class="col-md-12">
                                                       </br>
                                                     
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%;background-Color:green; float: right; margin-top: 10px; height: 45px;border-radius:20px">Close</button>

 
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    
                                 
                            </div>
                           
                            <!-- next div code -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





  <link type="text/css" href="admin/ui/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
  <link href="admin/ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link href="admin/ui/javascript/summernote/summernote.css" rel="stylesheet">
  <link href="admin/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <link href="admin/ui/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
  <link href="admin/ui/stylesheet/custom.css" type="text/css" rel="stylesheet" />


  <link type="text/css" href="admin/ui/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<script type="text/javascript" src="admin/ui/javascript/jquery/daterangepicker/moment.js"></script>
<script type="text/javascript" src="admin/ui/javascript/jquery/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="admin/ui/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript" src="admin/ui/javascript/jquery/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="admin/ui/javascript/jquery/flot/jquery.flot.tickrotor.js"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.flot/0.8.3/jquery.flot.min.js"></script>

<script type="text/javascript">
var start_date = '';
var end_date = '';
var block_range = 'day';


 


jQuery(document).ready(function() {
 

    var cb = function(start, end, label) { /* date range picker callback */
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        /* set global dates */
        start_date = start.format('YYYY-MM-DD');
        end_date = end.format('YYYY-MM-DD');
        /******************************************/

         getNewData();
    };

    var option_daterangepicker = {
        startDate: moment().subtract('days', 29),
        endDate: moment(),
        minDate: '01/01/2012',
        maxDate: '12/31/2050',
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Last 7 Days': [moment().subtract('days', 6), moment()],
            'Last 15 Days': [moment().subtract('days', 14), moment()],
            'Last 30 Days': [moment().subtract('days', 29), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    };

    jQuery('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

    jQuery('#reportrange').daterangepicker(option_daterangepicker, cb);

    start_date  = option_daterangepicker.startDate.format('YYYY-MM-DD');
    end_date    = option_daterangepicker.endDate.format('YYYY-MM-DD')


     getNewData();
});


$(document).delegate('.company-name', 'change', function() { 
   getNewData();

});
 
function getNewData() {

 $optionvalue= $('.company-name option:selected').val() ;
   // alert($optionvalue);
    
    $.ajax({
        type: 'get',        
        url: 'index.php?path=account/dashboard/getDashboardData&start='+ start_date +'&end='+ end_date+'&customer_id='+$optionvalue,         
        dataType: 'json',
        success: function(json) { 
           
            $('#total_orders').html(json['total_orders']);
            $('#avg_value').html(json['avg_value']);
            $('#total_spent').html(json['total_spent']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

valueofbasket($optionvalue);
 //recentPattern($optionvalue);
}




  function getPurchaseHistory($product_id) {
               
             //   $('#poModal-message').html(''); 
 $customer_id= $('.company-name option:selected').val() ;
                 $.ajax({
                    url: 'index.php?path=account/dashboard/getPurchaseHistory&product_id='+$product_id+'&customer_id='+$customer_id,
                    type: 'POST',
                    dataType: 'json',
                    data:{product_id:$product_id,customer_id:$customer_id},
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           $('input[name="timespurchased"]').val(json['timespurchased']) ;
                           $('input[name="qunatitypurchased"]').val(json['qunatitypurchased']) ;
                           $('input[name="totalvalue"]').val(json['totalvalue']) ;
                        }
                        else {
                             $('input[name="timespurchased"]').val('') ;
                           $('input[name="qunatitypurchased"]').val('') ;
                           $('input[name="totalvalue"]').val('') ;
                            
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) { 

                         $('input[name="timespurchased"]').val('') ;
                           $('input[name="qunatitypurchased"]').val('') ;
                           $('input[name="totalvalue"]').val('') ;
                                
                                    return false;
                                }
                });


               
               $('input[name="product_id"]').val($product_id) ;
                  
            }



</script>


 

  <script type="text/javascript"> 

$(function() {

   
    $("select.company-name").prop('disabled', function() {
        return $('option', this).length < 2;
        
    });
    
});

    function recentPattern(optionvalue) {
      $.ajax({
        type: 'get',
        url: 'index.php?path=account/dashboard/recentbuyingpattern&start='+ start_date +'&end='+ end_date +'&selectedcustomer_id='+optionvalue,
        dataType: 'json',
        success: function (json) {

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
              rotateTicks: 75
            },
            yaxis: {
              mode: "money",
              min: 0,
              tickDecimals: 2,
              tickFormatter: function (v, axis) {
                return "KES " + v.toFixed(axis.tickDecimals)
              }
            }
          };

          json['order']['color'] = "#008db9";
          $.plot('#chart-recentbuyingpattern', [json['order']], option);

          $('#chart-recentbuyingpattern').bind('plothover', function (event, pos, item) {
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
        error: function (xhr, ajaxOptions, thrownError) {
          //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });

    }
  </script>


  <script>

    $(document).delegate('.download', 'click', function (e) {
      var baseurl = window.location.origin + window.location.pathname;
      // alert(baseurl);
      var choice = confirm($(this).attr('data-confirm'));
      var added = "false";

      if (choice) {
        e.preventDefault();
        $orderid = $(this).attr('value');
        $store_id = $(this).attr('data-store-id');

        $.ajax({
          url: 'index.php?path=account/dashboard/getOrderProducts',
          dataType: 'json',
          type: 'POST',
          data: {'order_id':$orderid},
          success: function (json) {
            $(json).each(function (index, item) {

              // each iteration
              var product_id = item.product_id;
              var quantity = item.quantity;
              if (quantity > 0) {
                added = "true";
                cart.add(product_id, quantity, 0, $store_id, '', '');

                console.log("added to cart");
              }
            });
          },
          complete: function () {

            baseurl = baseurl + "?path=checkout/checkoutitems";
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
      }
    });


    function excel() {
            
    url = 'index.php?path=account/dashboard/export_mostpurchased_products_excel&customer_id=<?php echo $customer_id; ?>';
    
    location = url;
}





function valueofbasket(optionvalue) {

  
  
   // $('#valueofbasket_score').html('<img src="ui/image/loader.gif">');
   // $('#chart-valueofbasket').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',        
        url: 'index.php?path=account/dashboard/valueofbasket&start='+ start_date +'&end='+ end_date +'&selectedcustomer_id='+optionvalue,
         
        dataType: 'json',
        success: function(json) {
            var option = {
                shadowSize: 0,
                lines: {
                    show: true
                },
                grid: {
                    backgroundColor: '#FFFFFF',
                    hoverable: true
                },
                points: {
                    show: true,
                    fillColor: '#008db9'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis: {
                    mode: "money",
                    min: 0,
                    tickDecimals: 2,
                    tickFormatter: function (v, axis) { return "KES" + v.toFixed(axis.tickDecimals)   }
                }
            };

            json['order']['color'] = "#008db9";
            $.plot('#chart-valueofbasket', [json['order']], option);

            $('#chart-valueofbasket').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-valueofbasket').css('cursor', 'pointer');
                } else {
                    $('#chart-valueofbasket').css('cursor', 'auto');
                }
            });

            //$('#valueofbasket_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}

  </script>

  
  <style>

    #footer {
      position: unset !important;
    }

    #content {
      padding-bottom: 0px !important;
    }

    .header__logo-container img {
      margin-top: 0px !important;
      width: 15rem !important;
    }

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
  </style>








