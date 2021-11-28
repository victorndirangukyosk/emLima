<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    
    <div class="page-header">
    <div class="container-fluid">
        <div class="pull-right">
            <button value="1" class="btn btn-success" id="btn-payu-confirm" type="button">Pay By Payu</button>
        </div>
        
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
    
  <div class="container-fluid">    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>	
      </div>
      <div class="panel-body">  
          
          <form action="" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          
          <input type="hidden" name="package_id" value="<?= $package_id ?>" />
                 
          <div id="payu-content" class="payment-content">            
              
              <div class="form-group">
                  <label class="col-sm-2">Postcode</label>
                  <div class="col-sm-10">
                      <input type="text" name='postcode' class="form-control" />
                  </div>
              </div>
              
              <div class="form-group">
                  <label class="col-sm-2">Payment address 1</label>
                  <div class="col-sm-10">
                      <input type="text" name='payment_address_1' class="form-control" />
                  </div>
              </div>
              
              <div class="form-group">
                  <label class="col-sm-2">Payment address 2</label>
                  <div class="col-sm-10">
                      <input type="text" name='payment_address_2' class="form-control" />
                  </div>
              </div>
              
              <div class="form-group">
                  <label class="col-sm-2">Payment zone</label>
                  <div class="col-sm-10">
                      <input type="text" name='payment_zone' class="form-control" />
                  </div>
              </div>
              
              <div class="form-group">
                  <label class="col-sm-2">Payment city</label>
                  <div class="col-sm-10">
                      <input type="text" name='payment_city' class="form-control" />
                  </div>
              </div>    
          </div>          
      
          <input type="hidden" name="payu_action" value="<?= $payu_action ?>" />
          <input type="hidden" name="cod_action" value="<?= $cod_action ?>" />
          
      </form>
        
    </div>
  </div>
</div>
      </div>
<?php echo $footer ?>

<script>
    
    $('#btn-payu-confirm').click(function(){
        
        var errors = 0;
        $("#payu-content :input").map(function(){
             if( !$(this).val() ) {
                  $(this).css('border','1px solid red');
                  errors++;
            } else if ($(this).val()) {
                  $(this).css('border','');
            }   
        });        
        if(errors > 0){
            return false;
        }
        $('#form').attr('action', $('input[name="payu_action"]').val()).submit();
    });
    
    
</script>

<style>
.button {
  border: none;
  cursor: pointer;
  background: none repeat scroll 0 0 #003a88;
  border-radius: 10px;
  color: #fff;
  display: inline-block;
  padding: 5px 15px;
  text-decoration: none;
}
input {
    border: 1px solid #ccc;
}
</style>