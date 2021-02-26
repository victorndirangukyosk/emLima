<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <r><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Approval Pending Orders</p></r></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("r").mouseover(function(){
    $("r").css("color", "white");
  });
  $("r").mouseout(function(){
    $("r").css("color", "black");
  });
});
</script>