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
                            <i class="fa fa-bar-chart-o"></i>Overview(Selected Month & Year)</h3>
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-eye"></i></button>
                        </div>

                        <div class="pull-right">                    

                            <div class="input-group date" style=" cursor: pointer; padding: 0px 10px;  font-weight: normal;margin-right:20px;margin-top:-4px;">
                                <input type="text" name="filter_monthyear_input" value="<?php echo $filter_monthyear_input; ?>"  data-date-format="YYYY-MM" id="input-monthyear-filter" class="form-control">
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

                            <div class="input-group date" style=" cursor: pointer; padding: 0px 10px;  font-weight: normal;margin-right:20px;margin-top:-4px;">
                                <input type="text" name="filter_date" value="<?php echo $filter_date_input; ?>"  data-date-format="YYYY-MM-DD" id="input-date-filter" class="form-control">
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

    
    $('#input-monthyear-filter').datetimepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'YYYY MM',
            pickTime: false,
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
    });
    
</script>