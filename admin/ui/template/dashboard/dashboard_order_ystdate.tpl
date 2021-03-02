<div class="panel profit db mbm">
    <div class="panel-body">
        <a  id="href_total_orders_yst" href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rdd><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" ><span id="total_orders_yst"><?php echo $total; ?></span></h4>
        <p class="description">Orders</p></rdd></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rdd").mouseover(function(){
    $("rdd").css("color", "white");
  });
  $("rdd").mouseout(function(){
    $("rdd").css("color", "black");
  });
});
</script>