<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
        <i class="fa fa-bar-chart-o"></i> <?php echo $heading_title; ?></h3>
      <div class="pull-right" id="chart-date-range">
       
          <div id="block-range" class="btn-group">
           

            <li class="btn btn-default active" id="day"><?php echo $text_day; ?></li>
            <li class="btn btn-default" id="month"><?php echo $text_month; ?></li>
            <li class="btn btn-default " id="year"><?php echo $text_year; ?></li>
          </div>
           <div class="pull-right" >
                    <button style="height:26px" type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success pull-right" data-original-title="Download Excel"><i class="fa fa-download"></i></button>

            </div>
          <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 0px 10px; border: 1px solid #ccc; font-weight: normal;margin-right:20px;">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
          </div>
           
      </div>
  </div>
  <div class="panel-body">
      <div id="tab_toolbar" class="panel-body" style="width: 100%; display: table; color: #555555;">

            <?php if($this->user->isVendor()) { ?>
                    <dl onclick="getChart(this, 'sales');" class="col-xs-4 col-lg-4 active" style="background-color: #008db9;border-left-width: 1px;border-left-style: solid;border-left-color: rgb(221, 221, 221);">
            <?php } else { ?>

                <dl onclick="getChart(this, 'sales');" class="col-xs-4 col-lg-4 active" style="background-color: #008db9;border-left-width: 1px;
    border-left-style: solid;
    border-left-color: rgb(221, 221, 221);">

            <?php } ?>
              <dt><?php echo 'Revenue Collected '; ?></dt>
              <dd class="data_value size_l"><span id="sales_score"></span></dd>
          </dl>

          <?php if($this->user->isVendor()) { ?>
                    <dl onclick="getChart(this, 'orders');" class="col-xs-4 col-lg-4 passive" style="background-color: #5cb85c">
            <?php } else { ?>

                <dl onclick="getChart(this, 'orders');" class="col-xs-4 col-lg-4 passive" style="background-color: #5cb85c">

            <?php } ?>
            
              <dt><?php echo  'Orders Delivered '; ?></dt>
              <dd class="data_value size_l"><span id="orders_score"></span></dd>
          </dl>

          <?php if(!$this->user->isVendor()) { ?>
                    <dl onclick="getChart(this, 'customers');" class="col-xs-4 col-lg-4 passive" style="background-color: #d9534f">
                      <dt><?php echo $text_customer . ' Onboarded' ; ?></dt>
                      <dd class="data_value size_l"><span id="customers_score"></span></dd>
                  </dl>
            <?php } else { ?>
                <dl class="col-xs-4 col-lg-4 passive" style="background-color: #d9534f">
                      <dt>Stores</dt>
                      <dd class=""><span id=""><?= $store_count ?></span></dd>
                  </dl>

            <?php } ?>

            <!-- My added -->


            

          
 <?php if($this->user->isVendor()) { ?>
                    <dl onclick="getChart(this, 'bookedsales');" class="col-xs-4 col-lg-4 passive" style="background-color: #5b0060;border-left-width: 1px;border-left-style: solid;border-left-color: rgb(221, 221, 221);">
            <?php } else { ?>

                <dl onclick="getChart(this, 'bookedsales');" class="col-xs-4 col-lg-4 passive" style="background-color: #5b0060;border-left-width: 1px;
    border-left-style: solid;
    border-left-color: rgb(221, 221, 221);">

            <?php } ?>
              <dt><?php echo 'Revenue Booked '; ?></dt>
                          <!-- <dd class="data_value size_l"><span id=""> </span><span id="created_orders_value"> <?= $todaysCreatedOrders['value'] ?> </span></dd> -->

          <dd class="data_value size_l"><span id="bookedsales_score"></span></dd> </dl>


 

          
          <?php if($this->user->isVendor()) { ?>
                    <dl onclick="getChart(this, 'createdorders');" class="col-xs-4 col-lg-4 passive" style="background-color: #585a00">
            <?php } else { ?>

                <dl onclick="getChart(this, 'createdorders');" class="col-xs-4 col-lg-4 passive" style="background-color: #585a00">

            <?php } ?>
            
              <dt><?php echo  'Orders Created '; ?></dt>
                           <!--<dd class="data_value size_l"><span id=""> Count: </span><span id="created_orders_total"><?= $todaysCreatedOrders['total'] ?> </span></dd>-->

          <dd class="data_value size_l"><span id="createdorders_score"></span></dd>
           </dl>



          

          <!--<?php if($this->user->isVendor()) { ?>
                    <dl onclick="getChartX(this, 'orders');" class="col-xs-4 col-lg-4 passive" >
            <?php } else { ?>

                <dl onclick="getChartX(this, 'orders');" class="col-xs-4 col-lg-4 passive" >

            <?php } ?>
            
              <dt>Delivered Orders</dt>
              <dd class="data_value size_l"><span id=""> Count: </span><span id="delivered_orders_total"><?= $todaysDeliveredOrders['total'] ?> </span></dd>
              <dd class="data_value size_l"><span id="">Value: </span><span id="delivered_orders_value"> <?= $todaysDeliveredOrders['value'] ?> </span></dd>
          </dl>-->

          
            <dl onclick="getChart(this, 'cancelledorders');" class="col-xs-4 col-lg-4 passive" style="background-color: #800000">
                <dt>Cancelled Orders</dt>
                <dd class="data_value size_l"><span id="cancelledorders_score"></span></dd>
                <!--<dd class="data_value size_l"><span id=""> Count: </span><span id="cancelled_orders_total"><?= $todaysCancelledOrders['total'] ?> <br> </span>  - Value: </span><span id="cancelled_orders_value"> <?= $todaysCancelledOrders['value'] ?> </span></dd>
                <dd class="data_value size_l"><span id="">Value: </span><span id="cancelled_orders_value"> <?= $todaysCancelledOrders['value'] ?> </span></dd>-->
          </dl>

             



          <!-- My added end -->

          <!-- <dl onclick="getChart(this, 'affiliates');" class="col-xs-4 col-lg-2 passive  fourth-tab" style="background-color: #6b399c">
              <dt><?php echo $text_affiliates; ?></dt>
              <dd class="data_value size_l"><span id="affiliates_score"></span></dd>
          </dl>
          <dl onclick="getChart(this, 'reviews');" class="col-xs-4 col-lg-2 passive" style="background-color: #f2994b">
              <dt><?php echo $text_reviews; ?></dt>
              <dd class="data_value size_l"><span id="reviews_score"></span></dd>
          </dl>
          <dl onclick="getChart(this, 'rewards');" class="col-xs-4 col-lg-2 passive" style="background-color: #b3591f">
              <dt><?php echo $text_rewards; ?></dt>
              <dd class="data_value size_l"><span id="rewards_score"></span></dd>
          </dl> -->
          
      </div>
      <div id="charts" class="panel-body">
          <div id="chart-sales" class="chart chart_active"></div>
          <div id="chart-bookedsales" class="chart "></div>
          <div id="chart-orders" class="chart "></div>
          <div id="chart-createdorders" class="chart "></div>
          <div id="chart-cancelledorders" class="chart "></div>
          <div id="chart-customers" class="chart "></div>
          <div id="chart-affiliates" class="chart "></div>
          <div id="chart-reviews" class="chart "></div>
          <div id="chart-rewards" class="chart "></div>
      </div>
  </div>
