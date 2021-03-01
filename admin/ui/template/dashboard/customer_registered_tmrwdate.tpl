<div class="panel db mbm">
  <div class="panel-body">
    <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
    <r2tmrw><p class="icon"><i class="icon fa fa-user"></i></p>
    <h4 class="value"  id="total_customers_tomorrow"><span><?php echo $total; ?></span></h4>
    <p class="description">Customers Registered</p></r2tmrw></a>
  </div>
</div>
<script>
$(document).ready(function(){
  $("r2tmrw").mouseover(function(){
    $("r2tmrw").css("color", "white");
  });
  $("r2tmrw").mouseout(function(){
    $("r2tmrw").css("color", "black");
  });
});
</script>