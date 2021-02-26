<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <ra><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Approval Pending Orders</p></ra></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("ra").mouseover(function(){
    $("ra").css("color", "white");
  });
  $("ra").mouseout(function(){
    $("ra").css("color", "black");
  });
});
</script>