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
    <div class="row" id="sum_widgets">
      <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $sale; ?></div>
      <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order; ?></div>
      <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $customer; ?></div>
      <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $online; ?></div>
      <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $manualorders; ?></div>
      <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $onlineorders; ?></div>
      
    </div>
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
        
      <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><?php echo $charts; ?></div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><?php echo $recenttabs; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>