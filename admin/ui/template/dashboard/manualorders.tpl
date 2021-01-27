<div class="panel visit db mbm">
  <div class="panel-body" id="manualordersdiv">
  <a  id="manualordersa" style="color:black;text-decoration: none;" href="<?php echo $manual_orders_url; ?>">
   <manual> <p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
    <h4 class="value"><span><?php echo $total; ?></span></h4>    
   <p class="description"> Manual Orders
   <h8 class="value"><span>(From Jan 2021)</span></h8></p></manual></a>
  </div>
</div>
 
 
<script>
$(document).ready(function(){
  $("manual").mouseover(function(){
    $("manual").css("color", "white");
  });
  $("manual").mouseout(function(){
    $("manual").css("color", "black");
  });
});
</script>