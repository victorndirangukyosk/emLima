<div class="panel profit db mbm">
    <div class="panel-body">
        <a  id="href_total_orders_tomorrow" href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rdtmrw><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" ><span id="total_orders_tomorrow"><?php echo $total; ?></span></h4>
        <p class="description">Orders</p></rdtmrw></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rdtmrw").mouseover(function(){
    $("rdtmrw").css("color", "white");
  });
  $("rdtmrw").mouseout(function(){
    $("rdtmrw").css("color", "black");
  });
});
</script>