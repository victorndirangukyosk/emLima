<div class="panel visit db mbm">
  <div class="panel-body" id="pnlnew">
      <a id="total_online_orders_url" style="color:black;text-decoration: none;" href="<?php echo $online_orders_url; ?>">
    <online><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
    <h4 class="value" id="total_online_orders" ><span><?php echo $total; ?></span></h4>
    
    <p class="description"> Online Orders
   </p></online></a>
    
   
  </div>
</div>

<script>
$(document).ready(function(){
  $("online").mouseover(function(){
    $("online").css("color", "white");
  });
  $("online").mouseout(function(){
    $("online").css("color", "black");
  });
});
</script>