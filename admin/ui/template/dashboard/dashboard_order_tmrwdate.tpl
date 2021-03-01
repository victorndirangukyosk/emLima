<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rdtmrw><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders_tomorrow"><span><?php echo $total; ?></span></h4>
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