</div>
<link type="text/css" href="ui/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
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
    var cb = function(start, end, label) { /* date range picker callback */
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        /* set global dates */
        start_date = start.format('YYYY-MM-DD');
        end_date = end.format('YYYY-MM-DD');
        /******************************************/

        getCharts();
    };

    var option_daterangepicker = {
        startDate: moment().subtract('days', 14),
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

    jQuery('#reportrange span').html(moment().subtract('days', 14).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

    jQuery('#reportrange').daterangepicker(option_daterangepicker, cb);

    start_date  = option_daterangepicker.startDate.format('YYYY-MM-DD');
    end_date    = option_daterangepicker.endDate.format('YYYY-MM-DD')

    
    block_range = $('#block-range li.active').attr('id');

    getCharts();
});

$('#block-range li').on('click', function(e) {
    e.preventDefault();

    $(this).parent().find('li').removeClass('active');
    $(this).addClass('active');

    block_range = $(this).attr('id');

    getCharts();
});

function getChart(tab, chart) {
    jQuery('#tab_toolbar dl').removeClass('active');
    jQuery('#tab_toolbar dl').addClass('passive');
    jQuery('#charts div').removeClass('chart_active');

    jQuery('#chart-'+chart).addClass('chart_active');
    jQuery(tab).removeClass('passive');
    jQuery(tab).addClass('active');

    switch (chart) {
        case 'sales' :
            sales();
            break;
             case 'bookedsales' :
            bookedsales();
            break;
        case 'orders' :
            orders();
            break;
             case 'createdorders' :
            createdorders();
            break;

              case 'cancelledorders' :
            cancelledorders();
            break;
        case 'customers' :
            customers();
            break;
        // case 'affiliates' :
        //     affiliates();
        //     break;
        case 'reviews' :
            reviews();
            break;
        case 'rewards' :
            rewards();
            break;
        default :
            break;
    }
}

