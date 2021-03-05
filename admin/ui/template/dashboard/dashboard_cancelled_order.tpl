<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rc><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_cancelled_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Cancelled Orders</p></rc></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rc").mouseover(function(){
    $("rc").css("color", "white");
  });
  $("rc").mouseout(function(){
    $("rc").css("color", "black");
  });
});
</script>