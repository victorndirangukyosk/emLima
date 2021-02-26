<div class="panel db mbm">
  <div class="panel-body">
    <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
    <r2><p class="icon"><i class="icon fa fa-user"></i></p>
    <h4 class="value"><span><?php echo $total; ?></span></h4>
    <p class="description">Customers Registered</p></r2></a>
  </div>
</div>
<script>
$(document).ready(function(){
  $("r2").mouseover(function(){
    $("r2").css("color", "white");
  });
  $("r2").mouseout(function(){
    $("r2").css("color", "black");
  });
});
</script>