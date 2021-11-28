<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?= $text_select_shopper ?></h3>
                <div class="pull-right">
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
                                    <input type="text" name="filter_date_start" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_end" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?= $entry_shopper ?></label>
                                <input name="filter_shopper" id="input-status" class="form-control" value="" />
                                <input name="filter_shopper_id" type="hidden"  value="" />
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="getChartData();">
                                <i class="fa fa-search"></i> 
                                <?= $button_filter ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="chart" style="height: 300px;"></div>
                
            </div>
        </div>
    </div>
    
    <!--
    <link type="text/css" href="ui/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
    <script type="text/javascript" src="ui/javascript/jquery/daterangepicker/moment.js"></script>
    <script type="text/javascript" src="ui/javascript/jquery/daterangepicker/daterangepicker.js"></script>
    -->
    
    <script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.tickrotor.js"></script>

    <script type="text/javascript"><!--
                
    function getChartData() {
        
        $url = 'index.php?path=report/shopper_order/getChartData&token=<?= $token ?>';
        
        $filter_date_start = $('input[name="filter_date_start"]').val();
        
        if($filter_date_start) {
            $url += '&filter_date_start=' + $filter_date_start;
        }
        
        $filter_date_end = $('input[name="filter_date_end"]').val();
        
        if($filter_date_end) {
            $url += '&filter_date_end=' + $filter_date_end;
        }
        
        $filter_shopper_id = $('input[name="filter_shopper_id"]').val();
        
        if($filter_shopper_id) {
            $url += '&filter_shopper_id=' + $filter_shopper_id;
        }
        
        $.get($url, function(json){
                            
              data = [{ data:json['order']['fullfilled'], label:"fullfilled", lines:{ show:true }, points:{ show:true }}
                  ,{ data:json['order']['rejected'], label:"rejected", lines:{ show:true }, points:{ show:true }}
                  ,{ data:json['order']['assigned'], label:"assigned", lines:{ show:true }, points:{ show:true }}];
                    
              options = {
                  xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                  },
                  legend:{
                      position:"nw"
                  }
              };
              
              chart = $.plot($("#chart"), data, options);
              
        });
    }
        
    $('input[name=\'filter_shopper\']').autocomplete({
        'source': function(request, response) {
                $.ajax({
                        url: 'index.php?path=report/shopper/name_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',			
                        success: function(json) {
                            response($.map(json, function(item) {
                                    return {
                                            label: item['name'],
                                            value: item['user_id']
                                    }
                            }));
                        }
                });
        },
        'select': function(item) {
                $('input[name=\'filter_shopper\']').val(item['label']);
                $('input[name=\'filter_shopper_id\']').val(item['value']);
        }	
    });

        $('.date').datetimepicker({
                pickTime: false
        });
//--></script>
</div>
<?php echo $footer; ?>