<?php echo $header; ?>

<div class="row">
    <div class="col-md-6">
        
        <div id="tracking">
            <img alt="Tracking..." src="ui/image/ship_wheel_loading.gif" />
        </div>
        
        <div class="small-box">
            <div class="inner">
                <h3 id='total_distance'>0</h3>
                <p><?= $text_total_distance ?>Total Distance</p>
            </div>
            <div class="icon">
                <i class="fa fa-road"></i>
            </div>
        </div>
        
        <div class="small-box">
            <div class="inner">
                <h3 id='total_commision'>0</h3>
                <p><?= $text_total_commision ?>Total Commision</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
        </div>
        
        <button class="btn btn-lg btn-primary" id='btn-shipped'>
            <?= $button_order_shipped ?>Order Shipped
        </button>
        
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
                    <th><?= $column_vendor_order_id ?></th>
                    <td><?= $vendor_order_id ?></td>
                </tr>
                <tr>
                    <th><?= $column_delivery_date ?></th>
                    <td><?= $delivery_date.', '.$delivery_timeslot ?></td>
                </tr>
                <tr>
                    <th><?= $column_payment_method ?>Payment method</th>
                    <td><?= $payment_method ?></td>
                </tr>
                <tr>
                    <th><?= $column_total ?></th>
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

<script>
     
    var watchID;
    var geoLoc;
    var timeoutVal = 3000;
    var order_id = '<?= $vendor_order_id ?>';
         
    start_watching();
    
    function start_watching() {
        
        $('#tracking').show();
        
        if (navigator.geolocation) {
           geoLoc = navigator.geolocation;
           watchID = geoLoc.watchPosition(showPosition, errorHandler, {
               enableHighAccuracy: true, 
               timeout: timeoutVal, 
               maximumAge: 0 
           });
        } else {
           alert("Geolocation is not supported by this browser.");
        }
    }
    
    //save position to database 
    //update total_distance, total_commision 
    function showPosition(position) {
             
        $.post('index.php?path=shopper/order/savePosition&token=<?= $token ?>',
        {
            "order_id" : order_id,
            "latitude": position.coords.latitude,
            "longitude": position.coords.longitude
        },
        function(data){            
            var data = JSON.parse(data);
            
            if(data.total_distance) {
                $('#total_distance').html(data.total_distance+'km');
            }
            
            if(data.total_commision) {
                $('#total_commision').html(data.total_commision);
            }
        });
    }
    
    function stopWatch(){
        geoLoc.clearWatch(watchID);
    }
    
    function errorHandler(err) {
        if (err.code === 1) {
           alert("Error: Access is denied!");
        }else if( err.code === 2) {
           alert("Error: Position is unavailable!");
        }
    }    
    
    $('#btn-shipped').click(function(){
        $.post('index.php?path=shopper/order/orderShipped&token=<?= $token ?>',
        {
            "order_id" : order_id
        },
        function(data){  
            location = 'index.php?path=shopper/order/track_info&token=<?= $token ?>&vendor_order_id='+order_id;
        });
    });
    
</script>
