<div class="panel profit db mbm">
    <div class="panel-body">
        <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rp><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Processing Orders</p></rp></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rp").mouseover(function(){
    $("rp").css("color", "white");
  });
  $("rp").mouseout(function(){
    $("rp").css("color", "black");
  });
});
</script>