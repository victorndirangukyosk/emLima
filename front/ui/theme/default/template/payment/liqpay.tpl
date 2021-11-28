<form id="form-liqpay" action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="operation_xml" value="<?php echo $xml; ?>">
  <input type="hidden" name="signature" value="<?php echo $signature; ?>">
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>

<script>
    $('#form-liqpay').submit(fucntion(e){
        if (!saveAddress()) { 
            e.preventDefault();
            return false; 
        }else{
            return true;
        }
    });
</script>    
