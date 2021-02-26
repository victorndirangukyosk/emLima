<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rd><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Received Orders</p></rd></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rd").mouseover(function(){
    $("rd").css("color", "white");
  });
  $("rd").mouseout(function(){
    $("rd").css("color", "black");
  });
});
</script>