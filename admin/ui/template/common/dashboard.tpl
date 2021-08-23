<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb" style="display:none">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_install) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>Overview</h3>
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <div class="row" id="sum_widgets">
                                <br>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_received; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_processed; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_cancelled; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_incomeplete; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_approval_pening; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_fast; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $manualorders; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $onlineorders; ?></div>

                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $online; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_customers_registered; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_customers_onboarded; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_customers_approval_pending; ?></div>

                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_revenue_booked; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_revenue_collected; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_revenue_pending; ?></div>

                            </div>  
                        </div>
                    </div>

                </div>
            </div>

        </div> 

        <!--Over View Selected Month And Year-->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>Overview(Selected Year & Month)</h3>
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-eye"></i></button>
                        </div>

                        <div class="pull-right">                    

                            <div class="input-group date monthyear" style=" cursor: pointer; padding: 0px 10px;  font-weight: normal;margin-right:20px;margin-top:-4px;">
                                <input type="text" name="filter_monthyear_input" value="<?php echo $filter_monthyear_input; ?>"  data-date-format="YYYY-MM" id="input-monthyear-filter" class="form-control" onkeydown="return false">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample2">
                        <div class="card card-body">
                            <div class="row" id="sum_widgets">
                                <br>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_received_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_processed_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_cancelled_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_incomeplete_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_approval_pending_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order_fast_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $manualorders_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $onlineorders_month; ?></div>

                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_customers_registered_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_customers_onboarded_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_customers_approval_pending_month; ?></div>

                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_revenue_booked_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_revenue_collected_month; ?></div>
                                <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $total_revenue_pending_month; ?></div>

                            </div>  
                        </div>
                    </div>

                </div>
            </div>

        </div> 

        <!--//third DIv Start-->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bar-chart-o"></i>Overview (Date)</h3>

                        <div class="pull-right">

                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseDate" aria-expanded="false" aria-controls="collapseDate"><i class="fa fa-eye"></i></button>
                        </div>

                        <div class="pull-right">                    

                            <div class="input-group date" style=" cursor: pointer; padding: 0px 10px;  font-weight: normal;margin-right:20px;margin-top:-4px;" id="div_date_filter">
                                <input type="text" name="input_date_filter" value="<?php echo $filter_date_input; ?>"  data-date-format="YYYY-MM-DD" id="input_date_filter" class="form-control">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div> 


                        </div>





                    </div>

                    <div class="collapse" id="collapseDate">
                        <div class="card card-body">
                            <div class="row" id="sum_widgets">
                                <br>
                                <div class="col-lg-2 col-md-4 col-sm-6"><?php echo $dashboard_yesterday; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $order_received_ystdate; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $total_revenue_booked_ystdate; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $total_customers_registered_ystdate; ?></div>
                                <!--<div class="col-lg-2 col-md-4 col-sm-6"><?php echo $total_customers_approval_pending_ystdate; ?></div>-->


                                <div class="col-lg-2 col-md-4 col-sm-6"><?php echo $dashboard_today; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $order_received_todaydate; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $total_revenue_booked_todaydate; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $total_customers_registered_todaydate; ?></div>
                                <!--<div class="col-lg-2 col-md-4 col-sm-6"><?php echo $total_customers_approval_pending__todaydate; ?></div>-->

                                <div class="col-lg-2 col-md-4 col-sm-6"><?php echo $dashboard_tomorrow; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $order_received_tmrwdate; ?></div>
                                <div class="col-lg-3 col-md-4 col-sm-6"><?php echo $total_revenue_booked_tmrwdate; ?></div>


                            </div>  
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!--//third Div END-->

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><?php echo $recenttabs; ?></div>
        </div>
    </div>
</div>
<?php echo $footer; ?>


