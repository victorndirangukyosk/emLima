<div class="panel profit db mbm">
    <div class="panel-body">
      <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <rf><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
        <h4 class="value" id="total_orders"><span><?php echo $total; ?></span></h4>
        <p class="description">Fast Orders</p></rf></a>

    </div>
</div>
<script>
$(document).ready(function(){
  $("rf").mouseover(function(){
    $("rf").css("color", "white");
  });
  $("rf").mouseout(function(){
    $("rf").css("color", "black");
  });
});
</script>
