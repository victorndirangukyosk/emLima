<div class="panel visit db mbm">
  <div class="panel-body" id="pnlnew">
  <a style="color:black;text-decoration: none;" href="<?php echo $online_orders_url; ?>">
    <online><p class="icon"><i class="icon fa fa-shopping-cart"></i></p>
    <h4 class="value" id="total_online_orders" ><span><?php echo $total; ?></span></h4>
    
    <p class="description"> Online Orders
    <h8 class="value"><span>(From Jan 2021)</span></h8></p></online></a>
    
   
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