<script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript">

    $('.date').datetimepicker({
        pickTime: false
    });


    $('.monthyear').datetimepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        dateFormat: 'YYYY MM',
        pickTime: false,
        onClose: function (dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });

    $(document).on('dp.change', '.monthyear', function (e) {
        console.log($('#input-monthyear-filter').val());
        var monthyear = $('#input-monthyear-filter').val();

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/ReceivedOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_received_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_received_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_received_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/ProcessedOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_processing_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_processing_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_processing_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/CancelledOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_cancelled_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_cancelled_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_cancelled_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/IncompleteOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_incomplete_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_incomplete_orders').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/ApprovalPendingOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_approvalpending_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_approvalpending_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_approvalpending_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/FastOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_fast_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_fast_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_fast_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/onlineorders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_online_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_online_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_online_orders_url').attr("href", json.online_orders_url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/manualorders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_manual_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_manual_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #manualordersa').attr("href", json.manual_orders_url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
        var total = 0;
        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/customer/CustomersOnboarded&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_customer_onboarded').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                if(json.total != null) {
                    total = json.total;
                } else {
                   total = 0; 
                }
                $('#collapseExample2 #total_customer_onboarded').html('<span>' + total + '</span>');
                $('#collapseExample2 #total_customer_onboarded_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/customer/CustomersRegistered&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_customer_registered').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_customer_registered').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_customer_registered_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/customer/CustomersPendingApproval&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_customer_pending_approval').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_customer_pending_approval').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_customer_pending_approval_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/TotalRevenueBookedDashBoard&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #actual_sales').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #actual_sales').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/TotalRevenueCollectedDashBoard&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #actual_sales_revenue').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #actual_sales_revenue').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/TotalRevenuePendingDashBoard&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #actual_pending_sales').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #actual_pending_sales').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
    });
    
    $(window).load(function() {
    //$( document ).ready(function() {
        console.log($('#input-monthyear-filter').val());
        var monthyear = $('#input-monthyear-filter').val();
                $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/ReceivedOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_received_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_received_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_received_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/ProcessedOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_processing_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_processing_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_processing_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/CancelledOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_cancelled_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_cancelled_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_cancelled_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/IncompleteOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_incomplete_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_incomplete_orders').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/ApprovalPendingOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_approvalpending_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_approvalpending_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_approvalpending_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/FastOrders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_fast_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_fast_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_fast_orders_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/onlineorders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_online_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_online_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_online_orders_url').attr("href", json.online_orders_url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/manualorders&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_manual_orders').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_manual_orders').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #manualordersa').attr("href", json.manual_orders_url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
        var total = 0;
        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/customer/CustomersOnboarded&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_customer_onboarded').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                if(json.total != null) {
                    total = json.total;
                } else {
                   total = 0; 
                }
                $('#collapseExample2 #total_customer_onboarded').html('<span>' + total + '</span>');
                $('#collapseExample2 #total_customer_onboarded_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/customer/CustomersRegistered&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_customer_registered').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_customer_registered').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_customer_registered_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/customer/CustomersPendingApproval&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #total_customer_pending_approval').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #total_customer_pending_approval').html('<span>' + json.total + '</span>');
                $('#collapseExample2 #total_customer_pending_approval_url').attr("href", json.url);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/TotalRevenueBookedDashBoard&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #actual_sales').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #actual_sales').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/TotalRevenueCollectedDashBoard&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #actual_sales_revenue').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #actual_sales_revenue').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $.ajax({
            type: 'get',
            url: 'index.php?path=dashboard/order/TotalRevenuePendingDashBoard&filter_monthyear_added=' + monthyear + '&token=<?php echo $token; ?>',
            dataType: 'json',
            beforeSend: function () {
                $('#collapseExample2 #actual_pending_sales').html('<img src="ui/image/loader.gif">');
            },
            success: function (json) {
                console.log(json);
                $('#collapseExample2 #actual_pending_sales').html('<span>' + json.total + '</span>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
        
    });

    $('#div_date_filter').datetimepicker().on('dp.change', function (e) {
        //console.log(e.date);
        var input_date_filter = $('input[name=\'input_date_filter\']').val();
        if (input_date_filter) {
            //alert(input_date_filter);
            $.ajax({
                url: 'index.php?path=dashboard/order/DashboardOrderDataByDate&token=<?php echo $token; ?>&date=' + encodeURIComponent(input_date_filter),
                dataType: 'json',
                success: function (json) {
                    // alert(json['TotalRevenueBookedYst']);
                    // alert(json['TotalRevenueBookedTmrw']);
                    $("#actual_sales_ystdate").text(json['TotalRevenueBookedYst']);
                    $("#actual_sales_todaydate").text(json['TotalRevenueBookedToday']);
                    $("#actual_sales_tmrwdate").text(json['TotalRevenueBookedTmrw']);

                    // alert(json['DelveredOrdersYst']);
                    // alert(json['Today']);
                    $("#Yst_date").text(json['Yst']);
                    $("#Today_date").text(json['Today']);
                    $("#Tmrw_date").text(json['Tmrw']);

                    $("#total_orders_yst").text(json['DelveredOrdersYst']);
                    $("#total_orders_today").text(json['DelveredOrdersToday']);
                    $("#total_orders_tomorrow").text(json['DelveredOrdersTmrw']);
                    $("#href_total_orders_yst").attr("href", (json['DelveredOrdersYst_url']));
                    $("#href_total_orders_today").attr("href", (json['DelveredOrdersToday_url']));
                    $("#href_total_orders_tomorrow").attr("href", (json['DelveredOrdersTmrw_url']));


                }
            });

            $.ajax({
                url: 'index.php?path=dashboard/customer/DashboardCustomerDataByDate&token=<?php echo $token; ?>&date=' + encodeURIComponent(input_date_filter),
                dataType: 'json',
                success: function (json) {
                    // alert(json['CustomerRegisteredYst_url']);
                    $("#total_customers_yesterday").text(json['CustomerRegisteredYst']);
                    $("#total_customers_today").text(json['CustomerRegisteredToday']);
                    //$("#total_customers_tomorrow").text(json['CustomerRegisteredTmrw']);

                    $("#href_total_customers_yesterday").attr("href", (json['CustomerRegisteredYst_url']));
                    $("#href_total_customers_today").attr("href", (json['CustomerRegisteredToday_url']));
                    $("#href_total_customers_tomorrow").attr("href", (json['CustomerRegisteredTmrw_url']));




                }
            });



        }


    })
</script>