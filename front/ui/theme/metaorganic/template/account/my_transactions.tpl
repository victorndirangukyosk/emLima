<?php echo $header; ?>
                      <div class="container">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row">
  <div class="col-md-9">
	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#pending">Pending Payments</a></li>
	  <li><a data-toggle="tab" href="#successfull">Successfull Payments</a></li>
	  <li><a data-toggle="tab" href="#cancelled">Cancelled Payments</a></li>
	</ul>

		<div class="tab-content">
		  <div id="pending" class="tab-pane fade in active">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>Order Id </th>
					<th>Total Amount</th>
					<th>Order Date</th>
					<th>Action</th>
				  </tr>
				</thead>
				<tbody>
				 <?php if(count($pending_transactions)){?>
                  <?php foreach($pending_transactions as $transaction){?>
				  <tr>
					<td><?php echo $transaction['order_id'];?></td>
					<td><?php echo round($transaction['total'],2).' '.$transaction['currency_code'];?></td>
					<td><?php echo $transaction['date_added'];?></td>
					<td><a href="#">Pay Now</a></td>
				  </tr>
				  <?php } ?>
				 <?php } ?>
				</tbody>
			  </table>
		  </div>
		  <div id="successfull" class="tab-pane fade">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>Order Id </th>
					<th>Total Amount</th>
					<th>Order Date</th>
					<!--<th>Action</th>-->
				  </tr>
				</thead>
				<tbody>
				 <?php if(count($success_transactions)){?>
                  <?php foreach($success_transactions as $transaction){?>
				  <tr>
					<td><?php echo $transaction['order_id'];?></td>
					<td><?php echo round($transaction['total'],2).' '.$transaction['currency_code'];?></td>
					<td><?php echo $transaction['date_added'];?></td>
					<!--<td>Pay Now</td>-->
				  </tr>
				  <?php } ?>
				 <?php } ?>
				</tbody>
			  </table>
		  </div>
		  <div id="cancelled" class="tab-pane fade">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>Order Id </th>
					<th>Total Amount</th>
					<th>Order Date</th>
					<!--<th>Action</th>-->
				  </tr>
				</thead>
				<tbody>
				 <?php if(count($cancelled_transactions)){?>
                  <?php foreach($cancelled_transactions as $transaction){?>
				  <tr>
					<td><?php echo $transaction['order_id'];?></td>
					<td><?php echo round($transaction['total'],2).' '.$transaction['currency_code'];?></td>
					<td><?php echo $transaction['date_added'];?></td>
					<!--<td>Pay Now</td>-->
				  </tr>
				  <?php } ?>
				 <?php } ?>
				</tbody>
			  </table>
		  </div>
		</div>
   </div>
   </div>
   </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo $footer; ?>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);


    var page_category = 'my-account-page';
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                    clear = true;
               }
             if (clear) {
            clearInterval(intervalID);
         }
        },
        period);
        })(page_category);


</script>

<?php } ?>
<script type="text/javascript">

$('button[id^=\'button-custom-field\']').on('click', function() {
    var node = this;
    
    $('#form-upload').remove();
    
    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

    $('#form-upload input[name=\'file\']').trigger('click');
    
    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }
    
    timer = setInterval(function() {
        if ($('#form-upload input[name=\'file\']').val() != '') {
            clearInterval(timer);
            
            $.ajax({
                url: 'index.php?path=tool/upload',
                type: 'post',       
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,     
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');            
                },      
                success: function(json) {
                    $(node).parent().find('.text-danger').remove();
                    
                    if (json['error']) {
                        $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                    }
                                
                    if (json['success']) {
                        alert(json['success']);
                        
                        $(node).parent().find('input').attr('value', json['code']);
                    }
                },          
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});
//--></script> 
<!--  jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    
<script type="text/javascript">

    /*jQuery(function($){
        console.log("signup mask");
       $("#tel").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    });*/
    /*jQuery(function($){
        console.log("mask");
       $("#tel").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    jQuery(function($) {
        console.log("tax mask");
       $("#tax_number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    

    $('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });    
</script> 
                 

    <?php if($redirect_coming) { ?>
      <script type="text/javascript">
        $('#save-button').click();
      </script>
      
    <?php } ?>
	<style>
	.nav-tabs>li {
		width: 33.3%;
    }
	</style>
</body>
</html>