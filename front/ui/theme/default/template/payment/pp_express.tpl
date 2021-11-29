<div class="buttons">
  <div class="text-center">
      <a id="button-pp-exp-confirm" href="<?php echo $button_continue_action; ?>" class="btn btn-primary">
          <?php echo $button_continue; ?>
      </a>
  </div>
</div>


<script type="text/javascript"><!--
$('#button-pp-exp-confirm').on('click', function() {
    
    if (!saveAddress()) { 
        return false; 
    }else{
        return true;
    }
});
//--></script> 
