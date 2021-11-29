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

        <div class='col-md-6'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart"></i><?= $text_orders ?></h3>
                </div>
                <div class="panel-body">
                    <div id="orders" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart"></i><?= $text_vendors ?></h3>
                </div>
                <div class="panel-body">
                    <div id="vendors" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart"></i><?= $text_stores ?></h3>
                </div>
                <div class="panel-body">
                    <div id="stores" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart"></i><?= $text_shoppers ?></h3>
                </div>
                <div class="panel-body">
                    <div id="shoppers" style="height: 300px;"></div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.pie.min.js"></script>

<script>
$(function(){
    
    $.plot('#stores', <?= json_encode($stores) ?>, {
        series: {
            pie: {
                show: true,
                radius: 1,
                label: {
                    show: true,
                    radius: 1,
                    formatter: labelFormatter,
                    background: {
                        opacity: 0.8
                    }
                }
            }
        },
        legend: {
            show: true
        }
    });
    
    $.plot('#vendors', <?= json_encode($vendors) ?>, {
        series: {
            pie: {
                show: true,
                radius: 1,
                label: {
                    show: true,
                    radius: 1,
                    formatter: labelFormatter,
                    background: {
                        opacity: 0.8
                    }
                }
            }
        },
        legend: {
            show: true
        }
    });
    
    $.plot('#shoppers', <?= json_encode($shoppers) ?>, {
        series: {
            pie: {
                show: true,
                radius: 1,
                label: {
                    show: true,
                    radius: 1,
                    formatter: labelFormatter,
                    background: {
                        opacity: 0.8
                    }
                }
            }
        },
        legend: {
            show: true
        }
    });
    
    $.plot('#orders', <?= json_encode($orders) ?>, {
        series: {
            pie: {
                show: true,
                radius: 1,
                label: {
                    show: true,
                    radius: 1,
                    formatter: labelFormatter,
                    background: {
                        opacity: 0.8
                    }
                }
            }
        },
        legend: {
            show: true
        }
    });
});  

function labelFormatter(label, series) {
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
}
</script>
    
    