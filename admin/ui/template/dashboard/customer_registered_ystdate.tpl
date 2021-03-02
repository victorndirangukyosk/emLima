<div class="panel db mbm">
  <div class="panel-body">
    <a id="href_total_customers_yesterday" href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
    <r2yst><p class="icon"><i class="icon fa fa-user"></i></p>
    <h4 class="value"  id="total_customers_yesterday"><span><?php echo $total; ?></span></h4>
    <p class="description">Customers Registered</p></r2yst></a>
  </div>
</div>
<script>
$(document).ready(function(){
  $("r2yst").mouseover(function(){
    $("r2yst").css("color", "white");
  });
  $("r2yst").mouseout(function(){
    $("r2yst").css("color", "black");
  });
});
</script>