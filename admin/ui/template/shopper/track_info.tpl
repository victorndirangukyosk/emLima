<?php echo $header; ?>

<div class="row">
    <div class="col-md-6">

        <div id="map"></div>

        <br />
            
        <div class="small-box">
            <div class="inner">
                <h3 id='total_distance'><?= round($shopper_distance, 2) ?></h3>
                <p><?= $text_total_distance ?>Total Distance</p>
            </div>
            <div class="icon">
                <i class="fa fa-road"></i>
            </div>
        </div>

        <div class="small-box">
            <div class="inner">
                <h3 id='total_commision'><?= round($shopper_commision, 2) ?></h3>
                <p><?= $text_total_commision ?>Total Commision</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="order_info">            
            <table class="table table-bordered">
                <tr>
                    <td colspan="2" class="table-heading">
                        <h3><?= $text_order_info ?>Order Info</h3>
                    </td>
                </tr>
                <tr>
                    <th><?= $column_vendor_order_id ?>Vendor Order ID</th>
                    <td><?= $vendor_order_id ?></td>
                </tr>
                <tr>
                    <th><?= $column_delivery_date ?>Delivery Time</th>
                    <td><?= $delivery_date.', '.$delivery_timeslot ?></td>
                </tr>
                <tr>
                    <th><?= $column_payment_method ?>Payment method</th>
                    <td><?= $payment_method ?></td>
                </tr>
                <tr>
                    <th><?= $column_total ?>Total</th>
                    <td><?= $total ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="table-heading">
                        <h3><?= $text_customer_info ?>Customer Info </h3>
                    </td>
                </tr>
                <tr>
                    <th><?= $column_name ?>Name</th>
                    <td><?= $shipping_name ?></td>
                </tr>
                <tr>
                    <th><?= $column_contact_no ?>Contact no</th>
                    <td><?= $shipping_contact_no ?></td>
                </tr>
                <tr>
                    <th><?= $column_address ?>Address</th>
                    <td><?= $shipping_address ?></td>
                </tr>
                <tr>
                    <th><?= $column_city ?>City</th>
                    <td><?= $shipping_city ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="table-heading">
                        <h3><?= $text_store_info ?>Store Info </h3>
                    </td>
                </tr>
                <tr>
                    <th><?= $column_name ?>Name</th>
                    <td><?= $store_name ?></td>
                </tr>
                <tr>
                    <th><?= $column_address ?>Address</th>
                    <td><?= $store_address ?></td>
                </tr>
            </table>
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
        <?php foreach($path as $row) { 
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