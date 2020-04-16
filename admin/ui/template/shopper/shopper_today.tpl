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

            <div id="sum_widgets">
                <div class="panel income db mbm">
                    <div class="panel-body">
                        <p class="icon"><i class="icon fa fa-money"></i></p>
                        <h4 class="value"><span><?= $commision ?></span></h4>
                        <p class="description"><?= $text_total_commision ?></p>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i><?= $text_today_shipped ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?= $column_order_id ?></th>
                                <th><?= $column_vendor_order_id ?></th>
                                <th><?= $column_store ?></th>
                                <th><?= $column_commision ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order){ ?>
                            <tr>
                                <td><?= $order['order_id'] ?></td>
                                <td><?= $order['vendor_order_id'] ?></td>
                                <td><?= $order['store_name'] ?></td>
                                <td><?= $order['shopper_commision'] ?></td>
                            </tr>
                            <?php } ?>

                            <?php if(!$orders) { ?>
                            <tr>
                                <td colspan="4" class="text-center"><?= $text_no_orders ?></td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-map-marker"></i> <?= $text_today_route ?></h3>
                </div>
                <div class="panel-body">
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php echo $footer; ?> 

<script src="https://maps.googleapis.com/maps/api/js"></script>

<script>

    var lat_min = <?= $limits['lat_min'] ?>;
    var lat_max = <?= $limits['lat_max'] ?>;
    var lng_min = <?= $limits['lng_min'] ?>;
    var lng_max = <?= $limits['lng_max'] ?>;

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        map.setCenter(new google.maps.LatLng(
          ((lat_max + lat_min) / 2.0),
          ((lng_max + lng_min) / 2.0)
        ));

        map.fitBounds(new google.maps.LatLngBounds(
          //bottom left
          new google.maps.LatLng(lat_min, lng_min),
          //top right
          new google.maps.LatLng(lat_max, lng_max)
        ));

        var flightPlanCoordinates = [
        <?php foreach($route as $row) { 
            echo '{ lat: '.$row['latitude'].', lng: '.$row['longitude'].' },'; 
        } ?>   
        ];

        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        flightPath.setMap(map);
    }

    $(function(){
        initMap();
    });
</script>