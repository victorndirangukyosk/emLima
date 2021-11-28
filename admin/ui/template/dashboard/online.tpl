<div class="panel visit db mbm">
  <div class="panel-body">
  <a href="<?php echo $online_customers_url; ?>" style="color:black;text-decoration: none;">
    <r><p class="icon"><i class="icon fa fa-group"></i></p>
    <h4 class="value"><span><?php echo $total; ?></span></h4>
   <p class="description"> <?php echo $heading_title; ?></p></r></a>
  </div>
</div>

 
 
<script>
$(document).ready(function(){
  $("r").mouseover(function(){
    $("r").css("color", "white");
  });
  $("r").mouseout(function(){
    $("r").css("color", "black");
  });
});
</script>