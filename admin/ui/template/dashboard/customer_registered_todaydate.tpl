<div class="panel db mbm">
  <div class="panel-body">
    <a id="href_total_customers_today" href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
    <r2today><p class="icon"><i class="icon fa fa-user"></i></p>
    <h4 class="value" id="total_customers_today"><span><?php echo $total; ?></span></h4>
    <p class="description">Customers Registered</p></r2today></a>
  </div>
</div>
<script>
$(document).ready(function(){
  $("r2today").mouseover(function(){
    $("r2today").css("color", "white");
  });
  $("r2today").mouseout(function(){
    $("r2today").css("color", "black");
  });
});
</script>