function getCharts(){
    sales();
    bookedsales();
    orders();
    createdorders();
    cancelledorders();
    customers();
    ///affiliates();
    reviews();
    rewards();
}

function getChartsX(){
    
}


function sales() {
    $('#sales_score').html('<img src="ui/image/loader.gif">');
    $('#chart-sales').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        <?php if($this->user->isVendor()){ ?>
        url: 'index.php?path=dashboard/charts/vendorsales&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php }else{ ?>
        url: 'index.php?path=dashboard/charts/sales&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php } ?>
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
                    tickFormatter: function (v, axis) { return "<?php echo $symbol_left; ?>" + v.toFixed(axis.tickDecimals) + "<?php echo $symbol_right; ?>" }
                }
            };

            json['order']['color'] = "#008db9";
            $.plot('#chart-sales', [json['order']], option);

            $('#chart-sales').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-sales').css('cursor', 'pointer');
                } else {
                    $('#chart-sales').css('cursor', 'auto');
                }
            });

            $('#sales_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}




function bookedsales() {
    $('#bookedsales_score').html('<img src="ui/image/loader.gif">');
    $('#chart-bookedsales').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        <?php if($this->user->isVendor()){ ?>
        url: 'index.php?path=dashboard/charts/vendorbookedsales&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php }else{ ?>
        url: 'index.php?path=dashboard/charts/bookedsales&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php } ?>
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
                    tickFormatter: function (v, axis) { return "<?php echo $symbol_left; ?>" + v.toFixed(axis.tickDecimals) + "<?php echo $symbol_right; ?>" }
                }
            };

            json['order']['color'] = "#008db9";
            $.plot('#chart-bookedsales', [json['order']], option);

            $('#chart-bookedsales').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-bookedsales').css('cursor', 'pointer');
                } else {
                    $('#chart-bookedsales').css('cursor', 'auto');
                }
            });

            $('#bookedsales_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}


function orders() {
    $('#orders_score').html('<img src="ui/image/loader.gif">');
    $('#chart-orders').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        <?php if($this->user->isVendor()){ ?>
        url: 'index.php?path=dashboard/charts/VendorOrders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php }else{ ?>
        url: 'index.php?path=dashboard/charts/orders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,    
        <?php } ?>
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
                    fillColor: '#5cb85c'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#5cb85c";
            $.plot('#chart-orders', [json['order']], option);

            console.log("ordr");
            console.log(json);
            $('#chart-orders').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-orders').css('cursor', 'pointer');
                } else {
                    $('#chart-orders').css('cursor', 'auto');
                }
            });

            $('#orders_score').html(json['order']['total']);

            /* start */
            $('#created_orders_total').html(json['created_orders']['total']);
            $('#created_orders_value').html(json['created_orders']['value']);

            $('#delivered_orders_total').html(json['delivered_orders']['total']);
            $('#delivered_orders_value').html(json['delivered_orders']['value']);

            $('#cancelled_orders_total').html(json['cancelled_orders']['total']);
            $('#cancelled_orders_value').html(json['cancelled_orders']['value']);

            /* end */
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}




function createdorders() {
    $('#createdorders_score').html('<img src="ui/image/loader.gif">');
    $('#chart-createdorders').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        <?php if($this->user->isVendor()){ ?>
        url: 'index.php?path=dashboard/charts/VendorCreatedOrders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php }else{ ?>
        url: 'index.php?path=dashboard/charts/Createdorders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,    
        <?php } ?>
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
                    fillColor: '#5cb85c'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#5cb85c";
            $.plot('#chart-createdorders', [json['order']], option);

            console.log("ordr");
            console.log(json);
            $('#chart-createdorders').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-createdorders').css('cursor', 'pointer');
                } else {
                    $('#chart-createdorders').css('cursor', 'auto');
                }
            });

            $('#createdorders_score').html(json['order']['total']);

            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}





