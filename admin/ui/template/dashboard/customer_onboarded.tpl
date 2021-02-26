<div class="panel db mbm">
    <div class="panel-body">
       <a href="<?php echo $url; ?>" style="color:black;text-decoration: none;">
        <r3><p class="icon"><i class="icon fa fa-user"></i></p>
        <h4 class="value"><span><?php echo $total; ?></span></h4>
        <p class="description">Customers Onboarded</p></r3></a>
    </div>
</div>
<script>
$(document).ready(function(){
  $("r3").mouseover(function(){
    $("r3").css("color", "white");
  });
  $("r3").mouseout(function(){
    $("r3").css("color", "black");
  });
});
</script>