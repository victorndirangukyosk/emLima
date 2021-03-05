<div class="panel profit db mbm">
    <div class="panel-body">
        <a id="total_processing_orders_url" href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rq><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_processing_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Processing Orders</p></rq></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rq").mouseover(function(){
    $("rq").css("color", "white");
  });
  $("rq").mouseout(function(){
    $("rq").css("color", "black");
  });
});
</script>