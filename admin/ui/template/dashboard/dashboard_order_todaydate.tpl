<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rdt><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders_today"><span><?php echo $total; ?></span></h4>
        <p class="description">Orders</p></rdt></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rdt").mouseover(function(){
    $("rdt").css("color", "white");
  });
  $("rdt").mouseout(function(){
    $("rdt").css("color", "black");
  });
});
</script>