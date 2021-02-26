<div class="panel db mbm">
  <div class="panel-body">
    <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
    <r1><p class="icon"><i class="icon fa fa-user"></i></p>
    <h4 class="value"><span><?php echo $total; ?></span></h4>
    <p class="description">Customers Pending Approval</p></r1></a>
  </div>
</div>
<script>
$(document).ready(function(){
  $("r1").mouseover(function(){
    $("r1").css("color", "white");
  });
  $("r1").mouseout(function(){
    $("r1").css("color", "black");
  });
});
</script>