function cancelledorders() {
    $('#cancelledorders_score').html('<img src="ui/image/loader.gif">');
    $('#chart-cancelledorders').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        <?php if($this->user->isVendor()){ ?>
        url: 'index.php?path=dashboard/charts/VendorCancelledOrders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        <?php }else{ ?>
        url: 'index.php?path=dashboard/charts/Cancelledorders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,    
        <?php } ?>
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
                    fillColor: '#5cb85c'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#5cb85c";
            $.plot('#chart-cancelledorders', [json['order']], option);

            console.log("ordr");
            console.log(json);
            $('#chart-cancelledorders').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-cancelledorders').css('cursor', 'pointer');
                } else {
                    $('#chart-cancelledorders').css('cursor', 'auto');
                }
            });

            $('#cancelledorders_score').html(json['order']['total']);

            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function customers() {
    $('#customers_score').html('<img src="ui/image/loader.gif">');
    $('#chart-customers').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        url: 'index.php?path=dashboard/charts/customers&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
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
                    fillColor: '#d9534f'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#d9534f";
            $.plot('#chart-customers', [json['order']], option);

            $('#chart-customers').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-customers').css('cursor', 'pointer');
                } else {
                    $('#chart-customers').css('cursor', 'auto');
                }
            });

            $('#customers_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function affiliates() {
    $('#affiliates_score').html('<img src="ui/image/loader.gif">');
    $('#chart-affiliates').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        url: 'index.php?path=dashboard/charts/affiliates&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
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
                    fillColor: '#6b399c'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#6b399c";
            $.plot('#chart-affiliates', [json['order']], option);

            $('#chart-affiliates').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-affiliates').css('cursor', 'pointer');
                } else {
                    $('#chart-affiliates').css('cursor', 'auto');
                }
            });

            $('#affiliates_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function reviews() {
    $('#reviews_score').html('<img src="ui/image/loader.gif">');
    $('#chart-reviews').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        url: 'index.php?path=dashboard/charts/reviews&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
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
                    fillColor: '#f2994b'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#f2994b";
            $.plot('#chart-reviews', [json['order']], option);

            $('#chart-reviews').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-reviews').css('cursor', 'pointer');
                } else {
                    $('#chart-reviews').css('cursor', 'auto');
                }
            });

            $('#reviews_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function rewards() {
    $('#rewards_score').html('<img src="ui/image/loader.gif">');
    $('#chart-rewards').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        url: 'index.php?path=dashboard/charts/rewards&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
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
                    fillColor: '#b3591f'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#b3591f";
            $.plot('#chart-rewards', [json['order']], option);

            $('#chart-rewards').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-rewards').css('cursor', 'pointer');
                } else {
                    $('#chart-rewards').css('cursor', 'auto');
                }
            });

            $('#rewards_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
</script>


<script type="text/javascript"> 
 

function excel() {
 //alert( $('#sales_score').html()); 


    url = 'index.php?path=dashboard/charts/export_excel&token=<?php echo $token; ?> '; 
    url=url.trim();

                url += '&start_date=' + encodeURIComponent(start_date); 
                url += '&end_date=' + encodeURIComponent(end_date);
            

            var ss = $('#sales_score').html();

            if ( ss!= '') {
                url += '&ss=' + encodeURIComponent(ss);
            }

 var os = $('#orders_score').html();

            if ( os!= '') {
                url += '&os=' + encodeURIComponent(os);
            }


             var cs = $('#customers_score').html();

            if ( cs!= '') {
                url += '&cs=' + encodeURIComponent(cs);
            }


             var bs = $('#bookedsales_score').html();

            if ( bs!= '') {
                url += '&bs=' + encodeURIComponent(bs);
            }


             var cos = $('#createdorders_score').html();

            if ( cos!= '') {
                url += '&cos=' + encodeURIComponent(cos);
            }


             var cns = $('#cancelledorders_score').html();

            if ( cns!= '') {
                url += '&cns=' + encodeURIComponent(cns);
            }
            
  if(url.indexOf("gif")>-1)
  {
            //alert("Please wait ,until the data is loaded and then again click download");
             alert("Please try again");
  }
  else{
        location = url;
  }
    
   
}

 </script>
